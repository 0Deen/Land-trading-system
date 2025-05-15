<?php include 'components/connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Payment Successful</title>

   <!-- Font Awesome CDN link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- Custom CSS file link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Success Message Section -->
<section class="form-container" style="text-align: center;">
   <h3>🎉 Payment Successful!</h3>
   <p>Thank you — your payment was processed correctly.</p>
   <a href="home.php" class="btn">Back to Home</a>
</section>

<?php include 'components/footer.php'; ?>

<!-- Custom JS file -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

<!-- Optional SweetAlert Success Popup -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script>
swal("Success!", "Your payment has been completed.", "success");
</script>

</body>
</html>
