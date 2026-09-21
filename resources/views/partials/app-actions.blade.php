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
      <form class="modal-content" action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h2 class="modal-title h5" id="createPostLabel">Create post</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="d-flex align-items-center gap-2 mb-3">
            <img class="avatar" src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="">
            <div>
              <p class="fw-semibold mb-1">{{ auth()->user()->name }}</p>
              <label class="visually-hidden" for="postPrivacy">Who can see this post</label>
              <select class="form-select form-select-sm w-auto" id="postPrivacy" name="privacy">
                <option value="public" @selected(auth()->user()->privacy_status === 'public')>Public</option>
                <option value="private" @selected(auth()->user()->privacy_status === 'private')>Private</option>
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

          <div id="postMediaPreview" class="mt-3 d-none text-center">
            <div class="position-relative d-inline-block">
              <img id="postMediaPreviewImage" class="img-fluid rounded d-none" style="max-height: 240px;" alt="Selected image preview">
              <video id="postMediaPreviewVideo" class="img-fluid rounded d-none" style="max-height: 240px;" controls></video>
              <button type="button" id="postMediaRemove" class="btn btn-sm btn-dark position-absolute top-0 end-0 m-1" aria-label="Remove selected file">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
              </button>
            </div>
            <p id="postMediaName" class="small text-secondary mt-1 mb-0"></p>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary px-4">Post</button>
        </div>
      </form>
    </div>
  </div>
  @endauth
