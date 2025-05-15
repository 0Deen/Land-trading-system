<?php  
<<<<<<< HEAD
session_start();
include 'components/connect.php';

// Check login via session or cookie
=======

session_start();  // Always start the session first!

include 'components/connect.php';

// Use session first, fallback to cookie
>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} elseif (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
<<<<<<< HEAD
    $_SESSION['user_id'] = $user_id;
=======
    $_SESSION['user_id'] = $user_id;  // sync cookie to session
>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
} else {
    header('location:login.php');
    exit;
}
<<<<<<< HEAD
=======

>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< HEAD
   <title>Dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="dashboard">
   <h1 class="heading">Dashboard</h1>

   <div class="box-container"> <!-- Fixed this line, changed 'd' to 'div' -->
=======
   <title>dashboard</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="dashboard">

   <h1 class="heading">dashboard</h1>

   <div class="box-container">
>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a

      <div class="box">
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ? LIMIT 1");
         $select_profile->execute([$user_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

         if ($fetch_profile) {
             echo "<h3>Welcome!</h3>";
             echo "<p>" . htmlspecialchars($fetch_profile['name']) . "</p>";
         } else {
             echo "<h3>User not found</h3>";
             echo "<p>Please log in again.</p>";
         }
      ?>
<<<<<<< HEAD
      <a href="update.php" class="btn">Update Profile</a>
      </div>

      <div class="box">
         <h3>Filter Search</h3>
         <p>Search your dream land?</p>
         <a href="search.php" class="btn">Search Now</a>
=======
      <a href="update.php" class="btn">update profile</a>
      </div>

      <div class="box">
         <h3>filter search</h3>
         <p>search your dream land?</p>
         <a href="search.php" class="btn">search now</a>
>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
      </div>

      <div class="box">
      <?php
        $count_properties = $conn->prepare("SELECT * FROM `property` WHERE user_id = ?");
        $count_properties->execute([$user_id]);
        $total_properties = $count_properties->rowCount();
      ?>
      <h3><?= $total_properties; ?></h3>
<<<<<<< HEAD
      <p>Properties Listed</p>
      <a href="my_listings.php" class="btn">View All Listings</a>
=======
      <p>properties listed</p>
      <a href="my_listings.php" class="btn">view all listings</a>
>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
      </div>

      <div class="box">
      <?php
        $count_requests_received = $conn->prepare("SELECT * FROM `requests` WHERE receiver = ?");
        $count_requests_received->execute([$user_id]);
        $total_requests_received = $count_requests_received->rowCount();
      ?>
      <h3><?= $total_requests_received; ?></h3>
<<<<<<< HEAD
      <p>Requests Received</p>
      <a href="requests.php" class="btn">View All Requests</a>
=======
      <p>requests received</p>
      <a href="requests.php" class="btn">view all requests</a>
>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
      </div>

      <div class="box">
      <?php
        $count_requests_sent = $conn->prepare("SELECT * FROM `requests` WHERE sender = ?");
        $count_requests_sent->execute([$user_id]);
        $total_requests_sent = $count_requests_sent->rowCount();
      ?>
      <h3><?= $total_requests_sent; ?></h3>
<<<<<<< HEAD
      <p>Requests Sent</p>
      <a href="saved.php" class="btn">View Saved Lands</a>
=======
      <p>requests sent</p>
      <a href="saved.php" class="btn">view saved lands</a>
>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
      </div>

      <div class="box">
      <?php
        $count_saved_properties = $conn->prepare("SELECT * FROM `saved` WHERE user_id = ?");
        $count_saved_properties->execute([$user_id]);
        $total_saved_properties = $count_saved_properties->rowCount();
      ?>
      <h3><?= $total_saved_properties; ?></h3>
<<<<<<< HEAD
      <p>Properties Saved</p>
      <a href="saved.php" class="btn">View Saved Lands</a>
      </div>

      <div class="box">
      <?php
        $select_payments = $conn->prepare("SELECT * FROM `payments`");
        $select_payments->execute();
        $count_payments = $select_payments->rowCount();
      ?>
      <h3><?= $count_payments; ?></h3>
      <p>Total Payments</p>
      <a href="viewPayments.php" class="btn">View Payments</a>
      </div>

      <div class="box">
   <h3>Recent Conversations</h3>
   <?php
   $getConversations = $conn->prepare("
      SELECT mt.id AS thread_id,
             u1.name AS user1_name,
             u2.name AS user2_name,
             m.message,
             m.sent_at,
             mt.user1_id,
             mt.user2_id
      FROM message_threads mt
      JOIN users u1 ON mt.user1_id = u1.id
      JOIN users u2 ON mt.user2_id = u2.id
      LEFT JOIN (
         SELECT thread_id, message, sent_at
         FROM messages
         WHERE id IN (
            SELECT MAX(id) FROM messages GROUP BY thread_id
         )
      ) m ON m.thread_id = mt.id
      WHERE mt.user1_id = :user OR mt.user2_id = :user
      ORDER BY m.sent_at DESC
   ");
   $getConversations->execute(['user' => $user_id]);
   $conversations = $getConversations->fetchAll(PDO::FETCH_ASSOC);

   if (!empty($conversations)) {
      foreach ($conversations as $conversation) {
         $thread_id = $conversation['thread_id'];
         $latest_message = $conversation['message'];
         $sent_at = $conversation['sent_at'];

         $with = ($conversation['user1_id'] == $user_id) ? $conversation['user2_name'] : $conversation['user1_name'];

         $preview = strlen($latest_message) > 30 ? substr($latest_message, 0, 30) . '...' : $latest_message;

         echo "<p><a class='btn' href='Messaging/messaging.php?thread_id={$thread_id}'>" . 
              htmlspecialchars($with) . "<br><small>" . htmlspecialchars($preview) . "</small></a></p>";
      }
   } else {
      echo "<p>No conversations yet.</p>";
   }
   ?>
</div>

   </div>
=======
      <p>properties saved</p>
      <a href="saved.php" class="btn">view saved lands</a>
      </div>

      <div class="box">
   <?php
      $select_payments = $conn->prepare("SELECT * FROM `payments`");
      $select_payments->execute();
      $count_payments = $select_payments->rowCount();
   ?>
   <h3><?= $count_payments; ?></h3>
   <p>total payments</p>
   <a href="viewPayments.php" class="btn">view payments</a>
</div>
<div class="box">
   <?php
      $select_payments = $conn->prepare("SELECT * FROM `messages`");
      $select_payments->execute();
      $count_payments = $select_payments->rowCount();
   ?>
   <h3><?= $count_payments; ?></h3>
   <p>total messages</p>
   <a href="inbox.php" class="btn">view messages</a>
</div>
   </div>

>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
<?php include 'components/message.php'; ?>
<<<<<<< HEAD
=======

>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
</body>
</html>
