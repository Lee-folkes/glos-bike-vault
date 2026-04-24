  <!-- Custom styles for the admin dashboard -->
  @push('styles')
    @vite('resources/css/pages/admin-dashboard.css')
@endpush
<!-- Custom scripts for the admin dashboard -->
@push ('scripts')
    <!-- Custom JS for the dashboard page -->
    @vite('resources/js/pages/admin-dashboard.js')
@endpush
    
  
  <!-- Toolbar Section: Search, filter and sort options for bike cards -->
    <section class="toolbar-section">
            <div class="container toolbar-container">
                <div class="search-group">
                    <input type="text" class="search-input" placeholder="Search bikes...">
                </div>
            
                <input type="date" class="filter-select" name="date_stolen" title="Filter by date stolen" aria-label="Filter by date stolen">

                <select class="filter-select">
                    <option value="newest">Sort: Newest</option>
                    <option value="oldest">Sort: Oldest</option>
                </select>

            </div>
    </section>

<!-- Bike list Section -->
    <section class="bikes-section">
        <div class="container">
            <div class="bikes-section-header">
                <div class="h2">Stolen and Recovered Bikes</div>
            </div>

            <!-- If no bikes are registered, show empty state message -->
            @if($stolenBikes->isEmpty())
                <div class="bikes-empty">
                    <p>No bikes found matching your criteria.</p>
                    <p>Click <strong>+ Register New Bike</strong> to get started.</p>
                </div>

            <!-- Else loop through the stolen bikes array and populate a list view of results -->
            @else
                <div class="bike-table-header">
                    <div>User</div>
                    <div>MPN</div>
                    <div>Status</div>
                    <div>Date Stolen</div>
                    <div>Last Location</div>
                    <div>Actions</div>
                </div>

                <div class="bikes-list">
                    @foreach($stolenBikes as $bike)
                        <div class="bike-card">
                            <div class="bike-card-name" style="font-size: var(--font-size-base)">
                                {{ $bike->user->email }}
                            </div>
                            <div class="detail-value">
                                {{ $bike->mpn ?? 'N/A' }}
                            </div>
                            <div>
                                <span class="bike-card-type status-{{ $bike->status }}">{{ ucfirst($bike->status) }}</span>
                            </div>
                            <div class="detail-value">
                                {{ $bike->stolen_at ? $bike->stolen_at->format('M d, Y') : 'N/A' }}
                            </div>
                            <div class="detail-value">
                                {{ $bike->last_location ?? 'Unknown' }}
                            </div>
                            <div class="admin-actions">
                                <button class="action-btn action-status" title="Change Status" data-bike-id="{{ $bike->id }}">
                                    <i class="bx bx-alert-triangle"></i>
                                <span>Status</span>
                                </button>
                                <button class="action-btn action-info" title="View Info" 
                                    data-bike-id="{{ $bike->id }}"
                                    data-bike="{{ json_encode($bike) }}">
                                    <i class="bx bx-info-circle"></i>
                                    <span>Info</span>
                                </button>
                                <button class="action-btn action-map" title="View Map" data-bike-id="{{ $bike->id }}"
                                    data-bike-id="{{ $bike->id }}"
                                    data-bike="{{ json_encode($bike) }}">
                                    <i class="bx bx-map"></i>
                                    <span>Map</span>
                                </button>
                            </div>
                        </div>
                    @endforeach
           @endif        
    </section>
    <!-- Admin Bike Info Modal -->
<div class="modal-overlay" id="infoBikeModal" aria-labelledby="infoBikeModalLabel" inert hidden>
    <div class="modal-dialog">
        <div class="modal-header">
            <div class="bike-card-header">
                <h3 class="bike-card-name" id="infoBikeName"></h3>
                <span class="bike-card-type" id="infoBikeType"></span>
                <span class="bike-card-type" id="infoBikeStatus"></span>
            </div>
            <button type="button" class="modal-close" id="closeInfoModalBtn" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body bike-card" style="box-shadow: none; border: none; padding: 0;">
            
            <img id="infoBikeImage" src="" alt="Bike Image" class="bike-card-image" style="display: none;">

            <div class="bike-card-body">
                <div class="bike-card-detail"><span class="detail-label">Brand</span><span class="detail-value" id="infoBikeBrand"></span></div>
                <div class="bike-card-detail"><span class="detail-label">Model</span><span class="detail-value" id="infoBikeModel"></span></div>
                <div class="bike-card-detail"><span class="detail-label">MPN</span><span class="detail-value" id="infoBikeMpn"></span></div>
                <div class="bike-card-detail"><span class="detail-label">Colour</span><span class="detail-value" id="infoBikeColour"></span></div>
                <div class="bike-card-detail"><span class="detail-label">Wheel Size</span><span class="detail-value" id="infoBikeWheelSize"></span></div>
                <div class="bike-card-detail"><span class="detail-label">Gears</span><span class="detail-value" id="infoBikeGears"></span></div>
                <div class="bike-card-detail"><span class="detail-label">Brakes</span><span class="detail-value" id="infoBikeBrakes"></span></div>
                <div class="bike-card-detail"><span class="detail-label">Suspension</span><span class="detail-value" id="infoBikeSuspension"></span></div>
                <div class="bike-card-detail"><span class="detail-label">Stolen At</span><span class="detail-value" id="infoBikeStolenAt"></span></div>
                <div class="bike-card-detail"><span class="detail-label">Last Location</span><span class="detail-value" id="infoBikeLocation"></span></div>
            </div>

            <div class="bike-card-footer">
                <span class="bike-card-meta" id="infoBikeMeta"></span>
            </div>
        </div>
    </div>
</div>

<!-- Admin Map Modal -->
<div class="modal-overlay" id="adminMapModal" aria-labelledby="mapModalLabel" inert hidden>
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 class="bike-card-name" id="mapModalLabel">Last Known Location</h3>
            <button type="button" class="modal-close" id="closeMapModalBtn" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <p id="adminMapFallbackText" style="display: none;"></p>
            <!-- Leaflet Map Container -->
            <div id="adminMapContainer" style="height: 300px; width: 100%; display: none;"></div>
        </div>
    </div>
</div>

