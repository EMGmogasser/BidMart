<head>
    <meta charset="utf-8" />
    <title>Reciet</title>
    <link rel="icon" href="assets/img/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">

    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- QR code cdn -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.1/build/qrcode.min.js"></script>

    <script defer type="module" src='assets/js/reciet.js'></script>
    <style>
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
         }
        .logo{
            display: flex;
            justify-content: center;
        }
        .logo img{
            width: 90px;
        }
        .reciet,.details ul{
            display:flex;
            flex-direction:column;
            gap:15px;
        }
        .reciet{
            width: 50%;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            box-shadow: var(--shadow2);
            padding: 20px;
            border-radius: 10px;
        }
        ul{
            list-style-type: none;
        }
        li{
            display:flex;
            justify-content:space-between;
        }
        .status{
            color:green;
            text-align:center;
            text-transform: uppercase;
        }
        .DECLINED,.DUPLICATE{
            color:red;
        }
        button{
            text-align:center;
            margin: auto;
            width: 150px;
        }
        @media (max-width: 767px) {
            .reciet{
                width: 90%;
            }
        }
        @media (max-width: 500px) {
            li{
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }
        }
        button:disabled{
            background-color:gray;
        }
        button:disabled:hover{
            background-color:gray;
        }
        .qr-code-container{
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body>
    <!-- loader -->
    <div class="full-c" style="">
        <div id="loader" style="display: block;">
            <div class="spinner"></div>
            <p> Loading . . .</p>
        </div>
    </div>
    <div class="reciet">
        <div class="logo"><img src="assets/img/logo.png" alt=""></div>
        <h2 class="status"></h2>
        <div class="qr-code-container"></div>  
        <div class="details">
            <ul> 
                <li>
                    <p>User name</p>
                    <p class="user"></p>
                </li>
                <li>
                    <p>Paymeny ID</p>
                    <p class="id"></p>
                </li>
                <li>
                    <p>Amount</p>
                    <p class="amount"></p>
                </li>
                <li>
                    <p>fees</p>
                    <p class="fees"></p>
                </li>
                <li>
                    <p>Total</p>
                    <p class="total"></p>
                </li>
            </ul>
        </div>
        <button  class="primary-btn proceed" disabled>Proceed</button>
    </div>
</body>
