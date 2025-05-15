<?php  
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
   header('location:login.php');
   exit;
}

if(isset($_POST['post'])){

   $id = create_unique_id();

   $property_name = filter_var($_POST['property_name'], FILTER_SANITIZE_STRING);
   $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
   $deposite = filter_var($_POST['deposite'], FILTER_SANITIZE_STRING);
   $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
   $offer = filter_var($_POST['offer'], FILTER_SANITIZE_STRING);
   $type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);
   $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);
   $loan = filter_var($_POST['loan'], FILTER_SANITIZE_STRING);
   $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
   $bhk = filter_var($_POST['bhk'], FILTER_SANITIZE_STRING); // hectares

   $security = isset($_POST['security']) ? 'yes' : 'no';
   $road = isset($_POST['roads']) ? 'yes' : 'no';
   $electricity = isset($_POST['electricity']) ? 'yes' : 'no';
   $water_supply = isset($_POST['water_supply']) ? 'yes' : 'no';
   $neighbours = isset($_POST['neighbours']) ? 'yes' : 'no';
   $shopping_centre = isset($_POST['shopping_centre']) ? 'yes' : 'no';

   // Handle image uploads
   function handle_image_upload($field_name) {
      if(!empty($_FILES[$field_name]['name'])) {
         $image_name = filter_var($_FILES[$field_name]['name'], FILTER_SANITIZE_STRING);
         $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
         $new_name = create_unique_id().'.'.$image_ext;
         $tmp_name = $_FILES[$field_name]['tmp_name'];
         $size = $_FILES[$field_name]['size'];
         $folder = 'uploaded_files/'.$new_name;

         if($size > 2000000) {  // 2MB limit
            global $warning_msg;
            $warning_msg[] = "$field_name size is too large!";
            return '';
         } else {
            move_uploaded_file($tmp_name, $folder);
            return $new_name;
         }
      }
      return '';
   }

   $image_01 = handle_image_upload('image_01');
   $image_02 = handle_image_upload('image_02');
   $image_03 = handle_image_upload('image_03');
   $image_04 = handle_image_upload('image_04');
   $image_05 = handle_image_upload('image_05');

   // Insert property details into database
   $insert_property = $conn->prepare("INSERT INTO `property`(
      id, user_id, property_name, address, price, type, offer, status, deposite,
      neighbours, loan, electricity, security, shopping_centre, road, water_supply,
      image_01, image_02, image_03, image_04, image_05, description, bhk
   ) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

   $insert_property->execute([
      $id,
      $user_id,
      $property_name,
      $address,
      $price,
      $type,
      $offer,
      $status,
      $deposite,
      $neighbours,
      $loan,
      $electricity,
      $security,
      $shopping_centre,
      $road,
      $water_supply,
      $image_01,
      $image_02,
      $image_03,
      $image_04,
      $image_05,
      $description,
      $bhk // add this line (or sanitize it like others)
   ]);

   $success_msg[] = 'Land posted successfully!';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>post land</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="property-form">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Land details</h3>
      <div class="box">
         <p>Land name <span>*</span></p>
         <input type="text" name="property_name" required maxlength="50" placeholder="enter property name" class="input">
      </div>
      <div class="flex">
         <div class="box">
            <p>Land price <span>*</span></p>
            <input type="number" name="price" required min="0" max="9999999999" maxlength="10" placeholder="enter property price" class="input">
         </div>
         <div class="box">
            <p>deposite amount <span>*</span></p>
            <input type="number" name="deposite" required min="0" max="9999999999" maxlength="10" placeholder="enter deposite amount" class="input">
         </div>
         <div class="box">
            <p>Land address <span>*</span></p>
            <input type="text" name="address" required maxlength="100" placeholder="enter property full address" class="input">
         </div>
         <div class="box">
            <p>offer type <span>*</span></p>
            <select name="offer" required class="input">
               <option value="sale">sale</option>
               <option value="resale">buy</option>
               <option value="rent">rent</option>
            </select>
         </div>
         <div class="box">
            <p>Land type <span>*</span></p>
            <select name="type" required class="input">
               <option value="beach plot">beach plot</option>
               <option value="residential land">residential land</option>
               <option value="farming land">farming land</option>
            </select>
         </div>
         <div class="box">
            <p>Land status <span>*</span></p>
            <select name="status" required class="input">
               <option value="ready to sell">ready to sell</option>
               <option value="under negotiation">under negotiation</option>
            </select>
         </div>
      </div>
      <div class="box">
         <p>how many hectas <span>*</span></p>
         <select name="bhk" required class="input">
            <option value="0.5">0.5 ha </option>
            <option value="1">1 ha</option>
            <option value="2">2 ha</option>
            <option value="3">3 ha</option>
            <option value="4">4 ha</option>
            <option value="5">5 ha</option>
            <option value="6">6 ha</option>
            <option value="8">8 ha</option>
            <option value="10">10 ha</option>
         </select>
      </div>
      <div class="box">
         <p>loan <span>*</span></p>
         <select name="loan" required class="input">
            <option value="available">available</option>
            <option value="not available">not available</option>
         </select>
      </div>
      <div class="box">
         <p>Land description <span>*</span></p>
         <textarea name="description" maxlength="1000" class="input" required cols="30" rows="10" placeholder="write about property..."></textarea>
      </div>
      <div class="checkbox">
         <div class="box">
            <p><input type="checkbox" name="road" value="yes" />road</p>
            <p><input type="checkbox" name="security" value="yes" />security</p>
            <p><input type="checkbox" name="water_supply" value="yes" />water supply</p>
            <p><input type="checkbox" name="electricity" value="yes" />electricity</p>
            <p><input type="checkbox" name="neighbours" value="yes" />neighbours</p>
            <p><input type="checkbox" name="shopping_centre" value="yes" />shopping centre</p>
         </div>
      </div>
      <div class="box">
         <p>image 01 <span>*</span></p>
         <input type="file" name="image_01" class="input" accept="image/*" required>
      </div>
      <div class="flex"> 
         <div class="box">
            <p>image 02</p>
            <input type="file" name="image_02" class="input" accept="image/*">
         </div>
         <div class="box">
            <p>image 03</p>
            <input type="file" name="image_03" class="input" accept="image/*">
         </div>
         <div class="box">
            <p>image 04</p>
            <input type="file" name="image_04" class="input" accept="image/*">
         </div>
         <div class="box">
            <p>image 05</p>
            <input type="file" name="image_05" class="input" accept="image/*">
         </div>   
      </div>
      <input type="submit" value="post land" class="btn" name="post">
   </form>

</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>
