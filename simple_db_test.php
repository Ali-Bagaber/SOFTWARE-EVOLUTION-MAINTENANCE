<?php
echo "PHP is working\n";

try {
    // Test database connection
    $pdo = new PDO('mysql:host=localhost;dbname=mcmc_inquiry_db', 'root', '');
    echo "Database connection successful\n";
    
    // Check table structure
    $stmt = $pdo->query("DESCRIBE inquiry_histories");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "inquiry_histories table columns:\n";
    foreach ($columns as $column) {
        echo "- {$column['Field']} ({$column['Type']})\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
