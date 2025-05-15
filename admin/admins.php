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

   $verify_delete = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
   $verify_delete->execute([$delete_id]);

   if($verify_delete->rowCount() > 0){
      $delete_admin = $conn->prepare("DELETE FROM `admins` WHERE id = ?");
      $delete_admin->execute([$delete_id]);
      $success_msg[] = 'Admin deleted!';
   }else{
      $warning_msg[] = 'Admin deleted already!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admins</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include '../components/admin_header.php'; ?>
<!-- header section ends -->

<!-- admins section starts  -->

<section class="grid">

   <h1 class="heading">admins</h1>

   <form action="" method="POST" class="search-form">
      <input type="text" name="search_box" placeholder="search admins..." maxlength="100" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>

   <div class="box-container">

   <?php
      if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
         $search_box = $_POST['search_box'];
         $search_box = filter_var($search_box, FILTER_SANITIZE_STRING);
         $select_admins = $conn->prepare("SELECT * FROM `admins` WHERE name LIKE '%{$search_box}%'");
         $select_admins->execute();
      }else{
         $select_admins = $conn->prepare("SELECT * FROM `admins`");
         $select_admins->execute();
      }
      if($select_admins->rowCount() > 0){
         while($fetch_admins = $select_admins->fetch(PDO::FETCH_ASSOC)){
   ?>
   <?php if( $fetch_admins['id'] == $admin_id){ ?>
   <div class="box" style="order: -1;">
      <p>name : <span><?= $fetch_admins['name']; ?></p>
      <a href="update.php" class="option-btn">update account</a>
      <a href="register.php" class="btn">register new</a>
   </div>
   <?php }else{?>
   <div class="box">
      <p>name : <span><?= $fetch_admins['name']; ?></p>
      <form action="" method="POST">
         <input type="hidden" name="delete_id" value="<?= $fetch_admins['id']; ?>">
         <input type="submit" value="delete admin" onclick="return confirm('delete this admin?');" name="delete" class="delete-btn">
      </form>
   </div>
   <?php } ?>
   <?php
      }
   }elseif(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
      echo '<p class="empty">no results found!</p>';
   }else{
   ?>
      <p class="empty">no admins added yet!</p>
      <div class="box" style="text-align: center;">
      <p>create a new admin</p>
      <a href="register.php" class="btn">register now</a>
   </div>
   <?php
      }
   ?>

   </div>

</section>

<!-- admins section ends -->
















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>