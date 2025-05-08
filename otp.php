<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- icon -->
    <link rel="icon" href="assets/img/logo.png">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- sweet alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/register.css">
    <link rel="stylesheet" href="assets/css/otp.css">

    <!-- JS -->
    <script defer src="assets/js/otpController.js" type="module"></script>
    <script defer src="assets/js/components/theme.js"></script>
</head>

<body>
    <div class="register-container">
        <!-- Left Section -->

        <div class="otp-container">
            <div class="otp">

                <h1>OTP Verification</h1>
                <p>Enter the code we sent you to your e-mail here</p>
                <div class="otp-input">
                    <input type="number" min="0" max="9" required>
                    <input type="number" min="0" max="9" required>
                    <input type="number" min="0" max="9" required>
                    <input type="number" min="0" max="9" required>

                </div>
                <div class="resend-text">
                    Didn't receive the code?
                    <button disabled class="resend-link">Resend
                        Code
                        <span id="timer"></span>
                    </button>

                </div>
                <button id="verify">Verify</button>
            </div>
        </div>

        <!-- Right Section -->
        <div class="image-container">
            <img src="assets/img/register.png" alt="side Section Image">
        </div>
    </div>

</body>

</html>