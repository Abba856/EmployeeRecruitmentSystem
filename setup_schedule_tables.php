<?php
// Database connection
require('connect.php');

// Create scheduled_exams table
$sql1 = "CREATE TABLE IF NOT EXISTS scheduled_exams (
    schedule_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    exam_datetime DATETIME NOT NULL,
    duration INT(11) NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB";

// Create exam_candidate_assoc table to link scheduled exams with candidates
$sql2 = "CREATE TABLE IF NOT EXISTS exam_candidate_assoc (
    assoc_id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    schedule_id INT(11) NOT NULL,
    candidate_id INT(11) NOT NULL,
    FOREIGN KEY (schedule_id) REFERENCES scheduled_exams(schedule_id) ON DELETE CASCADE,
    FOREIGN KEY (candidate_id) REFERENCES personal(userid) ON DELETE CASCADE
) ENGINE=InnoDB";

$success = true;

if (!$connection->query($sql1)) {
    echo "Error creating scheduled_exams table: " . $connection->error . "\n";
    $success = false;
}

if (!$connection->query($sql2)) {
    echo "Error creating exam_candidate_assoc table: " . $connection->error . "\n";
    $success = false;
}

if ($success) {
    echo "Database tables created successfully!";
} else {
    echo "There were errors creating the tables.";
}

$connection->close();
?>