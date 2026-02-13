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
                <p>Please enter your details</p>
            </div>
            <form id="loginForm">
                <input type="email" name="email" id="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <button type="submit">Login</button>
                <div class="register">
                    <p>Don't have an account?</p>
                    <a href="/register">Register here</a>
                </div>
            </form>        
            
            <p id="message"></p>
        </div> 
    </div>
</div>
@endsection