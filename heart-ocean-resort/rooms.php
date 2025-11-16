<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>Cottages — Heart of D' Ocean Beach Resort</title>
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

  <main class="container">
    <h1>Cottages & Rates</h1>
    <div class="cards">
      <div class="room-card">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600&h=350&fit=crop" alt="Cottage A with sea view">
        <div class="room-body">
          <h3>Cottage A — Sea View</h3>
          <p>Up to 6 pax • ₱4,000 / day</p>
          <a class="btn" href="booking.php?room=Cottage%20A%20—%20₱4,000">Reserve</a>
        </div>
      </div>

      <div class="room-card">
        <img src="https://images.unsplash.com/photo-1582582621959-48d27397dc69?w=600&h=350&fit=crop" alt="Cottage B garden view">
        <div class="room-body">
          <h3>Cottage B — Garden</h3>
          <p>Up to 4 pax • ₱2,500 / day</p>
          <a class="btn" href="booking.php?room=Cottage%20B%20—%20₱2,500">Reserve</a>
        </div>
      </div>

      <div class="room-card">
        <img src="https://images.unsplash.com/photo-1506929562872-bb421503ef21?w=600&h=350&fit=crop" alt="Day trip snorkeling">
        <div class="room-body">
          <h3>Day Trip Package</h3>
          <p>Snorkel + Lunch • ₱1,200 / person</p>
          <a class="btn" href="booking.php?room=Day%20Trip%20—%20₱1,200">Reserve</a>
        </div>
      </div>
    </div>
  </main>

  <footer class="site-footer container">
    <div>© 2025 Heart of D' Ocean Beach Resort</div>
  </footer>

  <script src="main.js"></script>
</body>
</html>