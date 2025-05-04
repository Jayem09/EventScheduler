<?php
$xmlFile = 'C:/xampp_new/htdocs/IPT_UbEvent/dashboard/data/event.xml';

// Ensure required fields are provided
if (isset($_POST['date'], $_POST['time'], $_POST['eventName'], $_POST['location'], $_POST['department'], 
          $_POST['eventType'], $_POST['targetAudience'], $_POST['registrationLink'], $_POST['contactInformation'], $_POST['agenda'])) {

    // Load or create the XML file
    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
    } else {
        $xml = new SimpleXMLElement('<events></events>');
    }

    // Generate a unique ID for the XML entry
    $eventId = uniqid();

    // Add the new event to the XML
    $newEvent = $xml->addChild('event');
    $newEvent->addChild('id', $eventId);
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
    if ($xml->asXML($xmlFile)) {
        header("Location: http://127.0.0.1/IPT_UbEvent/dashboard/browseEvent/browseEvent.html");
        exit();
    } else {
        echo " Error saving to XML. Please check file permissions.";
    }

} else {
    echo " Error: Missing required form fields.";
}
?>
