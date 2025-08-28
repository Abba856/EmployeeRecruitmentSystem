<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing database connection...<br>";

// Exact copy of the connection code from connect.php
$connection = new mysqli('localhost', 'root', '', 'recruitment');

if ($connection->connect_error) {
    echo "Database Connection Failed: " . $connection->connect_error;
} else {
    echo "Database Connection Successful!<br>";
    
    // Test a simple query
    $result = $connection->query("SELECT COUNT(*) as count FROM admin");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "Number of admins: " . $row['count'] . "<br>";
    } else {
        echo "Query failed: " . $connection->error . "<br>";
    }
}

echo "Test completed.";
?>