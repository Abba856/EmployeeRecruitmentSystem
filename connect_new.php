<?php
// New connection file to bypass caching issues
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'recruitment';

$connection = new mysqli($host, $username, $password, $database);

if ($connection->connect_error) {
    die("Database Connection Failed: " . $connection->connect_error);
}
?>