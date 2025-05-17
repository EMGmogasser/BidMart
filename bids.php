<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bids</title>
    <link href='https://fonts.googleapis.com/css?family=Cabin' rel='stylesheet'>

    <!-- icon -->
    <link rel="icon" href="assets/img/logo.png">

    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Swiper.js CSS CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.0.2/swiper-bundle.min.css">
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.0.2/swiper-bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- css -->
    <link rel="stylesheet" href="assets/css/components/swiper.css">
    <link rel="stylesheet" href="assets/css/components/products.css">
    <link rel="stylesheet" href="assets/css/master.css">
    <link rel="stylesheet" href="assets/css/bids.css">

    <!-- JS -->
    <script defer src="assets/js/components/swiper.js"></script>
    <script defer type='module' src="assets/js/bid_details.js"></script>

    <style>
        #loader{
            height: fit-content;
            max-width: 800px;
            padding-inline: 3% !important;
            font-size: 1.3rem;
        }
        #loader p{
            margin-block:10px;
        }
        #loader p:first-child{
            margin-block: 10% 20px;
        }
        #loader input{
            width: 100%;
            height: 50px;
            background-color: #d9d9d91a;
            color: var(--text-color2);
            border-radius: 14px;
            border: 1px solid var(--background-negative);
            padding: 15px;
            text-align:center;
            font-size: 18px;
            outline: none;
            margin-top: 10px;
        }
        #loader .bidBtn{
            width: 50%;
            padding-block:10px;
        }
        #loader span.price{
            font-weight: bold;
            color: goldenrod;
        }
        .empty{
            height:100%;
            background:unset;
        }
        #loader ul{
            margin-left:15px;
            font-size:0.9rem;
            text-align:start;
            margin-bottom:20px;
        }
        #loader a{
            color:royalblue;
        }
        #loader a:hover{
            text-decoration:underline
        }
    </style>
    <!-- swipers style -->
     <style>
        .images {
            width: 30%;
        }
        .images .mySwiper2{
            box-shadow: var(--shadow);
            border-radius: 15px;
        }
        .images .swiper-button-next,.images .swiper-button-prev{
            height: 100%;
            top: 0;
            right: 0;
            width: 20%;
            margin:unset;
        }
        .images .swiper-button-next:hover,.images .swiper-button-prev:hover{
            background-color: #ffffff8c;
        }
        .images .swiper-button-next:hover:after,.images .swiper-button-prev:hover:after{
            color:var(--primary);
        }
        .images .swiper-button-prev{
            left:0;
        }
        .images .swiper-button-prev:after,.images .swiper-button-next:after{
            font-size:25px;
            font-weight: bolder;
        }
        .swiper-wrapper{
            min-height: unset;
            width: fit-content;
            max-width:100%
        }
        .images .swiper {
            width: 100%;
            height: 100%;
        }

        .images .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .images .mySwiper .swiper-slide img,.images .mySwiper2 {
            border:2px solid var(--primary);

        }
        .images .swiper-slide img {
            border-radius: 15px;
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .images .swiper {
            width: 100%;
            height:fit-content;
            max-height: 350px;
            margin-left: auto;
            margin-right: auto;
        }

        .images .swiper-slide {
            background-size: cover;
            background-position: center;
        }

        .images .mySwiper2 {
            height: 80%;
            width: 100%;
        }

        .images .mySwiper {
            height: 20%;
            box-sizing: border-box;
            padding: 10px 0;
        }

        .images .mySwiper .swiper-slide {
            width: 100px;
            height: 100%;
            max-height:150px;
            opacity: 0.4;
        }

        .images .mySwiper .swiper-slide-thumb-active {
            opacity: 1;
        }

        .images .swiper-slide img {
            display: block;
            height: 100%;
            width: 100%;
            object-fit: cover;
        }

        .images .mySwiper .swiper-slide img {
            max-height:120px;
            width: 100px;
        }
        @media (max-width: 767px) {
            .images {
                width: 70%;
            }
        }
        @media (max-width: 400px) {
            .images .swiper-button-next,.images .swiper-button-prev{
                visibility:hidden;
            }
        }
        button:disabled,button:disabled:hover{
            background:gray;
        }
        </style>
</head>

<body>
    <!-- header -->
    <?php include "header.php"?>

    <!-- poster -->
    <div class="poster">
        <!-- <a class="img loading-img" href="" target="_blank">
            <div class="spinner"></div>
            <img src=""  loading="lazy" style="visibility:hidden;">
        </a> -->
        <div class="images">
            <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper mySwiper2">
                <div class="swiper-wrapper poster-images">
                    
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            <div thumbsSlider="" class="swiper mySwiper">
                <div class="swiper-wrapper poster-thumbnails">
                    
                </div>
            </div>
        </div>
        <div class="content">
            <h2 class="title"></h2>
            <div class="description">
                <span class="fixed">Description : </span>
                <span class="text"> N/A</span>
            </div>
            <div class="start_date">
                <span class="fixed"> Start Date : </span>
                <span class="text">N/A</span>
            </div>
            <div class="start_price">
                <span class="fixed">start bid : </span>
                <span class="text">N/A</span>
            </div>
            <div class="current_price">
                <span class="fixed">current price : </span>
                <span class="text">N/A</span>
            </div>
            <div class="end_date">
                <span class="fixed"> End Date : </span>
                <span class="text">N/A</span>
            </div>
            <div class="enrolls">
                *<span class="text">0</span>
                <span class="fixed">Person enrolled</span>
            </div>
            <div class="bidders_number">
                *<span class="text">0</span>
                <span class="fixed">Bidding process</span>
            </div>
            <div class="actin-buttons">
                <button href="#" class="primary-btn enroll" disabled>Enroll</button>
                <button href="#" class="primary-btn bid"disabled>Bid!</button>
            </div>
        </div>

    </div>

    <!-- modal -->
    <div class="full-c enroll" style="display:none;">
        <div id="loader" style="display: block;">
            <button class="close">x</button>
            <p >The Bid insurance to enroll is <span class="price">200$</span></p>
            <ul class="note">
                <li>you must enter a number larger than the highlighted number</li>
                <li>A tax fee of 5% will be added to the number you enter</li>
                <li>In case you are the highest bidder anw want to cancel 20% of the money won't be refunded</li>
                <li>For more detailes check the <a href="bid_rules.php">bid rules</a> <br>or <a href="contact_us.php">contact us</a></li>
            </ul>
            <!-- <input type="number" name="bid" id="bidAmount" class="bidAmount" placeholder="Your bid">   
            <p style="font-size: 0.9rem;">This price may change after (<span class="timer">7</span>s)</p>  -->
            <button class="enrollBtn primary-btn">Enroll</button>        
        </div>
    </div>
    <div class="full-c bid" style="display:none;">
        <div id="loader" style="display: block;">
            <button class="close">x</button>
            <p >The Bid insurance to enroll is <span class="price">200$</span></p>
            <ul class="note">
                <li>you must enter a number larger than the highlighted number</li>
                <li>A tax fee of 5% will be added to the number you enter</li>
                <li>In case you are the highest bidder anw want to cancel 20% of the money won't be refunded</li>
                <li>For more detailes check the <a href="bid_rules.php">bid rules</a> <br>or <a href="contact_us.php">contact us</a></li>
            </ul>
            <input type="number" name="bid" id="bidAmount" class="bidAmount" placeholder="Your bid">   
            <p style="font-size: 0.9rem;">This price may change after (<span class="timer">7</span>s)</p> 
            <button class="bidBtn primary-btn">Bid</button>        
        </div>
    </div>

    <!-- top bidders -->
    <div class="top_bidders">
        <h2>Top bidders</h2>
        <div class="bidders">
            <div class="empty">There is no bidders yet</div>
            <div class="top_bidder" data-set="false" data-rank='1' id="0">
                <p class="rank">1.</p>
                <div class="img">
                    <img src="https://hk.herova.net/products/uploads/users/defult_user.jpg" alt="anonymous | BidMart" class="pfp" loading="lazy">
                </div>
                <p class="name">Unset</p>
                <p class="bid_price">$0</p>
            </div>
            <div class="top_bidder" data-set="false" data-rank='2' id="1">
                <p class="rank">2.</p>
                <div class="img">
                    <img src="https://hk.herova.net/products/uploads/users/defult_user.jpg" alt="anonymous | BidMart" class="pfp" loading="lazy">
                </div>
                <p class="name">Unset</p>
                <p class="bid_price">$0</p>
            </div>
            <div class="top_bidder" data-set="false" data-rank='3' id="2">
                <p class="rank">3.</p>
                <div class="img">
                    <img src="https://hk.herova.net/products/uploads/users/defult_user.jpg" alt="anonymous | BidMart" class="pfp" loading="lazy">
                </div>
                <p class="name">Unset</p>
                <p class="bid_price">$0</p>
            </div>
        </div>
    </div>

    <!-- recommended bids -->
    <div class="astron-container recommended loading">
        <div class="swiper products-container">
            <h2 class="swiper-title">Recommended Bids</h2>
            <div class="swiper-loader">
                <div class="spinner"></div>
            </div>
            <div class="swiper-wrapper recommended-bids ">
                
            </div>
            <!-- Navigation Buttons -->
        </div>
        <div class="swiper-navigation">
            <div class="button-prev">
                <i class="fa fa-angle-left" aria-hidden="true"></i>
            </div>
            <div class="button-next">
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </div>

        </div>
    </div>

    <!-- footer -->
    <?php  include "footer.php"?>
</body>