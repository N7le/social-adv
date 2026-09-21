  @auth
  <!-- ===================== Mobile tab bar ===================== -->
  <nav class="tab-bar d-lg-none" aria-label="Primary (compact)">
    <a @class(['tab-bar__item', 'active' => $activePage === 'home']) href="{{ route('home') }}" @if ($activePage === 'home') aria-current="page" @endif><i class="bi bi-house-door" aria-hidden="true"></i><span>Feed</span></a>
    <a @class(['tab-bar__item', 'active' => $activePage === 'friends']) href="{{ route('friends') }}" @if ($activePage === 'friends') aria-current="page" @endif><i class="bi bi-people" aria-hidden="true"></i><span>Friends</span></a>
    <button class="tab-bar__item border-0 bg-transparent" type="button" data-bs-toggle="modal" data-bs-target="#createPostModal">
      <i class="bi bi-plus-square text-brand" aria-hidden="true"></i><span>Post</span>
    </button>
    <a @class(['tab-bar__item', 'active' => $activePage === 'messages']) href="{{ route('messages') }}" @if ($activePage === 'messages') aria-current="page" @endif><i class="bi bi-chat-dots" aria-hidden="true"></i><span>Chats</span></a>
    <a @class(['tab-bar__item', 'active' => $activePage === 'notifications']) href="{{ route('notifications') }}" @if ($activePage === 'notifications') aria-current="page" @endif><i class="bi bi-bell" aria-hidden="true"></i><span>Alerts</span></a>
  </nav>  <!-- ===================== Create post dialog ===================== -->
  <div class="modal fade" role="dialog" id="createPostModal" tabindex="-1" aria-labelledby="createPostLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
      <form class="modal-content" action="#" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h2 class="modal-title h5" id="createPostLabel">Create post</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="d-flex align-items-center gap-2 mb-3">
            <img class="avatar" src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="">
            <div>
              <p class="fw-semibold mb-1">{{ auth()->user()->name }}</p>
              <label class="visually-hidden" for="postAudience">Who can see this post</label>
              <select class="form-select form-select-sm w-auto" id="postAudience" name="audience">
                <option value="public" selected>🌐 Public</option>
                <option value="friends">👥 Friends</option>
                <option value="friends-except">👥 Friends except…</option>
                <option value="only-me">🔒 Only me</option>
              </select>
            </div>
          </div>

          <label class="visually-hidden" for="postBody">Post text</label>
          <textarea class="form-control border-0 fs-5 mb-3" id="postBody" name="body" rows="4"
                    placeholder="What's on your mind, {{ str(auth()->user()->name)->before(' ') }}?"></textarea>

          <div class="dropzone position-relative">
            <i class="bi bi-image fs-2" aria-hidden="true"></i>
            <span class="fw-semibold text-body">Add a photo or video</span>
            <small>One file per post · or drag and drop it here</small>
            <label class="visually-hidden" for="postMedia">Attach a photo or video</label>
            <input type="file" id="postMedia" name="media" accept="image/*,video/*">
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Save draft</button>
          <button type="submit" class="btn btn-primary px-4">Post</button>
        </div>
      </form>
    </div>
  </div>
  @endauth
