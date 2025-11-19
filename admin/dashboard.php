<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

include '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Get stats
$stats = [];
$queries = [
    'total_bookings' => "SELECT COUNT(*) as count FROM bookings",
    'today_bookings' => "SELECT COUNT(*) as count FROM bookings WHERE DATE(created_at) = CURDATE()",
    'total_revenue' => "SELECT COALESCE(SUM(amount), 0) as total FROM bookings",
    'pending_bookings' => "SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'"
];

foreach($queries as $key => $query) {
    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stats[$key] = $result;
}

// Get recent bookings
$stmt = $db->prepare("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 10");
$stmt->execute();
$recent_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Heart of D' Ocean Resort</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin: 2rem 0; }
        .stat-card { background: var(--card); padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-align: center; }
        .stat-number { font-size: 2.5rem; font-weight: bold; color: var(--accent); margin: 0; }
        .booking-item { border-bottom: 1px solid rgba(0,0,0,0.1); padding: 1rem 0; }
        .booking-item:last-child { border-bottom: none; }
        .live-badge { background: #4CAF50; color: white; padding: 0.2rem 0.5rem; border-radius: 12px; font-size: 0.8rem; margin-left: 0.5rem; }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="logo" href="dashboard.php">Heart of D' Ocean Admin</a>
            <nav class="nav">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="dashboard.php">Dashboard</a>
                <a href="../index.php" target="_blank">View Site</a>
                <a href="logout.php" class="btn">Logout</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <h1>Live Booking Dashboard <span class="live-badge">LIVE</span></h1>
        <p class="muted">Real-time updates every 10 seconds</p>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Bookings</h3>
                <p class="stat-number" id="statTotal"><?php echo $stats['total_bookings']['count']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Today's Bookings</h3>
                <p class="stat-number" id="statToday"><?php echo $stats['today_bookings']['count']; ?></p>
            </div>
            <div class="stat-card">
                <h3>Total Revenue</h3>
                <p class="stat-number" id="statRevenue">₱<?php echo number_format($stats['total_revenue']['total'], 2); ?></p>
            </div>
            <div class="stat-card">
                <h3>Pending Bookings</h3>
                <p class="stat-number" id="statPending"><?php echo $stats['pending_bookings']['count']; ?></p>
            </div>
        </div>

        <section class="card">
            <h2>Recent Bookings <small class="muted">(Live Updates)</small></h2>
            <div id="recentBookings">
                <?php foreach($recent_bookings as $booking): ?>
                <div class="booking-item">
                    <strong><?php echo htmlspecialchars($booking['name']); ?></strong>
                    <span class="muted">• <?php echo htmlspecialchars($booking['room_type']); ?></span>
                    <div class="muted"><?php echo $booking['guests']; ?> guests • Check-in: <?php echo $booking['checkin_date']; ?></div>
                    <div>📧 <?php echo htmlspecialchars($booking['email']); ?> • 📞 <?php echo htmlspecialchars($booking['phone']); ?></div>
                    <div class="muted">Booked: <?php echo date('M j, Y g:i A', strtotime($booking['created_at'])); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <script>
    // Auto-refresh every 10 seconds for live data
    function refreshDashboard() {
        fetch('get_live_data.php')
            .then(response => response.json())
            .then(data => {
                // Update stats
                document.getElementById('statTotal').textContent = data.stats.total_bookings;
                document.getElementById('statToday').textContent = data.stats.today_bookings;
                document.getElementById('statRevenue').textContent = '₱' + data.stats.total_revenue;
                document.getElementById('statPending').textContent = data.stats.pending_bookings;
                
                // Update bookings list
                document.getElementById('recentBookings').innerHTML = data.bookings_html;
            })
            .catch(error => console.error('Error refreshing dashboard:', error));
    }

    // Refresh immediately and then every 10 seconds
    refreshDashboard();
    setInterval(refreshDashboard, 10000);
    </script>
</body>
</html>