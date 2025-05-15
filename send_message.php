<?php
session_start();
$conn = new mysqli("localhost", "root", "", "home_db");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

$user_id = $_SESSION['user_id']; // User ID from session
$receiver_id = $_POST['receiver_id'];
$property_id = $_POST['property_id'];
$message = $_POST['message'];

// Insert message into the database
$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, property_id, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $user_id, $receiver_id, $property_id, $message);

if ($stmt->execute()) {
    header("Location: chat.php?property_id=$property_id");
} else {
    echo json_encode(["status" => "error", "message" => "Failed to send message"]);
}
?>
