<?php
require_once("connection.php");
?>
<?php
if(isset($_GET['back'])){
  header("location:../index.php");
  session_unset();
}
?>
<?php
if(isset($_POST['submit'])){

$target_file = $target_dir . basename($_FILES["device_image"]["name"]);
$name = $_FILES["device_image"]["name"];

if (move_uploaded_file($_FILES["device_image"]["tmp_name"], $target_file)) {
  $uploadOk = 1;
}
else{
  $uploadOk = 0;
}
  $insert_device = "INSERT INTO inventory_list (Device_Name, Serial_Number, Model_Number,Model_Year,`IMEI No/Tag No`, accessories, device_image)
  VALUES ('{$_POST['device_name']}', '{$_POST['serial_number']}', '{$_POST['model_number']}','{$_POST['model_year']}','{$_POST['IMEINo/TagNo']}','{$_POST['accessories']}','{$name}')";

  if (!mysqli_query($conn, $insert_device) && $uploadOk == 1) {

      $pass_error="Unable to add new device ";
  }
  else{
      header("location:./submit.php");
  }
}

////////////////////////////////////////
if(isset($_GET['remove'])){
  $del_query = "DELETE FROM inventory_list WHERE `S.NO`={$_GET['remove']}";
  $img_query = "SELECT * FROM inventory_list WHERE `S.NO`={$_GET['remove']}";
  $img_result = mysqli_query($conn, $img_query);

  if (mysqli_num_rows($img_result) > 0) {
    while ($imgcheck = mysqli_fetch_assoc($img_result)) {
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
    if(isset($img)){
  unlink($target_dir.$img);}
  if (!mysqli_query($conn, $del_query)) {
    echo "Error deleting record: " . mysqli_error($conn);
  }
  
}

?>
<!DOCTYPE html>
<html>

<head>
  <title>Inventory tool-Add device</title>
  <link href="../assests/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assests/css/style.css" rel="stylesheet">
  <script src='../assests/js/code.jquery.com_jquery-3.7.0.min.js'></script>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
  <div class="container">
  <a class="navbar-brand" href="#">
      <!-- <img src="../assests/img/icon.jpeg" alt="" width="30" height="24" class="d-inline-block align-text-top"> -->
      <!-- Comcast -->
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
       
        
        <?php
  $access_query = "SELECT * FROM users WHERE name='{$_SESSION['name']}' AND doj ='{$_SESSION['doj']}' AND password='{$_SESSION['pass']}'";
  $access_result = mysqli_query($conn, $access_query);

  if (mysqli_num_rows($access_result) > 0) {
    while ($check = mysqli_fetch_assoc($access_result)) {
      if($check['access'] == 1 ){
        echo" <li class='nav-item'><a href='./add_user.php' class='btn button m-2'>Add new user</a></li>
        <li class='nav-item'><a href='./add_device.php' class='btn button m-2 active'>Add Device</a></li>
        <li class='nav-item'><a href='./submit.php' class='btn button m-2'>Home</a></li>";
      }
    }}?>
   <li class="nav-item">
        <form><button class='btn btn-danger m-2' name='back'>Log-out</button></form>
        </li>
        
      </ul>
      
    </div>
  </div>
</nav>
<div class="container">
        <!-- <img src="../assests/img/icon.jpeg" class="w-100 border border-1  height" alt="Image at top"> -->
<div class="col-md-6 offset-md-3 margintop border border-1  border-light shadow p-5 rounded-4">
        <p class='fw-bold fs-3 text-center text-dark'>Add Device</p>
        <?php if (isset($pass_error)) {
                                        echo "<p class='alert alert-danger'>$pass_error</p>";
                                    } ?>
        <form method='post' enctype="multipart/form-data">
            <div class="mb-3">
                <label for="device_name" class="form-label">Device name</label>
                <input type="text" class="form-control" id="device_name" name='device_name' required>

            </div>
            <div class="mb-3">
                <label for="serial_number" class="form-label">Serial Number</label>
                <input type="text" class="form-control" id="serial_number" name='serial_number' required>

            </div>
            <div class="mb-3">
                <label for="model_number" class="form-label">Model Number</label>
                <input type="text" class="form-control" id="model_nummer" name='model_number' required>
            </div>
            <div class="mb-3">
                <label for="model_year" class="form-label">Model Year</label>
                <input type="text" class="form-control" id="model_year" name='model_year' required>
            </div>
            <div class="mb-3">
                <label for="IMEINo/TagNo" class="form-label">IMEI No/Tag No</label>
                <input type="text" class="form-control" id="IMEINo/TagNo" name='IMEINo/TagNo' required>
            </div>
            <div class="mb-3">
                <label for="Accessories" class="form-label">Accessories</label>
                <input type="text" class="form-control" id="Accessories" name='accessories'>
                <small class='note'>Note : Enter accessories separated by comma , Don't use space and (Nil/Null)</small>
            </div>
            <div class="mb-3">
                <label for="device_image" class="form-label">Device image</label>
                <input type="file" class="form-control" id="device_image" name='device_image' required>
            </div>
            <button type="submit" class="btn btn-primary" name='submit'>Submit</button>
        </form>
    </div>
</div>
<div class="container " style="margin-top: 100px !important;">
<?php
$showall_device_query = "SELECT * FROM inventory_list";
        $showall_device = mysqli_query($conn, $showall_device_query);

        if (mysqli_num_rows($showall_device) > 0) {
            // output data of each row
            $index=1;
            echo " <div class='p-4 '>
            <table id='table-product-list' class='table table-hover table-bordered table-striped table-sm'>
            <thead class='table-dark '>
              <tr>
                <th scope='col'>S.NO</th>
                <th scope='col'>Device_Name</th>
                <th scope='col'>Serial_Number</th>
                <th scope='col'>Model_Number</th>
                <th scope='col'>Model_Year</th>
                <th scope='col'>IMEI No/Tag No</th>
                <th scope='col'>Accessories</th>
                <th scope='col'>Device image</th>
                <th scope='col'>Action</th>
                
                
                </tr>
            </thead><tbody>";
            while ($row = mysqli_fetch_assoc($showall_device)) {
              $img = $row['device_image'];
                echo "
  
                <tr>
                  <th scope='row'>{$index}</th>
                  <td>{$row['Device_Name']}</td>
                  <td>{$row['Serial_Number']}</td>
                  <td>{$row['Model_Number']}</td>
                  <td>{$row['Model_Year']}</td>
                  <td>{$row['IMEI No/Tag No']}</td>
                  <td><ol>";

                  if($row['accessories'] != null){
                    $accArr = explode(',',$row['accessories']);
                    foreach($accArr as $key => $val){
                      echo "<li>$val</li>";
                    }
                  }
                  echo "</ol></td><td><img src='$target_dir$img'   width=50 height=50 alt='$img'> </td>
                  <td><form>
                  <button class='btn btn-danger' name='remove' value='{$row['S.NO']}' >Remove</button>
<a href='./formEdit.php?id={$row['S.NO']}' class='btn btn-success rounded-5 mt-1'>Update</a>
                  </form>
                  </td>
                 ";
                  $index +=1;
            }
            echo "
            </tbody>
            </table></div>";
        } else {
        $err="Empty table";
        }
    
?>
</div>
<script src='../assests/js/bootstrap.bundle.min.js'></script>
</body>

</html>