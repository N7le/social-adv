@props(['post', 'author' => $post->user])

<article id="post-{{ $post->id }}" {{ $attributes->merge(['class' => 'card mb-3']) }}>
  <h2 class="visually-hidden">Post by {{ $author->name }}</h2>
  <div class="card-body pb-2">
    <header class="d-flex align-items-start gap-2 mb-2">
      <a href="{{ route('profile.show', ['username' => $author->username]) }}">
        <img class="avatar" src="{{ $author->profile_photo ? asset('storage/'.$author->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="{{ $author->name }}">
      </a>
      <div class="flex-grow-1 min-w-0">
        <p class="mb-0">
          <a class="fw-semibold text-body text-decoration-none" href="{{ route('profile.show', ['username' => $author->username]) }}">{{ $author->name }}</a>
        </p>
        <p class="small text-secondary mb-0">
          <time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->diffForHumans() }}</time> ·
          @if ($post->privacy === 'private')
            <i class="bi bi-people-fill" aria-hidden="true"></i> Friends
          @else
            <i class="bi bi-globe-americas" aria-hidden="true"></i> Public
          @endif
        </p>
      </div>
    </header>
    @if ($post->body)
      <p class="mb-0 text-break" style="white-space: pre-wrap">{{ $post->body }}</p>
    @endif
  </div>

  @if ($post->media_path)
    @if (str_starts_with($post->media_type ?? '', 'video/'))
      <video class="post-photo w-100" controls preload="metadata">
        <source src="{{ url('/file/'.$post->media_path) }}" type="{{ $post->media_type }}">
      </video>
    @else
      <div class="post-photo">
        <img src="{{ url('/file/'.$post->media_path) }}" alt="Image shared by {{ $author->name }}" loading="lazy">
      </div>
    @endif
  @endif

  <div class="card-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 small text-secondary">
      <livewire:like-count :post="$post" :key="'like-count-post-'.$post->id" />
      <livewire:share-count :post="$post" :key="'share-count-post-'.$post->id" />
    </div>
    @auth
      <hr class="my-2">
      <div class="d-flex post-actions" role="group" aria-label="Post actions">
        <livewire:like-button :post="$post" :key="'like-post-'.$post->id" />
          <a class="btn flex-fill" href="#comments-post-{{ $post->id }}"><i class="bi bi-chat me-1" aria-hidden="true"></i>Comment</a>
        @can('share', $post)
          <livewire:share-button :post="$post" :key="'share-post-'.$post->id" />
        @endcan
      </div>
    @endauth
    <hr class="my-2">
      <livewire:comment-section :post-id="$post->id" :key="'comments-post-'.$post->id" />
  </div>
</article>