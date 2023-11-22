<?php
session_start(); 
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

include("connection.php");

$id = $course_name = $course_duration = $course_status = "";
$active_students = 0; // Initialize active students count

// Get the course ID from the URL
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $student_check_query = "select count(*) as active_students from students where course_id = $id";
    $student_check_result = $connection->query($student_check_query);
    if ($student_check_result === false) {
        die("Query error: " . $connection->error);
    }
    if ($student_check_result->num_rows == 1) {
        $active_students_data = $student_check_result->fetch_assoc();
        $active_students = $active_students_data["active_students"];
    }

    // Fetch course data based on course ID
    $query = "select * from courses where id = $id";
    $result = $connection->query($query);

    if ($result->num_rows == 1) {
        // Course data found, retrieve and store the course information
        $course_data = $result->fetch_assoc();
        $course_name = $course_data["course_name"];
        $course_status = $course_data["course_status"];
        $course_duration = $course_data["course_duration"];
    } else {
        echo "Course not found.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["confirm_delete"])) {
    if ($active_students > 0) {
        echo "<script>
        alert('There are active students in this course. You cannot delete this course.');
            window.location.href = 'dashboard.php'; 
        </script>";
    } else {
        echo "<script>
            var confirmDelete = confirm('Are you sure you want to delete this course?');
            if (confirmDelete) {
                window.location.href = 'delete_course.php?id=$id';
            }
        </script>";
    }
}

$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delete Course</title>
    <link rel="stylesheet" href="\StudentManagement\students\assets\delete_course.css">
</head>
<body>
    <h1>Delete Course</h1>
    <div class="links">
        <a class="link" href="dashboard.php">Dashboard</a>
        <a class="link" href="logout.php">Logout</a>
    </div>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="course_name">Course Name:</label>
        <input type="text" id="course_name" name="course_name" value="<?php echo $course_name; ?>"><br>
        <label for="course_duration">Course Duration:</label>
        <select id="course_duration" name="course_duration">
            <option value="1 month" <?php if ($course_duration === "1 month") echo "selected"; ?>>1 month</option>
            <option value="2 months" <?php if ($course_duration === "2 months") echo "selected"; ?>>2 months</option>
            <option value="3 months" <?php if ($course_duration === "3 months") echo "selected"; ?>>3 months</option>
            <option value="6 months" <?php if ($course_duration === "6 months") echo "selected"; ?>>6 months</option>
            <option value="1 year" <?php if ($course_duration === "1 year") echo "selected"; ?>>1 year</option>
            <option value="2 years" <?php if ($course_duration === "2 years") echo "selected"; ?>>2 years</option>
            <option value="3 years" <?php if ($course_duration === "3 years") echo "selected"; ?>>3 years</option>
        </select>
        <label for="course_status">Course Status:</label>
        <select id="course_status" name="course_status">
            <option value="1" <?php if ($course_status === "1") echo "selected"; ?>>Enabled</option>
            <option value="0" <?php if ($course_status === "0") echo "selected"; ?>>Disabled</option>
        </select>
        <input type="submit" name="confirm_delete" value="Delete Course">
    </form>
</body>
</html>
