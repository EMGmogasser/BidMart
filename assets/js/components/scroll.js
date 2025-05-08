const scrollContainer = document.querySelector('.services_inner');
const dots = document.querySelectorAll('.dot');

let isDragging = false;
let startX;
let scrollLeft;

// Enable drag functionality
scrollContainer.addEventListener('mousedown', (e) => {
  isDragging = true;
  startX = e.pageX - scrollContainer.offsetLeft;
  scrollLeft = scrollContainer.scrollLeft;
  scrollContainer.style.cursor = 'grabbing';
});

scrollContainer.addEventListener('mouseleave', () => {
  isDragging = false;
  scrollContainer.style.cursor = 'grab';
});

scrollContainer.addEventListener('mouseup', () => {
  isDragging = false;
  scrollContainer.style.cursor = 'grab';
});

scrollContainer.addEventListener('mousemove', (e) => {
  if (!isDragging) return;
  e.preventDefault();
  const x = e.pageX - scrollContainer.offsetLeft;
  const walk = (x - startX) * 1.5; // Adjust scroll speed
  scrollContainer.scrollLeft = scrollLeft - walk;
});

// Update active dots on scroll
scrollContainer.addEventListener('scroll', () => {
  const cardWidth = document.querySelector('.service').offsetWidth + 16; // Card width + gap
  const scrollPosition = scrollContainer.scrollLeft;

  const currentIndex = Math.round(scrollPosition / cardWidth);
  dots.forEach((dot, index) => {
    dot.classList.toggle('active', index === currentIndex);
  });
});

// Dot navigation
dots.forEach((dot, index) => {
  dot.addEventListener('click', () => {
    const cardWidth = document.querySelector('.service').offsetWidth;
    scrollContainer.scrollTo({
      left: index * (cardWidth + 16), // card width + gap
      behavior: 'smooth',
    });

    dots.forEach((d) => d.classList.remove('active'));
    dot.classList.add('active');
  });
});
