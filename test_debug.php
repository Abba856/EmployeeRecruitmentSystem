<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Before mysqli connection<br>";

try {
    $connection = new mysqli('localhost', 'root', '', 'recruitment');
    echo "After mysqli connection<br>";
    
    if ($connection->connect_error) {
        echo "Connection error: " . $connection->connect_error . "<br>";
    } else {
        echo "Connection successful<br>";
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "<br>";
}

echo "End of script<br>";
?>