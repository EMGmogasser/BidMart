<head>
    <!-- css -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/components/header.css">

    <!-- JS -->
    <script defer type='module' src="assets/js/components/header.js"></script>
    <script defer src="assets/js/components/theme.js"></script>
</head>

<div class="headers font-montserrat">
    <!-- main header -->
    <header class="header">
        <a href="index.php">
            <img src="assets/img/logo.png" class="logo" loading="lazy">
        </a>
        <div class="actions">
            <button class="login-btn primary-btn" data-href='login.php'>login</button>
            <button class="login-btn primary-btn" id='logout' style='display: none ' onclick="logout()">log out</button>
            

            <label class="dark-mode-toggle">
                <input type="checkbox" id="dark-mode-switch">
                <span class="slider">
                    <i class="fas fa-moon " style="color: white;"></i>
                    <i class="fas fa-sun " style="color: #f39c12;"></i>
                </span>
            </label>
        </div>
        <div id="menu">
            <i class="fa-solid fa-bars fa-2x"></i>
        </div>
        <nav class="navbar">
            <a class="nav-item" href="index.php" id="index.php">Home</a>
            <div class="custom-select" id="customSelect" tabindex="0">
                <!-- Custom button mimicking the select -->
                <a type="button" id="customSelectButton">
                    <span id="selectedValue">Categories</span>
                    <span class="arrow"><i class="fa-solid fa-chevron-down"></i></span>
                </a>

                <!-- Custom dropdown list -->
                <ul role="listbox" id="customSelectList">
                </ul>

                <!-- Hidden native select to maintain form integrity and screen reader support -->
                <select id="nativeSelect" aria-hidden="true" tabindex="-1" style="display: none;">
                    <option value="option1">Option 1</option>
                    <option value="option2">Option 2</option>
                    <option value="option3">Option 3</option>
                </select>
            </div>
            <a class="nav-item" href="products.php" id="products.php">Bids</a>
            <a class="nav-item" href="test.php" id="test.php">About us</a>
            <a class="nav-item upload-product" href="signup_bid.php" id="place_bid.php">Add Product</a>
            <a class="nav-item" id='logout-anchor' onclick='logout()'>
            <i class="fa-solid fa-right-from-bracket"></i>
            Log out
            </a>
        </nav>
        <div class="nav-overlay"></div>
    </header>
    <div class="header-conserver"></div>

    <!-- bottom header -->
    <div class="bottom-nav">
        <a href="index.php" class="home nav-item">
            <div>
                <i class="fa fa-home fa-2x" aria-hidden="true"></i>
            </div>
            <p>Home</p>
        </a>

        <a href="sliders.php" class="cart nav-item">
            <div>
                <i class="fa-solid fa-cart-shopping fa-2x"></i>
            </div>
            <p>My Bids</p>
        </a>

        <a href="footer.php" class="notifications nav-item">
            <div>
                <i class="fa-regular fa-bell fa-2x"></i>
            </div>
            <p>alerts</p>
        </a>

        <a href='login.php' class="profile nav-item">
            <div>
                <i class="fa-regular fa-user fa-2x"></i>
            </div>
            <p>profile</p>
        </a>
    </div>

    <!-- news -->
    <div class="news">
        <div class="news-logo">
            <img src="assets/img/news-arrow.png" alt="logo-tv" loading="lazy">
            <p class="news-word">News</p>
        </div>
        <div class="news-slide marquee">
            <a class="news-content"></a>
        </div>
    </div>
</div>

<script>
    function logout(){
        const cookies = document.cookie.split(";");
        
        for (let i = 0; i < cookies.length; i++) {
            const cookie = cookies[i];
            const eqPos = cookie.indexOf("=");
            const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
            
            // Create a generic cookie deletion string.
            document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
            document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;domain=" + window.location.hostname;
            document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;domain=." + window.location.hostname;
            
            if(window.location.hostname.startsWith("www.")){
                document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;domain=" + window.location.hostname.slice(4);
                document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;domain=." + window.location.hostname.slice(4);
            }
        }
        console.log(document.cookie);
        window.location.href = "index.php";

    }
</script>