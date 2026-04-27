@extends('layouts.app')
@push('styles')
    @vite('resources/css/pages/login_register.css')
@endpush
@section('content')

<div class="container" style="min-height: calc(100svh - 120px);">
    <div class="login-card">
        <div class="login-form">
            <div class="header">
                <h1>Add Admin User</h1>
                <p>Register a new police administrator</p>
            </div>
            <form method="POST" action="{{ route('admin.store-admin') }}">
                @csrf
                <input type="text" name="name" id="name" placeholder="Name" value="{{ old('name') }}" required>
                <input type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}" required>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
                <button type="submit">Create User</button>
                
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