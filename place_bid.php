<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place a bid</title>
    <link href='https://fonts.googleapis.com/css?family=Cabin' rel='stylesheet'>

    <!-- icon -->
    <link rel="icon" href="assets/img/logo.png">

    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  
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
    <script defer src="assets/js/mapControl.js"></script>
    <script defer src="assets/js/place_bid.js"></script>
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

    <!-- form -->
    <div class="form" id="placeBid">
        <form id="uploadProduct" action="" method="post">
            <h1>place a bid</h1>
            <div class="upload-container">
                <input type="file" id="imageUpload" multiple accept="image/*">
                <label for="imageUpload" class="upload-label">
                    <i class="fa-regular fa-image"></i>
                    <span class="upload-text">Drag and drop your product photo</span>
                    <img id="imagePreview" class="image-preview" style="width = 100%;"></img>
                </label>
            </div>
            <div class="previews"></div>
            <div class="input-container">
                <input type="text" name="productName" minlength="12" id="productName" placeholder="Product name"
                    autocomplete="off" required>
                    
            </div>
            <div class="input-container">
                <textarea name="description" id="description" minlength="30" placeholder="Description"
                    required></textarea>
            </div>
            <div class="input-container-two">
                <div>
                    <input type="number" min='0' name="startingPrice" id="startingPrice" placeholder="Starting price"
                        autocomplete="off" required>
                </div>
                <div>
                    <input type="number" min='0' name="startingPrice" id="expectedPrice" placeholder="Expected price"
                        autocomplete="off" required>
                </div>
            </div>
            <div class="input-container location-container">
                <button type="button" id="locationButton"> <span id="locationDisplay">Choose location</span></button>
                <input type="hidden" name="Location" id="location" required>
            </div>
            <div class="input-container-two">
                <div>
                    <label for="startingDate">Delivery Date:</label>
                    <input type="date" name="deliveryDate" id="deliveryDate" placeholder="delivery Date "
                        autocomplete="off" required>
                    <p class="note">the date we get the product into the storehouse *</p>
                </div>
                <div>
                    <label for="startingDate">Start Date:</label>
                    <input type="date" name="startingDate" id="startingDate" placeholder="Starting Date"
                        autocomplete="off" required>
                    <p class="note">When the product will be available for users to start bidding *</p>
                </div>
            </div>
            <div class="input-container-two">
                <div>
                    <input type="number" min='7' max='300' name="period" id="period" placeholder="Period of Bid"
                        autocomplete="on" required>
                    <p class="note">maximum number of days the product will be available for bidding *</p>
                </div>
                <div>
                    <select id="category" name="category">
                        <option value="" disabled selected>Select a Category</option>
                        <option value="electronics_phones">Phones & Accessories</option>
                    </select>
                </div>
            </div>
            <input type="submit" class='button1' value="Place a Bid">
            <p class="copyright">powered by <a href="herova.net" style="color:#0B8A00;">Herova</a></p>
        </form>
    </div>

    <!-- leaflet map -->
    <div id="mapPopup" class="map-popup">
        <div class="map-popup-content">
            <div id="map" class="map"></div>
            <button id="saveLocation">Save Location</button>
            <button id="closeMap">Close</button>
        </div>
    </div>

    <!-- footer -->
    <?php  include "footer.php"?>
</body>

</html>