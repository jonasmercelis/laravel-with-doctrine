@extends('shared.document')
@section('pageTitle', 'Login')
@section('content')
    <div>
        <h3>Login</h3>
        <form method="post" action="{{ route('login') }}">
            @csrf
            <div>
                <label for="email">Email</label>
                <input
                    autofocus
                    id="email"
                    autocomplete="email"
                    name="email"
                    type="email"
                >
            </div>
            <div>
                <label for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    autocomplete="current-password"
                >
            </div>
            <br>
            <div>
                <input
                    id="remember"
                    name="remember"
                    type="checkbox"
                >
                <label for="remember">Remember</label>
            </div>
            <br>
            <div>
                <input type="submit">
            </div>
            <div>
                @foreach($errors->all() as $error)
                    <div style="color: red;">{{ $error }}</div>
                @endforeach
            </div>
        </form>
    </div>
@endsection
