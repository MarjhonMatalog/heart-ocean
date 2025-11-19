<?php
// Include database connection for real functionality
include 'config/database.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Booking — Heart Of D' Ocean Beach Resort</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="styles.css" />
</head> 
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a class="logo" href="index.php">Heart Of D' Ocean Beach Resort</a>
      <nav class="nav" id="mainNav">
        <button class="close-menu" id="closeMenu">✕</button>
        <a href="index.php">Home</a>
        <a href="rooms.php">Cottages</a>
        <a href="gallery.php">Gallery</a>
        <a href="booking.php" class="cta">Book Now</a>
        <button id="darkToggle" class="icon-btn" aria-label="Toggle dark mode">🌙</button>
      </nav>
      <button id="menuBtn" class="hamburger" aria-label="Toggle menu">☰</button>
    </div>
  </header>

  <main class="container booking-page">
    <h1>Make a Reservation</h1>

    <form id="bookingForm" class="form-card" action="process_booking.php" method="POST">
      <label>Full name<input type="text" id="name" name="name" required placeholder="Enter your full name"></label>
      <label>Email<input type="email" id="email" name="email" required placeholder="your.email@example.com"></label>
      <label>Contact No<input type="text" id="phone" name="phone" required placeholder="+63 XXX XXX XXXX"></label>
      <label>Choose cottage / package
        <select id="room" name="room_type" required>
          <option value="">Select an option</option>
          <option value="Cottage A — ₱4,000">Cottage A — ₱4,000</option>
          <option value="Cottage B — ₱2,500">Cottage B — ₱2,500</option>
          <option value="Day Trip — ₱1,200">Day Trip — ₱1,200</option>
        </select>
      </label>

      <label>Check-in date<input type="date" id="date" name="checkin_date" required></label>
      <label>Guests<input type="number" id="guests" name="guests" value="2" min="1" max="10" required></label>

      <div class="form-actions">
        <button type="submit" class="btn">Reserve & Pay via GCash</button>
        <button type="button" id="saveDraft" class="btn ghost">Save Draft</button>
      </div>

      <div id="formMessage" class="muted" aria-live="polite"></div>
    </form>

    <section class="payment-section">
      <h2>GCash Payment</h2>
      <p>After submitting your booking, you'll be redirected to GCash for secure payment processing.</p>
      <div class="qr-container">
        <div style="text-align: center;">
          <div style="font-size: 4rem; margin-bottom: 1rem;">📱</div>
          <p class="muted">Scan to Pay via GCash</p>
        </div>
        <div class="payment-details">
          <h3>Payment Details</h3>
          <p><strong>Merchant:</strong> Heart Of D' Ocean Beach Resort</p>
          <p><strong>Mobile:</strong> 0917-123-4567</p>
          <p><strong>Supported:</strong> GCash, Credit/Debit Cards</p>
        </div>
      </div>
    </section>
  </main>

  <footer class="site-footer container">
    <div>© 2025 Heart Of D' Ocean Beach Resort</div>
    <div class="muted">Secure online booking system</div>
  </footer>

  <script src="main.js"></script>
</body>
</html>