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
    <link rel="stylesheet" href="assets/css/account.css">

    <style>
        .news {
            visibility: hidden;
        }

        .account {
            margin-top: 50px;
        }

        .hidden {
            display: none;
        }

        .myOrders{
            flex-grow: 1;
            /* cursor: pointer; */
            max-height: 500px;
            overflow-y: scroll;
            padding: 20px;
            border: 1px solid #2f4f4f61;
            scrollbar-width: none;
            border-radius: 30px;
            -ms-overflow-style: none;
            box-shadow: inset 0 0 11px 0px var(--background-negative);
        }   
        .myOrders::-webkit-scrollbar {
            display: none;            /* Chrome, Safari, Edge */
        }

        .orderdetails{
            width: 100%;
        }

        #toggle1{
            width: 90vw;
            max-width: 800px;
            display: flex;
            gap: 40px;
        }
        #toggle1.hidden{
            display:none;
        }


        #product-filter{
            /* position: sticky; */
            margin-top: 60px;
            height: fit-content;
            top: 35vh;
            display: flex;
            flex-direction: column;
            width: fit-content;
            padding: 20px;
            background: var(--background);
            border: 1px solid #2f4f4f61;
            box-shadow: var(--shadow);
            border-radius: 20px;
            gap: 20px;
        }


    </style>
</head>

<body>
    <?php include_once "header.php"; ?>
    <div class="account">
        <div class="swap">
            <a class="info" onclick="swapping(3)" href="#toggle3" data-toggle="list"  style='color:var(--text-color);'>Account</a>
            <div class="vertical"></div>
            <a class="balance" onclick="swapping(2)" href="#toggle2" data-toggle="list" style='color:var(--text-color);'>Balance</a>
            <div class="vertical"></div>
            <a class="orders active" onclick="swapping(1)" href="#toggle1" data-toggle="list">Products</a>
        </div>

        <!-- Account Section -->
        <div class="form hidden" id="toggle3">
            <div class="pfp" id="photo">
                <img class="user" src=""  alt="user">
            </div>
            <form id="signup" action="" method="post">
                <div class="input-container">
                    <i class="far fa-user"></i>
                    <input type="name" style="min-width: 210px;margin-top: 45px;" name="username" id="username" placeholder="Username" autocomplete="on" required>
                </div>
                <div class="input-container">
                    <i class="far fa-envelope"></i>
                    <input type="text" style="min-width: 210px;" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="Email Address"  autocomplete="on" required>
                </div>
                <div class="input-container">
                    <i class="fal fa-phone"></i>
                    <input type="tel" style="min-width: 210px;" name="phone" id="phone" placeholder="Phone Number" pattern="[0-9]" minlength="9" maxlength="14" autocomplete="on" >
                </div>
                <div class="input-container">
                    <i class="fal fa-globe-africa"></i>
                    <input type="text" style="min-width: 210px;" name="country" id="country" placeholder="Country" autocomplete="on" >
                </div>
                <div class="input-container">
                    <i class="fal fa-globe-africa"></i>
                    <input type="text" style="min-width: 210px;" name="address" id="address" placeholder="Address"  autocomplete="on">
                </div>
                <div class="input-container-two">
                    <div>
                        <i class="fas fa-university"></i>
                        <input type="text" name="gov" id="gov" placeholder="Government" autocomplete="on">
                    </div>
                    <div>
                        <i class="fal fa-city"></i>
                        <input type="text" name="city" id="city" placeholder="City"  autocomplete="on" >
                    </div>
                </div>
                <div class="input-container">
                    <i class="fal fa-hand-holding-usd"></i>
                    <select style="min-width: 210px;" name="status">
                        <option value="inland">Inland</option>
                        <option value="outland">Outland</option>
                    </select>
                </div>
                <div class="input-container">
                    <i class="far fa-lock"></i>
                    <input type="password" style="min-width: 210px;" id="Password" placeholder="Password" name="Password"  minlength="8" maxlength="15" autocomplete="off" >
                </div>
                <a class="button1" type="submit">Update</a>
                <p>Powered by <a href="herova.net" style="color:#0B8A00;">Herova</a></p>
            </form>
        </div>

        <!-- Balance Section -->
        <div class="balance-container hidden" id="toggle2">
            <i class="far fa-university"></i>
            <div class="balance-details">
                <div class="infor">
                    <p>Bank Name:</p>
                    <span>Bank ELbalaad</span>
                </div>
                <div class="infor">
                    <p>Country:</p>
                    <span>Saudi Arabia</span>
                </div>
                <div class="infor">
                    <p>IBAN:</p>
                    <span>5*** **** **** 4756</span>
                </div>
                <a class="button1" href="change_bank.php">Change my bank account</a>
                <p style="color:red;font-size: 14px;">*Important note: Any new account takes about 2 days to verify it.</p>
                <h2>Your Balance:</h2>
                <p class="balance-no">800 000 USD</p>
                <div class="couponss">
                    <div class="loyalty">
                        <i class="fal fa-hand-holding-usd"></i>
                        <p>Loyalty Points: <span id="loyaltyPoints">11000</span></p>
                    </div>
                </div>
                <div class="coupons">
                    <div>
                        <a href="" id="rulesLink">Loyalty Points Rules</a>
                    </div>
                    <div id="rulesPopup" class="popup">
                        <div class="popup-content">
                            <span class="close">&times;</span>
                            <h2>Loyalty Points Rules</h2>
                            <p>Earn loyalty points with every purchase and redeem them for exciting rewards! Here's how it works:</p>
                            <div class="rule">
                                <h3>1. Earning Points</h3>
                                <ul>
                                    <li>For every $1 spent, you earn <strong>10 loyalty points</strong>.</li>
                                    <li>Special promotions may offer bonus points on select products.</li>
                                    <li>Points are credited to your account after the order is successfully delivered.</li>
                                </ul>
                            </div>
                            <div class="rule">
                                <h3>2. Redeeming Points</h3>
                                <ul>
                                    <li><strong>100 points</strong> = $1 discount on your next purchase.</li>
                                    <li>Points can be redeemed during checkout.</li>
                                    <li>Minimum redemption amount is <strong>500 points</strong>.</li>
                                </ul>
                            </div>
                            <div class="rule">
                                <h3>3. Expiry of Points</h3>
                                <ul>
                                    <li>Points expire after <strong>12 months</strong> from the date they are earned.</li>
                                    <li>You will receive a reminder email before your points expire.</li>
                                </ul>
                            </div>
                            <div class="rule">
                                <h3>4. Other Rules</h3>
                                <ul>
                                    <li>Points are non-transferable and can only be used by the account holder.</li>
                                    <li>Points cannot be exchanged for cash.</li>
                                    <li>In case of order cancellation, points earned for that order will be deducted.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <p style="margin: 15px 0;">Powered by <a href="herova.net" style="color:#0B8A00;">Herova</a></p>
            </div>
        </div>

        <!-- Products Section -->
        <div id="toggle1" >
            <div id="product-filter">
                <label >
                    <input type="radio" checked name="filter" value="uploaded" />
                    <span>Uploaded</span>
                </label>

                <label >
                    <input type="radio" name="filter" value="enrolled" />
                    <span>Enrolled in</span>
                </label>
            </div>

            <div class="myOrders" style="cursor:pointer;">
             
             </div>
        </div>
   

    <script>
        function swapping(divNumber) {
            window.scrollTo(0, 0);
            const elements = {
                1: document.getElementById("toggle1"),
                2: document.getElementById("toggle2"),
                3: document.getElementById("toggle3"),
            };

            const links = {
                1: document.querySelector('body > div.account > div.swap > a.orders'),
                2: document.querySelector('body > div.account > div.swap > a.balance'),
                3: document.querySelector('body > div.account > div.swap > a.info'),
            };

            // Hide all elements and reset link colors
            Object.values(elements).forEach(el => el.classList.add("hidden"));
            Object.values(links).forEach(link => link.style.color = "var(--text-color)");

            // Show the selected element and highlight the link
            if (elements[divNumber]) {
                elements[divNumber].classList.remove("hidden");
                links[divNumber].style.color = "#d56b00";
            }
            window.scrollTo(0, 0);
        }
    </script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

      <script src="js/user_products.js"></script>
        <script src="js/settings.js"></script>

    <?php include_once "footer.php"; ?>
</body>

</html>