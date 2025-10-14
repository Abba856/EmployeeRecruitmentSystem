<?php
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['email'])) {
    header("Location: myaccount.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form data
    require('connect.php');
        
    $post = $_POST['post'];
    $resume = $_POST['resume'];
    $pemail = $_POST['pemail'];
    $semail = $_POST['semail'];
    $password = $_POST['setpassword'];
      
    // Get the latest userid from personal table (assuming the user just registered in step 1 before this)
    $get_userid_query = "SELECT userid FROM personal ORDER BY userid DESC LIMIT 1";
    $get_userid_result = $connection->query($get_userid_query);
    if ($get_userid_result->num_rows > 0) {
        $row = $get_userid_result->fetch_assoc();
        $userid = $row['userid'];
        
        // Insert into account table using the same userid
        $query = "INSERT INTO `account` (post,resume,pemail,semail,password,userid) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($query);
        $stmt->bind_param("sssssi", $post, $resume, $pemail, $semail, $password, $userid);
        $result = $stmt->execute();
    } else {
        // If no personal record found, registration flow is broken
        $msg = "Registration flow error. Please start from the beginning.";
        $error = $msg . "<br><br>TIP : Verify that you have not registered any account on mentioned primary or secondary email address.";
        $result = false;
    }
    
    if($result) {
        $url = "finish.php";
        header("Location: " . $url);
        exit();
    } else {
        $msg = "Database error. May be you have already registered an account on this email address or it is something else...Sorry for it.";
        $error = $msg . "<br><br>TIP : Verify that you have not registered any account on mentioned primary or secondary email address.";
    }
}

// Display the form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Account Information | Employee Recruitment System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-building"></i>
                    <span>Employee Recruitment System</span>
                </div>
                <ul class="nav-links">
                    <li><a href="login.php">Login</a></li>
                    <li><a href="registerform1.php">Register</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-lock"></i> Registration - Step 3: Account Information</h2>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
                <div style="text-align: center; margin-top: 20px;">
                    <a href="login.php" class="btn">Go Back To Login</a>
                </div>
            <?php endif; ?>
            
            <div style="display: flex; justify-content: center; margin-bottom: 20px;">
                <div style="display: flex; align-items: center;">
                    <div style="width: 30px; height: 30px; background: var(--light-gray); color: var(--dark-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">1</div>
                    <div style="width: 50px; height: 2px; background: var(--light-gray); margin: 0 10px;"></div>
                    <div style="width: 30px; height: 30px; background: var(--light-gray); color: var(--dark-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">2</div>
                    <div style="width: 50px; height: 2px; background: var(--primary-color); margin: 0 10px;"></div>
                    <div style="width: 30px; height: 30px; background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">3</div>
                </div>
            </div>
            
            <form action="registerform3.php" method="POST">
                <div class="form-group">
                    <label for="post" class="form-label">Position Applying For</label>
                    <select id="post" name="post" class="form-control" required>
                        <option value="">Select Position</option>
                        <option value="Web Developer">Web Developer</option>
                        <option value="Mobile App Developer">Mobile App Developer</option>
                        <option value="DataBase Administrator">DataBase Administrator</option>
                        <option value="Search Engine Optimizer">Search Engine Optimizer</option>
                        <option value="Product Manager">Product Manager</option>
                        <option value="HR Manager">HR Manager</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="resume" class="form-label">Resume File Name</label>
                    <input type="text" id="resume" name="resume" class="form-control" placeholder="Enter your resume file name" required>
                </div>
                
                <div class="form-group">
                    <label for="pemail" class="form-label">Primary Email</label>
                    <input type="email" id="pemail" name="pemail" class="form-control" placeholder="Enter your primary email" required>
                </div>
                
                <div class="form-group">
                    <label for="semail" class="form-label">Secondary Email</label>
                    <input type="email" id="semail" name="semail" class="form-control" placeholder="Enter your secondary email" required>
                </div>
                
                <div class="form-group">
                    <label for="setpassword" class="form-label">Password</label>
                    <input type="password" id="setpassword" name="setpassword" class="form-control" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn btn-block">
                    <i class="fas fa-user-plus"></i> Complete Registration
                </button>
            </form>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p>&copy; 2025 Employee Recruitment System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>