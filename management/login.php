<?php

session_start(); 

include("connection.php");

$username = $password = "";
$login_error = false;

if ($_POST) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $connection->query($query);
    if ($result->num_rows == 1) {
       
        $_SESSION['admin_username'] = $username; // Set the session variable
        header("Location: dashboard.php");
        exit();
    } else {
        $login_error = "Login failed. Please check your username and password.";
    }
}

$connection->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="\StudentManagement\students\assets\admin_login.css">
</head>
<body>
    <h1>Admin Login</h1>
    <form method="post">
        <?php
        if($login_error){
        ?>
            <div class="error"><?php
            echo $login_error
            ?></div>
        <?php 
        }
        ?>
        <label for="useraname">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Login">
    </form>

</body>
</html>



