import './bootstrap';
import './bootstrap'; // This imports Axios automatically

const loginForm = document.getElementById('loginForm');

if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const messageBox = document.getElementById('message');

        try {
            // Step 1: Initialize CSRF protection
            await axios.get('/sanctum/csrf-cookie');

            // Step 2: Send the Login request
            const response = await axios.post('/api/login', {
                email: email,
                password: password
            });

            messageBox.innerText = "Success! Redirecting...";
            window.location.href = '/dashboard'; // Send them to a protected page

        } catch (error) {
            messageBox.innerText = "Login Failed: " + error.response.data.message;
        }
    });
}