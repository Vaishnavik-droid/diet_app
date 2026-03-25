<?php
include "config/db.php";

/* 🔐 Session protection */
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* 📥 Form data (Safe Fetch) */
$age        = $_POST['age'] ?? null;
$gender     = $_POST['gender'] ?? null;
$height_cm  = $_POST['height'] ?? null;
$weight     = $_POST['weight'] ?? null;
$disease_id = $_POST['disease_id'] ?? null;   // ✅ FIXED

/* 🛑 Basic validation */
if (!$age || !$gender || !$height_cm || !$weight || !$disease_id) {
    die("Invalid form submission.");
}

/* 🧮 BMI calculation */
$height_m = $height_cm / 100;
$bmi = $weight / ($height_m * $height_m);

/* 🔍 Check if profile exists */
$check = $conn->prepare(
    "SELECT profile_id FROM user_health_profile WHERE user_id=?"
);
$check->bind_param("i", $user_id);
$check->execute();
$result = $check->get_result();

/* 🔄 Insert or Update */
if ($result->num_rows > 0) {

    $sql = "UPDATE user_health_profile
            SET age=?, gender=?, height_cm=?, weight_kg=?, bmi=?, disease_id=?
            WHERE user_id=?";

} else {

    $sql = "INSERT INTO user_health_profile
            (age, gender, height_cm, weight_kg, bmi, disease_id, user_id)
            VALUES (?,?,?,?,?,?,?)";
}

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "isdddii",
    $age,
    $gender,
    $height_cm,
    $weight,
    $bmi,
    $disease_id,   // ✅ FIXED
    $user_id
);

$stmt->execute();

/* ➜ Redirect */
header("Location: diet.php");
exit();
?>
