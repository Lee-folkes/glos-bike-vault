@extends('layouts.app')
@push('styles')
    @vite('resources/css/pages/dashboard.css')
@endpush
@push ('scripts')
    @vite('resources/js/pages/dashboard.js')
@section('content')

<!-- Toolbar Section:
 Search, filter and sort options for bike cards -->
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

            <button class="btn btn-primary" id="registerBikeBtn">+ Register New Bike</button>
        </div>
    </section>

<!-- Bike Cards Section:
 Display of registered bikes with status indicators -->
 

 
 <!-- Register new bike modal -->
<div class="modal-overlay" id="registerBikeModal" aria-labelledby="registerBikeModalLabel" inert>
    <div class="modal-dialog">
        <div class="modal-header">
            <h2 class="modal-title" id="registerBikeModalLabel">Register New Bike</h2>
            <button type="button" class="modal-close" id="closeModalBtn" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="registerBikeForm">
                <div class="form-group">
                    <label for="bikeName">Bike Name</label>
                    <input type="text" id="bikeName" placeholder="Enter bike name" required>
                </div>
                <div class="form-group">
                    <label for="bikeType">Bike Type</label>
                    <select id="bikeType" required>
                        <option value="">Select Type</option>
                        <option value="road">Road Bike</option>
                        <option value="mountain">Mountain Bike</option>
                        <option value="hybrid">Hybrid Bike</option>
                        <option value="electric">Electric Bike</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="serialNumber">Serial Number</label>
                    <input type="text" id="serialNumber" placeholder="Enter serial number" required>
                </div>
                <!-- Additional fields can be added here -->
                <button type="submit">Register Bike</button>
            </form>
        </div>
    </div>


@endsection
