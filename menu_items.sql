-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 10:36 AM
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
-- Database: `datastore_food`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` enum('food','drink') NOT NULL,
  `category` enum('food','drink') NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `image` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `price`, `created_at`, `type`, `category`, `image_path`, `image`) VALUES
(41, 'Burger Thịt Bò', 50000, '2025-05-07 03:14:51', 'food', 'food', 'burger.jpg', NULL),
(42, 'Pizza Hải Sản', 120000, '2025-05-07 03:14:51', 'food', 'food', 'pizza.jpg', NULL),
(43, 'Bánh Mì Kẹp Thịt', 35000, '2025-05-07 03:14:51', 'food', 'food', 'banhmi.jpg', NULL),
(44, 'Phở Bò', 70000, '2025-05-07 03:14:51', 'food', 'food', 'pho_bo.jpg', NULL),
(45, 'Gà Rán', 80000, '2025-05-07 03:14:51', 'food', 'food', 'ga_ran.jpg', NULL),
(46, 'Mì Xào Hải Sản', 65000, '2025-05-07 03:14:51', 'food', 'food', 'mi_xao.jpg', NULL),
(47, 'Cơm Tấm Sườn', 60000, '2025-05-07 03:14:51', 'food', 'food', 'com_tam.jpg', NULL),
(48, 'Bún Chả Hà Nội', 55000, '2025-05-07 03:14:51', 'food', 'food', 'bun_cha.jpg', NULL),
(49, 'Nem Rán', 40000, '2025-05-07 03:14:51', 'food', 'food', 'nem_ran.jpg', NULL),
(50, 'Salad Trộn', 30000, '2025-05-07 03:14:51', 'food', 'food', 'salad.jpg', NULL),
(51, 'Cà Phê Đen', 25000, '2025-05-07 03:15:06', 'drink', 'drink', 'ca_phe_den.jpg', NULL),
(52, 'Trà Sữa', 35000, '2025-05-07 03:15:06', 'drink', 'drink', 'tra_sua.jpg', NULL),
(53, 'Nước Cam Tươi', 20000, '2025-05-07 03:15:06', 'drink', 'drink', 'nuoc_cam.jpg', NULL),
(54, 'Sinh Tố Dâu', 30000, '2025-05-07 03:15:06', 'drink', 'drink', 'sinh_to_dau.jpg', NULL),
(55, 'Cà Phê Sữa', 30000, '2025-05-07 03:15:06', 'drink', 'drink', 'ca_phe_sua.jpg', NULL),
(56, 'Nước Suối', 10000, '2025-05-07 03:15:06', 'drink', 'drink', 'nuoc_suoi.jpg', NULL),
(57, 'Sữa Tươi', 18000, '2025-05-07 03:15:06', 'drink', 'drink', 'sua_tuoi.jpg', NULL),
(58, 'Trà Đào', 22000, '2025-05-07 03:15:06', 'drink', 'drink', 'tra_dao.jpg', NULL),
(59, 'Nước Ép Cà Rốt', 25000, '2025-05-07 03:15:06', 'drink', 'drink', 'ep_ca_rot.jpg', NULL),
(60, 'Trà Chanh', 15000, '2025-05-07 03:15:06', 'drink', 'drink', 'tra_chanh.jpg', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
