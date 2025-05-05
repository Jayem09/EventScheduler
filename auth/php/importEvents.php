<?php
$targetDir = dirname(__DIR__, 2) . '/dashboard/data/';
$targetFile = $targetDir . 'event.xml';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['xmlFile']) && $_FILES['xmlFile']['error'] === UPLOAD_ERR_OK) {
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Move uploaded file to the correct location
    if (move_uploaded_file($_FILES['xmlFile']['tmp_name'], $targetFile)) {
        // Success: Show alert and redirect to the HTML page
        echo "<script>
                alert('XML file imported successfully!');
                window.location.href = 'http://127.0.0.1/IPT_UbEvent/dashboard/browseEvent/browseEvent.html';
              </script>";
        exit();
    } else {
        echo "<script>alert('Failed to move uploaded XML file.'); history.back();</script>";
    }
} else {
    echo "<script>alert('Invalid file or upload error.'); history.back();</script>";
}
?>
