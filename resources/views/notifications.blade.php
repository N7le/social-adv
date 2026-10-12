    @extends('layouts.app')

    @section('title', 'Notifications · YouBee Social')
    @section('description', 'Review your latest YouBee Social notifications.')
    @section('activePage', 'notifications')

    @section('content')
      <main class="col-12 col-lg-8 col-xl-6" id="main">

        @php
            [$newNotifications, $earlierNotifications] = $notifications->getCollection()->partition(fn ($notification) => is_null($notification->read_at));
        @endphp

        <div class="card mb-3">
          <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
              <h1 class="h4 mb-0">Notifications</h1>
              @if ($unreadCount > 0)
                <form action="{{ route('notifications.readAll') }}" method="post">
                  @csrf
                  <button class="btn btn-sm btn-light" type="submit">
                    <i class="bi bi-check2-all me-1" aria-hidden="true"></i>Mark all as read
                  </button>
                </form>
              @endif
            </div>

            <ul class="nav nav-pills gap-2" aria-label="Filter notifications">
              <li class="nav-item">
                <a @class(['nav-link', 'active' => $filter !== 'unread', 'link-muted' => $filter === 'unread']) href="{{ route('notifications') }}" @if ($filter !== 'unread') aria-current="page" @endif>All</a>
              </li>
              <li class="nav-item">
                <a @class(['nav-link', 'active' => $filter === 'unread', 'link-muted' => $filter !== 'unread']) href="{{ route('notifications', ['filter' => 'unread']) }}" @if ($filter === 'unread') aria-current="page" @endif>
                  Unread
                  @if ($unreadCount > 0)
                    <span class="badge rounded-pill text-bg-danger">{{ $unreadCount }}</span>
                  @endif
                </a>
              </li>
            </ul>
          </div>
        </div>

        @if ($notifications->isEmpty())
          <section class="card" aria-label="No notifications">
            <div class="card-body text-center text-secondary py-5">
              <i class="bi bi-bell fs-2 d-block mb-2" aria-hidden="true"></i>
              <p class="mb-0">{{ $filter === 'unread' ? 'You\'re all caught up. No unread notifications.' : 'No notifications yet.' }}</p>
            </div>
          </section>
        @endif

        @if ($newNotifications->isNotEmpty())
          <!-- ===== New ===== -->
          <section class="card mb-3" aria-labelledby="newNotificationsHeading">
            <div class="card-body">
              <h2 class="h6 text-secondary mb-2" id="newNotificationsHeading">New</h2>
              <ul class="list-unstyled mb-0">
                @foreach ($newNotifications as $notification)
                  <x-notification-item :notification="$notification" :actor="$actors->get($notification->data['actor_id'] ?? null)" />
                @endforeach
              </ul>
            </div>
          </section>
        @endif

        @if ($earlierNotifications->isNotEmpty())
          <!-- ===== Earlier ===== -->
          <section class="card" aria-labelledby="earlierNotificationsHeading">
            <div class="card-body">
              <h2 class="h6 text-secondary mb-2" id="earlierNotificationsHeading">Earlier</h2>
              <ul class="list-unstyled mb-0">
                @foreach ($earlierNotifications as $notification)
                  <x-notification-item :notification="$notification" :actor="$actors->get($notification->data['actor_id'] ?? null)" />
                @endforeach
              </ul>
            </div>
          </section>
        @endif

        @if ($notifications->hasMorePages())
          <div class="text-center mt-3">
            <a class="btn btn-light" href="{{ $notifications->nextPageUrl() }}">See previous notifications</a>
          </div>
        @endif
      </main>

      <!-- ========== Right rail ========== -->
      <div class="col-xl-3 d-none d-xl-block">
        <div class="sticky-rail pb-3">

          <x-people-you-may-know :user="auth()->user()" />

          <section class="card mb-3" aria-labelledby="birthdaysHeading">
            <div class="card-body d-flex align-items-start gap-3">
              <i class="bi bi-gift fs-3 text-brand" aria-hidden="true"></i>
              <div>
                <h2 class="h6 mb-1" id="birthdaysHeading">Birthdays</h2>
                <p class="small text-secondary mb-0">
                  <strong class="text-body">Ali Nahleh</strong> and
                  <strong class="text-body">2 others</strong> have birthdays today.
                </p>
              </div>
            </div>
          </section>

          <section class="card" aria-labelledby="contactsHeading">
            <div class="card-body">
              <h2 class="h6 mb-3" id="contactsHeading">Contacts</h2>
              <ul class="list-unstyled mb-0 vstack gap-1">
                <li>
                  <a class="d-flex align-items-center gap-2 p-1 rounded text-decoration-none text-body hover-surface" href="{{ route('messages') }}">
                    <span class="presence presence--online"><img class="avatar avatar-sm" src="{{ asset('assets/img/avatar-2.svg') }}" alt=""><span class="presence__dot"></span></span>
                    <span class="small fw-medium">Ali Nahleh</span>
                  </a>
                </li>
                <li>
                  <a class="d-flex align-items-center gap-2 p-1 rounded text-decoration-none text-body hover-surface" href="{{ route('messages') }}">
                    <span class="presence presence--online"><img class="avatar avatar-sm" src="{{ asset('assets/img/avatar-3.svg') }}" alt=""><span class="presence__dot"></span></span>
                    <span class="small fw-medium">Maysam Chaalan</span>
                  </a>
                </li>
                <li>
                  <a class="d-flex align-items-center gap-2 p-1 rounded text-decoration-none text-body hover-surface" href="{{ route('messages') }}">
                    <span class="presence presence--away"><img class="avatar avatar-sm" src="{{ asset('assets/img/avatar-7.svg') }}" alt=""><span class="presence__dot"></span></span>
                    <span class="small fw-medium">Elias Al Omar</span>
                  </a>
                </li>
                <li>
                  <a class="d-flex align-items-center gap-2 p-1 rounded text-decoration-none text-body hover-surface" href="{{ route('messages') }}">
                    <span class="presence"><img class="avatar avatar-sm" src="{{ asset('assets/img/avatar-8.svg') }}" alt=""><span class="presence__dot"></span></span>
                    <span class="small fw-medium">Haitham Ismail</span>
                  </a>
                </li>
              </ul>
            </div>
          </section>

        </div>
      </div>

    @endsection

