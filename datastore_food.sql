-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 23, 2025 lúc 03:52 AM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `datastore_food`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `item_id`, `quantity`) VALUES
(1, 33, 42, 1),
(2, 33, 51, 1),
(3, 33, 43, 1),
(4, 33, 55, 1),
(5, 33, 52, 2),
(6, 33, 44, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `item_images`
--

CREATE TABLE `item_images` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `image` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `item_images`
--

INSERT INTO `item_images` (`id`, `item_id`, `image_path`, `image`) VALUES
(3, 42, 'pizza_1.jpg', NULL),
(4, 42, 'pizza_2.jpg', NULL),
(5, 43, 'banhmi_1.jpg', NULL),
(6, 43, 'banhmi_2.jpg', NULL),
(7, 44, 'pho_bo_1.jpg', NULL),
(8, 44, 'pho_bo_2.jpg', NULL),
(9, 45, 'ga_ran_1.jpg', NULL),
(10, 45, 'ga_ran_2.jpg', NULL),
(11, 46, 'mi_xao_1.jpg', NULL),
(12, 46, 'mi_xao_2.jpg', NULL),
(13, 47, 'com_tam_1.jpg', NULL),
(14, 47, 'com_tam_2.jpg', NULL),
(15, 48, 'bun_cha_1.jpg', NULL),
(16, 48, 'bun_cha_2.jpg', NULL),
(21, 51, 'ca_phe_den_1.jpg', NULL),
(22, 51, 'ca_phe_den_2.jpg', NULL),
(23, 52, 'tra_sua_1.jpg', NULL),
(24, 52, 'tra_sua_2.jpg', NULL),
(25, 53, 'nuoc_cam_1.jpg', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loyalty_points`
--

CREATE TABLE `loyalty_points` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `popularity` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('available','out_of_stock') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` enum('food','drink') NOT NULL,
  `category` enum('food','drink') NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `image` blob DEFAULT NULL,
  `description` text DEFAULT NULL,
  `popularity` int(11) DEFAULT 0,
  `description_image` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `additional_info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `menu_items`
--

INSERT INTO `menu_items` (`id`, `store_id`, `name`, `price`, `created_at`, `type`, `category`, `image_path`, `image`, `description`, `popularity`, `description_image`, `quantity`, `additional_info`) VALUES
(42, 1, 'Pizza Hải Sản', 120000, '2025-05-06 20:14:51', 'food', 'food', 'pizza.jpg', NULL, 'Pizza hải sản với lớp vỏ giòn, topping hải sản tươi ngon và phô mai béo ngậy.', 4, 'pizza_description.jpg', 30, 'Chứa các loại hải sản tươi như tôm, mực, và cá hồi.'),
(43, 1, 'Bánh Mì Kẹp Thịt', 35000, '2025-05-06 20:14:51', 'food', 'food', 'banhmi.jpg', NULL, 'Bánh mì kẹp thịt nướng với rau sống và gia vị thơm ngon.', 4, 'banhmi_description.jpg', 40, 'Một món ăn đường phố phổ biến ở Việt Nam.'),
(44, 1, 'Phở Bò', 70000, '2025-05-06 20:14:51', 'food', 'food', 'pho_bo.jpg', NULL, 'Phở bò với nước dùng trong và thịt bò mềm, ăn kèm với giá đỗ và rau thơm.', 5, 'pho_bo_description.jpg', 60, 'Món ăn truyền thống của Việt Nam, được yêu thích vào buổi sáng.'),
(45, 1, 'Gà Rán', 80000, '2025-05-06 20:14:51', 'food', 'food', 'ga_ran.jpg', NULL, 'Gà rán giòn bên ngoài, thịt mềm bên trong, ăn kèm với nước sốt đặc biệt.', 4, 'ga_ran_description.jpg', 35, 'Được chế biến từ gà tươi, da giòn và thịt mềm.'),
(46, 1, 'Mì Xào Hải Sản', 65000, '2025-05-06 20:14:51', 'food', 'food', 'mi_xao.jpg', NULL, 'Mì xào hải sản với tôm, mực, và rau củ, ăn kèm gia vị đặc trưng.', 4, 'mi_xao_description.jpg', 50, 'Một món ăn dễ làm nhưng đầy hương vị.'),
(47, 1, 'Cơm Tấm Sườn', 60000, '2025-05-06 20:14:51', 'food', 'food', 'com_tam.jpg', NULL, 'Cơm tấm sườn nướng với nước mắm đặc trưng, ăn kèm với trứng ốp la.', 5, 'com_tam_description.jpg', 45, 'Một món ăn đặc sản miền Nam Việt Nam.'),
(48, 1, 'Bún Chả Hà Nội', 55000, '2025-05-06 20:14:51', 'food', 'food', 'bun_cha.jpg', NULL, 'Bún chả Hà Nội với thịt nướng thơm lừng, nước mắm chua ngọt và bún tươi.', 5, 'bun_cha_description.jpg', 40, 'Món ăn nổi tiếng ở Hà Nội.'),
(51, 1, 'Cà Phê Đen', 25000, '2025-05-06 20:15:06', 'drink', 'drink', 'ca_phe_den.jpg', NULL, 'Cà phê đen thơm ngon, đậm đà, không đường.', 5, 'ca_phe_den_description.jpg', 100, 'Phù hợp cho người yêu thích cà phê nguyên chất.'),
(52, 1, 'Trà Sữa', 35000, '2025-05-06 20:15:06', 'drink', 'drink', 'tra_sua.jpg', NULL, 'Trà sữa thơm ngon, ngọt ngào với topping trân châu.', 5, 'tra_sua_description.jpg', 80, 'Một thức uống phổ biến tại các quán trà sữa.'),
(53, 1, 'Nước Cam Tươi', 20000, '2025-05-06 20:15:06', 'drink', 'drink', 'nuoc_cam.jpg', NULL, 'Nước cam tươi ép nguyên chất, mát lạnh, bổ sung vitamin C.', 4, 'nuoc_cam_description.jpg', 120, 'Một thức uống giải khát tuyệt vời trong mùa hè.'),
(54, 1, 'Sinh Tố Dâu', 30000, '2025-05-06 20:15:06', 'drink', 'drink', 'sinh_to_dau.jpg', NULL, 'Sinh tố dâu tươi ngon, ngọt ngào, mát lạnh.', 4, 'sinh_to_dau_description.jpg', 50, 'Dành cho những ai yêu thích trái cây tươi.'),
(55, 1, 'Cà Phê Sữa', 30000, '2025-05-06 20:15:06', 'drink', 'drink', 'ca_phe_sua.jpg', NULL, 'Cà phê sữa đá thơm ngon, đậm đà với vị ngọt ngào từ sữa.', 5, 'ca_phe_sua_description.jpg', 90, 'Một lựa chọn hoàn hảo cho những tín đồ cà phê.'),
(82, 1, 'Chè Ba Màu', 25000, '2025-05-16 05:31:56', 'food', 'food', '6826cdccba8d1.webp', NULL, 'Món chè giải nhiệt với 3 tầng màu sắc tươi sáng, kết hợp đậu xanh, đậu đỏ, thạch và nước cốt dừa béo ngậy, ngọt thanh.', 0, NULL, 0, NULL),
(84, 1, 'Bánh Mì Trứng Muối', 35000, '2025-05-16 06:01:55', 'food', 'food', 'banhmi_trungmuoi.jpg', NULL, 'Bánh mì giòn rụm với trứng muối béo ngậy.', 4, 'banhmi_trungmuoi_description.jpg', 60, 'Ăn sáng chuẩn vị Sài Gòn.'),
(85, 1, 'Phở Gà', 60000, '2025-05-16 06:01:55', 'food', 'food', 'pho_ga.jpg', NULL, 'Phở gà thanh ngọt nước dùng, thịt gà dai ngon.', 5, 'pho_ga_description.jpg', 50, 'Món ăn truyền thống Việt Nam.'),
(87, 1, 'Bún Bò Huế', 65000, '2025-05-16 06:01:55', 'food', 'food', 'bun_bo_hue.jpg', NULL, 'Bún bò cay nồng đậm đà hương vị Huế.', 5, 'bun_bo_hue_description.jpg', 40, 'Chua cay hấp dẫn.'),
(90, 1, 'Bánh Xèo', 40000, '2025-05-16 06:01:55', 'food', 'food', 'banh_xeo.jpg', NULL, 'Bánh xèo vàng giòn, nhân tôm thịt thơm ngon.', 4, 'banh_xeo_description.jpg', 50, 'Món ăn miền Nam cực yêu thích.'),
(91, 1, 'Nem Nướng', 50000, '2025-05-16 06:01:55', 'food', 'food', 'nem_nuong.jpg', NULL, 'Nem nướng thơm lừng, chấm nước mắm chua ngọt.', 5, 'nem_nuong_description.jpg', 60, 'Ăn là ghiền.'),
(92, 1, 'Bánh Canh', 55000, '2025-05-16 06:01:55', 'food', 'food', 'banh_canh.jpg', NULL, 'Bánh canh nóng hổi với nước dùng ngọt thanh.', 4, 'banh_canh_description.jpg', 50, 'Phù hợp mùa lạnh.'),
(93, 1, 'Cháo Lòng', 40000, '2025-05-16 06:01:55', 'food', 'food', 'chao_long.jpg', NULL, 'Cháo lòng đậm đà, thơm mùi tiêu và hành.', 4, 'chao_long_description.jpg', 45, 'Ăn sáng cực ổn.'),
(94, 1, 'Trà Sữa Trân Châu', 35000, '2025-05-16 06:01:55', 'drink', 'drink', 'tra_sua_tran_chau.jpg', NULL, 'Trà sữa ngọt ngào với trân châu dai dai.', 5, 'tra_sua_tran_chau_description.jpg', 80, 'Thức uống hot nhất hiện nay.'),
(96, 1, 'Cà Phê Sữa Đá', 30000, '2025-05-16 06:01:55', 'drink', 'drink', 'ca_phe_sua_da.jpg', NULL, 'Cà phê sữa đá đậm đà, vị ngọt béo.', 5, 'ca_phe_sua_da_description.jpg', 100, 'Tuyệt phẩm buổi sáng.'),
(97, 1, 'Sinh Tố Bơ', 40000, '2025-05-16 06:01:55', 'drink', 'drink', 'sinh_to_bo.jpg', NULL, 'Sinh tố bơ mịn màng, thơm béo.', 5, 'sinh_to_bo_description.jpg', 75, 'Giải nhiệt mùa hè.'),
(99, 1, 'Bánh Bao', 30000, '2025-05-16 06:01:55', 'food', 'food', 'banh_bao.jpg', NULL, 'Bánh bao nhân thịt thơm mềm.', 4, 'banh_bao_description.jpg', 70, 'Ăn sáng tiện lợi.'),
(101, 1, 'Gà Xào Sả Ớt', 70000, '2025-05-16 06:01:55', 'food', 'food', 'ga_xao_sa_ot.jpg', NULL, 'Gà xào sả ớt cay cay, thơm phức.', 4, 'ga_xao_sa_ot_description.jpg', 40, 'Món chính đậm đà.'),
(102, 1, 'Bún Thịt Nướng', 55000, '2025-05-16 06:01:55', 'food', 'food', 'bun_thit_nuong.jpg', NULL, 'Bún thịt nướng thơm ngon, ăn kèm rau.', 5, 'bun_thit_nuong_description.jpg', 60, 'Ăn no mà không ngán.'),
(103, 1, 'Cà Phê Đen', 25000, '2025-05-16 06:01:55', 'drink', 'drink', 'ca_phe_den.jpg', NULL, 'Cà phê đen nguyên chất, đậm vị.', 5, 'ca_phe_den_description.jpg', 110, 'Thức uống cho dân ghiền cà phê.'),
(104, 1, 'Trà Đào', 30000, '2025-05-16 06:01:55', 'drink', 'drink', 'tra_dao.jpg', NULL, 'Trà đào ngọt dịu, thơm mát.', 4, 'tra_dao_description.jpg', 90, 'Uống là nghiện.'),
(105, 1, 'Kem Trái Cây', 45000, '2025-05-16 06:01:55', 'food', 'food', 'kem_trai_cay.jpg', NULL, 'Kem trái cây mát lạnh, nhiều vị.', 5, 'kem_trai_cay_description.jpg', 50, 'Giải nhiệt mùa hè.'),
(106, 1, 'Cơm Gà Xối Mỡ', 60000, '2025-05-16 06:01:55', 'food', 'food', 'com_ga_xoi_mo.jpg', NULL, 'Cơm gà xối mỡ giòn rụm, thơm ngon.', 5, 'com_ga_xoi_mo_description.jpg', 55, 'Món chính cực ngon.'),
(107, 1, 'Mỳ Ý Sốt Bò', 80000, '2025-05-16 06:01:55', 'food', 'food', 'my_y_sot_bo.jpg', NULL, 'Mỳ Ý sốt bò đậm đà, phô mai béo.', 5, 'my_y_sot_bo_description.jpg', 40, 'Ăn kiểu Tây chuẩn vị.'),
(110, 1, 'Bánh Gối', 35000, '2025-05-16 06:01:55', 'food', 'food', 'banh_goi.jpg', NULL, 'Bánh gối giòn rụm, nhân thịt đậm đà.', 4, 'banh_goi_description.jpg', 60, 'Ăn vặt cực ngon.'),
(111, 1, 'Gà Nướng Mật Ong', 75000, '2025-05-16 06:01:55', 'food', 'food', 'ga_nuong_mat_ong.jpg', NULL, 'Gà nướng mật ong thơm ngọt, thịt mềm.', 5, 'ga_nuong_mat_ong_description.jpg', 45, 'Món ăn đặc biệt.'),
(112, 1, 'Bánh Cuốn', 40000, '2025-05-16 06:01:55', 'food', 'food', 'banh_cuon.jpg', NULL, 'Bánh cuốn mỏng, nhân thịt thơm ngon.', 4, 'banh_cuon_description.jpg', 50, 'Ăn sáng miền Bắc.'),
(113, 1, 'Nước Chanh Muối', 20000, '2025-05-16 06:01:55', 'drink', 'drink', 'nuoc_chanh_muoi.jpg', NULL, 'Nước chanh muối mát lạnh, chua cay.', 5, 'nuoc_chanh_muoi_description.jpg', 120, 'Giải khát nhanh.'),
(114, 1, 'Mì Xào Giòn', 50000, '2025-05-16 06:01:55', 'food', 'food', 'mi_xao_gion.jpg', NULL, 'Mì xào giòn ngon, sốt đậm đà.', 4, 'mi_xao_gion_description.jpg', 55, 'Món ngon miệng.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `type` enum('order','promotion','system') NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `shipper_id` int(11) DEFAULT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `verification_code` varchar(6) NOT NULL,
  `expiry_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `verification_code`, `expiry_time`) VALUES
(1, 25, '830941', 1745825764),
(2, 28, '670333', 1745825778),
(3, 25, '763321', 1745825876);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `restaurants`
--

CREATE TABLE `restaurants` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shippers`
--

CREATE TABLE `shippers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vehicle_type` enum('bike','car','motorbike') NOT NULL,
  `status` enum('available','busy','offline') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `store_info`
--

CREATE TABLE `store_info` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT 'default_store.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
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
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `name`, `phone`, `created_at`, `updated_at`, `verification_code`, `is_verified`, `registration_count`, `verified`, `points`, `address`, `avatar`) VALUES
(25, 'volengocson19@gmail.com', '$2y$10$SQubdFTU4FGHV9g7EALUK.AbmpPMvKcpy2YPQxQvY98K23t2uAZYO', '', 'Ngọc Sơn Võ', '0857551919', '2025-04-28 06:57:15', '2025-04-28 06:57:20', '365786', 0, 1, 1, 0, NULL, NULL),
(28, 'volengocson9@gmail.com', '$2y$10$W5.wtxOiDcnG6ytpjaNxve1.Ti0nU3T0o8fobBYBLHPqcoH.dBBpe', 'store_owner', 'Vole Ngocson', '0865237919', '2025-04-28 07:03:37', '2025-04-28 07:03:37', '913820', 0, 1, 0, 0, NULL, NULL),
(29, 'volengocson195@gmail.com', '$2y$10$.r85HrxvXDChnf5XKBRgOOC/dUpLMZ8V1TyX8oQlmw/t6sqPBEeuK', 'store_owner', 'Vole Ngocson', '0865237919', '2025-04-28 07:05:29', '2025-04-28 07:07:52', '400824', 0, 1, 1, 0, NULL, NULL),
(32, '123@gmail.com', '$2y$10$oZg3OKgwtZODN54S6ubJ2OXPLy4Pv/r2aQF8at5GjAGKWXN7Zpjki', 'customer', 'Thien Phong', '0861515151', '2025-05-05 04:35:36', '2025-05-05 04:35:36', '771812', 0, 1, 0, 0, NULL, NULL),
(33, 'volengocson123@gmail.com', '$2y$10$srJUMj6vzil/Ip8FjiG7HOzheNrUGClC59b4jmNhi6W01Bin6Nj/u', 'customer', 'Ngọc Sơn', '0851515151', '2025-05-05 04:36:20', '2025-05-15 00:36:50', '466137', 0, 1, 1, 72, '490/3 Hoang Van Thu', 'avatar_68253722b10701.43325695.jpg'),
(34, 'huuthien@gmail.com', '$2y$10$.Q3178lzWEc/vigtV.OEAOGLUwZLWrAP9WlVSSawtTEEygQ2oUQw.', 'customer', 'Thiện Béo', '0856191877', '2025-05-15 01:23:44', '2025-05-15 01:25:09', '976205', 0, 1, 1, 0, '311 Phạm Văn Đồng ', 'avatar_6825427523f4e8.84501826.jpg'),
(35, 'tp123@gmail.com', '$2y$10$FaTTUcj8GgPibatk1Q2/RO8nzFAvw.ZuSFPIxZUZrjGpH6FiG6Xhy', 'store_owner', 'Thiên Phong', '012345678', '2025-05-15 06:27:34', '2025-05-15 06:27:40', '396712', 0, 1, 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL,
  `valid_from` datetime NOT NULL,
  `valid_until` datetime NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Đang đổ dữ liệu cho bảng `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `discount_percentage`, `valid_from`, `valid_until`, `created_at`) VALUES
(1, 'SAVE10', 10.00, '2025-01-01 00:00:00', '2025-12-31 23:59:59', '2025-04-26 17:10:55');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Chỉ mục cho bảng `item_images`
--
ALTER TABLE `item_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Chỉ mục cho bảng `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Chỉ mục cho bảng `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Chỉ mục cho bảng `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `shipper_id` (`shipper_id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Chỉ mục cho bảng `shippers`
--
ALTER TABLE `shippers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `store_info`
--
ALTER TABLE `store_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `item_images`
--
ALTER TABLE `item_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `loyalty_points`
--
ALTER TABLE `loyalty_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `shippers`
--
ALTER TABLE `shippers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `store_info`
--
ALTER TABLE `store_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `item_images`
--
ALTER TABLE `item_images`
  ADD CONSTRAINT `item_images_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`id`);

--
-- Các ràng buộc cho bảng `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD CONSTRAINT `loyalty_points_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`);

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`shipper_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `restaurants`
--
ALTER TABLE `restaurants`
  ADD CONSTRAINT `restaurants_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`id`);

--
-- Các ràng buộc cho bảng `shippers`
--
ALTER TABLE `shippers`
  ADD CONSTRAINT `shippers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `store_info`
--
ALTER TABLE `store_info`
  ADD CONSTRAINT `store_info_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
