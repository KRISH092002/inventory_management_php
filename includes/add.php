<?php
//connection to database
require_once('connection.php');

if (isset($_POST['id']) && isset($_POST['name']) && isset($_POST['option']) && $_POST['option'] != 'select') {
  //check the table if the selected row is exist or not
  $check = "SELECT * FROM inventory_list  WHERE `S.NO`='{$_POST['id']}'";
  $check_result = mysqli_query($conn, $check);
  if (mysqli_num_rows($check_result) > 0) {
    while ($row = mysqli_fetch_assoc($check_result)) {
      if (!isset($row['Blocked by']) && $_POST['option'] !== 'Unblocked') {
        //update name and status
        $sql1 = "UPDATE inventory_list SET `Blocked by`='{$_POST['name']}',`Status`='{$_POST['option']}' WHERE `S.NO`='{$_POST['id']}'";

        if (mysqli_query($conn, $sql1)) {
          //if status is blocked
          if ($_POST['option'] == 'Blocked' && $_POST['option'] != 'select') {
            date_default_timezone_set("Asia/Kolkata");
            $date_time1 = date("jS F Y  h:i:sA");
            $update_block = "UPDATE inventory_list SET `Blocked time&Date`='$date_time1'  WHERE `S.NO`='{$_POST['id']}'";
            if (!mysqli_query($conn, $update_block)) {
              echo "Error in time update";
              http_response_code(200);
            } else {
              $insert_data = "INSERT INTO block_release_list (Device_name, Serial_number, Blocked_by,`Block_time&date`)
              VALUES ('{$row['Device_Name']}', '{$row['Serial_Number']}', '{$_POST['name']}','$date_time1')";

              if (!mysqli_query($conn, $insert_data)) {

                echo "Error: " . $insert_data . "<br>" . mysqli_error($conn);
                http_response_code(500);
              }
              else{
                echo "Blocked Successfuly";
                http_response_code(200);
              }
            }
          }
        } else {
          echo "Error updating record: " . mysqli_error($conn);
          http_response_code(500);
        }
      }else if(!isset($row['Blocked by']) && $_POST['option'] == 'Unblocked'){
          echo "You can't unblock without blocking";
          http_response_code(200);
      }
      
      
      
      
      
      else {
        if ($row['Blocked by'] == $_POST['name']) {
          // check the name matches to exist name
          $sql2 = "UPDATE inventory_list SET `Status`='{$_POST['option']}' WHERE `S.NO`='{$_POST['id']}'";

          if (mysqli_query($conn, $sql2)) {
            if ($_POST['option'] == 'Blocked' && $_POST['option'] != 'select' && $row['Status'] == "Unblocked") {
              //update if status is blocked
              date_default_timezone_set("Asia/Kolkata");
              $date_time3 = date("jS F Y  h:i:sA");
              $update_block = "UPDATE inventory_list SET `Blocked time&Date`='$date_time3',`Release time&Date`=' '   WHERE `S.NO`='{$_POST['id']}'";
              if (!mysqli_query($conn, $update_block)) {
                echo "Error in time update";
                http_response_code(200);
              } else {
                $insert_data2 = "INSERT INTO block_release_list (Device_name, Serial_number, Blocked_by,`Block_time&date`)
                VALUES ('{$row['Device_Name']}', '{$row['Serial_Number']}', '{$_POST['name']}','$date_time3')";

                if (!mysqli_query($conn, $insert_data2)) {

                  echo "Error: " . $insert_data2 . "<br>" . mysqli_error($conn);
                  http_response_code(500);
                }
                else{
                  echo "Blocked Successfully";
                  http_response_code(200);
                }
              }
            }
            if ($_POST['option'] == 'Unblocked' && $_POST['option'] != 'select' && $row['Status'] == "Blocked") {
              //update if status is unblocked
              date_default_timezone_set("Asia/Kolkata");
              $date_time4 = date("jS F Y  h:i:sA");
              $update_release = "UPDATE inventory_list SET `Blocked by`='',`Blocked time&Date`='',
              `Release time&Date`=''  WHERE `S.NO`='{$_POST['id']}'";
              if (!mysqli_query($conn, $update_release)) {
                echo "Error in time update";
                http_response_code(200);
              }
             else {
                $check_col2 = "SELECT * FROM block_release_list WHERE Blocked_by='{$_POST['name']}' AND Device_name='{$row['Device_Name']}' AND Serial_number='{$row['Serial_Number']}'";
                $check_col_result2 = mysqli_query($conn, $check_col2);
  
                if (mysqli_num_rows($check_col_result2) > 0) {
                  // output data of each row
                  while ($row_details2 = mysqli_fetch_assoc($check_col_result2)) {
                    if (!isset($row_details2['Release_time&date'])) {
                      $update_unblock2 = "UPDATE block_release_list SET `Release_time&date`='$date_time4' WHERE id='{$row_details2['id']}'";
  
                      if (!mysqli_query($conn, $update_unblock2)) {
  
                        echo "Error updating record: " . mysqli_error($conn);
                        http_response_code(500);
                      }
                      else{
                        echo "Unblocked Successfuly";
                        http_response_code(200);
                      }
                    }
                  }
                } else {
                  echo "Error 0 results";
                  http_response_code(200);
                }
              }
            }
          } else {
            echo "Error updating record: " . mysqli_error($conn);
            http_response_code(500);
          }
        } else if ($row['Blocked by'] != $_POST['name'] && $row['Status'] == 'Unblocked' && $_POST['option'] == 'Blocked' ) {
          date_default_timezone_set("Asia/Kolkata");
          $date_time5 = date("jS F Y  h:i:sA");
          $new_block = "UPDATE inventory_list SET `Blocked by`='{$_POST['name']}',Status='Blocked',`Blocked time&Date`='$date_time5',`Release time&Date`=' '   WHERE `S.NO`='{$_POST['id']}'";
          if (!mysqli_query($conn, $new_block)) {
            echo "Error in time update";
            http_response_code(200);
          } else {
            $insert_data3 = "INSERT INTO block_release_list (Device_name, Serial_number, Blocked_by,`Block_time&date`)
            VALUES ('{$row['Device_Name']}', '{$row['Serial_Number']}', '{$_POST['name']}','$date_time5')";

            if (!mysqli_query($conn, $insert_data3)) {

              echo "Error: " . $insert_data3 . "<br>" . mysqli_error($conn);
              http_response_code(500);
            }
            else{
              echo "Blocked Successfully";
              http_response_code(200);
            }
          }
        }
         else {
          $data = new stdClass;
          $data->report = 'wrongblocked';
          $data->msg=" {$row['Status']}  by someone else..";
          echo json_encode($data);
          http_response_code(200);
        }
      }
    }
  }
}



