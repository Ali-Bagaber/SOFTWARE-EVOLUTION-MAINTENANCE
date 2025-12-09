<?php
/**
 * Fix missing status history for inquiry #021
 * This script adds the missing "Verified as True" history record
 */

require_once 'vendor/autoload.php';

// Laravel bootstrap
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\inquiry;
use App\Models\inquiry_history;

try {
    // Find inquiry #021
    $inquiry = inquiry::where('inquiry_id', 21)->first();
    
    if (!$inquiry) {
        echo "Inquiry #021 not found!\n";
        exit(1);
    }
    
    echo "Found inquiry #021: {$inquiry->title}\n";
    echo "Current status: {$inquiry->status}\n";
    
    // Check if "Verified as True" history record already exists
    $verifiedHistory = inquiry_history::where('inquiry_id', 21)
        ->where('new_status', 'Verified as True')
        ->first();
    
    if ($verifiedHistory) {
        echo "History record for 'Verified as True' already exists!\n";
        exit(0);
    }
    
    // Create the missing history record
    $historyRecord = inquiry_history::create([
        'inquiry_id' => $inquiry->inquiry_id,
        'new_status' => 'Verified as True',
        'user_id' => 2, // Using user ID 2 as mentioned in the data
        'timestamp' => $inquiry->updated_at ?? now(),
        'agency_id' => 1 // Default agency ID
    ]);
    
    echo "Created missing history record for 'Verified as True' status!\n";
    echo "History ID: {$historyRecord->id}\n";
    echo "Timestamp: {$historyRecord->timestamp}\n";
    
    // Verify the fix
    $allHistory = inquiry_history::where('inquiry_id', 21)
        ->orderBy('timestamp', 'desc')
        ->get();
    
    echo "\nComplete Status History for Inquiry #021:\n";
    foreach ($allHistory as $history) {
        echo "- {$history->new_status} at {$history->timestamp} by user {$history->user_id}\n";
    }
    
    echo "\nFix completed successfully! ✅\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
