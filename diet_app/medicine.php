<?php
include "config/db.php";

/* 🔐 Session protection */
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* 💊 Fetch medicines for this user */
$sql = "SELECT m.medicine_name, m.note
        FROM medicines m
        JOIN user_health_profile u
        ON m.disease_id = u.disease_id
        WHERE u.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

/* ⚠️ If profile not filled */
if ($result->num_rows === 0) {
    echo "<div style='padding:20px'>Please fill your health profile first.</div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Medicine Info | Diet Health System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<?php include 'header.php'; ?>

<!-- MAIN LAYOUT WRAPPER -->
<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6">

        <!-- PAGE HEADER -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Medicine Information</h1>
            <p class="text-gray-500">Based on your health profile</p>
        </div>

        <!-- WARNING NOTE -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 text-red-700">
            ⚠️ These medicines are for informational purposes only.
            Please consult a doctor before taking any medication.
        </div>

        <!-- MEDICINE CARDS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-green-600 mb-2">
                        <?= htmlspecialchars($row['medicine_name']) ?>
                    </h3>
                    <p class="text-gray-700">
                        <?= htmlspecialchars($row['note']) ?>
                    </p>
                </div>
            <?php endwhile; ?>

        </div>

    </main>

</div>

<?php include 'footer.php'; ?>

</body>
</html>
