<?php

// Temporary diagnostic script - DELETE after use
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Laravel Diagnostic</h2>";

try {
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    // Boot the app by handling a request to the homepage
    $request = Illuminate\Http\Request::create('/', 'GET');
    
    // Register an exception handler to catch errors during boot
    set_exception_handler(function($e) {
        echo "<h3>❌ Uncaught Exception:</h3>";
        echo "<pre>" . htmlspecialchars(get_class($e) . ': ' . $e->getMessage()) . "</pre>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        if ($e->getPrevious()) {
            echo "<h3>Caused by:</h3>";
            echo "<pre>" . htmlspecialchars(get_class($e->getPrevious()) . ': ' . $e->getPrevious()->getMessage()) . "</pre>";
        }
    });

    $response = $kernel->handle($request);
    $status = $response->getStatusCode();
    echo "<p>Response status: <strong>$status</strong></p>";

    if ($status >= 500) {
        // Try to get the response body for error details
        $body = $response->getContent();
        // Check if it contains an error message
        if (strpos($body, 'exception') !== false || strpos($body, 'Error') !== false || strpos($body, 'error') !== false) {
            echo "<h3>Response body:</h3>";
            echo "<div style='border:1px solid #ccc; padding:10px; max-height:400px; overflow:auto;'>" . $body . "</div>";
        } else {
            echo "<p>Response body length: " . strlen($body) . " bytes</p>";
            echo "<div style='border:1px solid #ccc; padding:10px; max-height:400px; overflow:auto;'>" . htmlspecialchars(substr($body, 0, 5000)) . "</div>";
        }
    }

    // Check APP_ENV and APP_DEBUG
    echo "<p>APP_ENV: " . env('APP_ENV', '(not set)') . "</p>";
    echo "<p>APP_DEBUG: " . (env('APP_DEBUG') ? 'true' : 'false') . "</p>";
    echo "<p>APP_URL: " . env('APP_URL', '(not set)') . "</p>";

    // Check PHP version
    echo "<p>PHP version: " . phpversion() . "</p>";

    $kernel->terminate($request, $response);
} catch (Throwable $e) {
    echo "<h3>❌ Error:</h3>";
    echo "<pre>" . htmlspecialchars(get_class($e) . ': ' . $e->getMessage()) . "</pre>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    if ($e->getPrevious()) {
        echo "<h3>Caused by:</h3>";
        echo "<pre>" . htmlspecialchars(get_class($e->getPrevious()) . ': ' . $e->getPrevious()->getMessage()) . "</pre>";
        echo "<pre>" . htmlspecialchars($e->getPrevious()->getTraceAsString()) . "</pre>";
    }
}
