<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component {
    public string $name = '';
    public string $username = '';
    public string $bio = '';
    public string $work = '';
    public string $education = '';
    public string $city = '';
    public string $website = '';
    public string $birthday = '';

    public function mount(): void
    {
        $this->fillFromUser();
    }

    public function save(): void
    {
        $user = Auth::user();

        $fields = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[a-zA-Z0-9_.]+$/', Rule::unique('users', 'username')->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:160'],
            'work' => ['nullable', 'string', 'max:50'],
            'education' => ['nullable', 'string', 'max:50'],
            'city' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'birthday' => ['required', 'date', 'before:today'],
        ]);

        $user->update(array_map(fn ($value) => $value === '' ? null : $value, $fields));

        session()->flash('profile-status', 'Your profile has been updated.');
    }

    public function cancel(): void
    {
        $this->resetValidation();
        $this->fillFromUser();
    }

    private function fillFromUser(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->username = $user->username;
        $this->bio = $user->bio ?? '';
        $this->work = $user->work ?? '';
        $this->education = $user->education ?? '';
        $this->city = $user->city ?? '';
        $this->website = $user->website ?? '';
        $this->birthday = $user->birthday?->toDateString() ?? '';
    }
};
?>

<form class="card" wire:submit="save" novalidate>
    <div class="card-body">
        <h2 class="h5 mb-1">Profile</h2>
        <p class="text-secondary small mb-4">This is what people see when they open your profile.</p>

        @if (session('profile-status'))
            <div class="alert alert-success" role="status">{{ session('profile-status') }}</div>
        @endif

        <div class="setting-row">
            <div class="setting-row__text">
                <p class="fw-semibold mb-0">Profile picture</p>
                <p class="text-secondary small mb-0">Square images look best.</p>
            </div>
            <div class="setting-row__control d-flex align-items-center gap-3">
                <img class="avatar avatar-xl" src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('assets/img/avatar-1.svg') }}" alt="Your current profile picture">
                <button class="btn btn-light" type="button" data-bs-toggle="modal" data-bs-target="#changeAvatarModal">Change</button>
            </div>
        </div>

        <div class="setting-row">
            <div class="setting-row__text">
                <p class="fw-semibold mb-0">Cover photo</p>
                <p class="text-secondary small mb-0">Always public.</p>
            </div>
            <div class="setting-row__control d-flex align-items-center gap-3">
                <img class="rounded object-fit-cover" src="{{ auth()->user()->cover_photo ? asset('storage/'.auth()->user()->cover_photo) : asset('assets/img/cover.svg') }}" alt="Your current cover photo" width="120" height="38">
                <button class="btn btn-light" type="button" data-bs-toggle="modal" data-bs-target="#changeCoverModal">Change</button>
            </div>
        </div>

        <hr class="my-4">

        <div class="row g-3">
            <div class="col-12 col-sm-6">
                <label class="form-label" for="setName">Display name</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="setName" name="name"
                    wire:model="name" autocomplete="name" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label" for="setUsername">Username</label>
                <div class="input-group">
                    <span class="input-group-text">@</span>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('username')]) id="setUsername" name="username"
                        wire:model="username" pattern="[a-zA-Z0-9_.]{3,30}" autocomplete="username" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-text">Letters, numbers, dots and underscores. 3–30 characters.</div>
            </div>

            <div class="col-12">
                <label class="form-label" for="setBio">Bio</label>
                <textarea @class(['form-control', 'is-invalid' => $errors->has('bio')]) id="setBio" name="bio" rows="3" maxlength="160"
                    wire:model="bio"></textarea>
                <div class="form-text">Up to 160 characters.</div>
                @error('bio')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-sm-6">
                <label class="form-label" for="setWork">Work</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('work')]) id="setWork" name="work"
                    wire:model="work" maxlength="50">
                @error('work')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label" for="setEducation">Education</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('education')]) id="setEducation" name="education"
                    wire:model="education" maxlength="50">
                @error('education')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-sm-6">
                <label class="form-label" for="setCity">Current city</label>
                <input type="text" @class(['form-control', 'is-invalid' => $errors->has('city')]) id="setCity" name="city"
                    wire:model="city" maxlength="100">
                @error('city')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-sm-6">
                <label class="form-label" for="setWebsite">Website</label>
                <input type="url" @class(['form-control', 'is-invalid' => $errors->has('website')]) id="setWebsite" name="website"
                    wire:model="website" placeholder="https://example.com">
                @error('website')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 col-sm-6">
                <label class="form-label" for="setBirthday">Birthday</label>
                <input type="date" @class(['form-control', 'is-invalid' => $errors->has('birthday')]) id="setBirthday" name="birthday"
                    wire:model="birthday" max="{{ now()->subDay()->toDateString() }}" required>
                @error('birthday')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="card-footer bg-transparent d-flex justify-content-end gap-2 py-3">
        <button class="btn btn-light" type="button" wire:click="cancel">Cancel</button>
        <button class="btn btn-primary px-4" type="submit" wire:loading.attr="disabled" wire:target="save">Save changes</button>
    </div>
</form>
