<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>change password</title>
    <!-- icon -->
    <link rel="icon" href="assets/img/logo.png">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <!-- css -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/register.css">

    <style>
        .reset {
            color: var(--color-primary);
            float: right;
            margin: 7px 0;
        }

        @media (min-width:851px) {
            .register-container {
                justify-content: end;
            }

            .image-container {
                right: unset;
                left: 0
            }

            .login-data {
                margin-top: 30px;
            }
        }
    </style>

    <!-- JS -->
    <script defer src="assets/js/components/theme.js"></script>

</head>

<body>
    <div class="register-container">
        <!-- Left Section -->
        <div class="form-container">
            <img src="assets/img/logo.png" alt="PeakMart Logo" class="logo">
            <form class="login-data" id="change_pass">
                <h1>Change password</h1>
                <div class="input-group">
                    <i class="fa fa-lock"></i>
                    <input id="newPassword" type="password" placeholder="new Password" required>
                    <i class="fa fa-eye toggle-password"></i>
                    <div id="password" class="status-messages">
                        <p class="invalid " id="eight_letters">Includes at least 8
                            characters</p>
                        <p class="invalid " id="upper_lower">Includes lowercase and
                            uppercase letters</p>
                        <p class="invalid " id="special">Includes at least one special
                            character</p>
                    </div>

                </div>
                <div class="input-group">
                    <i class="fa fa-lock"></i>
                    <input id="confirmPassword" type="password" placeholder="confirm Password" required>
                    <i class="fa fa-eye toggle-password"></i>
                    <div id="password" class="status-messages">
                        <p class="invalid " id="eight_letters">Includes at least 8
                            characters</p>
                        <p class="invalid " id="upper_lower">Includes lowercase and
                            uppercase letters</p>
                        <p class="invalid " id="special">Includes at least one special
                            character</p>
                    </div>

                </div>
                <button type="submit" class="signup-btn" id="submit-btn">change</button>
            </form>

        </div>

        <!-- Right Section -->
        <div class="image-container">
            <img src="assets/img/register.png" alt="side Section Image">
        </div>
    </div>




    <script>
  document.getElementById('change_pass').addEventListener('submit', async function(event) {
    event.preventDefault();

    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const apiUrl = 'https://hk.herova.net/login_API/changepass.php';
    const urlParams = new URLSearchParams(window.location.search);
    const token = urlParams.get('token');

    // Basic validation
    if (newPassword !== confirmPassword) {
        Swal.fire({
            icon: 'error',
            title: 'Password Mismatch',
            text: 'The new password and confirm password do not match.',
            footer: 'Powered by <a style="color:#17b928;" href="https://herova.net">Herova</a>',
        });
        return;
    }

    try {
        // Make the API call directly
        const response = await fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                token: token,
                new_password: newPassword,
            }),
        });

        console.log(JSON.stringify({
            token: token,
            new_password: newPassword,
        }));

        const data = await response.json(); // This automatically parses the JSON response.

        console.log('API Response:', data);

        if (response.ok) {
            Swal.fire({
                icon: data.status,
                title: 'Password Changed',
                text: data.message || 'Your password has been successfully updated.',
                footer: 'Powered by <a style="color:#17b928;" href="https://herova.net">Herova</a>',
            });
        } else {
            Swal.fire({
                icon:data.status,
                title: 'Error',
                text: data.message || 'We could not process your request.',
                footer: 'Powered by <a style="color:#17b928;" href="https://herova.net">Herova</a>',
            });
        }
    } catch (error) {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong. Please try again later.',
            footer: 'Powered by <a style="color:#17b928;" href="https://herova.net">Herova</a>',
        });
    }
});

// Toggle password visibility
const togglePasswordIcons = document.querySelectorAll('.toggle-password');

togglePasswordIcons.forEach(icon => {
    icon.addEventListener('click', function() {
        const passwordField = icon.previousElementSibling;

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.add('fa-eye-slash');
            icon.classList.remove('fa-eye');
        } else {
            passwordField.type = 'password';
            icon.classList.add('fa-eye');
            icon.classList.remove('fa-eye-slash');
        }
    });
});
  </script>

</body>

</html>