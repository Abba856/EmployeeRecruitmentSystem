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

// Handle form submission for scheduling exams/interviews
if ($_POST && isset($_POST['examTitle'])) {
    $title = trim($_POST['examTitle']);
    $type = trim($_POST['examType']);
    $date = $_POST['examDate'];
    $time = $_POST['examTime'];
    $duration = (int)$_POST['examDuration'];
    $location = trim($_POST['examLocation']);
    $candidates = isset($_POST['candidates']) ? $_POST['candidates'] : array();
    
    // Validate required fields
    if (empty($title) || empty($type) || empty($date) || empty($time) || empty($duration) || empty($location)) {
        $error_message = "All required fields must be filled in.";
    } else {
        // Combine date and time into a single datetime value
        $datetime = $date . ' ' . $time;
        
        // Start transaction for data integrity
        $connection->autocommit(FALSE);
        
        try {
            // Insert the exam/interview schedule
            $insert_schedule_query = "INSERT INTO scheduled_exams (title, type, exam_datetime, duration, location) VALUES (?, ?, ?, ?, ?)";
            $stmt_schedule = $connection->prepare($insert_schedule_query);
            $stmt_schedule->bind_param("sssis", $title, $type, $datetime, $duration, $location);
            
            if (!$stmt_schedule->execute()) {
                throw new Exception("Error scheduling exam: " . $connection->error);
            }
            
            $schedule_id = $connection->insert_id; // Get the newly inserted schedule ID
            
            // Insert candidate associations
            foreach ($candidates as $candidate_id) {
                $insert_assoc_query = "INSERT INTO exam_candidate_assoc (schedule_id, candidate_id) VALUES (?, ?)";
                $stmt_assoc = $connection->prepare($insert_assoc_query);
                $stmt_assoc->bind_param("ii", $schedule_id, $candidate_id);
                
                if (!$stmt_assoc->execute()) {
                    throw new Exception("Error associating candidate: " . $connection->error);
                }
            }
            
            // Commit the transaction
            $connection->commit();
            $success_message = "Exam/Interview scheduled successfully!";
            
        } catch (Exception $e) {
            // Rollback the transaction on error
            $connection->rollback();
            $error_message = $e->getMessage();
        }
        
        // Restore autocommit to true
        $connection->autocommit(TRUE);
    }
}

// Handle deletion of scheduled exams
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    // Start transaction for data integrity
    $connection->autocommit(FALSE);
    
    try {
        // Delete candidate associations first (due to foreign key constraints)
        $delete_assoc_query = "DELETE FROM exam_candidate_assoc WHERE schedule_id = ?";
        $stmt_assoc = $connection->prepare($delete_assoc_query);
        $stmt_assoc->bind_param("i", $delete_id);
        
        if (!$stmt_assoc->execute()) {
            throw new Exception("Error removing candidate associations: " . $connection->error);
        }
        
        // Delete the scheduled exam
        $delete_schedule_query = "DELETE FROM scheduled_exams WHERE schedule_id = ?";
        $stmt_schedule = $connection->prepare($delete_schedule_query);
        $stmt_schedule->bind_param("i", $delete_id);
        
        if (!$stmt_schedule->execute()) {
            throw new Exception("Error deleting scheduled exam: " . $connection->error);
        }
        
        // Commit the transaction
        $connection->commit();
        $success_message = "Scheduled exam/interview deleted successfully!";
        
    } catch (Exception $e) {
        // Rollback the transaction on error
        $connection->rollback();
        $error_message = $e->getMessage();
    }
    
    // Restore autocommit to true
    $connection->autocommit(TRUE);
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
            
            <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
            </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
            </div>
            <?php endif; ?>
            
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Schedule exams and interviews for candidates.
            </div>
            
            <form action="scheduleexam.php" method="POST">
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
                        <option value="final">Final Interview</option>
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
                        <?php
                        // Fetch scheduled exams from database
                        $query = "SELECT se.schedule_id, se.title, se.type, se.exam_datetime, se.duration, se.location, 
                                         GROUP_CONCAT(CONCAT(p.firstname, ' ', p.lastname) SEPARATOR ', ') as candidate_names,
                                         COUNT(eca.candidate_id) as candidate_count
                                  FROM scheduled_exams se
                                  LEFT JOIN exam_candidate_assoc eca ON se.schedule_id = eca.schedule_id
                                  LEFT JOIN personal p ON eca.candidate_id = p.userid
                                  GROUP BY se.schedule_id
                                  ORDER BY se.exam_datetime DESC";
                        $result = $connection->query($query);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $datetime = new DateTime($row['exam_datetime']);
                                $formatted_datetime = $datetime->format('Y-m-d H:i');
                                
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . ucfirst(str_replace('_', ' ', $row['type'])) . "</td>";
                                echo "<td>" . $formatted_datetime . "</td>";
                                echo "<td>" . $row['duration'] . " mins</td>";
                                echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                                echo "<td>" . ($row['candidate_count'] > 0 ? $row['candidate_count'] . " candidates" : "None") . "</td>";
                                echo "<td>";
                                echo "<a href='?delete_id=" . $row['schedule_id'] . "' class='btn btn-danger' style='padding: 5px 10px;' onclick='return confirm(\"Are you sure you want to delete this scheduled exam?\");'><i class='fas fa-trash'></i></a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' style='text-align: center;'>No scheduled exams/interviews found</td></tr>";
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