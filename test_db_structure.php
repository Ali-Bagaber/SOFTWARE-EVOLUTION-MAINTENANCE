<?php
echo "Testing database column fixes...\n";

try {
    // Simple test to check if we can connect to MySQL
    $connection = mysqli_connect('localhost', 'root', '', 'mcmc_inquiry_db');
    
    if (!$connection) {
        throw new Exception('Cannot connect to database: ' . mysqli_connect_error());
    }
    
    echo "✅ Database connection successful\n";
    
    // Check table structure
    $result = mysqli_query($connection, "DESCRIBE inquiry_histories");
    
    if (!$result) {
        throw new Exception('Cannot describe table: ' . mysqli_error($connection));
    }
    
    echo "\n📋 inquiry_histories table structure:\n";
    echo "------------------------------------\n";
    
    $has_new_status = false;
    $has_user_id = false;  
    $has_timestamp = false;
    $has_agency_id = false;
    
    while ($row = mysqli_fetch_assoc($result)) {
        echo sprintf("%-15s %-20s %s\n", $row['Field'], $row['Type'], $row['Null'] == 'YES' ? 'NULL' : 'NOT NULL');
        
        if ($row['Field'] == 'new_status') $has_new_status = true;
        if ($row['Field'] == 'user_id') $has_user_id = true;
        if ($row['Field'] == 'timestamp') $has_timestamp = true;
        if ($row['Field'] == 'agency_id') $has_agency_id = true;
    }
    
    echo "\n🔍 Column validation:\n";
    echo "- new_status: " . ($has_new_status ? "✅ EXISTS" : "❌ MISSING") . "\n";
    echo "- user_id: " . ($has_user_id ? "✅ EXISTS" : "❌ MISSING") . "\n";
    echo "- timestamp: " . ($has_timestamp ? "✅ EXISTS" : "❌ MISSING") . "\n";
    echo "- agency_id: " . ($has_agency_id ? "✅ EXISTS" : "❌ MISSING") . "\n";
    
    if ($has_new_status && $has_user_id && $has_timestamp && $has_agency_id) {
        echo "\n🎉 All required columns are present! The database schema is correct.\n";
    } else {
        echo "\n⚠️  Some columns are missing. The error might still occur.\n";
    }
    
    mysqli_close($connection);
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
