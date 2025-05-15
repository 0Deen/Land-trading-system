<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING); 
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $role = filter_var($_POST['role'], FILTER_SANITIZE_STRING);  // Get role from form
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING); 
   $c_pass = sha1($_POST['c_pass']);
   $c_pass = filter_var($c_pass, FILTER_SANITIZE_STRING);   

   $select_users = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_users->execute([$email]);

   if($select_users->rowCount() > 0){
      $warning_msg[] = 'email already taken!';
   }else{
      if($pass != $c_pass){
         $warning_msg[] = 'Password not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(id, name, email, number, password, role) VALUES(?,?,?,?,?,?)");
         $insert_user->execute([$id, $name, $email, $number, $c_pass, $role,]);
         
         if($insert_user){
            $verify_users = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? LIMIT 1");
            $verify_users->execute([$email, $pass]);
            $row = $verify_users->fetch(PDO::FETCH_ASSOC);
         
            if($verify_users->rowCount() > 0){
               setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
               header('location:home.php');
            }else{
               $error_msg[] = 'something went wrong!';
            }
         }

      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">


</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<!-- register section starts  -->

<section class="form-container">

   <form action="" method="post">
      <h3>create an account!</h3>
      <input type="tel" name="name" required maxlength="50" placeholder="enter your name" class="box">
      <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="box">
      <input type="number" name="number" required min="0" max="9999999999" maxlength="10" placeholder="enter your number" class="box">
      <input type="password" name="pass" required maxlength="20" placeholder="enter your password" class="box">
      <input type="password" name="c_pass" required maxlength="20" placeholder="confirm your password" class="box">
      <select name="role" required class="box">
      <option value="">Select Role</option>
      <option value="buyer">Buyer</option>
      <option value="seller">Seller</option>
   </select>
<<<<<<< HEAD
      <p>already have an account? <a href="login.php">login now</a></p>
=======
      <p>already have an account? <a href="login.html">login now</a></p>
>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
      <input type="submit" value="register now" name="submit" class="btn">
   </form>

</section>

<!-- register section ends -->










<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>