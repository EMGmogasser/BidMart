// Function to fetch user details
async function fetchUserDetails() {
    try {
        const response = await fetch("https://hk.herova.net/data/user_info.php", {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
            credentials: 'include'
        });

        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const result = await response.json();
        if (result.status !== 'success' || !result.data) {
            throw new Error('Invalid response format from server');
        }

        const userData = result.data;
        populateForm(userData);
        updateSellerInfoDisplay(userData);
        
    } catch (error) {
        console.error('Error fetching user details:', error);
        alert('Failed to load user data. Please refresh the page.');
    }
}

// Function to populate the form with user data
function populateForm(userData) {
    if (!userData) return;

    const DEFAULT_PROFILE_IMAGE = './assets/img/user.jpg';

    const fieldMappings = {
  'username': userData.USER_NAME || '',
  'email': userData.EMAIL || '',
  'phone': userData.PHONE || '',  
  'Password': userData.PASSWORD || '',
  'country': userData.seller_info ? (userData.seller_info.COUNTRY || '') : '',
  'gov': userData.seller_info ? (userData.seller_info.GOV || '') : '',
  'city': userData.seller_info ? (userData.seller_info.CITY || '') : '',
  'address': userData.seller_info ? (userData.seller_info.ADDRESS || '') : '',
  'seller_ID_NO': userData.seller_info ? (userData.seller_info.ID_NO || '') : '',
  'seller_ID_PHOTO': userData.seller_info ? (userData.seller_info.ID_PHOTO || '') : '',
  'seller_BANK_NAME': userData.seller_info ? (userData.seller_info.BANK_NAME || '') : '',
  'seller_IBAN': userData.seller_info ? (userData.seller_info.IBAN || '') : '',
  'seller_BALANCE': userData.seller_info ? (userData.seller_info.BALANCE || '0') : '',
  'seller_LOYALTY_POINTS': userData.seller_info ? (userData.seller_info.LOYALTY_POINTS || '0') : ''
};

    Object.entries(fieldMappings).forEach(([fieldId, value]) => {
        const element = document.getElementById(fieldId);
        if (element) element.value = value;
    });

    const photoElement = document.querySelector('#photo img');
    if (photoElement) {
        photoElement.src = userData.PHOTO || DEFAULT_PROFILE_IMAGE;
        photoElement.onerror = function() {
            this.src = DEFAULT_PROFILE_IMAGE;
        };
    }

    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) {
        statusSelect.value = userData.STATUS || 'inland';
    }
}

function updateGeneralInfoDisplay(userData) {
    const generalContainer = document.getElementById('toggle1');
    if (!generalContainer) return;

    // عرض بيانات المستخدم العامة
    let html = `
        <div class="user-info">
            <p><strong>Username:</strong> ${userData.USER_NAME || ''}</p>
            <p><strong>Email:</strong> ${userData.EMAIL || ''}</p>
            <p><strong>Phone:</strong> ${userData.PHONE || ''}</p>
            <p><strong>Country:</strong> ${userData.COUNTRY || ''}</p>
            <p><strong>Address:</strong> ${userData.ADDRESS || ''}</p>
            <p><strong>Government:</strong> ${userData.GOVERNMENT || ''}</p>
            <p><strong>City:</strong> ${userData.CITY || ''}</p>
        </div>
    `;

    // إذا كان المستخدم بائعاً، يتم عرض بيانات إضافية للبائع
    if (userData.seller_info && userData.seller_info.SELLER_ACTIVATION == 1) {
        html += `
            <div class="seller-extra-info">
                <h3>Seller Details:</h3>
                <p><strong>Display Username:</strong> ${userData.seller_info.DISPLAY_USERNAME || ''}</p>
                <p><strong>ID No:</strong> ${userData.seller_info.ID_NO || ''}</p>
                <p><strong>ID Photo:</strong> 
                    ${userData.seller_info.ID_PHOTO ? `<img src="${userData.seller_info.ID_PHOTO}" alt="ID Photo" style="max-width:100px;">` : ''}
                </p>
                <p><strong>Seller Address:</strong> ${userData.seller_info.ADDRESS || ''}</p>
                <p><strong>City:</strong> ${userData.seller_info.CITY || ''}</p>
                <p><strong>Country:</strong> ${userData.seller_info.COUNTRY || ''}</p>
            </div>
        `;
    }

    generalContainer.innerHTML = html;
}


function updateSellerInfoDisplay(userData) {
    const balanceContainer = document.getElementById('toggle2');
    if (!balanceContainer) return;

    // التأكد من أن seller_info موجود وأن البائع مفعل (على سبيل المثال SELLER_ACTIVATION == 1)
    if (userData.seller_info && userData.seller_info.SELLER_ACTIVATION == 1) {
        balanceContainer.innerHTML = `
            <i class="far fa-university"></i>
            <div class="balance-details">
                <div class="infor">
                    <p>Bank Name:</p>
                    <span>${userData.seller_info.BANK_NAME || ''}</span>
                </div>
                <div class="infor">
                    <p>Country:</p>
                    <span>${userData.seller_info.COUNTRY || ''}</span>
                </div>
                <div class="infor">
                    <p>IBAN:</p>
                    <span>${userData.seller_info.IBAN || ''}</span>
                </div>
                <a class="button1" href="change_bank.php">Change my bank account</a>
                <p style="color:red;font-size: 14px;">*Important note: Any new account takes about 2 days to verify it.</p>
                <h2>Your Balance:</h2>
                <p class="balance-no">${userData.seller_info.BALANCE || ''}</p>
                <div class="couponss">
                    <div class="loyalty">
                        <i class="fal fa-hand-holding-usd"></i>
                        <p>Loyalty Points: <span id="loyaltyPoints">${userData.seller_info.LOYALTY_POINTS || ''}</span></p>
                    </div>
                </div>
                <p style="margin: 15px 0;">Powered by <a href="herova.net" style="color:#0B8A00;">Herova</a></p>
            </div>
        `;
    } else {
        // إذا المستخدم غير مسجل كبائع، عرض رسالة التسجيل كبائع
        balanceContainer.innerHTML = `
            <div class="seller-not-registered" style="padding:15px 0;">
                <i class="fas fa-exclamation-circle"></i>
                <h3>You are not registered as a seller yet</h3>
                <p style="padding:15px 0;">To access seller features and view your balance, please register as a seller.</p>
                <a class="button1" href="signup_bid.php">Register as Seller</a>
            </div>
            <p style="margin: 15px 0;">Powered by <a href="herova.net" style="color:#0B8A00;">Herova</a></p>
        `;
    }
}
// Function to update user information
async function updateUserInfo() {
    const updateButton = document.querySelector('.button1');
    const originalText = updateButton?.textContent;

    try {
        if (updateButton) {
            updateButton.disabled = true;
            updateButton.textContent = 'Updating...';
        }

        let formData = new FormData();
        const fields = ["USER_NAME", "EMAIL", "PHONE", "COUNTRY", "ADDRESS", "GOV", "CITY", "PASSWORD"];

        fields.forEach(field => {
            let element = document.getElementById(field.toLowerCase());
            let value = element ? element.value.trim() : "";
            formData.append(field, value);
        });
  
let username = document.getElementById("username").value.trim();
  
formData.append("USER_NAME", username || "default_username"); 
   const sellerFields = [
      "BANK_NAME",      // اسم البنك
      "IBAN",           // رقم الآيبان
      "BALANCE",        // الرصيد
      "LOYALTY_POINTS", // نقاط الولاء
      "seller_country", // الدولة الخاصة بالبائع
      "seller_gov",     // المحافظة/الجهة الخاصة بالبائع
      "seller_city",    // المدينة الخاصة بالبائع
      "seller_address"  // العنوان الخاص بالبائع
    ];
    sellerFields.forEach(field => {
      // إذا كان الحقل من بيانات البائع المُدمج داخل seller_info، يمكنك استخدام id يبدأ بـ "seller_"
      let element = document.getElementById(field.toLowerCase());
      let value = element ? element.value.trim() : "";
      formData.append(field, value);
    });

        const response = await fetch("https://hk.herova.net/data/update_info.php", {
            method: 'POST',
            body: formData,
            credentials: 'include'
        });

        const result = await response.json();
        if (result.status !== 'success') {
            throw new Error(result.message || 'Update failed');
        }

        alert('✅ Profile updated successfully!');
        await fetchUserDetails();

    } catch (error) {
        console.error('❌ Update error:', error);
        alert(`Update failed: ${error.message}`);
    } finally {
        if (updateButton) {
            updateButton.disabled = false;
            updateButton.textContent = originalText;
        }
    }
}

// Run when page loads
document.addEventListener('DOMContentLoaded', function () {
    fetchUserDetails();

    const form = document.getElementById('signup');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            updateUserInfo();
        });
    }

    const updateButton = document.querySelector('.button1');
    if (updateButton) {
        updateButton.addEventListener('click', function (e) {
            e.preventDefault();
            updateUserInfo();
        });
    }

 const photoInput = document.createElement('input');
    photoInput.type = 'file';
    photoInput.accept = 'image/*';
    photoInput.style.display = 'none';

    document.getElementById('photo')?.addEventListener('click', function () {
        photoInput.click();
    });

    photoInput.addEventListener('change', function (e) {
        if (e.target.files?.[0]) {
            changeProfilePhoto(e.target.files[0]);
        }
    });

    document.body.appendChild(photoInput);
});
// Photo upload function
async function changeProfilePhoto(file) {
    try {
        console.log('Uploading photo:', file.name);

        const formData = new FormData();
        formData.append('photo', file);

        const photoElement = document.querySelector('#photo img');
        if (photoElement) photoElement.style.opacity = '0.5';

        const response = await fetch("https://hk.herova.net/data/change_photo.php", {
            method: 'POST',
            body: formData,
            credentials: 'include'
        });

        const result = await response.json();
        console.log('Photo upload response:', result);

        if (result.status !== 'success') {
            throw new Error(result.message || 'Photo upload failed');
        }

        if (result.new_photo_url) {
            // ✅ تحديث الصورة وإضافة `timestamp` لمنع التخزين المؤقت
            photoElement.src = `${result.new_photo_url}?t=${new Date().getTime()}`;
            alert('✅ Profile photo updated!');
        }
    } catch (error) {
        console.error('❌ Photo upload error:', error);
        alert(`Photo upload failed: ${error.message}`);
    } finally {
        const photoElement = document.querySelector('#photo img');
        if (photoElement) photoElement.style.opacity = '1';
    }
}
