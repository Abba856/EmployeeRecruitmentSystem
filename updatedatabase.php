<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['email'])) {
    header("Location: login_modern.php");
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
    <title>Update Candidate Database | Employee Recruitment System</title>
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
                    <li><a href="updatedatabase.php" class="active">Candidate Database</a></li>
                    <li><a href="seefeedback.php">View Feedback</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-database"></i> Update Candidate Database</h2>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Manage candidate information in the database.
            </div>
            
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <i class="fas fa-plus-circle"></i>
                    <h3>Add New Candidate</h3>
                    <p>Add a new candidate to the database</p>
                    <a href="#" class="btn" style="margin-top: 15px;">Add Candidate</a>
                </div>
                
                <div class="dashboard-card">
                    <i class="fas fa-edit"></i>
                    <h3>Edit Candidate</h3>
                    <p>Modify existing candidate information</p>
                    <a href="#" class="btn" style="margin-top: 15px;">Edit Candidate</a>
                </div>
                
                <div class="dashboard-card">
                    <i class="fas fa-trash-alt"></i>
                    <h3>Delete Candidate</h3>
                    <p>Remove candidate from the database</p>
                    <a href="#" class="btn btn-danger" style="margin-top: 15px;">Delete Candidate</a>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-users"></i> Candidate List</h2>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Experience</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch candidates from database
                        $query = "SELECT p.userid, p.firstname, p.middlename, p.lastname, a.pemail, a.post, ac.experience 
                                 FROM personal p 
                                 JOIN account a ON p.userid = a.userid 
                                 JOIN academic ac ON p.userid = ac.userid 
                                 ORDER BY p.userid";
                        $result = $connection->query($query);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['userid'] . "</td>";
                                echo "<td>" . htmlspecialchars($row['firstname'] . " " . $row['middlename'] . " " . $row['lastname']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['pemail']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['post']) . "</td>";
                                echo "<td>" . $row['experience'] . " years</td>";
                                echo "<td>";
                                echo "<a href='#' class='btn' style='padding: 5px 10px; margin-right: 5px;'><i class='fas fa-edit'></i></a>";
                                echo "<a href='#' class='btn btn-danger' style='padding: 5px 10px;'><i class='fas fa-trash'></i></a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center;'>No candidates found</td></tr>";
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