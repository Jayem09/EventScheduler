<?php
$servername = "localhost";
$username = "root";
$password = "secretsecret4";
$dbname = "ub_lipa_event_scheduler";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the raw POST data (JSON)
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is received and is in the correct format
if (!$data || !isset($data['id'])) {
    echo json_encode(["error" => "No data received or invalid format"]);
    exit();
}

// Debugging: Log the received data for verification
file_put_contents('php://stderr', print_r($data, true));

// Sanitize the data
$eventId = $conn->real_escape_string($data['id']);
$eventName = $conn->real_escape_string($data['eventName']);
$location = $conn->real_escape_string($data['location']);
$department = $conn->real_escape_string($data['department']);
$eventType = $conn->real_escape_string($data['eventType']);
$targetAudience = $conn->real_escape_string($data['targetAudience']);
$registrationLink = $conn->real_escape_string($data['registrationLink']);
$contactInformation = $conn->real_escape_string($data['contactInformation']);
$agenda = $conn->real_escape_string($data['agenda']);

// Update query
$sql = "UPDATE events SET 
    event_name = '$eventName', 
    location = '$location', 
    department = '$department', 
    event_type = '$eventType', 
    target_audience = '$targetAudience', 
    registration_link = '$registrationLink', 
    contact_information = '$contactInformation', 
    agenda = '$agenda'
    WHERE id = '$eventId'";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo json_encode(["message" => "Event updated successfully"]);
} else {
    echo json_encode(["error" => "Error updating event: " . $conn->error]);
}

$conn->close();
?>
