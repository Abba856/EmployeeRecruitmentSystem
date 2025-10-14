<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Employee Recruitment System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <i class="fas fa-building"></i>
                    <span>Contact Us | Employee Recruitment System</span>
                </div>
                <ul class="nav-links">
                    <li><a href="login.php">Login</a></li>
                    <li><a href="registerform1.php">Register</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php" class="active">Contact</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-envelope"></i> Contact Us</h2>
            </div>
            
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>Our Location</h3>
                    <p>123 Business Avenue<br>Corporate District<br>New York, NY 10001</p>
                </div>

                <div class="dashboard-card">
                    <i class="fas fa-phone"></i>
                    <h3>Phone Number</h3>
                    <p>+234 (081) 123-4567<br>Mon-Fri, 9:00 AM - 6:00 PM</p>
                </div>

                <div class="dashboard-card">
                    <i class="fas fa-envelope"></i>
                    <h3>Email Address</h3>
                    <p>info@recruitmentsystem.com<br>support@recruitmentsystem.com</p>
                </div>

            </div>
            
            <div class="card" style="margin-top: 30px;">
                <h3 style="color: var(--primary-color); margin-bottom: 20px;">Send Us a Message</h3>
                <form action="feedbackprocessing.php" method="POST">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject" class="form-label">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter the subject of your message" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="message" class="form-label">Message</label>
                        <textarea id="message" name="message" class="form-control" rows="5" placeholder="Enter your message here..." required></textarea>
                    </div>
                    
                    <button type="submit" class="btn">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
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