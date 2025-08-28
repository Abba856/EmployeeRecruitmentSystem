<?php  
session_start();
require('connect.php');

$email = $_POST['email'];
$password = $_POST['password'];

// Using prepared statements to prevent SQL injection
$query = "SELECT * FROM `account` WHERE pemail=? AND password=?";
$stmt = $connection->prepare($query);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result1 = $stmt->get_result();
$usercount = $result1->num_rows;

$query = "SELECT * FROM `admin` WHERE email=? AND password=?";
$stmt = $connection->prepare($query);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result2 = $stmt->get_result();
$admincount = $result2->num_rows;

if ($usercount == 1) {
    $_SESSION['email'] = $email;
    $url = "myaccount.php";
    header("Location: " . $url);
    exit();
} else if ($admincount == 1) {
    $_SESSION['email'] = $email;
    $url = "adminaccount.php";
    header("Location: " . $url);
    exit();
} else {
    echo "Invalid Details.";
    echo "<a href='login.php'>Back to Login</a>";
}
