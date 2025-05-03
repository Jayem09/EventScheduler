<?php
// Database Connection
$servername = "localhost";
$username = "root";
$password = "secretsecret4";
$dbname = "ub_lipa_event_scheduler";

// Create connection to MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Path to the XML file
$xmlFile = 'C:/xampp_new/htdocs/IPT_UbEvent/dashboard/data/event.xml';  

// Check if the XML file exists
if (file_exists($xmlFile)) {
    // Load the existing XML file
    $xml = simplexml_load_file($xmlFile);
} else {
    // Create new XML structure if the file doesn't exist
    $xml = new SimpleXMLElement('<events></events>');
}

// Prepare the SQL query
$stmt = $conn->prepare("INSERT INTO events (date, time, event_name, location, department, event_type, target_audience, registration_link, contact_information, agenda) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind parameters to the SQL query
$stmt->bind_param("ssssssssss", $_POST['date'], $_POST['time'], $_POST['eventName'], $_POST['location'], $_POST['department'], 
                  $_POST['eventType'], $_POST['targetAudience'], $_POST['registrationLink'], $_POST['contactInformation'], $_POST['agenda']);

// Execute the query
if ($stmt->execute()) {
    // Get the last inserted id from MySQL (auto-incremented id)
    $eventId = $conn->insert_id;

    // Add the new event to XML with the same ID as in the database
    $newEvent = $xml->addChild('event');
    $newEvent->addChild('id', $eventId);  // Use the MySQL auto-incremented ID for XML
    $newEvent->addChild('date', $_POST['date']);
    $newEvent->addChild('time', $_POST['time']);
    $newEvent->addChild('eventName', $_POST['eventName']);
    $newEvent->addChild('location', $_POST['location']);
    $newEvent->addChild('department', $_POST['department']);
    $newEvent->addChild('eventType', $_POST['eventType']);
    $newEvent->addChild('targetAudience', $_POST['targetAudience']);
    $newEvent->addChild('registrationLink', $_POST['registrationLink']);
    $newEvent->addChild('contactInformation', $_POST['contactInformation']);
    $newEvent->addChild('agenda', $_POST['agenda']);

    // Save the updated XML
    if (!$xml->asXML($xmlFile)) {
        die("Error saving XML file.");
    }

    // Redirect to browse event page after successfully inserting into DB and XML
    header("Location: http://127.0.0.1/IPT_UbEvent/dashboard/browseEvent/browseEvent.html");
    exit();
} else {
    // Handle database insert error
    echo "Error inserting event into the database: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
