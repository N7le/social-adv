<?php

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

new class extends Component {
    public string $name = '';
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $birthday = '';
    public bool $terms = false;

    public function register(): void
    {
        $fields = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'min:3', 'max:30', 'regex:/^[a-zA-Z0-9_.]+$/', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:12'],
            'birthday' => ['required', 'date', 'before:today'],
            'terms' => ['accepted'],
        ]);

        $user = User::create($fields);

        Auth::login($user);
        request()->session()->regenerate();
        $this->redirect(route('home'));
    }
};
?>

<div class="card auth-card">
    <div class="card-body p-4 p-sm-5">
        <h1 class="h4 mb-1">Create an account</h1>
        <p class="text-secondary mb-4">It takes about a minute.</p>

        <form wire:submit='register' novalidate>
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label" for="regName">Full name</label>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="regName" name="name"
                        value="{{ old('name') }}" autocomplete="name" wire:model="name" required autofocus>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="regUsername">Username</label>
                    <div class="input-group">
                        <span class="input-group-text">@</span>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('username')]) id="regUsername" name="username"
                            value="{{ old('username') }}" pattern="[a-zA-Z0-9_.]{3,30}" autocomplete="username" wire:model='username'
                            required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text">Letters, numbers, dots and underscores. 3–30 characters.</div>
                </div>

                <div class="col-12">
                    <label class="form-label" for="regEmail">Email address</label>
                    <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) id="regEmail" name="email"
                        wire:model="email" value="{{ old('email') }}" autocomplete="email" placeholder="you@example.com" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="regPassword">Password</label>
                    <input type="password" @class(['form-control', 'is-invalid' => $errors->has('password')]) id="regPassword" name="password"
                        wire:model="password" autocomplete="new-password" minlength="12" required>
                    <div class="form-text">At least 12 characters.</div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label" for="regConfirm">Confirm password</label>
                    <input type="password" class="form-control" id="regConfirm" name="password_confirmation"
                        wire:model="password_confirmation" autocomplete="new-password" minlength="12" required>
                </div>

                <div class="col-12">
                    <label class="form-label" for="regBirthday">Date of birth</label>
                    <input type="date" @class(['form-control', 'is-invalid' => $errors->has('birthday')]) id="regBirthday" name="birthday"
                        wire:model="birthday" value="{{ old('birthday') }}" max="{{ now()->subDay()->toDateString() }}" required>
                    @error('birthday')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-check my-4">
                <input @class(['form-check-input', 'is-invalid' => $errors->has('terms')]) type="checkbox" id="regTerms" name="terms" value="1"
                    wire:model="terms" @checked(old('terms')) required>
                <label class="form-check-label" for="regTerms">
                    I agree to the <a class="link-muted" href="#">Terms</a> and
                    <a class="link-muted" href="#">Privacy Policy</a>.
                </label>
                @error('terms')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary w-100 py-2 mb-3" type="submit" wire:loading.attr='disabled' wire:target='register'>Create account</button>

            <p class="divider-text mb-3">already registered?</p>

            <a class="btn btn-light w-100 py-2" href="{{ route('login') }}">Log in instead</a>
        </form>
    </div>
</div>
