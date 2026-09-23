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
        <livewire:register/>
        </div>
      </div>

      <x-footer centered class="mt-5" />
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
