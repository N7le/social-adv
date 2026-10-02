<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class MainController extends Controller
{
    public function __invoke(): View
    {
        $posts = Post::with('user')
            ->latest()
            ->get()
            ->filter(fn (Post $post): bool => Gate::allows('view', $post));

        return view('feed', ['posts' => $posts]);
    }
}
