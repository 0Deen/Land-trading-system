<?php
require_once '../components/connect.php';

$thread_id = $_POST['thread_id'] ?? null;
$sender_id = $_POST['sender_id'] ?? null;
$message = trim($_POST['message'] ?? '');

if (!$thread_id || !$sender_id || $message === '') {
    die("Invalid input.");
}

$stmt = $conn->prepare("INSERT INTO messages (thread_id, sender_id, message, sent_at) VALUES (?, ?, ?, NOW())");
$stmt->execute([$thread_id, $sender_id, $message]);

header("Location: messaging.php?thread_id=$thread_id");
exit;
