// JavaScript for the dashboard page functionality
// This file is included in the dashboard Blade template and handles
// interactions such as search, filter, and sort for the bike cards.

// Get references to key DOM elements
const modal = document.getElementById('registerBikeModal');
const modalTitle = document.getElementById('registerBikeModalLabel');
const form = document.getElementById('registerBikeForm');
const formMethod = document.getElementById('formMethod');
const storeUrl = form.getAttribute('action'); // original POST url
const BikeSubmitBtn = document.getElementById('BikeSubmitBtn');

// Field names that map to form input names
const bikeFields = [
    'nickname', 'brand', 'model', 'type', 'mpn',
    'wheel_size', 'colour', 'num_gears', 'brake_type',
    'suspension', 'gender', 'age_group'
];

/**
 * Open the modal in "Register" mode — empty form, POST action.
 */
function openRegisterModal() {
    modalTitle.textContent = 'Register New Bike';
    BikeSubmitBtn.textContent = 'Register Bike';
    form.action = storeUrl;
    formMethod.value = 'POST';
    form.reset();
    modal.removeAttribute('inert');
    modal.removeAttribute('hidden');
}

/**
 * Open the modal in "Edit" mode — pre-filled form, PUT action.
 */
function openEditModal(bikeId, bikeData) {
    modalTitle.textContent = 'Edit Bike';
    BikeSubmitBtn.textContent = 'Update Bike';
    form.action = storeUrl + '/' + bikeId;   // /bikes/{id}
    formMethod.value = 'PUT';

    const bikeImageInput = form.querySelector('[name="bike_image"]');
    if (bikeImageInput) {
        bikeImageInput.value = '';
    }

    // Populate each form field with the bike's current data
    bikeFields.forEach(function(field) {
        const input = form.querySelector('[name="' + field + '"]');
        if (input) {
            input.value = bikeData[field] || '';
        }
    });

    modal.removeAttribute('inert');
    modal.removeAttribute('hidden');
}

function closeModal() {
    modal.setAttribute('inert', '');
    modal.setAttribute('hidden', '');
}

// "Register New Bike" button
document.getElementById('registerBikeBtn').addEventListener('click', openRegisterModal);

// Close modal via close button
document.getElementById('closeModalBtn').addEventListener('click', closeModal);

// Close modal when clicking on overlay
modal.addEventListener('click', function(e) {
    if (e.target === modal) {
        closeModal();
    }
});

// Edit buttons on each bike card
document.querySelectorAll('.action-edit').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const bikeId = this.getAttribute('data-bike-id');
        const bikeData = JSON.parse(this.getAttribute('data-bike'));
        openEditModal(bikeId, bikeData);
    });
});

// Status menu buttons on each bike card
function openStatusMenu(event) {
    event.stopPropagation();
    const menu = this.closest('.bike-card').querySelector('.bike-card-status-menu');
    if (menu) {
        menu.style.visibility = 'visible';
        menu.style.opacity = '1';
    }
}

document.querySelectorAll('.action-status').forEach(function(btn) {
    btn.addEventListener('click', openStatusMenu);
});

// Close status menu when clicking outside of it
document.addEventListener('click', function(e) {
    if (!e.target.closest('.bike-card-status-menu') && !e.target.closest('.action-status')) {
        document.querySelectorAll('.bike-card-status-menu').forEach(function(menu) {
            menu.style.visibility = 'hidden';
            menu.style.opacity = '0';
        });
    }
});

// Delete menu buttons on each bike card
function openDeleteMenu(event) {
    const menu = this.closest('.bike-card').querySelector('.bike-card-delete-menu');
    if (menu) {
        menu.style.visibility = 'visible';
        menu.style.opacity = '1';
    }
}

document.querySelectorAll('.action-delete').forEach(function(btn) {
    btn.addEventListener('click', openDeleteMenu);
});

// Close delete menu when clicking outside of it
document.addEventListener('click', function(e) {
    if (!e.target.closest('.bike-card-delete-menu') && !e.target.closest('.action-delete')) {
        document.querySelectorAll('.bike-card-delete-menu').forEach(function(menu) {
            menu.style.visibility = 'hidden';
            menu.style.opacity = '0';
        });
    }
});

// Close delete menu when clicking "Cancel"
document.querySelectorAll('.delete-cancel').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const menu = this.closest('.bike-card-delete-menu');
        if (menu) {
            menu.style.visibility = 'hidden';
            menu.style.opacity = '0';
        }
    });
});

// Status option buttons — send PATCH request to update bike status
// Skip .action-report buttons; those open a confirmation modal instead
document.querySelectorAll('.status-option:not(.action-report)').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const bikeId = this.getAttribute('data-bike-id');
        const newStatus = this.getAttribute('data-status');
        const card = this.closest('.bike-card');
        const menu = card.querySelector('.bike-card-status-menu');

        fetch('/bikes/' + bikeId + '/status', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ status: newStatus }),
        })
        .then(function(response) {
            if (!response.ok) throw new Error('Status update failed');
            return response.json();
        })
        .then(function(data) {
            // Update the status badge in the card header
            var badges = card.querySelectorAll('.bike-card-header .bike-card-type');
            var statusBadge = badges.length > 1 ? badges[badges.length - 1] : badges[0];
            if (statusBadge) {
                statusBadge.className = 'bike-card-type status-' + data.status;
                statusBadge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
            }

            // Hide the status menu and the hover actions overlay
            if (menu) {
                menu.style.visibility = 'hidden';
                menu.style.opacity = '0';
            }
            var actions = card.querySelector('.bike-card-actions');
            if (actions) {
                actions.style.visibility = 'hidden';
                actions.style.opacity = '0';
            }

            // Suppress the CSS :hover rule until the mouse leaves the card
            card.classList.add('hide-actions');
            card.addEventListener('mouseleave', function handler() {
                card.classList.remove('hide-actions');
                if (actions) {
                    actions.style.removeProperty('visibility');
                    actions.style.removeProperty('opacity');
                }
                card.removeEventListener('mouseleave', handler);
            });
        })
        .catch(function(error) {
            console.error('Error updating status:', error);
            alert('Failed to update bike status. Please try again.');
        });
    });
});

// Report Stolen: Leaflet map instance and marker
var reportMap = null;
var reportMarker = null;

// Open Report Stolen modal
document.querySelectorAll('.action-report').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();  // prevent status-menu close handler
        const bikeId = this.getAttribute('data-bike-id');
        const bikeData = JSON.parse(this.getAttribute('data-bike'));
        const modal = document.getElementById('reportStolenModal');
        modal.querySelector('#reportBikeId').value = bikeId;
        modal.querySelector('#reportBikeNickname').textContent = bikeData.nickname || 'this bike';
        modal.querySelector('#reportStolenForm').action = '/bikes/' + bikeId + '/status';

        // Reset location fields and map when opening modal
        document.getElementById('theftLocation').value = '';
        document.getElementById('reportLatitude').value = '';
        document.getElementById('reportLongitude').value = '';
        document.getElementById('locationFeedback').style.display = 'none';
        document.getElementById('reportMap').style.display = 'none';

        // Destroy previous map instance if it exists
        if (reportMap) {
            reportMap.remove();
            reportMap = null;
            reportMarker = null;
        }

        modal.removeAttribute('inert');
        modal.removeAttribute('hidden');
    });
});

// Close Report Stolen modal
function closeReportModal() {
    const reportModal = document.getElementById('reportStolenModal');
    reportModal.setAttribute('inert', '');
    reportModal.setAttribute('hidden', '');
}
document.getElementById('closeReportModalBtn').addEventListener('click', closeReportModal);

// Close Report Stolen modal when clicking on overlay
document.getElementById('reportStolenModal').addEventListener('click', function(e) {
    if (e.target === this) closeReportModal();
});

// Geocode location using OpenStreetMap Nominatim API
document.getElementById('getLocationBtn').addEventListener('click', function() {
    var locationInput = document.getElementById('theftLocation');
    var query = locationInput.value.trim();
    var feedback = document.getElementById('locationFeedback');
    var mapContainer = document.getElementById('reportMap');

    if (!query) {
        feedback.textContent = 'Please enter a location to search.';
        feedback.style.display = 'block';
        feedback.style.color = 'red';
        return;
    }

    feedback.textContent = 'Searching for location...';
    feedback.style.display = 'block';
    feedback.style.color = '';

    fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query) + '&limit=1', {
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(function(response) {
        if (!response.ok) throw new Error('Geocoding request failed');
        return response.json();
    })
    .then(function(results) {
        if (!results || results.length === 0) {
            feedback.textContent = 'Location not found. Please try a different search term.';
            feedback.style.color = 'red';
            return;
        }

        var place = results[0];
        var lat = parseFloat(place.lat);
        var lng = parseFloat(place.lon);

        // Store coordinates in hidden fields
        document.getElementById('reportLatitude').value = lat;
        document.getElementById('reportLongitude').value = lng;

        // Update feedback with found location
        feedback.textContent = 'Location found: ' + place.display_name;
        feedback.style.color = 'green';

        // Show the map container
        mapContainer.style.display = 'block';

        // Initialize or update the Leaflet map
        if (!reportMap) {
            reportMap = L.map('reportMap').setView([lat, lng], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(reportMap);
            reportMarker = L.marker([lat, lng]).addTo(reportMap);
        } else {
            reportMap.setView([lat, lng], 15);
            reportMarker.setLatLng([lat, lng]);
        }

        // Fix map rendering in modal (tiles may not load until invalidateSize is called)
        setTimeout(function() {
            reportMap.invalidateSize();
        }, 200);
    })
    .catch(function(error) {
        console.error('Geocoding error:', error);
        feedback.textContent = 'Failed to search for location. Please try again.';
        feedback.style.color = 'red';
    });
});

// PATCH request to report bike as stolen and update last known location
document.getElementById('reportStolenForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var bikeId = document.getElementById('reportBikeId').value;
    var lat = document.getElementById('reportLatitude').value;
    var lng = document.getElementById('reportLongitude').value;
    var locationText = document.getElementById('theftLocation').value.trim();

    // Build the last_location string: "lat,lng" if coordinates are available, otherwise the raw text
    var lastLocation = '';
    if (lat && lng) {
        lastLocation = lat + ',' + lng;
    } else if (locationText) {
        lastLocation = locationText;
    }

    fetch('/bikes/' + bikeId + '/status', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: JSON.stringify({ status: 'stolen', last_location: lastLocation }),
    })
    .then(function(response) {
        if (!response.ok) {
            return response.text().then(function(text) {
                console.error('Server response:', text);
                throw new Error('Failed to report bike as stolen');
            });
        }
        return response.json();
    })
    .then(function(data) {
        closeReportModal();
        window.location.reload();
    })
    .catch(function(error) {
        console.error('Error reporting bike as stolen:', error);
        alert('Failed to report bike as stolen. Please try again.');
    });
});