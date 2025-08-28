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
        
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $state = $_POST['state'];
    $statespecify = $_POST['statespecify'];
    $city = $_POST['city'];
    $cityspecify = $_POST['cityspecify'];
      
    // Using prepared statement to prevent SQL injection
    $query = "INSERT INTO `personal` (firstname, middlename, lastname, gender, birthdate, state, statespecify, city, cityspecify) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("sssssssss", $firstname, $middlename, $lastname, $gender, $birthdate, $state, $statespecify, $city, $cityspecify);
    $result = $stmt->execute();
    
    if($result) {
        $url = "register2.php";
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
    <title>Register - Personal Information | Employee Recruitment System</title>
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
                    <li><a href="registerform1.php" class="active">Register</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-user"></i> Registration - Step 1: Personal Information</h2>
            </div>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div style="display: flex; justify-content: center; margin-bottom: 20px;">
                <div style="display: flex; align-items: center;">
                    <div style="width: 30px; height: 30px; background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">1</div>
                    <div style="width: 50px; height: 2px; background: var(--primary-color); margin: 0 10px;"></div>
                    <div style="width: 30px; height: 30px; background: var(--light-gray); color: var(--dark-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">2</div>
                    <div style="width: 50px; height: 2px; background: var(--light-gray); margin: 0 10px;"></div>
                    <div style="width: 30px; height: 30px; background: var(--light-gray); color: var(--dark-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">3</div>
                </div>
            </div>
            
            <form action="registerform1.php" method="POST">
                <div class="form-group">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter your first name" required>
                </div>
                
                <div class="form-group">
                    <label for="middlename" class="form-label">Middle Name</label>
                    <input type="text" id="middlename" name="middlename" class="form-control" placeholder="Enter your middle name">
                </div>
                
                <div class="form-group">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter your last name" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Gender</label>
                    <div style="display: flex; gap: 20px;">
                        <label style="display: flex; align-items: center;">
                            <input type="radio" name="gender" value="male" required> 
                            <span style="margin-left: 5px;">Male</span>
                        </label>
                        <label style="display: flex; align-items: center;">
                            <input type="radio" name="gender" value="female" required> 
                            <span style="margin-left: 5px;">Female</span>
                        </label>
                        <label style="display: flex; align-items: center;">
                            <input type="radio" name="gender" value="other" required> 
                            <span style="margin-left: 5px;">Other</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="birthdate" class="form-label">Birth Date</label>
                    <input type="date" id="birthdate" name="birthdate" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="state" class="form-label">State</label>
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
                    <label for="statespecify" class="form-label">Please Specify State</label>
                    <input type="text" id="statespecify" name="statespecify" class="form-control" placeholder="Enter your state">
                </div>
                
                <div class="form-group">
                    <label for="city" class="form-label">City</label>
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
                    <label for="cityspecify" class="form-label">Please Specify City</label>
                    <input type="text" id="cityspecify" name="cityspecify" class="form-control" placeholder="Enter your city">
                </div>
                
                <button type="submit" class="btn btn-block">
                    <i class="fas fa-arrow-right"></i> Continue to Step 2
                </button>
            </form>
        </div>
    </div>

    <div class="footer">
        <div class="container">
            <p>&copy; 2025 Employee Recruitment System. All rights reserved.</p>
        </div>
    </div>

    <script>
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
    </script>
</body>
</html>