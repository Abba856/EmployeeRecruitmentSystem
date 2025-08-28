<?php
$connection = new mysqli('localhost', 'root', '', 'recruitment');

if ($connection->connect_error) {
    die("Database Connection Failed: " . $connection->connect_error);
}
?>
