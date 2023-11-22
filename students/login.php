<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_management";

$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$email = $password = "";
$login_error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare the SQL statement with placeholders
    $query = "SELECT * FROM students WHERE email = ?";

    if ($stmt = $connection->prepare($query)) {
        // Bind the parameters to the placeholders
        $stmt->bind_param("s", $email);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Fetch the row
            $row = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $row['password'])) {
                $_SESSION['student_username'] = $email; // Set the session variable
                header("Location: \StudentManagement\management\dashboard.php");
                exit();
            } else {
                $login_error = "Login failed. Incorrect password.";
            }
        } else {
            $login_error = "Login failed. User not found.";
        }

        $stmt->close();
    } else {
        $login_error = "An error occurred while preparing the statement.";
    }
}

$connection->close();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login page</title>
    <link rel="stylesheet" href="\phpnew\uploads\cssfiles\login.css">
</head>
<body>

  <div class="wrapper">
    <form method="post">
       <h1>Login here</h1>
       <?php
        if($login_error){
        ?>
            <div class="error"><?php
            echo $login_error
            ?></div>
        <?php 
        }
        ?>
       <div class="input-box">
          <label for="email">Email:</label>
        <input type="text" id="email" name="email" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>


          <div class="remember-forgot">
              <label>
                  <input type="checkbox">Remember me?
              </label>
              <a href="#">Forgot Password?</a>
          </div>

          <button type="submit">Login</button>

          <div class="register-link">
              <a href="registration.php">Don't have an account? Register here.</a>
          </div>
       </div>
    </form>
  </div>

</body>
</html>
