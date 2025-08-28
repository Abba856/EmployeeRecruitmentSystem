# Employee Recruitment System

## Overview

This is a web-based employee recruitment system that allows candidates to register, view job requirements, and submit their profiles. Administrators can manage job requirements and view candidate information.

## Features

- User registration with a 3-step process
- Job requirement viewing
- Profile management
- Admin panel for managing job requirements
- Feedback system

## Fixed Issues

All deprecated `mysql_*` functions have been replaced with MySQLi prepared statements to ensure compatibility with modern PHP versions and prevent SQL injection vulnerabilities.

## Installation

1. Import the `recruitment.sql` file into your MySQL database
2. Update the database connection settings in `connect.php`
3. Place the files in your web server directory
4. Access the system through your web browser

## Security

- All database queries use prepared statements to prevent SQL injection
- User input is properly sanitized
- Session management for authentication

## Files

- **connect.php** - Database connection
- **login.php** - User login page
- **registerform1.php** - Personal information registration
- **registerform2.php** - Academic information registration
- **registerform3.php** - Account information registration
- **myaccount.php** - User dashboard
- **requirementboard.php** - Job requirements listing
- **viewpersonal.php** - View personal profile
- **viewacademic.php** - View academic profile
- **viewaccount.php** - View account profile
- **adminaccount.php** - Admin dashboard
- **updaterequirement.php** - Update job requirements
- **seefeedback.php** - View user feedback (new)
- **recruitment.sql** - Database schema

## Requirements

- PHP 7.0 or higher
- MySQL 5.0 or higher
- Web server (Apache, Nginx, etc.)