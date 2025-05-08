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
    <script defer src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- intlTelInput-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <!-- css -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/register.css">

    <!-- JS -->
    <script defer src="assets/js/registerController.js" type="module"></script>
    <script defer src="assets/js/components/theme.js"></script>

</head>

<body>
    <div class="register-container">
        <!-- Left Section -->
        <div class="form-container">
            <img src="assets/img/logo.png" alt="PeakMart Logo" class="logo">
            <form class="register-data">
                <h1>Create An account</h1>
                <div class="input-group">
                    <i class="fa fa-user"></i>
                    <input id="username" name='username' type="text" placeholder="Username" required
                        autocomplete="username">
                    <div id="username" class="status-messages">
                        <p class="invalid" id="start">User name must start with a
                            letter</p>
                        <p class="invalid" id="length">must be longer than 3 letters</p>
                        <p class="invalid" id="letters">Only letters, numbers, or _
                            allowed</p>
                    </div>
                </div>
                <div class="input-group">
                    <i class="fa fa-envelope"></i>
                    <input id="email" type="email" name='email' placeholder="Email Address" required
                        autocomplete="email">
                    <div id="email" class="status-messages">
                        <p class="invalid" id="email">Only Gmail , Outlook or icloud
                            emails allowed</p>
                    </div>
                </div>
                <div class="input-group">
                    <input id="phone" type="tel" name='tel' placeholder="Phone number" required autocomplete="tel"
                        maxlength="11">
                    <div id="phone" class="status-messages">
                        <p class="invalid" id="phone">Enter a valid mobile number</p>
                    </div>
                </div>
                <div class="input-group">
                    <i class="fa fa-lock"></i>
                    <input id="password" type="password" name='password' placeholder="Password" required
                        autocomplete="off">
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
                    <input id="confirm_password" type="password" name='confirm_password' placeholder="Confirm password"
                        required autocomplete="off">
                    <i class="fa fa-eye toggle-password"></i>
                    <div id="confirm_password" class="status-messages">
                        <p class="invalid" id="confirm_password">The two passwords
                            must match</p>
                    </div>
                </div>
                <button type="submit" class="signup-btn" id="submit-btn">Sign
                    up</button>
            </form>
            <p class="login-text">
                already have an account? <a href="login.php">Login</a>
            </p>
        </div>

        <!-- Right Section -->
        <div class="image-container">
            <img src="assets/img/register.png" alt="side Section Image">
        </div>
    </div>

</body>

</html>