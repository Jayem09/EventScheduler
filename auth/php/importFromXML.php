<?php
$uploadDir = dirname(__DIR__, 2) . '/dashboard/data/';
$targetFile = $uploadDir . 'event.xml';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['xmlFile']) && $_FILES['xmlFile']['error'] === UPLOAD_ERR_OK) {
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($_FILES['xmlFile']['tmp_name'], $targetFile)) {
        // ✅ Don't return file; just redirect
        echo "<script>alert('Import successful!'); window.location.href = '../../dashboard/browseEvent.html';</script>";
        exit();
    } else {
        echo "❌ Failed to move uploaded XML file.";
    }
} else {
    echo "❌ Invalid upload or no file selected.";
}
?>
