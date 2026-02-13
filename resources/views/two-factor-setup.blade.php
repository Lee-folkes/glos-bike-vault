@extends('layouts.app')
@section('content')

<div class="container">
    <div class="login-card">
        <div class="login-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Glos Bike Vault logo">
        </div>
        <div class="login-form">
            <div class="header">
                <h1>Setup Two-Factor Authentication</h1>
                <p>Scan this QR code with your authenticator app (Google Authenticator, Authy, etc.)</p>
            </div>

            <div class="qr-code" style="text-align: center; margin: 20px 0;">
                {!! auth()->user()->twoFactorQrCodeSvg() !!}
            </div>

            <p style="text-align: center; color: var(--colour-light-primary);">Enter the 6-digit code from your authenticator app to confirm setup:</p>

            <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}">
                @csrf
                <input type="text" name="code" id="code" placeholder="6-digit code" required autofocus autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6">
                <button type="submit">Confirm & Continue</button>

                @if ($errors->any())
                    <div style="color: red; margin-top: 10px; text-align: center;">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </form>

            <div class="recovery-codes" style="margin: 20px 0;">
                <h3>Recovery Codes</h3>
                <p><strong>Save these recovery codes in a secure location. You can use them if you lose your authenticator device.</strong></p>
                <ul style="list-style: none; padding: 0; font-family: monospace; background: #f5f5f5; padding: 15px; border-radius: 4px;">
                    @foreach (json_decode(decrypt(auth()->user()->two_factor_recovery_codes), true) as $code)
                        <li style="margin: 5px 0;">{{ $code }}</li>
                    @endforeach
                </ul>
            </div>
        </div> 
    </div>
</div>

<style>
    .qr-code {
        display: inline-block;
        background: #ffffff;
        padding: 16px;
        border-radius: 8px;
    }

    .qr-code svg {
        display: block;
        width: 200px;
        height: 200px;
    }
</style>
@endsection
