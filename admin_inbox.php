<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$connection = new mysqli('localhost', 'root', '', 'home_db');
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Sanitize input values
$property_id = isset($_GET['listing_id']) ? intval($_GET['listing_id']) : 0;
$owner_id = isset($_GET['owner_id']) ? intval($_GET['owner_id']) : 0;
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$current_user_id = $_SESSION['user_id'];

// Ensure only participants can access the chat
if (!in_array($current_user_id, [$owner_id, $user_id])) {
    echo "Unauthorized access.";
    exit;
}

// Get listing details
$listing_query = "SELECT title FROM listings WHERE id = ?";
$stmt = $connection->prepare($property_query);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$property_result = $stmt->get_result();
$property = $listing_result->fetch_assoc();
$title = htmlspecialchars($property['name'] ?? 'Unknown property');

// Get chat messages
$chat_query = "
    SELECT messages.*, users.name AS sender_name 
    FROM messages
    JOIN users ON messages.sender_id = users.id
    WHERE messages.listing_id = ? 
      AND ((messages.sender_id = ? AND messages.receiver_id = ?) 
        OR (messages.sender_id = ? AND messages.receiver_id = ?))
    ORDER BY messages.id ASC";
$stmt = $connection->prepare($chat_query);
$stmt->bind_param("iiiii", $property_id, $user_id, $owner_id, $owner_id, $user_id);
$stmt->execute();
$chat_result = $stmt->get_result();

// Message sending
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = trim($_POST['message']);
    if (!empty($message)) {
        $sender_id = $current_user_id;
        $receiver_id = $sender_id == $owner_id ? $user_id : $owner_id;

        $insert_message_query = "INSERT INTO messages (property_id, sender_id, receiver_id, message) 
                                 VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($insert_message_query);
        $stmt->bind_param("iiis", $propery_id, $sender_id, $receiver_id, $message);
        if ($stmt->execute()) {
            header("Location: chat.php?property_id=$property_id&owner_id=$owner_id&user_id=$user_id");
            exit;
        } else {
            echo "Error: Message could not be sent.";
        }
    } else {
        echo "Please enter a message.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat for land: <?php echo $name; ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .chat-container { display: flex; flex-direction: column; max-width: 600px; margin: 20px auto; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .chat-header { background-color: #1abc9c; color: white; padding: 10px; text-align: center; }
        .chat-messages { flex-grow: 1; padding: 10px; overflow-y: auto; height: 400px; background-color: #f9f9f9; }
        .message { margin-bottom: 10px; padding: 10px; border-radius: 8px; max-width: 70%; }
        .sent { background-color: #d1f7c4; margin-left: auto; }
        .received { background-color: #f1f0f0; margin-right: auto; }
        .chat-input { display: flex; padding: 10px; background-color: #fff; border-top: 1px solid #ddd; }
        .chat-input textarea { flex-grow: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .chat-input button { margin-left: 10px; padding: 10px 15px; background-color: #1abc9c; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .chat-input button:hover { background-color: #16a085; }
    </style>
</head>
<body>

<div class="chat-container">
    <div class="chat-header">
        <h2>Chat for: <?php echo $name; ?></h2>
    </div>
    <div class="chat-messages" id="chatBox">
        <?php while ($message = $chat_result->fetch_assoc()): ?>
            <div class="message <?php echo $message['sender_id'] == $current_user_id ? 'sent' : 'received'; ?>">
                <p><strong><?php echo htmlspecialchars($message['sender_name']); ?>:</strong></p>
                <p><?php echo htmlspecialchars($message['message']); ?></p>
            </div>
        <?php endwhile; ?>
    </div>
    <form class="chat-input" method="POST">
        <textarea name="message" placeholder="Type your message..." required></textarea>
        <button type="submit">Send</button>
    </form>
</div>

<script>
    // Auto-scroll to bottom of chat on load
    const chatBox = document.getElementById("chatBox");
    chatBox.scrollTop = chatBox.scrollHeight;
</script>

</body>
</html>
