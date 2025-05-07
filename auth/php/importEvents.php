<?php
$targetDir = dirname(__DIR__, 2) . '/dashboard/data/';
$targetFile = $targetDir . 'event.xml';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['xmlFile']) && $_FILES['xmlFile']['error'] === UPLOAD_ERR_OK) {
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Load existing XML
    if (file_exists($targetFile)) {
        $existingXml = simplexml_load_file($targetFile);
    } else {
        $existingXml = new SimpleXMLElement('<events></events>');
    }

    // Load uploaded XML
    $uploadedXml = simplexml_load_file($_FILES['xmlFile']['tmp_name']);
    if ($uploadedXml === false) {
        echo "<script>alert('Failed to read uploaded XML file.'); history.back();</script>";
        exit();
    }

    // Append each <event> from uploaded file
    foreach ($uploadedXml->event as $event) {
        $newEvent = $existingXml->addChild('event');
        foreach ($event->children() as $key => $value) {
            $newEvent->addChild($key, htmlspecialchars($value));
        }
    }

    // Save merged XML back to file
    $existingXml->asXML($targetFile);

    echo "<script>
            alert('XML file imported and data appended successfully!');
            window.location.href = 'http://127.0.0.1/IPT_UbEvent/dashboard/browseEvent/browseEvent.html';
          </script>";
    exit();
} else {
    echo "<script>alert('Invalid file or upload error.'); history.back();</script>";
}
?>
