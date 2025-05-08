const swiperClasses = [
  ".trending",
  ".future",
  ".current",
  ".finished",
  ".recommended",
];

const swiperInstances = [];

function initializeSwiper(selector) {
  // Shared settings for all swipers
  const settings = {
    loop: true,
    spaceBetween: 30,
    // centeredSlides: true,
    speed: 500,
    autoplay: {
      delay: 2500,
      disableOnInteraction: true,
    },
    navigation: {
      nextEl: `${selector} .button-next`,
      prevEl: `${selector} .button-prev`,
    },
    keyboard: {
      enabled: false,
    },
    breakpoints: {
      1440: { slidesPerView: 4 },
      1024: { slidesPerView: 3.5, spaceBetween: 50 },
      768: { slidesPerView: 2.5 },
      640: { slidesPerView: 2 },
      480: { slidesPerView: 1.5 },
      320: { slidesPerView: 1 },
    },
  };

  // Initialize the swiper
  return new Swiper(selector + " .swiper", settings);
}

swiperClasses.forEach((swiperClass) => {
  const swiperEL = document.querySelector(swiperClass);
  if (swiperEL) {
    const swiperInstance = initializeSwiper(swiperClass);

    swiperInstances.push(swiperInstance);

    swiperEL.addEventListener("click", () => enableKeyboard(swiperInstance));
    swiperEL.addEventListener("touchstart", () =>
      enableKeyboard(swiperInstance)
    );
    swiperEL.addEventListener("focusin", () => enableKeyboard(swiperInstance));
  }
});

let activeSwiper = null;

// Enable keyboard for the selected Swiper and disable for others
function enableKeyboard(swiper) {
  if (activeSwiper !== swiper) {
    if (activeSwiper) activeSwiper.keyboard.disable(); // Disable previous active Swiper
    swiper.keyboard.enable(); // Enable current Swiper
    activeSwiper = swiper;
  }
}
