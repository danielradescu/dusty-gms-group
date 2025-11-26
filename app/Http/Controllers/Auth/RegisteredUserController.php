<?php

namespace App\Http\Controllers\Auth;

use App\Enums\JoinRequestStatus;
use App\Http\Controllers\Controller;
use App\Models\JoinRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
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

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $token = $request->input('invitation_token');
        $joinRequest = null;

        if ($token) {
            $joinRequest = \App\Models\JoinRequest::where('invitation_token', $token)
                ->where('status', \App\Enums\JoinRequestStatus::APPROVED)
                ->first();
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:'.User::class,
                function($attribute, $value, $fail) use ($request, $joinRequest) {
                    if (! JoinRequest::where('email', $value)->where('status', JoinRequestStatus::APPROVED)->exists()) {
                        // 1️⃣ If registration has a token → skip email approval check
                        if ($joinRequest) {
                            return;
                        }

                        // 2️⃣ Otherwise, check if this email has an approved join request
                        $approved = \App\Models\JoinRequest::query()
                            ->where('email', $value)
                            ->where('status', \App\Enums\JoinRequestStatus::APPROVED)
                            ->exists();

                        if (! $approved) {
                            $fail("Your email address is not yet approved to register. Please submit a join request first.");
                        }
                    }
                }],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (! $joinRequest) {
            $joinRequest = \App\Models\JoinRequest::where('email', $request->email)
                ->where('status', \App\Enums\JoinRequestStatus::APPROVED)
                ->first();
        }

        // Double safety check
        if (! $joinRequest) {
            abort(403, 'No approved join request found for this registration.');
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->reviewed_by = $joinRequest->reviewed_by;
        $user->save();

        // Mark the JoinRequest as registered
        if ($joinRequest) {
            $joinRequest->status = \App\Enums\JoinRequestStatus::REGISTERED;
            $joinRequest->invitation_used_at = now();
            $joinRequest->invitation_token = null; //invalidate immediately
            $joinRequest->save();
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
