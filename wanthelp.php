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
    <title>Request Help | Employee Recruitment System</title>
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
                    <li><a href="helpandfeedback.php" class="active">Help</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-question-circle"></i> Request Administrative Help</h2>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Fill out the form below to request administrative assistance.
            </div>
            
            <form id="wanthelp" name="wanthelp" action="wanthelpprocessing.php" method="post">
                <div class="form-group">
                    <label for="helpsubject" class="form-label">Subject</label>
                    <input type="text" id="helpsubject" name="helpsubject" class="form-control" placeholder="Enter subject" required>
                </div>
                
                <div class="form-group">
                    <label for="helpcontent" class="form-label">Problem Description</label>
                    <textarea id="helpcontent" name="helpcontent" class="form-control" rows="8" placeholder="Describe your problem in detail" required></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Request
                    </button>
                    <a href="myaccount.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                </div>
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
