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
}

/**
 * Open the modal in "Edit" mode — pre-filled form, PUT action.
 */
function openEditModal(bikeId, bikeData) {
    modalTitle.textContent = 'Edit Bike';
    BikeSubmitBtn.textContent = 'Update Bike';
    form.action = storeUrl + '/' + bikeId;   // /bikes/{id}
    formMethod.value = 'PUT';

    // Populate each form field with the bike's current data
    bikeFields.forEach(function(field) {
        const input = form.querySelector('[name="' + field + '"]');
        if (input) {
            input.value = bikeData[field] || '';
        }
    });

    modal.removeAttribute('inert');
}

function closeModal() {
    modal.setAttribute('inert', '');
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

// Status option buttons — send PATCH request to update bike status
document.querySelectorAll('.status-option').forEach(function(btn) {
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