<?php
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['email'])) {
    header("Location: myaccount.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Complete | Employee Recruitment System</title>
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
        <div class="card" style="text-align: center; max-width: 600px; margin: 50px auto;">
            <div style="font-size: 4rem; color: var(--success-color); margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h2 style="color: var(--success-color); margin-bottom: 20px;">Registration Complete!</h2>
            
            <p style="font-size: 1.2rem; margin-bottom: 30px;">
                Thank you for registering with our Employee Recruitment System. 
                Your registration has been successfully completed.
            </p>
            
            <div class="alert alert-info" style="text-align: left;">
                <h3><i class="fas fa-info-circle"></i> Next Steps</h3>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>You can now login to your account using your credentials</li>
                    <li>Check the job requirements board for available positions</li>
                    <li>Update your profile information as needed</li>
                    <li>Apply for positions that match your qualifications</li>
                </ul>
            </div>
            
            <div style="margin-top: 30px;">
                <a href="login.php" class="btn btn-success" style="padding: 15px 30px; font-size: 1.1rem;">
                    <i class="fas fa-sign-in-alt"></i> Login to Your Account
                </a>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p>&copy; 2025 Employee Recruitment System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>