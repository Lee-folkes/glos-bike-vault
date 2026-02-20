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

    <!-- Bike Cards Section -->
    <section class="bikes-section">
        <div class="container">
            <div class="bikes-section-header">
                <div class="h2">Your Registered Bikes</div>
            </div>

            <!-- If no bikes are registered, show empty state message -->
            @if($bikes->isEmpty())
                <div class="bikes-empty">
                    <p>You haven't registered any bikes yet.</p>
                    <p>Click <strong>+ Register New Bike</strong> to get started.</p>
                </div>

            <!-- Else loop through registered bikes and display each in a card format -->
            @else
                <div class="bikes-grid">
                    @foreach($bikes as $bike)
                        <div class="bike-card">

                            <!--Action buttons for edit, status change, and delete (only shown on hover) -->
                            <!-- TODO Add accessible labels and keyboard support for these buttons -->
                            <div class="bike-card-actions">
                                <button class="action-btn action-edit" title="Edit" 
                                data-bike-id="{{ $bike->id }}"
                                data-bike="{{ json_encode($bike->only(['nickname','brand','model','type','mpn','wheel_size','colour','num_gears','brake_type','suspension','gender','age_group'])) }}">
                                    <i class="bx bx-edit-alt"></i>
                                    <span>Edit</span>
                                </button>
                                <button class="action-btn action-status" title="Change Status" data-bike-id="{{ $bike->id }}">
                                    <i class="bx bx-alert-triangle"></i>
                                    <span>Status</span>
                                </button>
                                <button class="action-btn action-delete" title="Delete" data-bike-id="{{ $bike->id }}">
                                    <i class="bx bx-trash"></i>
                                    <span>Delete</span>
                                </button>
                            </div>

                            <!--status change menu (only shown on hover) -->
                            <!-- TODO Add accessible labels and keyboard support for these buttons -->
                            <div class="bike-card-status-menu">
                                <button class="status-option" data-status="active" data-bike-id="{{ $bike->id }}">
                                    <i class="bx bx-check"></i>
                                    <span>Mark as Active / Safe</span>
                                </button>
                                <button class="status-option" data-status="stolen" data-bike-id="{{ $bike->id }}">
                                    <i class="bx bx-x"></i>
                                    <span>Report as Stolen</span>
                                </button>
                                <button class="status-option" data-status="sold" data-bike-id="{{ $bike->id }}">
                                    <i class="bx bx-archive"></i>
                                    <span>Mark as Sold / Archived</span>
                                </button>
                            </div>

                            <!-- Bike name, type and status display -->
                            <div class="bike-card-header">
                                <h3 class="bike-card-name">{{ $bike->nickname }}</h3>
                                <span class="bike-card-type">{{ ucfirst($bike->type) }}</span>
                                <span class="bike-card-type status-{{ $bike->status }}">{{ ucfirst($bike->status) }}</span>
                            </div>

                            <!-- Remaining bike details -->
                            <div class="bike-card-body">
                                <div class="bike-card-detail">
                                    <span class="detail-label">Brand</span>
                                    <span class="detail-value">{{ $bike->brand }}</span>
                                </div>
                                <div class="bike-card-detail">
                                    <span class="detail-label">Model</span>
                                    <span class="detail-value">{{ $bike->model }}</span>
                                </div>
                                <div class="bike-card-detail">
                                    <span class="detail-label">MPN</span>
                                    <span class="detail-value">{{ $bike->mpn }}</span>
                                </div>
                                <div class="bike-card-detail">
                                    <span class="detail-label">Colour</span>
                                    <span class="detail-value">{{ $bike->colour }}</span>
                                </div>
                                <div class="bike-card-detail">
                                    <span class="detail-label">Wheel Size</span>
                                    <span class="detail-value">{{ $bike->wheel_size }}"</span>
                                </div>
                                <div class="bike-card-detail">
                                    <span class="detail-label">Gears</span>
                                    <span class="detail-value">{{ $bike->num_gears }}</span>
                                </div>
                                <div class="bike-card-detail">
                                    <span class="detail-label">Brakes</span>
                                    <span class="detail-value">{{ ucfirst($bike->brake_type) }}</span>
                                </div>
                                <div class="bike-card-detail">
                                    <span class="detail-label">Suspension</span>
                                    <span class="detail-value">{{ ucfirst($bike->suspension) }}</span>
                                </div>
                            </div>

                            <!-- Footer with gender, age group and registration date -->
                            <div class="bike-card-footer">
                                <span class="bike-card-meta">{{ ucfirst($bike->gender) }} · {{ ucfirst($bike->age_group) }}</span>
                                <span class="bike-card-date">Registered {{ $bike->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

 <!-- Register new bike modal -->
<div class="modal-overlay" id="registerBikeModal" aria-labelledby="registerBikeModalLabel" inert>
    <div class="modal-dialog">
        <div class="modal-header">
            <h2 class="modal-title" id="registerBikeModalLabel">Register New Bike</h2>
            <button type="button" class="modal-close" id="closeModalBtn" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <form id="registerBikeForm" method="POST" action="{{ route('bikes.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="form-group form-row-full">
                    <label for="bikeNick">Bike Nickname</label>
                    <input name="nickname" type="text" id="bikeNick" placeholder="Enter bike nickname" required>
                </div>

                <div class="form-group">
                    <label for="bikeBrand">Brand</label>
                    <input name="brand" type="text" id="bikeBrand" placeholder="Enter brand" required>
                </div>
                <div class="form-group">
                    <label for="model">Model</label>
                    <input name="model" type="text" id="model" placeholder="Enter model" required>
                </div>

                <div class="form-group">
                    <label for="bikeType">Bike Type</label>
                    <select name="type" id="bikeType" required>
                        <option value="">Select Type</option>
                        <option value="road">Road Bike</option>
                        <option value="mountain">Mountain Bike</option>
                        <option value="hybrid">Hybrid Bike</option>
                        <option value="electric">Electric Bike</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="mpnNumber">MPN / Serial Number</label>
                    <input name="mpn" type="text" id="mpnNumber" placeholder="Enter MPN number" required>
                </div>

                <div class="form-group">
                    <label for="wheelSize">Wheel Size (inches)</label>
                    <input name="wheel_size" type="text" id="wheelSize" placeholder="Enter wheel size" required>
                </div>
                <div class="form-group">
                    <label for="colour">Colour</label>
                    <input name="colour" type="text" id="colour" placeholder="Enter colour" required>
                </div>

                <div class="form-group">
                    <label for="numGears">Number of Gears</label>
                    <input name="num_gears" type="text" id="numGears" placeholder="Enter number of gears" required>
                </div>
                <div class="form-group">
                    <label for="brakeType">Brake Type</label>
                    <input name="brake_type" type="text" id="brakeType" placeholder="Enter brake type" required>
                </div>

                <div class="form-group">
                    <label for="bikeSuspension">Suspension Type</label>
                    <select name="suspension" id="bikeSuspension" required>
                        <option value="">Select Type</option>
                        <option value="full">Full Suspension</option>
                        <option value="hardtail">Hardtail</option>
                        <option value="none">None</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="bikeGender">Gender</label>
                    <select name="gender" id="bikeGender" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="unisex">Unisex</option>
                    </select>
                </div>

                <div class="form-group form-row-full">
                    <label for="ageGroup">Age Group</label>
                    <select name="age_group" id="ageGroup" required>
                        <option value="">Select Age Group</option>
                        <option value="child">Child</option>
                        <option value="teen">Teen</option>
                        <option value="adult">Adult</option>
                    </select>
                </div>

                <button type="submit" class="form-row-full" id="BikeSubmitBtn">Register Bike</button>
            </form>
        </div>
    </div>

    


@endsection
