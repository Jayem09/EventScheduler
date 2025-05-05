<?php
$xmlFile = 'C:/xampp_new/htdocs/IPT_UbEvent/dashboard/data/event.xml';

// Ensure required fields are provided
if (
    isset($_POST['date'], $_POST['startTime'], $_POST['endTime'], $_POST['eventName'], $_POST['location'], $_POST['department'], 
          $_POST['eventType'], $_POST['targetAudience'], $_POST['registrationLink'], $_POST['contactInformation'], $_POST['agenda'])
) {
    // Load or create the XML file
    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
    } else {
        $xml = new SimpleXMLElement('<events></events>');
    }

    // Format time string: e.g., "8:30 AM - 10:30 AM"
    function formatTime($time) {
        return date("g:i A", strtotime($time));
    }

    $startTimeFormatted = formatTime($_POST['startTime']);
    $endTimeFormatted = formatTime($_POST['endTime']);
    $newTimeRange = $startTimeFormatted . ' - ' . $endTimeFormatted;

    $newDate = $_POST['date'];
    $newLocation = trim($_POST['location']);

    // Overlap Check
    foreach ($xml->event as $event) {
        $existingDate = (string)$event->date;
        $existingLocation = trim((string)$event->location);
        $existingTime = (string)$event->time;

        // Extract start and end time from existing record
        if (preg_match('/^(.+)\s*-\s*(.+)$/', $existingTime, $matches)) {
            $existingStart = strtotime($matches[1]);
            $existingEnd = strtotime($matches[2]);
        } else {
            continue;
        }

        $newStart = strtotime($startTimeFormatted);
        $newEnd = strtotime($endTimeFormatted);

        $isSameDate = $newDate === $existingDate;
        $isSameLocation = strcasecmp($newLocation, $existingLocation) === 0;
        $isTimeOverlap = ($newStart < $existingEnd && $newEnd > $existingStart);

        if ($isSameDate && $isSameLocation && $isTimeOverlap) {
            echo "Error: This event overlaps with an existing event on the same date, time, and location.";
            exit();
        }
    }

// Determine auto-increment ID
if (!isset($xml->event) || count($xml->event) === 0) {
    $eventId = 1;
} else {
    $existingIds = [];
    foreach ($xml->event as $event) {
        $existingIds[] = (int)$event->id;
    }
    $eventId = max($existingIds) + 1;
}

    $newEvent = $xml->addChild('event');
    $newEvent->addChild('id', $eventId);
    $newEvent->addChild('date', $newDate);        
    $newEvent->addChild('time', $newTimeRange);
    $newEvent->addChild('eventName', htmlspecialchars($_POST['eventName']));
    $newEvent->addChild('location', htmlspecialchars($newLocation));
    $newEvent->addChild('department', htmlspecialchars($_POST['department']));
    $newEvent->addChild('eventType', htmlspecialchars($_POST['eventType']));
    $newEvent->addChild('targetAudience', htmlspecialchars($_POST['targetAudience']));
    $newEvent->addChild('registrationLink', htmlspecialchars($_POST['registrationLink']));
    $newEvent->addChild('contactInformation', htmlspecialchars($_POST['contactInformation']));
    $newEvent->addChild('agenda', htmlspecialchars($_POST['agenda']));
    
    if ($xml->asXML($xmlFile)) {
        echo "Success";
    } else {
        echo "Error saving to XML. Please check file permissions.";
    }
    

} else {
    echo "Error: Missing required form fields.";
}
?>
