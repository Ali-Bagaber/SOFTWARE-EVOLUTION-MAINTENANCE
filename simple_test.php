<?php
echo "Starting PDF facade test...\n";

try {
    require_once __DIR__.'/vendor/autoload.php';
    echo "Autoloader loaded\n";
    
    $app = require_once __DIR__.'/bootstrap/app.php';
    echo "App bootstrapped\n";
    
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "Kernel bootstrapped\n";
    
    if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
        echo "PDF facade class exists!\n";
    } else {
        echo "PDF facade class not found\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
