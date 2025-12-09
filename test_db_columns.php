<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'mcmc',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

try {
    $columns = Capsule::schema()->getColumnListing('inquiries');
    echo "Columns in inquiries table:\n";
    foreach ($columns as $column) {
        echo "- $column\n";
    }

    // Check if our specific columns exist
    $targetColumns = ['accepted_at', 'acceptance_notes', 'rejected_at', 'rejection_reason', 'rejection_comments'];
    echo "\nChecking for accept/reject columns:\n";
    foreach ($targetColumns as $col) {
        if (in_array($col, $columns)) {
            echo "✓ $col exists\n";
        } else {
            echo "✗ $col MISSING\n";
        }
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
