<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FriendsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return view('friends', [
            'friends' => $user->friends()->orderBy('name')->get(),
            'receivedRequests' => $user->friendRequestsReceived()
                ->where('status', 'pending')
                ->with('sender')
                ->latest()
                ->get(),
            'sentRequests' => $user->friendRequestsSent()
                ->where('status', 'pending')
                ->with('receiver')
                ->latest()
                ->get(),
        ]);
    }
}
