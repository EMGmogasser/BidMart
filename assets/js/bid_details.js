import { helper } from "./config.js";

"use strict";
const poster = document.querySelector(".poster");
const title = poster.querySelector(".title");
const description = poster.querySelector(".description .text");
const urlParams = new URLSearchParams(window.location.search);
const posterImg = document.querySelector('.poster img');
const posterPrice = document.querySelector('.poster .start_price .text');
const posterMaxPrice = document.querySelector('.poster .current_price .text');
const popupMaxPrice = document.querySelector('.full-c .price');
const topBiddersDOM = document.querySelector(" .top_bidders .bidders");
const top3DOM = document.querySelectorAll('.top_bidder');
const recommendedBidsContainer = document.querySelector(".recommended-bids");
const id = urlParams.get("id");
const startDate = document.querySelector(".poster .start_date .text");
const endDate = document.querySelector(".poster .end_date .text");
const totalBidders = document.querySelector(".poster .bidders_number .text");
const time = document.querySelector('.full-c .timer');
//////////////////////////////////////////////////////////////////////////
const openBtn = document.querySelector("a.enroll");
let topPrice = null;
const topPriceDOM = document.querySelector("span.price");
const modal = document.querySelector(".full-c");
const closeBtn = document.querySelector("button.close");
const bidBtn = document.querySelector(".bidBtn");
const bidInput = document.querySelector("input.bidAmount");
let timer = 7

// control price popup
openBtn.addEventListener("click",()=>{
  modal.style.display = "block";
})
closeBtn.addEventListener("click",()=>{
  modal.style.display = "none";
})
modal.addEventListener('click',e=>{
  const close = e.target.closest("#loader");
  if (!close) modal.style.display = "none";
})
// 

async function fetchProduct() {
  if (!id) {
    console.error("404 page not found (كدا وكدا يعني)");
    return;
  }
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
      const photo = (JSON.parse(res.data.PHOTO) && JSON.parse(res.data.PHOTO)[0] !== undefined)? JSON.parse(res.data.PHOTO)[0] : "assets/img/product.png";
      posterImg.src = photo ;
      posterImg.addEventListener("load", () => {
        posterImg.parentElement.classList.remove("loading-img");
        posterImg.style.visibility = "visible";
    });
      posterPrice.textContent = res.data.STARTING_PRICE +'$';
      posterMaxPrice.textContent = res.data.STARTING_PRICE +'$';
      popupMaxPrice.textContent = res.data.STARTING_PRICE +'$';
      startDate.textContent = res.data.START_DATE;
      endDate.textContent = res.data.END_DATE;
      return res.data;
    } else {
      console.log("Failed to retrieve product data.");
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

async function fetchTopBidders() {
  const url = `https://hk.herova.net/bids/top3.php?id=${id}`;
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
            totalBidders.textContent = res.total_bidders;
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
  topPriceDOM.textContent = topPrice+'$';
  if(topPrice){
    posterMaxPrice.textContent = topPrice+'$';
    popupMaxPrice.textContent = topPrice+'$';
  }
  renderTopBidders(topBidders);
}

//control the bid amount
async function proceedPayment(){
  const amount = +bidInput.value;
  const total = amount*1.05;
  if (amount > +popupMaxPrice.textContent.slice(0, -1)){
    console.log(amount);
    const userName = await helper.getCookie('USER_NAME');
    console.log(userName);
    const url = `https://hk.herova.net/payment/pay4new.php?name=${userName}&price=${total}`;
    window.location.href=url;
    
  }
}
bidBtn.addEventListener('click', proceedPayment);
bidBtn.addEventListener('keydown', function(event) {
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

