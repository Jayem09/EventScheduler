<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

$eventId = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($eventId === '') {
    echo json_encode(["error" => "Missing event ID."]);
    exit;
}

$xmlFile = 'C:/xampp_new/htdocs/IPT_UbEvent/dashboard/data/event.xml';

if (!file_exists($xmlFile)) {
    echo json_encode(["error" => "XML file not found."]);
    exit;
}

$xml = simplexml_load_file($xmlFile);
$eventFound = false;

foreach ($xml->event as $index => $event) {
    if (trim((string)$event->id) === $eventId) {
        unset($xml->event[$index]);
        $eventFound = true;
        break;
    }
}

if ($eventFound) {
    clearstatcache(true, $xmlFile);
    $saved = file_put_contents($xmlFile, $xml->asXML());

    if ($saved !== false) {
        echo json_encode(["message" => "✅ Event deleted from XML."]);
    } else {
        echo json_encode(["error" => "❌ Failed to save XML."]);
    }
} else {
    echo json_encode(["error" => "❌ Event not found in XML."]);
}
?>
