    @props(['activePage' => 'home'])

      <!-- ========== Left navigation rail ========== -->
      <div class="col-lg-4 col-xl-3 d-none d-lg-block">
        <div class="sticky-rail pb-3">

          @auth
          <a class="d-flex align-items-center gap-2 text-decoration-none text-body p-2 rounded hover-surface" href="{{ route('profile') }}">
            <img class="avatar avatar-lg" src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="">
            <span class="min-w-0">
              <span class="d-block fw-semibold text-truncate">{{ auth()->user()->name }}</span>
              <small class="text-secondary">{{ '@'.auth()->user()->username }}</small>
            </span>
          </a>

          <nav class="nav flex-column side-nav mt-2" aria-label="Sections">
            <a @class(['nav-link', 'active' => $activePage === 'home']) href="{{ route('home') }}" @if ($activePage === 'home') aria-current="page" @endif><i class="bi bi-house-door" aria-hidden="true"></i><span>Feed</span></a>
            <a @class(['nav-link', 'active' => $activePage === 'profile']) href="{{ route('profile') }}" @if ($activePage === 'profile') aria-current="page" @endif><i class="bi bi-person" aria-hidden="true"></i><span>Profile</span></a>
            <a @class(['nav-link', 'active' => $activePage === 'friends']) href="{{ route('friends') }}" @if ($activePage === 'friends') aria-current="page" @endif><i class="bi bi-people" aria-hidden="true"></i><span>Friends</span><span class="badge rounded-pill text-bg-danger ms-auto">2<span class="visually-hidden"> new</span></span></a>
            <a @class(['nav-link', 'active' => $activePage === 'messages']) href="{{ route('messages') }}" @if ($activePage === 'messages') aria-current="page" @endif><i class="bi bi-chat-dots" aria-hidden="true"></i><span>Messages</span><span class="badge rounded-pill text-bg-danger ms-auto">2<span class="visually-hidden"> new</span></span></a>
            <a @class(['nav-link', 'active' => $activePage === 'notifications']) href="{{ route('notifications') }}" @if ($activePage === 'notifications') aria-current="page" @endif><i class="bi bi-bell" aria-hidden="true"></i><span>Notifications</span><span class="badge rounded-pill text-bg-danger ms-auto">3<span class="visually-hidden"> new</span></span></a>
            <a class="nav-link" href="{{ route('profile').'#photos' }}"><i class="bi bi-images" aria-hidden="true"></i><span>Photos</span></a>
            <a class="nav-link" href="#"><i class="bi bi-bookmark" aria-hidden="true"></i><span>Saved</span></a>
            <a @class(['nav-link', 'active' => $activePage === 'settings']) href="{{ route('settings') }}" @if ($activePage === 'settings') aria-current="page" @endif><i class="bi bi-gear" aria-hidden="true"></i><span>Settings</span></a>
          </nav>

          <button class="btn btn-primary w-100 mt-3" type="button" data-bs-toggle="modal" data-bs-target="#createPostModal">
            <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Create post
          </button>
          @else
          <div class="vstack gap-2">
            <p class="small text-secondary mb-1">Join YouBee to post and connect with people.</p>
            <a class="btn btn-primary" href="{{ route('login') }}">Log in</a>
            <a class="btn btn-light" href="{{ route('register') }}">Create account</a>
          </div>
          @endauth

          <hr class="my-3">

          <x-footer />
        </div>
      </div>
