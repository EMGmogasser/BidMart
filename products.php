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

    <!-- css -->
    <link rel="stylesheet" href="assets/css/components/swiper.css">
    <link rel="stylesheet" href="assets/css/components/products.css">
    <link rel="stylesheet" href="assets/css/master.css">
    <link rel="stylesheet" href="assets/css/bids.css">
    <style>
        .categories{
            margin-top:90px !important;
        }
        .categories-list{
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap:1%;
        }
        .cat{
            width: 120px;
            height: 120px;
        }
        .cat img {
            height: 65%;
            width: auto;
            filter: brightness(0.2);

            /* max-width: unset;
            max-height: unset;
            min-width: unset; */
        }
        .cat p {
            text-align: center;
            font-size: 0.9rem;
            font-weight: unset;
        }
        #load-more{
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 30px;
        }
        #load-more .spinner{
            position: static;
        }
    </style>

    <!-- JS -->
     <script defer type="module" src="assets/js/products.js"></script>
</head>

<body>
    <!-- header -->
    <?php include "header.php"?>

    <!-- categories -->
    <div class="categories astron-container">
        <div class="categories_inner">
            <h2 class="swiper-title">Categories</h2>
            <div class="categories-list"></div>
        </div>
    </div>
        
    <!-- products -->
    <div class="astron-container ">
        <div class="categories_inner">
            <h2 class="swiper-title">Products</h2>
            <div class="products-grid loading "></div>
            <div class="empty"><p>No items found in this category!</p></div>
        </div>
    </div>
    <!-- observer -->
    <div  class="astron-container loading" id="load-more" ">
        <h2>Fetching more Products . . .</h2>
        <div class="spinner"></div>
    </div>
    <!-- footer -->
    <?php  include "footer.php"?>
</body>