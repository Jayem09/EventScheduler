<?php
$servername = "localhost";
$username = "root";
$password = "secretsecret4"; 
$dbname = "ub_lipa_event_scheduler"; 

// Create connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load events from event.xml
$xmlFile = 'C:/xampp_new/htdocs/IPT_UbEvent/dashboard/data/event.xml';  
$xml = simplexml_load_file($xmlFile);

// Flag to track whether new events are inserted
$newDataImported = false;

foreach ($xml->event as $event) {
    $date = $event->date;
    $time = $event->time;
    $eventName = $event->eventName;
    $location = $event->location;
    $department = $event->department;
    $eventType = $event->eventType;
    $targetAudience = $event->targetAudience;
    $registrationLink = $event->registrationLink;
    $contactInformation = $event->contactInformation;
    $agenda = $event->agenda;

    // Check if the event already exists in the database 
    $checkSql = "SELECT * FROM events WHERE event_name = '$eventName' AND date = '$date'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows == 0) {
        // If the event doesn't exist, insert it into the database
        $sql = "INSERT INTO events (date, time, event_name, location, department, event_type, target_audience, registration_link, contact_information, agenda)
                VALUES ('$date', '$time', '$eventName', '$location', '$department', '$eventType', '$targetAudience', '$registrationLink', '$contactInformation', '$agenda')";

        if ($conn->query($sql)) {
            // Mark the flag as true if a new event was successfully inserted
            $newDataImported = true;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

if ($newDataImported) {
    // Display import success message before redirection
    echo "Import successful! Redirecting...";
} else {
    // If no new data was imported, display a message
    echo "All data is already imported.";
}

// Close the database connection
$conn->close();

// Redirect after a short delay to the browseEvent page
header("refresh:3;url=http://127.0.0.1/IPT_UbEvent/dashboard/browseEvent/BrowseEvent.html");
exit();
?>
