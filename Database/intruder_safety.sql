-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2026 at 11:18 AM
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
-- Database: `intruder_safety`
--

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`) VALUES
(19, 2, '36632fd240afc4e45e0deb29c892a02a76f152f026321f82243da425549e3c1c', '2026-06-26 12:29:04'),
(20, 2, 'e78fc6cd8d928454285ec8b86f390ade76aafb497da6901d9687bdb3f28d81b2', '2026-06-26 12:29:21'),
(21, 3, '39b1f858e12d4865e03d01db5bf9064d72f7594d281e01c28c5cd0ae5ad35074', '2026-06-26 12:30:05'),
(22, 3, 'e1bd350f7f3622091427c20947db0c9496c0c89e4b6d5c42485f7dc0dc2c1b70', '2026-06-26 12:30:17'),
(23, 3, 'a10d8ccab84a3f5dcb18fa94480253b0715c20b5f14a6a1edfce5740c02a16de', '2026-06-26 12:30:56'),
(24, 3, '775a010461d2b6c9fdff375293609e126847b6d14c6f55b1e0bd17bc129b72db', '2026-06-26 15:23:56'),
(26, 2, 'eb90d11106d0ac69c5aa4a0aca31e93f521b1529aefd9eab0293b26cf318b27a', '2026-06-26 15:30:24'),
(27, 2, '7a3a3ddbcf2dd479461458a74bec2ff08d3dd42c554b066081e4ad37e8ad8eb8', '2026-06-26 15:33:06'),
(28, 2, 'ed3c96d4c0d5be09280c5a2fa5d4d6b8fc93e2d4d5d000cf741ec4ddf129ce33', '2026-06-26 15:33:18'),
(29, 2, '86761a0666bc16d27c936c28c33aa76e0522d20bf20e2162842bf9f356207449', '2026-06-26 15:33:44'),
(30, 2, '21fa49c5e7f6e85739ea920f28252262137ad495b7034323210fe73f5c64089a', '2026-06-26 15:33:57'),
(31, 2, '2b200b57d84f5facaf965306fc12f81e3ac09168c6f2b0b38a1cde1c78881430', '2026-06-26 15:34:31'),
(32, 2, 'b4d63dc71ae403b68111c9e5428cd78c1772d38d9cfec4e81030787fadd038d1', '2026-06-26 15:34:53'),
(33, 2, 'df78e2c39124b187820e311f52d0b1fa2f19e13f382d936f34c7a624f5e19291', '2026-06-26 15:41:03'),
(34, 2, '71116ddc45a055756956ea9cd47d2e8356842dfd800f1601cdebe1221faecfb5', '2026-06-26 15:41:48'),
(35, 2, 'a63dd844498bb1ecab647f7445ed77897ce4f31d6b4c48e96f2ceb4955a46b8a', '2026-06-26 15:42:04');

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`id`, `user_id`, `description`, `location`, `photo`, `created_at`) VALUES
(3, 3, 'Manual Test Capture', '21.233307,72.8697107', '1782458810_violation_1782458809844.jpg', '2026-06-26 07:26:50'),
(4, 3, 'Manual Test Capture', '21.233307,72.8697107', '1782459562_violation_1782458817981.jpg', '2026-06-26 07:39:22'),
(5, 3, 'Manual Test Capture', '21.2332928,72.8697231', '1782463931_violation_1782463931594.jpg', '2026-06-26 08:52:11'),
(6, 3, 'Manual Test Capture', '21.2332928,72.8697231', '1782463987_violation_1782463948970.jpg', '2026-06-26 08:53:07'),
(7, 3, 'Manual Test Capture', '21.2332928,72.8697231', '1782463987_violation_1782463952457.jpg', '2026-06-26 08:53:07'),
(8, 3, 'Manual Test Capture', '21.2332928,72.8697231', '1782463987_violation_1782463952457.jpg', '2026-06-26 08:53:07'),
(9, 3, 'Manual Test Capture', '21.2332928,72.8697231', '1782463987_violation_1782463952457.jpg', '2026-06-26 08:53:07');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$8XV/NzYdpNy2YUc0EiCWH.MTn6aj040MD6OZMLZaah7yiLSzIsN.u', 'admin', '2026-06-26 05:47:38'),
(2, 'Amit', '2024068823@vnsgu.ac.in', '$2y$10$Xfbqw6ir7PkH8UZ0T8JSuessOBeD893vWA/wgAWsjCArNPAufXajm', 'user', '2026-06-26 05:58:57'),
(3, 'Test', 'royalgroup5912@gmail.com', '$2y$10$Zo/1YXnulUdojkWa9YOBaON5cGX1HI9nXqluPVdWSSOc2zhPg7vCe', 'user', '2026-06-26 05:59:59');

-- --------------------------------------------------------

--
-- Table structure for table `user_tokens`
--

CREATE TABLE `user_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tokens`
--

INSERT INTO `user_tokens` (`id`, `user_id`, `token`, `created_at`) VALUES
(1, 3, 'f4d7df5e12d3a669ad4cf6eb5e8869cf57c63299619f46ec1a214e5778290263', '2026-06-26 06:05:23'),
(2, 3, 'd9bbf52cec6204b1bb2150193d9ea50f7a5a855f03a59ac119023744ab606323', '2026-06-26 09:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_tokens`
--
ALTER TABLE `user_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_tokens`
--
ALTER TABLE `user_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `records`
--
ALTER TABLE `records`
  ADD CONSTRAINT `records_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
