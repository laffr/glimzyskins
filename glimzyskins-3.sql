-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Lis 30, 2025 at 08:33 PM
-- Wersja serwera: 10.4.28-MariaDB
-- Wersja PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `glimzyskins`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `cases`
--

CREATE TABLE `cases` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`id`, `name`, `description`, `price`, `image`, `created_at`) VALUES
(1, 'Premium Mix Case', 'Skrzynka z AWP, Knife, M4A4', 99.99, '/cases/case1.png', '2025-11-30 00:11:32');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `case_items`
--

CREATE TABLE `case_items` (
  `id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image_path` varchar(500) NOT NULL,
  `chance` decimal(6,3) NOT NULL,
  `value` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `case_items`
--

INSERT INTO `case_items` (`id`, `case_id`, `name`, `image_path`, `chance`, `value`) VALUES
(1, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 45.000, 35.00),
(2, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 25.000, 80.00),
(3, 1, 'AWP Vice', '/skins/gloves/vice.png', 15.000, 150.00),
(4, 1, 'AWP POP AWP', '/skins/awp/pop.png', 8.000, 280.00),
(5, 1, 'AWP Ice Coaled', '/skins/awp/icecoaled.png', 4.000, 450.00),
(6, 1, 'M4A4 Howl', '/skins/m4a4/howl.png', 1.500, 3500.00),
(7, 1, 'AWP Lightning Strike', '/skins/awp/lightning.png', 0.800, 1800.00),
(8, 1, 'AWP Fade', '/skins/awp/fade.png', 0.400, 2200.00),
(9, 1, 'Skeleton Knife Ruby', '/skins/knife/skeletonruby.png', 0.250, 15000.00),
(10, 1, 'Karambit Emerald', '/skins/knife/karambitemerald.png', 0.049, 35000.00),
(11, 1, 'AWP Dragon Lore', '/skins/awp/lore.png', 0.001, 25000.00);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `history`
--

CREATE TABLE `history` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action_type` enum('open_case','sell_item','add_to_inventory') NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `item_value` decimal(10,2) DEFAULT NULL,
  `case_name` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history`
--

INSERT INTO `history` (`id`, `user_id`, `action_type`, `item_name`, `item_value`, `case_name`, `amount`, `created_at`) VALUES
(1, 1, 'add_to_inventory', 'AWP Silk Tiger', 35.00, 'Premium Mix Case', NULL, '2025-11-30 13:29:05'),
(2, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 13:29:45'),
(3, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 13:30:32'),
(4, 1, 'add_to_inventory', 'AWP Vice', 150.00, 'Premium Mix Case', NULL, '2025-11-30 13:36:48'),
(5, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 13:36:52'),
(6, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 13:36:52'),
(7, 1, 'add_to_inventory', 'AWP Graffiti', 80.00, 'Premium Mix Case', NULL, '2025-11-30 13:37:24'),
(8, 1, 'add_to_inventory', 'AWP Vice', 150.00, 'Premium Mix Case', NULL, '2025-11-30 13:37:24'),
(9, 1, 'add_to_inventory', 'AWP Silk Tiger', 35.00, 'Premium Mix Case', NULL, '2025-11-30 13:37:24'),
(10, 1, 'add_to_inventory', 'AWP Graffiti', 80.00, 'Premium Mix Case', NULL, '2025-11-30 13:37:24'),
(11, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 13:37:33'),
(12, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 13:37:33'),
(13, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 13:37:33'),
(14, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 13:37:33'),
(15, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 13:37:33'),
(16, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 13:37:33'),
(17, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 13:47:13'),
(18, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 13:47:13'),
(19, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 13:47:13'),
(20, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 13:47:13'),
(21, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 13:47:13'),
(22, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 13:47:13'),
(23, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 13:47:34'),
(24, 1, 'sell_item', NULL, NULL, NULL, NULL, '2025-11-30 14:12:35'),
(25, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 14:15:41'),
(26, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 14:15:44'),
(27, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 14:23:12'),
(28, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 14:23:12'),
(29, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 14:23:12'),
(30, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 14:23:21'),
(31, 1, 'sell_item', NULL, NULL, NULL, NULL, '2025-11-30 15:16:35'),
(32, 1, 'sell_item', NULL, NULL, NULL, NULL, '2025-11-30 15:17:07'),
(33, 1, 'sell_item', NULL, NULL, NULL, NULL, '2025-11-30 15:17:16'),
(34, 1, 'add_to_inventory', 'AWP Vice', 150.00, 'Premium Mix Case', NULL, '2025-11-30 15:17:33'),
(35, 1, 'add_to_inventory', 'AWP Silk Tiger', 35.00, 'Premium Mix Case', NULL, '2025-11-30 15:17:33'),
(36, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 15:17:38'),
(37, 1, 'sell_item', NULL, NULL, NULL, NULL, '2025-11-30 15:26:46'),
(38, 1, 'sell_item', NULL, NULL, NULL, NULL, '2025-11-30 15:50:47'),
(39, 1, 'add_to_inventory', 'AWP Silk Tiger', 35.00, 'Premium Mix Case', NULL, '2025-11-30 15:51:05'),
(40, 1, 'add_to_inventory', 'AWP Silk Tiger', 35.00, 'Premium Mix Case', NULL, '2025-11-30 15:53:44'),
(41, 1, 'sell_item', NULL, NULL, NULL, NULL, '2025-11-30 15:58:10'),
(42, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 15:58:36'),
(43, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 15:58:36'),
(44, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 16:00:17'),
(45, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 16:00:17'),
(46, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 16:00:20'),
(47, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 16:00:20'),
(48, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 16:00:20'),
(49, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 16:00:20'),
(50, 1, 'add_to_inventory', 'AWP Ice Coaled', 450.00, 'Premium Mix Case', NULL, '2025-11-30 16:07:51'),
(51, 1, 'add_to_inventory', 'AWP Silk Tiger', 35.00, 'Premium Mix Case', NULL, '2025-11-30 16:09:23'),
(52, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 16:33:28'),
(53, 1, 'sell_item', 'AWP POP AWP', 280.00, NULL, 280.00, '2025-11-30 16:36:33'),
(54, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 16:38:00'),
(55, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 16:38:14'),
(56, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 16:38:40'),
(57, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 16:39:48'),
(58, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 16:50:49'),
(59, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 16:50:49'),
(60, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 16:50:49'),
(61, 1, 'sell_item', 'AWP POP AWP', 280.00, NULL, 280.00, '2025-11-30 16:50:49'),
(62, 1, 'sell_item', 'AWP Fade', 2200.00, NULL, 2200.00, '2025-11-30 16:50:49'),
(63, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 16:50:49'),
(64, 1, 'sell_item', 'AWP Ice Coaled', 450.00, NULL, 450.00, '2025-11-30 16:50:49'),
(65, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 16:50:49'),
(66, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 16:50:49'),
(67, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 16:50:49'),
(68, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 16:54:16'),
(69, 1, 'sell_item', 'AWP POP AWP', 280.00, NULL, 280.00, '2025-11-30 16:54:16'),
(70, 1, 'sell_item', 'AWP POP AWP', 280.00, NULL, 280.00, '2025-11-30 16:54:16'),
(71, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 16:54:16'),
(72, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 17:13:29'),
(73, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 17:13:29'),
(74, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 17:13:29'),
(75, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 17:13:29'),
(76, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 17:13:29'),
(77, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 17:13:29'),
(78, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 17:13:29'),
(79, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 17:13:29'),
(80, 1, 'sell_item', 'AWP Lightning Strike', 1800.00, NULL, 1800.00, '2025-11-30 17:13:29'),
(81, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 17:13:29'),
(82, 1, 'sell_item', 'AWP POP AWP', 280.00, NULL, 280.00, '2025-11-30 17:13:29'),
(83, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 17:13:29'),
(84, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 17:13:29'),
(85, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 17:13:29'),
(86, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 17:13:29'),
(87, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 17:13:29'),
(88, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 17:13:29'),
(89, 1, 'sell_item', 'AWP POP AWP', 280.00, NULL, 280.00, '2025-11-30 17:13:29'),
(90, 1, 'sell_item', 'AWP POP AWP', 280.00, NULL, 280.00, '2025-11-30 17:13:29'),
(91, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 17:13:29'),
(92, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 17:13:29'),
(93, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 17:13:29'),
(94, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 17:19:31'),
(95, 1, 'sell_item', 'AWP Ice Coaled', 450.00, NULL, 450.00, '2025-11-30 17:19:31'),
(96, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 17:19:31'),
(97, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 17:19:31'),
(98, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 17:19:31'),
(99, 1, 'sell_item', 'AWP Graffiti', 80.00, NULL, 80.00, '2025-11-30 17:19:31'),
(100, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 17:20:23'),
(101, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 17:31:26'),
(102, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 18:23:11'),
(103, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 18:23:11'),
(104, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 18:23:11'),
(105, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 18:23:11'),
(106, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 18:23:11'),
(107, 1, 'sell_item', 'AWP Silk Tiger', 35.00, NULL, 35.00, '2025-11-30 18:23:11'),
(108, 1, 'sell_item', 'AWP Vice', 150.00, NULL, 150.00, '2025-11-30 18:23:11');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_image` varchar(500) NOT NULL,
  `item_value` decimal(10,2) NOT NULL,
  `acquired_from` varchar(255) DEFAULT NULL,
  `acquired_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_sold` tinyint(1) DEFAULT 0,
  `sold_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `user_id`, `item_name`, `item_image`, `item_value`, `acquired_from`, `acquired_at`, `is_sold`, `sold_at`) VALUES
(1, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 13:29:05', 1, '2025-11-30 15:26:46'),
(2, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 13:36:48', 1, '2025-11-30 14:15:44'),
(3, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 13:37:24', 1, '2025-11-30 15:58:10'),
(4, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 13:37:24', 1, '2025-11-30 15:16:35'),
(5, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 13:37:24', 1, '2025-11-30 14:15:41'),
(6, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 13:37:24', 1, '2025-11-30 14:23:12'),
(7, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 15:17:33', 1, '2025-11-30 15:17:38'),
(8, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 15:17:33', 1, '2025-11-30 16:00:20'),
(9, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 15:51:05', 1, '2025-11-30 16:00:20'),
(10, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 15:53:44', 1, '2025-11-30 16:00:20'),
(11, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 15:57:55', 1, '2025-11-30 15:58:36'),
(12, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 15:58:28', 1, '2025-11-30 15:58:36'),
(13, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 16:00:05', 1, '2025-11-30 16:00:20'),
(14, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 16:00:06', 1, '2025-11-30 16:00:17'),
(15, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 16:00:07', 1, '2025-11-30 16:00:17'),
(19, 1, 'AWP Silk Tiger', 'http://localhost/glimzyskins/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 16:11:15', 1, '2025-11-30 16:33:28'),
(20, 1, 'AWP POP AWP', '/skins/awp/pop.png', 280.00, 'Premium Mix Case', '2025-11-30 16:36:16', 1, '2025-11-30 16:36:33'),
(21, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 16:37:57', 1, '2025-11-30 16:38:00'),
(22, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 16:38:12', 1, '2025-11-30 16:38:14'),
(23, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 16:38:30', 1, '2025-11-30 16:38:40'),
(24, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 16:39:42', 1, '2025-11-30 16:39:48'),
(25, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 16:43:08', 1, '2025-11-30 16:50:49'),
(26, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 16:43:23', 1, '2025-11-30 16:50:49'),
(27, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 16:43:35', 1, '2025-11-30 16:50:49'),
(28, 1, 'AWP Ice Coaled', '/skins/awp/icecoaled.png', 450.00, 'Premium Mix Case', '2025-11-30 16:43:36', 1, '2025-11-30 16:50:49'),
(29, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 16:43:37', 1, '2025-11-30 16:50:49'),
(30, 1, 'AWP Fade', '/skins/awp/fade.png', 2200.00, 'Premium Mix Case', '2025-11-30 16:43:38', 1, '2025-11-30 16:50:49'),
(31, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 16:44:03', 1, '2025-11-30 16:50:49'),
(32, 1, 'AWP POP AWP', '/skins/awp/pop.png', 280.00, 'Premium Mix Case', '2025-11-30 16:44:03', 1, '2025-11-30 16:50:49'),
(33, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 16:48:04', 1, '2025-11-30 16:50:49'),
(34, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 16:50:31', 1, '2025-11-30 16:50:49'),
(35, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 16:51:10', 1, '2025-11-30 16:54:16'),
(36, 1, 'AWP POP AWP', '/skins/awp/pop.png', 280.00, 'Premium Mix Case', '2025-11-30 16:51:10', 1, '2025-11-30 16:54:16'),
(37, 1, 'AWP POP AWP', '/skins/awp/pop.png', 280.00, 'Premium Mix Case', '2025-11-30 16:51:10', 1, '2025-11-30 16:54:16'),
(38, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 16:51:10', 1, '2025-11-30 16:54:16'),
(39, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 16:54:16', 1, '2025-11-30 17:13:29'),
(40, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 16:54:49', 1, '2025-11-30 17:13:29'),
(41, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 16:54:53', 1, '2025-11-30 17:13:29'),
(42, 1, 'AWP POP AWP', '/skins/awp/pop.png', 280.00, 'Premium Mix Case', '2025-11-30 16:55:02', 1, '2025-11-30 17:13:29'),
(43, 1, 'AWP POP AWP', '/skins/awp/pop.png', 280.00, 'Premium Mix Case', '2025-11-30 17:00:00', 1, '2025-11-30 17:13:29'),
(44, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 17:00:13', 1, '2025-11-30 17:13:29'),
(45, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 17:02:40', 1, '2025-11-30 17:13:29'),
(46, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 17:04:24', 1, '2025-11-30 17:13:29'),
(47, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 17:04:38', 1, '2025-11-30 17:13:29'),
(48, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 17:06:36', 1, '2025-11-30 17:13:29'),
(49, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 17:07:00', 1, '2025-11-30 17:13:29'),
(50, 1, 'AWP POP AWP', '/skins/awp/pop.png', 280.00, 'Premium Mix Case', '2025-11-30 17:07:18', 1, '2025-11-30 17:13:29'),
(51, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 17:08:12', 1, '2025-11-30 17:13:29'),
(52, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 17:09:48', 1, '2025-11-30 17:13:29'),
(53, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 17:09:48', 1, '2025-11-30 17:13:29'),
(54, 1, 'AWP Lightning Strike', '/skins/awp/lightning.png', 1800.00, 'Premium Mix Case', '2025-11-30 17:09:48', 1, '2025-11-30 17:13:29'),
(55, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 17:10:11', 1, '2025-11-30 17:13:29'),
(56, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 17:10:11', 1, '2025-11-30 17:13:29'),
(57, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 17:10:11', 1, '2025-11-30 17:13:29'),
(58, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 17:10:11', 1, '2025-11-30 17:13:29'),
(59, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 17:10:34', 1, '2025-11-30 17:13:29'),
(60, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 17:13:04', 1, '2025-11-30 17:13:29'),
(61, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 17:14:37', 1, '2025-11-30 17:19:31'),
(62, 1, 'AWP Ice Coaled', '/skins/awp/icecoaled.png', 450.00, 'Premium Mix Case', '2025-11-30 17:19:01', 1, '2025-11-30 17:19:31'),
(63, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 17:19:01', 1, '2025-11-30 17:19:31'),
(64, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 17:19:01', 1, '2025-11-30 17:19:31'),
(65, 1, 'AWP Graffiti', '/skins/awp/grafiti.png', 80.00, 'Premium Mix Case', '2025-11-30 17:19:01', 1, '2025-11-30 17:19:31'),
(66, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 17:19:21', 1, '2025-11-30 17:19:31'),
(67, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 17:19:54', 1, '2025-11-30 17:20:23'),
(68, 1, 'AWP Vice', '/skins/gloves/vice.png', 150.00, 'Premium Mix Case', '2025-11-30 17:23:44', 1, '2025-11-30 18:23:11'),
(69, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 17:26:51', 1, '2025-11-30 17:31:26'),
(70, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 17:31:20', 1, '2025-11-30 18:23:11'),
(71, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 17:38:27', 1, '2025-11-30 18:23:11'),
(72, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 18:22:02', 1, '2025-11-30 18:23:11'),
(73, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 18:22:51', 1, '2025-11-30 18:23:11'),
(74, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 18:23:04', 1, '2025-11-30 18:23:11'),
(75, 1, 'AWP Silk Tiger', '/skins/awp/silktiger.png', 35.00, 'Premium Mix Case', '2025-11-30 18:23:04', 1, '2025-11-30 18:23:11');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `birth_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `balance`, `birth_date`, `created_at`, `updated_at`) VALUES
(1, 'wiktorjan1111@gmail.com', 'aaa', '$2y$10$t/3COlnIMqD6xrZN4wz8O./rd2959nU7Y7zJ6HdHazICiAyHttDoO', 5065.88, '2007-11-11', '2025-11-20 07:22:18', NULL),
(2, 'laffrkontakt@gmail.com', 'laffr', '$2y$10$ZWu2zxVSDZAiygq8MF9GveeJxvVJJ.lgkXUNwkeQMWL5IrQjHjuzK', 600.00, '2007-11-11', '2025-11-30 18:07:19', NULL);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `case_items`
--
ALTER TABLE `case_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `case_id` (`case_id`);

--
-- Indeksy dla tabeli `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_history_user` (`user_id`);

--
-- Indeksy dla tabeli `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_inventory_user` (`user_id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `case_items`
--
ALTER TABLE `case_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `case_items`
--
ALTER TABLE `case_items`
  ADD CONSTRAINT `case_items_ibfk_1` FOREIGN KEY (`case_id`) REFERENCES `cases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `fk_history_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `fk_inventory_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
