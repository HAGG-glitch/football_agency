<?php
// Database connection settings
$host = "localhost";
$user = "root";        // default XAMPP user
$pass = "";            // default XAMPP password
$db   = "football_agency"; // change to your DB name

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to create users table
$sql = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin','Player','Agent','Club Manager') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
";

if ($conn->query($sql) === TRUE) {
    echo "✔ Users table created successfully.";
} else {
    echo "❌ Error creating table: " . $conn->error;
}

$conn->close();
?>
