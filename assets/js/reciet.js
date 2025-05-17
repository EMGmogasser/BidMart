import { helper } from "./config.js";
const loader = document.querySelector('.full-c')
const params = new URLSearchParams(window.location.search);
const tap_id = params.get('tap_id');
const url=`https://hk.herova.net/payment/ret_pay.php?tap_id=${tap_id}`;
const statusDom = document.querySelector('.status');
const id = document.querySelector('.id');
const user = document.querySelector('.user');
const amount = document.querySelector('.amount');
const fees = document.querySelector('.fees');
const total = document.querySelector('.total');
let targetPage ='index.php';
let formData;

const proceedBtn = document.querySelector('button.proceed');
proceedBtn.addEventListener('click', () => {
    window.location.href = targetPage;
});

async function receiveImages() {
    const storedData = localStorage.getItem('imageBlobs');
    if (!storedData) return [];
    
    const imageData = JSON.parse(storedData);
    return imageData.map(item => {
        // You can use the dataUrl directly in img src attributes
        // Or if you need a File/Blob object:
        const byteString = atob(item.dataUrl.split(',')[1]);
        const mimeType = item.type;
        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);
        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ab], { type: mimeType });
    });
    
}

function objectToFormData(obj) {
    const formData = new FormData();
    for (const key in obj) {
        if (obj.hasOwnProperty(key)) {
        formData.append(key, obj[key]);
        }
    }
    return formData;
}

async function postData(formData) {
    console.log('upload');
    let response;
    // console.log(formData.get(photo));
    const url = "https://hk.herova.net/products/new_Product.php";
    try {
        response = await fetch(url, {
            method: "POST",
            body: formData,
        });
        
        if (!response.ok) {
            throw new Error(`Server error occured please try again and if this problem is repeated make sure to take screenshot of the reciet and contact us`);
        }
        
        const responseData = await response.json();
        Swal.fire({
            icon:"success",
            title: "product Uploaded successfully",
            html: `
            <p>your product is being reviewed by the administrator takes one or two days</p>
            You will be redirected in <strong></strong> seconds.
            `,
        })
        localStorage.removeItem('product');
        localStorage.removeItem('imageBlops');
        loader.style.display = "none";
        proceedBtn.disabled=false;
        return true;
    } catch (error) {
        console.log(error , response);
        // console.log('swal success');
        Swal.fire({
            title: "Product upload failure",
            text: error.message,
            icon: "error",
            confirmButtonText: "Retry",
        }).then((result) => {
                setTimeout(()=>{
                    postData(formData);
                },3000)
            });
        return false;
    }
}

async function prepareProduct(id){
    const formObject = await JSON.parse(localStorage.getItem("product"));
    const images = await receiveImages();
    // console.log(images);
    formData = objectToFormData(formObject);
    for (let i = 0; i < images.length; i++) {
        formData.append("photo[]", images[i]); 
    }
    formData.append("TAB_ID", id); 

    return postData(formData);
} 

function generateAndDisplayQR() {
  const currentUrl = window.location.href;
  console.log(currentUrl);
  const container = document.querySelector('.qr-code-container');
  
  // Clear previous content
  container.innerHTML = '<p>Generating QR code...</p>';
  
  QRCode.toDataURL(currentUrl, { width: 200, margin: 2 }, (err, url) => {
    container.innerHTML = ''; // Clear loading message
    
    if (err) {
      container.innerHTML = '<p class="error">Failed to generate QR code</p>';
      return;
    }
    
    // Create QR image
    const qrImg = document.createElement('img');
    qrImg.src = url;
    qrImg.alt = 'Page QR Code';
    qrImg.className = 'qr-code-image';
    container.appendChild(qrImg);
    
  });
}

async function confirmPayment(reciet) {
    const url = `https://hk.herova.net/payment/confirme_pay.php`;
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(reciet),
        });

        const data = await response.json();
        const confirmation = data.status === 'success'? true : false;
        return confirmation;
    } catch (error) {
        console.error(error);
        return false;
    }
}
async function enrollProduct() {
    const url = 'https://hk.herova.net/payment/Enroll.php';
    const cookies = await helper.getAllCookies();
    const enrollment={
        user_id : cookies.HK,
        product_id : localStorage.getItem('pid'),
        tap_id : id.textContent,
        fees : total.textContent.split(' ')[0],
    }
    console.log(enrollment);
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(enrollment),
        });
        
        if (!response.ok) {
            throw new Error(`Server error occured please try again and if this problem is repeated make sure to take screenshot of the reciet and contact us`);
        }
        
        const responseData = await response.json();
        console.log(responseData);
        Swal.fire({
            icon:"success",
            title: "Enrolled Successfully !",
        })
        // localStorage.removeItem('product');
        // localStorage.removeItem('imageBlops');
        // loader.style.display = "none";
        // proceedBtn.disabled=false;
        return true;
    } catch (error) {
        console.log(error);
        // console.log('swal success');
        Swal.fire({
            title: "Failed to Enroll in Auction",
            text: error.message,
            icon: "error",
            confirmButtonText: "Ok",
        }).then((result) => {
            setTimeout(enrollProduct,3000)
                
            });
        return false;
    }
}

async function recietDetails(){
    try{
        const res = await fetch(url);
        const data = await res.json();    
        const cookies = await helper.getAllCookies();
        const userId = cookies.HK;
        const prevPage = localStorage.getItem('currentPage');
        const reason = prevPage === 'seller_payment.php'? 'UPLOAD' : 'ENROLL'

        const reciet = {
            USER_ID:+userId,
            TAB_ID:data.id,
            REASON: reason,
            // QR_CODE:qrData,
            STATUS:data.status,
            AMOUNT:data.amount,
        };

        if (reciet.STATUS ==='CAPTURED'){
            generateAndDisplayQR();
            const confirmed = await confirmPayment(reciet);
            console.log(confirmed);
            if (prevPage !=='seller_payment.php') {
                loader.style.display='none';
                proceedBtn.disabled=false;
                targetPage = prevPage;
                if (!confirmed){
                    Swal.fire({
                        title: "PAYMENT CONFIRM FAILURE",
                        text: "This payment reciet seems to be recorded before",
                        icon: "error",
                        // confirmButtonText: "Retry",
                    });
                    statusDom.textContent = "payment duplicate";
                    reciet.STATUS = 'DUPLICATE';
                } else{
                    enrollProduct();
                }
            }else {
                if (confirmed){        
                    const uploaded = prepareProduct(reciet.TAB_ID);
                    if (uploaded){
                        targetPage = 'setting.php#toggle3';
                    }else{
                        targetPage = 'place_bid.php';
                    }
                }else{
                    Swal.fire({
                        title: "PAYMENT CONFIRM FAILURE",
                        text: "This payment reciet seems to be recorded before",
                        icon: "error",
                        // confirmButtonText: "Retry",
                    });
                    statusDom.textContent = "payment duplicate";
                    reciet.STATUS = 'DUPLICATE';
                    targetPage = 'seller_payment.php';
                    loader.style.display='none';
                    proceedBtn.disabled=false;
                }
            }
        }
        else{
            Swal.fire({
                title: "PAYMENT FAILURE",
                text: "Your payment wasn't proceeded successfully please try agein",
                icon: "error",
                confirmButtonText: "Retry",
            });
            if (prevPage !=='seller_payment.php') {
                targetPage = prevPage
            }else{
                targetPage = 'seller_payment.php';
            }
        }

        // update DOM
        // console.log(reciet);
        statusDom.textContent = "payment "+ reciet.STATUS;
        statusDom.classList.add(reciet.STATUS);
        id.textContent = reciet.TAB_ID;
        user.textContent = data.customer.first_name;
        amount.textContent = (reciet.AMOUNT/1.05).toFixed(2)+" "+ data.currency;
        fees.textContent = (reciet.AMOUNT/1.05*0.05).toFixed(2)+" "+ data.currency;
        total.textContent = (reciet.AMOUNT).toFixed(2)+" "+ data.currency;
    }catch(error){
        console.error(error);
        Swal.fire({
            title: "An error has occured",
            text: error.message,
            icon: "error",
            confirmButtonText: "Retry",
        });
    }
}
recietDetails();