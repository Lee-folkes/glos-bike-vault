/**
 * Admin Dashboard View Logic
 * 
 * This script handles the interactive behaviour of the administrator dashboard.
 * It manages the lifecycle of three primary modal dialogues:
 * 1. The Bike Information modal for displaying comprehensive bike details.
 * 2. The Map modal for visualising the last known location of a stolen bike using Leaflet.js.
 * 3. The Status Update modal for securely sending asynchronous state changes to the backend.
 */
document.addEventListener('DOMContentLoaded', () => {
    const infoButtons = document.querySelectorAll('.action-info');
    const infoModal = document.getElementById('infoBikeModal');
    const closeBtn = document.getElementById('closeInfoModalBtn');

    /**
     * Utility function to capitalise the first letter of a string.
     * Useful for formatting lower-case database values (e.g., 'stolen' -> 'Stolen').
     * 
     * @param {string} str - The string to format.
     * @returns {string} The capitalised string.
     */
    const capitalize = (str) => str ? str.charAt(0).toUpperCase() + str.slice(1) : '';

    // --- Bike Information Modal ---
    
    // Attach click events to all 'info' buttons to populate and reveal the details dialogue
    infoButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Retrieve and parse the JSON payload stored in the button's data attribute
            const bike = JSON.parse(this.getAttribute('data-bike'));
            
            // Populate Basic Info fields
            document.getElementById('infoBikeName').textContent = bike.nickname || 'Unknown';
            document.getElementById('infoBikeType').textContent = capitalize(bike.type);
            document.getElementById('infoBikeBrand').textContent = bike.brand;
            document.getElementById('infoBikeModel').textContent = bike.model;
            document.getElementById('infoBikeMpn').textContent = bike.mpn || 'N/A';
            document.getElementById('infoBikeColour').textContent = bike.colour;
            document.getElementById('infoBikeWheelSize').textContent = bike.wheel_size ? `${bike.wheel_size}"` : 'N/A';
            document.getElementById('infoBikeGears').textContent = bike.num_gears;
            document.getElementById('infoBikeBrakes').textContent = capitalize(bike.brake_type);
            document.getElementById('infoBikeSuspension').textContent = capitalize(bike.suspension);
            
            // Format and apply dynamic CSS classes for the Status label
            const statusEl = document.getElementById('infoBikeStatus');
            statusEl.textContent = capitalize(bike.status);
            statusEl.className = 'bike-card-type status-' + bike.status;

            // Handle Stolen metadata (if applicable)
            document.getElementById('infoBikeStolenAt').textContent = bike.stolen_at ? new Date(bike.stolen_at).toLocaleDateString() : 'Unknown';
            document.getElementById('infoBikeLocation').textContent = bike.last_location || 'Unknown';

            // Meta Footer grouping
            document.getElementById('infoBikeMeta').textContent = `${capitalize(bike.gender)} · ${capitalize(bike.age_group)}`;

            // Handle Image display, falling back to hidden if no path exists
            const imgEl = document.getElementById('infoBikeImage');
            if (bike.img_path) {
                imgEl.src = `/storage/${bike.img_path}`;
                imgEl.style.display = 'block';
            } else {
                imgEl.style.display = 'none';
                imgEl.src = '';
            }

            // Expose the modal to the user
            infoModal.hidden = false;
            infoModal.removeAttribute('inert');
        });
    });

    // Standard close event for the Information modal
    closeBtn.addEventListener('click', () => {
        infoModal.hidden = true;
        infoModal.setAttribute('inert', '');
    });

    // --- Location Mapping Modal ---

    const mapButtons = document.querySelectorAll('.action-map');
    const adminMapModal = document.getElementById('adminMapModal');
    const closeMapBtn = document.getElementById('closeMapModalBtn');
    const mapFallbackText = document.getElementById('adminMapFallbackText');
    const mapContainer = document.getElementById('adminMapContainer');
    
    let adminMap = null;
    let adminMarker = null;

    // Attach click events to initialise the map with geospatial data
    mapButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const bike = JSON.parse(this.getAttribute('data-bike'));
            const locationStr = bike.last_location || '';
            
            adminMapModal.hidden = false;
            adminMapModal.removeAttribute('inert');

            // Reset the view state before rendering
            mapFallbackText.style.display = 'none';
            mapContainer.style.display = 'none';

            // Clean up the previous map instance to prevent WebGL context memory leaks
            if (adminMap) {
                adminMap.remove();
                adminMap = null;
            }

            // Check if last_location is a valid set of coordinates (e.g. "51.505,-0.09")
            const coords = locationStr.split(',');
            if (coords.length === 2 && !isNaN(parseFloat(coords[0])) && !isNaN(parseFloat(coords[1]))) {
                const lat = parseFloat(coords[0]);
                const lng = parseFloat(coords[1]);

                mapContainer.style.display = 'block';

                // Initialise Leaflet.js map focused on the fetched coordinates
                adminMap = L.map('adminMapContainer').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(adminMap);
                
                adminMarker = L.marker([lat, lng]).addTo(adminMap);

                // Delay size invalidation slightly to ensure the CSS transition to visible is complete,
                // preventing grey tiles or off-centre maps inside the modal.
                setTimeout(() => {
                    adminMap.invalidateSize();
                }, 200);

            } else {
                // If it's a plain text address rather than coordinates, display it as a fallback
                mapFallbackText.textContent = locationStr ? `Location registered as: ${locationStr}` : 'Location unknown.';
                mapFallbackText.style.display = 'block';
            }
        });
    });

    closeMapBtn.addEventListener('click', () => {
        adminMapModal.hidden = true;
        adminMapModal.setAttribute('inert', '');
    });

    // --- Status Update Modal Logic ---
    
    const statusButtons = document.querySelectorAll('.action-status');
    const statusUpdateModal = document.getElementById('statusUpdateModal');
    const closeStatusModalBtn = document.getElementById('closeStatusModalBtn');
    const statusOptions = document.querySelectorAll('.status-option');
    let currentBikeIdForStatus = null;

    // Attach click events to track the active bike ID
    statusButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            currentBikeIdForStatus = this.getAttribute('data-bike-id');
            statusUpdateModal.hidden = false;
            statusUpdateModal.removeAttribute('inert');
        });
    });

    // Handle closing the status modal and clearing active state
    closeStatusModalBtn.addEventListener('click', () => {
        statusUpdateModal.hidden = true;
        statusUpdateModal.setAttribute('inert', '');
        currentBikeIdForStatus = null;
    });

    // Attach listeners to individual status buttons inside the modal
    statusOptions.forEach(option => {
        option.addEventListener('click', async function() {
            if (!currentBikeIdForStatus) return;
            
            const newStatus = this.getAttribute('data-status');
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
            
            try {
                // Temporarily disable buttons to prevent duplicate submission
                statusOptions.forEach(opt => opt.disabled = true);
                
                // Submit an asynchronous PATCH request to the Laravel backend
                const response = await fetch(`/bikes/${currentBikeIdForStatus}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                if (response.ok) {
                    // Reloading the page cleanly reflects the new status and refreshes Scout sorting
                    window.location.reload();
                } else {
                    console.error('Failed to update status', await response.text());
                    alert('Failed to update status. Please try again.');
                }
            } catch (error) {
                console.error('Error updating status:', error);
                alert('An error occurred. Please check your connection and try again.');
            } finally {
                // Revert disabled states and hide modal on completion
                statusOptions.forEach(opt => opt.disabled = false);
                statusUpdateModal.hidden = true;
                statusUpdateModal.setAttribute('inert', '');
                currentBikeIdForStatus = null;
            }
        });
    });
});
