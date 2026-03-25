<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "diet_health_system"
);

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
