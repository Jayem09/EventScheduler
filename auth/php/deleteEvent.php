<?php
$servername = "localhost";
$username = "root";
$password = "secretsecret4";
$dbname = "ub_lipa_event_scheduler";

// Create connection to MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the event ID to delete
$eventId = $_GET['id'];

// SQL query to delete the event from the database
$sql = "DELETE FROM events WHERE id = '$eventId'";

// Execute the query and check for success
if ($conn->query($sql) === TRUE) {
    // Load the XML file
    $xmlFile = 'C:/xampp_new/htdocs/IPT_UbEvent/dashboard/data/event.xml';

    if (file_exists($xmlFile)) {
        // Load the existing XML file
        $xml = simplexml_load_file($xmlFile);

        // Find and remove the event in the XML file by matching the ID
        foreach ($xml->event as $index => $event) {
            if ((string)$event->id === $eventId) {
                unset($xml->event[$index]);
                break; // Stop once the event is found and removed
            }
        }

        // Save the updated XML file
        if ($xml->asXML($xmlFile)) {
            echo json_encode(["message" => "Event deleted successfully from both DB and XML."]);
        } else {
            echo json_encode(["error" => "Error saving XML file after deleting the event."]);
        }
    } else {
        echo json_encode(["error" => "XML file not found."]);
    }
} else {
    echo json_encode(["error" => "Error deleting event from database: " . $conn->error]);
}

$conn->close();
?>
