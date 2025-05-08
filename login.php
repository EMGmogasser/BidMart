<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- icon -->
    <link rel="icon" href="assets/img/logo.png">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- sweet alert -->
    <script defer src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/register.css">

    
    <style>
        .reset{
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
<script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script defer type="module" src="assets/js/login.js"></script>

</head>

<body>
    <div class="register-container">
        <!-- Left Section -->
        <div class="form-container">
            <img src="assets/img/logo.png" alt="PeakMart Logo" class="logo">
            <form class="login-data" id="loginForm">
                <h1>Welcome to PeakMart!</h1>
                <div class="input-group">
                    <i class="fa fa-envelope"></i>
                    <input id="email" type="email" placeholder="Email Address" required autocomplete="email">
                    <div id="email" class="status-messages">
                        <p class="invalid" id="email">Only Gmail , Outlook or icloud
                            emails allowed</p>
                    </div>
                </div>
                <div class="input-group">
                    <i class="fa fa-lock"></i>
                    <input id="password" type="password" placeholder="Password" required>
                    <i class="fa fa-eye toggle-password"></i>
                    <div id="password" class="status-messages">
                        <p class="invalid " id="eight_letters">Includes at least 8
                            characters</p>
                        <p class="invalid " id="upper_lower">Includes lowercase and
                            uppercase letters</p>
                        <p class="invalid " id="special">Includes at least one special
                            character</p>
                    </div>
                    <a class="reset" href="reset_pass.php">Forgot password? </a>
                </div>
                <button type="submit" class="signup-btn" id="submit-btn">Log in</button>
            </form>
            <p class="login-text">don't have an account? <a href="register.php">Sign up</a></p>

        </div>

        <!-- Right Section -->
        <div class="image-container">
            <img src="assets/img/register.png" alt="side Section Image">
        </div>
    </div>
    
</body>

</html>