<?php
$servername = "localhost";
$username = "root";
$password = "secretsecret4";
$dbname = "ub_lipa_event_scheduler";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$eventId = $_GET['id'];

// SQL query to delete the event from the database
$sql = "DELETE FROM events WHERE id = '$eventId'";

// Execute the query and check for success
if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "Event deleted successfully"]);
} else {
    echo json_encode(["error" => "Error deleting event: " . $conn->error]);
}

$conn->close();
?>
