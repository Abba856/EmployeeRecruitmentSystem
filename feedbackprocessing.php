<?php  
session_start();
if (isset($_SESSION['email'])){
$email = $_SESSION['email'];
}
require('connect.php');

// Using prepared statement to prevent SQL injection
$query = "SELECT * FROM account WHERE account.pemail=?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$userid = $row['userid'];
$user = $row['pemail'];

$feedback = $_POST['feedback'];

// Using prepared statement to prevent SQL injection
$query = "INSERT INTO `feedback` (userid,user,feedback) VALUES (?, ?, ?)";
$stmt = $connection->prepare($query);
$stmt->bind_param("iss", $userid, $user, $feedback);
$result = $stmt->execute();

if($result){
    echo'feedback successfully submitted'."<br>"."<br>"."<a href='helpandfeedback.php'>Go Back To Account</a>";
} else {
    echo'Database error';
}
?>