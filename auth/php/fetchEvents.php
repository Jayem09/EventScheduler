<?php
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

$sql = "SELECT * FROM events";
$result = $conn->query($sql);

$events = array();

if ($result->num_rows > 0) {
    // Fetch all events
    while($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
} else {
    echo "0 results";
}

$conn->close();

// Return events as JSON
echo json_encode($events);
?>
