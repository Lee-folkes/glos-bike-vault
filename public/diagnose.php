<?php

// Temporary diagnostic script - DELETE after use
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Laravel Diagnostic</h2>";

// Try to boot Laravel and catch any errors
try {
    require __DIR__.'/../vendor/autoload.php';
    echo "<p>✅ Autoloader loaded</p>";
    
    $app = require_once __DIR__.'/../bootstrap/app.php';
    echo "<p>✅ App bootstrapped</p>";

    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "<p>✅ Kernel created</p>";

    // Handle a fake request to fully boot the app (sets facade root)
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::create('/diagnose-clear-cache', 'GET')
    );
    echo "<p>Response status from test request: " . $response->getStatusCode() . "</p>";

    // Now facades work — clear caches
    Illuminate\Support\Facades\Artisan::call('config:clear');
    echo "<p>✅ Config cache cleared</p>";

    Illuminate\Support\Facades\Artisan::call('route:clear');
    echo "<p>✅ Route cache cleared</p>";

    Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "<p>✅ View cache cleared</p>";

    Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo "<p>✅ App cache cleared</p>";

    $kernel->terminate($request, $response);

    echo "<h3>All caches cleared! Try loading the site again.</h3>";
} catch (Throwable $e) {
    echo "<h3>❌ Error found:</h3>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
