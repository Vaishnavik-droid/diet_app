<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="bg-white shadow-md px-6 py-4 flex justify-between items-center">

    <!-- Logo -->
    <div class="text-2xl font-bold text-green-600">
        🥗 Diet Health System
    </div>

    <!-- Right Side -->
    <div class="flex items-center space-x-6">

        <?php if (isset($_SESSION['name'])): ?>
            <span class="text-gray-700">
                Welcome, <strong><?= htmlspecialchars($_SESSION['name']) ?></strong>
            </span>
        <?php endif; ?>

        <a href="auth/logout.php"
           class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition">
           Logout
        </a>

    </div>

</header>
