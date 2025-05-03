<?php
// Check if the form was submitted with the selected format
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exportFormat = $_POST['format']; // Get selected export format from the form

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "secretsecret4"; 
    $dbname = "ub_lipa_event_scheduler"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch all events from the database
    $sql = "SELECT * FROM events";
    $result = $conn->query($sql);

    if ($exportFormat == 'xml') {
        // Create new XML file
        $xml = new SimpleXMLElement('<events></events>');

        // Loop through the database results and add them to the XML
        while ($row = $result->fetch_assoc()) {
            $event = $xml->addChild('event');
            $event->addChild('date', $row['date']);
            $event->addChild('time', $row['time']);
            $event->addChild('eventName', $row['event_name']);
            $event->addChild('location', $row['location']);
            $event->addChild('department', $row['department']);
            $event->addChild('eventType', $row['event_type']);
            $event->addChild('targetAudience', $row['target_audience']);
            $event->addChild('registrationLink', $row['registration_link']);
            $event->addChild('contactInformation', $row['contact_information']);
            $event->addChild('agenda', $row['agenda']);
        }

        // Set headers for downloading the file as XML
        header('Content-Type: application/xml');
        header('Content-Disposition: attachment; filename="events.xml"');

        // Output the XML to the browser
        echo $xml->asXML();

    } elseif ($exportFormat == 'csv') {
        // Set headers for downloading the file as CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="events.csv"');

        // Open output stream to the browser
        $output = fopen('php://output', 'w');

        // Set CSV column headers
        fputcsv($output, ['Date', 'Time', 'Event Name', 'Location', 'Department', 'Event Type', 'Target Audience', 'Registration Link', 'Contact Information', 'Agenda']);

        // Loop through the database results and write each row to CSV
        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['date'],
                $row['time'],
                $row['event_name'],
                $row['location'],
                $row['department'],
                $row['event_type'],
                $row['target_audience'],
                $row['registration_link'],
                $row['contact_information'],
                $row['agenda']
            ]);
        }

        // Close the output stream
        fclose($output);
    }

    // Close the database connection
    $conn->close();
    exit();
}
?>
