// Profile specific scripts

document.addEventListener('DOMContentLoaded', () => {
    // Utility to close modals
    const closeAllModals = () => {
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.setAttribute('inert', '');
            modal.style.display = 'none';
        });
    };

    // Global modal close handlers
    document.querySelectorAll('.modal-close').forEach(btn => {
        btn.addEventListener('click', closeAllModals);
    });

    document.querySelectorAll('.modal-overlay').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeAllModals();
        });
    });

    // Password Reset Modal
    const passwordModal = document.getElementById('passwordModal');
    const openPasswordBtn = document.getElementById('openPasswordBtn');
    
    if (openPasswordBtn && passwordModal) {
        openPasswordBtn.addEventListener('click', () => {
            passwordModal.removeAttribute('inert');
            passwordModal.style.display = 'flex';
        });
    }

    // 2FA Disable Confirmation Modal
    const disable2faModal = document.getElementById('disable2faModal');
    const openDisable2faBtn = document.getElementById('openDisable2faBtn');
    
    if (openDisable2faBtn && disable2faModal) {
        openDisable2faBtn.addEventListener('click', () => {
            disable2faModal.removeAttribute('inert');
            disable2faModal.style.display = 'flex';
        });
    }

    // 2FA Enable Confirmation Modal (QR Code)
    const enable2faModal = document.getElementById('enable2faModal');
    // We check if this modal exists and show it auto if it has a dataset flag
    if (enable2faModal && enable2faModal.dataset.show === 'true') {
        enable2faModal.removeAttribute('inert');
        enable2faModal.style.display = 'flex';
    }
});