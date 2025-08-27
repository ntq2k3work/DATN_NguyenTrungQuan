<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
    @yield('script')
    @vite('resources/css/app.css')
    @yield('link-css')
    <title>@yield('title', 'My Laravel App')</title>
</head>
<body>
    <header>
        @include('partials.header')
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        @include('partials.footer')
    </footer>

    @stack('scripts')
</body>
</html>
