<?php
    require('connect.php');
 
    $email = $_POST['email'];
    $password = $_POST['password'];
  
    // Using prepared statement to prevent SQL injection
    $query = "INSERT INTO `admin` (email, password) VALUES (?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ss", $email, $password);
    $result = $stmt->execute();
        if($result)
		{
            $msg = "admin registered Successfully.";
				echo($msg);
			echo"<br>"."<br>";
			echo "<a href='adminaccount.php'>Click here to go to account</a>";
			
        }
			else
		{
              $msg = "Database error. Its looking like you have already registered an account on this email address";
			echo($msg);
					echo"<br>"."<br>";
			echo "<a href='admin.php'>Go Back</a>";
			
        }
		
    
	
    ?>