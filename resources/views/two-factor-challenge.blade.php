@extends('layouts.app')
@section('content')

<div class="container">
    <div class="login-card">
        <div class="login-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Glos Bike Vault logo">
        </div>
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
            
            <div style="margin-top: 20px; text-align: center;">
                <p style="margin-bottom: 10px;">Or use a recovery code:</p>
                <form id="recoveryCodeForm" method="POST" action="{{ url('/two-factor-challenge') }}">
                    @csrf
                    <input type="text" name="recovery_code" id="recovery_code" placeholder="Recovery Code" autocomplete="one-time-code">
                    <button type="submit">Use Recovery Code</button>
                </form>
            </div>

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
