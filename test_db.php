<?php
$connection = new mysqli('localhost', 'root', '', 'recruitment');

if ($connection->connect_error) {
    die("Database Connection Failed: " . $connection->connect_error);
} else {
    echo "Database Connection Successful!";
}

// Test a simple query
$result = $connection->query("SELECT COUNT(*) as count FROM admin");
if ($result) {
    $row = $result->fetch_assoc();
    echo "<br>Number of admins: " . $row['count'];
} else {
    echo "<br>Query failed: " . $connection->error;
}
?>