<?php
include "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Fetch diet for this user */
$sql = "SELECT d.*
        FROM diet_plans d
        JOIN user_health_profile u
        ON d.disease_id = u.disease_id
        WHERE u.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$diet = $result->fetch_assoc();

if (!$diet) {
    echo "<div style='padding:20px'>Please fill your health profile first.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Diet Plan | Diet Health System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-cover bg-center bg-no-repeat"
      style="background-image: url('assets/diet-bg.jpg');">

<!-- DARK OVERLAY -->
<div class="min-h-screen bg-black/50">

    <!-- PAGE LAYOUT -->
    <div class="flex">

        <!-- SIDEBAR -->
        <?php include 'sidebar.php'; ?>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-8">

            <!-- HEADER -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white">
                    Your Personalized Diet Plan
                </h1>
                <p class="text-gray-200">
                    Based on your health profile
                </p>
            </div>

            <!-- DIET CARDS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Breakfast -->
                <div class="bg-white/90 backdrop-blur rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-green-600 mb-2">Breakfast</h3>
                    <p class="text-gray-700"><?= htmlspecialchars($diet['breakfast']) ?></p>
                </div>

                <!-- Lunch -->
                <div class="bg-white/90 backdrop-blur rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-green-600 mb-2">Lunch</h3>
                    <p class="text-gray-700"><?= htmlspecialchars($diet['lunch']) ?></p>
                </div>

                <!-- Dinner -->
                <div class="bg-white/90 backdrop-blur rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-green-600 mb-2">Dinner</h3>
                    <p class="text-gray-700"><?= htmlspecialchars($diet['dinner']) ?></p>
                </div>

                <!-- Snacks -->
                <div class="bg-white/90 backdrop-blur rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-green-600 mb-2">Snacks</h3>
                    <p class="text-gray-700"><?= htmlspecialchars($diet['snacks']) ?></p>
                </div>

                <!-- Avoid Foods -->
                <div class="md:col-span-2 bg-red-100/90 backdrop-blur border border-red-300 rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-red-600 mb-2">
                        🚫 Avoid These Foods
                    </h3>
                    <p class="text-gray-800"><?= htmlspecialchars($diet['avoid_foods']) ?></p>
                </div>

            </div>

        </main>
    </div>
</div>

</body>
</html>
