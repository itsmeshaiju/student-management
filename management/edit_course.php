<?php
session_start(); 
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

include("connection.php");

$id = $course_name = $course_duration = $course_status = "";

// Get the course ID from the URL
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $query = "SELECT * FROM courses WHERE id = $id";
    $result = $connection->query($query);

    if ($result->num_rows == 1) {
        // Course data found, retrieve and pre-fill the form fields
        $course_data = $result->fetch_assoc();
        $course_name = $course_data["course_name"];
        $course_duration = $course_data["course_duration"];
        $course_status = $course_data["course_status"];
    } else {
        echo "Course not found.";
    }
}

// Get the list of all course names for the dropdown
// $course_names_query = "SELECT id, course_name FROM courses";
// $course_names_result = $connection->query($course_names_query);

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the updated course data from the form
    $id = $_POST["id"];
    // echo $id;
    // exit; 
    $course_name = $_POST["course_name"];
    $course_duration = $_POST["course_duration"];
    $course_status = $_POST["course_status"];
  //echo "course_status=$course_status";exit;
    $query = "UPDATE courses SET course_name = ?, course_duration = ?, course_status = ? WHERE id = ?";

    $stmt = $connection->prepare($query);

    if ($stmt === false) {
        die("Error preparing update statement: " . $connection->error);
    }

    $stmt->bind_param("ssii", $course_name, $course_duration, $course_status, $id);
    $result = $stmt->execute();

    if ($result === false) {
        echo "Update failed: " . $stmt->error;
    } else {
        echo "Update successful!";
    }
    $stmt->close();
}
$connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Course</title>
    <style>
         body {
            font-family: Arial, sans-serif;
            background-size: cover;
            background-position: center;
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
            color: white;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        select {
            appearance: none;
        }

        input[type="submit"] {
            background-color: #000;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #333;
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
</head>
<body>
    <h1>Edit Course</h1>
    <div class="links">
        <a class="link" href="dashboard.php">Dashboard</a>
        <a class="link" href="logout.php">Logout</a>
    </div>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
    <label for="course_name">Course Name:</label>
    <input type="text" id="course_name" name="course_name" value="<?php echo $course_name; ?>" required><br>
        </select>
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
        </select><br>
        <input type="submit" value="Update Course">
    </form>
</body>
</html>
