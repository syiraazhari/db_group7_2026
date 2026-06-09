<?php
// ============================================================
// includes/db.php — MySQL database connection
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');         // Change to your MySQL username
define('DB_PASS', '');             // Change to your MySQL password
define('DB_NAME', 'rembayung_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die('<p style="color:red;text-align:center;padding:40px;">
        Database connection failed: ' . $conn->connect_error . '<br>
        Please run <strong>setup.php</strong> first.
    </p>');
}

$conn->set_charset('utf8mb4');
?>
