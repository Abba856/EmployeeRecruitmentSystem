<?php
// Start session
session_start();

// If user is already logged in, redirect to appropriate dashboard
if (isset($_SESSION['email'])) {
    // Check if user is admin or regular user
    // For now, we'll just redirect to myaccount.php
    header("Location: myaccount.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Employee Recruitment System</title>
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
                    <li><a href="login_modern.php" class="active">Login</a></li>
                    <li><a href="registerform1.php">Register</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card" style="max-width: 500px; margin: 0 auto;">
            <div class="card-header">
                <h2><i class="fas fa-sign-in-alt"></i> Login to Your Account</h2>
            </div>
            
            <form action="loginprocessing.php" method="POST">
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn btn-block">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                
                <div style="text-align: center; margin-top: 20px;">
                    <a href="auth.php" style="color: var(--primary-color);">Forgot Password?</a>
                </div>
            </form>
            
            <div style="margin-top: 20px; text-align: center;">
                <p>Don't have an account? <a href="registerform1.php" style="color: var(--primary-color);">Register Now</a></p>
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