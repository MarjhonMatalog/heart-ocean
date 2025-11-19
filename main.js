// Mobile menu functionality
const menuBtn = document.getElementById('menuBtn');
const mainNav = document.getElementById('mainNav');
const closeMenu = document.getElementById('closeMenu');

if (menuBtn && mainNav && closeMenu) {
  menuBtn.addEventListener('click', () => {
    mainNav.classList.add('active');
  });

  closeMenu.addEventListener('click', () => {
    mainNav.classList.remove('active');
  });

  // Close menu when clicking on links
  const navLinks = document.querySelectorAll('.nav a');
  navLinks.forEach(link => {
    link.addEventListener('click', () => {
      mainNav.classList.remove('active');
    });
  });
}

// Dark mode toggle
const darkToggle = document.getElementById('darkToggle');
if (darkToggle) {
  darkToggle.addEventListener('click', () => {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    document.documentElement.setAttribute('data-theme', newTheme);
    darkToggle.textContent = newTheme === 'dark' ? '☀️' : '🌙';
    
    // Save preference to localStorage
    localStorage.setItem('theme', newTheme);
  });

  // Check for saved theme preference
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme) {
    document.documentElement.setAttribute('data-theme', savedTheme);
    darkToggle.textContent = savedTheme === 'dark' ? '☀️' : '🌙';
  }
}

// Active page indicator
function setActivePage() {
  const currentPage = window.location.pathname.split('/').pop() || 'index.php';
  const navLinks = document.querySelectorAll('.nav a');
  
  navLinks.forEach(link => {
    link.classList.remove('active');
    const linkPage = link.getAttribute('href');
    if (linkPage === currentPage || (currentPage === '' && linkPage === 'index.php')) {
      link.classList.add('active');
    }
  });
}

// Scroll effect for header
window.addEventListener('scroll', () => {
  const header = document.querySelector('.site-header');
  if (header) {
    if (window.scrollY > 50) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  }
});

// Promo slide close functionality
const closePromo = document.getElementById('closePromo');
const promoSlide = document.getElementById('promoSlide');

if (closePromo && promoSlide) {
  closePromo.addEventListener('click', () => {
    promoSlide.classList.add('hidden');
    localStorage.setItem('promoClosed', 'true');
  });

  // Check if promo was previously closed
  if (localStorage.getItem('promoClosed') === 'true') {
    promoSlide.classList.add('hidden');
  }
}

// Lightbox functionality for gallery images
function initLightbox() {
  const images = document.querySelectorAll('.masonry img, .grid img');
  
  images.forEach(img => {
    img.addEventListener('click', function() {
      openLightbox(this.src, this.alt);
    });
  });
}

function openLightbox(imageSrc, imageAlt) {
  const lightbox = document.createElement('div');
  lightbox.className = 'lightbox';
  lightbox.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    cursor: pointer;
  `;
  
  const img = document.createElement('img');
  img.src = imageSrc;
  img.alt = imageAlt;
  img.style.cssText = `
    max-width: 90%;
    max-height: 90%;
    border-radius: 10px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
    cursor: default;
  `;
  
  const closeBtn = document.createElement('button');
  closeBtn.textContent = '✕';
  closeBtn.style.cssText = `
    position: absolute;
    top: 20px;
    right: 20px;
    background: rgba(0, 0, 0, 0.5);
    border: none;
    color: white;
    font-size: 2rem;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
  `;
  
  lightbox.appendChild(img);
  lightbox.appendChild(closeBtn);
  
  closeBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    lightbox.remove();
  });
  
  lightbox.addEventListener('click', (e) => {
    if (e.target === lightbox) {
      lightbox.remove();
    }
  });
  
  document.body.appendChild(lightbox);
}

// ========== BOOKING FORM FUNCTIONALITY ==========
function initBookingPage() {
  const bookingForm = document.getElementById('bookingForm');
  const saveDraftBtn = document.getElementById('saveDraft');
  
  // Load draft if exists
  loadDraft();
  
  // Set minimum date to today
  const dateInput = document.getElementById('date');
  if (dateInput) {
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;
    
    // If no value set, set to 3 days from now
    if (!dateInput.value) {
      const futureDate = new Date();
      futureDate.setDate(futureDate.getDate() + 3);
      dateInput.value = futureDate.toISOString().split('T')[0];
    }
  }
  
  // Handle URL parameters for pre-filled room selection
  const urlParams = new URLSearchParams(window.location.search);
  const roomParam = urlParams.get('room');
  if (roomParam && document.getElementById('room')) {
    const roomSelect = document.getElementById('room');
    for (let i = 0; i < roomSelect.options.length; i++) {
      if (roomSelect.options[i].text.includes(roomParam)) {
        roomSelect.selectedIndex = i;
        break;
      }
    }
  }
  
  // Save draft functionality
  if (saveDraftBtn) {
    saveDraftBtn.addEventListener('click', saveDraft);
  }
  
  // Form submission - Use PHP backend but enhance with JavaScript
  if (bookingForm) {
    bookingForm.addEventListener('submit', function(e) {
      // Let PHP handle the form submission, but we can enhance with JS
      showMessage('Processing your booking...', 'info');
    });
  }
}

function saveDraft() {
  const formData = {
    name: document.getElementById('name').value,
    email: document.getElementById('email').value,
    phone: document.getElementById('phone').value,
    room: document.getElementById('room').value,
    date: document.getElementById('date').value,
    guests: document.getElementById('guests').value
  };
  
  localStorage.setItem('bookingDraft', JSON.stringify(formData));
  showMessage('Draft saved! You can continue later.', 'success');
}

function loadDraft() {
  const draft = localStorage.getItem('bookingDraft');
  if (draft) {
    const formData = JSON.parse(draft);
    Object.keys(formData).forEach(key => {
      const element = document.getElementById(key);
      if (element && formData[key]) {
        element.value = formData[key];
      }
    });
  }
}

function showMessage(message, type = 'info') {
  const formMessage = document.getElementById('formMessage');
  if (formMessage) {
    formMessage.textContent = message;
    formMessage.className = type === 'success' ? 'success-message' : 'muted';
    formMessage.style.display = 'block';
    
    setTimeout(() => {
      formMessage.style.display = 'none';
    }, 5000);
  }
}

// Page-specific initialization
function initPageSpecificFeatures() {
  const currentPage = window.location.pathname.split('/').pop();
  
  if (currentPage === 'booking.php') {
    initBookingPage();
  }
  
  if (currentPage === 'gallery.php') {
    initLightbox();
  }
}

// Initialize everything when page loads
document.addEventListener('DOMContentLoaded', function() {
  setActivePage();
  initPageSpecificFeatures();
  
  console.log('🏝️ Heart Of D\' Ocean Beach Resort website loaded successfully!');
});