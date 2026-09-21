<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilePictureController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'avatar' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
                'dimensions:min_width=320,min_height=320',
            ],
        ]);

        $user = $request->user();
        $previousProfilePhoto = $user->profile_photo;
        $profilePhoto = $validated['avatar']->store('profile-photos', 'public');

        $user->update(['profile_photo' => $profilePhoto]);

        if ($previousProfilePhoto !== null) {
            Storage::disk('public')->delete($previousProfilePhoto);
        }

        return back()->with('status', 'Profile picture updated.');
    }
}
