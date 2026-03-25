-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2026 at 07:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `diet_health_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `daily_health_log`
--

CREATE TABLE `daily_health_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `calories` int(11) DEFAULT 0,
  `water` float DEFAULT 0,
  `weight` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `daily_health_log`
--

INSERT INTO `daily_health_log` (`id`, `user_id`, `log_date`, `calories`, `water`, `weight`, `created_at`) VALUES
(1, 1, '2026-01-10', 2200, 2.5, NULL, '2026-01-16 15:26:18'),
(2, 1, '2026-01-11', 2400, 3, NULL, '2026-01-16 15:26:18'),
(3, 3, '2026-01-12', 2100, 2.8, NULL, '2026-01-16 15:26:18'),
(8, 2, '2026-01-14', 2100, 2.8, NULL, '2026-01-16 15:46:24'),
(9, 2, '2026-01-15', 2300, 3.2, NULL, '2026-01-16 15:46:24'),
(10, 2, '2026-01-16', 2200, 3, NULL, '2026-01-16 15:46:24'),
(11, 4, '2026-01-16', 200, 3, 22, '2026-01-16 16:17:49'),
(20, 1, '2026-01-16', 1900, 4, 69, '2026-01-16 18:28:52');

-- --------------------------------------------------------

--
-- Table structure for table `diet_plans`
--

CREATE TABLE `diet_plans` (
  `diet_id` int(11) NOT NULL,
  `disease_id` int(11) NOT NULL,
  `breakfast` text NOT NULL,
  `lunch` text NOT NULL,
  `dinner` text NOT NULL,
  `snacks` text NOT NULL,
  `avoid_foods` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diet_plans`
--

INSERT INTO `diet_plans` (`diet_id`, `disease_id`, `breakfast`, `lunch`, `dinner`, `snacks`, `avoid_foods`) VALUES
(1, 1, 'Oats, boiled eggs, green tea', 'Brown rice, dal, vegetables', 'Grilled chicken, salad', 'Fruits, nuts', 'Sugar, sweets, soft drinks'),
(2, 2, 'Low-salt oats, fruits', 'Chapati, vegetables', 'Soup, salad', 'Roasted seeds', 'Extra salt, fried food'),
(3, 3, 'Boiled eggs, fruits', 'Rice, vegetables', 'Light curry, chapati', 'Nuts', 'Processed food');

-- --------------------------------------------------------

--
-- Table structure for table `diseases`
--

CREATE TABLE `diseases` (
  `disease_id` int(11) NOT NULL,
  `disease_name` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `diseases`
--

INSERT INTO `diseases` (`disease_id`, `disease_name`, `description`) VALUES
(1, 'Diabetes', 'A condition that affects blood sugar levels'),
(2, 'Blood Pressure', 'High or low blood pressure related conditions'),
(3, 'Thyroid', 'Hormonal imbalance related to thyroid gland');

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `medicine_id` int(11) NOT NULL,
  `disease_id` int(11) NOT NULL,
  `medicine_name` varchar(100) NOT NULL,
  `note` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`medicine_id`, `disease_id`, `medicine_name`, `note`) VALUES
(1, 1, 'Metformin', 'Consult doctor before use'),
(2, 2, 'Amlodipine', 'Do not self-medicate'),
(3, 3, 'Levothyroxine', 'Doctor prescription required');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default_profile.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `first_name`, `last_name`, `profile_image`, `created_at`, `updated_at`) VALUES
(1, 'rehan', 'bubnalerehan17@gmail.com', '$2y$10$cVXl4j4awE2tcNX0Wz/PVu.Uy9bhh1553Efz.CpBGo11ZnmNTCTkG', 'Rehan', 'Bubnale', 'default_profile.jpg', '2026-01-14 18:45:42', '2026-01-14 18:45:42'),
(2, 'vaishnavi01', 'vaishnavikumbhar@gmail.com', '$2y$10$nEMUnc3zJ.0tur998tv45evARp7VxReEoUBgHXnWhwLrSdV8FJK5u', 'Vaishnavi', 'Kumbhar', 'default_profile.jpg', '2026-01-15 16:33:09', '2026-01-15 16:33:09');

-- --------------------------------------------------------

--
-- Table structure for table `user_health_profile`
--

CREATE TABLE `user_health_profile` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `height_cm` float NOT NULL,
  `weight_kg` float NOT NULL,
  `bmi` float NOT NULL,
  `disease_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_health_profile`
--

INSERT INTO `user_health_profile` (`profile_id`, `user_id`, `age`, `gender`, `height_cm`, `weight_kg`, `bmi`, `disease_id`, `updated_at`) VALUES
(1, 1, 23, 'Male', 200, 70, 17.5, 2, '2026-01-16 15:56:30'),
(2, 2, 20, 'Female', 162, 45, 17.1468, 1, '2026-01-16 15:37:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `daily_health_log`
--
ALTER TABLE `daily_health_log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_day` (`user_id`,`log_date`);

--
-- Indexes for table `diet_plans`
--
ALTER TABLE `diet_plans`
  ADD PRIMARY KEY (`diet_id`),
  ADD KEY `disease_id` (`disease_id`);

--
-- Indexes for table `diseases`
--
ALTER TABLE `diseases`
  ADD PRIMARY KEY (`disease_id`);

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
  ADD PRIMARY KEY (`medicine_id`),
  ADD KEY `disease_id` (`disease_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_health_profile`
--
ALTER TABLE `user_health_profile`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `disease_id` (`disease_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `daily_health_log`
--
ALTER TABLE `daily_health_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `diet_plans`
--
ALTER TABLE `diet_plans`
  MODIFY `diet_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `diseases`
--
ALTER TABLE `diseases`
  MODIFY `disease_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_health_profile`
--
ALTER TABLE `user_health_profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `diet_plans`
--
ALTER TABLE `diet_plans`
  ADD CONSTRAINT `diet_disease_fk` FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`disease_id`) ON DELETE CASCADE;

--
-- Constraints for table `medicines`
--
ALTER TABLE `medicines`
  ADD CONSTRAINT `medicine_disease_fk` FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`disease_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_health_profile`
--
ALTER TABLE `user_health_profile`
  ADD CONSTRAINT `health_disease_fk` FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`disease_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `health_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
