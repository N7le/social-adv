<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProfileController extends Controller
{
    public function __invoke(Request $request, User $username): View
    {
        $posts = $username->posts()
            ->latest()
            ->get()
            ->filter(fn ($post): bool => Gate::allows('view', $post));

        return view('profile', [
            'profileUser' => $username,
            'posts' => $posts,
            'isOwner' => $request->user()?->is($username) ?? false,
        ]);
    }
}
