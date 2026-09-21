<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoverPhotoController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cover' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ]);

        $user = $request->user();
        $previousCoverPhoto = $user->cover_photo;
        $coverPhoto = $validated['cover']->store('cover-photos', 'public');

        $user->update(['cover_photo' => $coverPhoto]);

        if ($previousCoverPhoto !== null) {
            Storage::disk('public')->delete($previousCoverPhoto);
        }

        return back()->with('status', 'Cover photo updated.');
    }
}
