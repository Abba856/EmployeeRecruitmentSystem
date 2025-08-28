<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Direct database connection test<br>";

// Direct connection without using our connect.php file
$connection = new mysqli('localhost', 'root', '', 'recruitment');

if ($connection->connect_error) {
    echo "Connection failed: " . $connection->connect_error . "<br>";
} else {
    echo "Connection successful!<br>";
    
    // Test a simple query
    $result = $connection->query("SELECT COUNT(*) as count FROM admin");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "Number of admins: " . $row['count'] . "<br>";
    } else {
        echo "Query failed: " . $connection->error . "<br>";
    }
    
    $connection->close();
}

echo "Test completed.";
?>