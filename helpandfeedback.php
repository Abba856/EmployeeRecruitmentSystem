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
    <title>Support & Feedback | Employee Recruitment System</title>
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
                    <li><a href="events.php">Events</a></li>
                    <li><a href="helpandfeedback.php" class="active">Support</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-headset"></i> Support & Feedback</h2>
            </div>
            
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <i class="fas fa-question-circle"></i>
                    <h3>Need Help?</h3>
                    <p>If you're having trouble with the system, check our help documentation or contact support.</p>
                    <a href="wanthelp.php" class="btn" style="margin-top: 15px;">Get Help</a>
                </div>

                <div class="dashboard-card">
                    <i class="fas fa-comment-dots"></i>
                    <h3>Give Feedback</h3>
                    <p>Help us improve the system by providing your feedback and suggestions.</p>
                    <a href="feedback.php" class="btn" style="margin-top: 15px;">Provide Feedback</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-info-circle"></i> Frequently Asked Questions</h2>
            </div>
            
            <div class="alert alert-info">
                <h3>How do I update my profile information?</h3>
                <p>You can update your profile information by going to "My Profile" and clicking the "Edit Profile" button.</p>
            </div>
            
            <div class="alert alert-info">
                <h3>How do I apply for a position?</h3>
                <p>Check the "Job Requirements" section to see available positions. If you meet the criteria, you can apply through the system.</p>
            </div>
            
            <div class="alert alert-info">
                <h3>How do I reset my password?</h3>
                <p>If you've forgotten your password, use the "Forgot Password" link on the login page to reset it.</p>
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
