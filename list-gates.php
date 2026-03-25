<?php
// list-gates.php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Gate;

echo "=== Listing ALL Defined Gates ===\n\n";

$allGates = Gate::abilities();
echo "Total gates found: " . count($allGates) . "\n\n";

if (count($allGates) > 0) {
    foreach ($allGates as $gate => $callback) {
        echo "- '{$gate}'\n";
    }
} else {
    echo "❌ No gates are defined!\n";
}

echo "\n=== Checking Provider Registration ===\n";

// Check if AuthServiceProvider is registered
$providers = config('app.providers');
echo "Total providers: " . count($providers) . "\n";

$authProviderFound = false;
foreach ($providers as $provider) {
    if (strpos($provider, 'AuthServiceProvider') !== false) {
        $authProviderFound = true;
        echo "✅ AuthServiceProvider registered: {$provider}\n";
    }
}

if (!$authProviderFound) {
    echo "❌ AuthServiceProvider NOT registered in config/app.php!\n";
}

echo "\n=== Complete ===\n";