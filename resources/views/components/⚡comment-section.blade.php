<?php

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Post $post;

    public string $body = '';

    public ?int $replyToId = null;

    public function mount(int $postId): void
    {
        $this->post = Post::findOrFail($postId);
    }

    /**
     * @return array{comments: Collection<int, Comment>, count: int}
     */
    #[Computed]
    public function thread(): array
    {
        $comments = $this->post->comments()->with('user')->orderBy('created_at')->orderBy('id')->get();
        $commentsByParent = $comments->groupBy(fn (Comment $comment): int => $comment->comment_id ?? 0);

        return [
            'comments' => $this->buildThread($commentsByParent, 0),
            'count' => $comments->count(),
        ];
    }

    #[Computed]
    public function canComment(): bool
    {
        return Auth::check() && Gate::allows('comment', $this->post);
    }

    public function startReply(int $commentId): void
    {
        Gate::authorize('comment', $this->post);
        $this->post->comments()->findOrFail($commentId);
        $this->replyToId = $commentId;
        $this->body = '';
    }

    public function cancelReply(): void
    {
        $this->replyToId = null;
        $this->body = '';
    }

    public function createComment(): void
    {
        Gate::authorize('comment', $this->post);

        $validated = $this->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);
        $parentComment = $this->replyToId === null
            ? null
            : $this->post->comments()->findOrFail($this->replyToId);

        $this->post->comments()->create([
            'user_id' => Auth::id(),
            'comment_id' => $parentComment?->id,
            'body' => $validated['body'],
        ]);

        $this->body = '';
        $this->replyToId = null;
        unset($this->thread);
    }

    private function buildThread(Collection $commentsByParent, int $parentId): Collection
    {
        return $commentsByParent->get($parentId, collect())->map(function (Comment $comment) use ($commentsByParent): Comment {
            $comment->setRelation('threadReplies', $this->buildThread($commentsByParent, $comment->id));

            return $comment;
        });
    }
};
?>

<section id="comments-post-{{ $post->id }}" aria-label="Comments">
    <h3 class="small fw-semibold mb-0">{{ $this->thread['count'] }} {{ $this->thread['count'] === 1 ? 'comment' : 'comments' }}</h3>

    @forelse ($this->thread['comments'] as $comment)
        @include('partials.comment-thread', ['comments' => collect([$comment]), 'depth' => 0])
    @empty
        <p class="small text-secondary mb-0 mt-2">No comments yet.</p>
    @endforelse

    @can('comment', $this->post)
        @php
            $replyTarget = $replyToId === null ? null : $post->comments()->with('user')->find($replyToId);
        @endphp
        <form class="d-flex align-items-start gap-2 mt-3" wire:submit="createComment">
            <img class="avatar avatar-sm" src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="">
            <div class="flex-grow-1 min-w-0">
                @if ($replyTarget)
                    <div class="small text-secondary mb-1">
                        Replying to {{ $replyTarget->user->name }}
                        <button class="btn btn-link btn-sm p-0 ms-1" type="button" wire:click="cancelReply">Cancel</button>
                    </div>
                @endif
                <label class="visually-hidden" for="comment-post-{{ $post->id }}">Write a comment</label>
                <textarea
                    class="form-control rounded-3"
                    id="comment-post-{{ $post->id }}"
                    wire:model="body"
                    maxlength="2000"
                    rows="2"
                    placeholder="{{ $replyTarget ? 'Write a reply...' : 'Write a comment...' }}"
                    aria-describedby="comment-error-{{ $post->id }}"
                ></textarea>
                @error('body')
                    <p class="small text-danger mt-1 mb-0" id="comment-error-{{ $post->id }}">{{ $message }}</p>
                @enderror
            </div>
            <button class="btn btn-primary rounded-circle btn-icon flex-shrink-0" type="submit" aria-label="Send comment" wire:loading.attr="disabled" wire:target="createComment">
                <i class="bi bi-send" aria-hidden="true"></i>
            </button>
        </form>
    @endif
</section>