@extends('layouts.app')
@push('styles')
    <!-- Custom CSS for the dashboard page -->
    @vite('resources/css/pages/dashboard.css')
    <!-- Leaflet CSS for map display -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
@endpush
@push ('scripts')
    <!-- Leaflet JS for map functionality -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <!-- Custom JS for the dashboard page -->
    @vite('resources/js/pages/dashboard.js')
@endpush
@section('content')

<!-- This section is only shown if the user is a police admin -->
@can('access-admin')

    @include('partials.admin-dashboard')
  
<!-- This section is shown to regular users -->
@else

    @include('partials.user-dashboard')

@endcan

@endsection
