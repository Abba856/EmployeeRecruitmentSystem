<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Include database connection
require('connect.php');

// Check if user is admin
$query = "SELECT * FROM admin WHERE email=?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$admincount = $result->num_rows;

if ($admincount != 1) {
    header("Location: myaccount.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Employee Recruitment System</title>
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
                    <li><a href="adminaccount.php" class="active">Admin Dashboard</a></li>
                    <li><a href="updaterequirement.php">Update Requirements</a></li>
                    <li><a href="updatedatabase.php">Candidate Database</a></li>
                    <li><a href="seefeedback.php">View Feedback</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="profile-info">
                    <h2>Admin Dashboard</h2>
                    <p>Welcome, <?php echo htmlspecialchars($email); ?></p>
                </div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <i class="fas fa-edit"></i>
                <h3>Update Requirements</h3>
                <p>Modify job requirements and vacancies</p>
                <a href="updaterequirement.php" class="btn" style="margin-top: 15px;">Manage Requirements</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-users"></i>
                <h3>Candidate Database</h3>
                <p>View and manage candidate information</p>
                <a href="updatedatabase.php" class="btn" style="margin-top: 15px;">Manage Candidates</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-calendar-check"></i>
                <h3>Schedule Events</h3>
                <p>Schedule interviews and other events</p>
                <a href="scheduleexam.php" class="btn" style="margin-top: 15px;">Schedule Events</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-filter"></i>
                <h3>Sort Candidates</h3>
                <p>Filter candidates based on criteria</p>
                <a href="sort.php" class="btn" style="margin-top: 15px;">Sort Candidates</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-envelope"></i>
                <h3>Send Emails</h3>
                <p>Send emails to multiple candidates</p>
                <a href="multiplemail.php" class="btn" style="margin-top: 15px;">Send Emails</a>
            </div>

            <div class="dashboard-card">
                <i class="fas fa-comments"></i>
                <h3>User Feedback</h3>
                <p>View feedback from users</p>
                <a href="seefeedback.php" class="btn" style="margin-top: 15px;">View Feedback</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-chart-bar"></i> System Statistics</h2>
            </div>
            <div class="dashboard-grid">
                <?php
                // Get total candidates
                $query = "SELECT COUNT(*) as count FROM personal";
                $result = $connection->query($query);
                $candidates = $result->fetch_assoc()['count'];
                
                // Get total admins
                $query = "SELECT COUNT(*) as count FROM admin";
                $result = $connection->query($query);
                $admins = $result->fetch_assoc()['count'];
                
                // Get total feedback
                $query = "SELECT COUNT(*) as count FROM feedback";
                $result = $connection->query($query);
                $feedback = $result->fetch_assoc()['count'];
                ?>
                <div class="dashboard-card">
                    <i class="fas fa-users"></i>
                    <h3><?php echo $candidates; ?></h3>
                    <p>Total Candidates</p>
                </div>
                
                <div class="dashboard-card">
                    <i class="fas fa-user-shield"></i>
                    <h3><?php echo $admins; ?></h3>
                    <p>Total Admins</p>
                </div>
                
                <div class="dashboard-card">
                    <i class="fas fa-comments"></i>
                    <h3><?php echo $feedback; ?></h3>
                    <p>User Feedback</p>
                </div>
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
