<?php require_once("connection.php"); ?>
<?php
if (isset($_GET['back'])) {
    header("location:../index.php");
    session_unset();
}
if (isset($_POST['reset'])) {
    header("location:blocked_list.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Blocked List</title>
    <link href="../assests/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assests/css/style.css" rel="stylesheet">
    <script src='../assests/js/code.jquery.com_jquery-3.7.0.min.js'></script>
    <script src='../assests/js/cdn.jsdelivr.net_gh_rainabba_jquery-table2excel@1.1.0_dist_jquery.table2excel.min.js'></script>
    <!-- <script src='https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js'></script> -->
</head>

<body>
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <!-- <img src="../assests/img/icon.jpeg" alt="" width="30" height="24" -->
                        <!-- class="d-inline-block align-text-top"> -->
                    <!-- Comcast -->
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
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
                                    echo " <li class='nav-item'><a href='./add_user.php' class='btn button m-2'>Add new</a></li>
        <li class='nav-item'><a href='./add_device.php' class='btn button m-2'>Add Device</a></li>
        ";
                                }
                            }
                        } ?>
<li class='nav-item'><a href='./submit.php' class='btn button m-2'>Home</a></li>
                        <li class="nav-item">
                            <form><button class='btn btn-danger m-2' name='back'>Log-out</button></form>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
    </div>
    <div class="container">
        <!-- <img src="../assests/img/icon.jpeg" class="w-100 border border-1  height" alt="Image at top"> -->
        <div class="d-flex justify-content-center m-3">
            
            <span class=' fs-3 m-2 text-dark'>FILTER :</span>
            <?php
             if (isset($err)) {
                echo " <p class='alert alert-danger'>$err</p>";
            } ?>
            
            <form method='post' class="row g-3">

                <div class="col-auto">
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control" id="floatingInput" placeholder="From date"
                            name='from_date' value="<?php if(isset($_POST['from_date'])){echo $_POST['from_date'];}else{echo '';}?>" >
                        <label for="floatingInput">From date</label>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="form-floating mb-3">
                        <input type="date" class="form-control"  value="<?php if(isset($_POST['to_date'])){echo $_POST['to_date'];}else{echo '';}?>" id="floatingInput" placeholder="To date" name='to_date'>
                        <label for="floatingInput">To date</label>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary p-3" name="filter">Filter</button>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-dark p-3" name="reset">Reset</button>
                </div>

            </form>
          
        </div>
    </div>
    <div class="d-flex justify-content-start ms-4"> 
            <button class="btn btn-outline-success " onclick="exportCSVExcel()">Download Table</button>
        </div> 
    <?php
    if (isset($_POST['filter'])) {
        if (isset($_POST['from_date']) && isset($_POST['to_date'])) {
            $d1 = strtotime($_POST['from_date']);
            $from = date("Y-m-d", $d1);
            $d2 = strtotime($_POST['to_date']);
            $to = date("Y-m-d", $d2);

            $check_data_query = "SELECT * FROM block_release_list";
            $data_result = mysqli_query($conn, $check_data_query);

            if (mysqli_num_rows($data_result) > 0) {
                // output data of each row
                $index=1;
                echo " <div  class='p-4 '>
                <table id='table-product-list' class='table table-hover table-striped table-sm '>
            <thead class='table-dark'>
              <tr>
                <th scope='col'>S.NO</th>
                <th scope='col'>Device_Name</th>
                <th scope='col'>Serial_Number</th>
                
                <th scope='col'>Blocked by</th>
                <th scope='col'>Blocked time&Date</th>
                
                <th scope='col'>Release time&Date</th>
                </tr>
            </thead><tbody>";
                while ($rowOfdata = mysqli_fetch_assoc($data_result)) {
                    $date = $rowOfdata['Block_time&date'];
                    $d = strtotime($date);
                    $row_date = date("Y-m-d", $d);
                    ///
                    if ($from <= $row_date && $to >= $row_date) {

                        echo "
  
    <tr>
      <th scope='row'>{$index}</th>
      <td>{$rowOfdata['Device_Name']}</td>
      <td>{$rowOfdata['Serial_Number']}</td>
     
      <td>{$rowOfdata['Blocked_by']}</td>
      <td>{$rowOfdata['Block_time&date']}</td>
      <td>{$rowOfdata['Release_time&date']}</td></tr>";


      $index +=1;
                    }
                }
                echo "
            </tbody>
            </table></div>";
            } else {
                echo "<p class='text-center display-4 fw-bolder'>0 results</p>";
            }
        } else {
            $err = "Enter from date and to date ";
        }
    } else {
        $showall_data_query = "SELECT * FROM block_release_list ORDER BY id DESC";
        $showall_result = mysqli_query($conn, $showall_data_query);

        if (mysqli_num_rows($showall_result) > 0) {
            // output data of each row
            $index=1;
            echo " <div class='p-4 '>
            <table id='table-product-list' class='table table-hover table-striped table-sm'>
            <thead class='table-dark '>
              <tr>
                <th scope='col'>S.NO</th>
                <th scope='col'>Device_Name</th>
                <th scope='col'>Serial_Number</th>
                
                <th scope='col'>Blocked by</th>
                <th scope='col'>Blocked time&Date</th>
                
                <th scope='col'>Release time&Date</th>
                </tr>
            </thead><tbody>";
            while ($row_all = mysqli_fetch_assoc($showall_result)) {
                echo "
  
                <tr>
                  <th scope='row'>{$index}</th>
                  <td>{$row_all['Device_Name']}</td>
                  <td>{$row_all['Serial_Number']}</td>
                 
                  <td>{$row_all['Blocked_by']}</td>
                  <td>{$row_all['Block_time&date']}</td>
                  <td>{$row_all['Release_time&date']}</td></tr>";
                  $index +=1;
            }
            echo "
            </tbody>
            </table></div>";
        } else {
        $err="Empty table";
        }
    }

    ?>
<script>
   function exportCSVExcel() {
    	$('#table-product-list').table2excel({
    		exclude: ".no-export",
    		filename: "Blocked Data.xls",
    		fileext: ".xls",
    		exclude_links: true,
    		exclude_inputs: true
    	});
    }
   
    </script>
    <script src="../assests/js/bootstrap.bundle.min.js"></script>
</body>

</html>