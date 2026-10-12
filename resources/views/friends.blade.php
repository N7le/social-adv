    @extends('layouts.app')

    @section('title', 'Friends · YouBee Social')
    @section('description', 'Find friends and manage your YouBee Social connections.')
    @section('activePage', 'friends')

    @section('content')
      <main class="col-12 col-lg-8 col-xl-9" id="main">

        <!-- ===== Find people ===== -->
        <section class="card mb-3" aria-labelledby="findPeopleHeading">
          <div class="card-body">
            <h1 class="h4 mb-1" id="findPeopleHeading">Friends</h1>
            <p class="text-secondary mb-3">Find people you know, and keep track of who's waiting on you.</p>

            <form class="row g-2 align-items-end" role="search" action="#" method="get">
              <div class="col-12 col-md">
                <label class="form-label" for="peopleSearch">Search by name or username</label>
                <div class="input-group">
                  <span class="input-group-text" id="peopleSearchIcon"><i class="bi bi-search" aria-hidden="true"></i></span>
                  <input type="search" class="form-control" id="peopleSearch" name="q"
                         placeholder="e.g. Baqer Alayyan" aria-describedby="peopleSearchIcon">
                </div>
              </div>
              <div class="col-6 col-md-auto">
                <label class="form-label" for="peopleCity">City</label>
                <select class="form-select" id="peopleCity" name="city">
                  <option value="" selected>Anywhere</option>
                  <option>Manchester</option>
                  <option>London</option>
                  <option>Lisbon</option>
                  <option>Berlin</option>
                </select>
              </div>
              <div class="col-6 col-md-auto">
                <label class="form-label" for="peopleSchool">School</label>
                <select class="form-select" id="peopleSchool" name="school">
                  <option value="" selected>Any</option>
                  <option>Central Saint Martins</option>
                  <option>University of Manchester</option>
                </select>
              </div>
              <div class="col-12 col-md-auto">
                <button class="btn btn-primary w-100" type="submit">Search</button>
              </div>
            </form>

            <hr class="my-3">

            <div class="d-flex flex-wrap gap-2">
              <button class="btn btn-sm btn-light" type="button"><i class="bi bi-person-lines-fill me-1" aria-hidden="true"></i>Import contacts</button>
              <button class="btn btn-sm btn-light" type="button"><i class="bi bi-upc-scan me-1" aria-hidden="true"></i>Scan a friend code</button>
              <button class="btn btn-sm btn-light" type="button"><i class="bi bi-envelope me-1" aria-hidden="true"></i>Invite by email</button>
            </div>
          </div>
        </section>

        <!-- ===== Tabs ===== -->
        <div class="card">
          <div class="card-body pb-0">
            <ul class="nav nav-pills gap-2 flex-nowrap overflow-auto pb-3" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-requests" data-bs-toggle="pill" data-bs-target="#panel-requests"
                        type="button" role="tab" aria-controls="panel-requests" aria-selected="true">
                  Requests
                  @if ($receivedRequests->isNotEmpty())
                    <span class="badge rounded-pill text-bg-danger">{{ $receivedRequests->count() }}</span>
                  @endif
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-suggestions" data-bs-toggle="pill" data-bs-target="#panel-suggestions"
                        type="button" role="tab" aria-controls="panel-suggestions" aria-selected="false">Suggestions</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-all" data-bs-toggle="pill" data-bs-target="#panel-all"
                        type="button" role="tab" aria-controls="panel-all" aria-selected="false">All friends</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-sent" data-bs-toggle="pill" data-bs-target="#panel-sent"
                        type="button" role="tab" aria-controls="panel-sent" aria-selected="false">Sent</button>
              </li>
            </ul>
          </div>

          <div class="card-body pt-2">
            <div class="tab-content">

              <!-- ========== Requests ========== -->
              <div class="tab-pane fade show active" id="panel-requests" role="tabpanel" aria-labelledby="tab-requests" tabindex="0">
                @if ($receivedRequests->isEmpty())
                  <p class="text-secondary mb-0">No pending friend requests.</p>
                @else
                  <h2 class="h6 text-secondary mb-3">{{ $receivedRequests->count() }} {{ Str::plural('person', $receivedRequests->count()) }} want{{ $receivedRequests->count() === 1 ? 's' : '' }} to be your friend</h2>
                  <ul class="list-unstyled vstack gap-2 mb-0">
                    @foreach ($receivedRequests as $friendRequest)
                      <x-friend-request :friend-request="$friendRequest" />
                    @endforeach
                  </ul>
                @endif
              </div>

              <!-- ========== Suggestions ========== -->
              <div class="tab-pane fade" id="panel-suggestions" role="tabpanel" aria-labelledby="tab-suggestions" tabindex="0">
                <h2 class="h6 text-secondary mb-3">People you may know</h2>
                <div class="row g-3 row-cols-2 row-cols-sm-3 row-cols-xl-4">
                  <div class="col">
                    <div class="card person-card">
                      <div class="card-body">
                        <a class="text-decoration-none text-body w-100" href="{{ route('profile') }}">
                          <img class="avatar avatar-xl mb-2" src="{{ asset('assets/img/avatar-4.svg') }}" alt="">
                          <p class="fw-semibold mb-0 text-truncate w-100">Ali Ayoub</p>
                        </a>
                        <p class="mutuals mb-3">12 mutual friends</p>
                        <div class="d-grid gap-2 w-100 mt-auto">
                          <button class="btn btn-sm btn-primary" type="button"><i class="bi bi-person-plus me-1" aria-hidden="true"></i>Add friend</button>
                          <button class="btn btn-sm btn-light" type="button">Remove</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="card person-card">
                      <div class="card-body">
                        <a class="text-decoration-none text-body w-100" href="{{ route('profile') }}">
                          <img class="avatar avatar-xl mb-2" src="{{ asset('assets/img/avatar-5.svg') }}" alt="">
                          <p class="fw-semibold mb-0 text-truncate w-100">Elie Amin</p>
                        </a>
                        <p class="mutuals mb-3">5 mutual friends</p>
                        <div class="d-grid gap-2 w-100 mt-auto">
                          <button class="btn btn-sm btn-primary" type="button"><i class="bi bi-person-plus me-1" aria-hidden="true"></i>Add friend</button>
                          <button class="btn btn-sm btn-light" type="button">Remove</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="card person-card">
                      <div class="card-body">
                        <a class="text-decoration-none text-body w-100" href="{{ route('profile') }}">
                          <img class="avatar avatar-xl mb-2" src="{{ asset('assets/img/avatar-6.svg') }}" alt="">
                          <p class="fw-semibold mb-0 text-truncate w-100">Baqer Alayyan</p>
                        </a>
                        <p class="mutuals mb-3">3 mutual friends</p>
                        <div class="d-grid gap-2 w-100 mt-auto">
                          <button class="btn btn-sm btn-primary" type="button"><i class="bi bi-check2 me-1" aria-hidden="true"></i>Requested</button>
                          <button class="btn btn-sm btn-light" type="button">Cancel</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col">
                    <div class="card person-card">
                      <div class="card-body">
                        <a class="text-decoration-none text-body w-100" href="{{ route('profile') }}">
                          <img class="avatar avatar-xl mb-2" src="{{ asset('assets/img/avatar-9.svg') }}" alt="">
                          <p class="fw-semibold mb-0 text-truncate w-100">Rashad Nahleh</p>
                        </a>
                        <p class="mutuals mb-3">7 mutual friends</p>
                        <div class="d-grid gap-2 w-100 mt-auto">
                          <button class="btn btn-sm btn-primary" type="button"><i class="bi bi-check2 me-1" aria-hidden="true"></i>Requested</button>
                          <button class="btn btn-sm btn-light" type="button">Cancel</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="text-center mt-4">
                  <button class="btn btn-light" type="button">See more suggestions</button>
                </div>
              </div>

              <!-- ========== All friends ========== -->
              <div class="tab-pane fade" id="panel-all" role="tabpanel" aria-labelledby="tab-all" tabindex="0">
                @if ($friends->isEmpty())
                  <p class="text-secondary mb-0">You haven't added any friends yet.</p>
                @else
                  <h2 class="h6 text-secondary mb-3">{{ $friends->count() }} {{ Str::plural('friend', $friends->count()) }}</h2>

                  <ul class="list-unstyled vstack gap-1 mb-0">
                    @foreach ($friends as $friend)
                      <li class="d-flex align-items-center gap-3 p-2 rounded hover-surface">
                        <a href="{{ route('profile.show', ['username' => $friend->username]) }}">
                          <img class="avatar avatar-lg" src="{{ $friend->profile_photo ? asset('storage/'.$friend->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="{{ $friend->name }}">
                        </a>
                        <span class="flex-grow-1 min-w-0">
                          <a class="fw-semibold text-body text-decoration-none d-block text-truncate" href="{{ route('profile.show', ['username' => $friend->username]) }}">{{ $friend->name }}</a>
                          <span class="mutuals">{{ '@'.$friend->username }}</span>
                        </span>
                        <a class="btn btn-sm btn-light" href="{{ route('messages') }}"><i class="bi bi-chat-dots me-1" aria-hidden="true"></i>Message</a>
                        <livewire:add-friend :target="$friend" :wire:key="'friend-'.$friend->id" />
                      </li>
                    @endforeach
                  </ul>
                @endif
              </div>

              <!-- ========== Sent requests ========== -->
              <div class="tab-pane fade" id="panel-sent" role="tabpanel" aria-labelledby="tab-sent" tabindex="0">
                @if ($sentRequests->isEmpty())
                  <p class="text-secondary mb-0">You haven't sent any friend requests.</p>
                @else
                  <h2 class="h6 text-secondary mb-3">Requests you've sent</h2>
                  <ul class="list-unstyled vstack gap-1 mb-0">
                    @foreach ($sentRequests as $friendRequest)
                      <x-friend-request :friend-request="$friendRequest" />
                    @endforeach
                  </ul>
                @endif
              </div>
            </div>
          </div>
        </div>
      </main>
    @endsection
