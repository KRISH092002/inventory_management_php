<?php
//connection to database
require_once('connection.php');
$access_query = "SELECT * FROM users WHERE name='{$_SESSION['name']}' AND doj ='{$_SESSION['doj']}' AND password='{$_SESSION['pass']}'";
$access_result = mysqli_query($conn, $access_query);

if (mysqli_num_rows($access_result) > 0) {
    while ($check = mysqli_fetch_assoc($access_result)) {

        echo json_encode($check);
        http_response_code(200);
    }
}


?>