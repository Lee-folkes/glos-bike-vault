document.addEventListener('DOMContentLoaded', () => {
    const infoButtons = document.querySelectorAll('.action-info');
    const infoModal = document.getElementById('infoBikeModal');
    const closeBtn = document.getElementById('closeInfoModalBtn');

    // Utility to capitalize first letter
    const capitalize = (str) => str ? str.charAt(0).toUpperCase() + str.slice(1) : '';

    infoButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const bike = JSON.parse(this.getAttribute('data-bike'));
            
            // Populate Basic Info
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
            
            // Format Status label
            const statusEl = document.getElementById('infoBikeStatus');
            statusEl.textContent = capitalize(bike.status);
            statusEl.className = 'bike-card-type status-' + bike.status;

            // Handle Stolen Info
            document.getElementById('infoBikeStolenAt').textContent = bike.stolen_at ? new Date(bike.stolen_at).toLocaleDateString() : 'Unknown';
            document.getElementById('infoBikeLocation').textContent = bike.last_location || 'Unknown';

            // Meta Footer
            document.getElementById('infoBikeMeta').textContent = `${capitalize(bike.gender)} · ${capitalize(bike.age_group)}`;

            // Handle Image
            const imgEl = document.getElementById('infoBikeImage');
            if (bike.img_path) {
                imgEl.src = `/storage/${bike.img_path}`;
                imgEl.style.display = 'block';
            } else {
                imgEl.style.display = 'none';
                imgEl.src = '';
            }

            // Show Modal
            infoModal.hidden = false;
            infoModal.removeAttribute('inert');
        });
    });

    // Close modal handling
    closeBtn.addEventListener('click', () => {
        infoModal.hidden = true;
        infoModal.setAttribute('inert', '');
    });

    // Logic for map button


    const mapButtons = document.querySelectorAll('.action-map');
    const adminMapModal = document.getElementById('adminMapModal');
    const closeMapBtn = document.getElementById('closeMapModalBtn');
    const mapFallbackText = document.getElementById('adminMapFallbackText');
    const mapContainer = document.getElementById('adminMapContainer');
    
    let adminMap = null;
    let adminMarker = null;

    mapButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const bike = JSON.parse(this.getAttribute('data-bike'));
            const locationStr = bike.last_location || '';
            
            adminMapModal.hidden = false;
            adminMapModal.removeAttribute('inert');

            // Reset view
            mapFallbackText.style.display = 'none';
            mapContainer.style.display = 'none';

            // Clean up previous map instance
            if (adminMap) {
                adminMap.remove();
                adminMap = null;
            }

            // Check if last_location looks like "lat,lng" coordinates
            const coords = locationStr.split(',');
            if (coords.length === 2 && !isNaN(parseFloat(coords[0])) && !isNaN(parseFloat(coords[1]))) {
                const lat = parseFloat(coords[0]);
                const lng = parseFloat(coords[1]);

                mapContainer.style.display = 'block';

                adminMap = L.map('adminMapContainer').setView([lat, lng], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(adminMap);
                
                adminMarker = L.marker([lat, lng]).addTo(adminMap);

                // Fix loading issue inside modal (from dashboard.js)
                setTimeout(() => {
                    adminMap.invalidateSize();
                }, 200);

            } else {
                // Not coordinates, just display the raw text (or "Unknown")
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

    // Open Modal
    statusButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            currentBikeIdForStatus = this.getAttribute('data-bike-id');
            statusUpdateModal.hidden = false;
            statusUpdateModal.removeAttribute('inert');
        });
    });

    // Close Modal
    closeStatusModalBtn.addEventListener('click', () => {
        statusUpdateModal.hidden = true;
        statusUpdateModal.setAttribute('inert', '');
        currentBikeIdForStatus = null;
    });

    // Handle Status Update
    statusOptions.forEach(option => {
        option.addEventListener('click', async function() {
            if (!currentBikeIdForStatus) return;
            
            const newStatus = this.getAttribute('data-status');
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';
            
            try {
                // Disable options during loading
                statusOptions.forEach(opt => opt.disabled = true);
                
                const response = await fetch(`/bikes/${currentBikeIdForStatus}/status`, {
                    method: 'PATCH', // Assumes route matches Route::patch('/bikes/{bike}/status')
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                if (response.ok) {
                    // Quickest way to reflect changes and refresh lists: reload page
                    // Alternatively, update DOM elements manually if no reload is preferred
                    window.location.reload();
                } else {
                    console.error('Failed to update status', await response.text());
                    alert('Failed to update status. Please try again.');
                }
            } catch (error) {
                console.error('Error updating status:', error);
                alert('An error occurred. Please check your connection and try again.');
            } finally {
                statusOptions.forEach(opt => opt.disabled = false);
                statusUpdateModal.hidden = true;
                statusUpdateModal.setAttribute('inert', '');
                currentBikeIdForStatus = null;
            }
        });
    });
});
