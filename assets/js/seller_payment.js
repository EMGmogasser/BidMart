import { helper } from "./config.js";

const loaderModal = document.querySelector(".full-c");
const proceedBtn = document.querySelector(".proceed");
const basicFeesDOM = document.querySelector(".basic.fees");
const taxFeesDOM = document.querySelector(".tax.fees");
const totalFeesDOM = document.querySelector(".total.fees");
let formData;

proceedBtn.addEventListener('click',processPayment)

async function receiveImages() {
    const base64Strings = JSON.parse(localStorage.getItem('imageBlobs'));
    if (base64Strings) {
        const imageBlobs = base64Strings.map((base64) => {
            const byteCharacters = atob(base64);
            const byteArrays = [];
            for (let offset = 0; offset < byteCharacters.length; offset += 512) {
                const slice = byteCharacters.slice(offset, offset + 512);
                const byteNumbers = new Array(slice.length);
                for (let i = 0; i < slice.length; i++) {
                    byteNumbers[i] = slice.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                byteArrays.push(byteArray);
            }
            return new Blob(byteArrays, { type: 'image/jpeg' }); // Adjust type as needed
        });
        // Use imageBlobs
        return imageBlobs;
    }
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

async function prepareProduct(){
    const formObject = await JSON.parse(localStorage.getItem("product"));
    calcFees(formObject);
    const images = await receiveImages();
    formData = objectToFormData(formObject);
    for (let i = 0; i < images.length; i++) {
        formData.append("photo[]", images[i]); 
    }
    // localStorage.removeItem("imageBlobs");
    // localStorage.removeItem("product");
} 

function calcFees(formObject){
    const distance = Math.round(+JSON.parse(formObject.location).distance);
    const distanceFeesEGP = distance < 3 ?  15 : distance < 6 ? 20 : distance < 10 ? 30 : distance < 15 ? 40 : distance < 50 ? distance.toFixed(0) * 3 : distance.toFixed(0) * 2.85;   
    const distanceFeesUSD = distanceFeesEGP / 50;
    const priceFees = ((+formObject.starting_price + +formObject.expected_price)/2) * 0.05;
    basicFeesDOM.textContent = (parseFloat(priceFees) + parseFloat(distanceFeesUSD)).toFixed(2) + '$';
    taxFeesDOM.textContent = ((parseFloat(priceFees) + parseFloat(distanceFeesUSD))*0.025).toFixed(2) + '$';
    totalFeesDOM.textContent = ((parseFloat(priceFees) + parseFloat(distanceFeesUSD))*1.025).toFixed(2) + '$';
    console.log(`distance fees : ${distanceFeesUSD} USD , product fees : ${priceFees} USD`);
}

async function processPayment(){
    loaderModal.style.visibility = "visible";
    loaderModal.style.display = "block";
    const uploaded = postData(formData);
    const userName = await helper.getCookie('USER_NAME');
    console.log(userName);
    if (uploaded) {
        const url = `https://hk.herova.net/payment/pay4new.php?name=${userName}&price=${+totalFeesDOM.textContent.slice(0, -1)}`;
        window.location.href=url;
    }
}

async function postData(formData) {
    const url = "https://hk.herova.net/products/new_Product.php";
    try {
        const response = await fetch(url, {
            method: "POST",
            body: formData,
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status},message ${response.message}`);
        }
        
        const responseData = await response.json();
        loaderModal.style.visibility = "hidden";
        loaderModal.style.display = "none";
        Swal.fire({
            icon:"success",
            title: "product Uploaded successfully",
            html: `
            <p>your product is being reviewed by the administrator takes one or two days</p>
            You will be redirected in <strong></strong> seconds.
            `,
            // timer: 10000, 
            // timerProgressBar: true,
            // didOpen: () => {
            //     Swal.showLoading();
            //     const b = Swal.getHtmlContainer().querySelector("strong");
            //     let timerInterval = setInterval(() => {
                //     b.textContent = (Swal.getTimerLeft() / 1000).toFixed(0);
                //     }, 100);
                // }
            }).then((result) => {
                window.location.href = "index.php"; // Replace with your URL
                //}
            });
            return true;
        } catch (error) {
            // console.log('swal success');
            Swal.fire({
                title: "Register Failure",
                text: error.message,
                icon: "error",
                confirmButtonText: "Retry",
            });
            loaderModal.style.visibility = "hidden";
            loaderModal.style.display = "none";
            return false;
        }
    }        
    
    prepareProduct();