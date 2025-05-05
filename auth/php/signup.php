<?php
session_start();
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "secretsecret4", "ub_lipa_event_scheduler");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $username = trim($_POST['username']);
    $rawPassword = trim($_POST['password']);

    // Password validation
    if (
        strlen($rawPassword) < 8 ||
        !preg_match('/[A-Z]/', $rawPassword) ||
        !preg_match('/[a-z]/', $rawPassword) ||
        !preg_match('/[0-9]/', $rawPassword) ||
        !preg_match('/[\W_]/', $rawPassword)
    ) {
        echo json_encode([
            "success" => false,
            "message" => "Password must be at least 8 characters long and include uppercase, lowercase, number, and special character."
        ]);
        exit();
    }

    // Hash password after validation
    $password = password_hash($rawPassword, PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Username already taken."]);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password, first_name, last_name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $password, $fname, $lname);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Signup failed. Please try again."]);
        }
        $stmt->close();
    }
    $check->close();
}
$conn->close();
?>
