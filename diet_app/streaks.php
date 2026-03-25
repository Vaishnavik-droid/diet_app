<?php
/* ===========================
   SESSION + CONFIG
=========================== */
session_start();
date_default_timezone_set('Asia/Kolkata');

include "config/db.php";

/* ===========================
   LOGIN CHECK
=========================== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');
$message = "";

/* ===========================
   FETCH USER STREAK DATA
=========================== */
$query = "SELECT current_streak, best_streak, last_followed 
          FROM diet_streaks 
          WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $insert = $conn->prepare(
        "INSERT INTO diet_streaks (user_id, current_streak, best_streak) 
         VALUES (?, 0, 0)"
    );
    $insert->bind_param("i", $user_id);
    $insert->execute();

    $current_streak = 0;
    $best_streak = 0;
    $last_followed = null;
} else {
    $row = $result->fetch_assoc();
    $current_streak = (int)$row['current_streak'];
    $best_streak = (int)$row['best_streak'];
    $last_followed = $row['last_followed'];
}

/* ===========================
   BUTTON CLICK LOGIC
=========================== */
if (isset($_POST['followed'])) {

    if ($last_followed === $today) {
        $message = "✅ You already marked today!";
    } else {

        $yesterday = date('Y-m-d', strtotime('-1 day'));

        if ($last_followed === $yesterday) {
            $current_streak++;
        } else {
            $current_streak = 1;
        }

        if ($current_streak > $best_streak) {
            $best_streak = $current_streak;
        }

        $update = $conn->prepare("
            UPDATE diet_streaks 
            SET current_streak = ?, 
                best_streak = ?, 
                last_followed = ?
            WHERE user_id = ?
        ");
        $update->bind_param(
            "iisi",
            $current_streak,
            $best_streak,
            $today,
            $user_id
        );
        $update->execute();

        $message = "🔥 Great job! Diet followed today.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Diet Streak</title>

<style>
body{
    margin:0;
    min-height:100vh;
    font-family:'Segoe UI', Arial, sans-serif;
    background:
        linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
        url("assets/streakbg.jpg");
    background-size:cover;
    background-position:center;
}

/* ===== LAYOUT ===== */
.app{
    display:flex;
    min-height:100vh;
}

/* ===== SIDEBAR ===== */
.sidebar{
    width:240px;
    padding:25px;
    background:rgba(255,255,255,0.15);
    backdrop-filter:blur(18px);
    -webkit-backdrop-filter:blur(18px);
    color:#fff;
}

.sidebar h2{
    margin-bottom:30px;
    font-size:20px;
}

.sidebar a{
    display:block;
    padding:12px 15px;
    margin-bottom:10px;
    color:#fff;
    text-decoration:none;
    border-radius:10px;
    transition:0.3s;
}

.sidebar a:hover,
.sidebar a.active{
    background:rgba(255,255,255,0.25);
}

/* ===== CONTENT ===== */
.content{
    flex:1;
    display:flex;
    justify-content:center;
    align-items:flex-start;
    padding:60px 20px;
}

/* ===== STREAK CARD ===== */
.streak-card{
    max-width:420px;
    width:100%;
    padding:25px;
    background:rgba(255,255,255,0.8);
    backdrop-filter:blur(14px);
    border-radius:20px;
    box-shadow:0 18px 45px rgba(0,0,0,0.35);
}

.streak-header{
    display:flex;
    justify-content:space-between;
    margin-bottom:20px;
}

.streak-count{
    font-size:46px;
    font-weight:700;
    color:#ff7a00;
    margin-bottom:15px;
}

.week-row{
    display:flex;
    justify-content:space-between;
    margin-bottom:20px;
}

.day{
    width:38px;
    height:38px;
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
    background:#eee;
    color:#aaa;
}

.day.active{
    background:#ff7a00;
    color:#fff;
}

.stats{
    display:flex;
    justify-content:space-between;
    margin-bottom:20px;
}

.stat-box{
    width:48%;
    background:rgba(255,255,255,0.9);
    padding:15px;
    border-radius:14px;
}

button{
    width:100%;
    padding:15px;
    border:none;
    border-radius:14px;
    background:#ff7a00;
    color:white;
    font-size:15px;
    cursor:pointer;
}

.msg{
    text-align:center;
    margin-top:14px;
    color:green;
    font-weight:600;
}
</style>
</head>

<body>

<div class="app">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>🥗 HealthApp</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="diet.php">Diet Plan</a>
        <a href="medicine.php">Medicine</a>
        <a href="streaks.php" class="active">Streaks 🔥</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- CONTENT -->
    <div class="content">

        <div class="streak-card">

            <div class="streak-header">
                <h3>🔥 Current Streak</h3>
                <span>Diet Consistency</span>
            </div>

            <div class="streak-count">
                <?= $current_streak ?> days
            </div>

            <div class="week-row">
                <div class="day active">M</div>
                <div class="day active">T</div>
                <div class="day active">W</div>
                <div class="day">T</div>
                <div class="day">F</div>
                <div class="day">S</div>
                <div class="day">S</div>
            </div>

            <div class="stats">
                <div class="stat-box">
                    <h4>Best Streak</h4>
                    <p><?= $best_streak ?> days</p>
                </div>
                <div class="stat-box">
                    <h4>Status</h4>
                    <p>Active</p>
                </div>
            </div>

            <form method="POST">
                <button name="followed">✅ I followed my diet today</button>
            </form>

            <?php if ($message): ?>
                <p class="msg"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

        </div>

    </div>

</div>

</body>
</html>
