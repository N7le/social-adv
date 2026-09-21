@props(['activePage' => 'home'])

  <!-- ===================== Top bar ===================== -->
  <header class="app-header fixed-top">
    <nav class="container-xxl h-100 d-flex align-items-center gap-2 gap-lg-3" aria-label="Primary">

      <button class="icon-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#navDrawer"
              aria-controls="navDrawer" aria-label="Open navigation menu">
        <i class="bi bi-list" aria-hidden="true"></i>
      </button>

      <a class="app-brand d-inline-flex align-items-center gap-2 text-decoration-none" href="{{ route('home') }}">
        <img src="{{ asset('assets/img/logo.svg') }}" alt="" width="34" height="34">
        <span class="d-none d-sm-inline">YouBee</span>
      </a>

      <form class="header-search flex-grow-1 d-none d-md-block" role="search" action="#" method="get">
        <label class="visually-hidden" for="globalSearch">Search YouBee</label>
        <div class="input-group">
          <span class="input-group-text" id="globalSearchIcon"><i class="bi bi-search" aria-hidden="true"></i></span>
          <input type="search" class="form-control" id="globalSearch" name="q"
                 placeholder="Search people and posts" aria-describedby="globalSearchIcon">
        </div>
      </form>

      <div class="d-flex align-items-center gap-1 gap-sm-2 ms-auto">
        <button class="icon-btn d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#searchDrawer"
                aria-controls="searchDrawer" aria-label="Search"><i class="bi bi-search" aria-hidden="true"></i></button>
        <a @class(['icon-btn d-none d-lg-inline-flex', 'active' => $activePage === 'home']) href="{{ route('home') }}" title="Feed" aria-label="Feed" @if ($activePage === 'home') aria-current="page" @endif><i class="bi bi-house-door" aria-hidden="true"></i></a>
        @auth
        <a @class(['icon-btn d-none d-lg-inline-flex', 'active' => $activePage === 'friends']) href="{{ route('friends') }}" title="Friends" aria-label="Friends, 2 requests" @if ($activePage === 'friends') aria-current="page" @endif>
          <i class="bi bi-people" aria-hidden="true"></i><span class="icon-btn__badge">2</span>
        </a>
        <a @class(['icon-btn', 'active' => $activePage === 'messages']) href="{{ route('messages') }}" title="Messages" aria-label="Messages, 2 unread" @if ($activePage === 'messages') aria-current="page" @endif>
          <i class="bi bi-chat-dots" aria-hidden="true"></i><span class="icon-btn__badge">2</span>
        </a>
        <div class="dropdown">
          <button class="icon-btn" type="button" data-bs-toggle="dropdown" data-bs-display="static"
                  data-bs-auto-close="outside" aria-expanded="false" aria-label="Notifications, 3 unread">
            <i class="bi bi-bell" aria-hidden="true"></i><span class="icon-btn__badge">3</span>
          </button>

          <div class="dropdown-menu dropdown-menu-end notif-menu shadow border-0 mt-2">
            <div class="d-flex align-items-center justify-content-between gap-2 px-3 py-2 border-bottom">
              <span class="fw-semibold">Notifications</span>
              <button class="btn btn-sm btn-light" type="button">
                <i class="bi bi-check2-all me-1" aria-hidden="true"></i>Mark all as read
              </button>
            </div>

            <div class="notif-menu__list">
              <a class="notification-item notification-item--unread align-items-center text-decoration-none text-body" href="{{ route('notifications') }}">
                <span class="position-relative flex-shrink-0">
                  <img class="avatar" src="{{ asset('assets/img/avatar-4.svg') }}" alt="">
                  <span class="notification-icon bg-primary"><i class="bi bi-person-plus-fill" aria-hidden="true"></i></span>
                </span>
                <span class="flex-grow-1 min-w-0">
                  <span class="d-block small clamp-2"><strong>Ali Ayoub</strong> sent you a friend request.</span>
                  <small class="text-brand fw-semibold">18 minutes ago</small>
                </span>
                <span class="unread-dot flex-shrink-0" aria-hidden="true"></span>
              </a>

              <a class="notification-item notification-item--unread align-items-center text-decoration-none text-body" href="{{ route('notifications') }}">
                <span class="position-relative flex-shrink-0">
                  <img class="avatar" src="{{ asset('assets/img/avatar-2.svg') }}" alt="">
                  <span class="notification-icon bg-primary"><i class="bi bi-hand-thumbs-up-fill" aria-hidden="true"></i></span>
                </span>
                <span class="flex-grow-1 min-w-0">
                  <span class="d-block small clamp-2"><strong>Ali Nahleh</strong> and 14 others liked your post.</span>
                  <small class="text-brand fw-semibold">40 minutes ago</small>
                </span>
                <span class="unread-dot flex-shrink-0" aria-hidden="true"></span>
              </a>

              <a class="notification-item notification-item--unread align-items-center text-decoration-none text-body" href="{{ route('notifications') }}">
                <span class="position-relative flex-shrink-0">
                  <img class="avatar" src="{{ asset('assets/img/avatar-3.svg') }}" alt="">
                  <span class="notification-icon bg-success"><i class="bi bi-chat-fill" aria-hidden="true"></i></span>
                </span>
                <span class="flex-grow-1 min-w-0">
                  <span class="d-block small clamp-2"><strong>Maysam Chaalan</strong> commented on your photo.</span>
                  <small class="text-brand fw-semibold">1 hour ago</small>
                </span>
                <span class="unread-dot flex-shrink-0" aria-hidden="true"></span>
              </a>

              <a class="notification-item align-items-center text-decoration-none text-body" href="{{ route('notifications') }}">
                <span class="position-relative flex-shrink-0">
                  <img class="avatar" src="{{ asset('assets/img/avatar-8.svg') }}" alt="">
                  <span class="notification-icon bg-danger"><i class="bi bi-hand-thumbs-up-fill" aria-hidden="true"></i></span>
                </span>
                <span class="flex-grow-1 min-w-0">
                  <span class="d-block small clamp-2"><strong>Haitham Ismail</strong> liked your photo in Studio work.</span>
                  <small class="text-secondary">Yesterday</small>
                </span>
              </a>
            </div>

            <div class="border-top p-2">
              <a class="btn btn-light w-100" href="{{ route('notifications') }}">View all notifications</a>
            </div>
          </div>
        </div>

        <div class="dropdown">
          <button class="btn p-0 border-0 rounded-circle lh-1" type="button" data-bs-toggle="dropdown"
                  data-bs-display="static" aria-expanded="false" aria-label="Account menu">
            <img class="avatar" src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="{{ auth()->user()->name }}">
          </button>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu--wide shadow border-0 mt-2">
            <li>
              <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('profile') }}">
                <img class="avatar" src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="">
                <span>
                  <span class="d-block fw-semibold">{{ auth()->user()->name }}</span>
                  <small class="text-secondary">See your profile</small>
                </span>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item py-2" href="{{ route('settings') }}"><i class="bi bi-gear me-2" aria-hidden="true"></i>Settings &amp; privacy</a></li>
            <li><a class="dropdown-item py-2" href="{{ route('notifications') }}"><i class="bi bi-bell me-2" aria-hidden="true"></i>Notification preferences</a></li>
            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-bookmark me-2" aria-hidden="true"></i>Saved items</a></li>
            <li><a class="dropdown-item py-2" href="#"><i class="bi bi-question-circle me-2" aria-hidden="true"></i>Help &amp; support</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="dropdown-item py-2 text-danger" type="submit"><i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i>Log out</button>
              </form>
            </li>
          </ul>
        </div>
        @else
          <a class="btn btn-sm btn-light" href="{{ route('login') }}">Log in</a>
          <a class="btn btn-sm btn-primary" href="{{ route('register') }}">Create account</a>
        @endauth
      </div>
    </nav>
  </header>

  <!-- ===================== Mobile drawer ===================== -->
  <div class="offcanvas offcanvas-start" role="dialog" tabindex="-1" id="navDrawer" aria-labelledby="navDrawerLabel">
    <div class="offcanvas-header border-bottom">
      <h2 class="offcanvas-title h6 mb-0 d-inline-flex align-items-center gap-2" id="navDrawerLabel">
        <img src="{{ asset('assets/img/logo.svg') }}" alt="" width="28" height="28"> YouBee
      </h2>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      @auth
      <a class="d-flex align-items-center gap-2 text-decoration-none text-body p-2 rounded hover-surface mb-2" href="{{ route('profile') }}">
        <img class="avatar avatar-lg" src="{{ asset('assets/img/avatar-1.svg') }}" alt="">
        <span>
          <span class="d-block fw-semibold">{{ auth()->user()->name }}</span>
          <small class="text-secondary">{{ '@'.auth()->user()->username }}</small>
        </span>
      </a>
      @endauth

      <form class="mb-3 d-md-none" role="search" action="#" method="get">
        <label class="visually-hidden" for="drawerSearch">Search YouBee</label>
        <input type="search" class="form-control" id="drawerSearch" name="q" placeholder="Search YouBee">
      </form>

      <nav class="nav flex-column side-nav" aria-label="Sections">
        <a @class(['nav-link', 'active' => $activePage === 'home']) href="{{ route('home') }}" @if ($activePage === 'home') aria-current="page" @endif><i class="bi bi-house-door" aria-hidden="true"></i><span>Feed</span></a>
        @auth
        <a @class(['nav-link', 'active' => $activePage === 'profile']) href="{{ route('profile') }}" @if ($activePage === 'profile') aria-current="page" @endif><i class="bi bi-person" aria-hidden="true"></i><span>Profile</span></a>
        <a @class(['nav-link', 'active' => $activePage === 'friends']) href="{{ route('friends') }}" @if ($activePage === 'friends') aria-current="page" @endif><i class="bi bi-people" aria-hidden="true"></i><span>Friends</span><span class="badge rounded-pill text-bg-danger ms-auto">2<span class="visually-hidden"> new</span></span></a>
        <a @class(['nav-link', 'active' => $activePage === 'messages']) href="{{ route('messages') }}" @if ($activePage === 'messages') aria-current="page" @endif><i class="bi bi-chat-dots" aria-hidden="true"></i><span>Messages</span><span class="badge rounded-pill text-bg-danger ms-auto">2<span class="visually-hidden"> new</span></span></a>
        <a @class(['nav-link', 'active' => $activePage === 'notifications']) href="{{ route('notifications') }}" @if ($activePage === 'notifications') aria-current="page" @endif><i class="bi bi-bell" aria-hidden="true"></i><span>Notifications</span><span class="badge rounded-pill text-bg-danger ms-auto">3<span class="visually-hidden"> new</span></span></a>
        <a class="nav-link" href="{{ route('profile').'#photos' }}"><i class="bi bi-images" aria-hidden="true"></i><span>Photos</span></a>
        <a class="nav-link" href="#"><i class="bi bi-bookmark" aria-hidden="true"></i><span>Saved</span></a>
        <a @class(['nav-link', 'active' => $activePage === 'settings']) href="{{ route('settings') }}" @if ($activePage === 'settings') aria-current="page" @endif><i class="bi bi-gear" aria-hidden="true"></i><span>Settings</span></a>
        @endauth
      </nav>

      @auth
      <button class="btn btn-primary w-100 mt-3" type="button" data-bs-toggle="modal" data-bs-target="#createPostModal">
        <i class="bi bi-plus-lg me-1" aria-hidden="true"></i>Create post
      </button>
      @else
      <div class="d-grid gap-2 mt-3">
        <a class="btn btn-primary" href="{{ route('login') }}">Log in</a>
        <a class="btn btn-light" href="{{ route('register') }}">Create account</a>
      </div>
      @endauth
    </div>
  </div>

  <!-- ===================== Mobile search drawer ===================== -->
  <div class="offcanvas offcanvas-top d-md-none" role="dialog" tabindex="-1" id="searchDrawer" aria-labelledby="searchDrawerLabel">
    <div class="offcanvas-header pb-2">
      <h2 class="offcanvas-title h6 mb-0" id="searchDrawerLabel">Search</h2>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body pt-0">
      <form role="search" action="#" method="get">
        <label class="visually-hidden" for="mobileSearch">Search YouBee</label>
        <div class="input-group">
          <span class="input-group-text" id="mobileSearchIcon"><i class="bi bi-search" aria-hidden="true"></i></span>
          <input type="search" class="form-control" id="mobileSearch" name="q"
                 placeholder="People and posts" aria-describedby="mobileSearchIcon" autocomplete="off">
        </div>
      </form>
      <h3 class="side-nav__label mt-3 px-0">Recent</h3>
      <ul class="list-unstyled mb-0 vstack gap-1">
        <li><a class="d-flex align-items-center gap-2 p-2 rounded text-decoration-none text-body hover-surface" href="{{ route('profile') }}"><i class="bi bi-clock-history text-secondary" aria-hidden="true"></i>Maysam Chaalan</a></li>
        <li><a class="d-flex align-items-center gap-2 p-2 rounded text-decoration-none text-body hover-surface" href="{{ route('profile') }}"><i class="bi bi-clock-history text-secondary" aria-hidden="true"></i>Ali Nahleh</a></li>
        <li><a class="d-flex align-items-center gap-2 p-2 rounded text-decoration-none text-body hover-surface" href="{{ route('profile') }}"><i class="bi bi-clock-history text-secondary" aria-hidden="true"></i>Ali Ayoub</a></li>
      </ul>
    </div>
  </div>

