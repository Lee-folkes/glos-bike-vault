@extends('layouts.guest')
@push('styles')
    @vite('resources/css/pages/login_register.css')
@endpush
@section('content')

<div class="container">
    <div class="login-card">
        <div class="login-form">
            <div class="header">
                <h1>Setup Two-Factor Authentication</h1>
                <p>Scan this QR code with your authenticator app (Google Authenticator, Authy, etc.)</p>
            </div>

            <div class="qr-code">
                {!! auth()->user()->twoFactorQrCodeSvg() !!}
            </div>

            <p>Enter the 6-digit code from your authenticator app to confirm setup:</p>

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
        </div> 
    </div>
</div>

<style>


</style>
@endsection
