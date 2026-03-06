<?php

// Temporary diagnostic script - DELETE after use
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Laravel Diagnostic</h2>";

// Check for maintenance mode file
$maintenanceFile = __DIR__ . '/../storage/framework/maintenance.php';
if (file_exists($maintenanceFile)) {
    echo "<p>⚠️ <strong>Maintenance mode is ACTIVE!</strong> File exists: storage/framework/maintenance.php</p>";
    unlink($maintenanceFile);
    echo "<p>✅ Maintenance mode file DELETED. Site should be back up.</p>";
} else {
    echo "<p>✅ Not in maintenance mode</p>";
}

// Check .env file
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    echo "<p>✅ .env file exists</p>";
} else {
    echo "<p>❌ <strong>.env file is MISSING!</strong> This will cause a 500/503.</p>";
}

// Check storage directory permissions
$storagePath = __DIR__ . '/../storage';
echo "<p>Storage writable: " . (is_writable($storagePath) ? '✅ Yes' : '❌ No') . "</p>";
echo "<p>Storage/logs writable: " . (is_writable($storagePath . '/logs') ? '✅ Yes' : '❌ No') . "</p>";
echo "<p>Storage/framework writable: " . (is_writable($storagePath . '/framework') ? '✅ Yes' : '❌ No') . "</p>";
echo "<p>Storage/framework/views writable: " . (is_writable($storagePath . '/framework/views') ? '✅ Yes' : '❌ No') . "</p>";
echo "<p>Bootstrap/cache writable: " . (is_writable(__DIR__ . '/../bootstrap/cache') ? '✅ Yes' : '❌ No') . "</p>";

echo "<h3>Done. Try loading the site again.</h3>";
