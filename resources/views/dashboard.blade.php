@extends('layouts.app')
@push('styles')
    @vite('resources/css/pages/dashboard.css')
@endpush
@section('content')

<section class="toolbar-section">
        <div class="container toolbar-container">
            <div class="search-group">
                <input type="text" class="search-input" placeholder="Search bikes...">
            </div>

            <select class="filter-select">
                <option value="">Status: All</option>
                <option value="active">Active / Safe</option>
                <option value="stolen">Reported Stolen</option>
                <option value="sold">Sold / Archived</option>
            </select>

            <select class="filter-select">
                <option value="newest">Sort: Newest</option>
                <option value="oldest">Sort: Oldest</option>
            </select>

            <button class="btn btn-primary">+ Register New Bike</button>
        </div>
    </section>

            


@endsection
