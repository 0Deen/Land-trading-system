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

   $verify_delete = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
   $verify_delete->execute([$delete_id]);

   if($verify_delete->rowCount() > 0){
      $select_images = $conn->prepare("SELECT * FROM `property` WHERE user_id = ?");
      $select_images->execute([$delete_id]);
      while($fetch_images = $select_images->fetch(PDO::FETCH_ASSOC)){
         $image_01 = $fetch_images['image_01'];
         $image_02 = $fetch_images['image_02'];
         $image_03 = $fetch_images['image_03'];
         $image_04 = $fetch_images['image_04'];
         $image_05 = $fetch_images['image_05'];
         unlink('../uploaded_files/'.$image_01);
         if(!empty($image_02)){
            unlink('../uploaded_files/'.$image_02);
         }
         if(!empty($image_03)){
            unlink('../uploaded_files/'.$image_03);
         }
         if(!empty($image_04)){
            unlink('../uploaded_files/'.$image_04);
         }
         if(!empty($image_05)){
            unlink('../uploaded_files/'.$image_05);
         }
      }
      $delete_listings = $conn->prepare("DELETE FROM `property` WHERE user_id = ?");
      $delete_listings->execute([$delete_id]);
      $delete_requests = $conn->prepare("DELETE FROM `requests` WHERE sender = ? OR receiver = ?");
      $delete_requests->execute([$delete_id, $delete_id]);
      $delete_saved = $conn->prepare("DELETE FROM `saved` WHERE user_id = ?");
      $delete_saved->execute([$delete_id]);
      $delete_user = $conn->prepare("DELETE FROM `users` WHERE id = ?");
      $delete_user->execute([$delete_id]);
      $success_msg[] = 'user deleted!';
   }else{
      $warning_msg[] = 'User deleted already!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users</title>

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

   <h1 class="heading">users</h1>

   <form action="" method="POST" class="search-form">
      <input type="text" name="search_box" placeholder="search users..." maxlength="100" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>

   <div class="box-container">

   <?php
      if(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
         $search_box = $_POST['search_box'];
         $search_box = filter_var($search_box, FILTER_SANITIZE_STRING);
         $select_users = $conn->prepare("SELECT * FROM `users` WHERE name LIKE '%{$search_box}%' OR number LIKE '%{$search_box}%' OR email LIKE '%{$search_box}%'");
         $select_users->execute();
      }else{
         $select_users = $conn->prepare("SELECT * FROM `users`");
         $select_users->execute();
      }
      if($select_users->rowCount() > 0){
         while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){

            $count_property = $conn->prepare("SELECT * FROM `property` WHERE user_id = ?");
            $count_property->execute([$fetch_users['id']]);
            $total_properties = $count_property->rowCount();
   ?>
   <div class="box">
      <p>name : <span><?= $fetch_users['name']; ?></span></p>
      <p>number : <a href="tel:<?= $fetch_users['number']; ?>"><?= $fetch_users['number']; ?></a></p>
      <p>email : <a href="mailto:<?= $fetch_users['email']; ?>"><?= $fetch_users['email']; ?></a></p>
      <p>properties listed : <span><?= $total_properties; ?></span></p>
      <form action="" method="POST">
         <input type="hidden" name="delete_id" value="<?= $fetch_users['id']; ?>">
         <input type="submit" value="delete user" onclick="return confirm('delete this user?');" name="delete" class="delete-btn">
      </form>
   </div>
   <?php
      }
   }elseif(isset($_POST['search_box']) OR isset($_POST['search_btn'])){
      echo '<p class="empty">results not found!</p>';
   }else{
      echo '<p class="empty">no users accounts added yet!</p>';
   }
   ?>

   </div>

</section>

<!-- users section ends -->
















<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="../js/admin_script.js"></script>

<?php include '../components/message.php'; ?>

</body>
</html>