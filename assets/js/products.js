import { helper } from "./config.js";
"use strict";

const categoriesContainer = document.querySelector(".categories-list");
const productsContainer = document.querySelector(".products-grid");
const emptyDOM = document.querySelector(".empty");
let page=1

async function fetchProductData() {
    // Get the current URL
    const href = new URL(window.location.href);

    // Get the ID parameter
    const id = href.searchParams.get('id');

    let url = `https://hk.herova.net/products/cat_p.php?page=${page}`;
    page++;
    if (id) url += `&id=${id}`;

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

async function manageProducts() {
    const products = await fetchProductData();
    if (products.length) {
        emptyDOM.style.display = "none";
        // Observe the "load-more" element
        observer.observe(document.getElementById("load-more"));

    }else {
        emptyDOM.style.display = "grid";
        document.getElementById("load-more").style.display = "none";
    };

    helper.renderGridProducts(productsContainer,products);
}

// intersection observer
async function loadMoreData() {
    const exist = document.querySelectorAll(".product");
    if (!exist.length ){
         return
        };
    
    const products = await fetchProductData();
    if (!products.length) {
        document.getElementById("load-more").style.display = "none";
        return;
    }
    helper.renderGridProducts(productsContainer,products);

    // Append the new data to the container
    observer.observe(document.getElementById("load-more")); // Re-observe
}

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      loadMoreData();
    }
  });
});


helper.setCategoriesDOM(categoriesContainer);
manageProducts();