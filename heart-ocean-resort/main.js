// main.js - single file for client-side demo logic
(function(){
  // simple utility
  const $ = (sel, ctx=document)=> ctx.querySelector(sel);
  const $$ = (sel, ctx=document)=> Array.from(ctx.querySelectorAll(sel));

  /* --------- Mobile Menu Toggle ---------- */
  $$('#menuBtn').forEach(btn => {
    btn?.addEventListener('click', ()=>{
      const nav = $('#mainNav');
      if(nav) nav.classList.toggle('active');
    });
  });

  /* --------- Promo slide behavior ---------- */
  const promo = $('#promoSlide');
  if(promo){
    const close = $('#closePromo');
    if(localStorage.getItem('promoClosed') === '1') promo.classList.add('hidden');
    close?.addEventListener('click', ()=> {
      promo.classList.add('hidden'); 
      localStorage.setItem('promoClosed','1');
    });
  }

  /* --------- Dark mode toggle ---------- */
  function applyTheme(theme){
    if(theme === 'dark') {
      document.documentElement.setAttribute('data-theme','dark');
    } else {
      document.documentElement.removeAttribute('data-theme');
    }
  }
  
  const stored = localStorage.getItem('theme') || 'light';
  applyTheme(stored);
  
  $$('#darkToggle').forEach(btn=>{
    btn?.addEventListener('click', ()=>{
      const next = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      applyTheme(next);
      localStorage.setItem('theme', next);
    });
  });

  /* --------- Gallery images ---------- */
  const galleryImgs = [
    "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=500&h=300&fit=crop",
    "https://images.unsplash.com/photo-1503264116251-35a269479413?w=500&h=300&fit=crop",
    "https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=500&h=300&fit=crop",
    "https://images.unsplash.com/photo-1493558103817-58b2924bce98?w=500&h=300&fit=crop",
    "https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=500&h=300&fit=crop",
    "https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=500&h=300&fit=crop"
  ];

  // Fill mini gallery on homepage
  const mg = $('#miniGallery');
  if(mg){
    galleryImgs.slice(0,4).forEach(u=>{
      const img = document.createElement('img'); 
      img.src = u; 
      img.loading = 'lazy';
      img.alt = 'Resort gallery image';
      mg.appendChild(img);
    });
  }

  // Fill full gallery page
  const grid = $('#galleryGrid');
  if(grid){
    galleryImgs.forEach(u=>{
      const img = document.createElement('img'); 
      img.src = u; 
      img.loading = 'lazy';
      img.alt = 'Resort gallery image';
      grid.appendChild(img);
    });
  }

  /* --------- Booking Form ---------- */
  const bookingForm = $('#bookingForm');
  if(bookingForm){
    // Prefill room from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const roomParam = urlParams.get('room');
    if (roomParam) {
      const decodedRoom = decodeURIComponent(roomParam);
      $('#room').value = decodedRoom;
    }

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    $('#date').min = today;

    bookingForm.addEventListener('submit', async function(e){
      e.preventDefault();
      
      const submitBtn = this.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      const messageEl = $('#formMessage');
      
      // Show loading state
      submitBtn.textContent = 'Processing...';
      submitBtn.disabled = true;
      messageEl.textContent = '';
      messageEl.className = 'form-message';

      const formData = {
        name: $('#name').value.trim(),
        email: $('#email').value.trim(),
        phone: $('#phone').value.trim(),
        room_type: $('#room').value,
        checkin_date: $('#date').value,
        guests: parseInt($('#guests').value)
      };

      // Basic validation
      if (!formData.name || !formData.email || !formData.phone || !formData.checkin_date || !formData.room_type) {
        messageEl.textContent = 'Please fill all required fields';
        messageEl.className = 'form-message error';
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        return;
      }

      try {
        const response = await fetch('process_booking.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(formData)
        });

        const data = await response.json();

        if(data.success) {
          messageEl.textContent = data.message + ' Redirecting to payment...';
          messageEl.className = 'form-message success';
          
          // Simulate payment redirect
          setTimeout(() => {
            const amount = data.amount || estimateAmount(formData.room_type);
            const payUrl = `https://www.paypal.com/pay?amount=${amount}&currency=PHP`;
            window.open(payUrl, '_blank');
            messageEl.textContent = 'Payment page opened in new tab. Booking saved successfully!';
            this.reset();
          }, 1500);
        } else {
          messageEl.textContent = 'Error: ' + data.message;
          messageEl.className = 'form-message error';
        }
      } catch (error) {
        console.error('Error:', error);
        messageEl.textContent = 'Network error. Please try again.';
        messageEl.className = 'form-message error';
      } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
      }
    });

    // Save draft functionality
    $('#saveDraft')?.addEventListener('click', ()=>{
      const draft = {
        name: $('#name').value,
        email: $('#email').value,
        phone: $('#phone').value,
        room: $('#room').value
      };
      localStorage.setItem('bookingDraft', JSON.stringify(draft));
      $('#formMessage').textContent = 'Draft saved locally.';
      $('#formMessage').className = 'form-message';
    });

    // Restore draft if exists
    const draft = localStorage.getItem('bookingDraft');
    if(draft) {
      try {
        const dd = JSON.parse(draft);
        if(dd.name) $('#name').value = dd.name;
        if(dd.email) $('#email').value = dd.email;
        if(dd.phone) $('#phone').value = dd.phone;
        if(dd.room) $('#room').value = dd.room;
      } catch(e) {
        console.error('Error loading draft:', e);
      }
    }
  }

  function estimateAmount(room){
    if(room.includes('Cottage A')) return 4000;
    if(room.includes('Cottage B')) return 2500;
    if(room.includes('Day Trip')) return 1200;
    return 1000;
  }

})();