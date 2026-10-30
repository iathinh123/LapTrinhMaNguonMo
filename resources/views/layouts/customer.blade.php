<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chat.css') }}">
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; padding-top: 0; }
        .main-container { max-width: 1280px; margin: 0 auto; }
        main { flex-grow: 1; }
    </style>
    @stack('styles')
</head>
<body data-username="{{ auth()->user()?->TenDangNhap }}">
@include('includes.navbar')

<main class="flex-grow-1">
    <div class="container main-container mt-3">
        @include('includes.flash')
    </div>

    @yield('content')
</main>

@include('includes.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>window.APP = { base: @json(url('/')), csrf: @json(csrf_token()) };</script>
@stack('scripts')
</body>
</html>
