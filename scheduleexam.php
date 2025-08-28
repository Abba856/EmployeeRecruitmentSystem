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
    <title>Schedule Exam Activities | Employee Recruitment System</title>
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
                    <li><a href="scheduleexam.php" class="active">Schedule Exams</a></li>
                    <li><a href="seefeedback.php">View Feedback</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-calendar-check"></i> Schedule Exam Activities</h2>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Schedule exams and interviews for candidates.
            </div>
            
            <form action="#" method="POST">
                <div class="form-group">
                    <label for="examTitle" class="form-label">Exam/Interview Title</label>
                    <input type="text" id="examTitle" name="examTitle" class="form-control" placeholder="Enter exam or interview title" required>
                </div>
                
                <div class="form-group">
                    <label for="examType" class="form-label">Type</label>
                    <select id="examType" name="examType" class="form-control" required>
                        <option value="">Select Type</option>
                        <option value="technical">Technical Exam</option>
                        <option value="aptitude">Aptitude Test</option>
                        <option value="interview">Interview</option>
                        <option value="hr">HR Interview</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="examDate" class="form-label">Date</label>
                    <input type="date" id="examDate" name="examDate" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="examTime" class="form-label">Time</label>
                    <input type="time" id="examTime" name="examTime" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="examDuration" class="form-label">Duration (minutes)</label>
                    <input type="number" id="examDuration" name="examDuration" class="form-control" min="10" max="300" placeholder="Enter duration in minutes" required>
                </div>
                
                <div class="form-group">
                    <label for="examLocation" class="form-label">Location/Venue</label>
                    <input type="text" id="examLocation" name="examLocation" class="form-control" placeholder="Enter location or virtual meeting link" required>
                </div>
                
                <div class="form-group">
                    <label for="candidates" class="form-label">Select Candidates</label>
                    <select id="candidates" name="candidates[]" class="form-control" multiple size="5">
                        <?php
                        // Fetch candidates from database
                        $query = "SELECT p.userid, p.firstname, p.middlename, p.lastname, a.pemail 
                                 FROM personal p 
                                 JOIN account a ON p.userid = a.userid 
                                 ORDER BY p.firstname";
                        $result = $connection->query($query);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['userid'] . "'>" . 
                                     htmlspecialchars($row['firstname'] . " " . $row['middlename'] . " " . $row['lastname'] . " (" . $row['pemail'] . ")") . 
                                     "</option>";
                            }
                        }
                        ?>
                    </select>
                    <small>Hold Ctrl/Cmd to select multiple candidates</small>
                </div>
                
                <button type="submit" class="btn btn-block">
                    <i class="fas fa-calendar-plus"></i> Schedule Exam/Interview
                </button>
            </form>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-calendar-alt"></i> Scheduled Exams & Interviews</h2>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Date & Time</th>
                            <th>Duration</th>
                            <th>Location</th>
                            <th>Candidates</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Technical Assessment</td>
                            <td>Technical Exam</td>
                            <td>2025-09-15 10:00</td>
                            <td>90 mins</td>
                            <td>Room 101</td>
                            <td>12 candidates</td>
                            <td>
                                <a href="#" class="btn" style="padding: 5px 10px; margin-right: 5px;"><i class="fas fa-edit"></i></a>
                                <a href="#" class="btn btn-danger" style="padding: 5px 10px;"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>HR Interview</td>
                            <td>Interview</td>
                            <td>2025-09-16 14:00</td>
                            <td>60 mins</td>
                            <td>Room 205</td>
                            <td>8 candidates</td>
                            <td>
                                <a href="#" class="btn" style="padding: 5px 10px; margin-right: 5px;"><i class="fas fa-edit"></i></a>
                                <a href="#" class="btn btn-danger" style="padding: 5px 10px;"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
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