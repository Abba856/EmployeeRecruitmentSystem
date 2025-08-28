<?php
// Exact copy of the connection code from connect.php
$connection = new mysqli('localhost', 'root', '', 'recruitment');

if ($connection->connect_error) {
    die("Database Connection Failed: " . $connection->connect_error);
} else {
    echo "Database Connection Successful!";
}
?>