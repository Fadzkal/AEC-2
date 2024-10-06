-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 26, 2024 at 09:35 AM
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
-- Database: `projek`
--

-- --------------------------------------------------------

--
-- Table structure for table `demo_crud`
--

CREATE TABLE `demo_crud` (
  `id` int(6) UNSIGNED NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `demo_crud`
--

INSERT INTO `demo_crud` (`id`, `nama`, `email`) VALUES
(1, 'Muhammad Dzaki', 'muhammadikazd@gmail.com'),
(2, 'dzaki1', 'zefas7777@gmail.com'),
(3, 'dzaki 2', 'kingforce79@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `event_date`, `event_time`) VALUES
(1, 'PMPO25', '2024-09-16', '04:30:00'),
(3, 'qwe', '2024-09-14', '13:17:00'),
(4, 'qwe', '2024-09-18', '17:26:00'),
(5, 'ppp', '2024-09-26', '20:07:00');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `due_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `type`, `file`, `due_date`) VALUES
(41, 'matematika', 'Proyek', 'uploads/Tugas.zip', '2024-10-05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(100) NOT NULL,
  `created` date NOT NULL,
  `token` varchar(100) NOT NULL,
  `tokenExpire` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `pass`, `created`, `token`, `tokenExpire`, `reset_token`, `reset_expires`) VALUES
(6, 'Daffa Nazwa', 'daffa', 'daffa@gmail.com', 'b8f20fd94b32a093f12949c8f870ba83963f6f4e', '2024-09-25', '', '2024-09-25 05:36:10.244351', NULL, NULL),
(7, 'Adhikara Wildan Firdaus', 'adhikara', 'wadhikara@gmail.com', '6204d8fa74d18bd103a1532e99b80f82330ece82', '2024-09-25', '', '2024-09-25 05:42:22.295532', NULL, NULL),
(8, 'Fadzkal Luthfi', 'paskal', 'fadzkal@email.com', 'f1f725d06af013456a9b98d923f9b2ce5c7ba91a', '2024-09-25', '', '2024-09-25 05:43:51.675849', NULL, NULL),
(9, 'Arbiyan Saputra', 'Xen1try', 'arbiyan2006@gmail.com', '11189dc89e2b08740bb7c2a9177379e1af432769', '2024-09-25', '', '2024-09-25 12:12:00.450686', NULL, NULL),
(10, 'Insan Salsabila', 'insaan', 'insan@gmail.com', '632292eea9ff790c0965b836125a73fd25b7119c', '2024-09-25', '', '2024-09-25 07:44:16.622800', NULL, NULL),
(11, 'Insan Salsabila', 'insaaan', 'insaan@gmail.com', '632292eea9ff790c0965b836125a73fd25b7119c', '2024-09-25', '', '2024-09-25 07:50:55.371314', NULL, NULL),
(12, 'Budiono Siregar', 'kapallaut', 'kapallaut@gmail.com', 'b1b3773a05c0ed0176787a4f1574ff0075f7521e', '2024-09-25', '', '2024-09-25 08:33:28.567894', NULL, NULL),
(13, 'Alvian Muhammad Reihan', 'vian', 'alvianreihan7@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2024-09-26', '', '2024-09-26 06:59:01.059557', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `demo_crud`
--
ALTER TABLE `demo_crud`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `demo_crud`
--
ALTER TABLE `demo_crud`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
