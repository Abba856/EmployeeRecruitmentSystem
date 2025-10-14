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
$query = "SELECT * FROM account WHERE pemail=?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$userid = $user_data['userid'];

// Fetch all profile data
$profile_query = "SELECT p.*, a.*, ac.* 
                  FROM personal p 
                  JOIN account a ON p.userid = a.userid 
                  JOIN academic ac ON p.userid = ac.userid 
                  WHERE p.userid = ?";
$profile_stmt = $connection->prepare($profile_query);
$profile_stmt->bind_param("i", $userid);
$profile_stmt->execute();
$profile_result = $profile_stmt->get_result();
$profile = $profile_result->fetch_assoc();

// Handle form submission for updating profile
if ($_POST && isset($_POST['action']) && $_POST['action'] == 'update_profile') {
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $gender = trim($_POST['gender']);
    $birthdate = $_POST['birthdate'];
    $state = trim($_POST['state']);
    $statespecify = isset($_POST['statespecify']) ? trim($_POST['statespecify']) : '';
    $city = trim($_POST['city']);
    $cityspecify = isset($_POST['cityspecify']) ? trim($_POST['cityspecify']) : '';
    $pemail = trim($_POST['pemail']);
    $semail = isset($_POST['semail']) ? trim($_POST['semail']) : '';
    $post = trim($_POST['post']);
    $resume = trim($_POST['resume']);
    $experience = (int)$_POST['experience'];
    $university = trim($_POST['university']);
    $institute = trim($_POST['institute']);
    $branch = trim($_POST['branch']);
    $degree = trim($_POST['degree']);
    $education_status = trim($_POST['status']);
    $cpi = floatval($_POST['cpi']);
    $semester = (int)$_POST['semester'];
    $current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_new_password = isset($_POST['confirm_new_password']) ? $_POST['confirm_new_password'] : '';

    // Validate required fields
    if (empty($firstname) || empty($lastname) || empty($gender) || empty($birthdate) || 
        empty($state) || empty($city) || empty($pemail) || empty($post) ||
        empty($resume) || empty($university) || empty($institute) || empty($branch) || empty($degree) || 
        empty($education_status) || empty($cpi)) {
        $error_message = "All required fields must be filled in.";
    } elseif ($education_status === 'pursuing' && empty($semester)) {
        $error_message = "Semester is required when status is Pursuing.";
    } elseif ($education_status === 'completed' && empty($experience)) {
        $error_message = "Experience is required when status is Completed.";
    } elseif (!empty($new_password) && $new_password !== $confirm_new_password) {
        $error_message = "New password and confirmation do not match.";
    } else {
        // Check if email is already taken by another user
        $email_check_query = "SELECT userid FROM account WHERE pemail=? AND userid!=?";
        $email_check_stmt = $connection->prepare($email_check_query);
        $email_check_stmt->bind_param("si", $pemail, $userid);
        $email_check_stmt->execute();
        $email_check_result = $email_check_stmt->get_result();
        
        if ($email_check_result->num_rows > 0) {
            $error_message = "This email is already in use by another account.";
        } else {
            // Start transaction for data integrity
            $connection->autocommit(FALSE);
            
            try {
                // Update personal table
                $update_personal_query = "UPDATE personal SET firstname=?, middlename=?, lastname=?, gender=?, birthdate=?, state=?, statespecify=?, city=?, cityspecify=? WHERE userid=?";
                $stmt_personal = $connection->prepare($update_personal_query);
                $stmt_personal->bind_param("sssssssssi", $firstname, $middlename, $lastname, $gender, $birthdate, $state, $statespecify, $city, $cityspecify, $userid);
                
                if (!$stmt_personal->execute()) {
                    throw new Exception("Error updating personal information: " . $connection->error);
                }
                
                // Update academic table
                $update_academic_query = "UPDATE academic SET university=?, institute=?, branch=?, degree=?, status=?, cpi=?, semester=?, experience=? WHERE userid=?";
                $stmt_academic = $connection->prepare($update_academic_query);
                $stmt_academic->bind_param("sssssdiii", $university, $institute, $branch, $degree, $education_status, $cpi, $semester, $experience, $userid);
                
                if (!$stmt_academic->execute()) {
                    throw new Exception("Error updating academic information: " . $connection->error);
                }
                
                // Update account table - handle password update if provided
                if (!empty($new_password)) {
                    // Get current password from database
                    $get_password_query = "SELECT password FROM account WHERE userid=?";
                    $get_password_stmt = $connection->prepare($get_password_query);
                    $get_password_stmt->bind_param("i", $userid);
                    $get_password_stmt->execute();
                    $result = $get_password_stmt->get_result();
                    $row = $result->fetch_assoc();
                    
                    if ($row && password_verify($current_password, $row['password'])) {
                        // Current password is correct, update with new password
                        $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $update_account_query = "UPDATE account SET pemail=?, semail=?, post=?, resume=?, password=? WHERE userid=?";
                        $stmt_account = $connection->prepare($update_account_query);
                        $stmt_account->bind_param("sssssi", $pemail, $semail, $post, $resume, $hashed_new_password, $userid);
                    } else {
                        throw new Exception("Current password is incorrect.");
                    }
                } else {
                    // Update account without changing password
                    $update_account_query = "UPDATE account SET pemail=?, semail=?, post=?, resume=? WHERE userid=?";
                    $stmt_account = $connection->prepare($update_account_query);
                    $stmt_account->bind_param("ssssi", $pemail, $semail, $post, $resume, $userid);
                }
                
                if (!$stmt_account->execute()) {
                    throw new Exception("Error updating account information: " . $connection->error);
                }
                
                // Update session email if it changed
                if ($email !== $pemail) {
                    $_SESSION['email'] = $pemail;
                    $email = $pemail; // Update $email variable for display purposes
                }
                
                // Commit the transaction
                $connection->commit();
                $success_message = "Profile updated successfully!";
                
                // Refresh profile data
                $profile_query = "SELECT p.*, a.*, ac.* 
                                  FROM personal p 
                                  JOIN account a ON p.userid = a.userid 
                                  JOIN academic ac ON p.userid = ac.userid 
                                  WHERE p.userid = ?";
                $profile_stmt = $connection->prepare($profile_query);
                $profile_stmt->bind_param("i", $userid);
                $profile_stmt->execute();
                $profile_result = $profile_stmt->get_result();
                $profile = $profile_result->fetch_assoc();
                
            } catch (Exception $e) {
                // Rollback the transaction on error
                $connection->rollback();
                $error_message = $e->getMessage();
            }
            
            // Restore autocommit to true
            $connection->autocommit(TRUE);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Employee Recruitment System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .password-strength {
            margin-top: 5px;
            font-size: 0.875rem;
        }
    </style>
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
                    <li><a href="requirementboard.php">Requirements</a></li>
                    <li><a href="helpandfeedback.php">Support</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-user-edit"></i> Edit Profile</h2>
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
            
            <form id="editProfileForm" method="post" action="editprofile.php">
                <input type="hidden" name="action" value="update_profile">
                
                <h3><i class="fas fa-user"></i> Personal Information</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname" class="form-label">First Name *</label>
                        <input type="text" id="firstname" name="firstname" class="form-control" value="<?php echo htmlspecialchars($profile['firstname']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="middlename" class="form-label">Middle Name</label>
                        <input type="text" id="middlename" name="middlename" class="form-control" value="<?php echo htmlspecialchars($profile['middlename']); ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastname" class="form-label">Last Name *</label>
                        <input type="text" id="lastname" name="lastname" class="form-control" value="<?php echo htmlspecialchars($profile['lastname']); ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Gender *</label>
                        <div style="display: flex; gap: 20px; margin-top: 8px;">
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="gender" value="male" <?php echo ($profile['gender'] == 'male') ? 'checked' : ''; ?> required style="margin-right: 5px;"> 
                                <span>Male</span>
                            </label>
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="gender" value="female" <?php echo ($profile['gender'] == 'female') ? 'checked' : ''; ?> required style="margin-right: 5px;"> 
                                <span>Female</span>
                            </label>
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="gender" value="other" <?php echo ($profile['gender'] == 'other') ? 'checked' : ''; ?> required style="margin-right: 5px;"> 
                                <span>Other</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="birthdate" class="form-label">Birth Date *</label>
                        <input type="date" id="birthdate" name="birthdate" class="form-control" value="<?php echo htmlspecialchars($profile['birthdate']); ?>" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="state" class="form-label">State *</label>
                        <select id="state" name="state" class="form-control" onchange="handleStateChange()" required>
                            <option value="">Select State</option>
                            <option value="Gujarat" <?php echo ($profile['state'] == 'Gujarat') ? 'selected' : ''; ?>>Gujarat</option>
                            <option value="Maharashtra" <?php echo ($profile['state'] == 'Maharashtra') ? 'selected' : ''; ?>>Maharashtra</option>
                            <option value="Delhi" <?php echo ($profile['state'] == 'Delhi') ? 'selected' : ''; ?>>Delhi</option>
                            <option value="Karnataka" <?php echo ($profile['state'] == 'Karnataka') ? 'selected' : ''; ?>>Karnataka</option>
                            <option value="Tamil Nadu" <?php echo ($profile['state'] == 'Tamil Nadu') ? 'selected' : ''; ?>>Tamil Nadu</option>
                            <option value="Goa" <?php echo ($profile['state'] == 'Goa') ? 'selected' : ''; ?>>Goa</option>
                            <option value="Chandigarh" <?php echo ($profile['state'] == 'Chandigarh') ? 'selected' : ''; ?>>Chandigarh</option>
                            <option value="Other" <?php echo ($profile['state'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="statespecify-container" style="display: <?php echo ($profile['state'] == 'Other') ? 'block' : 'none'; ?>;">
                        <label for="statespecify" class="form-label">Please Specify State</label>
                        <input type="text" id="statespecify" name="statespecify" class="form-control" value="<?php echo htmlspecialchars($profile['statespecify']); ?>" placeholder="Enter your state">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="city" class="form-label">City *</label>
                        <select id="city" name="city" class="form-control" onchange="handleCityChange()" required>
                            <option value="">Select City</option>
                            <option value="Ahmedabad" <?php echo ($profile['city'] == 'Ahmedabad') ? 'selected' : ''; ?>>Ahmedabad</option>
                            <option value="Mumbai" <?php echo ($profile['city'] == 'Mumbai') ? 'selected' : ''; ?>>Mumbai</option>
                            <option value="Delhi" <?php echo ($profile['city'] == 'Delhi') ? 'selected' : ''; ?>>Delhi</option>
                            <option value="Bangalore" <?php echo ($profile['city'] == 'Bangalore') ? 'selected' : ''; ?>>Bangalore</option>
                            <option value="Chennai" <?php echo ($profile['city'] == 'Chennai') ? 'selected' : ''; ?>>Chennai</option>
                            <option value="Agra" <?php echo ($profile['city'] == 'Agra') ? 'selected' : ''; ?>>Agra</option>
                            <option value="Alleppey" <?php echo ($profile['city'] == 'Alleppey') ? 'selected' : ''; ?>>Alleppey</option>
                            <option value="Other" <?php echo ($profile['city'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="cityspecify-container" style="display: <?php echo ($profile['city'] == 'Other') ? 'block' : 'none'; ?>;">
                        <label for="cityspecify" class="form-label">Please Specify City</label>
                        <input type="text" id="cityspecify" name="cityspecify" class="form-control" value="<?php echo htmlspecialchars($profile['cityspecify']); ?>" placeholder="Enter your city">
                    </div>
                </div>
                
                <h3><i class="fas fa-graduation-cap"></i> Academic Information</h3>
                
                <div class="form-group">
                    <label for="university" class="form-label">University *</label>
                    <input type="text" id="university" name="university" class="form-control" value="<?php echo htmlspecialchars($profile['university']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="institute" class="form-label">Institute *</label>
                    <input type="text" id="institute" name="institute" class="form-control" value="<?php echo htmlspecialchars($profile['institute']); ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="branch" class="form-label">Branch *</label>
                        <select id="branch" name="branch" class="form-control" required>
                            <option value="">Select Branch</option>
                            <option value="Information Technology" <?php echo ($profile['branch'] == 'Information Technology') ? 'selected' : ''; ?>>Information Technology</option>
                            <option value="Computer Science" <?php echo ($profile['branch'] == 'Computer Science') ? 'selected' : ''; ?>>Computer Science</option>
                            <option value="Electronics and Communication" <?php echo ($profile['branch'] == 'Electronics and Communication') ? 'selected' : ''; ?>>Electronics and Communication</option>
                            <option value="Electrical Engineering" <?php echo ($profile['branch'] == 'Electrical Engineering') ? 'selected' : ''; ?>>Electrical Engineering</option>
                            <option value="Mechanical Engineering" <?php echo ($profile['branch'] == 'Mechanical Engineering') ? 'selected' : ''; ?>>Mechanical Engineering</option>
                            <option value="Civil Engineering" <?php echo ($profile['branch'] == 'Civil Engineering') ? 'selected' : ''; ?>>Civil Engineering</option>
                            <option value="Management" <?php echo ($profile['branch'] == 'Management') ? 'selected' : ''; ?>>Management</option>
                            <option value="Other" <?php echo ($profile['branch'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="degree" class="form-label">Degree *</label>
                        <select id="degree" name="degree" class="form-control" required>
                            <option value="">Select Degree</option>
                            <option value="B.E/B.Tech" <?php echo ($profile['degree'] == 'B.E/B.Tech') ? 'selected' : ''; ?>>B.E/B.Tech</option>
                            <option value="M.E/M.Tech" <?php echo ($profile['degree'] == 'M.E/M.Tech') ? 'selected' : ''; ?>>M.E/M.Tech</option>
                            <option value="B.Sc" <?php echo ($profile['degree'] == 'B.Sc') ? 'selected' : ''; ?>>B.Sc</option>
                            <option value="M.Sc" <?php echo ($profile['degree'] == 'M.Sc') ? 'selected' : ''; ?>>M.Sc</option>
                            <option value="B.Com" <?php echo ($profile['degree'] == 'B.Com') ? 'selected' : ''; ?>>B.Com</option>
                            <option value="M.Com" <?php echo ($profile['degree'] == 'M.Com') ? 'selected' : ''; ?>>M.Com</option>
                            <option value="B.A" <?php echo ($profile['degree'] == 'B.A') ? 'selected' : ''; ?>>B.A</option>
                            <option value="M.A" <?php echo ($profile['degree'] == 'M.A') ? 'selected' : ''; ?>>M.A</option>
                            <option value="MBA" <?php echo ($profile['degree'] == 'MBA') ? 'selected' : ''; ?>>MBA</option>
                            <option value="Other" <?php echo ($profile['degree'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Education Status *</label>
                        <div style="display: flex; gap: 20px; margin-top: 8px;">
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="status" value="pursuing" <?php echo ($profile['status'] == 'pursuing') ? 'checked' : ''; ?> required onchange="toggleSemesterExperience()" style="margin-right: 5px;"> 
                                <span>Pursuing</span>
                            </label>
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="status" value="completed" <?php echo ($profile['status'] == 'completed') ? 'checked' : ''; ?> required onchange="toggleSemesterExperience()" style="margin-right: 5px;"> 
                                <span>Completed</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group" id="semester-group" style="display: <?php echo ($profile['status'] == 'pursuing') ? 'block' : 'none'; ?>;">
                        <label for="semester" class="form-label">Semester *</label>
                        <input type="number" id="semester" name="semester" class="form-control" min="1" max="12" value="<?php echo htmlspecialchars($profile['semester']); ?>" placeholder="Enter current semester">
                    </div>
                    
                    <div class="form-group" id="experience-group" style="display: <?php echo ($profile['status'] == 'completed') ? 'block' : 'none'; ?>;">
                        <label for="experience" class="form-label">Experience (years) *</label>
                        <input type="number" id="experience" name="experience" class="form-control" min="0" max="30" value="<?php echo htmlspecialchars($profile['experience']); ?>" placeholder="Enter experience in years">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="cpi" class="form-label">CPI/CGPA *</label>
                    <input type="number" id="cpi" name="cpi" class="form-control" step="0.01" min="0" max="10" value="<?php echo htmlspecialchars($profile['cpi']); ?>" placeholder="Enter your CPI/CGPA" required>
                </div>
                
                <h3><i class="fas fa-lock"></i> Account Information</h3>
                
                <div class="form-group">
                    <label for="post" class="form-label">Position Applying For *</label>
                    <select id="post" name="post" class="form-control" required>
                        <option value="">Select Position</option>
                        <option value="Web Developer" <?php echo ($profile['post'] == 'Web Developer') ? 'selected' : ''; ?>>Web Developer</option>
                        <option value="Mobile App Developer" <?php echo ($profile['post'] == 'Mobile App Developer') ? 'selected' : ''; ?>>Mobile App Developer</option>
                        <option value="DataBase Administrator" <?php echo ($profile['post'] == 'DataBase Administrator') ? 'selected' : ''; ?>>DataBase Administrator</option>
                        <option value="Search Engine Optimizer" <?php echo ($profile['post'] == 'Search Engine Optimizer') ? 'selected' : ''; ?>>Search Engine Optimizer</option>
                        <option value="Product Manager" <?php echo ($profile['post'] == 'Product Manager') ? 'selected' : ''; ?>>Product Manager</option>
                        <option value="HR Manager" <?php echo ($profile['post'] == 'HR Manager') ? 'selected' : ''; ?>>HR Manager</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="resume" class="form-label">Resume File *</label>
                    <input type="file" id="resume" name="resume" class="form-control" value="<?php echo htmlspecialchars($profile['resume']); ?>" placeholder="Enter your resume file name" required>
                </div>
                
                <div class="form-group">
                    <label for="pemail" class="form-label">Primary Email *</label>
                    <input type="email" id="pemail" name="pemail" class="form-control" value="<?php echo htmlspecialchars($profile['pemail']); ?>" placeholder="Enter your primary email - will be used for login" required>
                </div>
                
                <div class="form-group">
                    <label for="semail" class="form-label">Secondary Email</label>
                    <input type="email" id="semail" name="semail" class="form-control" value="<?php echo htmlspecialchars($profile['semail']); ?>" placeholder="Enter your secondary email - can be used for account recovery">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password (to change password)</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter current password to change password" autocomplete="off">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" autocomplete="off">
                        <div id="password-message" class="password-strength"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_new_password" class="form-label">Confirm New Password</label>
                        <input type="password" id="confirm_new_password" name="confirm_new_password" class="form-control" placeholder="Confirm new password" autocomplete="off">
                        <div id="confirm-message" class="password-strength"></div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="viewprofile.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Profile
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p>&copy; 2025 Employee Recruitment System. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Function to handle state change
        function handleStateChange() {
            const state = document.getElementById('state').value;
            const specifyContainer = document.getElementById('statespecify-container');
            if (state === 'Other') {
                specifyContainer.style.display = 'block';
            } else {
                specifyContainer.style.display = 'none';
            }
        }
        
        // Function to handle city change
        function handleCityChange() {
            const city = document.getElementById('city').value;
            const specifyContainer = document.getElementById('cityspecify-container');
            if (city === 'Other') {
                specifyContainer.style.display = 'block';
            } else {
                specifyContainer.style.display = 'none';
            }
        }
        
        // Function to toggle semester/experience based on education status
        function toggleSemesterExperience() {
            const status = document.querySelector('input[name="status"]:checked').value;
            const semesterGroup = document.getElementById('semester-group');
            const experienceGroup = document.getElementById('experience-group');
            
            if (status === 'pursuing') {
                semesterGroup.style.display = 'block';
                experienceGroup.style.display = 'none';
            } else {
                semesterGroup.style.display = 'none';
                experienceGroup.style.display = 'block';
            }
        }
        
        // Password matching functionality
        function checkPasswordMatch() {
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_new_password');
            const confirmMessage = document.getElementById('confirm-message');
            
            if (newPassword.value === confirmPassword.value && newPassword.value.length > 0) {
                confirmMessage.innerHTML = '<span style="color: green;">Passwords match!</span>';
            } else if (confirmPassword.value.length > 0) {
                confirmMessage.innerHTML = '<span style="color: red;">Passwords do not match!</span>';
            } else {
                confirmMessage.innerHTML = '';
            }
        }

        // Basic password strength indicator
        function checkPasswordStrength() {
            const password = document.getElementById('new_password');
            const message = document.getElementById('password-message');
            const pass = password.value;

            if (pass.length > 0 && pass.length < 8) {
                message.innerHTML = '<span style="color: red;">Password must be at least 8 characters long</span>';
            } else if (pass.length >= 8) {
                message.innerHTML = '<span style="color: green;">Good password length</span>';
            } else {
                message.innerHTML = '';
            }
            
            // Recheck password match when password changes
            if (document.getElementById('confirm_new_password').value.length > 0) {
                checkPasswordMatch();
            }
        }

        // Add event listeners when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const newPassword = document.getElementById('new_password');
            const confirmPassword = document.getElementById('confirm_new_password');
            
            if (newPassword) {
                newPassword.addEventListener('input', checkPasswordStrength);
            }
            
            if (confirmPassword) {
                confirmPassword.addEventListener('input', checkPasswordMatch);
            }
            
            // Initialize semester/experience visibility based on current selection
            const status = document.querySelector('input[name="status"]:checked').value;
            toggleSemesterExperience();
        });
    </script>
</body>
</html>