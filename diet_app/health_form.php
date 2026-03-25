<?php
include "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* ===========================
   FETCH DISEASE LIST
=========================== */
$diseaseQuery = "SELECT disease_id, disease_name FROM diseases";
$diseaseResult = $conn->query($diseaseQuery);

/* ===========================
   FETCH USER SELECTED DISEASE
=========================== */
$currentDiseaseId = null;
$currentDiseaseName = "";

$profileQuery = "
    SELECT d.disease_id, d.disease_name
    FROM user_health_profile u
    JOIN diseases d ON u.disease_id = d.disease_id
    WHERE u.user_id = ?
";

$stmt = $conn->prepare($profileQuery);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$profileResult = $stmt->get_result();

if ($row = $profileResult->fetch_assoc()) {
    $currentDiseaseId = $row['disease_id'];
    $currentDiseaseName = $row['disease_name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Health Profile | Diet Health System</title>
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
            <h1 class="text-2xl font-bold text-gray-800">Health Profile</h1>
            <p class="text-gray-500">Enter your health details to get personalized diet plans</p>
        </div>

        <!-- SHOW CURRENT DISEASE -->
        <?php if ($currentDiseaseName): ?>
            <div class="bg-blue-100 text-blue-700 p-3 rounded mb-4 max-w-3xl">
                Current Selected Disease:
                <strong><?= htmlspecialchars($currentDiseaseName) ?></strong>
            </div>
        <?php endif; ?>

        <!-- FORM CARD -->
        <div class="bg-white rounded-xl shadow p-6 max-w-3xl">

            <form action="save_health.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- AGE -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Age</label>
                    <input type="number" name="age" required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
                </div>

                <!-- GENDER -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Gender</label>
                    <select name="gender" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <!-- HEIGHT -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Height (cm)</label>
                    <input type="number" name="height" required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
                </div>

                <!-- WEIGHT -->
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Weight (kg)</label>
                    <input type="number" name="weight" required
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
                </div>

                <!-- DISEASE DROPDOWN -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Disease</label>
                    <select name="disease_id" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">

                        <option value="">--- Select Disease ---</option>

                        <?php while ($row = $diseaseResult->fetch_assoc()): ?>
                            <option value="<?= $row['disease_id'] ?>"
                                <?= ($row['disease_id'] == $currentDiseaseId) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($row['disease_name']) ?>
                            </option>
                        <?php endwhile; ?>

                    </select>
                </div>

                <!-- SUBMIT -->
                <div class="md:col-span-2 text-right">
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition">
                        Save Health Info
                    </button>
                </div>

            </form>
        </div>

    </main>

</div>

<?php include 'footer.php'; ?>

</body>
</html>
