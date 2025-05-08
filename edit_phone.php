<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Account</title>
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
        .fa-phone-alt{
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
      form h2{
            color: var(--mainColor);
        margin-bottom: 20px;
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
                <h2>Change Phone Number</h2>
                <div class="input-container">
                    <i class="fas fa-phone-alt"></i>
                    <input type="text" name="old_phone" id="old_phone" placeholder="Old Phone Number" required>
                </div>
                <div class="input-container">
                    <i class="fas fa-phone-alt"></i>
                    <input type="text" name="phone" id="phone" placeholder="New Phone Number" required>
                </div>
                <button class="button1" type="submit">Change</button>
                <p>Powered by <a href="https://herova.net" style="color:#0B8A00;">Herova</a></p>
            </form>
        </div>
    </div>

    <?php include_once "footer.php"; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('signup').addEventListener('submit', handleSubmit);
        });

        async function handleSubmit(event) {
            event.preventDefault(); // Prevent default form submission

            const oldPhone = document.getElementById('old_phone').value.trim();
            const phone = document.getElementById('phone').value.trim();

            if (!oldPhone || !phone) {
                alert("Please fill in both the old and new phone numbers.");
                return;
            }

            const formData = new FormData();
            formData.append("PHONE", phone);
            formData.append("OLD_PHONE", oldPhone);

            console.log("Sending FormData:", [...formData.entries()]);

            try {
                const response = await fetch('https://hk.herova.net/data/update_info.php?key=1', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    alert('Phone number updated successfully!');
                } else {
                    alert('Failed to update phone number: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while updating the phone number.');
            }
        }
    </script>

</body>

</html>
