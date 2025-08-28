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
    <title>Sort Candidates | Employee Recruitment System</title>
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
                    <li><a href="sort.php" class="active">Sort Candidates</a></li>
                    <li><a href="seefeedback.php">View Feedback</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-filter"></i> Sort Candidates Based on Criteria</h2>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Filter and sort candidates based on various criteria.
            </div>
            
            <form action="#" method="GET">
                <div class="form-group">
                    <label for="position" class="form-label">Position</label>
                    <select id="position" name="position" class="form-control">
                        <option value="">All Positions</option>
                        <option value="Web Developer">Web Developer</option>
                        <option value="Mobile App Developer">Mobile App Developer</option>
                        <option value="DataBase Administrator">DataBase Administrator</option>
                        <option value="Search Engine Optimizer">Search Engine Optimizer</option>
                        <option value="Product Manager">Product Manager</option>
                        <option value="HR Manager">HR Manager</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="experience" class="form-label">Minimum Experience (years)</label>
                    <input type="number" id="experience" name="experience" class="form-control" min="0" max="20" placeholder="Enter minimum experience">
                </div>
                
                <div class="form-group">
                    <label for="cpi" class="form-label">Minimum CPI</label>
                    <input type="number" id="cpi" name="cpi" class="form-control" min="0" max="10" step="0.1" placeholder="Enter minimum CPI">
                </div>
                
                <div class="form-group">
                    <label for="sortby" class="form-label">Sort By</label>
                    <select id="sortby" name="sortby" class="form-control">
                        <option value="name">Name</option>
                        <option value="experience">Experience</option>
                        <option value="cpi">CPI</option>
                        <option value="date">Registration Date</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="order" class="form-label">Order</label>
                    <select id="order" name="order" class="form-control">
                        <option value="asc">Ascending</option>
                        <option value="desc">Descending</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-block">
                    <i class="fas fa-filter"></i> Filter & Sort Candidates
                </button>
            </form>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-users"></i> Filtered Candidates</h2>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Position</th>
                            <th>Experience</th>
                            <th>CPI</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Build query based on filters
                        $query = "SELECT p.firstname, p.middlename, p.lastname, a.pemail, a.post, ac.experience, ac.cpi 
                                 FROM personal p 
                                 JOIN account a ON p.userid = a.userid 
                                 JOIN academic ac ON p.userid = ac.userid";
                        
                        $conditions = [];
                        $params = [];
                        $types = "";
                        
                        if (!empty($_GET['position'])) {
                            $conditions[] = "a.post = ?";
                            $params[] = $_GET['position'];
                            $types .= "s";
                        }
                        
                        if (!empty($_GET['experience'])) {
                            $conditions[] = "ac.experience >= ?";
                            $params[] = $_GET['experience'];
                            $types .= "i";
                        }
                        
                        if (!empty($_GET['cpi'])) {
                            $conditions[] = "ac.cpi >= ?";
                            $params[] = $_GET['cpi'];
                            $types .= "d";
                        }
                        
                        if (!empty($conditions)) {
                            $query .= " WHERE " . implode(" AND ", $conditions);
                        }
                        
                        // Add sorting
                        $sortby = !empty($_GET['sortby']) ? $_GET['sortby'] : 'name';
                        $order = !empty($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';
                        
                        switch ($sortby) {
                            case 'experience':
                                $query .= " ORDER BY ac.experience " . $order;
                                break;
                            case 'cpi':
                                $query .= " ORDER BY ac.cpi " . $order;
                                break;
                            case 'date':
                                $query .= " ORDER BY p.userid " . $order;
                                break;
                            case 'name':
                            default:
                                $query .= " ORDER BY p.firstname " . $order;
                                break;
                        }
                        
                        // Prepare and execute query
                        if (!empty($params)) {
                            $stmt = $connection->prepare($query);
                            $stmt->bind_param($types, ...$params);
                            $stmt->execute();
                            $result = $stmt->get_result();
                        } else {
                            $result = $connection->query($query);
                        }
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['firstname'] . " " . $row['middlename'] . " " . $row['lastname']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['pemail']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['post']) . "</td>";
                                echo "<td>" . $row['experience'] . " years</td>";
                                echo "<td>" . $row['cpi'] . "</td>";
                                echo "<td>";
                                echo "<a href='#' class='btn' style='padding: 5px 10px; margin-right: 5px;'><i class='fas fa-eye'></i></a>";
                                echo "<a href='#' class='btn' style='padding: 5px 10px; margin-right: 5px;'><i class='fas fa-envelope'></i></a>";
                                echo "<a href='#' class='btn btn-danger' style='padding: 5px 10px;'><i class='fas fa-times'></i></a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' style='text-align: center;'>No candidates found matching the criteria</td></tr>";
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