<?php
// debug.php - Run with: php debug.php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

echo "=== DEBUG PERMISSIONS SCRIPT ===\n\n";

// 1. Get super admin user
$user = User::where('email', 'superadmin@ju.edu.et')->first();
if (!$user) {
    echo "❌ ERROR: User not found!\n";
    exit(1);
}

echo "✅ 1. User Information:\n";
echo "   Name: " . $user->name . "\n";
echo "   Email: " . $user->email . "\n";
echo "   Role ID: " . $user->role_id . "\n\n";

// 2. Check role
if (!$user->role) {
    echo "❌ ERROR: User has no role assigned!\n";
    exit(1);
}

echo "✅ 2. Role Information:\n";
echo "   Role Name: " . $user->role->name . "\n";
echo "   Role Slug: " . $user->role->slug . "\n\n";

// 3. List all permissions
echo "✅ 3. Permissions (" . $user->role->permissions->count() . " total):\n";
$notificationPerms = [];
foreach ($user->role->permissions as $perm) {
    $isNotification = strpos($perm->slug, 'notification') !== false;
    if ($isNotification) {
        $notificationPerms[] = $perm->slug;
        echo "   🔔 " . $perm->slug . " (" . $perm->name . ")\n";
    } else {
        echo "   - " . $perm->slug . "\n";
    }
}

// 4. Check specific notification permissions
echo "\n✅ 4. Checking Notification Permissions:\n";
$testPerms = ['view_notifications', 'send_notifications', 'manage_notifications', 'send_custom_notification'];
foreach ($testPerms as $perm) {
    $hasPerm = $user->hasPermission($perm);
    echo "   " . $perm . ": " . ($hasPerm ? "✅ YES" : "❌ NO") . "\n";
}

// 5. Test can() method
echo "\n✅ 5. Testing can() method:\n";
foreach ($testPerms as $perm) {
    $canResult = $user->can($perm);
    echo "   " . $perm . ": " . ($canResult ? "✅ YES" : "❌ NO") . "\n";
}

// 6. Test Gate system (requires authentication)
echo "\n✅ 6. Testing Gate System:\n";
Auth::login($user);
echo "   Logged in as: " . Auth::user()->name . "\n\n";

foreach ($testPerms as $perm) {
    $gateResult = Gate::allows($perm);
    echo "   Gate::allows('" . $perm . "'): " . ($gateResult ? "✅ YES" : "❌ NO") . "\n";
    if (!$gateResult) {
        echo "     ⚠️  Problem: Gate denies '" . $perm . "' permission\n";
    }
}

// 7. Check if gates are defined
echo "\n✅ 7. Checking Gate Definitions:\n";
$allGates = Gate::abilities();
$definedGates = [];
foreach ($testPerms as $perm) {
    if (array_key_exists($perm, $allGates)) {
        $definedGates[] = $perm;
        echo "   '" . $perm . "' gate: ✅ DEFINED\n";
    } else {
        echo "   '" . $perm . "' gate: ❌ NOT DEFINED\n";
    }
}

// 8. Check AuthServiceProvider
echo "\n✅ 8. Checking AuthServiceProvider.php:\n";
$providerPath = app_path('Providers/AuthServiceProvider.php');
if (file_exists($providerPath)) {
    $content = file_get_contents($providerPath);
    
    // Check for Gate::before (super admin bypass)
    if (strpos($content, 'Gate::before') !== false) {
        echo "   Gate::before(): ✅ FOUND (super admin bypass)\n";
    } else {
        echo "   Gate::before(): ❌ NOT FOUND (add for super admin)\n";
    }
    
    // Check for gate definitions
    if (strpos($content, 'Gate::define') !== false) {
        echo "   Gate::define(): ✅ FOUND (gates are defined)\n";
    } else {
        echo "   Gate::define(): ❌ NOT FOUND (no gates defined)\n";
    }
    
    // Count total Gate::define calls
    $gateCount = substr_count($content, 'Gate::define');
    echo "   Total Gate::define calls: " . $gateCount . "\n";
} else {
    echo "   ❌ AuthServiceProvider.php not found!\n";
}

echo "\n=== SUMMARY ===\n";
if (count($definedGates) === count($testPerms)) {
    echo "✅ All gates are properly defined!\n";
} else {
    echo "❌ Missing gate definitions. Need to update AuthServiceProvider.\n";
}

echo "\n=== NEXT STEPS ===\n";
echo "1. If gates are missing, update AuthServiceProvider.php\n";
echo "2. Run: php artisan cache:clear\n";
echo "3. Run: php artisan config:clear\n";
echo "4. Test again\n";

echo "\n=== DEBUG COMPLETE ===\n";