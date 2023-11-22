<?php
session_start(); 
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

include("connection.php");

if (isset($_GET["id"])) {
    $course_id = $_GET["id"];

    // Fetch course name
    $query_course = "select course_name from courses where id = $course_id";
    $result_course = $connection->query($query_course);

    if ($result_course->num_rows == 1) {
        $course_data = $result_course->fetch_assoc();
        $course_name = $course_data["course_name"];

        // Fetch enrolled students
        $query_students = "select id, first_name, last_name, email from students
                           where course_id = $course_id";
        $result_students = $connection->query($query_students);
    } else {
        echo "Course not found.";
        exit;
    }
} else {
    echo "Course ID not provided.";
    exit;
}

$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="\StudentManagement\students\assets\dashboard.css">
</head>
<body>
    <h1>Students Enrolled in "<?php echo $course_name; ?>"</h1>
    <a class ="link" href="logout.php">Logout</a>
    <a class="link" href="dashboard.php">Back to Dashboard</a>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Edit</th>
                    <th>Status</th>

                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_students->num_rows > 0) {
                    while ($row = $result_students->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["first_name"] . "</td>";
                        echo "<td>" . $row["last_name"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td><a href='edit_student.php?id=" . $row["id"] . "'>Edit</a></td>";
                        echo "<td>Active</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "No students are enrolled in this course.";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
