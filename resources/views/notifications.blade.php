    @extends('layouts.app')

    @section('title', 'Notifications · YouBee Social')
    @section('description', 'Review your latest YouBee Social notifications and requests.')
    @section('activePage', 'notifications')

    @section('content')
      <main class="col-12 col-lg-8 col-xl-6" id="main">

        <div class="card mb-3">
          <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
              <h1 class="h4 mb-0">Notifications</h1>
              <button class="btn btn-sm btn-light" type="button">
                <i class="bi bi-check2-all me-1" aria-hidden="true"></i>Mark all as read
              </button>
            </div>

            <ul class="nav nav-pills gap-2" aria-label="Filter notifications">
              <li class="nav-item"><a class="nav-link active" href="#" aria-current="page">All</a></li>
              <li class="nav-item"><a class="nav-link link-muted" href="#">Unread <span class="badge rounded-pill text-bg-danger">3</span></a></li>
              <li class="nav-item"><a class="nav-link link-muted" href="#">Requests</a></li>
            </ul>
          </div>
        </div>

        <!-- ===== New ===== -->
        <section class="card mb-3" aria-labelledby="newNotificationsHeading">
          <div class="card-body">
            <h2 class="h6 text-secondary mb-2" id="newNotificationsHeading">New</h2>
            <ul class="list-unstyled mb-0">

              <li class="notification-item notification-item--unread position-relative align-items-center">
                <span class="position-relative flex-shrink-0">
                  <img class="avatar avatar-lg" src="{{ asset('assets/img/avatar-4.svg') }}" alt="">
                  <span class="notification-icon bg-primary"><i class="bi bi-person-plus-fill" aria-hidden="true"></i></span>
                </span>
                <span class="flex-grow-1 min-w-0">
                  <span class="d-block"><a class="fw-semibold text-body text-decoration-none" href="{{ route('profile') }}">Ali Ayoub</a> sent you a friend request.</span>
                  <small class="text-brand fw-semibold"><time datetime="2026-09-14T10:02">18 minutes ago</time></small>
                  <span class="d-flex gap-2 mt-2">
                    <button class="btn btn-sm btn-primary" type="button">Confirm</button>
                    <button class="btn btn-sm btn-light" type="button">Delete</button>
                  </span>
                </span>
              </li>

              <li class="notification-item notification-item--unread position-relative align-items-center">
                <span class="position-relative flex-shrink-0">
                  <img class="avatar avatar-lg" src="{{ asset('assets/img/avatar-2.svg') }}" alt="">
                  <span class="notification-icon bg-primary"><i class="bi bi-hand-thumbs-up-fill" aria-hidden="true"></i></span>
                </span>
                <span class="flex-grow-1 min-w-0">
                  <span class="d-block"><a class="fw-semibold text-body text-decoration-none stretched-link" href="#">Ali Nahleh</a> and <strong>14 others</strong> liked your post about type scales.</span>
                  <small class="text-brand fw-semibold"><time datetime="2026-09-14T09:40">40 minutes ago</time></small>
                </span>
                <span class="unread-dot flex-shrink-0 align-self-center" aria-hidden="true"></span>
                <span class="visually-hidden">Unread</span>
              </li>

              <li class="notification-item notification-item--unread position-relative align-items-center">
                <span class="position-relative flex-shrink-0">
                  <img class="avatar avatar-lg" src="{{ asset('assets/img/avatar-3.svg') }}" alt="">
                  <span class="notification-icon bg-success"><i class="bi bi-chat-fill" aria-hidden="true"></i></span>
                </span>
                <span class="flex-grow-1 min-w-0">
                  <span class="d-block"><a class="fw-semibold text-body text-decoration-none stretched-link" href="#">Maysam Chaalan</a> commented: “That second shot is unreal. Which lens?”</span>
                  <small class="text-brand fw-semibold"><time datetime="2026-09-14T09:05">1 hour ago</time></small>
                </span>
                <span class="unread-dot flex-shrink-0 align-self-center" aria-hidden="true"></span>
                <span class="visually-hidden">Unread</span>
              </li>

            </ul>
          </div>
        </section>

        <!-- ===== Earlier ===== -->
        <section class="card" aria-labelledby="earlierNotificationsHeading">
          <div class="card-body">
            <h2 class="h6 text-secondary mb-2" id="earlierNotificationsHeading">Earlier</h2>
            <ul class="list-unstyled mb-0">

              <li class="notification-item position-relative align-items-center">
                <span class="position-relative flex-shrink-0">
                  <img class="avatar avatar-lg" src="{{ asset('assets/img/avatar-8.svg') }}" alt="">
                  <span class="notification-icon bg-danger"><i class="bi bi-heart-fill" aria-hidden="true"></i></span>
                </span>
                <span class="flex-grow-1 min-w-0">
                  <span class="d-block"><a class="fw-semibold text-body text-decoration-none stretched-link" href="#">Haitham Ismail</a> liked your photo in <strong>Studio work</strong>.</span>
                  <small class="text-secondary"><time datetime="2026-09-13T18:20">Yesterday at 18:20</time></small>
                </span>
              </li>

              <li class="notification-item position-relative align-items-center">
                <span class="position-relative flex-shrink-0">
                  <img class="avatar avatar-lg" src="{{ asset('assets/img/avatar-5.svg') }}" alt="">
                  <span class="notification-icon bg-secondary"><i class="bi bi-share-fill" aria-hidden="true"></i></span>
                </span>
                <span class="flex-grow-1 min-w-0">
                  <span class="d-block"><a class="fw-semibold text-body text-decoration-none stretched-link" href="#">Elie Amin</a> shared your post.</span>
                  <small class="text-secondary"><time datetime="2026-09-13T11:05">Yesterday at 11:05</time></small>
                </span>
              </li>

              <li class="notification-item position-relative align-items-center">
                <span class="position-relative flex-shrink-0">
                  <img class="avatar avatar-lg" src="{{ asset('assets/img/avatar-7.svg') }}" alt="">
                  <span class="notification-icon bg-primary"><i class="bi bi-people-fill" aria-hidden="true"></i></span>
                </span>
                <span class="flex-grow-1 min-w-0">
                  <span class="d-block"><a class="fw-semibold text-body text-decoration-none stretched-link" href="#">Elias Al Omar</a> accepted your friend request.</span>
                  <small class="text-secondary"><time datetime="2026-09-12T16:40">2 days ago</time></small>
                </span>
              </li>

              <li class="notification-item position-relative align-items-center">
                <span class="position-relative flex-shrink-0">
                  <img class="avatar avatar-lg" src="{{ asset('assets/img/avatar-2.svg') }}" alt="">
                  <span class="notification-icon bg-warning"><i class="bi bi-gift-fill" aria-hidden="true"></i></span>
                </span>
                <span class="flex-grow-1 min-w-0">
                  <span class="d-block"><strong>Ali Nahleh</strong> has a birthday today. Write on their timeline.</span>
                  <small class="text-secondary"><time datetime="2026-09-12T07:00">2 days ago</time></small>
                </span>
              </li>

            </ul>

            <div class="text-center mt-3">
              <button class="btn btn-light" type="button">See previous notifications</button>
            </div>
          </div>
        </section>
      </main>

      <!-- ========== Right rail ========== -->
      <div class="col-xl-3 d-none d-xl-block">
        <div class="sticky-rail pb-3">

          <section class="card mb-3" aria-labelledby="suggestionsHeading">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <h2 class="h6 mb-0" id="suggestionsHeading">People you may know</h2>
                <a class="small link-muted" href="{{ route('friends') }}">See all</a>
              </div>
              <ul class="list-unstyled mb-0 vstack gap-3">
                <li class="d-flex align-items-center gap-2">
                  <img class="avatar" src="{{ asset('assets/img/avatar-4.svg') }}" alt="">
                  <div class="flex-grow-1 min-w-0">
                    <p class="fw-semibold small mb-0 text-truncate">Ali Ayoub</p>
                    <p class="mutuals mb-0 text-truncate">12 mutual friends</p>
                  </div>
                  <button class="btn btn-sm btn-outline-primary" type="button" aria-label="Add Ali Ayoub as a friend">
                    <i class="bi bi-person-plus" aria-hidden="true"></i>
                  </button>
                </li>
                <li class="d-flex align-items-center gap-2">
                  <img class="avatar" src="{{ asset('assets/img/avatar-5.svg') }}" alt="">
                  <div class="flex-grow-1 min-w-0">
                    <p class="fw-semibold small mb-0 text-truncate">Elie Amin</p>
                    <p class="mutuals mb-0 text-truncate">5 mutual friends</p>
                  </div>
                  <button class="btn btn-sm btn-outline-primary" type="button" aria-label="Add Elie Amin as a friend">
                    <i class="bi bi-person-plus" aria-hidden="true"></i>
                  </button>
                </li>
                <li class="d-flex align-items-center gap-2">
                  <img class="avatar" src="{{ asset('assets/img/avatar-6.svg') }}" alt="">
                  <div class="flex-grow-1 min-w-0">
                    <p class="fw-semibold small mb-0 text-truncate">Baqer Alayyan</p>
                    <p class="mutuals mb-0 text-truncate">3 mutual friends</p>
                  </div>
                  <button class="btn btn-sm btn-outline-primary" type="button" aria-label="Add Baqer Alayyan as a friend">
                    <i class="bi bi-person-plus" aria-hidden="true"></i>
                  </button>
                </li>
              </ul>
            </div>
          </section>

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

