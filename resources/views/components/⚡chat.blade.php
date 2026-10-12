<?php

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public ?int $selectedConversationId = null;

    public ?int $newMessageRecipientId = null;

    public string $newMessageBody = '';

    public function mount(): void
    {
        $first = $this->conversations->first();

        if ($first !== null) {
            $this->selectConversation($first->id);
        }
    }

    #[Computed]
    public function conversations(): Collection
    {
        $user = Auth::user();

        return Conversation::for($user)
            ->with(['userOne', 'userTwo', 'latestMessage'])
            ->withCount([
                'messages as unread_count' => fn ($query) => $query->whereNull('read_at')->where('sender_id', '!=', $user->id),
            ])
            ->get()
            ->sortByDesc(fn (Conversation $conversation) => $conversation->latestMessage?->created_at)
            ->values();
    }

    #[Computed]
    public function selectedConversation(): ?Conversation
    {
        if ($this->selectedConversationId === null) {
            return null;
        }

        return Conversation::with(['messages.sender', 'userOne', 'userTwo'])->find($this->selectedConversationId);
    }

    #[Computed]
    public function friends(): Collection
    {
        return Auth::user()->friends()->orderBy('name')->get();
    }

    public function selectConversation(int $conversationId): void
    {
        $conversation = Conversation::findOrFail($conversationId);

        abort_unless($conversation->hasParticipant(Auth::user()), 403);

        $this->selectedConversationId = $conversation->id;
        $this->markRead($conversation);

        unset($this->conversations, $this->selectedConversation);
    }

    /**
     * Persist a message and return its payload for the JS feed append (no rerender).
     *
     * @return array{id: int, conversation_id: int, body: string, time: string, datetime: string}
     */
    public function sendMessage(string $body): array
    {
        $conversation = $this->selectedConversation;

        abort_if($conversation === null, 404);
        abort_unless($conversation->hasParticipant(Auth::user()), 403);

        Gate::authorize('sendMessage', $conversation->otherParticipant(Auth::user()));

        $validated = validator(['body' => $body], ['body' => ['required', 'string', 'max:5000']])->validate();

        $message = $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'body' => $validated['body'],
        ]);

        event(new MessageSent($message));

        $this->skipRender();

        return [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'body' => $message->body,
            'time' => $message->created_at->format('H:i'),
            'datetime' => $message->created_at->toIso8601String(),
        ];
    }

    public function startConversation(): void
    {
        $this->validate([
            'newMessageRecipientId' => ['required', 'integer'],
            'newMessageBody' => ['required', 'string', 'max:5000'],
        ]);

        $recipient = User::findOrFail($this->newMessageRecipientId);

        Gate::authorize('sendMessage', $recipient);

        $conversation = Conversation::between(Auth::user(), $recipient);

        $message = $conversation->messages()->create([
            'sender_id' => Auth::id(),
            'body' => $this->newMessageBody,
        ]);

        event(new MessageSent($message));

        $this->reset('newMessageRecipientId', 'newMessageBody');
        $this->selectConversation($conversation->id);
        $this->dispatch('close-new-message-modal');
    }

    public function markConversationRead(): void
    {
        $conversation = $this->selectedConversation;

        if ($conversation !== null && $conversation->hasParticipant(Auth::user())) {
            $this->markRead($conversation);
        }

        $this->skipRender();
    }

    public function listTimestamp(?Message $message): string
    {
        if ($message === null) {
            return '';
        }

        $time = $message->created_at;

        if ($time->isToday()) {
            return $time->format('H:i');
        }

        if ($time->isYesterday()) {
            return 'Yest.';
        }

        if ($time->greaterThan(now()->subWeek())) {
            return $time->format('D');
        }

        return $time->format('M j');
    }

    private function markRead(Conversation $conversation): void
    {
        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_id', '!=', Auth::id())
            ->update(['read_at' => now()]);
    }
};
?>
<div class="card full-height-panel overflow-hidden">
  <div class="row g-0 h-100">

    <!-- ===== Conversation list (drawer on phones, column from md up) ===== -->
    <div class="col-md-5 col-lg-4 border-end h-100">
      <div class="offcanvas-md offcanvas-start h-100" role="dialog" tabindex="-1" id="conversationDrawer"
           aria-labelledby="conversationDrawerLabel" wire:ignore.self>

        <div class="offcanvas-header border-bottom">
          <h2 class="offcanvas-title h6 mb-0" id="conversationDrawerLabel">Chats</h2>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                  data-bs-target="#conversationDrawer" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">

          <div class="p-3 border-bottom">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <h2 class="h6 mb-0 d-none d-md-block">Chats</h2>
              <button class="btn btn-sm btn-primary ms-auto" type="button"
                      data-bs-toggle="modal" data-bs-target="#newMessageModal">
                <i class="bi bi-pencil-square me-1" aria-hidden="true"></i>New
              </button>
            </div>
          </div>

          <div class="flex-grow-1 overflow-auto p-2">
            <ul class="list-group list-group-flush conversation-list mb-0">
              @forelse ($this->conversations as $conversation)
                @php
                  $other = $conversation->otherParticipant(auth()->user());
                  $latest = $conversation->latestMessage;
                  $isActive = $conversation->id === $selectedConversationId;
                @endphp
                <li class="list-group-item d-flex align-items-center gap-2 position-relative {{ $isActive ? 'active' : 'hover-surface' }}"
                    wire:key="conversation-{{ $conversation->id }}" @if ($isActive) aria-current="true" @endif>
                  <img class="avatar" src="{{ $other->profile_photo ? asset('storage/'.$other->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="">
                  <span class="flex-grow-1 min-w-0">
                    <span class="d-flex justify-content-between gap-2">
                      <button type="button"
                              class="btn btn-link p-0 border-0 fw-semibold text-truncate text-body text-decoration-none stretched-link text-start"
                              wire:click="selectConversation({{ $conversation->id }})">{{ $other->name }}</button>
                      @if ($latest)
                        <small class="text-secondary flex-shrink-0">
                          <time datetime="{{ $latest->created_at->toIso8601String() }}">{{ $this->listTimestamp($latest) }}</time>
                        </small>
                      @endif
                    </span>
                    <span class="d-block small text-truncate {{ $conversation->unread_count > 0 ? 'fw-semibold' : 'text-secondary' }}">
                      @if ($latest)
                        {{ $latest->sender_id === auth()->id() ? 'You: ' : '' }}{{ $latest->body }}
                      @endif
                    </span>
                  </span>
                  @if ($conversation->unread_count > 0)
                    <span class="unread-dot flex-shrink-0" aria-hidden="true"></span>
                    <span class="visually-hidden">Unread</span>
                  @endif
                </li>
              @empty
                <li class="list-group-item text-secondary small">No conversations yet. Start one with the New button.</li>
              @endforelse
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- ===== Active thread ===== -->
    <div class="col-md-7 col-lg-8 d-flex flex-column h-100">
      @if ($conversation = $this->selectedConversation)
        @php $other = $conversation->otherParticipant(auth()->user()); @endphp

        <header class="d-flex align-items-center gap-2 p-2 p-sm-3 border-bottom">
          <button class="btn btn-light d-md-none" type="button" data-bs-toggle="offcanvas"
                  data-bs-target="#conversationDrawer" aria-controls="conversationDrawer" aria-label="Show conversations">
            <i class="bi bi-list" aria-hidden="true"></i>
          </button>

          <a class="d-flex align-items-center gap-2 text-decoration-none text-body min-w-0 flex-grow-1"
             href="{{ route('profile.show', ['username' => $other->username]) }}">
            <img class="avatar" src="{{ $other->profile_photo ? asset('storage/'.$other->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="">
            <span class="min-w-0">
              <span class="d-block fw-semibold text-truncate">{{ $other->name }}</span>
            </span>
          </a>

          <div class="dropdown">
            <button class="icon-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Conversation options">
              <i class="bi bi-info-circle" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
              <li>
                <a class="dropdown-item" href="{{ route('profile.show', ['username' => $other->username]) }}">
                  <i class="bi bi-person me-2" aria-hidden="true"></i>View profile
                </a>
              </li>
            </ul>
          </div>
        </header>

        <div class="chat-scroll" id="chatFeed" data-conversation-id="{{ $conversation->id }}">
          @foreach ($conversation->messages as $message)
            @if ($message->sender_id === auth()->id())
              <div class="msg msg--out" wire:key="message-{{ $message->id }}">
                <div>
                  <div class="msg__bubble">{{ $message->body }}</div>
                  <div class="msg__meta mt-1 text-end">
                    <time datetime="{{ $message->created_at->toIso8601String() }}">{{ $message->created_at->format('H:i') }}</time>@if ($message->read_at) · Seen @endif
                  </div>
                </div>
              </div>
            @else
              <div class="msg msg--in" wire:key="message-{{ $message->id }}">
                <img class="avatar avatar-sm align-self-end"
                     src="{{ $message->sender->profile_photo ? asset('storage/'.$message->sender->profile_photo) : asset('assets/img/avatar-1.svg') }}"
                     alt="{{ $message->sender->name }}">
                <div>
                  <div class="msg__bubble">{{ $message->body }}</div>
                  <div class="msg__meta mt-1">
                    <time datetime="{{ $message->created_at->toIso8601String() }}">{{ $message->created_at->format('H:i') }}</time>
                  </div>
                </div>
              </div>
            @endif
          @endforeach
        </div>

        <form class="d-flex align-items-end gap-1 gap-sm-2 p-2 p-sm-3 border-top" id="chatForm">
          @can('sendMessage', $other)
            <label class="visually-hidden" for="chatInput">Write a message to {{ $other->name }}</label>
            <textarea class="form-control rounded-4" id="chatInput" name="message" rows="1"
                      placeholder="Write a message…"></textarea>

            <button class="btn btn-primary rounded-circle btn-icon" type="submit" aria-label="Send message">
              <i class="bi bi-send-fill" aria-hidden="true"></i>
            </button>
          @else
            <p class="form-control-plaintext text-secondary small text-center mb-0 w-100">
              You can only message friends. Add {{ $other->name }} as a friend to continue this conversation.
            </p>
          @endcan
        </form>
      @else
        <div class="d-flex flex-column align-items-center justify-content-center h-100 text-secondary p-4 text-center">
          <i class="bi bi-chat-dots fs-1" aria-hidden="true"></i>
          <p class="mt-2 mb-0">Select a conversation or start a new one.</p>
        </div>
      @endif
    </div>
  </div>

  <template id="msgOutTemplate">
    <div class="msg msg--out">
      <div>
        <div class="msg__bubble"></div>
        <div class="msg__meta mt-1 text-end"><time></time></div>
      </div>
    </div>
  </template>

  <template id="msgInTemplate">
    <div class="msg msg--in">
      <img class="avatar avatar-sm align-self-end" src="" alt="">
      <div>
        <div class="msg__bubble"></div>
        <div class="msg__meta mt-1"><time></time></div>
      </div>
    </div>
  </template>

  <!-- ===================== New message dialog ===================== -->
  <div class="modal fade" role="dialog" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageLabel"
       aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
      <form class="modal-content" wire:submit="startConversation">
        <div class="modal-header">
          <h2 class="modal-title h5" id="newMessageLabel">New message</h2>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label" for="messageTo">To</label>
            <select class="form-select" id="messageTo" wire:model="newMessageRecipientId" required>
              <option value="">Choose a friend</option>
              @foreach ($this->friends as $friend)
                <option value="{{ $friend->id }}">{{ $friend->name }}</option>
              @endforeach
            </select>
            @error('newMessageRecipientId')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div>
            <label class="form-label" for="messageBody">Message</label>
            <textarea class="form-control" id="messageBody" wire:model="newMessageBody" rows="3"
                      placeholder="Say hello…" required></textarea>
            @error('newMessageBody')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled">Send</button>
        </div>
      </form>
    </div>
  </div>
</div>

@script
<script>
    const feed = () => document.getElementById('chatFeed');

    const scrollFeed = () => {
        const el = feed();

        if (el) {
            el.scrollTop = el.scrollHeight;
        }
    };

    const formatTime = (iso) => new Date(iso).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });

    const appendMessage = (templateId, body, iso, avatar = null, name = '') => {
        const el = feed();

        if (!el) {
            return;
        }

        const node = document.getElementById(templateId).content.cloneNode(true);
        node.querySelector('.msg__bubble').textContent = body;

        const time = node.querySelector('time');
        time.dateTime = iso;
        time.textContent = formatTime(iso);

        const img = node.querySelector('img');
        if (img && avatar) {
            img.src = avatar;
            img.alt = name;
        }

        el.appendChild(node);
        scrollFeed();
    };

    scrollFeed();
    $wire.$watch('selectedConversationId', () => requestAnimationFrame(scrollFeed));

    $wire.el.addEventListener('submit', async (event) => {
        if (!event.target.matches('#chatForm')) {
            return;
        }

        event.preventDefault();

        const input = document.getElementById('chatInput');
        const body = input.value.trim();

        if (!body) {
            return;
        }

        const message = await $wire.sendMessage(body);
        appendMessage('msgOutTemplate', message.body, message.datetime);
        input.value = '';
        input.focus();
    });

    // Enter sends, Shift+Enter inserts a newline.
    $wire.el.addEventListener('keydown', (event) => {
        if (event.target.matches('#chatInput') && event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            event.target.closest('form').requestSubmit();
        }
    });

    $wire.on('close-new-message-modal', () => {
        const modal = document.getElementById('newMessageModal');

        if (modal && window.bootstrap) {
            window.bootstrap.Modal.getOrCreateInstance(modal).hide();
        }
    });

    const userId = document.head.querySelector('meta[name="auth-user-id"]')?.content;

    if (userId && window.Echo) {
        window.Echo.private(`users.${userId}`)
            .listen('.message.created', (message) => {
                const el = feed();

                if (el && Number(el.dataset.conversationId) === message.conversation_id) {
                    appendMessage('msgInTemplate', message.body, message.created_at, message.sender.avatar, message.sender.name);
                    $wire.markConversationRead();
                } else {
                    $wire.$refresh();
                }
            });
    }
</script>
@endscript
