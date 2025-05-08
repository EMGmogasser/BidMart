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
    files = event.target.files; // Store multiple files
    if (files.length > 0) {
    for (let i = 0; i < files.length; i++) {
      const markup = `
                <div class="preview">
                <img src="${URL.createObjectURL(
                  files[i]
                )}" alt="product preview">
                </div>
      `;
      imagePreview.insertAdjacentHTML("beforeend", markup);
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
        imageBlobs.push(fileList[i]);
    }

    const base64Strings = await Promise.all(imageBlobs.map(async (blob) => {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onloadend = () => resolve(reader.result.split(',')[1]); // Extract base64 part
            reader.readAsDataURL(blob);
        });
    }));
    localStorage.setItem('imageBlobs', JSON.stringify(base64Strings));
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

    if (delivery_date < current_date){
        inputFields["delivery_date"].parentElement.insertAdjacentHTML("beforeend", `<p class="error">*Delivery date can't be in the past</p>`);
        validity = false;
    }
    if (delivery_date > start_date){
        inputFields["start_date"].parentElement.insertAdjacentHTML("beforeend", `<p class="error">*Start date can't be before delivery date</p>`);
        validity = false;
    }
    return validity;
}

function clearErrors(){
    const errors = document.querySelectorAll('.error');
    errors.forEach(error=>error.remove());
}

async function postData() {
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
          title: "Upload success",
          confirmButtonText: "Retry",
          html: `
            <p>your product is being reviewed by the administrator takes one or two days</p>
            You will be redirected in <strong></strong> seconds.
            `,
          timer: 15000, 
          timerProgressBar: true,
          didOpen: () => {
            Swal.showLoading();
            const b = Swal.getHtmlContainer().querySelector("strong");
            let timerInterval = setInterval(() => {
              b.textContent = (Swal.getTimerLeft() / 1000).toFixed(0);
            }, 100);
          }
      }).then((result) => {
        /* Read more about handling dismissals below */
        //if (result.dismiss === Swal.DismissReason.timer) {
          window.location.href = "index.php"; // Replace with your URL
        //}
      });
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
    }
}

submitBtn.addEventListener("click", (e) => {
    e.preventDefault();
    clearErrors();
    collectFormData();
    const validity = validateData();
    if (validity){
        loaderModal.style.visibility = "visible";
        loaderModal.style.display = "block";
        window.location.href = 'seller_payment.php'
        // postData();
    }
});

manageCategories();