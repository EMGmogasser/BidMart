<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>account</title>
    <link rel="icon" href="assets/img/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/otp.css">
    <link rel="stylesheet" href="assets/css/account.css">
    <link rel="stylesheet" href="assets/css/signup_bid.css">
    <style>
        .otp-container {
            height: 100% !important;
            margin: 75px 0;
            place-self: center;
            background: var(--background);
            border-radius: 15px;
            box-shadow: 1px 6px 7px 2px #0000002e;
            min-width: 270px;
            max-width: 550px;
            display: block;
            place-items: center;
        }

        .otp-container h2 {
            margin-bottom: 15px;
            color: var(--primary);
        }
        .fa-university{
            position: absolute;
            top: 30%;
            left: 10px;
            color: #5c5c5c;
        }
        form{
            text-align: -webkit-center;
        }
        @media (max-width:767px) {
            .otp-container {
                width: 80%;
            }
        }
    </style>
    <style>
        .news{
            visibility: hidden;
        }
    </style>
</head>

<body>

    <?php include_once "header.php"; ?>
    <div class="register-container">
        <div class="form" id="toggle1">
            <form id="signup" action="" method="post">
                <div class="input-container">
                    <i class="far fa-university"></i>
                    <select style="min-width: 210px;" name="bank_name" id="bank_name" required>
                        <option value="Bank ELbalaad">Bank ELbalaad</option>
                        <option value="Bank ELbalaad">Bank ELbalaad</option>
                    </select>
                </div>
                <div class="input-container">
                    <i class="fal fa-globe-africa"></i>
                    <input type="text" style="min-width: 210px;" name="country" id="country" placeholder="country" value="saudi arabia" autocomplete="on" required>
                </div>
                <div class="input-container">
                    <i class="fa-brands fa-cc-visa"></i>
                    <input type="text" style="min-width: 210px;" name="iban" id="iban" placeholder="IBAN" value="5*** **** **** 4756" autocomplete="on" required>
                </div>
                <a class="button1" type="submit">change</a>
                <p>powered by <a href="herova.net" style="color:#0B8A00;">Herova</a></p>
        </div>
    </div>

    </div>
    <?php include_once "footer.php"; ?>

</body>

</html>