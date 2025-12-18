-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql111.byetcluster.com
-- Generation Time: Dec 18, 2025 at 10:35 AM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40180122_epiz_12345678_my_newdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `drinks`
--

CREATE TABLE `drinks` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drinks`
--

INSERT INTO `drinks` (`id`, `name`, `description`, `price`, `category`, `created_at`, `image`) VALUES
(11, 'ðŸº European Beers', '', '100000.00', '', '2025-12-16 06:14:56', '6940f8e064564.jfif'),
(12, 'ðŸº American Beers', '', '10000000.00', '', '2025-12-16 06:26:56', '6940fbb0270e5.jfif'),
(13, 'African Beers', '', '200000.00', '', '2025-12-16 06:27:57', '6940fbedd90d9.jfif'),
(14, 'ðŸº Asian Beers', '', '80000.00', '', '2025-12-16 06:29:37', '6940fc51025f2.jfif'),
(15, 'Belgian Beers', '', '1200000.00', '', '2025-12-16 09:43:38', '694129ca47d24.jpeg'),
(16, 'ðŸ¯Mead (Honey wine)', '', '400000.00', '', '2025-12-16 09:46:29', '69412a758a10e.jpeg'),
(17, 'Champagne / Sparkling wine', '', '2500000.00', '', '2025-12-16 09:47:35', '69412ab770998.jpeg'),
(18, 'Whisky / Liquor', '', '600000.00', '', '2025-12-16 09:48:30', '69412aeea71ef.jpeg'),
(19, 'Goose Island Bourbon County Stou', '', '300000.00', '', '2025-12-17 12:53:52', '6942a7e02c922.jpeg'),
(20, 'Sierra Nevada Pale Ale', '', '5000000.00', '', '2025-12-17 12:55:12', '6942a83076eaf.jpeg'),
(21, 'Lagunitas IPA', '', '800000.00', '', '2025-12-17 12:56:40', '6942a88807532.jpeg'),
(23, 'Pliny the Elder', '', '1000000.00', '', '2025-12-17 13:00:58', '6942a98a9d8f3.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`id`, `name`, `price`, `image`) VALUES
(5, 'Pizza', '10000.00', 'hh.jpeg'),
(6, 'Sushi', '300000.00', 'tt.jpeg'),
(7, 'Chocolate', '200000.00', 'cc.jpeg'),
(8, 'Tacos', '400000.00', 'ddd.jpeg'),
(9, 'Cooked Grains & Staples', '20000.00', 'wwwww.jpeg'),
(10, 'Cooked Proteins', '600000.00', 'br.jpeg'),
(11, 'Stews & Special Dishes', '300000.00', 'hhhhhhhhh.jpeg'),
(12, 'Fast Foods / Street Foods', '8000.00', 'fas.jpeg'),
(13, 'Pad Thai', '120000.00', 'ddd.jpeg'),
(14, 'Fried Rice', '40000.00', 'oooooooo.jpeg'),
(15, 'Shepherdâ€™s Pie', '500000.00', 'kee.jpeg'),
(16, 'Wiener Schnitzel', '600000.00', 'xxx.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_type` enum('drink','food') NOT NULL,
  `drink_id` int(11) DEFAULT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `guests` int(11) NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'UKOBUKEYE Mordekai', 'mordekai893@gmail.com', '$2y$10$7WljXmN6IOTkJ076AWEZsuelPWewHIhZdgstWjn33jucrwUsztQYC', 'admin', '2025-11-20 12:09:14'),
(3, 'UKOBUKEYE Mordekai', 'umordekai4@gmail.com', '$2y$10$en5zE.a7gd5Wfc9q.hlDdeHZNwbwnujwbWV1QdLAKW6z9lpi/rcC6', 'admin', '2025-11-20 12:30:44'),
(7, 'Blaise', 'blaise@gmail.com', '$2y$10$xl4p4IsSnpGTxlz156MCbuk.w438mdZUB.yW6LuOEeXGO8Rn0k/Wm', 'admin', '2025-12-16 06:08:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drinks`
--
ALTER TABLE `drinks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_res_user` (`user_id`),
  ADD KEY `fk_res_drink` (`drink_id`);

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
-- AUTO_INCREMENT for table `drinks`
--
ALTER TABLE `drinks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_res_drink` FOREIGN KEY (`drink_id`) REFERENCES `drinks` (`id`),
  ADD CONSTRAINT `fk_res_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
