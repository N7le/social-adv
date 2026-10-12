@props(['friendRequest'])

@php
    $requester = $friendRequest->sender_id === auth()->id() ? $friendRequest->receiver : $friendRequest->sender;
@endphp

<li {{ $attributes->merge(['class' => 'd-flex flex-wrap align-items-center gap-3 p-2 rounded hover-surface']) }}>
    <a href="{{ route('profile.show', ['username' => $requester->username]) }}">
        <img class="avatar avatar-xl" src="{{ $requester->profile_photo ? asset('storage/'.$requester->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="{{ $requester->name }}">
    </a>
    <div class="flex-grow-1 min-w-0">
        <a class="fw-semibold text-body text-decoration-none d-block text-truncate" href="{{ route('profile.show', ['username' => $requester->username]) }}">{{ $requester->name }}</a>
        <p class="mutuals mb-0">{{ '@'.$requester->username }} · Sent <time datetime="{{ $friendRequest->created_at->toIso8601String() }}">{{ $friendRequest->created_at->diffForHumans() }}</time></p>
    </div>
    <div class="d-flex gap-2 ms-auto">
        <livewire:add-friend :target="$requester" :wire:key="'friend-request-'.$friendRequest->id" />
    </div>
</li>
