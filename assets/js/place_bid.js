const imageUpload = document.getElementById("imageUpload");
const imgContainer = document.querySelector(".upload-container");
const imagePreview = document.querySelector(".previews");
const uploadContainer = document.querySelector(".upload-container");
const submitBtn = document.querySelector(`input[type='submit']`);
const categoriesDOM = document.querySelector("#category");
const loaderModal = document.querySelector(".full-c");
let files = []; 
const inputFields = {
    name: document.getElementById("productName"),
    description: document.getElementById("description"),
    starting_price: document.getElementById("startingPrice"),
    expected_price: document.getElementById("expectedPrice"),
    location: document.getElementById("location"),
    start_date: document.getElementById("startingDate"),
    delivery_date: document.getElementById("deliveryDate"),
    period_of_bid: document.getElementById("period"),
    category_id: categoriesDOM,
};
const formData = new FormData();
const formObject = {};

// image upload functionality
imageUpload.addEventListener("change", (event) => {
    const newFiles = Array.from(event.target.files); // Store multiple files
    const bias = files.length;
    files = files.concat(...newFiles);
    // console.log(newFiles,files);
    if (newFiles.length > 0) {
        for (let i = 0; i < newFiles.length; i++) {
            const markup = `
                        <div class="preview" id="preview-${bias+i}">
                            <a class="close"><i class="fa-solid fa-xmark"></i></a>
                            <img src="${URL.createObjectURL(
                            newFiles[i]
                            )}" alt="product preview">
                        </div>
            `;
            imagePreview.insertAdjacentHTML("beforeend", markup);
            document.querySelector(`#preview-${bias+i} .close`).addEventListener('click',e=>{
                const preview = e.target.closest('.preview');
                preview.remove();
                files[bias+i] = null;
                // console.log(files);
            });
        }
  }
});

// manage categories
async function fetchCategories() {
    const url = "https://hk.herova.net/products/fetch.php?key=2";
    try {
        const response = await fetch(url);

        if (!response.ok) {
            const errorText = await response.json(); 
            throw new Error(
                `HTTP error! status: ${response.status},  Details: ${errorText}`
            );
        }

        const res = await response.json();
        if (res) {
            return res.data;
        } else {
            console.error("Failed to retrieve product data.");
        }
    } catch (error) {
        console.error("Error fetching data:", error);
        return null;
    }
}

async function manageCategories() {
    const categories = await fetchCategories();
    if (categories) {
        categories.forEach((category) => {
            const markup = `<option value="${category.CAT_ID}">${category.CAT_NAME}</option>`;
            categoriesDOM.insertAdjacentHTML("beforeend", markup);
        });
    }
}

uploadContainer.addEventListener("dragover", (e) => {
    e.preventDefault();
    uploadContainer.classList.add("highlight"); 
});

uploadContainer.addEventListener("dragleave", () => {
    uploadContainer.classList.remove("highlight");
});

uploadContainer.addEventListener("drop", (e) => {
    e.preventDefault();
    uploadContainer.classList.remove("highlight");

    files = e.dataTransfer.files; // Store multiple files from drag and drop
    imageUpload.files = e.dataTransfer.files; // Set the dropped files to the input

    // Trigger the change event to handle preview logic (as above)
    const event = new Event("change", { bubbles: true });
    imageUpload.dispatchEvent(event);
});

// collecting form data
function collectFormData() {
    for (const key in inputFields) {
        if (inputFields.hasOwnProperty(key)) {
            const value = inputFields[key].value;
            formData.append(`${key}`, value);
            formObject[`${key}`] = value;
        }
    }
    //add the images
    files = files.filter(item => item !== null);
    console.log(files);
    for (let i = 0; i < files.length; i++) {
        formData.append("photo[]", files[i]); 
    }
    sendImages(files);
    formObject.photo = files;
    // console.log(formObject.photo);
    localStorage.setItem("product",JSON.stringify(formObject));
}

async function sendImages(fileList) {
    const imageBlobs = [];
    for (let i = 0; i < fileList.length; i++) {
        if (fileList[i] !== null) imageBlobs.push(fileList[i]);
    }

    const imageData = await Promise.all(imageBlobs.map(async (blob) => {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onloadend = () => {
                // Store the entire Data URL which includes the MIME type
                resolve({
                    dataUrl: reader.result,
                    type: blob.type,
                    name: blob.name || `image_${Date.now()}.${blob.type.split('/')[1]}` // Optional: preserve filename
                });
            };
            reader.readAsDataURL(blob);
        });
    }));
    
    localStorage.setItem('imageBlobs', JSON.stringify(imageData));
    // window.location.href = 'destination.html';
}

function validateData(){
    let validity = true;
    // check for no empty fields
    if(! formObject.photo.length) {
        imgContainer.insertAdjacentHTML("afterend", `<p class="error">*This field can't be empty</p>`);
        validity = false;
    }
    for (const key in formObject) {
        if (formObject.hasOwnProperty(key) && formObject[key]) { 
        //  console.log(key + ": " + formObject[key]);
        }
        else{
            inputFields[`${key}`].parentElement.insertAdjacentHTML("beforeend", `<p class="error">*This field can't be empty</p>`);
            validity = false;
        }
    }
    // title , description length
    if (formObject.name.length < 6 && formObject.name.length > 0) {
        inputFields["name"].parentElement.insertAdjacentHTML("beforeend", `<p class="error">*Product name should be at least 6 letters</p>`);
        validity = false;
    }
    if (formObject.description.length < 20 && formObject.description.length > 0) {
        inputFields["description"].parentElement.insertAdjacentHTML("beforeend", `<p class="error">*Product description should be at least 20 letters</p>`);
        validity = false;
    }
    // expected price
    if (+formObject["expected_price"] < +formObject['starting_price'] ){
        inputFields["expected_price"].parentElement.insertAdjacentHTML("beforeend", `<p class="error">*expected price can't be less than start price</p>`);
        validity = false;
    }
    // period of bid
    if (inputFields.period_of_bid > 60){
        inputFields["period_of_bid"].parentElement.insertAdjacentHTML("beforeend", `<p class="error">*Product period can't exceed 60 days</p>`);
         validity = false;
    }
    // validate dates
    const delivery_date = new Date(formObject.delivery_date);
    const start_date = new Date(formObject.start_date);
    const current_date = new Date();

    delivery_date.setHours(0, 0, 0, 0);
    start_date.setHours(0, 0, 0, 0);
    current_date.setHours(0, 0, 0, 0);
    console.log(delivery_date,start_date,current_date);

    if (delivery_date < current_date){
        inputFields["delivery_date"].parentElement.insertAdjacentHTML("beforeend", `<p class="error">*Delivery date can't be in the past</p>`);
        validity = false;
    }
    if (delivery_date > start_date){
        inputFields["start_date"].parentElement.insertAdjacentHTML("beforeend", `<p class="error">*Start date can't be before delivery date</p>`);
        validity = false;
    }
    // Calculate the difference in days between delivery and start date
    const timeDifference = start_date.getTime() - delivery_date.getTime();
    const dayDifference = timeDifference / (1000 * 3600 * 24);

    if (dayDifference < 3) {
        inputFields["start_date"].parentElement.insertAdjacentHTML("beforeend", `<p class="error">*There must be at least 3 days between delivery date and start date</p>`);
        validity = false;
    }
    return validity;
}

function clearErrors(){
    const errors = document.querySelectorAll('.error');
    errors.forEach(error=>error.remove());
}

// async function postData() {
//     const url = "https://hk.herova.net/products/new_Product.php";
//     try {
//         const response = await fetch(url, {
//             method: "POST",
//             body: formData,
//         });

//         if (!response.ok) {
//             throw new Error(`HTTP error! status: ${response.status},message ${response.message}`);
//         }

//         const responseData = await response.json();
//         loaderModal.style.visibility = "hidden";
//         loaderModal.style.display = "none";
//         Swal.fire({
//           icon:"success",
//           title: "Upload success",
//           confirmButtonText: "Retry",
//           html: `
//             <p>your product is being reviewed by the administrator takes one or two days</p>
//             You will be redirected in <strong></strong> seconds.
//             `,
//           timer: 15000, 
//           timerProgressBar: true,
//           didOpen: () => {
//             Swal.showLoading();
//             const b = Swal.getHtmlContainer().querySelector("strong");
//             let timerInterval = setInterval(() => {
//               b.textContent = (Swal.getTimerLeft() / 1000).toFixed(0);
//             }, 100);
//           }
//       }).then((result) => {
//         /* Read more about handling dismissals below */
//         //if (result.dismiss === Swal.DismissReason.timer) {
//           window.location.href = "index.php"; // Replace with your URL
//         //}
//       });
//     } catch (error) {
//         // console.log('swal success');
//         Swal.fire({
//             title: "Register Failure",
//             text: error.message,
//             icon: "error",
//             confirmButtonText: "Retry",
//         });
//         loaderModal.style.visibility = "hidden";
//         loaderModal.style.display = "none";
//     }
// }

submitBtn.addEventListener("click", (e) => {
    e.preventDefault();
    clearErrors();
    collectFormData();
    const validity = validateData();
    if (validity){
        loaderModal.style.visibility = "visible";
        loaderModal.style.display = "block";
        window.location.href = 'seller_payment.php';
        // postData();
    }
});

manageCategories();