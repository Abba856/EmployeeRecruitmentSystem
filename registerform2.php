<?php
session_start();

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['email'])) {
    header("Location: myaccount.php");
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the form data
    require('connect.php');
        
    $university = $_POST['university'];
    $institute = $_POST['institute'];
    $branch = $_POST['branch'];
    $degree = $_POST['degree'];
    $status = $_POST['rad1'];
    $cpi = $_POST['cpi'];
    $semester = $_POST['semester'];
    $experience = $_POST['experience'];
      
    // Insert into academic table without userid (auto-increment will create one)
    $query = "INSERT INTO `academic` (university,institute,branch,degree,status,cpi,semester,experience) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("sssssdii", $university, $institute, $branch, $degree, $status, $cpi, $semester, $experience);
    $result = $stmt->execute();
    
    if ($result) {
        // Update the academic table to match the userid from the personal table
        // First get the latest personal userid
        $get_userid_query = "SELECT userid FROM personal ORDER BY userid DESC LIMIT 1";
        $get_userid_result = $connection->query($get_userid_query);
        if ($get_userid_result->num_rows > 0) {
            $row = $get_userid_result->fetch_assoc();
            $userid = $row['userid'];
            
            // Update academic table to use the same userid as personal
            $update_query = "UPDATE academic SET userid = ? WHERE userid = LAST_INSERT_ID()";
            $update_stmt = $connection->prepare($update_query);
            $update_stmt->bind_param("i", $userid);
            $result = $update_stmt->execute();
        } else {
            // If no personal record found, registration flow is broken
            $error = "Registration flow error. Please start from the beginning.";
            $result = false;
        }
    }
    
    if($result) {
        $url = "register3.php";
        header("Location: " . $url);
        exit();
    } else {
        $error = "Registration failed. Please try again.";
    }
}

// Display the form
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Academic Information | Employee Recruitment System</title>
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
                    <li><a href="login.php">Login</a></li>
                    <li><a href="registerform1.php">Register</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-graduation-cap"></i> Registration - Step 2: Academic Information</h2>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div style="display: flex; justify-content: center; margin-bottom: 20px;">
                <div style="display: flex; align-items: center;">
                    <div style="width: 30px; height: 30px; background: var(--light-gray); color: var(--dark-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">1</div>
                    <div style="width: 50px; height: 2px; background: var(--primary-color); margin: 0 10px;"></div>
                    <div style="width: 30px; height: 30px; background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">2</div>
                    <div style="width: 50px; height: 2px; background: var(--light-gray); margin: 0 10px;"></div>
                    <div style="width: 30px; height: 30px; background: var(--light-gray); color: var(--dark-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">3</div>
                </div>
            </div>
            
            <form action="registerform2.php" method="POST">
                <div class="form-group">
                    <label for="university" class="form-label">University</label>
                    <input type="text" id="university" name="university" class="form-control" placeholder="Enter your university name" required>
                </div>
                
                <div class="form-group">
                    <label for="institute" class="form-label">Institute</label>
                    <input type="text" id="institute" name="institute" class="form-control" placeholder="Enter your institute name" required>
                </div>
                
                <div class="form-group">
                    <label for="branch" class="form-label">Branch</label>
                    <input type="text" id="branch" name="branch" class="form-control" placeholder="Enter your branch" required>
                </div>
                
                <div class="form-group">
                    <label for="degree" class="form-label">Degree</label>
                    <select id="degree" name="degree" class="form-control" required>
                        <option value="">Select Degree</option>
                        <option value="B.E/B.Tech">B.E/B.Tech</option>
                        <option value="M.E/M.Tech">M.E/M.Tech</option>
                        <option value="B.Sc">B.Sc</option>
                        <option value="M.Sc">M.Sc</option>
                        <option value="B.Com">B.Com</option>
                        <option value="M.Com">M.Com</option>
                        <option value="B.A">B.A</option>
                        <option value="M.A">M.A</option>
                        <option value="MBA">MBA</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div style="display: flex; gap: 20px;">
                        <label style="display: flex; align-items: center;">
                            <input type="radio" name="rad1" value="pursuing" required> 
                            <span style="margin-left: 5px;">Pursuing</span>
                        </label>
                        <label style="display: flex; align-items: center;">
                            <input type="radio" name="rad1" value="completed" required> 
                            <span style="margin-left: 5px;">Completed</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="cpi" class="form-label">CPI/CGPA</label>
                    <input type="number" id="cpi" name="cpi" class="form-control" step="0.01" min="0" max="10" placeholder="Enter your CPI/CGPA" required>
                </div>
                
                <div class="form-group">
                    <label for="semester" class="form-label">Semester</label>
                    <input type="number" id="semester" name="semester" class="form-control" min="1" max="12" placeholder="Enter current semester" required>
                </div>
                
                <div class="form-group">
                    <label for="experience" class="form-label">Experience (years)</label>
                    <input type="number" id="experience" name="experience" class="form-control" min="0" max="30" placeholder="Enter your experience in years" required>
                </div>
                
                <button type="submit" class="btn btn-block">
                    <i class="fas fa-arrow-right"></i> Continue to Step 3
                </button>
            </form>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p>&copy; 2025 Employee Recruitment System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>