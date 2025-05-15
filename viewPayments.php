<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Payments</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css"> <!-- use user-style instead of admin -->

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="grid">

   <h1 class="heading">My Land Payments</h1>

   <div class="box-container">

   <?php
      $select_payments = $conn->prepare("SELECT payments.*, property.property_name FROM `payments` 
                                         JOIN `property` ON payments.property_id = property.id 
                                         WHERE payments.user_id = ?");
      $select_payments->execute([$user_id]);

      if($select_payments->rowCount() > 0){
         while($fetch_payment = $select_payments->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>Land Name : <span><?= htmlspecialchars($fetch_payment['property_name']); ?></span></p>
      <p>Amount Paid : <span>$<?= htmlspecialchars($fetch_payment['amount']); ?></span></p>
      <p>Payment Status : <span><?= htmlspecialchars($fetch_payment['payment_status']); ?></span></p>
      <p>Payment Date : <span><?= htmlspecialchars($fetch_payment['payment_date']); ?></span></p>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">You have not made any payments yet!</p>';
      }
   ?>

   </div>

</section>
<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>
