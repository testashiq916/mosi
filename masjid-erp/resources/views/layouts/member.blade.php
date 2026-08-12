<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Member Portal')</title>
</head>
<body>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <main>
        @yield('content')
    </main>
</body>
</html>
