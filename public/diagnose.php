<?php

// Temporary diagnostic script - DELETE after use
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Laravel Diagnostic</h2>";

$base = __DIR__ . '/..';

// Check ALL possible maintenance mode files
$files = [
    'storage/framework/maintenance.php',
    'storage/framework/down',
];

foreach ($files as $f) {
    $full = $base . '/' . $f;
    if (file_exists($full)) {
        echo "<p>⚠️ <strong>Found: $f</strong> — Deleting...</p>";
        unlink($full);
        echo "<p>✅ Deleted $f</p>";
    } else {
        echo "<p>✅ $f does not exist</p>";
    }
}

// Also list everything in storage/framework to find unexpected files
echo "<h3>Files in storage/framework/:</h3><ul>";
foreach (scandir($base . '/storage/framework') as $item) {
    if ($item === '.' || $item === '..') continue;
    $fullPath = $base . '/storage/framework/' . $item;
    $type = is_dir($fullPath) ? '📁' : '📄';
    $size = is_file($fullPath) ? ' (' . filesize($fullPath) . ' bytes)' : '';
    echo "<li>$type $item$size</li>";
}
echo "</ul>";

// Boot the app and check isDownForMaintenance
try {
    require $base . '/vendor/autoload.php';
    $app = require_once $base . '/bootstrap/app.php';
    echo "<p>App->isDownForMaintenance(): " . ($app->isDownForMaintenance() ? '⚠️ TRUE' : '✅ false') . "</p>";
} catch (Throwable $e) {
    echo "<p>❌ " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<h3>Done. Try loading the site again.</h3>";
