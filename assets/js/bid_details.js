import { helper } from "./config.js";

"use strict";
const poster = document.querySelector(".poster");
const title = poster.querySelector(".title");
const description = poster.querySelector(".description .text");
const urlParams = new URLSearchParams(window.location.search);
// const posterImg = document.querySelector('.poster img');
const posterImgs = document.querySelector('.poster .poster-images');
const posterThumbs = document.querySelector('.poster .poster-thumbnails');
const posterPrice = document.querySelector('.poster .start_price .text');
const posterMaxPrice = document.querySelector('.poster .current_price .text');
const topBiddersDOM = document.querySelector(" .top_bidders .bidders");
const top3DOM = document.querySelectorAll('.top_bidder');
const recommendedBidsContainer = document.querySelector(".recommended-bids");
const id = urlParams.get("id");
const startDate = document.querySelector(".poster .start_date .text");
const endDate = document.querySelector(".poster .end_date .text");
const enrollmentNumber = document.querySelector(".poster .enrolls .text");
const biddingNumber = document.querySelector(".poster .bidders_number .text");
////////////////////////////////////////////////////////////////////////////////////
const time = document.querySelector('.full-c.bid .timer');

const bidPopupPrice = document.querySelector('.full-c.bid .price');
const enrollPopupPrice = document.querySelector('.full-c.enroll .price');

const enrollPopupBtn = document.querySelector("button.enroll");
const bidPopupBtn = document.querySelector("button.bid");

const bidPopup = document.querySelector(".full-c.bid");
const enrollPopup = document.querySelector(".full-c.enroll");

let topPrice = null;
const topPriceDOM = document.querySelector(".bid span.price");
const enrollCloseBtn = document.querySelector(".enroll button.close");
const bidCloseBtn = document.querySelector(".bid button.close");
const enrollBtn = document.querySelector(".enrollBtn");
const bidBtn = document.querySelector(".bidBtn");
const bidInput = document.querySelector("input.bidAmount");
let timer = 7

// custom images sliders
function initializeSwipers(){
  var swiper1 = new Swiper(".mySwiper", {
    loop: true,
    lazy: true,
    spaceBetween: 10,
    slidesPerView: 'auto',
    freeMode: true,
    watchSlidesProgress: true,
  });
  var swiper2 = new Swiper(".mySwiper2", {
    loop: true,
    lazy: true,
    spaceBetween: 10,
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    thumbs: {
      swiper: swiper1,
    },
  });
}
// control enroll popup
enrollPopupBtn.addEventListener("click",()=>{
  enrollPopup.style.display = "block";
})
enrollCloseBtn.addEventListener("click",()=>{
  enrollPopup.style.display = "none";
})
enrollPopup.addEventListener('click',e=>{
  const close = e.target.closest("#loader");
  if (!close) enrollPopup.style.display = "none";
})
// control bid popup
bidPopupBtn.addEventListener("click",()=>{
  bidPopup.style.display = "block";
})
bidCloseBtn.addEventListener("click",()=>{
  bidPopup.style.display = "none";
})
bidPopup.addEventListener('click',e=>{
  const close = e.target.closest("#loader");
  if (!close) bidPopup.style.display = "none";
})

function addImageSlide(imgUrl, container) {
  // Create the elements
  const a = document.createElement('a');
  if (container.classList.contains('posterImages')){
    a.href = imgUrl;
    a.target = '_blank';
  }

  const div = document.createElement('div');
  div.className = 'swiper-slide';

  const img = document.createElement('img');
  img.src = imgUrl;
  img.loading = 'lazy';

  // Nest elements
  a.appendChild(img);
  div.appendChild(a);

  // Append to container
  container.appendChild(div);
  // console.log(div);

  // Add event listener (example: log image URL on click)
  a.addEventListener('click', (e) => {
    // console.log('Image clicked:', imgUrl);
    // You can prevent opening the link if needed:
    // e.preventDefault();
  });
}


async function fetchProduct() {
  if (!id) {
    console.error("404 page not found (كدا وكدا يعني)");
    return;
  }
  // const cookies = await helper.getAllCookies('HK');
  // const user_id = cookies.HK;
  // console.log(user_id);
  const url = `https://hk.herova.net/bids/get_product_data.php?id=${id}`;
  try {
    const response = await fetch(url);

    if (!response.ok) {
      const errorText = await response.text(); // Try to get error details from the server
      throw new Error(
        `HTTP error! status: ${response.status},  Details: ${errorText}`
      );
    }

    const res = await response.json();
    if (res) {
    //   const photo = (JSON.parse(res.data.PHOTO) && JSON.parse(res.data.PHOTO)[0] !== undefined)? JSON.parse(res.data.PHOTO)[0] : "assets/img/product.png";
    //   poster.querySelector('.img').href = photo ;
    //   posterImg.src = photo ;
    //   posterImg.addEventListener("load", () => {
      //     posterImg.parentElement.classList.remove("loading-img");
      //     posterImg.style.visibility = "visible";
      // });
      
      // handle poster images and thumbnails\
      // console.log((+res.data.STARTING_PRICE * 0.2),enrollPopupPrice);
      enrollPopupPrice.textContent = (+res.data.STARTING_PRICE * 0.2).toFixed(2) +'$';
      const photos = (JSON.parse(res.data.PHOTO) && JSON.parse(res.data.PHOTO)[0] !== undefined)? JSON.parse(res.data.PHOTO) : ["assets/img/product.png"];
      // console.log(photos);  
      photos.forEach(img=>{
        // console.log(img,posterImgs);
        addImageSlide(img,posterImgs);
        addImageSlide(img,posterThumbs);
      })
      initializeSwipers();

      posterPrice.textContent = res.data.STARTING_PRICE +'$';
      posterMaxPrice.textContent = res.data.STARTING_PRICE +'$';
      bidPopupPrice.textContent = res.data.STARTING_PRICE +'$';
      startDate.textContent = res.data.START_DATE;
      endDate.textContent = res.data.END_DATE;
      return res.data;
    } else {
      // console.log("Failed to retrieve product data.");
    }
  } catch (error) {
    console.error("Error fetching data:", error);
    return null;
  }
}

async function fetchProductList(key = "3") {
  /**
   * Fetches product data from the specified endpoint.
   * @param {string} key - The product key to fetch data for (default: "3").
   * @returns {Promise<object|null>} - A Promise that resolves to a JSON object
   *                                  containing the product data, or null if
   *                                  an error occurs.
   */
  const url = `https://hk.herova.net/products/fetch.php?key=${key}`;
  // const url = `https://corsproxy.io/?url=http://hk.herova.net/products/fetch.php?key=${key}`;

  try {
    const response = await fetch(url);

    if (!response.ok) {
      const errorText = await response.text(); // Try to get error details from the server
      throw new Error(
        `HTTP error! status: ${response.status},  Details: ${errorText}`
      );
    }

    const res = await response.json();
    if (res) {
      
      return res.data;
    } else {
      console.log("Failed to retrieve product data.");
    }
  } catch (error) {
    console.error("Error fetching data:", error);
    return null;
  }
}

async function manageRecommended() {
  const products = await fetchProductList();
  helper.renderSwiperData(recommendedBidsContainer, products);
}

function updateEnrollment(status){
  // console.log(status);
  if (status){
    enrollPopupBtn.disabled = true;
    bidPopupBtn.disabled = false;
  }
  else{
    enrollPopupBtn.disabled = false;
    bidPopupBtn.disabled = true;
  }
}

async function fetchTopBidders() {
  const cookies = await helper.getAllCookies('HK');
  const user_id = cookies.HK;
  // console.log(user_id);
  const url = `https://hk.herova.net/bids/top3.php?id=${id}&uid=${user_id}`;
  try {
    const response = await fetch(url);

    if (!response.ok) {
      const errorText = await response.text(); // Try to get error details from the server
      throw new Error(
        `HTTP error! status: ${response.status},  Details: ${errorText}`
      );
    }

    const res = await response.json();
    if (res) {
            enrollmentNumber.textContent = res.total_enrolled;
            biddingNumber.textContent = res.total_bidders;
            updateEnrollment(res.user_status);
      return res.data;
    } else {
      console.log("Failed to retrieve product data.");
    }
  } catch (error) {
    console.error("Error fetching data:", error);
    return null;
  }
}

function addNewTopBidder(bidder, rank) {
  const element = document.querySelector(`.top_bidder[data-rank="${rank}"]`);
  element.dataset.rank = rank;
  element.id = bidder.BIDDER_ID;
  element.querySelector(".name").textContent = bidder.USER_NAME;
  element.querySelector("img").src = bidder.PHOTO;
  element.querySelector(".bid_price").textContent =
  Math.floor(bidder.BID_AMOUNT) + "$";
}

function swapRank(rank1, rank2) {
  const element1 = topBiddersDOM.querySelector(
    `.top_bidder[data-rank="${rank1}"]`
  );
  
  const element2 = topBiddersDOM.querySelector(
    `.top_bidder[data-rank="${rank2}"]`
  );
  element1.querySelector(".rank").textContent = rank2 + ".";
  element2.querySelector(".rank").textContent = rank1 + ".";

  element1.dataset.rank = rank2;
  element2.dataset.rank = rank1;
}

function renderTopBidders(top3) {
  for (let i = 0; i < 3; i++) {
    const bidderDOM = topBiddersDOM.querySelector(
      `.top_bidder[data-rank="${i + 1}"]`
    );
    if (i >= top3.length){
      bidderDOM.dataset.set="false";
      bidderDOM.id=0;
      continue;
    }
    bidderDOM.dataset.set="true";

    if ( bidderDOM.id && +top3[i].BIDDER_ID !== +bidderDOM.id) {
      const found = top3.find((bidder) => +bidderDOM.id === +bidder.BIDDER_ID);

      if (found) {
        const newRank = top3.indexOf(found) + 1;
        const oldRank = bidderDOM.dataset.rank;

        //swap 2 ranks
        bidderDOM.querySelector(".bid_price").textContent = top3[newRank-1].BID_AMOUNT+'$';
        swapRank(newRank, oldRank);
        // addNewTopBidder(top3[newRank - 1], oldRank);
      } else {
        addNewTopBidder(top3[i], i + 1);
      }
    }else if (bidderDOM.id && +top3[i].BIDDER_ID == +bidderDOM.id ){
      const price = bidderDOM.querySelector(".bid_price");
      price.textContent= top3[i].BID_AMOUNT + '$';
    }
  }
}

async function manageProduct() {
  const product = await fetchProduct();
  // console.log(product);
  poster.id = product.I_ID;
  
  title.textContent = product.ITEM_NAME;
  description.textContent = product.DESCRIPTION;
}

async function manageTopBidders() {
  const topBidders = await fetchTopBidders();
  if (!topBidders.length){
    top3DOM.forEach(bidder => bidder.style.visibility="hidden");
    document.querySelector(".empty").style.display="flex";
    return
  }
  top3DOM.forEach(bidder => bidder.style.visibility="visible");
  document.querySelector(".empty").style.display="none";
  topPrice = +topBidders[0].BID_AMOUNT;
  // topPriceDOM.textContent = topPrice+'$';
  topPriceDOM.textContent = topPrice+'$';
  if(topPrice){
    // console.log(topPrice);
    posterMaxPrice.textContent = topPrice+'$';
    bidPopupPrice.textContent = topPrice+'$';
  }
  renderTopBidders(topBidders);
}

//control the bid amount

async function proceedBidding(){
  const cookies = await helper.getAllCookies('HK');
  const params = new URLSearchParams(window.location.search);
  const amount = +bidInput.value;
  bidInput.value='';
  bidBtn.disabled = true;
  setTimeout(()=>{
    bidBtn.disabled = false;
  },3000)

  const url = `https://hk.herova.net/bids/newbid.php`
  const Bid = {
    BID_AMOUNT: amount,
    BIDDER_ID: cookies.HK,
    I_ID: params.get('id')
  }
  // if (amount <= +bidPopupPrice.textContent.slice(0, -1)) return;

  try {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Bid),
    });
    const responseData = await response.json();
    if (!response.ok) {
        throw new Error(`${responseData.message}`);
    }
    
    console.log(responseData);
    bidPopup.style.display = "none";
    manageTopBidders();
    Swal.fire({
        icon:"success",
        title: "You Bidded Successfully !",
        confirmButtonText: "Ok",
    })
    return true;
} catch (error) {
    console.log(error);
    Swal.fire({
        title: "Failed to Bid in Auction",
        text: error.message,
        icon: "error",
        confirmButtonText: "Ok",
    })
    return false;
}
}

async function proceedPayment(){
  const params = new URLSearchParams(window.location.search);
  const pid = params.get('id');
  const amount = +enrollPopupPrice.textContent.slice(0,-1)*1.05;
  const userName = await helper.getCookie('USER_NAME');
  localStorage.setItem('pid',pid);
  // console.log(userName,pid);
  const url = `https://hk.herova.net/payment/pay4new.php?name=${userName}&price=${amount}`;
  window.location.href=url;   
}

enrollBtn.addEventListener('click', proceedPayment);
bidBtn.addEventListener('click', proceedBidding);
enrollBtn.addEventListener('keydown', function(event) {
  if (event.key === 'Enter') proceedPayment();
});

manageProduct();
manageTopBidders();
setInterval(()=>{
  if (!timer) {
    timer = 7;
    manageTopBidders();
  }
  else timer--;
  time.textContent = timer;
},1000);
manageRecommended();

