@extends('layouts.app')

@section('title', $profileUser->name.' · YouBee Social')
@section('description', 'View '.$profileUser->name.'\'s profile and posts.')
@section('activePage', 'profile')

@section('content')
  <main class="col-12 col-lg-8 col-xl-9" id="main">
    <section class="card mb-3 overflow-hidden" aria-labelledby="profileName">
      <div class="position-relative">
        <img class="cover" src="{{ $profileUser->cover_photo ? asset('storage/'.$profileUser->cover_photo) : asset('assets/img/cover.svg') }}" alt="{{ $profileUser->name }}'s cover photo">
        @if ($isOwner)
          <button class="btn btn-light btn-sm position-absolute bottom-0 end-0 m-3 shadow-sm"
                  type="button" data-bs-toggle="modal" data-bs-target="#changeCoverModal">
            <i class="bi bi-camera me-1" aria-hidden="true"></i>Edit cover
          </button>
        @endif
      </div>

      <div class="card-body">
        <div class="d-flex flex-column flex-md-row align-items-center align-items-md-end gap-3 profile-identity">
          <div class="profile-avatar-wrap position-relative">
            <img class="avatar avatar-2xl profile-avatar" src="{{ $profileUser->profile_photo ? asset('storage/'.$profileUser->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="{{ $profileUser->name }}">
            @if ($isOwner)
              <button class="btn btn-light btn-sm rounded-circle position-absolute bottom-0 end-0 shadow-sm"
                      type="button" data-bs-toggle="modal" data-bs-target="#changeAvatarModal"
                      aria-label="Change profile picture" title="Change profile picture">
                <i class="bi bi-camera-fill" aria-hidden="true"></i>
              </button>
            @endif
          </div>

          <div class="flex-grow-1 text-center text-md-start min-w-0">
            <h1 class="h3 mb-1" id="profileName">{{ $profileUser->name }}</h1>
            <p class="text-secondary mb-2">{{ '@'.$profileUser->username }}@if ($profileUser->work) · {{ $profileUser->work }}@endif</p>
            <span class="stat-block"><strong>{{ $posts->count() }}</strong><small class="text-secondary">Posts</small></span>
          </div>
          @auth
          @if ($isOwner)
            <a class="btn btn-primary" href="{{ route('settings') }}">
              <i class="bi bi-pencil me-1" aria-hidden="true"></i>Edit Profile
            </a>
          @else
            <livewire:add-friend :target="$profileUser" />
          @endif
          @endauth
        </div>
      </div>
    </section>

    <div class="row g-3">
      <div class="col-12 col-lg-5">
        <section class="card mb-3" aria-labelledby="introHeading">
          <div class="card-body">
            <h2 class="h6 mb-3" id="introHeading">Intro</h2>
            @if ($profileUser->bio)
              <p class="text-center text-balance">{{ $profileUser->bio }}</p>
            @endif
            <ul class="list-unstyled vstack gap-2 small mb-3">
              @if ($profileUser->work)
                <li><i class="bi bi-briefcase text-secondary me-2" aria-hidden="true"></i>{{ $profileUser->work }}</li>
              @endif
              @if ($profileUser->education)
                <li><i class="bi bi-mortarboard text-secondary me-2" aria-hidden="true"></i>Studied at <strong>{{ $profileUser->education }}</strong></li>
              @endif
              @if ($profileUser->city)
                <li><i class="bi bi-geo-alt text-secondary me-2" aria-hidden="true"></i>Lives in <strong>{{ $profileUser->city }}</strong></li>
              @endif
              @if ($profileUser->website)
                <li><i class="bi bi-link-45deg text-secondary me-2" aria-hidden="true"></i><a class="link-muted" href="{{ $profileUser->website }}" rel="noopener noreferrer">{{ $profileUser->website }}</a></li>
              @endif
              <li><i class="bi bi-calendar3 text-secondary me-2" aria-hidden="true"></i>Joined <time datetime="{{ $profileUser->created_at->format('Y-m') }}">{{ $profileUser->created_at->format('F Y') }}</time></li>
            </ul>
            @if ($isOwner)
              <a class="btn btn-light w-100" href="{{ route('settings') }}">Edit details</a>
            @endif
          </div>
        </section>
      </div>

      <div class="col-12 col-lg-7">
        @if ($isOwner)
          <section class="card mb-3" aria-labelledby="profileComposerHeading">
            <div class="card-body">
              <h2 class="visually-hidden" id="profileComposerHeading">Create a post</h2>
              <div class="d-flex align-items-center gap-2">
                <img class="avatar" src="{{ $profileUser->profile_photo ? asset('storage/'.$profileUser->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="">
                <button class="composer-trigger" type="button" data-bs-toggle="modal" data-bs-target="#createPostModal">
                  What's on your mind, {{ $profileUser->name }}?
                </button>
              </div>
            </div>
          </section>
        @endif

        @forelse ($posts as $post)
          <x-post :post="$post" :author="$profileUser" />
        @empty
          <section class="card">
            <div class="card-body text-center text-secondary py-5">
              <i class="bi bi-file-post fs-2 d-block mb-2" aria-hidden="true"></i>
              <p class="mb-0">No posts yet.</p>
            </div>
          </section>
        @endforelse
      </div>
    </div>
  </main>
@endsection

@if ($isOwner)
  @push('modals')
    @include('partials.profile-avatar-modal')
    @include('partials.profile-cover-modal')
  @endpush
@endif