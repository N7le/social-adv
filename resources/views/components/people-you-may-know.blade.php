<section class="card mb-3" aria-labelledby="suggestionsHeading">
    <div class="card-body">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h2 class="h6 mb-0" id="suggestionsHeading">People you may know</h2>
            <a class="small link-muted" href="{{ route('friends') }}">See all</a>
        </div>
        <ul class="list-unstyled mb-0 vstack gap-3">
            @foreach ($suggestions as $suggestion)
            <li class="d-flex align-items-center gap-2">
                <img class="avatar" src="{{ $suggestion['user']->profile_photo ? asset('storage/'.$suggestion['user']->profile_photo) : asset('assets/img/avatar-4.svg') }}" alt="">
                <div class="flex-grow-1 min-w-0">
                    <p class="fw-semibold small mb-0 text-truncate">{{ $suggestion['user']->name }}</p>
                    <p class="mutuals mb-0 text-truncate">
                        {{ $suggestion['mutualFriendsCount'] }} mutual {{ $suggestion['mutualFriendsCount'] === 1 ? 'friend' : 'friends' }}
                    </p>
                </div>
                <livewire:add-friend-simple :target="$suggestion['user']" :wire:key="'suggestion-'.$suggestion['user']->id" />
            </li>
            @endforeach
        </ul>
    </div>
</section>