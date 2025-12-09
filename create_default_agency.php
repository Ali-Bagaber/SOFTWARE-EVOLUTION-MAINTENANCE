<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\ManageUserModel\Agency;

try {
    echo "Creating default agency...\n";
    
    // Check if any agencies exist
    $existingAgency = Agency::first();
    
    if ($existingAgency) {
        echo "✅ Agency already exists: {$existingAgency->agency_name} (ID: {$existingAgency->agency_id})\n";
    } else {
        // Create a default agency
        $agency = Agency::create([
            'agency_name' => 'Malaysian Communications and Multimedia Commission',
            'agency_email' => 'contact@mcmc.gov.my',
            'description' => 'Main regulatory body for communications and multimedia services'
        ]);
        
        echo "✅ Created default agency: {$agency->agency_name} (ID: {$agency->agency_id})\n";
    }
    
    // List all agencies
    echo "\n📋 All agencies in database:\n";
    $agencies = Agency::all();
    foreach ($agencies as $agency) {
        echo "- ID: {$agency->agency_id}, Name: {$agency->agency_name}\n";
    }
    
    echo "\n🎉 Agency setup complete! Forward/Discard functions should now work.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
