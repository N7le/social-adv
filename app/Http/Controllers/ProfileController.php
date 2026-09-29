<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __invoke(Request $request, User $username): View
    {
        $posts = $username->posts()->latest()->get();

        return view('profile', [
            'profileUser' => $username,
            'posts' => $posts,
            'isOwner' => $request->user()?->is($username) ?? false,
        ]);
    }
}
