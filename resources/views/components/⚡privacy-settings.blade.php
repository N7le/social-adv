<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

new class extends Component {
    public string $posts_audience = 'public';
    public string $request_audience = 'everyone';

    public function mount(): void
    {
        $user = Auth::user();

        $this->posts_audience = $user->posts_audience;
        $this->request_audience = $user->request_audience;
    }

    public function save(): void
    {
        $fields = $this->validate([
            'posts_audience' => ['required', 'in:public,private'],
            'request_audience' => ['required', 'in:everyone,friends_of_friends'],
        ]);

        Auth::user()->update($fields);

        session()->flash('privacy-status', 'Your privacy settings have been updated.');
    }
};
?>

<form class="card" wire:submit="save" novalidate>
    <div class="card-body">
        <h2 class="h5 mb-1">Privacy</h2>
        <p class="text-secondary small mb-4">Decide who can find you and see what you share.</p>

        @if (session('privacy-status'))
            <div class="alert alert-success" role="status">{{ session('privacy-status') }}</div>
        @endif

        <div class="setting-row pt-0">
            <div class="setting-row__text">
                <label class="fw-semibold mb-0" for="privPosts">Who can see your future posts</label>
                <p class="text-secondary small mb-0">This becomes the default audience in the post composer.</p>
            </div>
            <div class="setting-row__control">
                <select @class(['form-select', 'w-auto', 'is-invalid' => $errors->has('posts_audience')]) id="privPosts" wire:model="posts_audience" required>
                    <option value="public">Public</option>
                    <option value="private">Friends</option>
                </select>
                @error('posts_audience')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="setting-row">
            <div class="setting-row__text">
                <label class="fw-semibold mb-0" for="privRequests">Who can send you friend requests</label>
                <p class="text-secondary small mb-0">Limits who sees the Add friend button on your profile.</p>
            </div>
            <div class="setting-row__control">
                <select @class(['form-select', 'w-auto', 'is-invalid' => $errors->has('request_audience')]) id="privRequests" wire:model="request_audience" required>
                    <option value="everyone">Everyone</option>
                    <option value="friends_of_friends">Friends of friends</option>
                </select>
                @error('request_audience')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="card-footer bg-transparent d-flex justify-content-end gap-2 py-3">
        <button class="btn btn-primary px-4" type="submit" wire:loading.attr="disabled" wire:target="save">Save changes</button>
    </div>
</form>