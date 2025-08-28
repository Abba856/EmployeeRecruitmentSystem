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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Requirements | Employee Recruitment System</title>
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
                    <li><a href="requirementboard.php" class="active">Requirements</a></li>
                    <li><a href="helpandfeedback.php">Support</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-briefcase"></i> Current Job Requirements</h2>
            </div>
            
            <p>Browse our current job openings and requirements. If you meet the criteria, apply through the system.</p>
        </div>

        <!-- Web Developer -->
        <div class="requirement-card">
            <h3><i class="fas fa-laptop-code"></i> Web Developer</h3>
            <div class="requirement-details">
                <?php
                $query = "SELECT * FROM `requirement` WHERE `postname`= ?" ;
                $stmt = $connection->prepare($query);
                $stmt->bind_param("s", "Web Developer");
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                ?>
                <div class="requirement-item">
                    <strong>Vacancies</strong>
                    <?php echo $row['vacancies']; ?>
                </div>
                <div class="requirement-item">
                    <strong>Experience</strong>
                    <?php echo $row['reqexperience']; ?> years
                </div>
                <div class="requirement-item">
                    <strong>Salary Range</strong>
                    $<?php echo number_format($row['minsalary']); ?> - $<?php echo number_format($row['maxsalary']); ?>
                </div>
            </div>
        </div>

        <!-- Mobile App Developer -->
        <div class="requirement-card">
            <h3><i class="fas fa-mobile-alt"></i> Mobile App Developer</h3>
            <div class="requirement-details">
                <?php
                $query = "SELECT * FROM `requirement` WHERE `postname`= ?" ;
                $stmt = $connection->prepare($query);
                $stmt->bind_param("s", "Mobile App Developer");
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                ?>
                <div class="requirement-item">
                    <strong>Vacancies</strong>
                    <?php echo $row['vacancies']; ?>
                </div>
                <div class="requirement-item">
                    <strong>Experience</strong>
                    <?php echo $row['reqexperience']; ?> years
                </div>
                <div class="requirement-item">
                    <strong>Salary Range</strong>
                    $<?php echo number_format($row['minsalary']); ?> - $<?php echo number_format($row['maxsalary']); ?>
                </div>
            </div>
        </div>

        <!-- Database Administrator -->
        <div class="requirement-card">
            <h3><i class="fas fa-database"></i> Database Administrator</h3>
            <div class="requirement-details">
                <?php
                $query = "SELECT * FROM `requirement` WHERE `postname`= ?" ;
                $stmt = $connection->prepare($query);
                $stmt->bind_param("s", "DataBase Administrator");
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                ?>
                <div class="requirement-item">
                    <strong>Vacancies</strong>
                    <?php echo $row['vacancies']; ?>
                </div>
                <div class="requirement-item">
                    <strong>Experience</strong>
                    <?php echo $row['reqexperience']; ?> years
                </div>
                <div class="requirement-item">
                    <strong>Salary Range</strong>
                    $<?php echo number_format($row['minsalary']); ?> - $<?php echo number_format($row['maxsalary']); ?>
                </div>
            </div>
        </div>

        <!-- Search Engine Optimizer -->
        <div class="requirement-card">
            <h3><i class="fas fa-search"></i> Search Engine Optimizer</h3>
            <div class="requirement-details">
                <?php
                $query = "SELECT * FROM `requirement` WHERE `postname`= ?" ;
                $stmt = $connection->prepare($query);
                $stmt->bind_param("s", "Search Engine Optimizer");
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                ?>
                <div class="requirement-item">
                    <strong>Vacancies</strong>
                    <?php echo $row['vacancies']; ?>
                </div>
                <div class="requirement-item">
                    <strong>Experience</strong>
                    <?php echo $row['reqexperience']; ?> years
                </div>
                <div class="requirement-item">
                    <strong>Salary Range</strong>
                    $<?php echo number_format($row['minsalary']); ?> - $<?php echo number_format($row['maxsalary']); ?>
                </div>
            </div>
        </div>

        <!-- Product Manager -->
        <div class="requirement-card">
            <h3><i class="fas fa-project-diagram"></i> Product Manager</h3>
            <div class="requirement-details">
                <?php
                $query = "SELECT * FROM `requirement` WHERE `postname`= ?" ;
                $stmt = $connection->prepare($query);
                $stmt->bind_param("s", "Product Manager");
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                ?>
                <div class="requirement-item">
                    <strong>Vacancies</strong>
                    <?php echo $row['vacancies']; ?>
                </div>
                <div class="requirement-item">
                    <strong>Experience</strong>
                    <?php echo $row['reqexperience']; ?> years
                </div>
                <div class="requirement-item">
                    <strong>Salary Range</strong>
                    $<?php echo number_format($row['minsalary']); ?> - $<?php echo number_format($row['maxsalary']); ?>
                </div>
            </div>
        </div>

        <!-- HR Manager -->
        <div class="requirement-card">
            <h3><i class="fas fa-users"></i> HR Manager</h3>
            <div class="requirement-details">
                <?php
                $query = "SELECT * FROM `requirement` WHERE `postname`= ?" ;
                $stmt = $connection->prepare($query);
                $stmt->bind_param("s", "HR Manager");
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                ?>
                <div class="requirement-item">
                    <strong>Vacancies</strong>
                    <?php echo $row['vacancies']; ?>
                </div>
                <div class="requirement-item">
                    <strong>Experience</strong>
                    <?php echo $row['reqexperience']; ?> years
                </div>
                <div class="requirement-item">
                    <strong>Salary Range</strong>
                    $<?php echo number_format($row['minsalary']); ?> - $<?php echo number_format($row['maxsalary']); ?>
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
