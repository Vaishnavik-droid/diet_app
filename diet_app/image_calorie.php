<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$foodDetected = "";
$calories = "";
$error = "";

/* Clarifai config */
$apiKey  = "e96e9389946e4c22bf1c849333797129";
$modelId = "bd367be194cf45149e75f01d59f77ba7"; // FOOD MODEL

if (isset($_POST['upload'])) {

    if (!empty($_FILES['food_image']['tmp_name'])) {

        $imageData = base64_encode(
            file_get_contents($_FILES['food_image']['tmp_name'])
        );

        $payload = json_encode([
            "inputs" => [[
                "data" => [
                    "image" => [
                        "base64" => $imageData
                    ]
                ]
            ]]
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,
            "https://api.clarifai.com/v2/models/$modelId/outputs"
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Key $apiKey",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        /* IMPORTANT FOR XAMPP / LOCALHOST */
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {

            $data = json_decode($response, true);

            if (
                isset($data['outputs'][0]['data']['concepts']) &&
                count($data['outputs'][0]['data']['concepts']) > 0
            ) {
                $rawFood =
                    $data['outputs'][0]['data']['concepts'][0]['name'];

                // Normalize: "green apple" → "apple"
                $foodDetected = strtolower(explode(" ", $rawFood)[0]);

                $stmt = $conn->prepare(
                    "SELECT avg_calories FROM food_calories WHERE food_name LIKE ?"
                );
                $like = "%$foodDetected%";
                $stmt->bind_param("s", $like);
                $stmt->execute();
                $res = $stmt->get_result();

                if ($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    $calories = $row['avg_calories'];
                } else {
                    $error = "Food detected but calorie data not found";
                }

            } else {
                $error = "Unable to detect food from image";
            }

        } else {
            $error = "Food recognition service failed";
        }

    } else {
        $error = "Please upload an image";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Image Calorie Estimator</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex bg-gray-100">

<?php include "sidebar.php"; ?>

<main class="flex-1 p-8">

    <h1 class="text-3xl font-bold mb-6">📸 Image Calorie Estimator</h1>

    <div class="bg-white rounded-xl shadow p-6 max-w-xl">

        <form method="POST" enctype="multipart/form-data" class="space-y-4">

            <input type="file" name="food_image" accept="image/*" required>

            <button type="submit" name="upload"
                    class="bg-green-500 text-white px-6 py-2 rounded-lg">
                Upload & Analyze
            </button>

        </form>

        <?php if ($foodDetected): ?>
            <div class="mt-6 p-4 bg-green-100 rounded-lg">
                🥗 Detected Food: <b><?= ucfirst($foodDetected) ?></b><br>
                🔥 Estimated Calories: <b><?= $calories ?> kcal</b>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="mt-6 p-4 bg-red-100 rounded-lg">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <p class="text-sm text-gray-500 mt-4">
            *Calories are estimated based on average serving size.
        </p>

    </div>

</main>

</body>
</html>
