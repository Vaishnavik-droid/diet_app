<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$calories = null;
$error = "";

if (isset($_POST['food_name'])) {
    $food = strtolower(trim($_POST['food_name']));

    $stmt = $conn->prepare(
        "SELECT calories_per_100g FROM food_calories WHERE food_name = ?"
    );
    $stmt->bind_param("s", $food);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $calories = $row['calories_per_100g'];
    } else {
        $error = "❌ Food not found in database";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Calorie Calculator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex bg-gray-100">

<!-- SIDEBAR -->
<?php include "sidebar.php"; ?>

<!-- MAIN CONTENT -->
<main class="flex-1 p-8">

    <h1 class="text-3xl font-bold mb-6 text-gray-800">
        🧮 Calorie Calculator
    </h1>

    <div class="bg-white rounded-xl shadow p-6 max-w-xl">

        <form method="POST" class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Enter Food Name
                </label>
                <input type="text" name="food_name" required
                       placeholder="e.g. Apple"
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
            </div>

            <button type="submit"
                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium">
                Calculate Calories
            </button>
        </form>

        <?php if ($calories !== null): ?>
            <div class="mt-6 p-4 bg-green-100 text-green-800 rounded-lg">
                ✅ Calories: <b><?= $calories ?> kcal</b> per 100g
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="mt-6 p-4 bg-red-100 text-red-700 rounded-lg">
                <?= $error ?>
            </div>
        <?php endif; ?>

    </div>

</main>

</body>
</html>
