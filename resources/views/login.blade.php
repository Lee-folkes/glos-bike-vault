@extends('layouts.app')
@section('content')
<div class="login-container">
    <div class="div">
        
    </div>
    <div class="form-container">
        <div class="login-header">
            <h1>Login to the Vault</h1>
        </div>
        <div class="login-form">
            <form id="loginForm">
                <input type="email" name="email" id="email" placeholder="Email" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="/register">Register here</a></p>
            <p id="message"></p>
        </div>

    </div>
    <div class="logo">
        <img src="/images/logo.png" alt="Vault Logo">
    </div>
</div>
@endsection