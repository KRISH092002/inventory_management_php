<?php
//connection to database
require_once('connection.php');

$data_query = "SELECT * FROM inventory_list";
$data_result = mysqli_query($conn, $data_query);

$arr = [];
if (mysqli_num_rows($data_result) > 0) {

    while($row = mysqli_fetch_assoc($data_result)){
        array_push($arr , $row);
    }


    echo json_encode($arr);
    http_response_code(200);
}

?>