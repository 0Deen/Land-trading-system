<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$connection = new mysqli('localhost', 'root', '', 'home_db');

// Check if the connection is successful
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$owner_id = $_SESSION['user_id']; // Logged-in owner

// Query to get all users who have messaged the owner
$query = "
    SELECT DISTINCT users.id, users.name 
    FROM messages
    JOIN users ON messages.sender_id = users.id
    WHERE messages.receiver_id = ?";

$stmt = $connection->prepare($query);
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$result = $stmt->get_result();

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox - Messages</title>
    <style>
        /* Inbox Styles */
.inbox-container {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
    border: 1px solid #ddd;
    animation: fadeIn 1s ease-in-out;
}

.inbox-container h2 {
    font-size: 2em;
    margin-bottom: 20px;
    color: #495057;
    text-align: center;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

.inbox-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.inbox-list li {
    padding: 15px;
    margin: 10px 0;
    background: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s, box-shadow 0.3s;
    border: 1px solid #e0e0e0;
    
}

.inbox-list li:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.inbox-list a {
    text-decoration: none;
    color: #343a40;
    font-weight: bold;
    font-size: 1.1em;
    transition: color 0.3s ease;
}

.inbox-list a:hover {
    color: #6c757d;
    text-decoration: underline;
}

.inbox-list ul {
    list-style: disc;
    margin-left: 20px;
    margin-top: 10px;
}

.inbox-list ul li {
    font-size: 0.9em;
    color: #555;
}

    </style>
</head>
<body>

<div class="inbox-container">
<h2>Inbox - Users Who Have Messaged You</h2>

<?php if ($result->num_rows > 0): ?>
    <ul class="inbox-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
                // Fetch the listings that the user has messaged about
                $user_id = $row['id']; // ID of the user who messaged the owner
                
                // Query to get the listing(s) for this user
                $property_query = "SELECT id, name FROM property WHERE user_id = ? AND id IN (SELECT DISTINCT property_id FROM messages WHERE sender_id = ? AND receiver_id = ?)";
                $property_stmt = $connection->prepare($property_query);
                $property_stmt->bind_param("iii", $owner_id, $user_id, $owner_id);
                $property_stmt->execute();
                $property_result = $property_stmt->get_result();
            ?>
            <li>
                <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                <?php if ($property_result->num_rows > 0): ?>
                    <ul>
                        <?php while ($property = $property_result->fetch_assoc()): ?>
                            <li>
                                <!-- Create a link to the chat.php with listing_id, owner_id, and user_id -->
                                <a href="admin_inbox.php?property_id=<?php echo $property['id']; ?>&owner_id=<?php echo $owner_id; ?>&user_id=<?php echo $user_id; ?>">
                                    Chat for: <?php echo htmlspecialchars($property['name']); ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>No messages yet.</p>
<?php endif; ?>
</div>

</body>
</html>

