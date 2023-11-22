<?php
session_start(); 
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

include("connection.php");

$id = $first_name = $last_name = $email = $date_of_birth = $age = $mobile_number = $state = $country = $course_id = "";

// Get the student ID from the URL
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = "select * from students where id = $id";
    $result = $connection->query($query);

    if ($result->num_rows == 1) {
        // Student data found, retrieve and store the student information
        $student_data = $result->fetch_assoc();
        $first_name = $student_data["first_name"];
        $last_name = $student_data["last_name"];
        $email = $student_data["email"];
        $date_of_birth = $student_data["date_of_birth"];
        $age = $student_data["age"];
        $mobile_number = $student_data["mobile_number"];
        $state = $student_data["state"];
        $country = $student_data["country"];
        $course_id = $student_data["course_id"];
    } else {
        echo "Student not found.";
    }
}

// Fetch the list of courses
$courses = array();
$query_courses = "select id, course_name from courses";
$result_courses = $connection->query($query_courses);
if ($result_courses->num_rows > 0) {
    while ($row = $result_courses->fetch_assoc()) {
        $courses[] = $row;
    }
}

// Check if the form was submitted for deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_delete"])) {
    // Get the student ID from the form
    $id = $_POST["id"];
    $query = "delete from students where id = ?";
    $stmt = $connection->prepare($query);

    if ($stmt === false) {
        die("Error preparing delete statement: " . $connection->error);
    }

    $stmt->bind_param("i", $id);
    $result = $stmt->execute();

    if ($result === false) {
        echo "Delete failed: " . $stmt->error;
    } else {
        echo "Student deleted successfully!";
    }

    $stmt->close();
}

$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delete Student</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
        background-color: #19698e;
    }

    h1 {
        text-align: center;
        background-color: #000;
        color: #fff;
        padding: 20px;
    }

    form {
        max-width: 400px;
        margin: 0 auto;
        background-color: #675f62;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        color:white;
    }

    label {
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    input[type="submit"] {
        background-color: #ff0000;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #cc0000;
    }

    .links {
        margin: 10px 0;
    }

    .link {
        text-decoration: none;
        background-color: #6d7176;
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        margin-right: 10px;
        float: right;
        margin-right: 10px;
    }

    .link:hover {
        background-color: #bdbebf;
    }
</style>
<body>
    <h1>Delete Student</h1>
    <div class="links">
        <a class="link" href="dashboard.php">Dashboard</a>
        <a class="link" href="logout.php">Logout</a>
    </div>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo $first_name; ?>" readonly><br>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo $last_name; ?>" readonly><br>
        <label for="email">Email:</label>
        <input type="text" id="email" name="email" value="<?php echo $email; ?>" readonly><br>
        <label for="date_of_birth">Date of Birth:</label>
        <input type="text" id="date_of_birth" name="date_of_birth" value="<?php echo $date_of_birth; ?>" readonly><br>
        <label for="age">Age:</label>
        <input type="text" id="age" name="age" value="<?php echo $age; ?>" readonly><br>
        <label for="mobile_number">Mobile Number:</label>
        <input type="text" id="mobile_number" name="mobile_number" value="<?php echo $mobile_number; ?>" readonly><br>
        <label for="state">State:</label>
        <input type="text" id="state" name="state" value="<?php echo $state; ?>" readonly><br>
        <label for="country">Country:</label>
        <input type="text" id="country" name="country" value="<?php echo $country; ?>" readonly><br>
        <label for="course_id">Course:</label>
        <select id="course_id" name="course_id" >
            <?php
            foreach ($courses as $course) {
                echo '<option value="' . $course['id'] . '"';
                if ($course['id'] == $course_id) {
                    echo ' selected';
                }
                echo '>' . $course['course_name'] . '</option>';
            }
            ?>
        </select><br>
        <input type="submit" name="confirm_delete" value="Delete Student" onclick="return confirm('Are you sure you want to delete this student?');">
    </form>
</body>
</html>
