<?php
include '../components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    die("Unauthorized: User not logged in.");
}

if (!isset($_GET['thread_id'])) {
    die("Thread ID missing.");
}

$thread_id = $_GET['thread_id'];

// Verify thread belongs to this user
$stmt = $conn->prepare("SELECT * FROM message_threads WHERE id = ? AND (user1_id = ? OR user2_id = ?)");
$stmt->execute([$thread_id, $user_id, $user_id]);
$thread = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$thread) {
    die("Unauthorized access.");
}

// Handle message send
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    if ($message !== '') {
        $stmt = $conn->prepare("INSERT INTO messages (thread_id, sender_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$thread_id, $user_id, $message]);
    }
}

// Fetch all messages in thread
$stmt = $conn->prepare("SELECT * FROM messages WHERE thread_id = ? ORDER BY sent_at ASC");
$stmt->execute([$thread_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messaging</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Link your main stylesheet -->
</head>
<body>

<?php include '../components/user_header.php'; ?> <!-- Include your header -->

<section class="message-thread-container">
    <div class="box">
        <h2>Message Thread</h2>
        <div id="chat-box" style="border: 1px solid #ccc; padding: 10px; height: 300px; overflow-y: scroll; margin-bottom: 15px;">
            <?php foreach ($messages as $msg): ?>
                <div class="message" style="margin-bottom: 10px;">
                    <span class="sender" style="font-weight: bold;">
                        <?= $msg['sender_id'] == $user_id ? 'You' : 'Them' ?>:
                    </span>
                    <?= htmlspecialchars($msg['message']) ?><br>
                    <small><?= $msg['sent_at'] ?></small>
                </div>
            <?php endforeach; ?>
        </div>

        <form method="post">
            <textarea name="message" rows="3" cols="50" required placeholder="Type your message..."></textarea><br>
            <button type="submit" class="btn">Send</button>
        </form>
    </div>
</section>

<?php include '../components/footer.php'; ?> <!-- Include your footer -->

</body>
</html>
