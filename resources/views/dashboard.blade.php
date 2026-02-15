@extends('layouts.app')
@push('styles')
    @vite('resources/css/pages/dashboard.css')
@endpush
@section('content')

<div class="header">
    <h1>Welcome, {{ auth()->user()->name }}!</h1>
</div>

            


@endsection
