<?php
session_start(); 
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

include("connection.php");

$query_course_info = "select id, course_name, course_duration, course_status from courses";
$result_course_info = $connection->query($query_course_info);
$query_student_info = "select students.id, students.first_name, students.last_name, students.email, 
                       students.course_id,students.date_of_birth, students.state, students.country, courses.course_name
                       from students
                       left join courses on students.course_id = courses.id";
$result_student_info = $connection->query($query_student_info);

if ($result_student_info === false) {
    die("Query error: " . $connection->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="\StudentManagement\students\assets\dashboard.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <a class="link" href="logout.php">Logout</a>
    <a class="link" href="course_registration.php">Add Course</a>
    <a class="link" href="\StudentManagement\students\registration.php">Add Student</a>

    <div class="container">
        <h2>List of Courses</h2>

        <table>
        <thead>
    <tr>
        <th>Course ID</th>
        <th>Course Name</th>
        <th>Course Duration</th>
        <th>Course Status</th>
        <th>Active Students Count</th>
        <th>View</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
</thead>

            <tbody>
                <?php
                if ($result_course_info->num_rows > 0) {
                    while ($row = $result_course_info->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["course_name"] . "</td>";
                        echo "<td>" . $row["course_duration"] . "</td>";
                        echo "<td>" . ($row["course_status"] == 1 ? 'Enabled' : 'Disabled') . "</td>";
                    
                        // Calculate the active students count for the course
                        $course_id = $row["id"];
                        $active_students_query = "select count(*) as active_count from students where course_id = $course_id";
                        $active_students_result = $connection->query($active_students_query);
                        
                        if ($active_students_result && $active_students_result->num_rows > 0) {
                            $active_count = $active_students_result->fetch_assoc()["active_count"];
                            echo "<td>" . $active_count . "</td>";
                            
                            
                            if ($active_count > 0) {
                                echo "<td><a href='view_students.php?id=" . $row["id"] . "'>View</a></td>";
                            } else {
                                echo "<td>No Active Students</td>";
                            }
                        } else {
                            echo "<td>0</td>";
                            echo "<td>No Active Students</td>";
                        }
                    
                        echo "<td><a href='edit_course.php?id=" . $row["id"] . "'>Edit</a></td>";
                        echo "<td><a href='delete_course.php?id=" . $row["id"] . "'>Delete</a></td>";
                        echo "</tr>";
                    }
                    
                } else {
                    echo "<tr><td colspan='6'>No course information found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>List of Students</h2>

        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Course Name</th>
                    <th>Date of Birth</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_student_info->num_rows > 0) {
                    while ($row = $result_student_info->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["first_name"] . "</td>";
                        echo "<td>" . $row["last_name"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["course_name"] . "</td>"; 
                        echo "<td>" . $row["date_of_birth"] . "</td>";
                        echo "<td>" . $row["state"] . "</td>";
                        echo "<td>" . $row["country"] . "</td>";
                        echo "<td><a href='edit_student.php?id=" . $row["id"] . "'>Edit</a></td>";
                        echo "<td><a href='delete_student.php?id=" . $row["id"] . "'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No student information found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
