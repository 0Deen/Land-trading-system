<?php

include '../components/connect.php';

if(isset($_COOKIE['admin_id'])){
   $admin_id = $_COOKIE['admin_id'];
}else{
   $admin_id = '';
   header('location:login.php');
}

if(isset($_POST['delete'])){

   $delete_id = $_POST['delete_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_delete = $conn->prepare("SELECT * FROM `payments` WHERE id = ?");
   $verify_delete->execute([$delete_id]);

   if($verify_delete->rowCount() > 0){
      $delete_payment = $conn->prepare("DELETE FROM `payments` WHERE id = ?");
      $delete_payment->execute([$delete_id]);
      $success_msg[] = 'Payment record deleted!';
   }else{
      $warning_msg[] = 'Payment already deleted!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Payments</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<!-- header section starts  -->
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<section class="grid">

   <h1 class="heading">Payments</h1>

   <div class="box-container">

   <?php
      $select_payments = $conn->prepare("SELECT * FROM `payments`");
      $select_payments->execute();
      if($select_payments->rowCount() > 0){
         while($fetch_payment = $select_payments->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>Card Owner : <span><?= $fetch_payment['card_owner']; ?></span></p>
      <p>Card Number : <span><?= $fetch_payment['card_number']; ?></span></p>
      <p>Amount : <span>$<?= $fetch_payment['amount']; ?></span></p>
      <p>Status : <span><?= $fetch_payment['payment_status']; ?></span></p>
      <p>Payment Date : <span><?= $fetch_payment['payment_date']; ?></span></p>
      <form action="" method="POST">
         <input type="hidden" name="delete_id" value="<?= $fetch_payment['id']; ?>">
         <input type="submit" value="delete payment" onclick="return confirm('Delete this payment?');" name="delete" class="delete-btn">
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">No payments found!</p>';
      }
   ?>

   </div>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="../js/admin_script.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>
