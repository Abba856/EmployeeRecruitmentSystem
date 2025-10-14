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

// Handle form submission for adding a new candidate
if ($_POST && isset($_POST['action']) && $_POST['action'] == 'add_candidate') {
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
    $password = $_POST['password'];
    $post = trim($_POST['post']);
    $resume = trim($_POST['resume']);  // Added resume field
    $experience = (int)$_POST['experience'];
    $university = trim($_POST['university']);
    $institute = trim($_POST['institute']);
    $branch = trim($_POST['branch']);
    $degree = trim($_POST['degree']);
    $education_status = trim($_POST['status']);  // Changed to avoid conflict with academic status
    $cpi = floatval($_POST['cpi']);
    $semester = (int)$_POST['semester'];

    // Validate required fields
    if (empty($firstname) || empty($lastname) || empty($gender) || empty($birthdate) || 
        empty($state) || empty($city) || empty($pemail) || empty($password) || empty($post) ||
        empty($resume) || empty($university) || empty($institute) || empty($branch) || empty($degree) || 
        empty($education_status) || empty($cpi)) {
        $error_message = "All required fields must be filled in.";
    } elseif ($education_status === 'pursuing' && empty($semester)) {
        $error_message = "Semester is required when status is Pursuing.";
    } elseif ($education_status === 'completed' && empty($experience)) {
        $error_message = "Experience is required when status is Completed.";
    } elseif (empty($error_message)) {
        // Check if email already exists
        $check_query = "SELECT * FROM account WHERE pemail = ?";
        $check_stmt = $connection->prepare($check_query);
        $check_stmt->bind_param("s", $pemail);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error_message = "A candidate with this email already exists.";
        } else {
            // Continue with the database operations
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Start transaction for data integrity
            $connection->autocommit(FALSE);
            
            try {
                // Insert into personal table 
                $insert_personal_query = "INSERT INTO personal (firstname, middlename, lastname, gender, birthdate, state, statespecify, city, cityspecify) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_personal = $connection->prepare($insert_personal_query);
                $stmt_personal->bind_param("sssssssss", $firstname, $middlename, $lastname, $gender, $birthdate, $state, $statespecify, $city, $cityspecify);
                
                if (!$stmt_personal->execute()) {
                    throw new Exception("Error adding personal information: " . $connection->error);
                }
                
                $userid = $connection->insert_id; // Get the actual userid created
                
                // Insert into academic table without userid (auto-increment will create one)
                $insert_academic_query = "INSERT INTO academic (university, institute, branch, degree, status, cpi, semester, experience) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_academic = $connection->prepare($insert_academic_query);
                $stmt_academic->bind_param("sssssdii", $university, $institute, $branch, $degree, $education_status, $cpi, $semester, $experience);
                
                if (!$stmt_academic->execute()) {
                    throw new Exception("Error adding academic information: " . $connection->error);
                }

                // Update the academic table to use the same userid as personal table
                $update_academic_query = "UPDATE academic SET userid = ? WHERE userid = LAST_INSERT_ID()";
                $stmt_update_academic = $connection->prepare($update_academic_query);
                $stmt_update_academic->bind_param("i", $userid);
                if (!$stmt_update_academic->execute()) {
                    throw new Exception("Error updating academic userid: " . $connection->error);
                }

                // Insert into account table using the userid
                $insert_account_query = "INSERT INTO account (pemail, semail, password, post, resume, userid) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_account = $connection->prepare($insert_account_query);
                $stmt_account->bind_param("sssssi", $pemail, $semail, $hashed_password, $post, $resume, $userid);
                
                if (!$stmt_account->execute()) {
                    throw new Exception("Error adding account: " . $connection->error);
                }
                
                // Commit the transaction
                $connection->commit();
                $success_message = "Candidate added successfully!";
                
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

// Handle form submission for editing a candidate
if ($_POST && isset($_POST['action']) && $_POST['action'] == 'edit_candidate') {
    $userid = (int)$_POST['userid'];
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
    $post = trim($_POST['post']);
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

    // Validate required fields
    if (empty($firstname) || empty($lastname) || empty($gender) || empty($birthdate) || 
        empty($state) || empty($city) || empty($pemail) || empty($post) ||
        empty($university) || empty($institute) || empty($branch) || empty($degree) || 
        empty($education_status) || empty($cpi)) {
        $error_message = "All required fields must be filled in.";
    } elseif ($education_status === 'pursuing' && empty($semester)) {
        $error_message = "Semester is required when status is Pursuing.";
    } elseif ($education_status === 'completed' && empty($experience)) {
        $error_message = "Experience is required when status is Completed.";
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
            $stmt_academic->bind_param("sssssdii", $university, $institute, $branch, $degree, $education_status, $cpi, $semester, $experience, $userid);
            
            if (!$stmt_academic->execute()) {
                throw new Exception("Error updating academic information: " . $connection->error);
            }
            
            // Update account table - handle password update if provided
            if (!empty($current_password)) {
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
                    $update_account_query = "UPDATE account SET pemail=?, post=?, password=? WHERE userid=?";
                    $stmt_account = $connection->prepare($update_account_query);
                    $stmt_account->bind_param("sssi", $pemail, $post, $hashed_new_password, $userid);
                } else {
                    throw new Exception("Current password is incorrect.");
                }
            } else {
                // Update without password change
                $update_account_query = "UPDATE account SET pemail=?, post=? WHERE userid=?";
                $stmt_account = $connection->prepare($update_account_query);
                $stmt_account->bind_param("ssi", $pemail, $post, $userid);
            }
            
            if (!$stmt_account->execute()) {
                throw new Exception("Error updating account information: " . $connection->error);
            }
            
            // Commit the transaction
            $connection->commit();
            $success_message = "Candidate updated successfully!";
            
        } catch (Exception $e) {
            // Rollback the transaction on error
            $connection->rollback();
            $error_message = $e->getMessage();
        }
        
        // Restore autocommit to true
        $connection->autocommit(TRUE);
    }
}

// Handle form submission for deleting a candidate
if ($_POST && isset($_POST['action']) && $_POST['action'] == 'delete_candidate') {
    $userid = (int)$_POST['userid'];
    
    // Start transaction for data integrity
    $connection->autocommit(FALSE);
    
    try {
        // Delete from account table (the foreign key will handle cascading if configured)
        $delete_account_query = "DELETE FROM account WHERE userid=?";
        $stmt_account = $connection->prepare($delete_account_query);
        $stmt_account->bind_param("i", $userid);
        
        if (!$stmt_account->execute()) {
            throw new Exception("Error deleting account information: " . $connection->error);
        }
        
        // Delete from academic table
        $delete_academic_query = "DELETE FROM academic WHERE userid=?";
        $stmt_academic = $connection->prepare($delete_academic_query);
        $stmt_academic->bind_param("i", $userid);
        
        if (!$stmt_academic->execute()) {
            throw new Exception("Error deleting academic information: " . $connection->error);
        }
        
        // Delete from personal table
        $delete_personal_query = "DELETE FROM personal WHERE userid=?";
        $stmt_personal = $connection->prepare($delete_personal_query);
        $stmt_personal->bind_param("i", $userid);
        
        if (!$stmt_personal->execute()) {
            throw new Exception("Error deleting personal information: " . $connection->error);
        }
        
        // Commit the transaction
        $connection->commit();
        $success_message = "Candidate deleted successfully!";
        
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
                <i class="fas fa-info-circle"></i> Manage candidate information in the database.
            </div>
            
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <i class="fas fa-plus-circle"></i>
                    <h3>Add New Candidate</h3>
                    <p>Add a new candidate to the database</p>
                    <button class="btn" style="margin-top: 15px;" onclick="openAddCandidateModal()">Add Candidate</button>
                </div>
                
                <div class="dashboard-card">
                    <i class="fas fa-edit"></i>
                    <h3>Edit Candidate</h3>
                    <p>Modify existing candidate information</p>
                    <button class="btn" style="margin-top: 15px;" onclick="showEditInstructions()">Edit Candidate</button>
                </div>
                
                <div class="dashboard-card">
                    <i class="fas fa-trash-alt"></i>
                    <h3>Delete Candidate</h3>
                    <p>Remove candidate from the database</p>
                    <button class="btn btn-danger" style="margin-top: 15px;" onclick="showDeleteInstructions()">Delete Candidate</button>
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
                        $query = "SELECT p.userid, p.firstname, p.middlename, p.lastname, a.pemail, a.post, ac.experience, ac.university, ac.institute, ac.branch, ac.degree, ac.status, ac.cpi, ac.semester, p.gender, p.birthdate, p.state, p.statespecify, p.city, p.cityspecify
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
                                echo "<a href='#' class='btn' style='padding: 5px 10px; margin-right: 5px;' onclick=\"openEditCandidateModal(
                                    " . $row['userid'] . ", 
                                    '" . addslashes(htmlspecialchars($row['firstname'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['middlename'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['lastname'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['gender'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['birthdate'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['state'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['statespecify'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['city'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['cityspecify'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['pemail'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['post'])) . "', 
                                    " . $row['experience'] . ", 
                                    '" . addslashes(htmlspecialchars($row['university'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['institute'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['branch'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['degree'])) . "', 
                                    '" . addslashes(htmlspecialchars($row['status'])) . "', 
                                    " . $row['cpi'] . ", 
                                    " . $row['semester'] . "
                                ); return false;\"><i class='fas fa-edit'></i></a>";
                                echo "<a href='#' class='btn btn-danger' style='padding: 5px 10px;' onclick=\"openDeleteCandidateModal(" . $row['userid'] . ", '" . addslashes(htmlspecialchars($row['firstname'] . " " . $row['middlename'] . " " . $row['lastname'])) . "'); return false;\"><i class='fas fa-trash'></i></a>";
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

    <!-- Edit Candidate Modal -->
    <div id="editCandidateModal" class="modal">
        <div class="modal-content" style="max-width: 800px;">
            <div class="modal-header">
                <h3><i class="fas fa-user-edit"></i> Edit Candidate</h3>
                <span class="close" onclick="closeEditCandidateModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editCandidateForm" action="updatedatabase.php" method="POST">
                    <input type="hidden" name="action" value="edit_candidate">
                    <input type="hidden" id="edit_userid" name="userid" value="">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_firstname">First Name:</label>
                            <input type="text" id="edit_firstname" name="firstname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_middlename">Middle Name:</label>
                            <input type="text" id="edit_middlename" name="middlename" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="edit_lastname">Last Name:</label>
                            <input type="text" id="edit_lastname" name="lastname" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Gender:</label>
                        <div style="display: flex; gap: 20px;">
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="gender" id="edit_gender_male" value="male" required> 
                                <span style="margin-left: 5px;">Male</span>
                            </label>
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="gender" id="edit_gender_female" value="female" required> 
                                <span style="margin-left: 5px;">Female</span>
                            </label>
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="gender" id="edit_gender_other" value="other" required> 
                                <span style="margin-left: 5px;">Other</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_birthdate">Birth Date:</label>
                        <input type="date" id="edit_birthdate" name="birthdate" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_state">State:</label>
                        <select id="edit_state" name="state" class="form-control" onchange="handleEditStateChange()" required>
                            <option value="">Select State</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Goa">Goa</option>
                            <option value="Chandigarh">Chandigarh</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="edit_statespecify-container" style="display: none;">
                        <label for="edit_statespecify">Please Specify State:</label>
                        <input type="text" id="edit_statespecify" name="statespecify" class="form-control" placeholder="Enter your state">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_city">City:</label>
                        <select id="edit_city" name="city" class="form-control" onchange="handleEditCityChange()" required>
                            <option value="">Select City</option>
                            <option value="Ahmedabad">Ahmedabad</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Bangalore">Bangalore</option>
                            <option value="Chennai">Chennai</option>
                            <option value="Agra">Agra</option>
                            <option value="Alleppey">Alleppey</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="edit_cityspecify-container" style="display: none;">
                        <label for="edit_cityspecify">Please Specify City:</label>
                        <input type="text" id="edit_cityspecify" name="cityspecify" class="form-control" placeholder="Enter your city">
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_pemail">Email:</label>
                        <input type="email" id="edit_pemail" name="pemail" class="form-control" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_post">Position Applied For:</label>
                            <select id="edit_post" name="post" class="form-control" required>
                                <option value="">Select Position</option>
                                <option value="Web Developer">Web Developer</option>
                                <option value="Mobile App Developer">Mobile App Developer</option>
                                <option value="DataBase Administrator">DataBase Administrator</option>
                                <option value="Search Engine Optimizer">Search Engine Optimizer</option>
                                <option value="Product Manager">Product Manager</option>
                                <option value="HR Manager">HR Manager</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_experience">Years of Experience:</label>
                            <input type="number" id="edit_experience" name="experience" class="form-control" min="0" max="30">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_university">University:</label>
                        <input type="text" id="edit_university" name="university" class="form-control" placeholder="Enter your university name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_institute">Institute:</label>
                        <input type="text" id="edit_institute" name="institute" class="form-control" placeholder="Enter your institute name" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_branch">Branch:</label>
                            <select id="edit_branch" name="branch" class="form-control" required>
                                <option value="">Select Branch</option>
                                <option value="Information Technology">Information Technology</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Electronics and Communication">Electronics and Communication</option>
                                <option value="Electrical Engineering">Electrical Engineering</option>
                                <option value="Mechanical Engineering">Mechanical Engineering</option>
                                <option value="Civil Engineering">Civil Engineering</option>
                                <option value="Management">Management</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_degree">Degree:</label>
                            <select id="edit_degree" name="degree" class="form-control" required>
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
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Education Status</label>
                        <div style="display: flex; gap: 20px;">
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="status" id="edit_pursuing" value="pursuing" onclick="handleEditStatusChange()"> 
                                <span style="margin-left: 5px;">Pursuing</span>
                            </label>
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="status" id="edit_completed" value="completed" onclick="handleEditStatusChange()"> 
                                <span style="margin-left: 5px;">Completed</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_cpi">CPI/CGPA:</label>
                            <input type="number" id="edit_cpi" name="cpi" class="form-control" step="0.01" min="0" max="10" placeholder="Enter your CPI/CGPA" required>
                        </div>
                        <div class="form-group">
                            <span id="edit_semester1">
                                <label for="edit_semester">Semester:</label>
                                <input type="number" id="edit_semester" name="semester" class="form-control" min="1" max="12" placeholder="Enter current semester">
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_experience2">Years of Experience:</label>
                            <input type="number" id="edit_experience2" name="experience" class="form-control" min="0" max="30" placeholder="Enter experience in years">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="current_password">Current Password (if changing):</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter current password to change password">
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password (if changing):</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn">Update Candidate</button>
                        <button type="button" class="btn btn-secondary" onclick="closeEditCandidateModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="deleteCandidateModal" class="modal">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h3>
                <span class="close" onclick="closeDeleteCandidateModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete candidate <strong id="candidateName"></strong>?</p>
                <p>This action cannot be undone and will permanently remove all candidate data.</p>
                
                <form id="deleteCandidateForm" action="updatedatabase.php" method="POST">
                    <input type="hidden" name="action" value="delete_candidate">
                    <input type="hidden" id="delete_userid" name="userid" value="">
                    
                    <div class="form-actions" style="text-align: right; margin-top: 20px;">
                        <button type="button" class="btn btn-secondary" onclick="closeDeleteCandidateModal()" style="margin-right: 10px;">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Candidate</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Add Candidate Modal -->
    <div id="addCandidateModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-user-plus"></i> Add New Candidate</h3>
                <span class="close" onclick="closeAddCandidateModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="addCandidateForm" method="POST">
                    <input type="hidden" name="action" value="add_candidate">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstname">First Name:</label>
                            <input type="text" id="firstname" name="firstname" required>
                        </div>
                        <div class="form-group">
                            <label for="middlename">Middle Name:</label>
                            <input type="text" id="middlename" name="middlename">
                        </div>
                        <div class="form-group">
                            <label for="lastname">Last Name:</label>
                            <input type="text" id="lastname" name="lastname" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="pemail">Primary Email:</label>
                        <input type="email" id="pemail" name="pemail" class="form-control" placeholder="Enter your primary email - will be used for login" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="semail">Secondary Email:</label>
                        <input type="email" id="semail" name="semail" class="form-control" placeholder="Enter your secondary email - can be used for account recovery">
                    </div>
                    
                    <div class="form-group">
                        <label for="post">Position Applying For:</label>
                        <select id="post" name="post" class="form-control" required>
                            <option value="">Select Position</option>
                            <option value="Web Developer">Web Developer</option>
                            <option value="Mobile App Developer">Mobile App Developer</option>
                            <option value="DataBase Administrator">DataBase Administrator</option>
                            <option value="Search Engine Optimizer">Search Engine Optimizer</option>
                            <option value="Product Manager">Product Manager</option>
                            <option value="HR Manager">HR Manager</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="resume">Resume File:</label>
                        <input type="file" id="resume" name="resume" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="birthdate">Birth Date:</label>
                        <input type="date" id="birthdate" name="birthdate" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="state">State:</label>
                        <select id="state" name="state" class="form-control" required>
                            <option value="">Select State</option>
                            <option value="Gujarat">Gujarat</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Goa">Goa</option>
                            <option value="Chandigarh">Chandigarh</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="statespecify-container" style="display: none;">
                        <label for="statespecify">Please Specify State:</label>
                        <input type="text" id="statespecify" name="statespecify" class="form-control" placeholder="Enter your state">
                    </div>
                    
                    <div class="form-group">
                        <label for="city">City:</label>
                        <select id="city" name="city" class="form-control" required>
                            <option value="">Select City</option>
                            <option value="Ahmedabad">Ahmedabad</option>
                            <option value="Mumbai">Mumbai</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Bangalore">Bangalore</option>
                            <option value="Chennai">Chennai</option>
                            <option value="Agra">Agra</option>
                            <option value="Alleppey">Alleppey</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="cityspecify-container" style="display: none;">
                        <label for="cityspecify">Please Specify City:</label>
                        <input type="text" id="cityspecify" name="cityspecify" class="form-control" placeholder="Enter your city">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="post">Position Applied For:</label>
                            <select id="post" name="post" class="form-control" required>
                                <option value="">Select Position</option>
                                <option value="Web Developer">Web Developer</option>
                                <option value="Mobile App Developer">Mobile App Developer</option>
                                <option value="DataBase Administrator">DataBase Administrator</option>
                                <option value="Search Engine Optimizer">Search Engine Optimizer</option>
                                <option value="Product Manager">Product Manager</option>
                                <option value="HR Manager">HR Manager</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="experience">Years of Experience:</label>
                            <input type="number" id="experience" name="experience" min="0" max="30" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="university">University:</label>
                        <input type="text" id="university" name="university" class="form-control" placeholder="Enter your university name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="institute">Institute:</label>
                        <input type="text" id="institute" name="institute" class="form-control" placeholder="Enter your institute name" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="branch">Branch:</label>
                            <select id="branch" name="branch" class="form-control" required>
                                <option value="">Select Branch</option>
                                <option value="Information Technology">Information Technology</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Electronics and Communication">Electronics and Communication</option>
                                <option value="Electrical Engineering">Electrical Engineering</option>
                                <option value="Mechanical Engineering">Mechanical Engineering</option>
                                <option value="Civil Engineering">Civil Engineering</option>
                                <option value="Management">Management</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="degree">Degree:</label>
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
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Education Status</label>
                        <div style="display: flex; gap: 20px;">
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="status" value="pursuing" id="pursuing" required> 
                                <span style="margin-left: 5px;">Pursuing</span>
                            </label>
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="status" value="completed" id="completed" required> 
                                <span style="margin-left: 5px;">Completed</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cpi">CPI/CGPA:</label>
                            <input type="number" id="cpi" name="cpi" class="form-control" step="0.01" min="0" max="10" placeholder="Enter your CPI/CGPA" required>
                        </div>
                        <div class="form-group">
                            <span id="semester1">
                                <label for="semester">Semester:</label>
                                <input type="number" id="semester" name="semester" class="form-control" min="1" max="12" placeholder="Enter current semester" required>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <span id="semester2">
                                <label for="experience_completed">Experience:</label>
                            </span>
                        </div>
                        <div class="form-group">
                            <span id="semester3">
                                <input type="number" id="experience" name="experience" class="form-control" min="0" max="30" placeholder="Enter experience in years">
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn">Add Candidate</button>
                        <button type="button" class="btn btn-secondary" onclick="closeAddCandidateModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p>&copy; 2025 Employee Recruitment System. All rights reserved.</p>
        </div>
    </div>

    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: #fff;
            margin: 2% auto;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #f5f5f5;
            border-bottom: 1px solid #ddd;
            border-radius: 8px 8px 0 0;
        }
        
        .modal-header h3 {
            margin: 0;
            color: #333;
        }
        
        .close {
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }
        
        .close:hover {
            color: #000;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        
        .form-actions {
            text-align: right;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .btn-secondary {
            background-color: #6c757d !important;
            margin-left: 10px;
        }
        
        .alert-success {
            background-color: #d4edda !important;
            color: #155724 !important;
            border-color: #c3e6cb !important;
        }
        
        .alert-error {
            background-color: #f8d7da !important;
            color: #721c24 !important;
            border-color: #f5c6cb !important;
        }
    </style>

    <script>
        // Function to open the add candidate modal
        function openAddCandidateModal() {
            document.getElementById('addCandidateModal').style.display = 'block';
        }
        
        // Function to close the add candidate modal
        function closeAddCandidateModal() {
            document.getElementById('addCandidateModal').style.display = 'none';
            // Reset the form when closing
            document.getElementById('addCandidateForm').reset();
            // Clear any previous messages
            var successAlert = document.querySelector('.alert-success');
            var errorAlert = document.querySelector('.alert-error');
            if (successAlert) successAlert.style.display = 'none';
            if (errorAlert) errorAlert.style.display = 'none';
            
            // Hide specify containers
            document.getElementById('statespecify-container').style.display = 'none';
            document.getElementById('cityspecify-container').style.display = 'none';
        }
        
        // Close modal if clicked outside the content
        window.onclick = function(event) {
            var modal = document.getElementById('addCandidateModal');
            if (event.target == modal) {
                closeAddCandidateModal();
            }
        }
        
        // Handle form submission with AJAX
        document.getElementById('addCandidateForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent normal form submission
            
            var formData = new FormData(this);
            
            // Show loading state
            var submitBtn = this.querySelector('button[type="submit"]');
            var originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            submitBtn.disabled = true;
            
            // Send AJAX request
            fetch('updatedatabase.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Reload the page to show the results
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the candidate. Please try again.');
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
        
        // Add event listeners for state and city dropdowns
        document.getElementById('state').addEventListener('change', function() {
            const specifyContainer = document.getElementById('statespecify-container');
            if (this.value === 'Other') {
                specifyContainer.style.display = 'block';
            } else {
                specifyContainer.style.display = 'none';
            }
        });
        
        document.getElementById('city').addEventListener('change', function() {
            const specifyContainer = document.getElementById('cityspecify-container');
            if (this.value === 'Other') {
                specifyContainer.style.display = 'block';
            } else {
                specifyContainer.style.display = 'none';
            }
        });
        
        // Add event listeners for education status radio buttons (similar to register2.js)
        window.onload = function() {
            // Initially hide the semester and experience fields appropriately
            document.getElementById('semester1').style.display = 'none';
            document.getElementById('semester2').style.display = 'none';
            document.getElementById('semester3').style.display = 'none';
            
            // Add change event for "pursuing" radio button
            document.getElementById('pursuing').addEventListener('change', function() {
                document.getElementById('semester1').style.display = 'block';
                document.getElementById('semester2').style.display = 'none';
                document.getElementById('semester3').style.display = 'none';
            });
            
            // Add change event for "completed" radio button
            document.getElementById('completed').addEventListener('change', function() {
                document.getElementById('semester1').style.display = 'none';
                document.getElementById('semester2').style.display = 'block';
                document.getElementById('semester3').style.display = 'block';
            });
        };
        
        // Function to open the edit candidate modal
        function openEditCandidateModal(userid, firstname, middlename, lastname, gender, 
            birthdate, state, statespecify, city, cityspecify, pemail, post, experience, 
            university, institute, branch, degree, education_status, cpi, semester) {
            
            document.getElementById('editCandidateModal').style.display = 'block';
            
            // Set form field values
            document.getElementById('edit_userid').value = userid;
            document.getElementById('edit_firstname').value = firstname;
            document.getElementById('edit_middlename').value = middlename || '';
            document.getElementById('edit_lastname').value = lastname;
            document.querySelector(`input[name="edit_gender"][value="${gender}"]`).checked = true;
            document.getElementById('edit_birthdate').value = birthdate;
            document.getElementById('edit_state').value = state;
            document.getElementById('edit_statespecify').value = statespecify || '';
            
            // Show/hide state specify field if needed
            const stateSpecifyContainer = document.getElementById('edit_statespecify-container');
            if (state === 'Other') {
                stateSpecifyContainer.style.display = 'block';
            } else {
                stateSpecifyContainer.style.display = 'none';
            }
            
            document.getElementById('edit_city').value = city;
            document.getElementById('edit_cityspecify').value = cityspecify || '';
            
            // Show/hide city specify field if needed
            const citySpecifyContainer = document.getElementById('edit_cityspecify-container');
            if (city === 'Other') {
                citySpecifyContainer.style.display = 'block';
            } else {
                citySpecifyContainer.style.display = 'none';
            }
            
            document.getElementById('edit_pemail').value = pemail;
            document.getElementById('edit_post').value = post;
            document.getElementById('edit_experience').value = experience;
            document.getElementById('edit_university').value = university;
            document.getElementById('edit_institute').value = institute;
            document.getElementById('edit_branch').value = branch;
            document.getElementById('edit_degree').value = degree;
            
            // Select the education status radio button
            document.querySelector(`input[name="edit_status"][value="${education_status}"]`).checked = true;
            
            // Show/hide fields based on education status
            if (education_status === 'pursuing') {
                document.getElementById('edit_semester1').style.display = 'block';
                document.getElementById('edit_semester2').style.display = 'none';
                document.getElementById('edit_semester3').style.display = 'none';
            } else {
                document.getElementById('edit_semester1').style.display = 'none';
                document.getElementById('edit_semester2').style.display = 'block';
                document.getElementById('edit_semester3').style.display = 'block';
            }
            
            document.getElementById('edit_cpi').value = cpi;
            document.getElementById('edit_semester').value = semester;
            
            // Set experience field based on education status
            if (education_status === 'completed') {
                document.getElementById('edit_experience').value = experience || 0;
                document.getElementById('edit_experience_completed').value = experience || 0;
            } else {
                document.getElementById('edit_experience').value = experience || 0;
            }
        }
        
        // Function to close the edit candidate modal
        function closeEditCandidateModal() {
            document.getElementById('editCandidateModal').style.display = 'none';
        }
        
        // Function to open the delete confirmation modal
        function openDeleteCandidateModal(userid, name) {
            document.getElementById('delete_userid').value = userid;
            document.getElementById('candidateName').textContent = name;
            document.getElementById('deleteCandidateModal').style.display = 'block';
        }
        
        // Function to close the delete confirmation modal
        function closeDeleteCandidateModal() {
            document.getElementById('deleteCandidateModal').style.display = 'none';
        }
        
        // Function to handle state change in edit form
        function handleEditStateChange() {
            const state = document.getElementById('edit_state').value;
            const specifyContainer = document.getElementById('edit_statespecify-container');
            if (state === 'Other') {
                specifyContainer.style.display = 'block';
            } else {
                specifyContainer.style.display = 'none';
            }
        }
        
        // Function to handle city change in edit form
        function handleEditCityChange() {
            const city = document.getElementById('edit_city').value;
            const specifyContainer = document.getElementById('edit_cityspecify-container');
            if (city === 'Other') {
                specifyContainer.style.display = 'block';
            } else {
                specifyContainer.style.display = 'none';
            }
        }
        
        // Add event listeners for edit form education status
        function handleEditStatusChange() {
            const status = document.querySelector('input[name="edit_status"]:checked').value;
            if (status === 'pursuing') {
                document.getElementById('edit_semester1').style.display = 'block';
                document.getElementById('edit_semester2').style.display = 'none';
                document.getElementById('edit_semester3').style.display = 'none';
            } else {
                document.getElementById('edit_semester1').style.display = 'none';
                document.getElementById('edit_semester2').style.display = 'block';
                document.getElementById('edit_semester3').style.display = 'block';
            }
        }
        
        // Show instructions for editing candidates
        function showEditInstructions() {
            alert('To edit a candidate, click the edit button (pencil icon) in the candidate list below.');
        }
        
        // Show instructions for deleting candidates
        function showDeleteInstructions() {
            alert('To delete a candidate, click the delete button (trash icon) in the candidate list below.');
        }
    </script>
</body>
</html>