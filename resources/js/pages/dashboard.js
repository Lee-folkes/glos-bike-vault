// JavaScript for the dashboard page functionality
// This file is included in the dashboard Blade template and handles
// interactions such as search, filter, and sort for the bike cards.



//Modal functionality for adding a new bike
const modal = document.getElementById('registerBikeModal');

// Event listener for the "Add Bike" button to open the modal
document.getElementById('registerBikeBtn').addEventListener('click', function() {
    modal.removeAttribute('inert');
});

// Close modal when close button is clicked
document.getElementById('closeModalBtn').addEventListener('click', function() {
    modal.setAttribute('inert', '');
});

// Close modal when clicking outside the dialog (on the overlay)
modal.addEventListener('click', function(e) {
    if (e.target === modal) {
        modal.setAttribute('inert', '');
    }
});

// Submit the form when the "Submit" button is clicked
document.getElementById('submitBikeBtn').addEventListener('click', function() {
    document.getElementById('registerBikeForm').submit();
});