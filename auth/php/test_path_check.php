<?php
$xmlFile = 'C:/xampp_new/htdocs/IPT_UbEvent/dashboard/data/event.xml';

if (file_exists($xmlFile)) {
    echo "✅ File found<br>";
    echo "📄 Last modified: " . date("Y-m-d H:i:s", filemtime($xmlFile)) . "<br>";
    echo "🔍 Contents:<br><pre>" . htmlspecialchars(file_get_contents($xmlFile)) . "</pre>";
} else {
    echo "❌ File not found at expected location.";
}
