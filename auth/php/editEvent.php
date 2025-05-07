<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$xmlFile = 'C:/xampp_new/htdocs/IPT_UbEvent/dashboard/data/event.xml';

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Validate input
$requiredFields = ['id', 'date', 'time', 'eventName', 'location', 'department', 'eventType', 'targetAudience', 'registrationLink', 'contactInformation', 'agenda'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field])) {
        echo json_encode(["error" => "Missing required field: $field"]);
        exit();
    }
}

// Check file existence
if (!file_exists($xmlFile)) {
    echo json_encode(["error" => "XML file not found."]);
    exit();
}

// Load XML
$xml = simplexml_load_file($xmlFile);
$eventFound = false;

// Find and update the event
foreach ($xml->event as $event) {
    if ((string)$event->id === $data['id']) {
        $event->date = $data['date'];
        $event->time = $data['time'];
        $event->eventName = $data['eventName'];
        $event->location = $data['location'];
        $event->department = $data['department'];
        $event->eventType = $data['eventType'];
        $event->targetAudience = $data['targetAudience'];
        $event->registrationLink = $data['registrationLink'];
        $event->contactInformation = $data['contactInformation'];
        $event->agenda = $data['agenda'];
        $eventFound = true;
        break;
    }
}

// Save or respond with error
if ($eventFound) {
    if ($xml->asXML($xmlFile)) {
        echo json_encode(["message" => "✅ Event updated successfully"]);
    } else {
        echo json_encode(["error" => "❌ Failed to save changes to XML."]);
    }
} else {
    echo json_encode(["error" => "❌ Event with ID {$data['id']} not found."]);
}
?>
