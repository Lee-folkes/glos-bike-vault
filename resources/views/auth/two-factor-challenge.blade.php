@extends('layouts.guest')
@push('styles')
    @vite('resources/css/pages/login_register.css')
@endpush
@section('content')

<div class="container">
    <div class="login-card">
        <div class="login-form">
            <div class="header">
                <h1>Two-Factor Authentication</h1>
                <p>Please confirm access to your account by entering the authentication code provided by your authenticator application.</p>
            </div>
            <form id="twoFactorForm" method="POST" action="{{ url('/two-factor-challenge') }}">
                @csrf
                <input type="text" name="code" id="code" placeholder="Authentication Code" required autofocus autocomplete="one-time-code">
                <button type="submit">Verify</button>
                <p id="message"></p>
            </form>
            
            @if ($errors->any())
                <div style="color: red; margin-top: 15px;">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif
        </div> 
    </div>
</div>
@endsection
