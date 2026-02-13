import './bootstrap';
import './bootstrap'; // This imports Axios automatically

// Handle the login form submission
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

// Handle User Registration Form Submission
const registerForm = document.getElementById('registerForm');

if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const passwordConfirmation = document.getElementById('password_confirmation').value;
        const messageBox = document.getElementById('message');

        try {
            //Initialize CSRF protection
            await axios.get('/sanctum/csrf-cookie');

            //check if all fields are filled
            if (!name || !email || !password || !passwordConfirmation) {
                messageBox.innerText = "Please fill in all fields!";
                return;
            }

            //check password length & complexity (at least 8 characters, including a number and a special character)
            const passwordRegex = /^(?=.*[0-9])(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{8,}$/;
            if (!passwordRegex.test(password)) {
                messageBox.innerText = "Password must be at least 8 characters long and include a number and a special character!";
                return;
            }

            //check if passwords match before sending the request
            if (password !== passwordConfirmation) {
                messageBox.innerText = "Passwords do not match!";
                return;
            }


            //Send the Registration request
            const response = await axios.post('/api/register', {
                name: name,
                email: email,
                password: password
            });

            messageBox.innerText = "Success! Redirecting...";
            window.location.href = '/login'; // After registration, send them to the login page

        } catch (error) {
            messageBox.innerText = "Registration Failed: " + error.response.data.message;
        }
    });
}


