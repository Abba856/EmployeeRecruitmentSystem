<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Include database connection
require('connect.php');

// Get user ID
$query = "SELECT * FROM account WHERE account.pemail=?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$userid = $row['userid'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Employee Recruitment System</title>
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
                    <li><a href="viewprofile.php" class="active">My Profile</a></li>
                    <li><a href="requirementboard.php">Requirements</a></li>
                    <li><a href="events.php">Events</a></li>
                    <li><a href="helpandfeedback.php">Support</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-user-circle"></i> My Profile</h2>
            </div>
            
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="profile-info">
                    <h2><?php echo htmlspecialchars($email); ?></h2>
                    <p>Candidate Profile</p>
                </div>
            </div>
        </div>

        <div class="profile-details">
            <!-- Personal Information -->
            <div class="detail-card">
                <h3><i class="fas fa-user"></i> Personal Information</h3>
                <?php
                $query = "SELECT * FROM personal,account WHERE personal.userid=?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("i", $userid);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                ?>
                <ul class="detail-list">
                    <li><strong>First Name:</strong> <?php echo htmlspecialchars($row['firstname']); ?></li>
                    <li><strong>Middle Name:</strong> <?php echo htmlspecialchars($row['middlename']); ?></li>
                    <li><strong>Last Name:</strong> <?php echo htmlspecialchars($row['lastname']); ?></li>
                    <li><strong>Gender:</strong> <?php echo htmlspecialchars($row['gender']); ?></li>
                    <li><strong>Birth Date:</strong> <?php echo htmlspecialchars($row['birthdate']); ?></li>
                    <li><strong>State:</strong> <?php echo htmlspecialchars($row['state']); ?></li>
                    <li><strong>City:</strong> <?php echo htmlspecialchars($row['city']); ?></li>
                </ul>
            </div>

            <!-- Academic Information -->
            <div class="detail-card">
                <h3><i class="fas fa-graduation-cap"></i> Academic Information</h3>
                <?php
                $query = "SELECT * FROM academic,account WHERE academic.userid=?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("i", $userid);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                ?>
                <ul class="detail-list">
                    <li><strong>University:</strong> <?php echo htmlspecialchars($row['university']); ?></li>
                    <li><strong>Institute:</strong> <?php echo htmlspecialchars($row['institute']); ?></li>
                    <li><strong>Branch:</strong> <?php echo htmlspecialchars($row['branch']); ?></li>
                    <li><strong>Degree:</strong> <?php echo htmlspecialchars($row['degree']); ?></li>
                    <li><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></li>
                    <li><strong>CPI:</strong> <?php echo htmlspecialchars($row['cpi']); ?></li>
                    <li><strong>Experience:</strong> <?php echo htmlspecialchars($row['experience']); ?> years</li>
                </ul>
            </div>

            <!-- Account Information -->
            <div class="detail-card">
                <h3><i class="fas fa-lock"></i> Account Information</h3>
                <?php
                $query = "SELECT * FROM account WHERE account.userid=?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param("i", $userid);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                ?>
                <ul class="detail-list">
                    <li><strong>Position:</strong> <?php echo htmlspecialchars($row['post']); ?></li>
                    <li><strong>Primary Email:</strong> <?php echo htmlspecialchars($row['pemail']); ?></li>
                    <li><strong>Secondary Email:</strong> <?php echo htmlspecialchars($row['semail']); ?></li>
                    <li><strong>Password:</strong> **********</li>
                </ul>
            </div>
        </div>

        <div class="card">
            <a href="editprofile.php" class="btn">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p>&copy; 2025 Employee Recruitment System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>