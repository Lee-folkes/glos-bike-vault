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
});