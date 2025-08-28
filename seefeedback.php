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
    <title>View Feedback | Employee Recruitment System</title>
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
                    <li><a href="adminaccount.php">Admin Dashboard</a></li>
                    <li><a href="updaterequirement.php">Update Requirements</a></li>
                    <li><a href="updatedatabase.php">Candidate Database</a></li>
                    <li><a href="seefeedback.php" class="active">View Feedback</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-comments"></i> User Feedback</h2>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> View feedback and suggestions from users to improve the system.
            </div>
        </div>
        
        <div class="card">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>User Email</th>
                            <th>Feedback</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all feedback
                        $query = "SELECT user, feedback, date FROM feedback ORDER BY date DESC";
                        $result = $connection->query($query);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['user']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['feedback']) . "</td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' style='text-align: center;'>No feedback found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
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