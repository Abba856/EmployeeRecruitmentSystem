<?php
include "connect.php"; 
ini_set("SMTP","ssl://smtp.gmail.com");
ini_set("smtp_port","465");

if (isset($_POST['fsubmit'])){
    $fpemail = $_POST['fpemail'];

    // Using prepared statement to prevent SQL injection
    $query1 = "select * from account where pemail=?";
    $stmt1 = $connection->prepare($query1);
    $stmt1->bind_param("s", $fpemail);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $count1 = $result1->num_rows;
  
    if($count1==1) {
        $rows = $result1->fetch_assoc();
        $password = $rows['password'];
        $to = $rows['pemail'];
        $from = "xyz technolabs";
        $headers = "From: $from\n";
        $body = " Here is your password  :".$password;
        $subject = "your account recovery";
     
        mail($to, $subject, $body, $headers);
    } else {
        echo"no account found on this email". "<a href='auth.php'>Go Back</a>";
    }
}

if (isset($_POST['fsubmit2'])){
    $fsemail = $_POST['fsemail'];
    $fpassword = $_POST['fpassword'];
    
    // Using prepared statement to prevent SQL injection
    $query2 = "SELECT * FROM `account` WHERE semail=? and password=?";
    $stmt2 = $connection->prepare($query2);
    $stmt2->bind_param("ss", $fsemail, $fpassword);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $count2 = $result2->num_rows;

    if($count2==1) {
        $rows = $result2->fetch_assoc();
        $pemail = $rows['pemail'];
        $to = $rows['semail'];
        $from = "xyz technolabs";
        $headers = "From: $from\n";
        $body = " Here is your primary email  :".$pemail;
        $subject = "your account recovery";
     
        mail($to, $subject, $body, $headers);
    } else {
        echo"no account found on this email". "<a href='auth.php'>Go Back</a>";
    }
}
?>
