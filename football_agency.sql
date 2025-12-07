-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2025 at 02:47 PM
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
-- Database: `football_agency`
--

-- --------------------------------------------------------

--
-- Table structure for table `agents`
--

CREATE TABLE `agents` (
  `agent_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `license_no` varchar(100) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `agency_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agents`
--

INSERT INTO `agents` (`agent_id`, `user_id`, `license_no`, `experience_years`, `agency_name`) VALUES
(1, 11, 'LIC12345', 5, 'Star Sports Agency'),
(2, 21, 'Linma22', 20, 'Top Shot');

-- --------------------------------------------------------

--
-- Table structure for table `clubs`
--

CREATE TABLE `clubs` (
  `club_id` int(11) NOT NULL,
  `club_name` varchar(150) NOT NULL,
  `location` varchar(150) DEFAULT NULL,
  `league` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `clubs`
--

INSERT INTO `clubs` (`club_id`, `club_name`, `location`, `league`, `created_at`) VALUES
(1, 'Silver Stars FC', 'Freetown', 'Premier League', '2025-11-22 11:41:38'),
(2, 'Golden Eagles', 'Bo', 'Premier League', '2025-11-22 11:41:38'),
(3, 'River Rovers', 'Kenema', 'Division 1', '2025-11-22 11:41:38');

-- --------------------------------------------------------

--
-- Table structure for table `club_managers`
--

CREATE TABLE `club_managers` (
  `manager_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `club_id` int(11) NOT NULL,
  `office_number` varchar(20) DEFAULT NULL,
  `manager_name` varchar(100) DEFAULT NULL,
  `age` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `club_managers`
--

INSERT INTO `club_managers` (`manager_id`, `user_id`, `club_id`, `office_number`, `manager_name`, `age`) VALUES
(1, 12, 1, 'OFF101', 'Daniel Koroma', '40'),
(2, 20, 2, 'OFF-123', 'Time Doe', '34');

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `player_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `club_id` int(11) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `height` varchar(10) DEFAULT NULL,
  `weight` varchar(10) DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`player_id`, `user_id`, `club_id`, `age`, `position`, `nationality`, `height`, `weight`, `image`) VALUES
(1, 2, 1, 22, 'Forward', 'Sierra Leonean', '5.10', '70kg', 'player1.jpg'),
(2, 3, 1, 24, 'Midfielder', 'Sierra Leonean', '5.9', '68kg', 'player2.jpg'),
(3, 4, 2, 21, 'Defender', 'Sierra Leonean', '6.0', '75kg', 'player3.jpg'),
(4, 5, 2, 23, 'Forward', 'Sierra Leonean', '5.11', '72kg', 'player4.jpg'),
(5, 6, 3, 20, 'Goalkeeper', 'Sierra Leonean', '6.2', '78kg', 'player5.jpg'),
(6, 7, 3, 25, 'Midfielder', 'Sierra Leonean', '5.8', '70kg', 'player6.jpg'),
(7, 8, 1, 22, 'Defender', 'Sierra Leonean', '5.9', '71kg', 'player7.jpg'),
(8, 9, 2, 24, 'Forward', 'Sierra Leonean', '5.10', '73kg', 'player8.jpg'),
(9, 10, 3, 26, 'Midfielder', 'Sierra Leonean', '5.11', '74kg', 'player9.jpg'),
(10, 13, 1, 23, 'Defender', 'Sierra Leonean', '6.0', '76kg', 'player10.jpg'),
(11, 14, 2, 21, 'Forward', 'Sierra Leonean', '5.9', '69kg', 'player11.jpg'),
(12, 22, 1, 15, 'Defender', 'Sierra Leonean', '170.5cm', '89.6', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `player_agent_assignments`
--

CREATE TABLE `player_agent_assignments` (
  `assignment_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player_agent_assignments`
--

INSERT INTO `player_agent_assignments` (`assignment_id`, `player_id`, `agent_id`, `assigned_at`) VALUES
(1, 1, 1, '2025-11-22 12:01:03'),
(2, 2, 1, '2025-11-22 12:01:03'),
(3, 3, 1, '2025-11-22 12:01:03'),
(4, 4, 1, '2025-11-22 12:01:03'),
(5, 5, 1, '2025-11-22 12:01:03'),
(6, 6, 1, '2025-11-22 12:01:03'),
(7, 7, 1, '2025-11-22 12:01:03'),
(8, 8, 1, '2025-11-22 12:01:03'),
(9, 9, 1, '2025-11-22 12:01:03'),
(10, 10, 1, '2025-11-22 12:01:03'),
(11, 11, 1, '2025-11-22 12:01:03'),
(12, 10, 2, '2025-11-22 12:23:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Player','Agent','Club Manager') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `phone`) VALUES
(1, 'Joshua Yoki', 'joshua@example.com', 'hashed_password1', 'Admin', '2025-11-22 11:40:16', '232770000001'),
(2, 'Michael Obi', 'michael@example.com', 'hashed_password2', 'Player', '2025-11-22 11:40:16', '232770000002'),
(3, 'David Kamara', 'david@example.com', 'hashed_password3', 'Player', '2025-11-22 11:40:16', '232770000003'),
(4, 'John Sesay', 'john@example.com', 'hashed_password4', 'Player', '2025-11-22 11:40:16', '232770000004'),
(5, 'Samuel Koroma', 'samuel@example.com', 'hashed_password5', 'Player', '2025-11-22 11:40:16', '232770000005'),
(6, 'Abdul Conteh', 'abdul@example.com', 'hashed_password6', 'Player', '2025-11-22 11:40:16', '232770000006'),
(7, 'Musa Bangura', 'musa@example.com', 'hashed_password7', 'Player', '2025-11-22 11:40:16', '232770000007'),
(8, 'Mohamed Jalloh', 'mohamed@example.com', 'hashed_password8', 'Player', '2025-11-22 11:40:16', '232770000008'),
(9, 'Patrick Kamara', 'patrick@example.com', 'hashed_password9', 'Player', '2025-11-22 11:40:16', '232770000009'),
(10, 'Sulaiman Conteh', 'sulaiman@example.com', 'hashed_password10', 'Player', '2025-11-22 11:40:16', '232770000010'),
(11, 'Victor Sesay', 'victor@example.com', 'hashed_password11', 'Agent', '2025-11-22 11:40:16', '232770000011'),
(12, 'Daniel Koroma', 'daniel@example.com', 'hashed_password12', 'Club Manager', '2025-11-22 11:40:16', '232770000012'),
(13, 'Emmanuel Conteh', 'emmanuel@example.com', 'hashed_password13', 'Player', '2025-11-22 11:40:16', '232770000013'),
(14, 'Ibrahim Kamara', 'ibrahim@example.com', 'hashed_password14', 'Player', '2025-11-22 11:40:16', '232770000014'),
(15, 'Abubakar Bangura', 'abubakar@example.com', 'hashed_password15', 'Player', '2025-11-22 11:40:16', '232770000015'),
(20, 'Tim Doe', 'johnDo.e@gmail.com', '$2y$10$WU.FC0HSuChx8WQu2MCuSukUtV.97aXR4Ev9KRnE/OzcgvXhh9G0S', 'Club Manager', '2025-11-22 12:12:43', NULL),
(21, 'Thomas  JJ', 'jj.e@gmail.com', '$2y$10$2pjGy9720rrx13rVwuLTDenzOLjLlvPMoTkDiC6j1RpughBp1HDD.', 'Agent', '2025-11-22 12:20:11', NULL),
(22, 'Jason A Yoki', 'jj.2e@gmail.com', '$2y$10$WMbUkssk1BusgNbdW0ksp.gQC7MaV27AnhJVbB9DdPjcDmyMXhDcO', 'Player', '2025-11-22 12:25:36', NULL),
(23, 'John Admin', 'admin1@example.com', '$2y$10$vhDDozwZ1dPIyxP0wPQWV.7ju78KQHpICyYrIpRAGUEC5TzE1a26q', 'Admin', '2025-11-23 22:57:33', NULL),
(24, 'Sarah Admin', 'admin2@example.com', '$2y$10$XTH9qWtTEQORc9ExeB8abeQz2qKiMriM0j4chX5WqipzLpMYScDna', 'Admin', '2025-11-23 22:57:33', NULL),
(25, 'Michael Striker', 'player1@example.com', '$2y$10$f/EFqW1khHdi1EwQu1W4y.7K/FYGo4OkHA3W8UXUy6Og5m1JpsiQa', 'Player', '2025-11-23 22:57:33', NULL),
(26, 'Samuel Midfielder', 'player2@example.com', '$2y$10$WTCHBWcfNRVqCe1pgwFWmOWwn2oB15bErqaYz3V5aW1YexlrJJMAq', 'Player', '2025-11-23 22:57:33', NULL),
(27, 'Alex Agent', 'agent1@example.com', '$2y$10$bY7710NBMkEToUshhFb.bOnvX7GCB0L./KBI/XVC1NxdIcyVMf.JS', 'Agent', '2025-11-23 22:57:34', NULL),
(28, 'Rico Agent', 'agent2@example.com', '$2y$10$aTLlSnGBUjU/VSfS2dGXReVUIfWlnpZ./PBfHCw3yO5HbrWG2xVGu', 'Agent', '2025-11-23 22:57:34', NULL),
(29, 'Chris Manager', 'manager1@example.com', '$2y$10$k77pXq4neVnscDxcr0tUv.8akL/s3em.SZmlj52RPmC.QlJfDGIg2', 'Club Manager', '2025-11-23 22:57:34', NULL),
(30, 'Daniel Manager', 'manager2@example.com', '$2y$10$I1tNdwa2.RS4qbOJzgxEDeTmbMB0QHPR3Q8VJz/Q5tFJuFjWcWnki', 'Club Manager', '2025-11-23 22:57:34', NULL),
(31, 'Joshua Yoki', 'jj@example.com', '$2y$10$Lmmxsck9dXzsT.SmfrXHa.pJCm2jNFxcVpYR2pzBuYgd556gB438a', 'Agent', '2025-11-30 16:20:57', NULL),
(32, 'TWim', '123@gmail.com', '$2y$10$zAkpwtywhdmWmUQj2lXhBuHCHzsNhrdCkHe69khzN5fwF.QhCEEOa', 'Agent', '2025-11-30 16:27:59', NULL),
(33, 'Yoki', '1j@example.com', '$2y$10$xaJDjtgOlUieH6JQLPcP7.k6rgQmJ1i6LhPIHyyeF2pPU.3sBZbG6', 'Agent', '2025-11-30 16:33:20', NULL),
(34, 'Maurice Bangura', '22jj@example.com', '$2y$10$x0IldgGHQljTmzPM1fSxTOUI7RqRh2SPd8fgwkxvglSZKZCWhi00W', 'Club Manager', '2025-11-30 16:37:00', NULL),
(35, 'tim Will', '33@example.com', '$2y$10$UCQL/.u28jl5p7S1t.Dz3uyS8CW.YCGL1/HQ3R3dV5RikTm6zDziy', 'Player', '2025-11-30 16:42:54', NULL),
(36, 'tin tin', 'kkaj@example.com', '$2y$10$lrCNTU3hW.MdiAPOdoMEY.4eRcI0L..B6KJoxE4evltH3FTEVj4Sq', 'Club Manager', '2025-11-30 16:45:03', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agents`
--
ALTER TABLE `agents`
  ADD PRIMARY KEY (`agent_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `clubs`
--
ALTER TABLE `clubs`
  ADD PRIMARY KEY (`club_id`);

--
-- Indexes for table `club_managers`
--
ALTER TABLE `club_managers`
  ADD PRIMARY KEY (`manager_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `club_id` (`club_id`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`player_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `club_id` (`club_id`);

--
-- Indexes for table `player_agent_assignments`
--
ALTER TABLE `player_agent_assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `player_id` (`player_id`),
  ADD KEY `agent_id` (`agent_id`);

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
-- AUTO_INCREMENT for table `agents`
--
ALTER TABLE `agents`
  MODIFY `agent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `clubs`
--
ALTER TABLE `clubs`
  MODIFY `club_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `club_managers`
--
ALTER TABLE `club_managers`
  MODIFY `manager_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `player_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `player_agent_assignments`
--
ALTER TABLE `player_agent_assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agents`
--
ALTER TABLE `agents`
  ADD CONSTRAINT `agents_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `club_managers`
--
ALTER TABLE `club_managers`
  ADD CONSTRAINT `club_managers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `club_managers_ibfk_2` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`club_id`) ON DELETE CASCADE;

--
-- Constraints for table `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `players_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `players_ibfk_2` FOREIGN KEY (`club_id`) REFERENCES `clubs` (`club_id`) ON DELETE SET NULL;

--
-- Constraints for table `player_agent_assignments`
--
ALTER TABLE `player_agent_assignments`
  ADD CONSTRAINT `player_agent_assignments_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `players` (`player_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `player_agent_assignments_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `agents` (`agent_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
