<?php
include 'components/connect.php';

// Initialize user_id from cookie, if set
if (isset($_COOKIE['user_id'])) {
   $user_id = $_COOKIE['user_id'];
} else {
   $user_id = '';
}

if (isset($_POST['submit'])) {
   // Sanitize user input
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL); 
   $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING); 

   // Hash the password using SHA1 to match what's stored in the DB
   $hashed_pass = sha1($pass);

   // Prepare the query to select the user
   $select_users = $conn->prepare("SELECT * FROM `users` WHERE email = ? LIMIT 1");
   $select_users->execute([$email]);

   // Check if user exists
   if ($select_users->rowCount() > 0) {
      $row = $select_users->fetch(PDO::FETCH_ASSOC);

      // Compare hashed password
      if ($hashed_pass === $row['password']) {
         // Set the cookie and session for user_id and user_role
         setcookie('user_id', $row['id'], time() + 60*60*24*30, '/');
         
         // Start session and store the user role
         session_start();
         $_SESSION['user_id'] = $row['id'];
         $_SESSION['role'] = $row['role'];  // Store the role in the session

         header('location:home.php');
         exit;
      } else {
         $warning_msg[] = 'Incorrect username or password!';
      }
   } else {
      $warning_msg[] = 'User not found!';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- login section starts -->
<section class="form-container">
   <form action="" method="post">
      <h3>welcome back!</h3>
      <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="box">
      <input type="password" name="pass" required maxlength="20" placeholder="enter your password" class="box">
      <p>don't have an account? <a href="register.php">register new</a></p>
      <p>Login as Admin!<a href="admin/login.php">Login now</a></p>
      <input type="submit" value="login now" name="submit" class="btn">
   </form>

   <!-- Display warning message -->
   <?php
   if (isset($warning_msg)) {
      foreach ($warning_msg as $msg) {
         echo '<div class="alert alert-warning">' . $msg . '</div>';
      }
   }
   ?>
</section>
<!-- login section ends -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
<?php include 'components/message.php'; ?>

</body>
</html>
