<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Booking — Heart of D' Ocean Beach Resort</title>
  <link rel="stylesheet" href="styles.css" />
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a class="logo" href="index.php">Heart of D' Ocean Beach Resort</a>
      <nav class="nav" id="mainNav">
        <a href="index.php">Home</a>
        <a href="rooms.php">Cottages</a>
        <a href="gallery.php">Gallery</a>
        <a href="booking.php" class="cta">Book Now</a>
        <button id="darkToggle" class="icon-btn" aria-label="Toggle dark mode">🌙</button>
      </nav>
      <button id="menuBtn" class="hamburger" aria-label="menu">☰</button>
    </div>
  </header>

  <main class="container booking-page">
    <h1>Make a Reservation</h1>

    <form id="bookingForm" class="form-card">
      <label>Full name <span class="required">*</span>
        <input type="text" id="name" name="name" required>
      </label>
      
      <label>Email <span class="required">*</span>
        <input type="email" id="email" name="email" required>
      </label>
      
      <label>Contact No <span class="required">*</span>
        <input type="text" id="phone" name="phone" required>
      </label>
      
      <label>Choose cottage / package <span class="required">*</span>
        <select id="room" name="room_type" required>
          <option value="">Select an option</option>
          <option value="Cottage A — ₱4,000">Cottage A — ₱4,000</option>
          <option value="Cottage B — ₱2,500">Cottage B — ₱2,500</option>
          <option value="Day Trip — ₱1,200">Day Trip — ₱1,200</option>
        </select>
      </label>

      <label>Check-in date <span class="required">*</span>
        <input type="date" id="date" name="checkin_date" required min="<?php echo date('Y-m-d'); ?>">
      </label>
      
      <label>Guests <span class="required">*</span>
        <input type="number" id="guests" name="guests" value="2" min="1" max="10" required>
      </label>

      <div class="form-actions">
        <button type="submit" class="btn">Reserve & Pay</button>
        <button type="button" id="saveDraft" class="btn ghost">Save Draft</button>
      </div>

      <div id="formMessage" class="form-message" aria-live="polite"></div>
    </form>

    <section class="info-section">
      <h2>How payment works (demo)</h2>
      <p>After you submit, the booking is saved to our database. Clicking Pay will open an external secure payment (example PayPal) — this is a redirect simulation only.</p>
    </section>
  </main>

  <footer class="site-footer container">
    <div>© 2025 Heart of D' Ocean Beach Resort</div>
  </footer>

  <script src="main.js"></script>
</body>
</html>