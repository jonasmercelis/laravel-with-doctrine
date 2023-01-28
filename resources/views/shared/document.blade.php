<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @hasSection('pageTitle')
        <title>{{ config('app.name') }} - @yield('pageTitle')</title>
    @else
        <title>{{ config('app.name') }}</title>
    @endif

</head>
<body>
<div>
    <div style="display: flex; justify-content: space-between;">
        @guest
            <div></div>
            <div>
                <a href="{{ route('login') }}">Login</a>
            </div>
        @endguest
        @auth
            <div>
                <b>Logged in as: </b> {{ auth()->user() }}
            </div>
            <div>
                <a href="#">Logout</a>
            </div>
        @endauth
    </div>
    <div>
        @yield('content')
    </div>
</div>
</body>
</html>
