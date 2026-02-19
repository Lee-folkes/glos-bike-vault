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