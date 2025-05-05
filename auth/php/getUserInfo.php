<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['username'], $_SESSION['first_name'], $_SESSION['last_name'])) {
    echo json_encode([
        "username" => $_SESSION['username'],
        "first_name" => $_SESSION['first_name'],
        "last_name" => $_SESSION['last_name']
    ]);
} else {
    echo json_encode(["error" => "Not logged in or missing session data"]);
}
?>
