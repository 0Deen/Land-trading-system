<<<<<<< HEAD
<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
   $user_id = $_COOKIE['user_id'];
} else {
   header('location:login.php');
   exit;
}

// Get property ID from query string
$land_id = $_GET['land_id'] ?? null;

if (!$land_id) {
   die('Invalid property selected.');
}

// Fetch the property details
$select_property = $conn->prepare("SELECT * FROM property WHERE id = ?");
$select_property->execute([$land_id]);

if ($select_property->rowCount() == 0) {
   die('Invalid property selected.');
}

$property = $select_property->fetch(PDO::FETCH_ASSOC);

// Prevent user from paying for their own land
if ($property['user_id'] == $user_id) {
   die('You cannot make a payment for your own property.');
}
?>
=======
<?php include 'components/connect.php'; ?>
>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Payment</title>

<<<<<<< HEAD
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
=======
   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS file link -->
>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<<<<<<< HEAD
<section class="form-container">
   <form action="paymentp.php" method="POST">
      <h3>Payment Details for: <?= htmlspecialchars($property['property_name']); ?></h3>
      <img src="images/cards.png" alt="cards" style="width: 100%; border-radius: 10px; margin: 10px 0;"><br>

      <!-- Hidden field for property ID -->
      <input type="hidden" name="property_id" value="<?= htmlspecialchars($property['id']); ?>">
=======
<!-- Payment Section Starts -->
<section class="form-container">

   <form action="paymentp.php" method="POST">
      <h3>Payment Details</h3>
      <img src="images/cards.png" alt="cards" style="width: 100%; border-radius: 10px; margin: 10px 0;"><br>
>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a

      <label for="cardNo">Card Number</label>
      <input type="text" name="cardNo" pattern="[0-9]{10,14}" placeholder="Enter Valid Card Number" required class="box">

      <label for="expiryDate">Expiration Date</label>
      <input type="text" name="expiryDate" pattern="(0[1-9]|1[0-2])/[0-9]{2}" placeholder="MM/YY" required class="box">

      <label for="cvCode">CV Code</label>
      <input type="password" name="cvCode" pattern="[0-9]{3}" placeholder="CVC" required class="box">

      <label for="cardName">Card Owner</label>
      <input type="text" name="cardName" placeholder="Enter Card Owner Name" required class="box">

<<<<<<< HEAD
      <label for="amount">Amount</label>
=======
      <label for="amount">amount</label>
>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
      <input type="text" name="amount" pattern="[0-9]{1,}" placeholder="Enter Amount" required class="box">

      <input type="submit" name="submit" class="btn" value="Confirm Payment">
   </form>
<<<<<<< HEAD
</section>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
=======

</section>
<!-- Payment Section Ends -->

<?php include 'components/footer.php'; ?>

<!-- Custom JS file -->
<script src="js/script.js"></script>

>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
<?php include 'components/message.php'; ?>

</body>
</html>
