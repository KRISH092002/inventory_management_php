<?php
require_once('connection.php');
?>
<?php
if(isset($_GET['back'])){
  header('location:../index.php');
  session_unset();
}


if(isset($_POST['submit'])){

    $target_file = $target_dir . basename($_FILES['device_image']['name']);
    $name = $_FILES['device_image']['name'];
    $img_query = "SELECT * FROM inventory_list WHERE `S.NO`={$_GET['id']}";
  $img_result = mysqli_query($conn, $img_query);

  if (mysqli_num_rows($img_result) > 0) {
    while ($imgcheck = mysqli_fetch_assoc($img_result)) {
        $device_name= $imgcheck['Device_Name'];
        $img = $imgcheck['device_image'];
        if($imgcheck['Blocked by'] !=''){
          echo"<script>alert('The device locked by ".$imgcheck['Blocked by'] ."')
          setTimeout(function(){
            window.location.href='./add_device.php'
          },1000)
          </script>";
         
          exit();
        }
    }}
    if(!empty($name)){
  unlink($target_dir.$img);}else{
    $name = $img;
  }
    if (move_uploaded_file($_FILES['device_image']['tmp_name'], $target_file)) {
      $uploadOk = 1;
    }
    else{
      $uploadOk = 0;
    }
      $insert_device = "UPDATE inventory_list SET Device_Name='{$_POST['device_name']}',Serial_Number='{$_POST['serial_number']}',Model_Number='{$_POST['model_number']}',
      Model_Year='{$_POST['model_year']}',`IMEI No/Tag No`='{$_POST['IMEINo/TagNo']}',accessories='{$_POST['accessories']}',device_image='$name' WHERE `S.NO`={$_GET['id']}";
    
      if (!mysqli_query($conn, $insert_device) && $uploadOk == 1) {
    
          $pass_error='Unable to update device ';
      }
      else{
          header('location:./add_device.php');
      }
    }
    
?>
<!DOCTYPE html>
<html>

<head>
  <title>Inventory tool-Add device</title>
  <link href='../assests/css/bootstrap.min.css' rel='stylesheet'>
  <link href='../assests/css/style.css' rel='stylesheet'>
  <script src='../assests/js/code.jquery.com_jquery-3.7.0.min.js'></script>
</head>

<body>
<nav class='navbar navbar-expand-lg navbar-dark bg-dark '>
  <div class='container'>
  <a class='navbar-brand' href='#'>
      <!-- <img src='../assests/img/icon.jpeg' alt='' width='30' height='24' class='d-inline-block align-text-top'> -->
      <!-- Comcast -->
    </a>
    <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
      <span class='navbar-toggler-icon'></span>
    </button>
    <div class='collapse navbar-collapse' id='navbarSupportedContent'>
      <ul class='navbar-nav ms-auto mb-2 mb-lg-0'>
       
        
        <?php
  $access_query = "SELECT * FROM users WHERE name='{$_SESSION['name']}' AND doj ='{$_SESSION['doj']}' AND password='{$_SESSION['pass']}'";
  $access_result = mysqli_query($conn, $access_query);

  if (mysqli_num_rows($access_result) > 0) {
    while ($check = mysqli_fetch_assoc($access_result)) {
      if($check['access'] == 1 ){
        echo" <li class='nav-item'><a href='./add_user.php' class='btn button m-2'>Add new user</a></li>
        <li class='nav-item'><a href='./add_device.php' class='btn button m-2 active'>Back</a></li>
        <li class='nav-item'><a href='./submit.php' class='btn button m-2'>Home</a></li>";
      }
    }}?>
   <li class='nav-item'>
        <form><button class='btn btn-danger m-2' name='back'>Log-out</button></form>
        </li>
        
      </ul>
      
    </div>
  </div>
</nav>
<div class='container'>
        <!-- <img src='../assests/img/icon.jpeg' class='w-100 border border-1  height' alt='Image at top'> -->
<div class='col-md-6 offset-md-3 margintop border border-1  border-light shadow p-5 rounded-4'>
        <p class='fw-bold fs-3 text-center text-dark'>Add Device</p>
        <form method='post' enctype='multipart/form-data'>
            <?php
        $img_query = "SELECT * FROM inventory_list WHERE `S.NO`={$_GET['id']}";
  $img_result = mysqli_query($conn, $img_query);

  if (mysqli_num_rows($img_result) > 0) {
    while ($imgcheck = mysqli_fetch_assoc($img_result)) {
        $device_name= $imgcheck['Device_Name'];
        $img = $imgcheck['device_image'];
        echo"<div class='mb-3'>
        <label for='device_name' class='form-label'>Device name</label>
        <input type='text' class='form-control' id='device_name' name='device_name' value='{$device_name}' required>

    </div>
    <div class='mb-3'>
        <label for='serial_number' class='form-label'>Serial Number</label>
        <input type='text' class='form-control' id='serial_number' name='serial_number' value='{$imgcheck['Serial_Number']}' required>

    </div>
    <div class='mb-3'>
        <label for='model_number' class='form-label'>Model Number</label>
        <input type='text' class='form-control' id='model_nummer' name='model_number' value='{$imgcheck['Model_Number']}' required>
    </div>
    <div class='mb-3'>
        <label for='model_year' class='form-label'>Model Year</label>
        <input type='text' class='form-control' id='model_year' name='model_year' value='{$imgcheck['Model_Year']}' required>
    </div>
    <div class='mb-3'>
        <label for='IMEINo/TagNo' class='form-label'>IMEI No/Tag No</label>
        <input type='text' class='form-control' id='IMEINo/TagNo' name='IMEINo/TagNo' value='{$imgcheck['IMEI No/Tag No']}' required>
    </div>
    <div class='mb-3'>
        <label for='Accessories' class='form-label'>Accessories</label>
        <input type='text' class='form-control' id='Accessories' name='accessories' value='{$imgcheck['accessories']}'>
        <small class='note'>Note : Enter accessories separated by comma , Don't use space and (Nil/Null)</small>
    </div>
    <div class='mb-3 row '>
        <label for='device_image' class='form-label'>Device image</label>
        <div class=' col'><input type='file' class='form-control' id='device_image' name='device_image'></div>
        <img src='$target_dir$img' class='col-3'  width='50' height='50'>
    </div>
    <button type='submit' class='btn btn-primary' name='submit'>Submit</button>";
    }}
     ?>       
        </form>
    </div>
</div>

<script src='../assests/js/bootstrap.bundle.min.js'></script>
</body>

</html>