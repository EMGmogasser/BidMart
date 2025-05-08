<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peakmart</title>
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

    <!-- css -->
    <link rel="stylesheet" href="assets/css/components/swiper.css">
    <link rel="stylesheet" href="assets/css/components/products.css">
    <link rel="stylesheet" href="assets/css/master.css">

    <!-- JS -->
    <script defer src="assets/js/components/swiper.js"></script>
    <script defer src="assets/js/components/scroll.js"></script>
    <script defer type='module' src="assets/js/getProducts.js"></script>

</head>

<body>

    <?php include "header.php" ?>
    <!-- cover -->
    <div class="cover">
        <div class="container">
            <div class="header_inner">
                <div class="head_cont">
                    <h1>Get the deal of a lifetime... Join and start bidding!</h1>
                    <a class="main-button redirect1" href="login.php">Join now</a>
                </div>
                <img src="assets/img/header.png" alt="header" loading="lazy">
            </div>
        </div>
    </div>

    <!-- services -->
    <div class="services">
        <div class="services_inner">
            <div class="service">
                <div class="service-cont">
                    <h2>Product delivery</h2>
                    <p>With PeakMart, you can have your orderdelivered to yourplace, anywhere, anytime</p>
                </div>
                <img src="assets/img/service1.png" alt="service1" loading="lazy">

            </div>
            <div class="service">
                <div class="service-cont">
                    <h2>Payment</h2>
                    <p>You can choose to pay easily via PeakMart’s digital wallet, credit cards, in installments or by
                        cash.</p>
                </div>
                <img src="assets/img/service2.png" alt="service2" loading="lazy">
            </div>
            <div class="service">
                <div class="service-cont">
                    <h2>Auction or direct sale</h2>
                    <p>On PeakMart you can choose to sell your items through an auction, a direct sale with a set price,
                        or
                        through a hybrid of the two forms</p>
                </div>
                <img src="assets/img/service3.png" alt="service3" loading="lazy">
            </div>
            <div class="service">
                <div class="service-cont">
                    <h2>100% Secure</h2>
                    <p>With PeakMark, You’re subjected to zero risk of getting mugged, harassed, or threatened in any
                        way</p>
                </div>
                <img src="assets/img/service4.png" alt="service4" loading="lazy">
            </div>
        </div>
        <div class="scroll-indicators">
            <span class="dot active"></span>
            <span class="dot"></span>
            <span class="dot"></span>
            <span class="dot"></span>
        </div>
    </div>

    <!-- trending bids -->
    <div class="astron-container trending loading">
        <div class="swiper products-container">
            <h2 class="swiper-title">Trending Bids</h2>
            <div class="swiper-loader">
                <div class="spinner"></div>
            </div>
            <div class="swiper-wrapper trending-bids ">
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

    <!-- future bids -->
    <div class="astron-container future loading">
        <div class="swiper products-container">
            <h2 class="swiper-title">future Bids</h2>
            <div class="swiper-loader">
                <div class="spinner"></div>
            </div>
            
            <div class="swiper-wrapper future-bids ">
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


    <!-- apply -->
    <div class="apply">
        <div class="apply-cont">
            <h2>Unlock Maximum Value</h2>
            <p>Showcase your item and watch the bids drive up the price.<br />
                Your chance to secure the highest profit is here!</p>
            <a class="main-button" href="signup_bid.php">Add yours</a>
        </div>
    </div>

    <!-- categories -->
    <div class="categories astron-container">
        <div class="categories_inner">
            <h2 class="swiper-title">Categories</h2>
            <div class="categories-list">
            </div>
        </div>
    </div>

    <!-- current bids -->
    <div class="astron-container current loading">
        <div class="swiper products-container">
            <h2 class="swiper-title">current Bids</h2>
            <div class="swiper-loader">
                <div class="spinner"></div>
            </div>
            <div class="swiper-wrapper current-bids ">
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

    <!-- ended bids -->
    <div class="astron-container finished loading">
        <div class="swiper products-container">
            <h2 class="swiper-title">Ended Bids</h2>
            <div class="swiper-loader">
                <div class="spinner"></div>
            </div>
            <div class="swiper-wrapper finished-bids ">
            </div>
        </div>
        <!-- Navigation Buttons -->
        <div class="swiper-navigation">
            <div class="button-prev">
                <i class="fa fa-angle-left" aria-hidden="true"></i>
            </div>
            <div class="button-next">
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </div>

        </div>
    </div>

    <!-- ads -->
    <div class="ads">
        <div class="ads_cont">
            <a class="main-button">Enroll now</a>
        </div>
    </div>

    <!-- clients -->
    <div class="clients">
        <div class="clients_inner">
            <h2>Trusted By 500+ Businesses</h2>
            <p>Explore on the world's best & largest Bidding marketplace with our beautiful Bidding products. We want to
                be a
                part of your smile, success and future growth.</p>
            <div class="clients-list">
                <img src="assets/img/client1.png" alt="client1" loading="lazy">
                <img src="assets/img/client2.png" alt="client2" loading="lazy">
                <img src="assets/img/client3.png" alt="client3" loading="lazy">
                <img src="assets/img/client4.png" alt="client4" loading="lazy">
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php include "footer.php" ?>
</body>
<script src="js/content.js"></script>

</html>