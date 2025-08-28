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
    <title>Update Requirement Statistics | Employee Recruitment System</title>
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
                    <li><a href="updaterequirement.php" class="active">Update Requirements</a></li>
                    <li><a href="updatedatabase.php">Candidate Database</a></li>
                    <li><a href="seefeedback.php">View Feedback</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-edit"></i> Update Requirement Statistics</h2>
            </div>
            
            <form action="reqformprocessing.php" method="POST">
                <div class="form-group">
                    <label for="postname" class="form-label">Select Position</label>
                    <select id="postname" name="postname" class="form-control" required>
                        <option value="">- - Select Position - -</option>
                        <option value="Web Developer">Web Developer</option>
                        <option value="Mobile App Developer">Mobile App Developer</option>
                        <option value="DataBase Administrator">DataBase Administrator</option>
                        <option value="Search Engine Optimizer">Search Engine Optimizer</option>
                        <option value="Product Manager">Product Manager</option>
                        <option value="HR Manager">HR Manager</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="vacancy" class="form-label">Enter Vacancies</label>
                    <input type="number" id="vacancy" name="vacancy" class="form-control" min="0" max="1000" required>
                </div>
                
                <div class="form-group">
                    <label for="reqexperience" class="form-label">Required Experience (years)</label>
                    <input type="number" id="reqexperience" name="reqexperience" class="form-control" min="0" max="20" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Salary Range (per month)</label>
                    <div style="display: flex; gap: 10px;">
                        <div style="flex: 1;">
                            <label for="minsalary" class="form-label">From</label>
                            <input type="number" id="minsalary" name="minsalary" class="form-control" min="5000" max="250000" step="500" required>
                        </div>
                        <div style="flex: 1;">
                            <label for="maxsalary" class="form-label">To</label>
                            <input type="number" id="maxsalary" name="maxsalary" class="form-control" min="5000" max="500000" step="500" required>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-block">
                    <i class="fas fa-save"></i> Update Requirements
                </button>
            </form>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-briefcase"></i> Current Requirements</h2>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Vacancies</th>
                            <th>Experience</th>
                            <th>Salary Range</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all requirements
                        $query = "SELECT * FROM requirement ORDER BY postname";
                        $result = $connection->query($query);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['postname']) . "</td>";
                                echo "<td>" . $row['vacancies'] . "</td>";
                                echo "<td>" . $row['reqexperience'] . " years</td>";
                                echo "<td>$" . number_format($row['minsalary']) . " - $" . number_format($row['maxsalary']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align: center;'>No requirements found</td></tr>";
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