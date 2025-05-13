<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Content-Type: application/json");

$eventId = isset($_GET['id']) ? trim($_GET['id']) : '';
if ($eventId === '') {
    echo json_encode(["error" => "Missing event ID."]);
    exit;
}

$xmlFile = 'C:/xampp_new/htdocs/IPT_UbEvent/dashboard/  data/event.xml';

if (!file_exists($xmlFile)) {
    echo json_encode(["error" => "XML file not found."]);
    exit;
}

// Load with DOMDocument
$dom = new DOMDocument();
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->load($xmlFile);

$xpath = new DOMXPath($dom);
$events = $xpath->query("//event[id='$eventId']");

if ($events->length > 0) {
    $event = $events->item(0);
    $event->parentNode->removeChild($event);
    $dom->save($xmlFile);
    echo json_encode(["message" => "✅ Event deleted"]);
} else {
    echo json_encode(["error" => "❌ Event not found"]);
}
?>