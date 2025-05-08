<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reset password</title>

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- sweet alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/register.css">
    <style>
    .input-group {
        width: 100%;
    }

    .reset {
        text-align: left;
    }

    .reset span {
        color: red;
    }

    .form-container form {
        align-items: center;
    }

    form button {
        width: 50%
    }
    .loginn{
        direction: rtl;
    }
    .image-container {
    left: 0 !important;
    }
    .reset, #email{
        direction: ltr;
    }
    .reset{
  color: var(--color-primary);
    float: right;
    margin: 7px 0;
}
    </style>
</head>

<body>

    <body>
        <div class="login-container">
            <!-- left Section -->
            <div class="image-container">
                <img src="assets/img/register.png" alt="side Section Image">
            </div>
            <!-- right Section -->
            <div class="loginn">
                <img src="assets/img/logo.png" alt="PeakMart Logo" class="logo">
                <div class="form-container">
                    <form class="login-data" id="reset">
                        <h1>Reset your password</h1>
                        <div class="input-group">
                            <i class="fa fa-envelope"></i>
                            <input id="email" type="email" placeholder="Email Address" required autocomplete="email">
                        </div>
                        <a class="reset" ><span>*</span> We will send you a message to set or reset
                            your new
                            password</a>
                        <button type="submit" class="signup-btn" id="submit-btn">Send Code</button>
                    </form>
                </div>

            </div>

        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
       <script>
    document.getElementById('reset').addEventListener('submit', async function (event) {
        event.preventDefault();

        const email = document.getElementById('email').value;
        const apiUrl = 'https://hk.herova.net/login_API/SendToMail.php';

        try {
            const response = await fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email }),
            });

            const data = await response.json();

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Password Reset Email Sent',
                    text: 'Check your inbox for password reset instructions.',
                    footer: 'Powered by <a style="color:#17b928;" href="https://herova.net">Herova</a>',
                }).then(() => {
                    // Redirect to the login page after the user clicks "OK"
                    window.location.href = 'login.php'; // Replace '/login' with your actual login page URL
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'We could not process your request.',
                    footer: 'Powered by <a style="color:#17b928;" href="https://herova.net">Herova</a>',
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong. Please try again later.',
                footer: 'Powered by <a style="color:#17b928;" href="https://herova.net">Herova</a>',
            });
        }
    }); // Closing bracket for addEventListener callback
</script>
    </body>

</html>
