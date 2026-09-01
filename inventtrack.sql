-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Sep 01, 2026 at 10:22 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventtrack`
--

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(250) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `title`, `description`, `image`, `active`, `created_at`) VALUES
(1, 'Gees', 'yummmy', 'Advs_Images/Picture1.jpg', 1, '2025-04-05 21:03:21'),
(2, 'mini pot', 'Buy the Best mini plants', 'Advs_Images/Picture24.jpg', 1, '2025-04-13 14:28:06');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `options` text NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `buyer_id`, `product_id`, `options`, `qty`) VALUES
(138, 14, 84, '{\"color_id\":\"\",\"size_id\":\"\"}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `image` varchar(250) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `active`, `created_at`) VALUES
(3, 'food', 'Categories_Images/food.webp', 1, '2025-03-18 07:38:04'),
(5, 'sweets', 'Categories_Images/sweets2.webp', 1, '2025-03-24 18:22:34'),
(6, 'accessories', 'Categories_Images/Picture9.png', 1, '2025-03-24 20:29:39'),
(7, 'cups ,coasters and soaps ', 'Categories_Images/Picture53.jpg', 1, '2025-03-26 19:07:04'),
(8, 'Gifts', 'Categories_Images/gifts catogery.png', 1, '2025-04-12 17:37:29'),
(9, 'Clothes', 'Categories_Images/', 1, '2025-05-11 15:01:05');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `buyer_id`, `product_id`, `active`, `created_at`) VALUES
(6, 32, 84, 1, '2025-12-29 10:11:30'),
(8, 32, 86, 1, '2025-12-29 10:22:54'),
(10, 14, 21, 1, '2026-01-02 09:09:55'),
(11, 14, 87, 1, '2026-01-06 20:35:39'),
(12, 32, 18, 1, '2026-08-31 19:52:58');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT 1,
  `total_price` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `buyer_id`, `status_id`, `total_price`, `created_at`) VALUES
(31, 32, 1, 13, '2025-12-11 10:38:35'),
(32, 32, 1, 90, '2025-12-11 10:45:21'),
(33, 32, 1, 3, '2025-12-13 10:26:05'),
(34, 32, 1, 60, '2025-12-28 08:41:55'),
(35, 14, 3, 144, '2025-12-29 11:23:24'),
(36, 14, 3, 24, '2026-01-01 12:20:18'),
(37, 14, 4, 24, '2026-01-01 12:27:10'),
(38, 34, 2, 14, '2026-01-01 13:12:38'),
(39, 32, 1, 4, '2026-01-01 13:45:31'),
(40, 14, 3, 72, '2026-01-02 09:12:34'),
(41, 14, 4, 0, '2026-01-02 09:13:14'),
(42, 14, 2, 4, '2026-01-04 19:03:20'),
(43, 14, 1, 1, '2026-01-06 10:44:40'),
(44, 32, 1, 44, '2026-09-01 18:47:43'),
(45, 32, 1, 25, '2026-09-01 18:52:51');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `option_id` text NOT NULL,
  `product_price` double NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `seller_id`, `product_id`, `option_id`, `product_price`, `quantity`, `created_at`) VALUES
(70, 31, 9, 85, '{\"color_id\":\"\",\"size_id\":\"\"}', 5, 2, '2025-12-11 10:38:37'),
(71, 31, 9, 84, '', 1, 3, '2025-12-11 10:38:42'),
(72, 32, 9, 89, '{\"color_id\":\"\",\"size_id\":\"\"}', 3, 30, '2025-12-11 10:45:23'),
(73, 33, 9, 84, '{\"color_id\":\"\",\"size_id\":\"\"}', 1, 3, '2025-12-13 10:26:08'),
(74, 34, 9, 89, '{\"color_id\":\"\",\"size_id\":\"\"}', 3, 20, '2025-12-28 08:41:57'),
(75, 35, 12, 80, '{\"color_id\":\"25\",\"size_id\":\"\"}', 12, 12, '2025-12-29 11:23:24'),
(76, 36, 12, 80, '{\"color_id\":\"\",\"size_id\":\"\"}', 12, 2, '2026-01-01 12:20:18'),
(77, 37, 12, 80, '{\"color_id\":\"\",\"size_id\":\"\"}', 12, 2, '2026-01-01 12:27:10'),
(78, 38, 9, 87, '{\"color_id\":\"\",\"size_id\":\"\"}', 3, 1, '2026-01-01 13:12:38'),
(79, 38, 9, 85, '{\"color_id\":\"\",\"size_id\":\"\"}', 5, 2, '2026-01-01 13:12:38'),
(80, 38, 9, 84, '{\"color_id\":\"\",\"size_id\":\"\"}', 1, 1, '2026-01-01 13:12:38'),
(81, 39, 9, 84, '{\"color_id\":\"\",\"size_id\":\"\"}', 1, 4, '2026-01-01 13:45:31'),
(82, 40, 9, 88, '{\"color_id\":\"\",\"size_id\":\"\"}', 4, 8, '2026-01-02 09:12:34'),
(84, 42, 9, 84, '{\"color_id\":\"\",\"size_id\":\"\"}', 1, 1, '2026-01-04 19:03:20'),
(85, 42, 9, 84, '{\"color_id\":\"\",\"size_id\":\"\"}', 1, 3, '2026-01-04 19:03:20'),
(86, 43, 9, 84, '{\"color_id\":\"\",\"size_id\":\"\"}', 1, 1, '2026-01-06 10:44:40'),
(87, 44, 9, 84, '{\"color_id\":\"\",\"size_id\":\"\"}', 1, 4, '2026-09-01 18:47:43'),
(88, 44, 17, 18, '{\"color_id\":\"\",\"size_id\":\"\"}', 40, 1, '2026-09-01 18:47:43'),
(89, 45, 13, 21, '{\"color_id\":\"\",\"size_id\":\"\"}', 25, 1, '2026-09-01 18:52:51');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `sub_category_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `image` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `total_rate` double NOT NULL DEFAULT 0,
  `out_of_stock` tinyint(1) GENERATED ALWAYS AS (case when `qty` <= 0 then 1 else 0 end) STORED,
  `is_customized` tinyint(1) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `qty` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `qr_token` varchar(64) DEFAULT NULL,
  `qr_scans` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `sub_category_id`, `seller_id`, `name`, `image`, `description`, `price`, `total_rate`, `is_customized`, `active`, `qty`, `created_at`, `qr_token`, `qr_scans`) VALUES
(8, 5, 16, 13, 'chocolate chip cookies ', 'Product_Images/cooo.jpg', 'cookies filled with chocolate chips ', 1.99, 4, 0, 1, 119, '2025-04-05 20:20:28', NULL, 0),
(9, 5, 17, 13, 'Brownie ', 'Product_Images/brow.jpg', 'chewy brownies ', 2, 0, 0, 1, 29, '2025-04-05 20:23:27', NULL, 0),
(10, 7, 14, 16, 'roses around ', 'Product_Images/around.jpg', 'roses', 5, 0, 0, 1, 30, '2025-04-12 18:34:03', NULL, 0),
(11, 7, 14, 16, 'sunny', 'Product_Images/sunflower.jpg', 'sunflower ', 6, 0, 0, 1, 20, '2025-04-12 18:45:12', NULL, 0),
(13, 7, 14, 16, 'المودة', 'Product_Images/Picture4.jpg', 'big cup \r\n', 7, 0, 0, 0, 20, '2025-04-12 18:46:54', NULL, 0),
(14, 7, 14, 16, 'Heart', 'Product_Images/heart.jpg', 'Hearts Cup \r\n', 8, 0, 0, 1, 30, '2025-04-12 18:48:00', NULL, 1),
(15, 7, 15, 16, 'coaster', 'Product_Images/coaster.jpg', 'coaster with flower design', 8, 0, 0, 1, 20, '2025-04-12 18:48:47', NULL, 4),
(16, 8, 18, 17, 'Watermelon bag', 'Product_Images/Picture7.jpg', 'tote bag with watermelon drawing', 6, 0, 0, 1, 30, '2025-04-12 18:53:57', NULL, 0),
(17, 6, 10, 17, 'Osama board', 'Product_Images/osama.jpg', 'board', 10, 0, 0, 1, 30, '2025-04-12 18:54:57', NULL, 0),
(18, 6, 10, 17, 'Eliana board', 'Product_Images/elyana.jpg', 'Eliana board drawing', 40, 4, 0, 1, 18, '2025-04-12 18:55:41', NULL, 0),
(19, 6, 10, 17, 'Sun bag', 'Product_Images/sunbag.jpg', 'big sun drew on the bag', 6, 0, 0, 1, 20, '2025-04-12 18:57:02', NULL, 0),
(21, 5, 19, 13, 'macrone ', 'Product_Images/maco.jpg', '25 for 21 jd', 25, 5, 0, 1, 47, '2025-04-12 19:02:29', NULL, 0),
(22, 7, 14, 18, '1', 'Product_Images/Picture12.jpg', 'handmade cups \r\n\r\n', 5, 0, 0, 1, 15, '2025-04-12 19:06:59', NULL, 0),
(23, 7, 14, 18, '2', 'Product_Images/Picture13.jpg', 'Couples cups with flower\r\n8 jd \r\n', 8, 0, 0, 1, 20, '2025-04-12 19:07:39', NULL, 0),
(24, 7, 14, 18, '3', 'Product_Images/Picture15.jpg', 'big cup \r\n', 6, 0, 0, 1, 17, '2025-04-12 19:08:08', NULL, 0),
(25, 7, 14, 18, 'Ramadan cups ', 'Product_Images/Picture14.jpg', '6 for 12', 12, 0, 0, 1, 20, '2025-04-12 19:08:51', NULL, 0),
(33, 5, 20, 21, 'pops ', 'Product_Images/sweets.jpg', '15 pop for 5', 5, 0, 0, 1, 100, '2025-04-12 19:24:57', NULL, 0),
(34, 5, 20, 21, 'Ramadan Box ', 'Product_Images/Picture28.jpg', '2 boxes for 15', 15, 5, 0, 1, 29, '2025-04-12 19:25:53', NULL, 0),
(35, 5, 20, 21, 'Fruit tarts', 'Product_Images/Picture31.jpg', '20 for 12 jd ', 12, 0, 0, 1, 30, '2025-04-12 19:26:46', NULL, 0),
(36, 5, 17, 21, 'brownies ', 'Product_Images/Picture30.jpg', '25 pieces for 7 jd', 7, 0, 0, 1, 200, '2025-04-12 19:27:38', NULL, 0),
(37, 5, 16, 21, 'cookies', 'Product_Images/Picture32.jpg', '40 pc for 10', 10, 0, 0, 1, 300, '2025-04-12 19:28:18', NULL, 0),
(38, 5, 20, 21, 'protien bars ', 'Product_Images/Picture29.jpg', '1 for .8', 0.8, 0, 0, 1, 300, '2025-04-12 19:29:22', NULL, 0),
(39, 5, 21, 22, 'cheesecake slices', 'Product_Images/Picture39.jpg', '1 pc for 3', 3, 0, 0, 1, 40, '2025-04-12 19:33:15', NULL, 0),
(40, 5, 21, 22, 'Mixed berries ', 'Product_Images/Picture40.jpg', 'Mixed berries cheesecake ', 28, 0, 0, 1, 20, '2025-04-12 19:33:48', NULL, 0),
(41, 5, 21, 22, 'cheesecake bites ', 'Product_Images/Picture36.jpg', '20 pieces for 10 jd ', 10, 0, 0, 1, 50, '2025-04-12 19:34:27', NULL, 0),
(42, 5, 21, 22, 'sambusa ', 'Product_Images/Picture42.jpg', '20 pc for 10 jd ', 10, 0, 0, 1, 200, '2025-04-12 19:35:15', NULL, 0),
(43, 5, 21, 22, 'Tiramisu ', 'Product_Images/Picture38.jpg', 'Tiramisu  cake', 28, 0, 0, 1, 10, '2025-04-12 19:35:51', NULL, 0),
(44, 5, 21, 22, 'classic cheescake ', 'Product_Images/Picture35.jpg', 'classic cheescake ', 24, 0, 0, 1, 10, '2025-04-12 19:36:23', NULL, 0),
(45, 8, 18, 23, 'Candle Bouquet', 'Product_Images/Picture43.jpg', 'Candle Bouquet', 20, 0, 0, 1, 15, '2025-04-12 19:39:30', NULL, 0),
(46, 8, 18, 23, 'Candle Bouquet', 'Product_Images/Picture44.jpg', 'Candle Bouquet', 18, 0, 0, 1, 20, '2025-04-12 19:40:00', NULL, 0),
(47, 8, 18, 23, 'Candle Bouquet', 'Product_Images/Picture45.jpg', 'Candle Bouquet', 18, 0, 0, 1, 14, '2025-04-12 19:40:23', NULL, 0),
(48, 7, 22, 25, 'Soap Box ', 'Product_Images/Picture53.jpg', 'Soap Box ', 20, 0, 0, 1, 40, '2025-04-12 19:47:26', NULL, 0),
(49, 7, 22, 25, 'Natural Lemon Soap ', 'Product_Images/Picture54.jpg', 'Natural Lemon Soap ', 4, 0, 0, 1, 40, '2025-04-12 19:47:52', NULL, 0),
(50, 7, 22, 25, 'Vanilla Soap ', 'Product_Images/Picture56.jpg', 'Vanilla Soap ', 4, 0, 0, 1, 39, '2025-04-12 19:48:37', NULL, 0),
(51, 7, 22, 25, 'Gift set ', 'Product_Images/Picture55.jpg', 'Gift set ', 12, 0, 0, 1, 40, '2025-04-12 19:49:08', NULL, 0),
(54, 5, 20, 27, 'cheelush', 'Product_Images/Picture1.jpg', 'ice-cream with caramel', 3.5, 0, 0, 1, 50, '2025-04-13 13:06:02', NULL, 0),
(55, 5, 20, 27, 'Helwo', 'Product_Images/Picture6.jpg', 'cheesecake ice-cream', 3.5, 0, 0, 1, 50, '2025-04-13 13:09:15', NULL, 0),
(56, 5, 20, 27, 'pista-liciouse', 'Product_Images/Picture2.jpg', 'mixed ice cream flavors ', 3.5, 0, 0, 1, 70, '2025-04-13 13:10:39', NULL, 0),
(57, 5, 20, 27, 'Chocolata', 'Product_Images/Picture5.jpg', 'chocolate ice cream', 3.5, 0, 0, 1, 60, '2025-04-13 13:11:29', NULL, 0),
(58, 5, 20, 27, 'Gathering box ', 'Product_Images/Picture3.jpg', '10 for 15 jd', 15, 0, 0, 1, 40, '2025-04-13 13:12:30', NULL, 0),
(59, 5, 20, 27, 'Gathering box', 'Product_Images/Picture4.jpg', '15 for 22 jd', 22, 0, 0, 1, 80, '2025-04-13 13:13:44', NULL, 0),
(60, 8, 18, 28, 'philodendron_green_princess', 'Product_Images/Picture11.jpg', 'green princess', 16, 0, 0, 1, 40, '2025-04-13 13:22:11', NULL, 0),
(61, 8, 18, 28, 'spathiphyllum', 'Product_Images/Picture16.jpg', 'special plant', 18, 0, 0, 1, 30, '2025-04-13 13:22:59', NULL, 0),
(62, 8, 18, 28, 'dracaena_fragrans', 'Product_Images/Picture17.jpg', 'dracaena_fragrans', 16, 0, 0, 1, 20, '2025-04-13 13:23:46', NULL, 0),
(63, 8, 18, 28, 'anthurium', 'Product_Images/Picture18.jpg', 'pink anthurium ', 17, 0, 0, 1, 30, '2025-04-13 13:24:24', NULL, 0),
(64, 8, 18, 28, 'Home Plant', 'Product_Images/Picture19.jpg', 'Home Plant', 10, 0, 0, 1, 35, '2025-04-13 13:24:51', NULL, 0),
(65, 8, 18, 28, 'Home Plant', 'Product_Images/Picture20.jpg', 'yellowish Home Plant', 40, 0, 0, 1, 55, '2025-04-13 13:25:41', NULL, 0),
(66, 5, 16, 29, 'Bites Mix Box ', 'Product_Images/Picture30.jpg', ' Mix Box ', 12, 0, 0, 1, 100, '2025-04-13 13:37:53', NULL, 0),
(67, 5, 16, 29, 'Tutti Frutti ', 'Product_Images/Picture33.jpg', 'strawberries and cookies', 20, 0, 0, 1, 90, '2025-04-13 13:40:05', NULL, 0),
(68, 5, 16, 29, 'cookies bites box ', 'Product_Images/Picture32.jpg', 'cookies bites box ', 12, 0, 0, 1, 40, '2025-04-13 13:40:56', NULL, 0),
(69, 5, 16, 29, 'Brownie Party Bow', 'Product_Images/Picture34.jpg', 'big cookies ', 12, 0, 0, 1, 40, '2025-04-13 13:42:08', NULL, 0),
(70, 5, 16, 29, 'singular cookie ', 'Product_Images/Picture31.jpg', 'singular cookie ', 1, 0, 0, 1, 30, '2025-04-13 13:43:23', NULL, 0),
(71, 5, 16, 29, 'Cookies Cake ', 'Product_Images/Picture35.jpg', 'Cookies cake', 7, 0, 0, 1, 15, '2025-04-13 13:45:04', NULL, 0),
(72, 5, 21, 30, 'Rounded mango tiramisu ', 'Product_Images/mangooooooooo.jpg', ' mango tiramisu ', 35, 0, 0, 1, 40, '2025-04-13 13:54:24', NULL, 0),
(73, 5, 21, 30, 'square  tiramisu ', 'Product_Images/squreee.jpg', 'square original tiramisu ', 30, 0, 0, 1, 30, '2025-04-13 13:55:08', NULL, 0),
(74, 5, 21, 30, 'rectangular tiramisu ', 'Product_Images/mango tiramisso.jpg', 'rectangular white tiramisu ', 15, 0, 0, 1, 23, '2025-04-13 13:56:28', NULL, 0),
(75, 6, 11, 31, 'saber bracelet', 'Product_Images/صبر.png', '\"saber\" word bracelet', 15, 0, 0, 1, 12, '2025-04-13 14:10:40', NULL, 0),
(76, 6, 13, 31, 'Flowered Earnings', 'Product_Images/وردات.png', 'white flowers earings', 7, 0, 0, 1, 12, '2025-04-13 14:11:41', NULL, 0),
(77, 6, 11, 31, 'Palestine map bracelets', 'Product_Images/فلسطين الوان.png', 'Palestine map bracelets', 12, 0, 1, 1, 44, '2025-04-13 14:13:21', NULL, 0),
(78, 6, 12, 31, 'butterfly ring', 'Product_Images/فراشة.png', 'butterfly ring', 6, 0, 0, 1, 12, '2025-04-13 14:14:37', NULL, 0),
(79, 7, 14, 12, 'orange cup', 'Product_Images/Picture9.png', 'orange cup', 9, 0, 0, 1, 12, '2025-04-13 14:16:50', NULL, 0),
(80, 7, 14, 12, 'colorful cups', 'Product_Images/Picture1.png', 'cups', 12, 5, 1, 1, 0, '2025-04-13 14:17:52', NULL, 0),
(81, 7, 14, 12, 'flowers cup', 'Product_Images/Picture7.png', 'flowers cup', 15, 0, 0, 1, 20, '2025-04-13 14:20:07', NULL, 1),
(82, 7, 15, 12, 'colorful coasters', 'Product_Images/Picture5.png', 'colorful coasters', 11, 0, 1, 1, 20, '2025-04-13 14:21:09', NULL, 3),
(84, 3, 23, 9, 'kobbeh', 'Product_Images/kobbeh.png', '1 kobbeh for 1 jd ', 1, 5, 0, 1, 180, '2025-05-04 18:51:14', NULL, 2),
(85, 3, 23, 9, 'Yalanji', 'Product_Images/sr7.png', '1 dish for 5 jds ', 5, 0, 0, 1, 396, '2025-05-04 18:52:07', NULL, 0),
(86, 3, 24, 9, 'Dawalli with meat', 'Product_Images/╪»┘ê╪º┘ä┘è ┘ä╪¡┘à╪⌐ .png', 'Soft green Dawalli with lamb meat ', 10, 0, 0, 1, 100, '2025-05-04 18:56:47', NULL, 0),
(87, 3, 23, 9, 'Taboouleh', 'Product_Images/╪¬╪¿┘ê┘ä╪⌐2.png', 'Lebanese traditional tabouleh ', 3, 0, 0, 1, 39, '2025-05-04 20:07:59', NULL, 0),
(88, 3, 23, 9, 'Msakhan Rolls', 'Product_Images/╪▒┘ê┘ä╪º╪¬ ┘à╪│╪«┘å .png', '6 pieces for 4 jds', 4, 3, 0, 1, 142, '2025-05-04 20:08:58', NULL, 0),
(89, 5, 16, 9, ' Circle Kaak', 'Product_Images/┘â╪╣┘â ┘à┘ê┘à╪▓.png', 'Palestinian kaak with flour', 3, 0, 0, 1, 16, '2025-05-04 20:10:15', NULL, 2),
(90, 6, 11, 31, 'Bracelet (ه)', 'Product_Images/ه.png', 'سنسال حرف (ه)', 15, 0, 0, 1, 30, '2025-05-04 20:31:03', NULL, 0),
(91, 6, 11, 31, 'Ribbon ', 'Product_Images/ببيونة.png', 'Ribbon bracelet ', 15, 0, 0, 1, 30, '2025-05-04 20:32:12', NULL, 0),
(92, 6, 11, 31, 'Blue Ribbon ', 'Product_Images/زببيونة.png', 'Blue Ribbon ', 15, 0, 0, 1, 40, '2025-05-04 20:33:01', NULL, 0),
(93, 6, 11, 31, 'Bracelets (ي)', 'Product_Images/ي.png', 'سنسال حرف ال ي', 15, 0, 0, 1, 50, '2025-05-04 20:33:56', NULL, 0),
(94, 6, 12, 31, 'Music', 'Product_Images/ميوزك.png', '..', 6, 0, 0, 1, 20, '2025-05-04 20:35:08', NULL, 0),
(95, 6, 12, 31, 'Rock', 'Product_Images/حجرة.png', '..', 5, 0, 0, 1, 15, '2025-05-04 20:36:00', NULL, 0),
(96, 6, 12, 31, 'Leaves Ring', 'Product_Images/ورقة شجر.png', '..', 5, 0, 0, 1, 20, '2025-05-04 20:37:18', NULL, 0),
(97, 6, 13, 31, 'Stars earings', 'Product_Images/نجوم.png', '..', 4, 0, 0, 1, 16, '2025-05-04 20:38:09', NULL, 0),
(98, 6, 13, 31, 'Blue Rock', 'Product_Images/حجرة زرقا.png', '..', 5, 0, 0, 1, 20, '2025-05-04 20:39:19', NULL, 0),
(99, 5, 21, 30, 'Mango and bluberry', 'Product_Images/squuurree.jpg', 'rectangular blueberry and mango Tiramisso', 15, 0, 0, 1, 40, '2025-05-04 20:57:12', NULL, 0),
(100, 5, 21, 30, 'Small Tiramisso', 'Product_Images/small tiramisoo.jpg', 'small circle tiramesso', 30, 0, 0, 1, 50, '2025-05-04 20:58:05', NULL, 0),
(101, 8, 18, 33, 'Green planet', 'Product_Images/gp2.jpg', 'Best gift green plant', 15, 0, 0, 1, 10, '2025-05-04 21:14:51', NULL, 0),
(102, 8, 18, 33, 'Orchid', 'Product_Images/gp3.jpg', 'Orchid plant ', 20, 0, 0, 1, 30, '2025-05-04 21:15:57', NULL, 0),
(103, 8, 18, 33, 'colored plant ', 'Product_Images/gp4.jpg', '..', 18, 0, 0, 1, 40, '2025-05-04 21:16:51', NULL, 0),
(104, 8, 18, 33, 'Flowers', 'Product_Images/gp6.jpg', '...', 20, 0, 0, 1, 40, '2025-05-04 21:17:23', NULL, 0),
(110, 5, 21, 36, 'Red cake ', 'Product_Images/bm2.jpg', '14 cm for 6 JD', 6, 0, 1, 1, 20, '2025-05-06 19:46:00', NULL, 0),
(111, 5, 21, 36, 'Cake with Cupcake', 'Product_Images/bm1.jpg', '14cm with 2 cupcakes for 10 jd', 10, 0, 0, 1, 50, '2025-05-06 19:48:52', NULL, 0),
(112, 5, 21, 36, 'Cake ', 'Product_Images/bm5.jpg', '22cm for 15 jd', 15, 0, 0, 1, 20, '2025-05-06 19:50:10', NULL, 0),
(113, 5, 21, 36, 'Cake', 'Product_Images/bm6.jpg', '17cm for 8 jd ', 8, 0, 0, 1, 25, '2025-05-06 19:50:52', NULL, 0),
(114, 5, 21, 36, 'Cupcake', 'Product_Images/bm3.jpg', 'Flavored Cupcake ', 1.5, 0, 0, 1, 50, '2025-05-06 19:52:17', NULL, 0),
(168, 8, 18, 49, 'Men’s gift box', 'Product_Images/g1.png', 'men\'s gift box ', 27, 0, 0, 1, 22, '2025-05-11 14:37:15', NULL, 0),
(169, 8, 18, 49, 'Chocolate gift box', 'Product_Images/g2.png', 'Gift box consists of your choice of chocolate ', 23, 0, 0, 1, 31, '2025-05-11 14:38:08', NULL, 0),
(171, 8, 18, 49, 'Bride Gift Box', 'Product_Images/g4.png', 'The perfect gift for your bride friend', 26, 0, 0, 1, 22, '2025-05-11 14:39:31', NULL, 0),
(205, 3, 23, 9, 'cake', 'Product_Images/wew.gif', 'blueberry cake', 4, 0, 0, 1, 33, '2025-12-28 08:49:18', NULL, 20);

-- --------------------------------------------------------

--
-- Table structure for table `product_feedbacks`
--

CREATE TABLE `product_feedbacks` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `product_feedbacks`
--

INSERT INTO `product_feedbacks` (`id`, `buyer_id`, `product_id`, `feedback`, `created_at`) VALUES
(3, 14, 8, '', '2025-04-05 20:28:37'),
(5, 14, 84, 'yum', '2026-01-06 19:21:22'),
(6, 14, 84, 'yum', '2026-01-06 19:27:16'),
(7, 14, 84, 'yum', '2026-01-06 19:30:52'),
(9, 14, 84, 'yummy', '2026-01-06 19:37:20'),
(10, 32, 85, 'hello', '2026-01-06 21:59:00'),
(11, 32, 84, 'hi', '2026-01-06 22:09:27'),
(12, 14, 88, 'hhh', '2026-01-06 22:14:05');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(250) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image`, `active`, `created_at`) VALUES
(5, 89, 'Product_Images/Sr7.png', 1, '2025-05-04 20:12:04'),
(6, 89, 'Product_Images/يلنجي2.png', 1, '2025-05-04 20:12:21'),
(7, 89, 'Product_Images/يلنجي3.png', 1, '2025-05-04 20:12:30');

-- --------------------------------------------------------

--
-- Table structure for table `product_options`
--

CREATE TABLE `product_options` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `value` varchar(250) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `product_options`
--

INSERT INTO `product_options` (`id`, `product_id`, `name`, `value`, `active`, `created_at`) VALUES
(18, 110, 'color', 'Red', 1, '2025-05-06 19:46:00'),
(19, 110, 'color', 'Blue', 1, '2025-05-06 19:46:00'),
(20, 110, 'size', 'S', 1, '2025-05-06 19:46:00'),
(32, 80, 'color', 'Red', 1, '2026-01-01 12:03:15'),
(33, 80, 'color', 'Blue', 1, '2026-01-01 12:03:15'),
(34, 82, 'color', 'Red', 1, '2026-01-02 01:59:24');

-- --------------------------------------------------------

--
-- Table structure for table `product_rates`
--

CREATE TABLE `product_rates` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `rate` double NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `product_rates`
--

INSERT INTO `product_rates` (`id`, `product_id`, `buyer_id`, `rate`, `created_at`) VALUES
(2, 8, 14, 4, '2025-05-03 15:14:43'),
(3, 21, 14, 5, '2025-05-04 14:34:09'),
(4, 18, 32, 4, '2025-05-04 16:55:10'),
(5, 80, 32, 5, '2025-05-04 16:56:29'),
(6, 34, 34, 5, '2025-05-04 21:26:13'),
(8, 84, 14, 5, '2025-05-08 10:08:59'),
(10, 80, 14, 5, '2026-01-06 20:09:58'),
(11, 88, 14, 3, '2026-01-06 22:19:28');

-- --------------------------------------------------------

--
-- Table structure for table `seller_feedbacks`
--

CREATE TABLE `seller_feedbacks` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `seller_feedbacks`
--

INSERT INTO `seller_feedbacks` (`id`, `buyer_id`, `seller_id`, `feedback`, `created_at`) VALUES
(2, 14, 9, 'love', '2026-01-06 19:45:22'),
(3, 32, 12, 'amazing', '2026-01-06 22:00:25');

-- --------------------------------------------------------

--
-- Table structure for table `seller_subscriptions`
--

CREATE TABLE `seller_subscriptions` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `subscription_type` varchar(250) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `price` double NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `seller_subscriptions`
--

INSERT INTO `seller_subscriptions` (`id`, `seller_id`, `subscription_type`, `start_date`, `end_date`, `price`, `active`, `created_at`) VALUES
(3, 13, '3 Months Contract (150 JOD)', '2025-04-06 00:00:00', '2025-07-05 00:00:00', 150, 1, '2025-04-05 20:16:59'),
(4, 16, '6 Months Contract (300 JOD)', '2025-04-21 00:00:00', '2025-10-18 00:00:00', 300, 1, '2025-04-12 18:31:34'),
(5, 17, '1 Months Contract (65 JOD)', '2025-04-30 00:00:00', '2025-05-30 00:00:00', 65, 1, '2025-04-12 18:50:34'),
(6, 18, '3 Months Contract (150 JOD)', '2025-04-28 00:00:00', '2025-07-27 00:00:00', 150, 1, '2025-04-12 19:05:10'),
(9, 21, '6 Months Contract (300 JOD)', '2025-04-30 00:00:00', '2025-10-27 00:00:00', 300, 1, '2025-04-12 19:20:48'),
(10, 22, '3 Months Contract (150 JOD)', '2025-04-30 00:00:00', '2025-07-29 00:00:00', 150, 1, '2025-04-12 19:30:42'),
(11, 23, '3 Months Contract (150 JOD)', '2025-04-24 00:00:00', '2025-07-23 00:00:00', 150, 1, '2025-04-12 19:38:37'),
(13, 25, '1 Months Contract (65 JOD)', '2025-04-27 00:00:00', '2025-05-27 00:00:00', 65, 1, '2025-04-12 19:45:33'),
(15, 27, '3 Months Contract (150 JOD)', '2025-04-29 00:00:00', '2025-07-28 00:00:00', 150, 1, '2025-04-13 13:04:41'),
(16, 28, '6 Months Contract (300 JOD)', '2025-04-17 00:00:00', '2025-10-14 00:00:00', 300, 1, '2025-04-13 13:18:33'),
(17, 29, '3 Months Contract (150 JOD)', '2025-04-29 00:00:00', '2025-07-28 00:00:00', 150, 1, '2025-04-13 13:34:50'),
(18, 30, '6 Months Contract (300 JOD)', '2025-04-23 00:00:00', '2025-10-20 00:00:00', 300, 1, '2025-04-13 13:50:51'),
(19, 31, '3 Months Contract (150 JOD)', '2025-04-24 00:00:00', '2025-07-23 00:00:00', 150, 1, '2025-04-13 14:04:01'),
(20, 33, '6 Months Contract (300 JOD)', '2025-05-20 00:00:00', '2025-11-16 00:00:00', 300, 1, '2025-05-04 21:07:52'),
(22, 36, '3 Months Contract (150 JOD)', '2025-05-21 00:00:00', '2025-08-19 00:00:00', 150, 1, '2025-05-06 19:38:17'),
(35, 49, '3 Months Contract (150 JOD)', '2025-05-22 00:00:00', '2025-08-20 00:00:00', 150, 1, '2025-05-11 14:34:49'),
(44, 9, '1 Months Contract (65 JOD)', '2026-01-07 00:00:00', '2026-02-06 00:00:00', 65, 1, '2026-01-06 20:58:34');

-- --------------------------------------------------------

--
-- Table structure for table `statuses`
--

CREATE TABLE `statuses` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `statuses`
--

INSERT INTO `statuses` (`id`, `name`) VALUES
(1, 'Pending'),
(2, 'Delivering'),
(3, 'Delivered'),
(4, 'Canceled');

-- --------------------------------------------------------

--
-- Table structure for table `sub_categories`
--

CREATE TABLE `sub_categories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `image` varchar(250) NOT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `sub_categories`
--

INSERT INTO `sub_categories` (`id`, `category_id`, `name`, `image`, `active`, `created_at`) VALUES
(6, 4, 'mix things', 'Categories_Images/appetizer1.jpg', 1, '2025-03-24 18:11:10'),
(7, 4, 'العيد', 'Categories_Images/', 1, '2025-03-24 18:24:56'),
(10, 6, 'custom made ', 'Categories_Images/Picture5.png', 1, '2025-03-24 20:34:19'),
(11, 6, 'Necklace', 'Categories_Images/ه.png', 1, '2025-03-24 20:37:40'),
(12, 6, 'rings', 'Categories_Images/صخرة.png', 1, '2025-03-24 20:39:57'),
(13, 6, 'Earings', 'Categories_Images/غيوم.png', 1, '2025-03-24 20:50:14'),
(14, 7, 'cups', 'Categories_Images/Picture9.png', 1, '2025-03-26 19:09:50'),
(15, 7, 'coasters', 'Categories_Images/Picture5.png', 1, '2025-03-26 19:10:04'),
(16, 5, 'cookies', 'Categories_Images/', 1, '2025-04-05 20:18:44'),
(17, 5, 'brownies ', 'Categories_Images/Picture2.jpg', 1, '2025-04-05 20:22:00'),
(18, 8, 'gifts', 'Categories_Images/gifts catogery.png', 1, '2025-04-12 18:53:09'),
(19, 5, 'macrone', 'Categories_Images/Picture9.jpg', 1, '2025-04-12 19:00:50'),
(20, 5, 'mix', 'Categories_Images/', 1, '2025-04-12 19:24:13'),
(21, 5, 'Cake', 'Categories_Images/Picture39.jpg', 1, '2025-04-12 19:32:19'),
(22, 7, 'soaps', 'Categories_Images/Picture57.jpg', 1, '2025-04-12 19:46:41'),
(23, 3, 'appetizers ', 'Categories_Images/', 1, '2025-05-04 18:48:58'),
(24, 3, 'main courses', 'Categories_Images/', 1, '2025-05-04 18:49:11'),
(25, 6, 'stickers', 'Categories_Images/stic3.png', 1, '2025-05-06 19:22:35'),
(26, 9, 'Blouse', 'Categories_Images/', 1, '2025-05-11 15:01:20'),
(27, 9, 'Hats', 'Categories_Images/', 1, '2025-05-11 15:01:27'),
(28, 9, 'Trouser', 'Categories_Images/', 1, '2025-05-11 15:01:35'),
(29, 9, 'jacket', 'Categories_Images/', 1, '2025-05-11 15:05:46'),
(30, 6, 'Pets accessories', 'Categories_Images/', 1, '2025-05-11 15:18:28'),
(31, 6, 'covers', 'Categories_Images/', 1, '2025-05-11 15:29:46'),
(32, 3, 'Jam', 'Categories_Images/', 1, '2025-05-11 15:41:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_type_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `phone` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `image` varchar(250) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `instagram_link` varchar(250) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_type_id`, `name`, `email`, `phone`, `password`, `image`, `description`, `instagram_link`, `active`, `created_at`) VALUES
(1, 1, 'Admin', 'admin@Inventrack.com', '1234567890', '$2y$10$eLNSQY6eNyrchcw2kNdi3.qsgP0qMSMIFTKCi/Fh66TI8kbirTwWy', NULL, NULL, '', 1, '2025-10-15 20:07:00'),
(9, 2, 'Cozy Kitchen', 'cozykit@example.com', '0000000001', '$2y$10$Skhxt8ioE9Y0mt7ykeIHW.D8aXU/e4bDmFFwWSvlkEgNWd44MY5q.', 'Sellers_Images/ChatGPT Image Aug 31, 2026, 11_25_07 AM.png', 'Homemade meals prepared with care.', 'https://www.instagram.com/demo_cozy_kitchen/', 1, '2025-08-19 17:55:39'),
(12, 2, 'Clay & Co.', 'seller02@example.com', '0000000002', '$2y$10$cvRzAhdk8A0Jkn72sJZ0TufKG7DWzWltddvBuu8ZQXb50/0d9n9zy', 'Sellers_Images/clayco.png', 'Handcrafted pottery and custom clay pieces.', 'https://www.instagram.com/demo_clay_and_co/', 1, '2025-08-19 19:11:01'),
(13, 2, 'Sugar Nest', 'seller03@example.com', '0000000003', '$2y$10$NDVqp3tFdZzJvaxXiw2AU.UOJaJaE1Q0t6C8XO3D6CVYhEecCh5PW', 'Sellers_Images/sugarnest.png', 'Fresh homemade desserts and sweet treats.', 'https://www.instagram.com/demo_sugar_nest/', 1, '2025-08-19 20:16:59'),
(14, 3, 'jood Maya', 'jood@gmail.com', '0791234577', '$2y$10$LVpMwLMWG5LECvJgN2gsieC.wktGH8RZJ5F3LN8TF5EypW6rV.4FS', NULL, NULL, '', 1, '2025-04-05 20:26:03'),
(16, 2, 'Little Clay Studio', 'seller04@example.com', '0000000004', '$2y$10$1CoH1.FQ1RsmjSciWl4xdeyvk/FBE1YC6h8EMYfQSrOYo6fuB4Wi6', 'Sellers_Images/littleclay.png', 'Custom handmade pottery and clay creations.', 'https://www.instagram.com/demo_little_clay_studio/', 1, '2025-08-19 18:31:34'),
(17, 2, 'Sunny Creations', 'seller05@example.com', '0000000005', '$2y$10$ffaGpaFNW295tj7fyr59JeEelSB/s3/p/4ebLjgs0XFfMyevCcfkm', 'Sellers_Images/sunny.png', 'Personalized handmade gifts and decorations.', 'https://www.instagram.com/demo_sunny_creations/', 1, '2025-08-20 18:50:34'),
(18, 2, 'Painted Cup Studio', 'seller06@example.com', '0000000006', '$2y$10$kaLRFdhjJxu/Hs30RkuI7e7q0VgofvxJuxYtXH/11XRpuZqTUmil.', 'Sellers_Images/paintedcup.png', 'Hand-painted mugs and custom designs.', 'https://www.instagram.com/demo_painted_cup_studio/', 1, '2025-08-20 19:05:10'),
(21, 2, 'Cookie Corner', 'seller07@example.com', '0000000007', '$2y$10$QPialJqQkgEzVBTJQcW/B.1iMwNOHqdYSOqf.eGiADuPxQRlOghju', 'Sellers_Images/cookiecor.png', 'Fresh brownies, cookies, and desserts.', 'https://www.instagram.com/demo_cookie_corner/', 1, '2025-04-12 19:20:48'),
(22, 2, 'Velvet Cheesecake', 'seller08@example.com', '0000000008', '$2y$10$.EwshwVlAPuImE35vdZgJunlKs2mlH7Oe7ecW/BeUDOFo9djHVKh2', 'Sellers_Images/velvet.png', 'Fresh specialty cheesecakes made to order.', 'https://www.instagram.com/demo_velvet_cheesecake/', 1, '2025-04-12 19:30:42'),
(23, 2, 'Glow Candle Co.', 'seller09@example.com', '0000000009', '$2y$10$ijsJazu8EwllZWkTtMGSt.KGI6LE2UwJaIcbwxw5xkkYYdSocgwLe', 'Sellers_Images/glowcan.png', 'Handmade decorative and scented candles.', 'https://www.instagram.com/demo_glow_candle_co/', 1, '2025-04-12 19:38:37'),
(25, 2, 'Pure Soap Studio', 'seller10@example.com', '0000000010', '$2y$10$xX/44O6lrAaEtDzTu207qur/mq0cqg0izNtUE3m7P2JzdOZ964Eee', 'Sellers_Images/pureso.png', 'Handmade soaps made with natural ingredients.', 'https://www.instagram.com/demo_pure_soap_studio/', 1, '2025-04-12 19:45:33'),
(27, 2, 'Cheesecake Bites', 'seller11@example.com', '0000000011', '$2y$10$F1BSinG4wLCu7TXg7mJMHO7vKdsne3jsyulH0tnASwzcSHKvLqtm2', 'Sellers_Images/cheesecake.png', 'Creative cheesecake bites and desserts.', 'https://www.instagram.com/demo_cheesecake_bites/', 1, '2025-04-13 13:04:41'),
(28, 2, 'Bloom & Beauty', 'seller12@example.com', '0000000012', '$2y$10$O4W4KOV9VjqnDphIVRIqxezQUywit2lzbVcOOuTUJs4uXyRkMn72e', 'Sellers_Images/bloomb.png', 'Beauty, gifts, and lifestyle products.', 'https://www.instagram.com/demo_bloom_and_beauty/', 1, '2025-04-13 13:18:33'),
(29, 2, 'Sprinkle Oven', 'seller13@example.com', '0000000013', '$2y$10$P1JsTmXSEr83Dm0/rtAFdOwPc4DImhMjPM8uSpgTKwJkpB.CORMIy', 'Sellers_Images/sprinkle.png', 'Fresh cookies and baked treats.', 'https://www.instagram.com/demo_sprinkle_oven/', 1, '2025-04-13 13:34:50'),
(30, 2, 'Tiramisu House', 'seller14@example.com', '0000000014', '$2y$10$UaI/QIwHgqup8egCHJboguNXJzre1BmnBTVeOBWDAWCijroOqyARy', 'Sellers_Images/tirma.png', 'Classic and flavored tiramisu desserts.', 'https://www.instagram.com/demo_tiramisu_house/', 1, '2025-04-13 13:50:51'),
(31, 2, 'Silver Bloom Jewelry', 'seller15@example.com', '0000000015', '$2y$10$m2G4bjliF/fqcadaDQWMjeiEjdSExv.33/1tRPjQeffFUB.io.39u', 'Sellers_Images/silver.png', 'Handmade jewelry and accessories.', 'https://www.instagram.com/demo_silver_bloom_jewelry/', 1, '2025-04-13 14:04:01'),
(32, 3, 'karen', 'karen@gmail.com', '0771234567', '$2y$10$BHoUfQtyQ7ey7sMVUX3KveYBinVVcJP2SkDoFmaaCWzopk0EiZy1G', NULL, NULL, '', 1, '2025-05-04 16:50:16'),
(33, 2, 'Little Gift Garden', 'seller16@example.com', '0000000016', '$2y$10$.hct0NUbwUrmNyRfpeo89eFMbBJrRVbDv27wUkmAHONRGu5N/Bkm2', 'Sellers_Images/giftgar.png', 'Plants, gifts, and handmade arrangements.', 'https://www.instagram.com/demo_little_gift_garden/', 1, '2025-05-04 21:07:52'),
(34, 3, 'Amina', 'Amina@example.com', '0722345', '$2y$10$E0Zz15aC5IZ4ZZ.klApacOvIgAHrNVG5GdoMyWRZrJiNyAUTKuRfa', NULL, NULL, NULL, 1, '2025-05-04 21:23:31'),
(36, 2, 'Cake Studio', 'seller17@example.com', '0000000017', '$2y$10$74X9tidzYfVvbIsysct/PuvRDxU5vNX652X7Rcfpt6BpWpoQWmmvS', 'Sellers_Images/cakestud.png', 'Custom cakes and desserts made to order.', 'https://www.instagram.com/demo_cake_studio/', 1, '2025-05-06 19:38:17'),
(49, 2, 'Gift Box Boutique', 'seller18@example.com', '0000000018', '$2y$10$JmMWYxsmric1PjQX9Ca8t./SGpua4sEyl.DsFZABSOh06tVDGOt8i', 'Sellers_Images/giftboxbo.png', 'Customized gift boxes for special occasions.', 'https://www.instagram.com/demo_gift_box_boutique/', 1, '2025-05-11 14:34:49');

-- --------------------------------------------------------

--
-- Table structure for table `users_types`
--

CREATE TABLE `users_types` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users_types`
--

INSERT INTO `users_types` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Seller'),
(3, 'Buyer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id_cart` (`buyer_id`),
  ADD KEY `product_id_cart` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id_favorit` (`buyer_id`),
  ADD KEY `product_id_favorit` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id_order` (`buyer_id`),
  ADD KEY `status_id_fk` (`status_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id_FK` (`order_id`),
  ADD KEY `product_id_order` (`product_id`),
  ADD KEY `seller_id_order_item_FK` (`seller_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_products_qr_token` (`qr_token`),
  ADD KEY `category_id_FK` (`category_id`),
  ADD KEY `sub_category_place_FK` (`sub_category_id`),
  ADD KEY `sellet_id_FK` (`seller_id`);

--
-- Indexes for table `product_feedbacks`
--
ALTER TABLE `product_feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id_feedback` (`buyer_id`),
  ADD KEY `product_id_feedback` (`product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id_Image_FK` (`product_id`);

--
-- Indexes for table `product_options`
--
ALTER TABLE `product_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id_option_FK` (`product_id`);

--
-- Indexes for table `product_rates`
--
ALTER TABLE `product_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id_rate` (`buyer_id`),
  ADD KEY `product_id_rate` (`product_id`);

--
-- Indexes for table `seller_feedbacks`
--
ALTER TABLE `seller_feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id_seller_feedback` (`buyer_id`),
  ADD KEY `seller_id_feedback` (`seller_id`);

--
-- Indexes for table `seller_subscriptions`
--
ALTER TABLE `seller_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id_subs` (`seller_id`);

--
-- Indexes for table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_categories`
--
ALTER TABLE `sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uset_type_FK` (`user_type_id`);

--
-- Indexes for table `users_types`
--
ALTER TABLE `users_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `advertisements`
--
ALTER TABLE `advertisements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=144;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT for table `product_feedbacks`
--
ALTER TABLE `product_feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_options`
--
ALTER TABLE `product_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `product_rates`
--
ALTER TABLE `product_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `seller_feedbacks`
--
ALTER TABLE `seller_feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `seller_subscriptions`
--
ALTER TABLE `seller_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sub_categories`
--
ALTER TABLE `sub_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `users_types`
--
ALTER TABLE `users_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `buyer_id_cart` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `product_id_cart` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `buyer_id_favorit` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `product_id_favorit` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `buyer_id_order` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `status_id_fk` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_id_FK` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `product_id_order` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `seller_id_order_item_FK` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `category_id_FK` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `sellet_id_FK` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `sub_category_place_FK` FOREIGN KEY (`sub_category_id`) REFERENCES `sub_categories` (`id`);

--
-- Constraints for table `product_feedbacks`
--
ALTER TABLE `product_feedbacks`
  ADD CONSTRAINT `buyer_id_feedback` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `product_id_feedback` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_id_Image_FK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_options`
--
ALTER TABLE `product_options`
  ADD CONSTRAINT `product_id_option_FK` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_rates`
--
ALTER TABLE `product_rates`
  ADD CONSTRAINT `buyer_id_rate` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `product_id_rate` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `seller_feedbacks`
--
ALTER TABLE `seller_feedbacks`
  ADD CONSTRAINT `buyer_id_seller_feedback` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `seller_id_feedback` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `seller_subscriptions`
--
ALTER TABLE `seller_subscriptions`
  ADD CONSTRAINT `seller_id_subs` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `uset_type_FK` FOREIGN KEY (`user_type_id`) REFERENCES `users_types` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
