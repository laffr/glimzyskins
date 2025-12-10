-- phpMyAdmin SQL Dump
-- Database: glimzyskins - Extended Schema for Cases System

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

USE `glimzyskins`;

-- --------------------------------------------------------

--
-- Rozszerzenie tabeli users o saldo
--

ALTER TABLE `users` 
ADD COLUMN `balance` DECIMAL(10,2) NOT NULL DEFAULT 1000.00 AFTER `password`;

-- --------------------------------------------------------

--
-- Table structure for table `rarity`
--

CREATE TABLE IF NOT EXISTS `rarity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `color` varchar(20) NOT NULL,
  `chance_percent` decimal(5,2) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rarity`
--

INSERT INTO `rarity` (`id`, `name`, `color`, `chance_percent`, `order`) VALUES
(1, 'Exceedingly Rare', '#FFD700', 0.10, 1),
(2, 'Covert', '#EB4B4B', 2.00, 2),
(3, 'Classified', '#D32CE6', 5.00, 3),
(4, 'Restricted', '#8847FF', 15.00, 4),
(5, 'Mil-Spec', '#4B69FF', 35.00, 5),
(6, 'Consumer Grade', '#B0C3D9', 42.90, 6);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `weapon_type` varchar(50) NOT NULL,
  `image_path` varchar(500) NOT NULL,
  `rarity_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `rarity_id` (`rarity_id`),
  CONSTRAINT `items_ibfk_1` FOREIGN KEY (`rarity_id`) REFERENCES `rarity` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE IF NOT EXISTS `cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `image_path` varchar(500),
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`id`, `name`, `description`, `price`, `is_active`) VALUES
(1, 'Glimzy Case #1', 'Pierwsza skrzynka z kolekcją skinów', 2.50, 1),
(2, 'Glimzy Case #2', 'Druga skrzynka z kolekcją skinów', 2.50, 1);

-- --------------------------------------------------------

--
-- Table structure for table `case_items`
--

CREATE TABLE IF NOT EXISTS `case_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `case_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `chance_percent` decimal(5,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `case_id` (`case_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `case_items_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`),
  CONSTRAINT `case_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_items`
--

CREATE TABLE IF NOT EXISTS `user_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `case_id` int(11) DEFAULT NULL,
  `obtained_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `item_id` (`item_id`),
  KEY `case_id` (`case_id`),
  CONSTRAINT `user_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`),
  CONSTRAINT `user_items_ibfk_3` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` enum('purchase','case_open','item_sell','deposit','withdraw') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `description` text,
  `case_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `case_id` (`case_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`),
  CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

