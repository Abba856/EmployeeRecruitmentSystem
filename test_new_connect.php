<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing with new connection file<br>";

require('connect_new.php');

if ($connection) {
    echo "Connection successful!<br>";
    
    // Test a simple query
    $result = $connection->query("SELECT COUNT(*) as count FROM admin");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "Number of admins: " . $row['count'] . "<br>";
    } else {
        echo "Query failed: " . $connection->error . "<br>";
    }
} else {
    echo "Connection failed<br>";
}

echo "Test completed.";
?>