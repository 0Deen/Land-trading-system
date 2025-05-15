<?php
include '../components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    die("Unauthorized: User not logged in.");
}

if (!isset($_GET['property_id'])) {
    die("Unauthorized: Property ID missing.");
}

$property_id = $_GET['property_id'];

// Get property and owner (seller)
$stmt = $conn->prepare("SELECT user_id FROM property WHERE id = ?");
$stmt->execute([$property_id]);
$property = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$property) {
    die("Property not found.");
}

$seller_id = $property['user_id'];

if ($user_id == $seller_id) {
    die("You cannot message yourself.");
}

// Check if thread already exists
$stmt = $conn->prepare("SELECT id FROM message_threads WHERE user1_id = ? AND user2_id = ? AND property_id = ?");
$stmt->execute([$user_id, $seller_id, $property_id]);
$thread = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$thread) {
    // Insert new thread
    $stmt = $conn->prepare("INSERT INTO message_threads (user1_id, user2_id, property_id) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $seller_id, $property_id]);
    $thread_id = $conn->lastInsertId();
} else {
    $thread_id = $thread['id'];
}

// Redirect to messaging
header("Location: messaging.php?thread_id=$thread_id");
exit;
