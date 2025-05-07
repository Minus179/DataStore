-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 12:23 PM
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
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','store_owner','shipper') NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `verification_code` varchar(255) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `registration_count` int(11) DEFAULT 0,
  `verified` tinyint(1) DEFAULT 0,
  `points` int(11) DEFAULT 0,
  `address` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `name`, `phone`, `created_at`, `updated_at`, `verification_code`, `is_verified`, `registration_count`, `verified`, `points`, `address`, `avatar`) VALUES
(25, 'volengocson19@gmail.com', '$2y$10$SQubdFTU4FGHV9g7EALUK.AbmpPMvKcpy2YPQxQvY98K23t2uAZYO', '', 'Ngọc Sơn Võ', '0857551919', '2025-04-28 06:57:15', '2025-04-28 06:57:20', '365786', 0, 1, 1, 0, NULL, NULL),
(28, 'volengocson9@gmail.com', '$2y$10$W5.wtxOiDcnG6ytpjaNxve1.Ti0nU3T0o8fobBYBLHPqcoH.dBBpe', 'store_owner', 'Vole Ngocson', '0865237919', '2025-04-28 07:03:37', '2025-04-28 07:03:37', '913820', 0, 1, 0, 0, NULL, NULL),
(29, 'volengocson195@gmail.com', '$2y$10$.r85HrxvXDChnf5XKBRgOOC/dUpLMZ8V1TyX8oQlmw/t6sqPBEeuK', 'store_owner', 'Vole Ngocson', '0865237919', '2025-04-28 07:05:29', '2025-04-28 07:07:52', '400824', 0, 1, 1, 0, NULL, NULL),
(32, '123@gmail.com', '$2y$10$oZg3OKgwtZODN54S6ubJ2OXPLy4Pv/r2aQF8at5GjAGKWXN7Zpjki', 'customer', 'Thien Phong', '0861515151', '2025-05-05 04:35:36', '2025-05-05 04:35:36', '771812', 0, 1, 0, 0, NULL, NULL),
(33, 'volengocson123@gmail.com', '$2y$10$srJUMj6vzil/Ip8FjiG7HOzheNrUGClC59b4jmNhi6W01Bin6Nj/u', 'customer', 'Ngọc Sơn', '0851515151', '2025-05-05 04:36:20', '2025-05-07 10:12:19', '466137', 0, 1, 1, 72, '490 Hoang Van Thu', 'avatar_681b3203ee6fd0.67448586.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
