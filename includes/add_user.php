<?php
require_once("connection.php");
?>
<?php
if (isset($_GET['back'])) {
  header("location:../index.php");
  session_unset();
}
?>
<?php
if (isset($_POST['submit'])) {
  if (isset($_POST['user_name']) && isset($_POST['user_sso']) && isset($_POST['pass_1']) && isset($_POST['pass_2'])) {
    if ($_POST['pass_1'] == $_POST['pass_2']) {
      $valid_user_query = "SELECT * FROM users WHERE name='{$_POST['user_name']}' AND doj ='{$_POST['user_sso']}'";
      $valid_user_result = mysqli_query($conn, $valid_user_query);

      if (mysqli_num_rows($valid_user_result) > 0) {
        $pass_error = "DOJ has already taken";
      } else {
        $insert_new = "INSERT INTO users (name, doj, password)
            VALUES ('{$_POST['user_name']}', '{$_POST['user_sso']}', '{$_POST['pass_1']}')";

        if (!mysqli_query($conn, $insert_new)) {

          echo "Error: " . $insert_new . "<br>" . mysqli_error($conn);
        } else {
          header("location:./submit.php");
        }
      }
    } else {
      $pass_error = "Passwords aren't same";
    }
  }
}
////////////////////////////////////////
if(isset($_GET['remove'])){
  $del_query = "DELETE FROM users WHERE id={$_GET['remove']}";

  if (!mysqli_query($conn, $del_query)) {
    echo "Error deleting record: " . mysqli_error($conn);
  }
  
}

?>
<!DOCTYPE html>
<html>

<head>
  <title>Registration form</title>
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
              if ($check['access'] == 1) {
                echo " <li class='nav-item '><a href='./add_user.php' class='active btn button m-2'>Add new user</a></li>
        <li class='nav-item'><a href='./add_device.php' class='btn button m-2'>Add Device</a></li>
        <li class='nav-item'><a href='./submit.php' class='btn button m-2'>Home</a></li>";
              }
            }
          } ?>

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
      <p class='fw-bold fs-3 text-center text-dark'>Register Form</p>
      <?php if (isset($pass_error)) {
                                      echo "<p class='alert alert-danger'>$pass_error</p>";
                                    } ?>
      <form method='post'>
        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" name='user_name' required>

        </div>
        <div class="mb-3">
          <label for="SSO" class="form-label">DOJ</label>
          <input type="text" class="form-control" id="SSO" name='user_sso' required>

        </div>
        <div class="mb-3">
          <label for="password1" class="form-label">Password</label>
          <input type="password" class="form-control" id="password1" name='pass_1' required>
        </div>
        <div class="mb-3">
          <label for="password2" class="form-label">confirm Password</label>
          <input type="text" class="form-control" id="password2" name='pass_2' required>
        </div>

        <button type="submit" class="btn btn-primary" name='submit'>Submit</button>
      </form>
    </div>
  </div>
  <!-- ////////////////////////////////////////////////////////////////// -->
<div class="container " style="margin-top: 100px !important;">
<?php
$showall_users_query = "SELECT * FROM users";
        $showall_users = mysqli_query($conn, $showall_users_query);

        if (mysqli_num_rows($showall_users) > 0) {
            // output data of each row
            $index=1;
            echo " <div class='p-4 '>
            <table id='table-product-list' class='table table-hover table-bordered table-striped table-sm'>
            <thead class='table-dark '>
              <tr>
                <th scope='col'>S.NO</th>
                <th scope='col'>Name</th>
                <th scope='col'>DOJ</th>
                <th scope='col'>Action</th>
                
                
                </tr>
            </thead><tbody>";
            while ($row_all = mysqli_fetch_assoc($showall_users)) {
                echo "
  
                <tr>
                  <th scope='row'>{$index}</th>
                  <td>{$row_all['name']}</td>
                  <td>{$row_all['doj']}</td>
                  <td><form>
                  <button class='btn btn-danger' name='remove' value='{$row_all['id']}' >Remove</button>
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