<?php
//connection to database
require_once("connection.php");
?>
<?php
if (isset($_GET['back'])) {
  header("location:../index.php");
  session_unset();
}
?>

<?php
//total count of devices
$data_count_query = "SELECT COUNT(Device_Name) FROM inventory_list";
$data_count_result = mysqli_query($conn, $data_count_query);
if (mysqli_num_rows($data_count_result) > 0) {
  while ($num = mysqli_fetch_assoc($data_count_result))
    $count = $num['COUNT(Device_Name)'];
}
//total count of blocked device
$block_count_query = "SELECT COUNT(*) FROM inventory_list WHERE Status= 'Blocked'";
$block_count_result = mysqli_query($conn, $block_count_query);
if (mysqli_num_rows($block_count_result) > 0) {
  while ($block = mysqli_fetch_assoc($block_count_result))
    $num_block = $block['COUNT(*)'];
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>Inventory tool</title>
  <link href="../assests/css/bootstrap.min.css" rel="stylesheet">
  <link href="../assests/css/style.css" rel="stylesheet">
  <script src='../assests/js/code.jquery.com_jquery-3.7.0.min.js'></script>
  <script src='../assests/js/angular.min.js'></script>
</head>

<body ng-app="myApp">

  <div ng-controller="inventoryCntrl" ng-init="getUserData();">
    <div class="container-fluid p-0">
      <nav class="navbar navbar-expand-lg  navbar-dark bg-dark shadow">
        <div class="container">
          <a class="navbar-brand" href="#"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
              <li class='nav-item' ng-if="userData.access == '1'"><a href='./add_user.php' class='btn button m-2'>Add new user</a></li>
              <li class='nav-item' ng-if="userData.access == '1'"><a href='./add_device.php' class='btn button m-2'>Add Device</a></li>
              <li class='nav-item'><a href='./blocked_list.php' class='btn button m-2'>Blocked List</a></li>
              <li class="nav-item">
                <form><button class='btn btn-danger m-2' name='back'>Log-out</button></form>
              </li>

            </ul>

          </div>
        </div>
      </nav>
    </div>
    <h1 class="text-center mt-3 text-dark">Device Inventory</h1>
    <div class="container">
      <div class="row justify-content-evenly mt-3">
        <div class="col-3 p-4 rounded-4 text-center bg-light brd">Total no of devices :
          <?php echo $count; ?>
        </div>
        <div class="col-3 p-4 rounded-4 text-center bg-light brd">Blocked count :
          <?php echo $num_block; ?>
        </div>
        <div class="col-3 p-4 rounded-4 text-center bg-light brd">Unblocked count :
          <?php echo $count - $num_block; ?>
        </div>
      </div>

    </div>
    <div class=' mx-1 p-0 mt-5 border border-2 ' ng-init="getTableData()">
      <table class='table table-hover w-100 table-sm table-bordered'>
        <thead class='table-primary text-center'>
          <tr>
            <th scope='col'>S.NO</th>
            <th scope='col'>Device_Name</th>
            <th scope='col'>Serial_Number</th>
            <th scope='col'>Model_Number</th>
            <th scope='col'>Model_Year</th>
            <th scope='col'>IMEI No/Tag No</th>
            <th scope='col'>Status</th>
            <th scope='col'>Blocked by</th>
            <th scope='col'>Blocked time&Date</th>
            <th scope='col'>Accessories</th>
            <th scope='col'>Device image</th>

          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="(key , table) in tableData track by $index">
            <th scope='row'>{{key + 1}}</th>
            <td class=''>{{table['Device_Name']}}</td>
            <td>{{table['Serial_Number']}}</td>
            <td>{{table['Model_Number']}}</td>
            <td>{{table['Model_Year']}}</td>
            <td>{{table['IMEI No/Tag No']}}</td>

            <td>
              <select name='option' class='form-control' ng-value="table.Status">
                <option value='select' selected>--select--</option>
                <option value='Blocked'>Blocked</option>
                <option value='Unblocked'>Unblocked</option>

              </select>
              <button class='btn btn-primary mt-1 w-100' ng-click="add(table['S.NO'],table.Status , $event , key)">Ok</button>
            </td>
            <td>{{table['Blocked by']}}</td>
            <td>{{table['Blocked time&Date']}}</td>
            <td>
              <ol class="{{'ol'+key}}" ng-init="getAccessories(table['accessories'] , 'ol'+key)">

              </ol>
            </td>
            <td><img src="<?php echo $target_dir ?>{{table.device_image ? table.device_image: 'inventory-management-blog.png'}}" width=50 height=50 alt='{{table.device_image}}'> </td>
          </tr>
        </tbody>
      </table>
    </div>

  </div>

  <script src='../assests/js/inventory.js'></script>
  <script src='../assests/js/bootstrap.bundle.min.js'></script>
</body>

</html>