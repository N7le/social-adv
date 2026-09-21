<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Connect and share with YouBee Social.')">
    <meta name="theme-color" content="#d97706">
    <title>@yield('title', 'YouBee Social')</title>

    <link rel="icon" href="{{ asset('assets/img/logo.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" integrity="sha384-XGjxtQfXaH2tnPFa9x+ruJTuLE3Aa6LhHSWRr1XeTyhezb4abCG4ccI5AkVDxqC+" crossorigin="anonymous">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <a class="visually-hidden-focusable btn btn-primary position-absolute top-0 start-0 m-2 z-3" href="#main">Skip to main content</a>

    <x-navbar :active-page="trim($__env->yieldContent('activePage', 'home'))" />

    <div class="container-xxl app-shell">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="status">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3">
            <x-sidebar :active-page="trim($__env->yieldContent('activePage', 'home'))" />
            @yield('content')
        </div>
    </div>

    @include('partials.app-actions', ['activePage' => trim($__env->yieldContent('activePage', 'home'))])

    @stack('modals')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    @stack('scripts')
</body>

</html>
