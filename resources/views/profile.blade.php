@extends('layouts.app')
@section('content')

<div class="container">
    <div class="login-card">
        <div class="login-form">
            <div class="header">
                <h1>Profile</h1>
                <p>Welcome, {{ auth()->user()->name }}!</p>
            </div>

            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
</div>
@endsection
