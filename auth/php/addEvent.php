<?php
$xmlFile = 'C:/xampp_new/htdocs/IPT_UbEvent/dashboard/data/event.xml';

// Ensure required fields are provided
if (
    isset($_POST['date'], $_POST['startTime'], $_POST['endTime'], $_POST['eventName'], $_POST['location'], $_POST['department'], 
          $_POST['eventType'], $_POST['targetAudience'], $_POST['registrationLink'], $_POST['contactInformation'], $_POST['agenda'])
) {
    // Load or create XML file
    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
    } else {
        $xml = new SimpleXMLElement('<events></events>');
    }

    function formatTime($time) {
        return date("g:i A", strtotime($time));
    }

    $startTimeFormatted = formatTime($_POST['startTime']);
    $endTimeFormatted = formatTime($_POST['endTime']);
    $newTimeRange = $startTimeFormatted . ' - ' . $endTimeFormatted;

    $newDate = $_POST['date'];
    $newLocation = trim($_POST['location']);

    // Check for time/location overlap
    foreach ($xml->event as $event) {
        $existingDate = (string)$event->date;
        $existingLocation = trim((string)$event->location);
        $existingTime = (string)$event->time;

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

        // If there is an overlap in date, location, and time, show error
        if ($isSameDate && $isSameLocation && $isTimeOverlap) {
            echo json_encode(["error" => "Error: This event overlaps with an existing event."]);
            exit();
        }
    }

    // Determine auto-increment ID
    if (!isset($xml->event) || count($xml->event) === 0) {
        $eventId = 1;
    } else {
        $existingIds = array_map(fn($e) => (int)$e->id, iterator_to_array($xml->event));
        $eventId = max($existingIds) + 1;
    }

    // Add to XML
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

    // Save XML
    if ($xml->asXML($xmlFile)) {
        // ✅ Save to Database
        $conn = new mysqli("localhost", "root", "secretsecret4", "ub_lipa_event_scheduler");
        if ($conn->connect_error) {
            echo json_encode(["error" => "XML saved, but DB connection failed: " . $conn->connect_error]);
            exit();
        }

        // Prepare statement to avoid SQL injection
        $stmt = $conn->prepare("INSERT INTO events (date, time, event_name, location, department, event_type, target_audience, registration_link, contact_information, agenda)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $newDate, $newTimeRange, $_POST['eventName'], $_POST['location'], $_POST['department'], $_POST['eventType'], $_POST['targetAudience'], $_POST['registrationLink'], $_POST['contactInformation'], $_POST['agenda']);

        if ($stmt->execute()) {
            echo json_encode(["success" => "Event successfully added."]);
        } else {
            echo json_encode(["error" => "DB insert failed: " . $conn->error]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(["error" => "Error saving to XML. Please check file permissions."]);
    }

} else {
    echo json_encode(["error" => "Error: Missing required form fields."]);
}
