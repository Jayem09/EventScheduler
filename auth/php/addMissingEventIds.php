<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Path to your XML file
$xmlFile = 'C:/xampp_new/htdocs/IPT_UbEvent/dashboard/data/event.xml';

// Check if file exists
if (!file_exists($xmlFile)) {
    die("❌ XML file not found.");
}

// Load XML using DOMDocument
$dom = new DOMDocument();
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;
$dom->load($xmlFile);

$events = $dom->getElementsByTagName("event");
$idCounter = 1;

// Assign <id> tags if missing
foreach ($events as $event) {
    $idElements = $event->getElementsByTagName("id");

    // Skip if <id> already exists
    if ($idElements->length > 0 && trim($idElements->item(0)->nodeValue) !== "") {
        continue;
    }

    // Create and insert new <id>
    $idElement = $dom->createElement("id", $idCounter++);
    
    // Insert as the first child (before <date>)
    $firstChild = $event->firstChild;
    $event->insertBefore($idElement, $firstChild);
}

// Save the updated XML
$dom->save($xmlFile);

echo "✅ All missing <id> tags added successfully!";
?>
