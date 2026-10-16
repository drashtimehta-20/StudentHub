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
-- Database: `student_db`
--
CREATE DATABASE IF NOT EXISTS `student_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `student_db`;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL,
  `phone` char(10) NOT NULL,
  `course` varchar(30) NOT NULL,
  `status` enum('active','deleted') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `phone`, `course`, `status`, `created_at`) VALUES
(1, 'Aarav Patel', 'aarav.patel@example.com', '9876500001', 'BCA', 'active', '2026-10-14 04:28:36'),
(2, 'Diya Shah', 'diya.shah@example.com', '9876500002', 'MCA', 'active', '2026-10-14 04:28:36'),
(3, 'Rohan Mehta', 'rohan.mehta@example.com', '9876500003', 'B.Tech', 'active', '2026-10-14 04:28:36'),
(4, 'Isha Desai', 'isha.desai@example.com', '9876500004', 'BBA', 'active', '2026-10-14 04:28:36'),
(5, 'Kabir Joshi', 'kabir.joshi@example.com', '9876500005', 'B.Sc', 'active', '2026-10-14 04:28:36'),
(6, 'Meera Trivedi', 'meera.trivedi@example.com', '9876500006', 'BCA', 'active', '2026-10-14 04:28:36'),
(7, 'Yash Parmar', 'yash.parmar@example.com', '9876500007', 'M.Tech', 'active', '2026-10-14 04:28:36'),
(8, 'Anaya Gandhi', 'anaya.gandhi@example.com', '9876500008', 'MCA', 'active', '2026-10-14 04:28:36'),
(9, 'Vivaan Rana', 'vivaan.rana@example.com', '9876500009', 'B.Tech', 'active', '2026-10-14 04:28:36'),
(10, 'Saanvi Modi', 'saanvi.modi@example.com', '9876500010', 'BBA', 'active', '2026-10-14 04:28:36'),
(11, 'Arjun Thakkar', 'arjun.thakkar@example.com', '9876500011', 'BCA', 'active', '2026-10-14 04:28:36'),
(12, 'Kavya Bhatt', 'kavya.bhatt@example.com', '9876500012', 'B.Sc', 'active', '2026-10-14 04:28:36'),
(13, 'Dev Solanki', 'dev.solanki@example.com', '9876500013', 'MCA', 'active', '2026-10-14 04:28:36'),
(14, 'Riya Chauhan', 'riya.chauhan@example.com', '9876500014', 'B.Tech', 'active', '2026-10-14 04:28:36'),
(15, 'Harsh Vora', 'harsh.vora@example.com', '9876500015', 'BCA', 'active', '2026-10-14 04:28:36'),
(16, 'Nidhi Pandya', 'nidhi.pandya@example.com', '9876500016', 'BBA', 'active', '2026-10-14 04:28:36'),
(17, 'Manav Kapadia', 'manav.kapadia@example.com', '9876500017', 'M.Tech', 'active', '2026-10-14 04:28:36'),
(18, 'Tara Dave', 'tara.dave@example.com', '9876500018', 'B.Sc', 'active', '2026-10-14 04:28:36'),
(19, 'Om Raval', 'om.raval@example.com', '9876500019', 'MCA', 'active', '2026-10-14 04:28:36'),
(20, 'Pooja Barot', 'pooja.barot@example.com', '9876500020', 'BCA', 'active', '2026-10-14 04:28:36'),
(21, 'Neel Amin', 'neel.amin@example.com', '9876500021', 'B.Tech', 'active', '2026-10-14 04:28:36'),
(22, 'Sneha Prajapati', 'sneha.prajapati@example.com', '9876500021', 'BBA', 'active', '2026-10-14 04:28:36'),
(23, 'Drashti Mehta', 'abcd@example.com', '9234567890', 'B.Tech', 'active', '2026-10-14 04:37:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
