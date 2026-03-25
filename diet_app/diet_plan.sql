-- phpMyAdmin SQL Dump
-- Diet & Health Recommendation System
-- Compatible with XAMPP (MariaDB / MySQL)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `diet_health_system`
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE `diet_health_system`;

-- --------------------------------------------------------
-- USERS TABLE (REUSED FROM AUCTION PROJECT)
-- --------------------------------------------------------

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'default_profile.jpg',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- DISEASES TABLE
-- --------------------------------------------------------

CREATE TABLE `diseases` (
  `disease_id` int(11) NOT NULL AUTO_INCREMENT,
  `disease_name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`disease_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `diseases` (`disease_name`, `description`) VALUES
('Diabetes', 'A condition that affects blood sugar levels'),
('Blood Pressure', 'High or low blood pressure related conditions'),
('Thyroid', 'Hormonal imbalance related to thyroid gland');

-- --------------------------------------------------------
-- USER HEALTH PROFILE
-- --------------------------------------------------------

CREATE TABLE `user_health_profile` (
  `profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `age` int(11) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `height_cm` float NOT NULL,
  `weight_kg` float NOT NULL,
  `bmi` float NOT NULL,
  `disease_id` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`profile_id`),
  KEY `user_id` (`user_id`),
  KEY `disease_id` (`disease_id`),
  CONSTRAINT `health_user_fk`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
    ON DELETE CASCADE,
  CONSTRAINT `health_disease_fk`
    FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`disease_id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- DIET PLANS TABLE
-- --------------------------------------------------------

CREATE TABLE `diet_plans` (
  `diet_id` int(11) NOT NULL AUTO_INCREMENT,
  `disease_id` int(11) NOT NULL,
  `breakfast` text NOT NULL,
  `lunch` text NOT NULL,
  `dinner` text NOT NULL,
  `snacks` text NOT NULL,
  `avoid_foods` text NOT NULL,
  PRIMARY KEY (`diet_id`),
  KEY `disease_id` (`disease_id`),
  CONSTRAINT `diet_disease_fk`
    FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`disease_id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `diet_plans`
(disease_id, breakfast, lunch, dinner, snacks, avoid_foods)
VALUES
(1, 'Oats, boiled eggs, green tea',
    'Brown rice, dal, vegetables',
    'Grilled chicken, salad',
    'Fruits, nuts',
    'Sugar, sweets, soft drinks'),

(2, 'Low-salt oats, fruits',
    'Chapati, vegetables',
    'Soup, salad',
    'Roasted seeds',
    'Extra salt, fried food'),

(3, 'Boiled eggs, fruits',
    'Rice, vegetables',
    'Light curry, chapati',
    'Nuts',
    'Processed food');

-- --------------------------------------------------------
-- MEDICINES TABLE (INFORMATIONAL ONLY)
-- --------------------------------------------------------

CREATE TABLE `medicines` (
  `medicine_id` int(11) NOT NULL AUTO_INCREMENT,
  `disease_id` int(11) NOT NULL,
  `medicine_name` varchar(100) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY (`medicine_id`),
  KEY `disease_id` (`disease_id`),
  CONSTRAINT `medicine_disease_fk`
    FOREIGN KEY (`disease_id`) REFERENCES `diseases` (`disease_id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `medicines`
(disease_id, medicine_name, note)
VALUES
(1, 'Metformin', 'Consult doctor before use'),
(2, 'Amlodipine', 'Do not self-medicate'),
(3, 'Levothyroxine', 'Doctor prescription required');

COMMIT;
