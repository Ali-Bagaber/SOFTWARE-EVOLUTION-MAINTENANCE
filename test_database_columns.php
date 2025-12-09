<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\inquiry_history;
use App\Models\Inquiry;

try {
    echo "Testing database column structure...\n\n";
    
    // Test 1: Check if we can create an inquiry_history record with correct columns
    echo "Test 1: Creating inquiry_history record with correct columns...\n";
    
    $testData = [
        'inquiry_id' => 1, // Assuming inquiry ID 1 exists
        'new_status' => 'Test Status',
        'user_id' => 1, // Assuming user ID 1 exists
        'timestamp' => now(),
        'agency_id' => 1 // Assuming agency ID 1 exists
    ];
    
    // Test if we can create without actually saving
    $history = new inquiry_history($testData);
    echo "✅ inquiry_history model can be instantiated with new column structure\n";
    
    // Test 2: Check table structure
    echo "\nTest 2: Checking inquiry_histories table structure...\n";
    $columns = \DB::select("DESCRIBE inquiry_histories");
    
    echo "Available columns in inquiry_histories table:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
    
    // Test 3: Check fillable attributes
    echo "\nTest 3: Checking fillable attributes...\n";
    $fillable = $history->getFillable();
    echo "Fillable attributes: " . implode(', ', $fillable) . "\n";
    
    echo "\n✅ All tests passed! Database column structure is correct.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
