<?php
/**
 * Admin Login Test Script
 * Run this file to verify admin login functionality
 */

// Include the autoloader
require __DIR__.'/vendor/autoload.php';

// Bootstrap the application
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Create a test email that ends with @admin.com
$testEmail = 'admin'.rand(1000,9999).'@admin.com';
$password = 'admin123456';
$name = 'Test Admin';

// First, ensure the test user exists
echo "Checking if test user exists: $testEmail\n";

$user = \App\Models\User::where('email', $testEmail)->first();

if (!$user) {
    echo "Creating test admin user...\n";
    $user = new \App\Models\User();
    $user->name = $name;
    $user->email = $testEmail;
    $user->password = \Illuminate\Support\Facades\Hash::make($password);
    $user->user_role = 'admin'; // Set role explicitly
    $user->save();
    echo "Created test admin user successfully: $testEmail\n";
} else {
    echo "Test user already exists\n";
}

echo "\n---------------------------------------------------------\n";
echo "To test admin login:\n";
echo "1. Go to http://localhost/mcmc/MCMC/public/login\n";
echo "2. Enter the following credentials:\n";
echo "   Email: $testEmail\n";
echo "   Password: $password\n";
echo "3. You should be redirected to the Admin Home Page\n";
echo "---------------------------------------------------------\n";

echo "\nTest completed. Please check the logs for any errors.\n";