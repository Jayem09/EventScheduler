<?php
// Start session if needed
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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hash the password

    // Check if username already exists
    $check_sql = "SELECT user_id FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Username already exists
        header("Location: ../auth/signup.html?error=username_taken");
        exit();
    } else {
        // Insert new user
        $sql = "INSERT INTO users (username, password, first_name, last_name) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $password, $fname, $lname);

        if ($stmt->execute()) {
            // Successful signup
            header("Location: ../auth/login.html?signup=success");
            exit();
        } else {
            // Insert failed
            header("Location: ../auth/signup.html?error=insert_failed");
            exit();
        }
        $stmt->close();
    }
    $check_stmt->close();
}
$conn->close();
?>
