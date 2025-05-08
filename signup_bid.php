<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>account</title>
    <link rel="icon" href="assets/img/logo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/css/account.css">
    <link rel="stylesheet" href="assets/css/signup_bid.css">
   
</head>

<body>
    <?php include_once "header.php"; ?>
    <div class="account">
        <div class="form" id="form1">
            <form id="signup" action="" method="post">
                <h2>Signup for bids</h2>
                <div class="input-container">
                    <i class="far fa-user"></i>
                    <input type="name" style="min-width: 210px;margin-top: 45px;" name="display_username" id="name" placeholder="username" autocomplete="on" required>
                </div>
               <div class="input-container">
                    <i class="fal fa-globe-africa"></i>
                    <select style="min-width: 210px;" name="country" id="country" required>
                        <option value="" disabled selected>Select a country</option>
                        <option value="Afghanistan">Afghanistan</option>
                        <option value="Albania">Albania</option>
                        <option value="Algeria">Algeria</option>
                        <option value="Andorra">Andorra</option>
                        <option value="Angola">Angola</option>
                        <option value="Argentina">Argentina</option>
                        <option value="Armenia">Armenia</option>
                        <option value="Australia">Australia</option>
                        <option value="Austria">Austria</option>
                        <option value="Azerbaijan">Azerbaijan</option>
                        <option value="Bahrain">Bahrain</option>
                        <option value="Bangladesh">Bangladesh</option>
                        <option value="Belgium">Belgium</option>
                        <option value="Brazil">Brazil</option>
                        <option value="Canada">Canada</option>
                        <option value="China">China</option>
                        <option value="Denmark">Denmark</option>
                        <option value="Egypt">Egypt</option>
                        <option value="France">France</option>
                        <option value="Germany">Germany</option>
                        <option value="India">India</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Italy">Italy</option>
                        <option value="Japan">Japan</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Malaysia">Malaysia</option>
                        <option value="Mexico">Mexico</option>
                        <option value="Netherlands">Netherlands</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="Norway">Norway</option>
                        <option value="Pakistan">Pakistan</option>
                        <option value="Philippines">Philippines</option>
                        <option value="Russia">Russia</option>
                        <option value="Saudi Arabia">Saudi Arabia</option>
                        <option value="South Africa">South Africa</option>
                        <option value="South Korea">South Korea</option>
                        <option value="Spain">Spain</option>
                        <option value="Sweden">Sweden</option>
                        <option value="Switzerland">Switzerland</option>
                        <option value="Turkey">Turkey</option>
                        <option value="United Arab Emirates">United Arab Emirates</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="United States">United States</option>
                    </select>
                </div>

                <div class="input-container-two">
                    <div>
                        <i class="fas fa-university"></i>
                        <input type="text" name="government_name" id="gov" placeholder="government" autocomplete="on" required>

                    </div>
                    <div>
                        <i class="fal fa-city"></i>
                        <input type="text" name="city_name" id="city" placeholder="city" autocomplete="on" required>
                    </div>
                </div>
                <div class="input-container">
                    <i class="fal fa-globe-africa"></i>
                    <input type="text" style="min-width: 210px;" name="address" id="address" placeholder="address" autocomplete="on" required>
                </div>

                <div class="input-container">
                    <i class="fal fa-hand-holding-usd"></i>
                    <select style="min-width: 210px;" name="status" id="status" required>
                        <option value="inland">inland</option>
                        <option value="outland">outland</option>
                    </select>
                </div>
                <div class="checkbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">I have read all the notice about the <a href="#" id="rulesLink">whole rules about the auction</a>and confirm it. </label>
                </div>
                <div class="checkbox">
                    <input type="checkbox" id="privacy" name="privacy" required>
                    <label for="terms">I have read all the notice about the <a href="#" id="privacyLink">privacy and police</a>and confirm it. </label>
                </div>

                <!-- Popup for Rules -->
                <div id="rulesPopup" class="popup">
                    <div class="popup-content">
                        <span class="close">&times;</span>
                        <h2>Auction Rules and Guidelines:</h2>
                        <P>1. Eligibility to Bid</P>
                        <ul>
                            <li>All users must be registered and verified on the website to place a bid.</li>
                            <li>Users must be at least 18 years old to participate in auctions.</li>
                        </ul>
                        <P>2. Bidding Process</P>
                        <ul>
                            <li>Each auction has a specified start time and end time. No bids will be accepted before the start time or after the end time.</li>
                            <li>All bids are legally binding. By placing a bid, you are committing to purchase the item if you win the auction.</li>
                            <li>Bids cannot be withdrawn or canceled once submitted.</li>
                        </ul>
                        <P>3. Automatic Bidding (if applicable)</P>
                        <ul>
                            <li>Users can set up automatic bidding to place bids on their behalf up to a maximum amount.</li>
                            <li>Automatic bids will increase incrementally to maintain the user’s position as the highest bidder until the maximum amount is reached.</li>
                        </ul>
                        <P>4. Bid Increments</P>
                        <ul>
                            <li>Bids must follow the minimum increment amount specified for each auction. Bids below this increment will not be accepted.</li>
                        </ul>
                        <P>5. Winning the Auction</P>
                        <ul>
                            <li>The winning bid amount is final, and the winner is obligated to pay this amount.</li>
                            <li>If a user wins the auction, they will receive an email confirmation with instructions on how to complete the purchase.</li>
                            <li>The highest bidder at the end of the auction will be declared the winner.</li>
                        </ul>
                        <P>6. Payment Terms</P>
                        <ul>
                            <li>All winning bids must be paid in full within [specify time frame, e.g., 48 hours] of the auction ending.</li>
                            <li>Failure to make the payment within the specified period may result in the cancellation of the winning bid, and the item may be offered to the next highest bidder or relisted.</li>
                            <li>Accepted payment methods are [list payment methods].</li>
                        </ul>
                        <P>7. Non-Payment and Penalties</P>
                        <ul>
                            <li>If a winning bidder fails to make payment within the specified time, they may be subject to penalties, including account suspension or permanent banning from the platform.</li>
                            <li>Repeated instances of non-payment may result in legal action or additional penalties.</li>
                        </ul>
                        <P>8. Auction Extension (if applicable)</P>
                        <ul>
                            <li>If a bid is placed within the last few minutes of the auction, the auction may be automatically extended to allow fair competition. The exact extension period will be stated in the auction details.</li>
                        </ul>
                        <P>9. Returns and Refunds</P>
                        <ul>
                            <li>All sales are final. No returns or refunds will be accepted unless explicitly stated in the auction description or due to specific circumstances (e.g., item not as described).</li>
                            <li>Any disputes regarding the condition of the item or delivery must be addressed within [specify time frame, e.g., 7 days] of receiving the item.</li>
                        </ul>
                        <P>10. User Conduct and Fair Play</P>
                        <ul>
                            <li>Users must conduct themselves professionally and refrain from any fraudulent activity.</li>
                            <li>Collusion, bid manipulation, or attempting to artificially influence auction results is strictly prohibited and will result in account suspension.</li>
                        </ul>
                        <P>11. Disputes and Resolution</P>
                        <ul>
                            <li>In the event of a dispute regarding a bid or auction result, the platform’s decision will be final.</li>
                            <li>Users are encouraged to reach out to customer support for any issues, and the platform will work to resolve disputes fairly.</li>
                        </ul>
                        <P>12. Disclaimer of Liability</P>
                        <ul>
                            <li>The platform is not responsible for any loss, damage, or claim resulting from the use of the auction platform.</li>
                            <li>All items are sold "as is" unless otherwise stated, and the platform makes no warranties regarding the condition, authenticity, or value of items listed for auction.</li>
                        </ul>
                        <P>13. Changes to Auction Rules</P>
                        <ul>
                            <li>The platform reserves the right to change these rules at any time. Users will be notified of any significant changes, and it is the responsibility of the users to review the rules before participating in any auction.</li>
                        </ul>
                    </div>
                </div>

                <!-- Popup for Privacy -->
                <div id="privacyPopup" class="popup">
                    <div class="popup-content">
                        <span class="close">&times;</span>
                        <h2>privacy and policy :</h2>
                        <P>Introduction of the usage agreement:</P>
                        <P>Our online store welcomes you and informs you that you will find below the terms and Conditions governing your use of this store and all the legal consequences that result from your use of the store's online services via this electronic platform, as the use of any person to the store, whether he is a consumer of the store's service or product or otherwise, this is and Ben. This agreement is considered valid and effective as soon as you approve it and start registering with the store under Article X of the Saudi electronic transactions system.</P>
                        <P>Article I-introduction and definitions:</P>
                        <P>The above preface is an integral part of this Agreement, and below you will find the semantics and definitions of the main terms consumed in this agreement :</P>
                        <P>1 - (store) this definition includes all forms of the online store, whether it is an electronic application, a website on the web, or a commercial store.</P>
                        <P>2 - (consumer) a person who deals in electronic commerce with a desire to obtain the products or services provided by the store through its electronic platform.</P>
                        <P>
                            3. (agreement) this term means the terms and conditions of this agreement, which govern and regulate the relationship between the parties to this agreement.
                        </P>
                        <P>Article II-legal capacity of the consumer:</P>
                        <P>1-the consumer acknowledges that he has a legal capacity considered legitimate and a system for dealing with the store, or that he is at least eighteen years old.</P>
                        <P>2-the consumer agrees that in case of violation of this article, he shall bear the consequences of such violation before third parties.</P>
                        <P>Article III-nature of an obligation:</P>
                        <P>1-the store's obligation to consumers or consumers is to provide ( service or product).</P>
                        <P>2-the store may provide other services such as after-sales services or other related services, due to the nature and type ( service or product ) required by the consumer.</P>
                        <P>Article IV - controls on the use of :</P>
                        <P>1-the consumer is obliged to use the store's electronic platform in accordance with public morals and regulations in force in the kingdom of Saudi Arabia.</P>
                        <P>2-the consumer is obliged when buying a service or product from this store not to use this service or product in violation of public morals and the regulations in force in the kingdom of Saudi Arabia.</P>
                        <P>Article V. accounts and registration obligations:</P>
                        <P>Immediately after applying for membership in this store as a user, you are obliged to disclose specific information and choose a username and a secret password to use when accessing the store's services. By doing so, you have agreed to:</P>
                        <P>1-you are responsible for maintaining the confidentiality of your account information and password confidentiality, and you hereby agree to inform this store immediately of any unauthorized use of your account information with the store or any other breach of your confidential information. </P>
                        <P>2-in no case will the store be responsible for any loss that may be inflicted on you directly or indirectly morally or materially as a result of disclosing consumer name information or login password.</P>
                        <P>3-you are committed to using your account or membership with the store yourself, as you are fully responsible for it, and if someone else uses it, this is a presumption that you have authorized him to use the store in your name and for your account.</P>
                        <P>
                            4-you are committed when using the store to use it with all seriousness and credibility.</P>
                        <P>5-you are obliged to disclose real, correct, up-to-date, complete and legal information about yourself as required during registration with the store and are obliged to update your data in case of changing it in reality or in case of need to do so.</P>
                        <P>6-our store is committed to treating your personal information and contact addresses with strict confidentiality.</P>
                        <P>7-if the store finds out that you have disclosed information that is not true, incorrect, current, incomplete, illegal or contrary to what is stated in the usage agreement, the store has the right to suspend, freeze or cancel your membership or your store and account on the platform, without prejudice to the store's other rights and legitimate means to recover their rights and protect other consumers.
                        </P>
                        <P>8-in case of non-compliance with any of the above, the store management has the right to suspend or cancel your store or membership or block you from accessing the store's services again.</P>
                        <P>Article VI-electronic communications and official means of communication:</P>
                        <P>1-the parties to this Agreement agree that communication is carried out by e-mail registered in the platform.</P>
                        <P>2-the consumer agrees that all agreements, advertisements, data and other communications that are provided electronically take the place of their written counterparts, which is a stand-alone argument, in meeting legal and legitimate needs.</P>
                        <P>3-the consumer agrees to the possibility of communicating with him and informing him of any provisions related to this agreement or related to dealing with him through the store management broadcasting public messages that will be sent to all consumers or to specific users of the store</P>
                        <P>Article VII-amendments to the usage agreement and fees:</P>
                        <p>1 - in case of cancellation of any incoming material or clause contained in this agreement or that there is any incoming material or any clause contained in this agreement is no longer in force, such an order does not cancel the validity of the rest of the materials, clauses, rules and provisions contained in this Agreement and remain valid until further notice from the store management</p>
                        <P>2-this agreement - which is amended from time to time as the case may be-constitutes the mechanism of action, understanding and agreement between (the consumer) and( the store) </P>
                        <P>3-the store may impose fees on some consumers, depending on the offers, products or services they request, or the fees or taxes imposed by the state on the nature of the product or service.</P>
                        <P>4-the store reserves the right to add, increase, reduce or deduct any fees or expenses under the articles, terms and conditions of the use agreement, on any of the consumers, whatever the reason for their registration.</P>
                        <P>Article VIII-payment and payment services for stores in the store</P>
                        <P>1-the store provides through its partners a payment system and payment in the store can be done completely online through the payment options available on the store or through any payment method provided by the store from time to time.</P>
                        <P>2-the store is obliged to determine the price of the service or product that it offers in its store according to the recognized market value.</P>
                        <P>3-the store is obliged to provide invoices, receipt documents and receipt documents for all amounts and profits that arise in its store, and is obliged to give the consumer an invoice for his purchase of a service or product </P>
                        <P>4-the store shall be obliged to provide the accounting specifications recognized in its electronic store, in accordance with the provisions of this Agreement, and for the legal, economic, commercial and organizational interests in this organization.</P>
                        <P>Article IX-intellectual property:
                        </P>
                        <P>1-the intellectual property rights of the store are fully owned by the store, whether owned by them before the establishment of this electronic platform or after its establishment.</P>
                        <P>2-the consumer or consumer respects the intellectual property rights of the store, including the name of the store itself, and the words, logos and other symbols of the store or displayed on it, as each right followed by the name of the store are fully intellectual property rights of the store</P>
                        <P>Article X - responsibility of the store:</P>
                        <P>1-the store is committed to conduct its business through this electronic platform in a regular manner and in accordance with the regulations in force in the kingdom of Saudi Arabia, and in accordance with the provisions of this agreement.</P>
                        <P>2-the store shall not bear any claims arising from errors or negligence, whether caused directly, indirectly, accidentally, by the consumer or by a third party such as shipping companies.</P>
                        <P>3-the name of the store, its employees, owners and their representatives are obliged to ensure that the product or service is sound, legitimate and authorized in accordance with the laws and regulations of the kingdom of Saudi Arabia and is used for legitimate purposes.</P>
                        <P>Article XI - restriction of access or membership:</P>
                        <P>The store can suspend or cancel the consumer's membership or restrict the consumer's access to the platform's services at any time, without warning, for any reason, and without limitation.</P>
                        <P>Article XII. applicable law or regulation:</P>
                        <P>This usage agreement is governed and formulated in accordance with the laws, regulations and legislations in force and in force in the kingdom of Saudi Arabia, and is fully and completely subject to the regulations in force with the authorities in the kingdom of Saudi Arabia.</P>
                        <P>Article XIII. general provisions: </P>
                        <P>In the event of cancellation of any incoming material or clause contained in this Use Agreement or that there is any incoming material or any clause contained in the use agreement is no longer effective, such an order does not cancel the validity of the rest of the materials, clauses and provisions contained in the Use Agreement and remain valid until further notice from the store management</P>
                        <P>This usage agreement - which may be amended from time to time as the case may be-constitutes the usage agreement, the mechanism of work, understanding, Agreement and contract between the store and the consumer, and both parties to this Agreement agree to bear in mind the following:</P>
                        <P>1-the Arabic language shall be the applicable language when interpreting the provisions of this agreement, or when translating them into another language.</P>
                        <P>2-all prices displayed on the services or products of the store may be amended from time to time.</P>
                        <P>3-the promotional or marketing offers that the store may place are temporary offers, as the store has the right to modify these promotional or marketing offers at any time or stop them.</P>
                        <P>4-the parties to this agreement are obliged to deal with each other in a manner that does not violate the Sharia rules, regulations and applicable laws related to the nature of dealing between the store and the consumer.</P>
                        <P>5-this Use Agreement is canceled only by a decision issued by the store management</P>
                    </div>
                </div>
                <a class="button1" type="submit" href="#form2" >continue</a>
                <p>powered by <a href="herova.net" style="color:#0B8A00;">Herova</a></p>
            </form>
        </div>
        <!-- Form 2 -->
        <div class="form" id="form2" style="display: none;" >
            <form id="signup2" action="" method="post" onsubmit="return validateForm2();">
                <h2>Signup for bids</h2>
                <p style="max-width: 500px;">National id info:</p>
                <div class="input-container">
                    <i class="far fa-user"></i>
                    <input type="name" style="min-width: 210px;" name="full_name" id="id_name" placeholder="full id name" autocomplete="on" required>
                </div>
                <div class="input-container">
                    <i class="far fa-user"></i>
                    <input type="text" style="min-width: 210px;" name="id_number" id="id_no" placeholder="id number"maxlength="20" minlength="8" autocomplete="on" required>
                </div>
                <label for="uploadFile1" id="drop-area-1">
                    <input type="file" id="uploadFile1" name="id_img" accept="image/*" style="width: 0; padding: 0; margin: 0;" hidden >
                    <div class="img-view">
                        <img src="" alt="">
                        <i class="fa-regular fa-image"></i>
                        <p>Drag and drop your id photo or pdf</p>
                    </div>
                </label>
                <p style="max-width: 500px;text-align:left;">Banking info:</p>
                <div class="input-container">
                    <i class="fas fa-university"></i>
                    <select style="min-width: 210px;" name="bank_name" id="bank_name" required>
                        <option value="national bank">national bank</option>
                        <option value="bank">bank</option>
                    </select>
                </div>
                <div class="input-container">
                    <i class="fa-solid fa-file-lines"></i>
                    <input type="text" style="min-width: 210px;" name="iban" id="iban" placeholder="IBAN of your account" autocomplete="on" pattern="[A-Za-z0-9]+"
       title="Only letters and numbers are allowed" required>
                </div>
                <label for="uploadFile2" id="drop-area-2">
                    <input type="file" id="uploadFile2" name="iban_img" accept="image/*" style="width: 0; padding: 0; margin: 0;" hidden >
                    <div class="img-view">
                        <img src="" alt="">
                        <i class="fa-regular fa-image"></i>
                        <p>Drag and drop your id photo or pdf</p>
                    </div>
                </label>

                <button class="button1">sign up</button>
                <p>powered by <a href="herova.net" style="color:#0B8A00;">Herova</a></p>
            </form>
        </div>
    </div>
    </div>
    </div>

    </div>
    <?php include_once "footer.php"; ?>

      <script>

        function initializeDropZone(dropZoneId, inputId) {
            const dropZone = document.getElementById(dropZoneId);
            const inputElement = document.getElementById(inputId);
            const img = dropZone.querySelector('img');
            const icon = dropZone.querySelector('i');
            const p = dropZone.querySelector('p');
            const div = dropZone.querySelector('div');

            // Handle file input change
            inputElement.addEventListener('change', function() {
                handleFile(this.files[0], img, p, icon, div);
            });

            // Handle drag and drop
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('dragging');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('dragging');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragging');
                const file = e.dataTransfer.files[0];
                inputElement.files = e.dataTransfer.files; // Assign the file to the input element
                handleFile(file, img, p, icon, div);
            });
        }

        function handleFile(file, img, p, icon, div) {
            if (file) {
                img.style.display = "block";
                p.style.display = 'none';
                icon.style.display = 'none';
                div.classList.add('fit-me');

                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = function() {
                    img.src = reader.result;
                    img.alt = file.name;
                    img.style.width = "100%";
                    img.style.height = "100%";
                    img.style.objectFit = "contain"; // Ensures it fits inside the drop area
                };
            }
        }

        // Initialize multiple drop zones
        initializeDropZone('drop-area-1', 'uploadFile1');
        initializeDropZone('drop-area-2', 'uploadFile2');
        // Get the popups and links
        var rulesPopup = document.getElementById("rulesPopup");
        var privacyPopup = document.getElementById("privacyPopup");
        var rulesLink = document.getElementById("rulesLink"); // Correctly target the <a> element
        var privacyLink = document.getElementById("privacyLink"); // Correctly target the <a> element
        var closeButtons = document.getElementsByClassName("close");

        // Open the rules popup
        rulesLink.onclick = function(event) {
            event.preventDefault(); // Prevent the default link behavior
            rulesPopup.style.display = "flex"; // Use "flex" to match the CSS
        }

        // Open the privacy popup
        privacyLink.onclick = function(event) {
            event.preventDefault(); // Prevent the default link behavior
            privacyPopup.style.display = "flex"; // Use "flex" to match the CSS
        }

        // Close the popups when the close button is clicked
        for (var i = 0; i < closeButtons.length; i++) {
            closeButtons[i].onclick = function() {
                rulesPopup.style.display = "none";
                privacyPopup.style.display = "none";
            }
        }

        // Close the popups when clicking outside of them
        window.onclick = function(event) {
            if (event.target == rulesPopup) {
                rulesPopup.style.display = "none";
            }
            if (event.target == privacyPopup) {
                privacyPopup.style.display = "none";
            }
        }

        function showForm2() {
            // إخفاء الفورم الأولى
            document.getElementById("form1").style.display = "none";
            // إظهار الفورم الثانية
            document.getElementById("form2").style.display = "block";
        }

    </script>
<script>
// ✅ دالة لتعيين الكوكي
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + encodeURIComponent(value) + "; path=/";
    console.log(`✅ Cookie Set: ${name} = ${value}`);
}

function getCookie(name) {
    let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
    return match ? decodeURIComponent(match[2]) : null;
}

  // ✅ Check all required fields in Form 1
function validateForm1() {
    const form = document.getElementById("signup");
    const requiredFields = form.querySelectorAll("[required]");
    for (let field of requiredFields) {
        if ((field.type === "checkbox" && !field.checked) || !field.value.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: 'Please fill in all required fields before submitting.',
            });
            return false;
        }
    }
    return true;
}

// ✅ Check all required fields in Form 2
function validateForm2() {
    const form = document.getElementById("signup2");
    const requiredFields = form.querySelectorAll("[required]");
    const idImg = document.getElementById('uploadFile1').files[0];
    const ibanImg = document.getElementById('uploadFile2').files[0];

    for (let field of requiredFields) {
        if ((field.type === "checkbox" && !field.checked) || !field.value.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Information',
                text: 'Please fill in all required fields before submitting.',
            });
            return false;
        }
    }
if (!idImg) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing ID Image',
            text: 'Please upload your ID image.',
        });
        return false;
    }

    // Check if IBAN image is missing
    if (!ibanImg) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing IBAN Image',
            text: 'Please upload your IBAN image.',
        });
        return false;
    }

    return true;
}

// ✅ إرسال النموذج الأول (Form 1)
async function submitForm1() {
    try {
        const form1Data = new FormData(document.getElementById('signup'));
        const form1Object = Object.fromEntries(form1Data.entries());

        console.log('🚀 Sending JSON to API 1:', JSON.stringify(form1Object));

        const api1Response = await fetch('https://hk.herova.net/bids/bid_own_rig.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(form1Object),
        });

        if (!api1Response.ok) {
            const errorResponse = await api1Response.text();
            throw new Error(`API 1 Error: ${errorResponse}`);
        }

        const api1Data = await api1Response.json();
        console.log('✅ API 1 Response:', api1Data);

        // ✅ حفظ البيانات في الكوكيز بعد نجاح التسجيل
        if (api1Data.PHONE) {
            setCookie("PHONE", api1Data.PHONE, 7);
            setCookie("USER_NAME", api1Data.USER_NAME || form1Object.full_name, 7);
        }

        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Form 1 submitted successfully. Redirecting to Form 2...',
            showConfirmButton: false,
            timer: 1500,
        }).then(() => {
            document.getElementById('form1').style.display = 'none';
            document.getElementById('form2').style.display = 'block';
        });
    } catch (error) {
        console.error('❌ API 1 Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Form 1 Submission Failed',
            text: error.message || 'An error occurred. Please try again.',
            confirmButtonText: 'OK',
        });
    }
}

// ✅ إرسال النموذج الثاني (Form 2)
async function submitForm2(event) {
    event.preventDefault();

    const form2Data = new FormData(document.getElementById('signup2'));
    const idNumber = document.getElementById('id_no').value;
    const fullName = document.getElementById('id_name').value;
    const idImg = document.getElementById('uploadFile1').files[0];
    const ibanImg = document.getElementById('uploadFile2').files[0];

    console.log(idImg,ibanImg);

    if (!idImg || !ibanImg) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Please upload both ID and IBAN images.' });
        return;
    }

    if (idImg.size > 2 * 1024 * 1024 || ibanImg.size > 2 * 1024 * 1024) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'File size should not exceed 2MB.' });
        return;
    }

    if (!['image/jpeg', 'image/png'].includes(idImg.type) || !['image/jpeg', 'image/png'].includes(ibanImg.type)) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Only JPEG and PNG images are allowed.' });
        return;
    }

    form2Data.append('id_number', idNumber);
    form2Data.append('full_name', fullName);
    form2Data.append('id_img', idImg, idImg.name);
    form2Data.append('iban_img', ibanImg, ibanImg.name);

    try {
        const api2Response = await fetch('https://hk.herova.net/bids/bid_owner_rig_info.php', {
            method: 'POST',
            body: form2Data,
            headers: { 'Accept': 'application/json' },
        });

        const responseText = await api2Response.text();
        console.log('📩 Raw API 2 Response:', responseText);

        if (!api2Response.ok) throw new Error(`API 2 Error: ${responseText}`);

        const api2Data = JSON.parse(responseText);
        console.log('✅ Parsed API 2 Response:', api2Data);

        // ✅ استخراج الرسالة من الـ API وتحديد الأيقونة المناسبة
        const message = api2Data.message || "Operation completed.";
        const icon = api2Data.status === "success" ? "success" : "error";

        Swal.fire({
            icon: icon,
            title: api2Data.status === "success" ? "Success!" : "Failed!",
            text: message,
            showConfirmButton: false,
            timer: 3000,
        }).then(async () => {
            // ✅ بعد نجاح التسجيل، انتظر قليلًا ثم أرسل OTP
            setTimeout(async () => {
                console.log("🔍 Checking PHONE in cookies:", getCookie("PHONE"));
                // if (getCookie("PHONE")) {
                    const otpSent = await sendOTP();
                    if (otpSent) window.location.href = 'whatsappOTP.php';
                // } else {
                    // Swal.fire({ icon: "error", title: "Error", text: "Failed to retrieve phone number." });
                // }
            }, 1000);
        });

    } catch (error) {
        console.error('❌ API 2 Error:', error);

        Swal.fire({
            icon: 'error',
            title: 'Signup Failed',
            text: error.message || 'No response from server. Please try again.',
            confirmButtonText: 'OK',
        });
    }
}

// ✅ إرسال OTP بعد التأكد من أن الرقم موجود في الكوكيز
async function sendOTP() {
    try {

        const response = await fetch("https://hk.herova.net/bids/whatsApp.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
        });

        const data = await response.json();
        if (data.status === "success") {
            console.log("✅ OTP Requested Successfully");
            return true;
        } else {
            console.error("❌ OTP Request Failed", data);
            return false;
        }
    } catch (error) {
        console.error("❌ Error requesting OTP:", error);
        return false;
    }
}

document.querySelector(".button1")?.addEventListener("click", function (event) {
    event.preventDefault();
    if (validateForm1()) {
        submitForm1();
    }
});
  document.getElementById('signup2')?.addEventListener('submit', function(event) {
    if (validateForm2()) {
        submitForm2(event);
    } else {
        event.preventDefault();
    }
});
</script>


    <!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>