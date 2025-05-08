import { helper } from "./config.js";

async function activateEmail(){
    setTimeout(async ()=>{
        const cookies = await helper.getAllCookies();
        if(cookies.EMAIL) window.location.href ="otp.php";
    },500)
} 

document.getElementById('loginForm').addEventListener('submit', async function (event) {
    event.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const apiUrl = 'https://hk.herova.net/login_API/login-api.php';

    try {
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email, password }),
        });

        const data = await response.json();

        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Login Successful',
                text: data.message ||'Welcome sir/miss!',
                footer: 'powered by <a style="color:#17b928;" href="https://herova.net">Herova</a>',
            });
        // Redirect after a delay
        setTimeout(() => {
                const previousPage = document.referrer || '/default-page'; // Replace '/default-page' with your default URL
                window.location.href = "index.php";
            }, 2000); 
        } else {
            if (data.message === "This account has not been activated yet!") {
                activateEmail();
                return;
            }
            Swal.fire({
                icon: 'error',
                title: 'Login Failed',
                text: data.message || 'Invalid credentials.',
                footer: 'powered by <a style="color:#17b928;" href="https://herova.net">Herova</a>',

            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: data.message ||'Something went wrong. Please try again later.',
            footer: 'powered by <a style="color:#17b928;" href="https://herova.net">Herova</a>',
            
        });
    }
});
    // Toggle password visibility
    document.querySelector('.toggle-password').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        this.classList.toggle('fa-eye-slash');
    });