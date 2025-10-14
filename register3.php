<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Account Information | Employee Recruitment System</title>
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
                    <li><a href="login.php">Login</a></li>
                    <li><a href="registerform1.php">Register</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-lock"></i> Registration - Step 3: Account Information</h2>
            </div>
            
            <div style="display: flex; justify-content: center; margin-bottom: 20px;">
                <div style="display: flex; align-items: center;">
                    <div style="width: 30px; height: 30px; background: var(--light-gray); color: var(--dark-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">1</div>
                    <div style="width: 50px; height: 2px; background: var(--light-gray); margin: 0 10px;"></div>
                    <div style="width: 30px; height: 30px; background: var(--light-gray); color: var(--dark-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">2</div>
                    <div style="width: 50px; height: 2px; background: var(--primary-color); margin: 0 10px;"></div>
                    <div style="width: 30px; height: 30px; background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">3</div>
                </div>
            </div>
            
            <form id="registerform3" method="post" name="registerform3" action="registerform3.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="post" class="form-label">Position Applying For *</label>
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
                    <label for="resume" class="form-label">Resume *</label>
                    <input type="file" id="resume" name="resume" class="form-control" accept="application/msword,application/pdf,application/rtf,.doc,.docx,.pdf" required>
                    <small class="form-text">Accepted formats: DOC, DOCX, PDF (Max file size: depends on server settings)</small>
                </div>
                
                <div class="form-group">
                    <label for="pemail" class="form-label">Primary Email *</label>
                    <input type="email" id="pemail" name="pemail" class="form-control" placeholder="Enter your primary email - will be used for login" required>
                </div>
                
                <div class="form-group">
                    <label for="semail" class="form-label">Secondary Email</label>
                    <input type="email" id="semail" name="semail" class="form-control" placeholder="Enter your secondary email - can be used for account recovery">
                </div>
                
                <div class="form-group">
                    <label for="setpassword" class="form-label">Password *</label>
                    <input type="password" id="setpassword" name="setpassword" class="form-control" placeholder="Enter your password" required autocomplete="off">
                    <div id="message1" class="password-strength"></div>
                </div>
                
                <div class="form-group">
                    <label for="confirm" class="form-label">Confirm Password *</label>
                    <input type="password" id="confirm" name="confirm" class="form-control" placeholder="Confirm your password" required autocomplete="off">
                    <div id="message2" class="password-strength"></div>
                </div>
                
                <div class="form-actions">
                    <a href="register2.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Previous
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Complete Registration <i class="fas fa-user-check"></i>
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
    
    <script src="register3.js" type="text/javascript"></script>
    <script>
        // Password matching functionality
        function checkPass() {
            const password = document.getElementById('setpassword');
            const confirm = document.getElementById('confirm');
            const message2 = document.getElementById('message2');
            
            if (password.value === confirm.value && password.value.length > 0) {
                message2.innerHTML = '<span style="color: green;">Passwords match!</span>';
                confirm.setCustomValidity('');
            } else if (confirm.value.length > 0) {
                message2.innerHTML = '<span style="color: red;">Passwords do not match!</span>';
                confirm.setCustomValidity('Passwords do not match');
            } else {
                message2.innerHTML = '';
                confirm.setCustomValidity('');
            }
        }

        // Basic password strength indicator
        function my() {
            const password = document.getElementById('setpassword');
            const message1 = document.getElementById('message1');
            const pass = password.value;

            if (pass.length > 0 && pass.length < 8) {
                message1.innerHTML = '<span style="color: red;">Password must be at least 8 characters long</span>';
                password.setCustomValidity('Password must be at least 8 characters long');
            } else if (pass.length >= 8) {
                message1.innerHTML = '<span style="color: green;">Good password length</span>';
                password.setCustomValidity('');
            } else {
                message1.innerHTML = '';
                password.setCustomValidity('');
            }
            
            // Recheck password match when password changes
            if (document.getElementById('confirm').value.length > 0) {
                checkPass();
            }
        }

        // Add event listeners when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const password = document.getElementById('setpassword');
            const confirm = document.getElementById('confirm');
            
            if (password) {
                password.addEventListener('keyup', my);
            }
            
            if (confirm) {
                confirm.addEventListener('keyup', checkPass);
            }
        });
    </script>
</body>
</html>
