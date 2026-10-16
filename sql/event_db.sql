-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2026 at 11:58 AM
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
-- Database: `event_db`
--
CREATE DATABASE IF NOT EXISTS `event_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `event_db`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$B9q/a0NXi5s/0FRsagbvP.Fj15RSkL.z3znGuYKY8BUxJOcB810Iq');

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log` (
  `id` int(11) NOT NULL,
  `admin_username` varchar(50) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `action` varchar(20) NOT NULL,
  `details` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`id`, `admin_username`, `event_id`, `action`, `details`, `created_at`) VALUES
(1, 'admin', 13, 'CREATE', 'Created \'Web Devp Quiz\' with poster 78a7c4d98c65c2f375081f1a1f2ff7c5.jpg', '2026-10-14 07:29:54'),
(2, 'admin', 8, 'UPDATE', 'venue: \'Open Air Theatre\' -> \'Assembly hall\'', '2026-10-14 07:32:33'),
(3, 'admin', 9, 'DELETE', 'Deleted \'Inter-College Cricket\'', '2026-10-14 07:33:37');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `event_date` date NOT NULL,
  `venue` varchar(150) NOT NULL,
  `category` enum('Technical','Cultural','Sports','Workshop','Seminar') NOT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'open',
  `poster` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `event_date`, `venue`, `category`, `status`, `poster`, `created_at`) VALUES
(1, 'Hackathon 2026', '24-hour coding hackathon open to all departments.', '2026-11-15', 'Main Auditorium', 'Technical', 'open', NULL, '2026-10-14 05:31:17'),
(2, 'Annual Cultural Fest', 'Music, dance and drama performances by students.', '2026-12-05', 'Open Air Theatre', 'Cultural', 'open', NULL, '2026-10-14 05:31:17'),
(3, 'Inter-College Cricket', 'Knockout cricket tournament between colleges.', '2026-11-22', 'College Ground', 'Sports', 'open', NULL, '2026-10-14 05:31:17'),
(4, 'PHP Workshop', 'Hands-on workshop on PHP and MySQL for beginners.', '2026-10-20', 'Computer Lab 3', 'Workshop', 'closed', NULL, '2026-10-14 05:31:17'),
(5, 'AI Awareness Seminar', 'Expert talk on the impact of AI in education.', '2026-10-12', 'Seminar Hall', 'Seminar', 'closed', NULL, '2026-10-14 05:31:17'),
(6, 'Web Design Contest', 'Design and build a responsive web page in 3 hours.', '2026-11-30', 'Computer Lab 1', 'Technical', 'open', NULL, '2026-10-14 05:31:17'),
(7, 'Hackathon 2026', '24-hour coding hackathon open to all departments.', '2026-11-15', 'Main Auditorium', 'Technical', 'open', NULL, '2026-10-14 05:32:06'),
(8, 'Annual Cultural Fest', 'Music, dance and drama performances by students.', '2026-12-05', 'Assembly hall', 'Cultural', 'open', NULL, '2026-10-14 05:32:06'),
(10, 'PHP Workshop', 'Hands-on workshop on PHP and MySQL for beginners.', '2026-10-20', 'Computer Lab 3', 'Workshop', 'closed', NULL, '2026-10-14 05:32:06'),
(11, 'AI Awareness Seminar', 'Expert talk on the impact of AI in education.', '2026-10-12', 'Seminar Hall', 'Seminar', 'closed', NULL, '2026-10-14 05:32:06'),
(12, 'Web Design Contest', 'Design and build a responsive web page in 3 hours.', '2026-11-30', 'Computer Lab 1', 'Technical', 'open', NULL, '2026-10-14 05:32:06'),
(13, 'Web Devp Quiz', 'Seminar of the subject Web Development', '2026-10-22', 'Seminar Hall', 'Technical', 'open', '78a7c4d98c65c2f375081f1a1f2ff7c5.jpg', '2026-10-14 07:29:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
