<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    http_response_code(401);
    exit('Unauthorized');
}

include '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// Get updated stats
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
    $stats[$key] = $key === 'total_revenue' ? number_format($result['total'], 2) : $result['count'];
}

// Get recent bookings
$stmt = $db->prepare("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 10");
$stmt->execute();
$recent_bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

$bookings_html = '';
foreach($recent_bookings as $booking) {
    $bookings_html .= '<div class="booking-item">';
    $bookings_html .= '<strong>' . htmlspecialchars($booking['name']) . '</strong>';
    $bookings_html .= '<span class="muted">• ' . htmlspecialchars($booking['room_type']) . '</span>';
    $bookings_html .= '<div class="muted">' . $booking['guests'] . ' guests • Check-in: ' . $booking['checkin_date'] . '</div>';
    $bookings_html .= '<div>📧 ' . htmlspecialchars($booking['email']) . ' • 📞 ' . htmlspecialchars($booking['phone']) . '</div>';
    $bookings_html .= '<div class="muted">Booked: ' . date('M j, Y g:i A', strtotime($booking['created_at'])) . '</div>';
    $bookings_html .= '</div>';
}

header('Content-Type: application/json');
echo json_encode([
    'stats' => $stats,
    'bookings_html' => $bookings_html
]);
?>