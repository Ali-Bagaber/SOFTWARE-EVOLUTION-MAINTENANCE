<?php

require_once __DIR__.'/vendor/autoload.php';

// Bootstrap the Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Test if the PDF facade is available
try {
    echo "Testing PDF facade availability...\n";
    
    // Check if the class exists
    if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
        echo "✓ Barryvdh\DomPDF\Facade\Pdf class exists\n";
    } else {
        echo "✗ Barryvdh\DomPDF\Facade\Pdf class not found\n";
    }
    
    // Check if the alias is registered
    if (class_exists('PDF')) {
        echo "✓ PDF alias is available\n";
    } else {
        echo "✗ PDF alias not available\n";
    }
    
    // Try to create a simple PDF
    echo "Attempting to create a simple PDF...\n";
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML('<h1>Test PDF</h1><p>This is a test PDF document.</p>');
    echo "✓ PDF creation successful\n";
    
    echo "PDF facade is working correctly!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
