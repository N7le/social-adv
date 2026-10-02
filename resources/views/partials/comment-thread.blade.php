@foreach ($comments as $comment)
    <article class="d-flex align-items-start gap-2 mt-3" @if ($depth > 0) style="margin-left: {{ min($depth * 1.25, 5) }}rem" @endif>
        <a href="{{ route('profile.show', ['username' => $comment->user->username]) }}" class="flex-shrink-0">
            <img class="avatar avatar-sm" src="{{ $comment->user->profile_photo ? asset('storage/'.$comment->user->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="{{ $comment->user->name }}">
        </a>
        <div class="min-w-0 flex-grow-1">
            <div class="d-inline-block bg-light rounded-3 px-3 py-2 text-break">
                <a class="small fw-semibold text-body text-decoration-none" href="{{ route('profile.show', ['username' => $comment->user->username]) }}">{{ $comment->user->name }}</a>
                <p class="mb-0 text-break" style="white-space: pre-wrap">{{ $comment->body }}</p>
            </div>
            <div class="d-flex align-items-center gap-2 ms-2 mt-1">
                <time class="small text-secondary" datetime="{{ $comment->created_at->toIso8601String() }}">{{ $comment->created_at->diffForHumans() }}</time>
                @if ($this->canComment)
                    <button class="btn btn-link btn-sm p-0 text-decoration-none" type="button" wire:click="startReply({{ $comment->id }})">Reply</button>
                @endif
            </div>
            @if ($comment->threadReplies->isNotEmpty())
                @include('partials.comment-thread', ['comments' => $comment->threadReplies, 'depth' => $depth + 1])
            @endif
        </div>
    </article>
@endforeach