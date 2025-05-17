document.addEventListener('DOMContentLoaded', () => {
  displayProducts();
});

async function fetchProducts() {
  try {
    const response = await fetch('https://hk.herova.net/data/user_products.php', {
      method: 'GET',
      headers: { 'Content-Type': 'application/json' },
      credentials: 'include'
    });
    
    const result = await response.json();
    return result.status === 'success' && result.data ? result.data : [];
  } catch (error) {
    console.error('Error fetching products:', error);
    return [];
  }
}

async function displayProducts() {
  const products = await fetchProducts();
  const productsContainer = document.querySelector('.myOrders');
  productsContainer.innerHTML = '';

  if (products.length > 0) {
    products.forEach((product, index) => {
      const productElement = document.createElement('div');
      productElement.classList.add('orderdetails');
      
      let photoUrl = '';
      try {
        const photoArray = JSON.parse(product.PHOTO);
        if (Array.isArray(photoArray) && photoArray.length > 0) {
          photoUrl = photoArray[0];
        }
      } catch (e) {
        console.error('Error parsing product photo', e);
      }

      // ✅ حساب end date
      let endDateFormatted = 'N/A';
      try {
        const startDate = new Date(product.START_DATE);
        const period = parseInt(product.PERIOD_OF_BID) || 0;
        const endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + period);
        endDateFormatted = endDate.toISOString().split('T')[0]; // yyyy-mm-dd
      } catch (err) {
        console.error('Error calculating end date:', err);
      }

      // ✅ التحقق من حالة STATUS
      const isEnded = product.STATUS && product.STATUS.toLowerCase() === 'ended';

      productElement.innerHTML = `
        <div class="number"><h3>${index + 1}.</h3></div>
        <div class="photo"><img src="${photoUrl}" alt="${product.ITEM_NAME}"></div>
        <div class="O-content">
          <div class="name"><h4>${product.ITEM_NAME}</h4></div>
          <div class="name"><h5>Auction End Date: <span>${endDateFormatted}</span></h5></div>
          <div style="margin-bottom: 5px; color:var(--color-primary);">
            <p>*${product.PARTICIPANTS || 0} people rolled in</p>
          </div>
          <div class="name" style="margin-top: 15px; color:var(--color-primary);">
            <h2>$${product.STARTING_PRICE}</h2>
          </div>
          
          <!-- ✅ هنا بنضيف شرط لإخفاء الأزرار وعرض النص "Ended" إذا كان المنتج Ended -->
          <div class="buttons">
            ${isEnded 
              ? `<span style="color: var(--color-primary); font-weight: bold;">Ended</span>` 
              : `
                <button class="button1 endBtn">End</button>
                <button class="bttn cancelBtn">Cancel`
            }
          </div>
        </div>
      `;

      // ✅ التنقل لصفحة المنتج
      productElement.addEventListener('click', () => {
        window.location.href = `bids.php?id=${product.I_ID}`;
      });

      // ✅ منع التحويل عند الضغط على الأزرار
      if (!isEnded) {
        productElement.querySelector('.endBtn').addEventListener('click', (e) => {
          e.stopPropagation();
          e.preventDefault();
          handleAction(product, 'ended');
        });

        productElement.querySelector('.cancelBtn').addEventListener('click', (e) => {
          e.stopPropagation();
          e.preventDefault();
          handleAction(product, 'canceled');
        });
      }

      productsContainer.appendChild(productElement);
    });
  } else {
    productsContainer.innerHTML = '<p>No products available</p>';
  }
}

function handleAction(product, actionType) {
  const messages = {
    canceled: {
      title: 'Are you sure you want to cancel?',
      text: 'If you cancel, 25% of the amount you paid will be deducted.',
      icon: 'warning'
    },
    ended: {
      title: 'End Auction?',
      text: 'Are you sure you want to end this auction?',
      icon: 'question'
    }
  };

  Swal.fire({
    title: messages[actionType].title,
    text: messages[actionType].text,
    icon: messages[actionType].icon,
    showCancelButton: true,
    confirmButtonText: 'Continue',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      requestPassword(product, actionType);
    }
  });
}

function requestPassword(product, actionType) {
  Swal.fire({
    title: 'Enter your password',
    input: 'password',
    inputPlaceholder: 'Enter your password',
    showCancelButton: true,
    confirmButtonText: 'Submit',
    cancelButtonText: 'Cancel'
  }).then(async (pwdResult) => {
    if (pwdResult.isConfirmed) {
      await sendActionRequest(product, pwdResult.value, actionType);
    }
  });
}

async function sendActionRequest(product, password, actionType) {
  try {
    Swal.fire({
      title: 'Please wait...',
      text: 'Processing your request...',
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading()
    });

    // const formData = new FormData();
    // formData.append('I_ID', product.I_ID);
    // formData.append('PASSWORD', password);
    // formData.append('STATUS', actionType);
    const dataObj = {
      I_ID:product.I_ID,
      PASSWORD:password,
      STATUS:actionType
    }

    const response = await fetch('https://hk.herova.net/data/p_status.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
      },
      body: JSON.stringify(dataObj),
      credentials: 'include'
    });

    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const result = await response.json();
    if (result.status === 'success') {
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: `Your request to ${actionType} the item was processed successfully.`
      });
      displayProducts();
    } else {
      throw new Error(result.message || 'Action failed');
    }
  } catch (error) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: error.message
    });
  }
}
