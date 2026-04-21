@extends('layouts.app')
@push('styles')
    @vite('resources/css/pages/dashboard.css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
@endpush
@push ('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
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
