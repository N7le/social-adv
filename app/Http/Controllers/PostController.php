<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['nullable', 'string', 'max:5000', 'required_without:media'],
            'privacy' => ['required', 'in:public,private'],
            'media' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,gif,webp,mp4,mov,webm',
                'max:51200',
                'required_without:body',
            ],
        ]);

        $mediaPath = null;
        $mediaType = null;

        if ($request->hasFile('media')) {
            $mediaPath = $validated['media']->store('posts', 'local');
            $mediaType = $validated['media']->getMimeType() ?: null;
        }

        $request->user()->posts()->create([
            'body' => $validated['body'] ?? null,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
            'privacy' => $validated['privacy'],
        ]);

        return back()->with('status', 'Post created.');
    }
}
