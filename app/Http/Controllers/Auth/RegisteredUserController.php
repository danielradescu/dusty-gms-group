<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function createNormal(Request $request): View
    {
        $token = $request->query('invitation_token');

        $joinRequest = null;
        if ($token) {
            $joinRequest = \App\Models\JoinRequest::where('invitation_token', $token)
                ->where('status', \App\Enums\JoinRequestStatus::APPROVED)
                ->first();
        }

        return view('auth.register', ['joinRequest' => $joinRequest]);
    }

    public function createSimple(Request $request): View
    {
        $token = $request->query('invitation_token');

        $joinRequest = null;
        if ($token) {
            $joinRequest = \App\Models\JoinRequest::where('invitation_token', $token)
                ->where('status', \App\Enums\JoinRequestStatus::APPROVED)
                ->first();
        }

        return view('auth.simple_register', ['joinRequest' => $joinRequest]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $token = $request->input('invitation_token');
        $email = $request->input('email');
        $joinRequestByToken = null;
        $joinRequestByEmail = null;



        $validationNameAndEmail = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:'.User::class,
            ],
        ];

        $validationRulesAll = array_merge($validationNameAndEmail, [
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($token) {
            //If registration has a token → skip email approval check
            $joinRequest = \App\Models\JoinRequest::where('invitation_token', $token)
                ->where('status', \App\Enums\JoinRequestStatus::APPROVED)
                ->whereNull('invitation_used_at')
                ->first();
        }

        if (! $joinRequest) {
            return redirect()->route('public-join-create')->with('error', 'Your email address is not yet approved to register. Please submit a join request first.');
        }

        $emailFromRegistration = false;

        if ($request->has('email')) {
            //normal registration validate all normally:
            $request->validate($validationRulesAll);

        } else {
            //simple registration
            $emailFromRegistration = true;
            //insert the values from join request, initially we only request the password
            $request->merge([
                'name' => $joinRequest->name,
                'email' => $joinRequest->email,
                'phone_number' => $joinRequest->phone,
            ]);


            $validator = Validator::make($request->all(), $validationNameAndEmail);
            //if i have problems with the name or email, go to normal registration
            if ($validator->fails()) {
                return redirect()
                    ->route('normal_register')
                    ->withErrors($validator)
                    ->withInput();
            }

            //then validate all at once normally:
            $request->validate($validationRulesAll);
        }


        try {
            $user = null;

            DB::transaction(function () use ($request, $emailFromRegistration, &$joinRequest, &$user) {

                // Double safety check, hope validator respond first
                if (! $joinRequest ) {
                    abort(403, 'No approved join request found for this registration.');
                }

                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->reviewed_by = $joinRequest->reviewed_by;
                if ($emailFromRegistration) {
                    //i have email confirmation
                    $user->email_verified_at = now();
                }
                $user->save();

                // Mark the JoinRequest as registered
                $joinRequest->status = \App\Enums\JoinRequestStatus::REGISTERED;
                $joinRequest->invitation_used_at = now();
                $joinRequest->invitation_token = null; //invalidate immediately
                $joinRequest->save();

                //set initiator for rewards:
                if ($joinRequest->initiated_by) {
                    $user->invited_by = $joinRequest->initiated_by;
                    $user->save();
                }
            });

            event(new Registered($user));
            Auth::login($user);

            return redirect(route('dashboard', absolute: false));
        } catch (\Throwable $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'email' => $request->email,
            ]);

            return redirect()->back()->with('error', 'Error registering, try again later or contact us if the problem persists at: ' . config('app.contact_email'));
        }

    }
}
