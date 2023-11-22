<?php

session_start(); 
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

include('connection.php');

$msg = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST["course_name"]; 
    $course_duration = $_POST["course_duration"]; 
    $course_status = $_POST["course_status"];

    $check_query = "select * from courses where course_name = '$course_name'";
    $check_result = $connection->query($check_query);

    if ($check_result->num_rows > 0) {
        $msg = "Course already exists in the database.";
    } else {
        $insert_query = "insert into courses (course_name,course_duration,course_status
         ) values ('$course_name', '$course_duration', '$course_status')";

        $result = $connection->query($insert_query);

        if ($result === true) {
            $msg="Course registered successfully!";
        }else{
            $msg="Error occured";
        }
    }
}
$connection->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Registration</title>
    <link rel="stylesheet" href="\StudentManagement\students\assets\course.css"> 
</head>
<body>
    <h1>Course Registration</h1>
    <div class="links">
        <a class="link" href="dashboard.php">Dashboard</a>
        <a class="link" href="logout.php">Logout</a>
    </div>
    <form method="post">
        <?php
        if ($msg) {
        ?>
            <div class="error"><?php echo $msg; ?></div>
        <?php 
        }
        ?>
        <label for="course_name">Course Name:</label>
        <input type="text" id="course_name" name="course_name" required><br>

        <label for="course_duration">Course Duration:</label>
        <select id="course_duration" name="course_duration">
            <option value="1 month">1 month</option>
            <option value="2 months">2 months</option>
            <option value="3 months" selected>3 months</option>
            <option value="6 months">6 months</option>
            <option value="1 year">1 year</option>
            <option value="2 years">2 years</option>
            <option value="3 years">3 years</option>
        </select>

        <label for="course_status">Course Status:</label>
        <select id="course_status" name="course_status">
            <option value="1">Enabled</option>
            <option value="0">Disabled</option>
        </select>

        <input type="submit" value="Register Course">
    </form>
</body>
</html>
