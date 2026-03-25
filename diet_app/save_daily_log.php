<?php
include "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$calories = (int)$_POST['calories'];
$water = (float)$_POST['water'];
$weight = isset($_POST['weight']) ? (float)$_POST['weight'] : null;

$sql = "INSERT INTO daily_health_log (user_id, log_date, calories, water, weight)
        VALUES (?, CURDATE(), ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        calories = VALUES(calories),
        water = VALUES(water),
        weight = VALUES(weight)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iidd", $user_id, $calories, $water, $weight);
$stmt->execute();

header("Location: dashboard.php");
exit();
