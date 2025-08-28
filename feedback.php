<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback | Employee Recruitment System</title>
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
                    <li><a href="myaccount.php">Dashboard</a></li>
                    <li><a href="viewprofile.php">My Profile</a></li>
                    <li><a href="requirementboard.php">Requirements</a></li>
                    <li><a href="helpandfeedback.php">Support</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-comment-dots"></i> Provide Feedback</h2>
            </div>
            
            <p>We value your feedback and suggestions to improve our system. Please share your thoughts below.</p>
            
            <form action="feedbackprocessing.php" method="POST" style="margin-top: 20px;">
                <div class="form-group">
                    <label for="feedback" class="form-label">Your Feedback</label>
                    <textarea id="feedback" name="feedback" class="form-control" rows="8" placeholder="Please share your feedback, suggestions, or any issues you've encountered..." required></textarea>
                </div>
                
                <button type="submit" class="btn btn-block">
                    <i class="fas fa-paper-plane"></i> Submit Feedback
                </button>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-lightbulb"></i> How Your Feedback Helps</h2>
            </div>
            <p>Your feedback helps us:</p>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li>Improve the user experience of our recruitment system</li>
                <li>Identify and fix issues or bugs</li>
                <li>Add new features that users want</li>
                <li>Optimize the system for better performance</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p>&copy; 2025 Employee Recruitment System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
