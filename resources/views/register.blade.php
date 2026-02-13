@extends('layouts.app')
@section('content')

<div class="container">
    <div class="login-card">
        <div class="login-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Glos Bike Vault logo">
        </div>
        <div class="login-form">
            <div class="header">
                <h1>Sign Up</h1>
                <p>Please enter your details</p>
            </div>
            <form method="POST" action="{{ url('/register') }}">
                @csrf
                <input type="text" name="name" id="name" placeholder="Name" value="{{ old('name') }}" required>
                <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
                <button type="submit">Register</button>
                <div class="register">
                    <p>Already have an account?</p>
                    <a href="/login">Login here</a>
                </div>
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