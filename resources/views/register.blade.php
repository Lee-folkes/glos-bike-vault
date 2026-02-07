@extends('layouts.app')
@section('content')
<h1>Register for the Vault</h1>
    <form id="registerForm">
        <input type="email" name="email" id="email" placeholder="Email" required>
        <input type="password" name="password" id="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="/login">Login here</a></p>
    <p id="message"></p>
@endsection