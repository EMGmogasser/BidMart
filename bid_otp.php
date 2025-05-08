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
            margin: 70px 0;
            place-self: center;
            background: var(--background);
            border-radius: 15px;
            box-shadow: 1px 6px 7px 2px #0000002e;
            min-width: 270px;
            max-width: 550px;
        }

        .otp p {
            margin-bottom: 20px;
        }

        button#verify {
            font-size: 22px;
        }

        @media (max-width:767px) {
            .otp-container {
                width: 80%;
            }
        }

        @media (max-width: 460px) {
            .otp-input input {
                width: 45px;
                height: 45px;
            }
        }

        .news {
            visibility: hidden;
        }
    </style>
</head>

<body>
    <?php include_once "header.php"; ?>
    <div class="register-container">
        <div class="otp-container">
            <div class="otp">
                <h1>OTP Verification</h1>
                <p>Enter the code we sent you on WhatsApp to <span id="phone-number">[Loading...]</span> <a href="edit_phone.php">Edit</a></p>

                <div class="otp-input">
                    <input type="number" min="0" max="9" required>
                    <input type="number" min="0" max="9" required>
                    <input type="number" min="0" max="9" required>
                    <input type="number" min="0" max="9" required>
                </div>

                <div class="resend-text">
                    Didn't receive the code?
                    <button disabled class="resend-link">Resend Code <span id="timer"></span></button>
                </div>

                <button id="verify">Verify</button>
            </div>
        </div>
    </div>
    <?php include_once "footer.php"; ?>

    <script>
document.addEventListener("DOMContentLoaded", fetchPhoneNumber);

function getCookie(name) {
    let match = document.cookie.match(new RegExp("(^| )" + name + "=([^;]+)"));
    return match ? decodeURIComponent(match[2]) : null;
}
document.querySelector(".resend-link").addEventListener("click", () => {
    sendOTP();  // Call sendOTP function to resend the OTP
    startResendTimer();  // Restart the timer after sending
});

function fetchPhoneNumber() {
    const phoneNumber = getCookie("PHONE");
    document.getElementById("phone-number").textContent = phoneNumber ? phoneNumber : "[Phone number not found]";
}

async function sendOTP() {
    try {
        const phoneNumber = getCookie("PHONE");

        const response = await fetch("https://hk.herova.net/bids/whatsApp.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ phone: phoneNumber }) // إرسال رقم الهاتف
        });

        const data = await response.json();
        if (data.status === "success") {
            console.log("✅ OTP Sent Successfully");
            startResendTimer();
        } else {
            console.error("❌ OTP Sending Failed:", data.message || "Unknown error");
        }
    } catch (error) {
        console.error("Error sending OTP:", error);
    }
}

document.querySelectorAll(".otp-input input").forEach((input, index, inputs) => {
    input.addEventListener("input", (e) => {
        if (e.target.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
    });

    input.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && !e.target.value && index > 0) {
            inputs[index - 1].focus();
        }
    });
});

let resendTimeout = 30;
let resendInterval;

function startResendTimer() {
    const resendButton = document.querySelector(".resend-link");
    const timerSpan = document.getElementById("timer");
    resendButton.disabled = true;

    if (resendInterval) {
        clearInterval(resendInterval);
    }

    resendInterval = setInterval(() => {
        timerSpan.textContent = `(${resendTimeout}s)`;
        resendTimeout--;
        if (resendTimeout < 0) {
            clearInterval(resendInterval);
            resendButton.disabled = false;
            timerSpan.textContent = "";
            resendTimeout = 30;
        }
    }, 1000);
}

async function verifyOTP() {
    const otpInputs = document.querySelectorAll(".otp-input input");
    const otpCode = Array.from(otpInputs).map(input => input.value).join("");

    if (otpCode.length !== 4) return;

    try {
        const response = await fetch("https://hk.herova.net/bids/verify.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ otp: otpCode })
        });

        const data = await response.json();
        if (data.status === "success") {
            Swal.fire({
                icon: "success",
                title: "Verification Successful",
                text: "You will be redirected shortly...",
                timer: 2000,
                showConfirmButton: false
            });
            setTimeout(() => {
                window.location.href = "hold.php";
            }, 2000);
        } else {
            Swal.fire({
                icon: "error",
                title: "Invalid OTP",
                text: "Please try again."
            });
        }
    } catch (error) {
        console.error("Error verifying OTP:", error);
    }
}

document.getElementById("verify").addEventListener("click", verifyOTP);
document.querySelector(".resend-link").addEventListener("click", () => {
    if (resendTimeout <= 0) {
        sendOTP();
    }
});

// بدء المؤقت عند تحميل الصفحة
startResendTimer();
      // Handle pasting full OTP into one field
document.querySelector(".otp-input").addEventListener("paste", function (e) {
    e.preventDefault();

    const pastedData = e.clipboardData.getData("text").trim();
    const inputs = document.querySelectorAll(".otp-input input");

    if (/^\d{4}$/.test(pastedData)) {
        pastedData.split("").forEach((char, i) => {
            if (inputs[i]) {
                inputs[i].value = char;
            }
        });

        // Focus last field to trigger any validation
        inputs[inputs.length - 1].focus();
    }
});

    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>