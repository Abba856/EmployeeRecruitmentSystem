<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simulate a login without redirect
session_start();
require('connect.php');

// Use dummy values instead of $_POST
$email = 'test@example.com';
$password = 'testpassword';

echo "Testing login with email: $email and password: $password<br>";

// Using prepared statements to prevent SQL injection
$query = "SELECT * FROM `account` WHERE pemail=? AND password=?";
$stmt = $connection->prepare($query);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result1 = $stmt->get_result();
$usercount = $result1->num_rows;

echo "User count: $usercount<br>";

$query = "SELECT * FROM `admin` WHERE email=? AND password=?";
$stmt = $connection->prepare($query);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result2 = $stmt->get_result();
$admincount = $result2->num_rows;

echo "Admin count: $admincount<br>";

if ($usercount == 1) {
    echo "Would redirect to myaccount.php<br>";
} else if ($admincount == 1) {
    echo "Would redirect to adminaccount.php<br>";
} else {
    echo "Invalid Details.<br>";
    echo "<a href='login.php'>Back to Login</a><br>";
}

echo "Test completed.";
?>