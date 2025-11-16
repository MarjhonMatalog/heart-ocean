<?php
// Set header first
header('Content-Type: application/json');

// Include database connection
include 'config/database.php';

try {
    // Get form data
    $input = file_get_contents("php://input");
    if (empty($input)) {
        throw new Exception("No data received");
    }
    
    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON data");
    }

    // Validate required fields
    $required = ['name', 'email', 'phone', 'room_type', 'checkin_date', 'guests'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            throw new Exception("Missing required field: " . $field);
        }
    }

    // Validate email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    $database = new Database();
    $db = $database->getConnection();
    
    $query = "INSERT INTO bookings 
              SET name=:name, email=:email, phone=:phone, room_type=:room_type, 
                  checkin_date=:checkin_date, guests=:guests, amount=:amount";
    
    $stmt = $db->prepare($query);
    
    // Calculate amount based on room type
    $amount = 0;
    if (strpos($data['room_type'], 'Cottage A') !== false) $amount = 4000;
    elseif (strpos($data['room_type'], 'Cottage B') !== false) $amount = 2500;
    elseif (strpos($data['room_type'], 'Day Trip') !== false) $amount = 1200;
    
    // Bind parameters
    $stmt->bindParam(":name", $data['name']);
    $stmt->bindParam(":email", $data['email']);
    $stmt->bindParam(":phone", $data['phone']);
    $stmt->bindParam(":room_type", $data['room_type']);
    $stmt->bindParam(":checkin_date", $data['checkin_date']);
    $stmt->bindParam(":guests", $data['guests']);
    $stmt->bindParam(":amount", $amount);
    
    if($stmt->execute()) {
        echo json_encode(array(
            "success" => true, 
            "message" => "Booking saved successfully!",
            "booking_id" => $db->lastInsertId(),
            "amount" => $amount
        ));
    } else {
        throw new Exception("Unable to save booking to database");
    }
    
} catch(Exception $exception) {
    http_response_code(400);
    echo json_encode(array(
        "success" => false, 
        "message" => "Error: " . $exception->getMessage()
    ));
}
?>