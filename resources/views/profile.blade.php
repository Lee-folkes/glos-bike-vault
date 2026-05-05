@extends('layouts.app')

@push('styles')
    @vite(['resources/css/pages/dashboard.css', 'resources/css/pages/profile.css', 'resources/js/pages/profile.js'])
@endpush

@section('content')
<div class="container profile-container">
    <div class="bikes-section-header profile-page-header">
        <div class="h2">Profile Settings</div>
    </div>

    <div class="bikes-grid profile-grid-container">
        <!-- Profile Info Section -->
            <div class="bike-card profile-card">
                <div class="profile-card-header">
                    <h2 class="profile-section-heading">Profile Information</h2>
                </div>
                
                <form method="POST" action="{{ route('user-profile-information.update') }}" class="profile-form-centered">
                    @csrf
                    @method('PUT')
                    
                    <div class="profile-form-row">
                        <div class="form-group profile-form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <p class="profile-form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group profile-form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <p class="profile-form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="profile-form-actions">
                        <button type="submit" class="status-option profile-btn-auto">
                            Save Changes
                        </button>
                        
                        @if (session('status') == 'profile-information-updated')
                            <p class="profile-inline-message">
                                Profile updated successfully.
                            </p>
                        @endif
                    </div>
                </form>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: var(--spacing-lg);">
                <!-- Password Section -->
                <div class="bike-card profile-card">
                    <div class="profile-card-header">
                        <h2 class="profile-section-heading">Security</h2>
                    </div>

                    @if (session('status') == 'password-updated')
                        <div class="profile-status-message">
                            Password updated successfully.
                        </div>
                    @endif

                    <button type="button" id="openPasswordBtn" class="status-option profile-btn-auto">
                        Change Password
                    </button>
                </div>

                <!-- 2FA Section -->
                <div class="bike-card profile-card">
                    <div class="profile-card-header-flex">
                        <h2 class="profile-section-heading">Two-Factor Authentication</h2>
                        @if (auth()->user()->two_factor_secret && auth()->user()->two_factor_confirmed_at)
                            <span class="bike-card-type status-active" >Enabled</span>
                        @else
                            <span class="bike-card-type status-stolen" >Disabled</span>
                        @endif
                    </div>

                    @if (auth()->user()->two_factor_secret && !auth()->user()->two_factor_confirmed_at)
                        <button type="button" class="status-option profile-btn-highlight" onclick="document.getElementById('enable2faModal').removeAttribute('inert'); document.getElementById('enable2faModal').style.display='flex';">
                            Finish 2FA Setup
                        </button>
                    @elseif (auth()->user()->two_factor_secret)
                        <!-- Disable 2FA -->
                        <button type="button" id="openDisable2faBtn" class="status-option profile-btn-stolen">
                            Disable 2FA
                        </button>
                    @else
                        <!-- Enable 2FA Form (Triggers redirect) -->
                        <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                            @csrf
                            <button type="submit" class="status-option profile-btn-auto">
                                Enable 2FA
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->

    <!-- Password Reset Modal -->
    <div class="modal-overlay" id="passwordModal" @if(!$errors->updatePassword->any()) inert style="display: none;" @endif>
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Change Password</h3>
                <button type="button" class="modal-close" aria-label="Close dialog"><i class='bx bx-x'></i></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('user-password.update') }}" class="profile-form-centered">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group form-row-full">
                        <label for="current_password">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required>
                        @error('current_password', 'updatePassword')
                            <p class="profile-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group form-row-full">
                        <label for="password">New Password</label>
                        <input type="password" name="password" id="password" required>
                        @error('password', 'updatePassword')
                            <p class="profile-form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group form-row-full">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required>
                    </div>

                    <button type="submit" class="status-option form-row-full profile-modal-btn-disabled" style="background-color: var(--colour-highlight-secondary); margin-top: var(--spacing-base);">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Disable 2FA Modal -->
    <div class="modal-overlay" id="disable2faModal" inert style="display: none;">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title">Disable Two-Factor Authentication</h3>
                <button type="button" class="modal-close" aria-label="Close dialog"><i class='bx bx-x'></i></button>
            </div>
            <div class="modal-body">
                <p class="form-row-full profile-2fa-modal-text">
                    Are you sure you want to disable two-factor authentication? This will make your account less secure.
                </p>
                <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="status-option form-row-full profile-modal-btn-disabled">
                        Yes, Disable 2FA
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Enable 2FA Confirmation Modal (QR Code) -->
    @if (session('status') == 'two-factor-authentication-enabled' || (auth()->user()->two_factor_secret && !auth()->user()->two_factor_confirmed_at))
        <div class="modal-overlay" id="enable2faModal" data-show="{{ (session('status') == 'two-factor-authentication-enabled' || $errors->confirmTwoFactorAuthentication->any()) ? 'true' : 'false' }}" @if(session('status') != 'two-factor-authentication-enabled' && !$errors->confirmTwoFactorAuthentication->any()) inert style="display: none;" @endif>
            <div class="modal-dialog">
                <div class="modal-header">
                    <h3 class="modal-title">Complete 2FA Setup</h3>
                    <button type="button" class="modal-close" aria-label="Close dialog"><i class='bx bx-x'></i></button>
                </div>
                <div class="modal-body">
                    <div class="form-row-full profile-2fa-modal-center">
                        <p class="profile-2fa-modal-text">
                            Scan this QR code with your authenticator app (Google Authenticator, Authy, etc.):
                        </p>
                        <div class="qr-code profile-qr-container">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>
                    </div>

                    <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}" class="form-row-full profile-form-centered" style="display: block;">
                        @csrf
                        <div class="form-group">
                            <label for="code" class="profile-label-center">Enter the 6-digit code</label>
                            <input type="text" name="code" id="code" placeholder="xxxxxx" required autofocus autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" class="profile-input-code">
                            @error('code', 'confirmTwoFactorAuthentication')
                                <p class="profile-form-error-center">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="profile-form-submit-container">
                            <button type="submit" class="status-option profile-btn-verify">
                                Verify & Enable
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
