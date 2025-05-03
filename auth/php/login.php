<?php
// Start session
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "secretsecret4";
$dbname = "ub_lipa_event_scheduler";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $sql = "SELECT user_id, username, password, first_name, last_name, user_type FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Password is correct, start a new session
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['last_name'] = $row['last_name'];
            $_SESSION['user_type'] = $row['user_type'];

            // Update last login timestamp
            $update_sql = "UPDATE users SET last_login = NOW() WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $row['user_id']);
            $update_stmt->execute();
            $update_stmt->close();

            // Redirect to dashboard
            header("Location: http://127.0.0.1/IPT_UbEvent/dashboard/Home/home.html");
            exit();
        } else {
            // Invalid password
            header("Location: ../auth/login.html?error=invalid_password");
            exit();
        }
    } else {
        // Username not found
        header("Location: ../auth/login.html?error=username_not_found");
        exit();
    }
    $stmt->close();
}
$conn->close();
?>
