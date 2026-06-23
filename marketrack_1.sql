-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2025 at 03:22 PM
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
-- Database: `marketrack`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `contact_number` varchar(30) DEFAULT NULL,
  `store_name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `reset_code` varchar(10) DEFAULT NULL,
  `code_expiration` datetime DEFAULT NULL,
  `failed_attempts` int(11) DEFAULT 0,
  `last_failed_login` datetime DEFAULT NULL,
  `is_locked` tinyint(1) DEFAULT 0,
  `reset_expiration` datetime DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `fullname`, `username`, `password`, `contact_number`, `store_name`, `address`, `reset_code`, `code_expiration`, `failed_attempts`, `last_failed_login`, `is_locked`, `reset_expiration`, `email`) VALUES
(1, 'Via Umali', 'Via', '$2y$10$Ko9tF573SI8iX6vpxkTbkeKdIDzdfR7B8Cz5dr4OFj7lSuVSa7yWu', '09925228674', 'Via\'s Sari-Sari Store', 'Brgy. San Jose Tiaong, Quezon', '297635', '2025-05-30 04:48:31', 0, NULL, 0, NULL, NULL),
(2, 'Keisha Coronado', 'Kish', '$2y$10$t6/9nWhJ7QH1JwX6npNZmOwPwO6psYYDJ4pRz6BiatzoBVfMF4hZG', '09919199884', 'Kishabol\'s store', 'Barangay 123 Nagcarlan, Laguna', NULL, NULL, 0, NULL, 0, NULL, NULL),
(3, 'Pauline Jayme', 'Pau', '$2y$10$6gp2LrVJwtkAAFoqDYL9gubsa6d01uOyObIrjNbVw1xrJ7M1iaU4W', '09126972516', 'paupau\'s store', 'Barangay 456 Bay, Laguna', NULL, NULL, 0, NULL, 0, NULL, NULL),
(4, 'hays', 'hays', '$2y$10$mYnugMVjldjWO03ZgGh/I.ATOhCCNlSMcMbJe12AwqPazXZlf6uvO', '09924804388', 'hays', 'hays', NULL, NULL, 0, NULL, 0, NULL, NULL),
(5, 'Via Vinus', 'Vinus', '$2y$10$ZMQWPwmkfrs1ITqGjbT3PO5T7gKIiFcRejbnbRNcV9xS6GHO2haYC', '09986544322', 'vinushis store', 'tiaong quezon', '754212', NULL, 0, NULL, 0, '2025-06-01 15:07:04', NULL),
(6, 'clarence', 'cla', '$2y$10$coNhKHCFVhz.ZKgDHcWlYupJMpn35DbKqNwpMLjZopiMOc5nNvb9y', '09919934827', 'clarence store', 'san jose tiaong quezon', '162550', NULL, 0, NULL, 0, '2025-06-01 15:09:36', NULL),
(7, 'Mama', 'Mama', '$2y$10$LetnMBvWe7IgAm7OB7IoRuX4sQEN320KybeSbo3Bmg1nhXIEJbEZu', '09924804388', 'mama\'s store', 'san jose tiaong quezon', '826962', NULL, 0, NULL, 0, '2025-06-01 16:16:39', NULL),
(8, 'Via Vinus Umali', 'Via_Vinus', '$2y$10$B94f9kZcjkVHqYjrN700bu/733OARnoYG3jrQ1dVfaZoThAiyP9Qy', '09126972516', 'Via\'s Store', 'brgy. san jose tiaong quezon', '478765', NULL, 0, NULL, 0, '2025-06-01 16:22:39', NULL),
(9, 'Shane Pauline Jayme', 'Shane', '$2y$10$YxqsaxFoEIglfnCGlxfA1eK6/V.ejlD.tHf6lM2RfPq9TmGDRWZh.', '09986532110', 'pau\'s store', 'bay laguna', NULL, NULL, 0, NULL, 0, NULL, NULL),
(10, 'clarence', 'clarence', '$2y$10$z0WjCbe0Y2MDau4i1IjeiO8PnqquuGPhbvs3mI8WTASRlBQlFhz4i', '09919934827', 'clarence', 'san jose tiaong quezon', NULL, NULL, 0, NULL, 0, NULL, NULL),
(11, 'cris', 'cri', '$2y$10$AyJXLtSCSbipHa0Gbf8L8uhUQTp93fuMvpitDYgrGsNcjjLlVFlKe', '09875443245', 'cristian store', 'spc', NULL, NULL, 0, NULL, 0, NULL, NULL),
(12, 'kuto', 'kuto', '$2y$10$ZKokiz2.4ILz3Szd0DKNa.pdC8N/0lzdsyq7P6ak61zYaut4WIxEm', '09126972516', 'kuto', 'brgy. kuto ', NULL, NULL, 0, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','processing','declined','completed','cancelled') DEFAULT 'pending',
  `rated` tinyint(1) DEFAULT 0,
  `promotion_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `total_price`, `order_date`, `status`, `rated`, `promotion_id`) VALUES
(1, 1, NULL, 13.50, '2025-05-09 16:51:39', 'completed', 1, NULL),
(2, 1, NULL, 138.50, '2025-05-09 17:25:21', 'completed', 1, NULL),
(3, 1, NULL, 380000.00, '2025-05-09 18:32:05', 'completed', 1, NULL),
(4, 1, NULL, 8.00, '2025-05-09 18:42:05', 'completed', 1, NULL),
(5, 3, NULL, 90.00, '2025-05-13 11:24:52', 'cancelled', 0, NULL),
(6, 7, NULL, 299.00, '2025-05-13 11:57:25', 'processing', 0, NULL),
(7, 2, NULL, 138.50, '2025-05-13 12:02:55', 'completed', 1, NULL),
(8, 5, NULL, 8.00, '2025-05-13 23:35:04', 'completed', 1, NULL),
(9, 2, NULL, 138.50, '2025-05-14 07:56:33', 'completed', 0, NULL),
(10, 5, NULL, 168.00, '2025-05-14 09:10:15', 'processing', 0, NULL),
(11, 1, NULL, 168.00, '2025-05-14 09:11:13', 'processing', 0, NULL),
(12, 5, NULL, 168.00, '2025-05-14 09:25:06', 'completed', 1, NULL),
(13, 1, NULL, 13.00, '2025-05-14 09:27:13', 'completed', 0, NULL),
(14, 5, NULL, 168.00, '2025-05-14 09:47:41', 'completed', 0, NULL),
(15, 5, NULL, 38.00, '2025-05-14 10:13:56', 'completed', 0, NULL),
(16, 1, NULL, 15.00, '2025-05-14 13:15:29', 'completed', 1, NULL),
(17, 1, NULL, 53.00, '2025-05-14 21:05:15', 'completed', 1, NULL),
(18, 1, NULL, 38.00, '2025-05-14 22:29:22', 'cancelled', 0, NULL),
(19, 1, NULL, 38.00, '2025-05-14 22:29:34', 'cancelled', 0, NULL),
(20, 1, NULL, 138.50, '2025-05-14 22:31:03', 'cancelled', 0, NULL),
(21, 1, NULL, 38.00, '2025-05-14 22:31:35', 'declined', 0, NULL),
(22, 3, NULL, 45.00, '2025-05-14 23:26:20', 'completed', 1, NULL),
(23, 1, NULL, 38.00, '2025-05-15 05:17:18', 'completed', 1, NULL),
(24, 7, NULL, 138.50, '2025-05-15 06:15:31', 'completed', 0, NULL),
(25, 11, NULL, 38.00, '2025-07-15 09:30:42', 'processing', 0, 5),
(26, 6, NULL, 38.00, '2025-07-15 10:31:18', 'processing', 0, 5),
(27, 11, NULL, 80.00, '2025-07-15 10:39:18', 'processing', 0, 6),
(28, 4, NULL, 299.00, '2025-07-17 08:40:45', 'processing', 0, 8),
(29, 4, NULL, 148.80, '2025-07-19 06:24:01', 'processing', 0, 7),
(30, 12, NULL, 38.00, '2025-08-13 01:27:55', 'pending', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variation_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `variation_id`, `quantity`, `price`) VALUES
(1, 1, 18, 50, 1, 13.00),
(2, 2, 2, 4, 1, 138.00),
(3, 3, 1, 1, 10000, 38.00),
(4, 4, 11, 36, 1, 8.00),
(5, 5, 3, 13, 2, 45.00),
(6, 6, 19, 54, 1, 299.00),
(7, 7, 2, 6, 1, 138.00),
(8, 8, 11, 36, 1, 8.00),
(9, 9, 2, 4, 1, 138.00),
(10, 10, 19, 55, 1, 168.00),
(11, 11, 19, 55, 1, 168.00),
(12, 12, 19, 55, 1, 168.00),
(13, 13, 18, 48, 1, 13.00),
(14, 14, 19, 55, 1, 168.00),
(15, 15, 1, 1, 1, 38.00),
(16, 16, 10, 35, 1, 15.00),
(17, 17, 7, 28, 1, 53.00),
(18, 18, 1, 1, 1, 38.00),
(19, 19, 1, 1, 1, 38.00),
(20, 20, 2, 4, 1, 138.00),
(21, 21, 1, 1, 1, 38.00),
(22, 22, 3, 13, 1, 45.00),
(23, 23, 1, 1, 1, 38.00),
(24, 24, 2, 4, 1, 138.00),
(25, 25, 1, 1, 1, 38.00),
(26, 26, 1, 1, 1, 38.00),
(27, 27, 3, 12, 1, 80.00),
(28, 28, 19, 54, 1, 299.00),
(29, 29, 6, 27, 3, 49.00),
(30, 30, 1, 1, 1, 38.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_ratings`
--

CREATE TABLE `order_ratings` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_item_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `product_quality` tinyint(4) NOT NULL,
  `delivery_service` tinyint(4) NOT NULL,
  `overall_satisfaction` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_ratings`
--

INSERT INTO `order_ratings` (`id`, `order_id`, `order_item_id`, `user_id`, `product_quality`, `delivery_service`, `overall_satisfaction`, `created_at`) VALUES
(1, 1, NULL, 5, 5, 5, 5, '2025-05-10 02:29:03'),
(2, 2, NULL, 5, 5, 5, 5, '2025-05-10 02:30:36'),
(3, 3, NULL, 5, 5, 1, 3, '2025-05-10 02:32:58'),
(4, 4, NULL, 5, 1, 2, 3, '2025-05-10 02:42:48'),
(5, 7, NULL, 2, 5, 5, 5, '2025-05-13 20:09:12'),
(6, 8, NULL, 5, 5, 5, 5, '2025-05-14 15:59:05'),
(7, 12, NULL, 5, 5, 5, 5, '2025-05-14 17:34:24'),
(8, 16, NULL, 1, 5, 5, 5, '2025-05-14 21:18:50'),
(9, 17, NULL, 1, 5, 5, 5, '2025-05-15 05:08:25'),
(10, 22, NULL, 3, 5, 5, 5, '2025-05-15 07:29:44'),
(11, 23, NULL, 1, 5, 5, 5, '2025-05-15 13:18:24');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `username`, `token`, `expires_at`) VALUES
(1, 'Via', '43a5779597efce2cac795e5cef95bd60', '2025-06-01 14:07:52');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `categories` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `demand_factor` decimal(3,2) DEFAULT 1.00,
  `cost_of_production` decimal(10,2) DEFAULT 0.00,
  `seasonality` varchar(20) DEFAULT 'Normal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `categories`, `price`, `created_at`, `image`, `demand_factor`, `cost_of_production`, `seasonality`) VALUES
(1, 'Kopiko', 'Candy', 0.00, '2025-04-27 12:39:10', 'new-kopiko-logo.jpg', 1.00, 0.00, 'Normal'),
(2, 'Fres Mint', 'Candy', 0.00, '2025-04-27 12:43:30', '2017-fres-logo.png', 1.00, 0.00, 'Normal'),
(3, 'Beng Beng', 'Wafer and Chocolate', 0.00, '2025-04-27 12:45:00', '2017-bengbeng-copy.png', 1.00, 0.00, 'Normal'),
(4, 'Cal Cheese Wafer', 'Wafer and Chocolate', 0.00, '2025-04-27 12:51:02', 'calchesee.jpg', 1.00, 0.00, 'Normal'),
(5, 'Wafello', 'Wafer and Chocolate', 0.00, '2025-04-27 12:57:30', 'logo-wafello.jpg', 1.00, 0.00, 'Normal'),
(6, 'Superstar', 'Wafer and Chocolate', 0.00, '2025-04-27 12:58:58', '2017-superstar-copy.png', 1.00, 0.00, 'Normal'),
(7, 'Malkist', 'Biscuit', 0.00, '2025-04-27 13:03:13', '1thumbnail-malkist.png', 1.00, 0.00, 'Normal'),
(8, 'Wow Pasta', 'Instant Food', 0.00, '2025-04-27 13:05:27', 'wowspageti.jpg', 1.00, 0.00, 'Normal'),
(9, 'Kopiko Lucky Day', 'Beverages', 0.00, '2025-04-27 13:07:17', 'logo-kopiko-lucky-day.png', 1.00, 0.00, 'Normal'),
(10, 'Pucuk Harum', 'Beverages', 0.00, '2025-04-27 13:08:51', '1thumbnail-pucuk.png', 1.00, 0.00, 'Normal'),
(11, 'Le Minerale', 'Beverages', 0.00, '2025-04-27 13:11:06', 'le-minerale-pack.png', 1.00, 0.00, 'Normal'),
(17, 'Energen', 'Cereal', 0.00, '2025-05-03 05:26:56', 'energen.jpg', 1.00, 0.00, 'Normal'),
(18, 'Kopiko', 'Coffee', 0.00, '2025-05-03 05:32:07', 'kopiko-coffee-pack.png', 1.00, 0.00, 'Normal'),
(19, 'Danisa', 'Biscuit', 0.00, '2025-05-09 14:25:50', 'danisa.png', 1.00, 0.00, 'Normal');

-- --------------------------------------------------------

--
-- Table structure for table `product_variations`
--

CREATE TABLE `product_variations` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `flavor` varchar(100) NOT NULL,
  `pack_size` varchar(100) NOT NULL,
  `price_unit` decimal(10,2) NOT NULL,
  `price_case` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `product_expiration` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variations`
--

INSERT INTO `product_variations` (`id`, `product_id`, `flavor`, `pack_size`, `price_unit`, `price_case`, `stock`, `product_expiration`) VALUES
(1, 1, 'KOPIKO COFFEE CANDY', '24X175G', 38.00, 1003.20, 197, '2026-01-01'),
(2, 1, 'KOPIKO COFFEE CANDY JAR', '6X560G', 111.00, 666.00, 200, '2026-01-01'),
(3, 1, 'KOPIKO CAPPUCCINO CANDY', '24X175G', 38.00, 912.00, 200, '2026-01-01'),
(4, 2, 'FRES MIXED CANDY JAR', '12X600G', 138.50, 1662.00, 197, '2026-01-01'),
(5, 2, 'FRES MINT BARLEY', '24X50X3G', 38.76, 930.24, 200, '2026-01-01'),
(6, 2, 'FRES MINT BARLEY', '12X200X3G', 138.50, 1662.00, 198, '2026-01-01'),
(7, 2, 'FRES MINT CHERRY', '24X50X3G', 38.76, 930.24, 200, '2026-01-01'),
(8, 2, 'FRES MINT CHERRY JAR', '12X200X3G', 138.50, 1662.00, 200, '2026-01-01'),
(9, 2, 'FRES MINT GRAPE', '24X50X3G', 38.76, 930.24, 200, '2026-01-01'),
(10, 2, 'FRES MINT GRAPE JAR', '12X200X3G', 138.50, 1662.00, 200, '2026-01-01'),
(11, 2, 'FRES APPLE PEACH CANDY', '24X150G', 38.76, 930.24, 200, '2026-01-01'),
(12, 3, 'BENG BENG CHOCOLATE', '12X10X26.5G', 80.00, 960.00, 200, '2026-01-01'),
(13, 3, 'BENG BENG SHARE IT', '16X95G', 45.00, 720.00, 199, '2026-01-01'),
(14, 4, 'CAL CHEESE WAFER', '20X20X8.5G', 48.00, 960.00, 200, '2026-01-01'),
(15, 4, 'CAL CHEESE CHEESE WAFER', '60X48G', 11.90, 714.00, 200, '2026-01-01'),
(16, 4, 'CAL CHEESE CHEESE WAFER', '20X10X20G', 57.10, 1142.00, 200, '2026-01-01'),
(17, 4, 'CAL CHEESE CHEESE CHOCO', '60X48G', 11.90, 714.00, 200, '2026-01-01'),
(18, 4, 'CAL CHEESE CHEESE CHOCO', '20X10X20.5G', 57.10, 1142.00, 200, '2026-01-01'),
(19, 5, 'WAFELLO CHOCO WAFER', '60X48G', 11.90, 714.00, 200, '2026-01-01'),
(20, 5, 'WAFELLO CHOCO WAFER', '20X10X21G', 57.10, 1142.00, 200, '2026-01-01'),
(21, 5, 'WAFELLO COCO CREME', '20X10X20.5G', 57.10, 1142.00, 200, '2026-01-01'),
(22, 5, 'WAFELLO COCO CREME', '60X48G', 11.90, 714.00, 200, '2026-01-01'),
(23, 5, 'WAFELLO BUTTER CARAMEL', '60X48G', 11.90, 714.00, 200, '2026-01-01'),
(24, 5, 'WAFELLO BUTTER CARAMEL', '20X10X20.5G', 57.10, 1142.00, 200, '2026-01-01'),
(25, 5, 'WAFELLO CREAMY VANILLA', '60X48G', 10.50, 630.00, 200, '2026-01-01'),
(26, 5, 'WAFELLO CREAMY VANILLA', '20X10', 55.00, 1100.00, 200, '2026-01-01'),
(27, 6, 'SUPERSTAR', '12X10X16G', 49.60, 595.20, 197, '2026-01-01'),
(28, 7, 'MALKIST CAPPUCCINO', '30X10X18G', 53.00, 1590.00, 199, '2026-01-01'),
(29, 7, 'MALKIST CHOCOLATE', '30X10X18G', 53.00, 1590.00, 200, '2026-01-01'),
(30, 7, 'MALKIST BARBECUE', '12X10X28G', 47.60, 571.20, 200, '2026-01-01'),
(31, 7, 'MALKIST SWEET GLAZED', '12X10X28G', 47.60, 571.20, 200, '2026-01-01'),
(32, 8, 'WOW PASTA CARBONARA', '12X5X88G', 66.50, 798.00, 200, '2026-01-01'),
(33, 8, 'WOW PASTA SPAGHETTI', '12X5X86G', 66.50, 798.00, 200, '2026-01-01'),
(34, 9, 'KOPIKO LUCKY DAY', '24X180ML', 20.00, 480.00, 200, '2026-01-01'),
(35, 10, 'PUCUK HARUM', '24BTLX350ML PH', 15.00, 360.00, 199, '2026-01-01'),
(36, 11, 'LE MINERALE', '24X330ML', 8.00, 192.00, 198, '2026-01-01'),
(37, 11, 'LE MINERALE', '24X600ML', 11.50, 276.00, 200, '2026-01-01'),
(38, 11, 'LE MINERALE', '12X1500ML', 22.00, 264.00, 200, '2026-01-01'),
(39, 11, 'LE MINERALE', '4X5000ML', 76.00, 304.00, 200, '2026-01-01'),
(44, 17, 'Chocolate', '30*40g', 9.25, 277.50, 200, '2026-01-01'),
(45, 17, 'Vanilla', '30*40g', 8.85, 265.50, 200, '2026-01-01'),
(46, 17, 'Champion (Choco)', '30*40g', 9.00, 270.00, 200, '2026-01-01'),
(47, 17, 'Pandesal Mate', '30*40g', 9.00, 270.00, 200, '2026-01-01'),
(48, 18, 'Black 3 in 1', '10*25g', 13.00, 130.00, 199, '2026-01-01'),
(49, 18, 'Brown Coffee 3 in 1', '10*27.5g', 13.00, 130.00, 200, '2026-01-01'),
(50, 18, 'Blanca Creamy Coffee', '10*30g', 13.00, 135.00, 200, '2026-01-01'),
(51, 18, 'Black 2 in 1', '10*25g', 12.00, 120.00, 200, '2026-01-01'),
(52, 18, 'Brown 2 in 1', '10*27.5g', 12.00, 120.00, 200, '2026-01-01'),
(53, 18, 'Blanca 2 in 1', '10*30g', 12.50, 125.00, 200, '2026-01-01'),
(54, 19, 'Danisa Traditional Butter Cookies', '12*454g Tin', 299.00, 3588.00, 198, '2026-01-01'),
(55, 19, 'Danisa Premium Butter Cookies', '8*162g Box', 168.00, 1344.00, 197, '2026-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `promotion_type` varchar(50) DEFAULT NULL,
  `promo_title` varchar(255) DEFAULT NULL,
  `promo_description` text DEFAULT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `cashback_amount` decimal(10,2) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `min_purchase` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_by` varchar(100) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `promotion_type`, `promo_title`, `promo_description`, `discount_percentage`, `cashback_amount`, `start_date`, `end_date`, `min_purchase`, `created_at`, `status`, `created_by`, `approved_at`) VALUES
(7, 'bundle', 'buy 3 for 100 pesos only', 'buy 3 for 100 pesos', 0.00, NULL, '2025-07-18', '2025-08-18', 100.00, '2025-07-17 08:24:40', 'approved', '6', '2025-07-17 16:24:58'),
(8, 'buy1take1', 'buy 1 take 1', 'buy 1 take 1', 0.00, NULL, '2025-07-17', '2025-08-17', 100.00, '2025-07-17 08:38:26', 'approved', '6', '2025-07-17 16:38:46');

-- --------------------------------------------------------

--
-- Table structure for table `promotion_products`
--

CREATE TABLE `promotion_products` (
  `id` int(11) NOT NULL,
  `promotion_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotion_products`
--

INSERT INTO `promotion_products` (`id`, `promotion_id`, `product_id`) VALUES
(10, 7, 6),
(11, 8, 19),
(12, 8, 19);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `date_sold` date NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `promo` enum('yes','no') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_batches`
--

CREATE TABLE `stock_batches` (
  `id` int(11) NOT NULL,
  `variation_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `expiration_date` date NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp(),
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_batches`
--

INSERT INTO `stock_batches` (`id`, `variation_id`, `quantity`, `expiration_date`, `date_added`, `stock`) VALUES
(1, 55, 0, '2026-01-01', '2025-05-14 08:43:04', 199),
(2, 54, 0, '2026-01-01', '2025-05-14 08:52:18', 199),
(3, 53, 0, '2026-01-01', '2025-05-14 08:53:29', 200),
(5, 52, 0, '2026-01-01', '2025-05-14 08:55:08', 200),
(6, 51, 0, '2026-01-01', '2025-05-14 08:55:51', 200),
(7, 50, 0, '2026-01-01', '2025-05-14 08:56:09', 200),
(8, 49, 0, '2026-01-01', '2025-05-14 08:56:22', 200),
(9, 48, 0, '2026-01-01', '2025-05-14 08:56:37', 200),
(10, 47, 0, '2026-01-01', '2025-05-14 08:56:48', 200),
(11, 46, 0, '2026-01-01', '2025-05-14 08:57:06', 200),
(12, 45, 0, '2026-01-01', '2025-05-14 08:57:21', 200),
(13, 44, 0, '2026-01-01', '2025-05-14 08:57:41', 200),
(14, 39, 0, '2026-01-01', '2025-05-14 08:58:19', 200),
(15, 38, 0, '2026-01-01', '2025-05-14 08:58:57', 200),
(16, 37, 0, '2026-01-01', '2025-05-14 08:59:10', 200),
(17, 36, 0, '2026-01-01', '2025-05-14 08:59:27', 200),
(18, 35, 0, '2026-01-01', '2025-05-14 08:59:47', 199),
(19, 34, 0, '2026-01-01', '2025-05-14 09:00:00', 200),
(20, 33, 0, '2026-01-01', '2025-05-14 09:00:13', 200),
(21, 32, 0, '2026-01-01', '2025-05-14 09:00:33', 200),
(22, 31, 0, '2026-01-01', '2025-05-14 09:01:03', 200),
(23, 30, 0, '2026-01-01', '2025-05-14 09:01:30', 200),
(24, 29, 0, '2026-01-01', '2025-05-14 09:02:23', 200),
(25, 28, 0, '2026-01-01', '2025-05-14 09:02:38', 199),
(26, 27, 0, '2026-01-01', '2025-05-14 09:02:55', 197),
(27, 26, 0, '2026-01-01', '2025-05-14 09:03:21', 200),
(28, 25, 0, '2026-01-01', '2025-05-14 09:03:37', 200),
(29, 24, 0, '2026-01-01', '2025-05-14 09:03:50', 200),
(30, 23, 0, '2026-01-01', '2025-05-14 09:04:05', 200),
(31, 22, 0, '2026-01-01', '2025-05-14 09:04:16', 200),
(32, 21, 0, '2026-01-01', '2025-05-14 09:04:32', 200),
(33, 20, 0, '2026-01-01', '2025-05-14 09:04:48', 200),
(34, 19, 0, '2026-01-01', '2025-05-14 09:05:08', 200),
(35, 18, 0, '2026-01-01', '2025-05-14 09:05:18', 200),
(36, 17, 0, '2026-01-01', '2025-05-14 09:05:30', 200),
(37, 16, 0, '2026-01-01', '2025-05-14 09:05:41', 200),
(38, 15, 0, '2026-01-01', '2025-05-14 09:05:54', 200),
(39, 14, 0, '2026-01-01', '2025-05-14 09:06:04', 200),
(40, 13, 0, '2026-01-01', '2025-05-14 09:06:17', 199),
(41, 12, 0, '2026-01-01', '2025-05-14 09:06:28', 199),
(42, 11, 0, '2026-01-01', '2025-05-14 09:06:36', 200),
(43, 10, 0, '2026-01-01', '2025-05-14 09:06:49', 200),
(44, 9, 0, '2026-01-01', '2025-05-14 09:07:00', 200),
(45, 8, 0, '2026-01-01', '2025-05-14 09:07:10', 200),
(46, 7, 0, '2026-01-01', '2025-05-14 09:07:21', 200),
(47, 6, 0, '2026-01-01', '2025-05-14 09:07:31', 200),
(48, 5, 0, '2026-01-01', '2025-05-14 09:07:44', 200),
(49, 4, 0, '2026-01-01', '2025-05-14 09:07:54', 199),
(50, 3, 0, '2026-01-01', '2025-05-14 09:08:05', 200),
(51, 2, 0, '2026-01-01', '2025-05-14 09:08:19', 200),
(52, 1, 0, '2026-01-01', '2025-05-14 09:08:29', 196),
(53, 55, 0, '2026-01-10', '2025-05-14 09:08:44', 10),
(54, 55, 0, '2026-02-02', '2025-05-14 09:09:32', 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Brand Manager','Assistant Brand Manager','Trade and Marketing Team','Merchandising Marketing Team','Logistics','Customer') NOT NULL,
  `fullname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `fullname`) VALUES
(4, 'manager', 'manager111', 'Brand Manager', 'Manager Name'),
(5, 'assistant', 'assistant111', 'Assistant Brand Manager', 'Assistant Name'),
(6, 'trade', 'trade111', 'Trade and Marketing Team', 'Trade Marketing Name'),
(7, 'merchandising', 'merch111', 'Merchandising Marketing Team', 'Merchandising Name'),
(8, 'logistics', 'logistics111', 'Logistics', 'Logistics Name'),
(9, 'Pau', 'pau111', 'Brand Manager', 'Pauline Jayme'),
(10, 'Kish', 'kish111', 'Assistant Brand Manager', 'Kiesha Coronado');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_ratings`
--
ALTER TABLE `order_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_ratings_ibfk_1` (`order_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variations`
--
ALTER TABLE `product_variations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promotion_products`
--
ALTER TABLE `promotion_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `promotion_id` (`promotion_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `stock_batches`
--
ALTER TABLE `stock_batches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variation_id` (`variation_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `order_ratings`
--
ALTER TABLE `order_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product_variations`
--
ALTER TABLE `product_variations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `promotion_products`
--
ALTER TABLE `promotion_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_batches`
--
ALTER TABLE `stock_batches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_variations`
--
ALTER TABLE `product_variations`
  ADD CONSTRAINT `product_variations_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `promotion_products`
--
ALTER TABLE `promotion_products`
  ADD CONSTRAINT `promotion_products_ibfk_1` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `promotion_products_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `stock_batches`
--
ALTER TABLE `stock_batches`
  ADD CONSTRAINT `stock_batches_ibfk_1` FOREIGN KEY (`variation_id`) REFERENCES `product_variations` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
