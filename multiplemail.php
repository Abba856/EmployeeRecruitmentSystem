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
    <title>Send Email to Multiple Candidates | Employee Recruitment System</title>
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
                    <li><a href="multiplemail.php" class="active">Send Emails</a></li>
                    <li><a href="seefeedback.php">View Feedback</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-envelope"></i> Send Email to Multiple Candidates</h2>
            </div>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Send emails to multiple candidates at once.
            </div>
            
            <form action="#" method="POST">
                <div class="form-group">
                    <label for="emailType" class="form-label">Email Type</label>
                    <select id="emailType" name="emailType" class="form-control" required>
                        <option value="">Select Email Type</option>
                        <option value="interview">Interview Invitation</option>
                        <option value="exam">Exam Notification</option>
                        <option value="result">Result Announcement</option>
                        <option value="custom">Custom Message</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="candidates" class="form-label">Select Candidates</label>
                    <select id="candidates" name="candidates[]" class="form-control" multiple size="8" required>
                        <?php
                        // Fetch candidates from database
                        $query = "SELECT p.userid, p.firstname, p.middlename, p.lastname, a.pemail, a.post 
                                 FROM personal p 
                                 JOIN account a ON p.userid = a.userid 
                                 ORDER BY p.firstname";
                        $result = $connection->query($query);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['pemail'] . "'>" . 
                                     htmlspecialchars($row['firstname'] . " " . $row['middlename'] . " " . $row['lastname'] . " (" . $row['pemail'] . " - " . $row['post'] . ")") . 
                                     "</option>";
                            }
                        }
                        ?>
                    </select>
                    <small>Hold Ctrl/Cmd to select multiple candidates</small>
                </div>
                
                <div id="customFields" style="display: none;">
                    <div class="form-group">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter email subject">
                    </div>
                    
                    <div class="form-group">
                        <label for="message" class="form-label">Message</label>
                        <textarea id="message" name="message" class="form-control" rows="6" placeholder="Enter your message"></textarea>
                    </div>
                </div>
                
                <div id="templateFields" style="display: none;">
                    <div class="form-group">
                        <label for="examDate" class="form-label">Exam Date</label>
                        <input type="date" id="examDate" name="examDate" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="examTime" class="form-label">Exam Time</label>
                        <input type="time" id="examTime" name="examTime" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="examLocation" class="form-label">Location/Venue</label>
                        <input type="text" id="examLocation" name="examLocation" class="form-control" placeholder="Enter location or virtual meeting link">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-block">
                    <i class="fas fa-paper-plane"></i> Send Emails
                </button>
            </form>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-history"></i> Email History</h2>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Recipients</th>
                            <th>Subject</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2025-09-01 10:30</td>
                            <td>Interview Invitation</td>
                            <td>15 candidates</td>
                            <td>Interview Schedule for Web Developer Position</td>
                            <td><span style="color: green;">Sent</span></td>
                        </tr>
                        <tr>
                            <td>2025-08-28 14:15</td>
                            <td>Exam Notification</td>
                            <td>22 candidates</td>
                            <td>Technical Assessment Exam</td>
                            <td><span style="color: green;">Sent</span></td>
                        </tr>
                        <tr>
                            <td>2025-08-25 09:45</td>
                            <td>Result Announcement</td>
                            <td>8 candidates</td>
                            <td>Results for Mobile App Developer Position</td>
                            <td><span style="color: green;">Sent</span></td>
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

    <script>
        document.getElementById('emailType').addEventListener('change', function() {
            const customFields = document.getElementById('customFields');
            const templateFields = document.getElementById('templateFields');
            
            if (this.value === 'custom') {
                customFields.style.display = 'block';
                templateFields.style.display = 'none';
            } else if (this.value === 'interview' || this.value === 'exam') {
                customFields.style.display = 'none';
                templateFields.style.display = 'block';
            } else {
                customFields.style.display = 'none';
                templateFields.style.display = 'none';
            }
        });
    </script>
</body>
</html>