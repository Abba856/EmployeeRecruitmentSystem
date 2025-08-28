<?php  
require('connect.php');

$postname = $_POST['postname'];
$vacancies = $_POST['vacancy'];
$reqexperience = $_POST['reqexperience'];
$minsalary = $_POST['minsalary'];
$maxsalary = $_POST['maxsalary'];

// Using prepared statement to prevent SQL injection
$query = "UPDATE requirement SET vacancies=?, reqexperience=?, minsalary=?, maxsalary=? WHERE postname=?";
$stmt = $connection->prepare($query);
$stmt->bind_param("iiiss", $vacancies, $reqexperience, $minsalary, $maxsalary, $postname);
$result = $stmt->execute();

if($result){
    echo'Requirement Statistics successfully updated'."<br>"."<br>"."<a href='adminaccount.php'>Go Back To Account</a>";
} else {
    echo'Database error';
}
?>