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
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-graduation-cap"></i> Registration - Step 2: Academic Information</h2>
            </div>
            
            <div style="display: flex; justify-content: center; margin-bottom: 20px;">
                <div style="display: flex; align-items: center;">
                    <div style="width: 30px; height: 30px; background: var(--light-gray); color: var(--dark-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">1</div>
                    <div style="width: 50px; height: 2px; background: var(--primary-color); margin: 0 10px;"></div>
                    <div style="width: 30px; height: 30px; background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">2</div>
                    <div style="width: 50px; height: 2px; background: var(--light-gray); margin: 0 10px;"></div>
                    <div style="width: 30px; height: 30px; background: var(--light-gray); color: var(--dark-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">3</div>
                </div>
            </div>
            
            <form id="registerform2" method="post" name="registerform2" action="registerform2.php">
                <div class="form-group">
                    <label for="university" class="form-label">University *</label>
                    <input type="text" id="university" name="university" class="form-control" pattern=".{3,}" title="3 characters minimum, write full name" required>
                </div>
                
                <div class="form-group">
                    <label for="institute" class="form-label">Institute *</label>
                    <input type="text" id="institute" name="institute" class="form-control" pattern=".{3,}" title="3 characters minimum, write full name" required>
                </div>
                
                <div class="form-group">
                    <label for="branch" class="form-label">Branch *</label>
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
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="degree" class="form-label">Degree *</label>
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
                        <label class="form-label">Status *</label>
                        <div style="display: flex; gap: 20px; margin-top: 8px;">
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="rad1" value="pursuing" required style="margin-right: 5px;"> 
                                <span>Pursuing</span>
                            </label>
                            <label style="display: flex; align-items: center;">
                                <input type="radio" name="rad1" value="completed" required style="margin-right: 5px;"> 
                                <span>Completed</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="cpi" class="form-label">Average CPI *</label>
                        <input type="number" id="cpi" name="cpi" class="form-control" min="4.00" max="9.99" step="0.01" title="CPI" required>
                    </div>
                    <div class="form-group" id="semester-group">
                        <label for="semester" class="form-label">Semester</label>
                        <input type="number" id="semester" name="semester" class="form-control" min="2" max="8" maxlength="2" title="semester">
                    </div>
                </div>
                
                <div class="form-group" id="experience-group">
                    <label for="experience" class="form-label">Experience (years)</label>
                    <input type="number" id="experience" name="experience" class="form-control" min="0" max="30" title="experience">
                </div>
                
                <div class="form-actions">
                    <a href="registerform1.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Previous
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Next <i class="fas fa-arrow-right"></i>
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
        // Handle the semester/experience field visibility based on education status
        document.addEventListener('DOMContentLoaded', function() {
            // Get the radio buttons for education status
            const pursuingRadio = document.querySelector('input[name="rad1"][value="pursuing"]');
            const completedRadio = document.querySelector('input[name="rad1"][value="completed"]');
            
            // Function to toggle visibility based on selection
            function toggleFields() {
                const semesterGroup = document.getElementById('semester-group');
                const experienceGroup = document.getElementById('experience-group');
                
                if (pursuingRadio && pursuingRadio.checked) {
                    // Show semester field, hide experience field
                    if (semesterGroup) semesterGroup.style.display = 'block';
                    if (experienceGroup) experienceGroup.style.display = 'none';
                } else if (completedRadio && completedRadio.checked) {
                    // Show experience field, hide semester field
                    if (semesterGroup) semesterGroup.style.display = 'none';
                    if (experienceGroup) experienceGroup.style.display = 'block';
                }
            }
            
            // Add event listeners to radio buttons
            if (pursuingRadio && completedRadio) {
                pursuingRadio.addEventListener('change', toggleFields);
                completedRadio.addEventListener('change', toggleFields);
                
                // Initialize visibility on page load
                toggleFields();
            } else {
                // Fallback: show both fields if no radio button is selected
                const semesterGroup = document.getElementById('semester-group');
                const experienceGroup = document.getElementById('experience-group');
                if (semesterGroup) semesterGroup.style.display = 'block';
                if (experienceGroup) experienceGroup.style.display = 'block';
            }
        });
    </script>
</body>
</html>
