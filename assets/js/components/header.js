import { helper } from "../config.js";
// ======= DOM Elements =======
const body = document.querySelector("body");
const menuBtn = document.getElementById("menu");
const navBar = document.querySelector(".navbar");
const navOverlay = document.querySelector(".nav-overlay");
const newsContainer = document.querySelector(".news");
const newsText = document.querySelector(".news-content");

const loginBtn = document.querySelector(".login-btn");
const logoutBtn = document.getElementById('logout');
const logoutAnchor = document.getElementById('logout-anchor');
const profile = document.querySelector(".bottom-nav .profile");
const profileName = document.querySelector(".bottom-nav .profile p");
const redirectBtn1 = document.querySelector(".redirect1");
const uploadProductAnchor = document.querySelector(".upload-product");

// categories constants
const customSelect 	= document.getElementById("customSelect");
const button = document.getElementById("customSelectButton");
const list = document.getElementById("customSelectList");
const arrow = document.querySelector('.arrow');
const width = getWindowWidth();


let cookies,userProfile;

// ======= login click =======
loginBtn.addEventListener(
  "click",
  () => (window.location.href = loginBtn.dataset.href)
);

// ======= cookies Check =======

async function setUserProfile() {
  cookies = await helper.getAllCookies();
  userProfile = cookies.EMAIL
    ? "buyer-NA"
    : cookies.HKH
    ? "seller-A"
    : cookies.HKHN
    ? "seller-NA"
    : cookies.HK
    ? "buyer-A"
    : "guest";
  localStorage.setItem("userProfile", userProfile);
  localStorage.setItem("user_id", cookies.HK);
  // redirects
  // header anchor
  uploadProductAnchor.href =
    userProfile === "seller-A"
      ? "hold.php"
      : userProfile === "seller-NA"
      ? "whatsappOTP.php"
      : userProfile === "buyer-A"
      ? "signup_bid.php"
      : userProfile === "buyer-NA"
      ? "otp.php"
      : "login.php";

  if (cookies.USER_NAME && width <= 780){
    logoutAnchor.style.display = "block";
  } else {
    logoutAnchor.style.display = "none";
  }

  if (window.location.href.includes("index.php")) {
    redirectBtn1.href =
      userProfile === "seller-A"
        ? "hold.php"
        : userProfile === "seller-NA"
        ? "whatsappOTP.php"
        : userProfile === "buyer-A"
        ? "signup_bid.php"
        : userProfile === "buyer-NA"
        ? "otp.php"
        : "login.php";
    redirectBtn1.textContent =
      userProfile === "guest"
        ? "Join now !"
        : userProfile === "buyer-NA"
        ? "Join now !"
        :  "Add Yours !";
  }
  if (cookies.USER_NAME) {
    loginBtn.innerHTML = cookies.USER_NAME;
    logoutBtn.style.display = 'block';
    loginBtn.dataset.href = "setting.php";
    profile.href = "setting.php";
    profileName.textContent = cookies.USER_NAME;
  }
}

// ======= categories control =======
function getWindowWidth() {
  if (typeof window.innerWidth === 'number') {
    // Non-IE
    return window.innerWidth;
  } else if (document.documentElement && document.documentElement.clientWidth) {
    // IE 6+ in standards mode
    return document.documentElement.clientWidth;
  } else if (document.body && document.body.clientWidth) {
    // IE 4 compatible
    return document.body.clientWidth;
  }
  return null; // or 0, or handle the lack of support as needed.
}

async function managceCategories(){
  const categories = await helper.getCategories();
  categories.forEach(cat=>{
    const markUp = `<li role="option"><a href="products.php?id=${cat.CAT_ID}">${cat.CAT_NAME}</a></li>`;
    list.insertAdjacentHTML('beforeend',markUp)
  })
}

// Open the dropdown
function openSelect() {
	customSelect.classList.add("open");
	button.setAttribute("aria-expanded", "true");
	list.style.display = "block";
  arrow.innerHTML = `<i class="fa-solid fa-angle-up"></i>`
}

// Close the dropdown
function closeSelect() {
	customSelect.classList.remove("open");
	button.setAttribute("aria-expanded", "false");
	list.style.display = "none";
  arrow.innerHTML =`<i class="fa-solid fa-chevron-down"></i>`;
}

// Toggle dropdown on button click
button.addEventListener("click", () => {
	customSelect.classList.contains("open") ? closeSelect() : openSelect();
});

// Close the dropdown if clicking outside the custom select
document.addEventListener("click", (e) => {
	if (!customSelect.contains(e.target)) closeSelect();
});


// Toggle sidebar visibility
menuBtn.addEventListener("click", () => {
  navBar.classList.toggle("active");
});

// Hide sidebar when clicking on the overlay
navOverlay.addEventListener("click", () => {
  navBar.classList.remove("active");
});

// ======= Active Page Marking =======
(function markActivePage() {
  let path = window.location.pathname.split("/").pop() || "index.php";

  document.querySelectorAll(".nav-item.active").forEach((link) => {
    link.classList.remove("active");
  });

  document.querySelectorAll(`.nav-item[id="${path}"]`).forEach((navItem) => {
    navItem.classList.add("active");
  });
})();

// ======= News Control =======

// Example news response

// Handle news display
async function controlNews() {
  const newsArr = await helper.getNews();

  if (newsArr == []) return;
  cycleNewsItems(newsArr, 0);
}

function cycleNewsItems(newsItems, index) {
  if (index >= newsItems.length) index = 0;

  const currentItem = newsItems[index];

  // Update DOM with current news item
  newsContainer.dataset.id = currentItem.NEWS_ID;
  newsContainer.dataset.time = 1; //currentItem.news_timer;
  newsText.textContent = currentItem.CONTENT;
  newsText.href = currentItem.LINK;
  // Update text scrolling speed
  updateTextSpeed();

  // Schedule the next news item
  setTimeout(() => {
    cycleNewsItems(newsItems, index + 1);
  }, (1 * 60 + 20) * 1000); //currentItem.news_timer instead of 1
}

// Update text scroll animation duration based on text and container width
function updateTextSpeed() {
  const textWidth = newsText.offsetWidth;
  const containerWidth = newsText.parentElement.offsetWidth;
  const speed = 100; // Speed in pixels per second
  const duration = (textWidth + containerWidth) / speed;

  newsText.style.animationDuration = `${duration}s`;
}

// Start controlling news and adjust scrolling on resize
managceCategories();
setUserProfile();
controlNews();
window.addEventListener("resize", updateTextSpeed);
	