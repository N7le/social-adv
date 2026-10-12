@props(['notification', 'actor'])

@php
    $isUnread = is_null($notification->read_at);
    [$icon, $iconBg] = match ($notification->data['action'] ?? null) {
        'liked' => ['bi-hand-thumbs-up-fill', 'bg-primary'],
        'commented' => ['bi-chat-fill', 'bg-success'],
        default => ['bi-bell-fill', 'bg-secondary'],
    };
    $actionText = match ($notification->data['action'] ?? null) {
        'liked' => 'liked your post.',
        'commented' => 'commented on your post.',
        default => null,
    };
@endphp

<li @class(['notification-item position-relative align-items-center', 'notification-item--unread' => $isUnread])>
    <span class="position-relative flex-shrink-0">
        <img class="avatar avatar-lg" src="{{ $actor?->profile_photo ? asset('storage/'.$actor->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="">
        <span class="notification-icon {{ $iconBg }}"><i class="bi {{ $icon }}" aria-hidden="true"></i></span>
    </span>
    <span class="flex-grow-1 min-w-0">
        <span class="d-block">
            @if ($actor && $actionText)
                <a class="fw-semibold text-body text-decoration-none" href="{{ route('profile.show', ['username' => $actor->username]) }}">{{ $actor->name }}</a>
                {{ $actionText }}
            @else
                {{ $notification->data['message'] ?? 'You have a new notification.' }}
            @endif
        </span>
        <small @class(['text-brand fw-semibold' => $isUnread, 'text-secondary' => ! $isUnread])>
            <time datetime="{{ $notification->created_at->toIso8601String() }}">{{ $notification->created_at->diffForHumans() }}</time>
        </small>
    </span>
    @if ($isUnread)
        <span class="unread-dot flex-shrink-0 align-self-center" aria-hidden="true"></span>
        <span class="visually-hidden">Unread</span>
    @endif
</li>
