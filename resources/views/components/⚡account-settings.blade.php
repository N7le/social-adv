<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Support\AuthSession;
use Livewire\Component;

new class extends Component {
    public string $email = '';
    public string $phone = '';

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public string $delete_password = '';

    public function mount(): void
    {
        $user = Auth::user();

        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
    }

    public function saveAccount(): void
    {
        $user = Auth::user();

        $fields = $this->validate([
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\s-]+$/'],
        ]);

        $user->update([
            'email' => $fields['email'],
            'phone' => $fields['phone'] === '' ? null : $fields['phone'],
        ]);

        session()->flash('account-status', 'Your account details have been updated.');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:12'],
        ]);

        Auth::user()->update(['password' => $this->password]);

        $this->reset('current_password', 'password', 'password_confirmation');

        session()->flash('password-status', 'Your password has been updated.');
    }

    public function deleteAccount(): void
    {
        $this->validate([
            'delete_password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        AuthSession::logout(request());

        $user->delete();

        $this->redirect(route('home'));
    }
};
?>

<div>
    <form class="card mb-3" wire:submit="saveAccount" novalidate>
        <div class="card-body">
            <h2 class="h5 mb-1">Account</h2>
            <p class="text-secondary small mb-4">Contact details and regional preferences.</p>

            @if (session('account-status'))
                <div class="alert alert-success" role="status">{{ session('account-status') }}</div>
            @endif

            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <label class="form-label" for="setEmail">Email address</label>
                    <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) id="setEmail" name="email"
                        wire:model="email" autocomplete="email" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label" for="setPhone">Mobile number</label>
                    <input type="tel" @class(['form-control', 'is-invalid' => $errors->has('phone')]) id="setPhone" name="phone"
                        wire:model="phone" autocomplete="tel" maxlength="30">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent d-flex justify-content-end gap-2 py-3">
            <button class="btn btn-primary px-4" type="submit" wire:loading.attr="disabled" wire:target="saveAccount">Save changes</button>
        </div>
    </form>

    <form class="card mb-3" wire:submit="updatePassword" novalidate>
        <div class="card-body">
            <h2 class="h6 mb-3">Change password</h2>

            @if (session('password-status'))
                <div class="alert alert-success" role="status">{{ session('password-status') }}</div>
            @endif

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label" for="setCurrentPw">Current password</label>
                    <input type="password" @class(['form-control', 'is-invalid' => $errors->has('current_password')]) id="setCurrentPw"
                        name="current_password" wire:model="current_password" autocomplete="current-password" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label" for="setNewPw">New password</label>
                    <input type="password" @class(['form-control', 'is-invalid' => $errors->has('password')]) id="setNewPw"
                        name="password" wire:model="password" autocomplete="new-password" minlength="12" required>
                    <div class="form-text">At least 12 characters.</div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label" for="setConfirmPw">Confirm new password</label>
                    <input type="password" class="form-control" id="setConfirmPw" name="password_confirmation"
                        wire:model="password_confirmation" autocomplete="new-password" minlength="12" required>
                </div>
            </div>
        </div>
        <div class="card-footer bg-transparent d-flex justify-content-end gap-2 py-3">
            <button class="btn btn-primary px-4" type="submit" wire:loading.attr="disabled" wire:target="updatePassword">Update password</button>
        </div>
    </form>

    <section class="card border-danger-subtle" aria-labelledby="dangerZoneHeading">
        <div class="card-body">
            <h2 class="h6 text-danger mb-3" id="dangerZoneHeading">Delete account</h2>
            <div class="setting-row pt-0">
                <div class="setting-row__text">
                    <p class="fw-semibold mb-0">Delete account</p>
                    <p class="text-secondary small mb-0">Permanently removes your posts, photos and messages after 30 days.</p>
                </div>
                <div class="setting-row__control">
                    <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete account</button>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" wire:submit="deleteAccount" novalidate>
                <div class="modal-header">
                    <h3 class="modal-title h6" id="deleteAccountModalLabel">Delete your account?</h3>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-secondary small">
                        This removes your profile, posts and messages. This action cannot be undone.
                        Enter your password to confirm.
                    </p>
                    <label class="form-label" for="deleteAccountPassword">Password</label>
                    <input type="password" @class(['form-control', 'is-invalid' => $errors->has('delete_password')]) id="deleteAccountPassword"
                        name="delete_password" wire:model="delete_password" autocomplete="current-password" required>
                    @error('delete_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger" type="submit" wire:loading.attr="disabled" wire:target="deleteAccount">Delete account</button>
                </div>
            </form>
        </div>
    </div>
</div>
