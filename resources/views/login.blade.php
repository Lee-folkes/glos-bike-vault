@extends('layouts.app')
@section('content')
<h1>Login to the Vault</h1>
    <form id="loginForm">
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="/register">Register here</a></p>
    <p id="message"></p>
@endsection