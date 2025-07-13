<?php
require_once('./includes/connection.php');
?>
<?php

if (isset($_POST['name']) && isset($_POST['doj']) && isset($_POST['pass'])) {

    $valid_query = "SELECT * FROM users WHERE name='{$_POST['name']}' AND doj ='{$_POST['doj']}' AND password='{$_POST['pass']}'";
    $valid_result = mysqli_query($conn, $valid_query);

    if (mysqli_num_rows($valid_result) > 0) {
        $_SESSION['name'] = $_POST['name'];
        $_SESSION['doj'] = $_POST['doj'];
        $_SESSION['pass'] = $_POST['pass'];
        header("location:./includes/submit.php");
    } else {
        $err = true;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory tool</title>
    <link href="./assests/css/bootstrap.min.css" rel="stylesheet">
    <link href="./assests/css/style.css" rel="stylesheet">
</head>

<body class="bg">
    <div class="container">
        <!-- <img src="./assests/img/icon.jpeg" class="w-100 border border-1  height" alt="Image at top"> -->
        <p class="h1 text-center mt-4 text-dark">QA Device Testing Team</p>
        <div class="col-md-6 offset-md-3 border border-1 rounded-4 border-primary shadow-lg p-5 back-filter">
            <?php if (isset($err)) {
                echo "<p class='alert alert-danger'>Incorrect Credentials</p>";
            } ?>
            <form method="post">
                <div class="row mb-3">
                    <div class="col-3 fw-bold">

                        <label for="name">Name: </label>
                    </div>
                    <div class="col">
                        <select id="name" name="name" class="form-control" required>
                            <option value=" ">choose Name</option>
                            <?php
                            $name_query = "SELECT name FROM users ";
                            $name_result = mysqli_query($conn, $name_query);

                            if (mysqli_num_rows($name_result) > 0) {
                                while ($show1 = mysqli_fetch_assoc($name_result)) {
                                    echo "<option value='{$show1['name']}'>{$show1['name']}</option>";
                                }
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-3 fw-bold">

                        <label for="doj">DOJ: </label>
                    </div>
                    <div class="col"><select id="doj" name="doj" class="form-control" required>
                            <option value="">choose DOJ</option>
                            <?php
                            $sso_query = "SELECT doj FROM users ";
                            $sso_result = mysqli_query($conn, $sso_query);

                            if (mysqli_num_rows($sso_result) > 0) {
                                while ($show2 = mysqli_fetch_assoc($sso_result)) {
                                    echo "<option value='{$show2['doj']}'>{$show2['doj']}</option>";
                                }
                            } ?>

                        </select></div>
                </div>
                <div class="row mb-3">
                    <div class="col-3 fw-bold">

                        <label for="pass">Password: </label>
                    </div>
                    <div class="col">
                        <input id="pass" type='password' name="pass" class="form-control" required>

                    </div>
                </div>

                <div class="d-flex justify-content-center">

                    <button type="submit" class="btn btn-outline-primary">Submit</button>

                </div>

            </form>

        </div>
    </div>
</body>

</html>