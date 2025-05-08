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
    <style>
        .register-container {
            height: calc(100vh - 90px);
            max-height: 1000px;
            padding-block: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .otp-container {
            height: fit-content !important;
            padding: 50px;
            margin: 0;
            place-self: center;
            background: var(--background);
            border-radius: 15px;
            box-shadow: 1px 6px 7px 2px #0000002e;
            min-width: 270px;
            max-width: 600px;
            display: block;
            place-items: center;
        }

        .otp-container h2 {
            margin-bottom: 15px;
            color: var(--primary);
            font-size: 2.5rem;
        }

        .otp-container p {
            font-size: 1.7rem;
        }

        @media (max-width:767px) {
            .otp-container {
                width: 80vw;
                padding:25px
            }
            .register-container {
            max-height:400px;
            }
            .otp-container h2 {
            font-size:1.7rem; 
            }
            .otp-container p {
            font-size:1.2rem; 
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
        <div class="otp-container">
            <h2>time for checking up!</h2>
            <P>we are checking up your application for safety sir, we holding your auction account and soon we email you
                and you can bid for all products you want.</P>

        </div>

    </div>
    <?php include_once "footer.php"; ?>
    <script>
    const id = localStorage.getItem("user_id")
    async function checkAuthority() {
        const promise = await fetch(`https://hk.herova.net/login_API/admin_c.php?userId=${id}`);
        const res = await promise.json();
        console.log(res);
        if (res.admin_activation) window.location.href = "place_bid.php";
    }
    checkAuthority();
    </script>

</body>

</html>