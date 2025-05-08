<script>
    async function sendOTP(){ 
        const response = await fetch("https://hk.herova.net/bids/whatsApp.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
        });

        const data = await response.json();
        if (data.status === "success") {
            window.location.href='bid_otp.php';
            console.log(data);
        } else {
            sendOTP();
        }
    }

    sendOTP();
    
</script>