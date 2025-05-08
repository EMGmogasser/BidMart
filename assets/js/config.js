let cookies;
const signupAPI = "https://hk.herova.net/login_API/signUp-api.php";
const otpAPI = "https://hk.herova.net/login_API/SendOTP.php";
const verifyOtpAPI = "https://hk.herova.net/login_API/verfiyOTP.php";
const newsAPI = "https://hk.herova.net/InPageApi/news-api.php?page=1&limit=10";

// const proxySignupAPI =
  "https://cors-anywhere.herokuapp.com/https://hk.herova.net/login_API/signUp-api.php";
// const proxyOtpAPI =
  "https://cors-anywhere.herokuapp.com/https://hk.herova.net/login_API/SendOTP.php";
// const proxyVerifyOtpAPI =
  "https://cors-anywhere.herokuapp.com/https://hk.herova.net/login_API/verfiyOTP.php";

export const helper = {
  getNews: async function () {
    try {
      const response = await fetch(newsAPI);
      const data = await response.json();
      const news = data.data;

      return news;
    } catch (error) {
      return [];
    }
  },

  signup: async function (userData) {
    try {
      const request = await fetch(signupAPI, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(userData),
      });

      const response = await request.json();
      const data = response.data;
      //console.log(response);

      //this.setCookies([
      //["EMAIL", data.email],
      // ["USER_ID", data.id],
      // ["USER_NAME", data.name],
      //]);

      return response;
    } catch (error) {
      console.error("Error:", error);
      return {
        status: "error",
        message: "Something went wrong!\nNetwork error",
      };
    }
  },

  sendOTP: async function () {
    try {
      const request = await fetch(otpAPI, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          // Cookie: document.cookie,
        },
        body: JSON.stringify({
          key: "SM",
        }),
        credentials: "include",
        redirect: "follow",
      });

      const response = await request.json();
      //console.log(response);

      return response;
    } catch (error) {
      console.error("Error:", error);
      return {
        status: "error",
        message: "Something went wrong!\nNetwork error",
      };
    }
  },

  verifyOTP: async function (enteredOTP) {
    //console.log(enteredOTP);

    try {
      const request = await fetch(verifyOtpAPI, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          // Cookie: document.cookie,
        },
        body: JSON.stringify({
          otp: enteredOTP,
        }),
        credentials: "include",
        redirect: "follow",
      });

      const response = await request.json();
      //console.log(response);

      return response;
    } catch (error) {
      console.error("Error:", error);
      return {
        status: "error",
        message: "Something went wrong!\nNetwork error",
      };
    }
  },

  getAllCookies: async function () {
    const request = await fetch("https://hk.herova.net/login_API/cookies.php");
    cookies = await request.json();
    //console.log(cookies);

    return cookies;
  },

  getCookie: function (key) {
    helper.getAllCookies();
    //console.log(cookies.key);
    return cookies[`${key}`];
  },

  setCookies: function (cookies) {
    cookies.forEach(([key, value]) => {
      document.cookie = `${key}=${value};`;
    });
  },

  renderSwiperData: function (container, products, ended = false) {
  if (!products || !Array.isArray(products)) {
    console.log(`${container.classList[0]} has no products or products is not an array`);
    return;
  }

  container.innerHTML = "";

  products.forEach((product) => {
    let photo;
    try {
      photo = JSON.parse(product.PHOTO) || "assets/img/product.png";
    } catch (e) {
      photo = "assets/img/product.png";
    }

    const productMarkup = `
      <div class="swiper-slide ${ended ? 'finished-bid' : ''} product">
        <div class="img loading-img">
          <div class="spinner"></div>
          <img src="${photo}" alt="product" loading="lazy">
        </div>
        <div class="details">
          <p class="title">${product.ITEM_NAME || 'No Title'}</p>
          ${ended ? `
            <p class="sold-out">Sold out</p>
            <p class="last-price">Sold for:
              <span class="last-price"> $${product.STARTING_PRICE || 299.99}</span>
            </p>
          ` : `
            <p class="end-date">Auction End Date:
              <span class="end-date">${product.END_DATE || 'No Date'}</span>
            </p>
            <p class="last-price">Now Bid:
              <span class="last-price"> $${product.STARTING_PRICE || 299.99}</span>
            </p>
            <a href="bids.php?id=${product.I_ID}" class="primary-btn enroll">Enroll Now</a>
          `}
        </div>
      </div>
    `;

    container.insertAdjacentHTML("beforeend", productMarkup);
  });

  // Remove spinner on whole swiper
  container.parentElement.parentElement.classList.remove("loading");

  // Remove spinner on loaded img
  container.querySelectorAll("img").forEach((image) => {
    image.addEventListener("load", () => {
      image.parentElement.classList.remove("loading-img");
    });
    image.addEventListener("error", () => {
      image.parentElement.classList.remove("loading-img");
      image.src = "assets/img/product.png"; // Fallback image on error
    });
  });
  },

  renderGridProducts:function(container, products){
    if (!products || !Array.isArray(products)) {
    console.log(`${container.classList[0]} has no products or products is not an array`);
    return;
  }

    products.forEach((product) => {
      let photo;
      try {
        photo = JSON.parse(product.PHOTO) || "assets/img/product.png";
      } catch (e) {
        photo = "assets/img/product.png";
      }

      const productMarkup = `
        <div class="swiper-slide  product">
          <div class="img loading-img">
            <div class="spinner"></div>
            <img src="${photo}" alt="product" loading="lazy">
          </div>
          <div class="details">
            <p class="title">${product.ITEM_NAME || 'No Title'}</p>
            
              <p class="end-date">Auction End Date:
                <span class="end-date">${product.END_DATE || 'No Date'}</span>
              </p>
              <p class="last-price">Now Bid:
                <span class="last-price"> $${product.STARTING_PRICE || 299.99}</span>
              </p>
              <a href="bids.php?id=${product.I_ID}" class="primary-btn enroll">Enroll Now</a>
          </div>
        </div>
      `;

      container.insertAdjacentHTML("beforeend", productMarkup);
    });

    // Remove spinner on whole swiper
    container.parentElement.parentElement.classList.remove("loading");

    // Remove spinner on loaded img
    container.querySelectorAll("img").forEach((image) => {
      image.addEventListener("load", () => {
        image.parentElement.classList.remove("loading-img");
      });
      image.addEventListener("error", () => {
        image.parentElement.classList.remove("loading-img");
        image.src = "assets/img/product.png"; // Fallback image on error
      });
    });
  },

  getCategories: async  function(){
    const promise = await fetch(`https://hk.herova.net/products/fetch.php?key=2`);
    const cats = await promise.json();
    return cats.data;
  },

  setCategoriesDOM:async function(container) {
    const categories = await helper.getCategories();
    categories.forEach(cat=>{
      const markup = `
                  <a href="products.php?id=${cat.CAT_ID}">
                      <div class="cat">
                          <img src="${cat.IMG}" alt="${cat.CAT_NAME} | BidMart" loading="lazy">
                          <p>${cat.CAT_NAME}</p>
                      </div>
                  </a>`
      
      container.insertAdjacentHTML('beforeend',markup);
    })
  }
};
