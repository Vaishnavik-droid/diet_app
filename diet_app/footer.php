<!-- Footer -->
<footer class="bg-white text-gray-800 mt-10 w-screen relative left-1/2 -translate-x-1/2 border-t">

    <div class="container mx-auto px-6 py-12">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

            <!-- Brand -->
            <div>
                <h3 class="text-2xl font-bold mb-4 flex items-center gap-2 text-green-600">
                    🥗 <span>Diet Health System</span>
                </h3>
                <p class="text-gray-600 mb-5">
                    A personalized health and nutrition platform that helps you 
                    manage diet plans, track streaks, monitor BMI, and maintain 
                    a healthier lifestyle.
                </p>

                <div class="flex space-x-4 text-sm">
                    <a href="#" class="text-gray-500 hover:text-green-600 transition">Facebook</a>
                    <a href="#" class="text-gray-500 hover:text-green-600 transition">Twitter</a>
                    <a href="#" class="text-gray-500 hover:text-green-600 transition">Instagram</a>
                    <a href="#" class="text-gray-500 hover:text-green-600 transition">LinkedIn</a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="dashboard.php" class="text-gray-600 hover:text-green-600 transition">Dashboard</a></li>
                    <li><a href="health_form.php" class="text-gray-600 hover:text-green-600 transition">Health Profile</a></li>
                    <li><a href="diet.php" class="text-gray-600 hover:text-green-600 transition">Diet Plan</a></li>
                    <li><a href="medicine.php" class="text-gray-600 hover:text-green-600 transition">Medicine Info</a></li>
                    <li><a href="streak.php" class="text-gray-600 hover:text-green-600 transition">Diet Streak</a></li>
                </ul>
            </div>

            <!-- Health Tools -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Health Tools</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-green-600 transition">BMI Tracker</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-green-600 transition">Calorie Monitor</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-green-600 transition">Water Intake Tracker</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-green-600 transition">Diet Recommendations</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-green-600 transition">Health Reports</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Contact Us</h3>
                <ul class="space-y-3 text-gray-600 text-sm">
                    <li>📍 Sharad Institute of Technology</li>
                    <li>📞 +91 7972569007</li>
                    <li>📧 rehanbubnale@116@gmail.com</li>
                    <li>🕒 Mon – Sat | 9:00 AM – 6:00 PM</li>
                </ul>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-300 mt-10 pt-6 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
            <p>© <?= date("Y") ?> Diet Health System | All Rights Reserved</p>
            <div class="mt-4 md:mt-0 space-x-4">
                <a href="#" class="hover:text-green-600 transition">Privacy Policy</a>
                <a href="#" class="hover:text-green-600 transition">Terms of Service</a>
                <a href="#" class="hover:text-green-600 transition">FAQ</a>
            </div>
        </div>

    </div>
</footer>
