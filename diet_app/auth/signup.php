<?php
require_once "../config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $first = trim($_POST['first_name']);
    $last = trim($_POST['last_name']);

    $sql = "INSERT INTO users (username, email, password, first_name, last_name)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $email, $password, $first, $last);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "User already exists or something went wrong";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up | Diet Health System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
  class="min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat"
  style="background-image: url('../assets/bg.png');"
>

<!-- Dark overlay -->
<div class="absolute inset-0 bg-black/40"></div>

<!-- Glass card -->
<div class="relative z-10 max-w-md w-full mx-4
            bg-white/20 backdrop-blur-lg
            border border-white/30
            rounded-2xl shadow-2xl p-8 text-white">

    <h1 class="text-3xl font-bold text-center mb-6">
        Create Account
    </h1>

    <?php if (!empty($error)): ?>
        <div class="bg-red-500/80 text-white px-4 py-2 rounded mb-4 text-center">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label class="block mb-1">Username</label>
            <input type="text" name="username" required
                   class="w-full px-4 py-2 rounded-lg bg-white/80 text-black
                          focus:outline-none focus:ring-2 focus:ring-green-400">
        </div>

        <div class="mb-3">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" required
                   class="w-full px-4 py-2 rounded-lg bg-white/80 text-black
                          focus:outline-none focus:ring-2 focus:ring-green-400">
        </div>

        <div class="mb-3">
            <label class="block mb-1">Password</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-2 rounded-lg bg-white/80 text-black
                          focus:outline-none focus:ring-2 focus:ring-green-400">
        </div>

        <div class="mb-3">
            <label class="block mb-1">First Name</label>
            <input type="text" name="first_name" required
                   class="w-full px-4 py-2 rounded-lg bg-white/80 text-black
                          focus:outline-none focus:ring-2 focus:ring-green-400">
        </div>

        <div class="mb-5">
            <label class="block mb-1">Last Name</label>
            <input type="text" name="last_name" required
                   class="w-full px-4 py-2 rounded-lg bg-white/80 text-black
                          focus:outline-none focus:ring-2 focus:ring-green-400">
        </div>

        <button type="submit"
                class="w-full bg-green-500 hover:bg-green-600
                       text-white py-2 rounded-lg font-semibold transition">
            Sign Up
        </button>
    </form>

    <p class="text-center mt-6 text-sm">
        Already have an account?
        <a href="login.php" class="text-green-300 hover:underline">Login</a>
    </p>

</div>

</body>
</html>
