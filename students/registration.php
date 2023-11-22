<?php
session_start(); 
if (!isset($_SESSION['admin_username'])) {
    header("Location: \StudentManagement\management\login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_management";

$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$msg = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $date_of_birth = $_POST['date_of_birth'];
    $age = $_POST['age'];
    $password = $_POST['password'];
    $mobile_number = $_POST['mobile_number'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $course_id = $_POST['course_id']; 

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check_query = "select * from students where email = '$email'";
    $check_result = $connection->query($check_query);

    if ($check_result->num_rows > 0) {
        $msg = "Email already exists in the database.";
    } else {

    // Prepare the SQL statement
$insert_query = $connection->prepare("INSERT INTO students (first_name, last_name, email, 
date_of_birth, age, password, mobile_number, state, country, course_id) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Bind the parameters
$insert_query->bind_param("sssssssssi", $first_name, $last_name, $email, $date_of_birth, $age, $hashed_password, $mobile_number, $state, $country, $course_id);

// Execute the query
$result = $insert_query->execute();

if ($result === true) {
    $msg = "You are registered successfully!";
} else {
    $msg = "Error occurred: " . $connection->error;
}

$insert_query->close();

    }
}

$course_name = "";

$id = 0; 


if (isset($_GET["id"])) {
    $id = $_GET["id"];
}


    // Fetch course names and IDs from the "courses" table
    $course_query = "select id, course_name from courses";
    $course_result = $connection->query($course_query);

    if ($course_result->num_rows > 0) {
        while ($row = $course_result->fetch_assoc()) {
            $course_name .= '<option value="' . $row['id'] . '">' . $row['course_name'] . '</option>';
        }
    }
    

$connection->close();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets\registration.css">
    <title>Registration Page</title>
</head>
<body>
<div class="links">
<a class ="link" href="\StudentManagement\management\logout.php">Logout</a><br>
<a class="link" href="\StudentManagement\management\dashboard.php">Dashboard</a>
</div>
    <div class="container">
        <form action="" method="post">
        <?php
        if($msg){
        ?>
            <div class="error"><?php
            echo $msg;
            ?></div>
        <?php 
        }
        ?>
            <h2>Register</h2>
            
            <label for="first_name">Firstname:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Lastname:</label>
            <input type="last_name" id="last_name" name="last_name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="date_of_birth">Date of birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required>

            <label for="age">Age:</label>
            <input type="text" id="age" name="age" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>


            <label for="mobile_number">Mobile number:</label>
            <input type="text" id="mobile_number" name="mobile_number" required>

            <label for="state">State:</label>
            <input type="text" id="state" name="state" required>

            <label for="country">Country:</label>
            <input type="text" id="country" name="country" required>

            <label for="course_id">Course Name:</label>
<select id="course_id" name="course_id">
    <?php echo $course_name; ?>
</select>

            <button type="submit">Register</button>

        </form>
    </div>

</body>
</html>
