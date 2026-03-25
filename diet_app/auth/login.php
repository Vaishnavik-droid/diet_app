<?php
require_once "../config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['name'] = $user['first_name'];
        header("Location: ../dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Diet Health System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center"
      style="background-image: url('../assets/bg.png');">

<!-- Overlay (optional but recommended for readability) -->
<div class="absolute inset-0 bg-black/30"></div>

<div class="relative max-w-md w-full mx-4 bg-white/20 backdrop-blur-lg border border-white/30
            rounded-2xl shadow-2xl p-8 text-white">

    <h1 class="text-3xl font-bold text-center mb-6">
        Welcome Back
    </h1>

    <!-- ERROR MESSAGE -->
    <?php if (!empty($error)): ?>
        <div class="bg-red-500/80 text-white px-4 py-2 rounded mb-4 text-center">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <!-- FORM START -->
    <form method="POST">

        <!-- INPUT -->
        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email"
                   class="w-full px-4 py-2 rounded-lg bg-white/80 text-black
                          focus:outline-none focus:ring-2 focus:ring-green-400"
                   required>
        </div>

        <div class="mb-6">
            <label class="block mb-1">Password</label>
            <input type="password" name="password"
                   class="w-full px-4 py-2 rounded-lg bg-white/80 text-black
                          focus:outline-none focus:ring-2 focus:ring-green-400"
                   required>
        </div>

        <button type="submit"
                class="w-full bg-green-500 hover:bg-green-600
                       text-white py-2 rounded-lg font-semibold transition">
            Login
        </button>

    </form>

    <p class="text-center mt-6 text-sm">
        Don’t have an account?
        <a href="signup.php" class="text-green-300 hover:underline">Sign up</a>
    </p>

</div>

</body>
