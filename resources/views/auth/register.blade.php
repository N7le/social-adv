<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Create your YouBee Social account.">
  <meta name="theme-color" content="#d97706">
  <title>Create account · YouBee Social</title>

  <link rel="icon" href="{{ asset('assets/img/logo.svg') }}" type="image/svg+xml">
  <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha384-XGjxtQfXaH2tnPFa9x+ruJTuLE3Aa6LhHSWRr1XeTyhezb4abCG4ccI5AkVDxqC+" crossorigin="anonymous">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
  <a class="visually-hidden-focusable btn btn-primary position-absolute top-0 start-0 m-2 z-3" href="#main">Skip to main content</a>

  <main class="auth-page" id="main">
    <div class="container-xxl py-5">
      <div class="row gy-5 gx-4 align-items-center justify-content-center">

        <div class="col-12 col-lg-6 text-center text-lg-start">
          <a class="d-inline-flex align-items-center gap-2 text-decoration-none mb-3" href="{{ route('home') }}">
            <img src="{{ asset('assets/img/logo.svg') }}" alt="" width="52" height="52">
            <span class="auth-pitch__title">YouBee</span>
          </a>
          <div class="auth-pitch mx-auto mx-lg-0">
            <p class="fs-4 text-balance mb-0">
              Share what you're making, keep up with the people you like, and leave the noise behind.
            </p>
          </div>
        </div>

        <div class="col-12 col-lg-6 d-flex justify-content-center justify-content-lg-end">
          <div class="card auth-card">
            <div class="card-body p-4 p-sm-5">
              <h1 class="h4 mb-1">Create an account</h1>
              <p class="text-secondary mb-4">It takes about a minute.</p>

              <form action="{{ route('register.store') }}" method="post" novalidate>
                <div class="row g-3">
                  <div class="col-12">
                    <label class="form-label" for="regName">Full name</label>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="regName" name="name"
                           value="{{ old('name') }}" autocomplete="name" required autofocus>
                    @error('name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-12">
                    <label class="form-label" for="regUsername">Username</label>
                    <div class="input-group">
                      <span class="input-group-text">@</span>
                       <input type="text" @class(['form-control', 'is-invalid' => $errors->has('username')]) id="regUsername" name="username"
                         value="{{ old('username') }}" pattern="[a-zA-Z0-9_.]{3,30}" autocomplete="username" required>
                       @error('username')
                         <div class="invalid-feedback">{{ $message }}</div>
                       @enderror
                    </div>
                    <div class="form-text">Letters, numbers, dots and underscores. 3–30 characters.</div>
                  </div>

                  <div class="col-12">
                    <label class="form-label" for="regEmail">Email address</label>
                          <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) id="regEmail" name="email"
                            value="{{ old('email') }}" autocomplete="email" placeholder="you@example.com" required>
                          @error('email')
                       <div class="invalid-feedback">{{ $message }}</div>
                          @enderror
                  </div>

                  <div class="col-12">
                    <label class="form-label" for="regPassword">Password</label>
                    <input type="password" @class(['form-control', 'is-invalid' => $errors->has('password')]) id="regPassword" name="password"
                           autocomplete="new-password" minlength="12" required>
                    <div class="form-text">At least 12 characters.</div>
                    @error('password')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="col-12">
                    <label class="form-label" for="regConfirm">Confirm password</label>
                          <input type="password" class="form-control" id="regConfirm" name="password_confirmation"
                           autocomplete="new-password" minlength="12" required>
                  </div>

                  <div class="col-12">
                    <label class="form-label" for="regBirthday">Date of birth</label>
                    <input type="date" @class(['form-control', 'is-invalid' => $errors->has('birthday')]) id="regBirthday" name="birthday"
                           value="{{ old('birthday') }}" max="{{ now()->subDay()->toDateString() }}" required>
                    @error('birthday')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>

                <div class="form-check my-4">
                  <input @class(['form-check-input', 'is-invalid' => $errors->has('terms')]) type="checkbox" id="regTerms" name="terms" value="1" @checked(old('terms')) required>
                  <label class="form-check-label" for="regTerms">
                    I agree to the <a class="link-muted" href="#">Terms</a> and
                    <a class="link-muted" href="#">Privacy Policy</a>.
                  </label>
                  @error('terms')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <button class="btn btn-primary w-100 py-2 mb-3" type="submit">Create account</button>

                <p class="divider-text mb-3">already registered?</p>

                <a class="btn btn-light w-100 py-2" href="{{ route('login') }}">Log in instead</a>
              </form>
            </div>
          </div>
        </div>
      </div>

      <x-footer centered class="mt-5" />
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
