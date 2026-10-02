<?php

use App\Models\Post;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Post $post;

    #[Locked]
    public int $likesCount = 0;

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->likesCount = (int) $post->numOfLikes;
    }

    #[On('likes-updated.{post.id}')]
    public function refreshCount(): void
    {
        $this->likesCount = $this->post->likes()->count();
    }
};
?>

<span>{{ $likesCount }} {{ Str::plural('like', $likesCount) }}</span>