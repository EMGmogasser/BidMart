<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Payment</title>
    <link rel="icon" href="assets/img/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Leaflet map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- css -->
    <link rel="stylesheet" href="assets/css/place_bid.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- JS -->
     <script defer type='module' src='assets/js/seller_payment.js'></script>

    <style>
        .popup-container {
            height: calc(100vh - 90px);
            max-height: 1000px;
            padding-block: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .popup-content {
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

        .popup-content h2 {
            margin-bottom: 15px;
            color: var(--primary);
            font-size: 2.5rem;
        }

        .popup-content p {
            font-size: 1.7rem;
        }

        .fees{
            color: green;
            text-decoration:underline;
        }

        @media (max-width:767px) {
            .popup-content {
                width: 80vw;
                padding:25px
            }
            .popup-container {
            max-height:400px;
            }
            .popup-content h2 {
            font-size:1.7rem; 
            }
            .popup-content p {
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
    <!-- header -->
    <?php include "header.php"?>
    <!-- loader -->
    <div class="full-c" style="display: none;">
        <div id="loader" style="display: block;">
            <div class="spinner"></div>
            <p> Loading . . .</p>
        </div>
    </div>
    <!-- popup -->
    <div class="popup-container">
        <div class="popup-content">
            <h2>Payment step</h2>
            <P>last step before finally uploading your product is paying fees estimated by <span class="fees basic">5$</span> for delivery and advertisement,<br>
            <span class="fees tax">5$</span> as tax <br><span class="fees total">10$</span> as a total
            </P>
            <br>
            <button class="primary-btn proceed" >proceed</button>
        </div>
    </div>
    <!--  -->

    <!-- footer -->
    <?php  include "footer.php"?>

</body>
</html>