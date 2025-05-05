<?php
$xmlFile = 'C:/xampp_new/htdocs/IPT_UbEvent/dashboard/data/event.xml';

// Read the raw POST data (JSON)
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['id'])) {
    echo json_encode(["error" => "No data received or invalid format"]);
    exit();
}

if (!file_exists($xmlFile)) {
    echo json_encode(["error" => "XML file not found."]);
    exit();
}

$xml = simplexml_load_file($xmlFile);
$eventFound = false;

foreach ($xml->event as $event) {
    if ((string)$event->id === $data['id']) {
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

if ($eventFound) {
    if ($xml->asXML($xmlFile)) {
        echo json_encode(["message" => "Event updated successfully"]);
    } else {
        echo json_encode(["error" => "Failed to save XML."]);
    }
} else {
    echo json_encode(["error" => "Event not found."]);
}
?>
