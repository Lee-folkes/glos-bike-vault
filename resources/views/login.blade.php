@extends('layouts.app')
@section('content')

<div class="container">
    <div class="login-card">
        <div class="login-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Glos Bike Vault logo">
        </div>
        <div class="login-form">
            <div class="header">
                <h1>Welcome back</h1>
                <p>Please enter your details!!</p>
            </div>
            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <button type="submit">Login</button>
                <div class="register">
                    <p>Don't have an account?</p>
                    <a href="/register">Register here</a>
                </div>
                @if (session('status'))
                    <p style="color: green; margin-top: 10px;">{{ session('status') }}</p>
                @endif
                @if ($errors->any())
                    <div style="color: red; margin-top: 10px;">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </form>        
        </div> 
    </div>
</div>
@endsection