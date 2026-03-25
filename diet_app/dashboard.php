<?php
include "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$userName = $_SESSION['name'] ?? 'User';

/* ================= CALORIES & WATER ================= */
$chartLabels = [];
$caloriesData = [];
$waterData = [];

$sql = "SELECT log_date, calories, water
        FROM daily_health_log
        WHERE user_id = ?
        ORDER BY log_date ASC
        LIMIT 7";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

/* Prepare last 7 days */
$dates = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[$date] = [
        'label' => date('D', strtotime($date)),
        'calories' => 0,
        'water' => 0
    ];
}

/* Fill real data */
while ($row = $result->fetch_assoc()) {
    $date = $row['log_date'];
    if (isset($dates[$date])) {
        $dates[$date]['calories'] = (int)$row['calories'];
        $dates[$date]['water'] = (float)$row['water'];
    }
}

/* Final arrays */
foreach ($dates as $d) {
    $chartLabels[] = $d['label'];
    $caloriesData[] = $d['calories'];
    $waterData[] = $d['water'];
}

/* ================= BMI DATA ================= */
$bmiLabels = [];
$bmiData = [];

/* Fetch height */
$heightSql = "SELECT height_cm FROM user_health_profile WHERE user_id = ?";
$hStmt = $conn->prepare($heightSql);
$hStmt->bind_param("i", $_SESSION['user_id']);
$hStmt->execute();
$hResult = $hStmt->get_result();
$heightRow = $hResult->fetch_assoc();

$heightMeters = $heightRow ? ($heightRow['height_cm'] / 100) : null;

if ($heightMeters) {
    $bmiSql = "SELECT log_date, weight
               FROM daily_health_log
               WHERE user_id = ? AND weight IS NOT NULL
               ORDER BY log_date ASC
               LIMIT 7";

    $bStmt = $conn->prepare($bmiSql);
    $bStmt->bind_param("i", $_SESSION['user_id']);
    $bStmt->execute();
    $bResult = $bStmt->get_result();

   /* Prepare last 7 days for BMI */
$bmiDates = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $bmiDates[$date] = [
        'label' => date('D', strtotime($date)),
        'bmi' => null
    ];
}

/* Fill BMI from DB */
while ($row = $bResult->fetch_assoc()) {
    $date = $row['log_date'];
    if (isset($bmiDates[$date])) {
        $bmi = $row['weight'] / ($heightMeters * $heightMeters);
        $bmiDates[$date]['bmi'] = round($bmi, 1);
    }
}

/* Final BMI arrays */
foreach ($bmiDates as $d) {
    $bmiLabels[] = $d['label'];
    $bmiData[] = $d['bmi'];
}

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Diet Health System</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 min-h-screen flex">

<!-- SIDEBAR -->
<?php include 'sidebar.php'; ?>

<!-- MAIN CONTENT -->
<main class="flex-1 p-6">

    <!-- TOP BAR -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Welcome, <?= htmlspecialchars($userName) ?> 👋
        </h1>

        <input type="text" placeholder="Search..."
               class="px-4 py-2 rounded-lg border focus:ring-2 focus:ring-green-400">
    </div>

    <!-- TOP STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500">Calories</p>
            <h2 class="text-2xl font-bold text-green-600">
                <?= end($caloriesData) ?? 0 ?> kcal
            </h2>
            <p class="text-sm text-gray-400">Latest</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500">Water Intake</p>
            <h2 class="text-2xl font-bold text-blue-500">
                <?= end($waterData) ?? 0 ?> L
            </h2>
            <p class="text-sm text-gray-400">Latest</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500">Exercise</p>
            <h2 class="text-2xl font-bold text-purple-500">150 min</h2>
            <p class="text-sm text-gray-400">This week</p>
        </div>
    </div>

    <!-- MIDDLE SECTION -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        <!-- CURRENT MEAL -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4">Your Current Meal</h2>
            <table class="w-full text-sm text-left">
                <thead class="text-gray-500 border-b">
                    <tr>
                        <th class="py-2">Food</th>
                        <th>Quantity</th>
                        <th>Calories</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="py-2">Fruit Salad</td>
                        <td>1 Bowl</td>
                        <td>120</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2">Boiled Eggs</td>
                        <td>2</td>
                        <td>156</td>
                    </tr>
                    <tr>
                        <td class="py-2">Brown Bread</td>
                        <td>2 Slices</td>
                        <td>140</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="space-y-6">

            <!-- WATER TRACKER -->
            <div class="bg-white p-6 rounded-xl shadow text-center">
                <h2 class="text-lg font-semibold mb-4">Water Tracker</h2>
                <div class="text-4xl font-bold text-blue-500 mb-2">75%</div>
                <p class="text-gray-500 mb-4">of daily goal</p>
                <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                    Add Water
                </button>
            </div>

            <!-- DAILY LOG (CORRECT PLACE ✅) -->
            <div class="bg-white p-5 rounded-xl shadow">
                <h2 class="text-md font-semibold mb-3">Daily Health Log</h2>

                <form action="save_daily_log.php" method="POST" class="space-y-3">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Calories (kcal)</label>
                        <input type="number" name="calories" required
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Water (L)</label>
                        <input type="number" step="0.1" name="water" required
                               class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
                    </div>
                    <div>
    <label class="block text-sm text-gray-600 mb-1">Weight (kg)</label>
    <input type="number" step="0.1" name="weight"
           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
</div>


                    <button type="submit"
                            class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded-lg text-sm">
                        Save Today’s Log
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- OVERVIEW TRACKER -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-lg font-semibold mb-4">Overview Tracker</h2>
        <canvas id="healthChart" height="100"></canvas>
    </div>
<?php if (!empty($bmiData)): ?>
<div class="bg-white p-6 rounded-xl shadow mt-8">
    <h2 class="text-lg font-semibold mb-4">BMI Progress</h2>
    <canvas id="bmiChart" height="100"></canvas>
</div>
<?php endif; ?>




</main>


   
<!-- CALORIES & WATER CHART -->
<script>
const labels = <?= json_encode($chartLabels) ?>;
const caloriesData = <?= json_encode($caloriesData) ?>;
const waterData = <?= json_encode($waterData) ?>;

const ctx = document.getElementById('healthChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Calories (kcal)',
                data: caloriesData,
                borderColor: '#22c55e',
                backgroundColor: 'rgba(34,197,94,0.2)',
                tension: 0.4
            },
            {
                label: 'Water (L)',
                data: waterData,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.2)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top' }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<!-- BMI CHART -->
<?php if (!empty($bmiData)): ?>
<script>
const bmiLabels = <?= json_encode($bmiLabels) ?>;
const bmiData = <?= json_encode($bmiData) ?>;

const bmiCtx = document.getElementById('bmiChart');

new Chart(bmiCtx, {
    type: 'line',
    data: {
        labels: bmiLabels,
        datasets: [{
            label: 'BMI',
            data: bmiData,
            borderColor: '#8b5cf6',
            backgroundColor: 'rgba(139,92,246,0.2)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: false
            }
        }
    }
});
</script>
<?php endif; ?>

