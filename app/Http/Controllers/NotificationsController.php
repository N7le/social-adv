<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $filter = $request->query('filter');

        $notifications = $user->notifications()
            ->where('type', 'not like', 'friend%')
            ->when($filter === 'unread', fn ($query) => $query->whereNull('read_at'))
            ->latest()
            ->simplePaginate(15)
            ->withQueryString();

        $actors = User::whereIn(
            'id',
            $notifications->getCollection()->pluck('data.actor_id')->filter()->unique()
        )->get()->keyBy('id');

        $unreadCount = $user->unreadNotifications()
            ->where('type', 'not like', 'friend%')
            ->count();

        return view('notifications', [
            'notifications' => $notifications,
            'actors' => $actors,
            'unreadCount' => $unreadCount,
            'filter' => $filter,
        ]);
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return redirect()->route('notifications');
    }
}
