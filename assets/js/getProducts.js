import { helper } from "./config.js";

"use strict";
const trendingBidsContainer = document.querySelector(".trending-bids");
const currentBidsContainer = document.querySelector(".current-bids");
const futureBidsContainer = document.querySelector(".future-bids");
const finishedBidsContainer = document.querySelector(".finished-bids");
const categoriesDOM = document.querySelector(".categories-list")

async function fetchProductData(key = "3") {
  
  const url = `https://hk.herova.net/products/fetch.php?key=${key}`;
  // const url = `https://corsproxy.io/?url=https://hk.herova.net/products/fetch.php?key=${key}`;

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


async function setProductsData() {
  const currentBids = await fetchProductData(1);
  helper.renderSwiperData(currentBidsContainer, currentBids);

  const trendingBids = await fetchProductData(3);
  helper.renderSwiperData(trendingBidsContainer, trendingBids);

  const endedBids = await fetchProductData(3);
  helper.renderSwiperData(finishedBidsContainer, endedBids, true);
  
  const soonBids = await fetchProductData(4);
  helper.renderSwiperData(futureBidsContainer, soonBids);
}

setProductsData();
helper.setCategoriesDOM(categoriesDOM);