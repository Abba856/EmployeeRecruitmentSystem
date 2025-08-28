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
    <title>Dashboard | Employee Recruitment System</title>
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
                    <li><a href="myaccount.php" class="active">Dashboard</a></li>
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
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="profile-info">
                    <h2>Welcome, <?php echo htmlspecialchars($email); ?>!</h2>
                    <p>Candidate Dashboard</p>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <i class="fas fa-user-circle"></i>
                <h3>My Profile</h3>
                <p>View and update your personal information</p>
                <a href="viewprofile.php" class="btn" style="margin-top: 15px;">View Profile</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-briefcase"></i>
                <h3>Job Requirements</h3>
                <p>Browse current job openings and requirements</p>
                <a href="requirementboard.php" class="btn" style="margin-top: 15px;">View Requirements</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-calendar-alt"></i>
                <h3>Upcoming Events</h3>
                <p>Check scheduled interviews and events</p>
                <a href="#" class="btn" style="margin-top: 15px;">View Events</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-headset"></i>
                <h3>Support</h3>
                <p>Get help or provide feedback</p>
                <a href="helpandfeedback.php" class="btn" style="margin-top: 15px;">Get Support</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-chart-line"></i> Application Status</h2>
            </div>
            <div class="alert alert-warning">
                <i class="fas fa-info-circle"></i> Your application is currently under review. We will notify you of any updates.
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
