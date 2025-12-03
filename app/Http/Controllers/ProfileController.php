<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        if ($request->hasFile('profile_image')) {
            // Delete old image if it exists
            if ($request->user()->photo && Storage::disk('profile_photo')->exists($request->user()->photo)) {
                Storage::disk('profile_photo')->delete($request->user()->photo);
            }

            $extension = $request->file('profile_image')->getClientOriginalExtension();
            $fileName = 'profile' . $request->user()->id . '.' . $extension;

            // Store new image
            // Store file in custom disk (will go to storage/app/public/profile_photo)
            Storage::disk('profile_photo')->putFileAs('', $request->file('profile_image'), $fileName);

            $request->user()->photo = $fileName;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->is_blocked = true;
        $user->save();
        //TODO delete the user:
//        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
