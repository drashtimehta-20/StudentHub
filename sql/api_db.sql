-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2026 at 11:57 AM
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
-- Database: `api_db`
--
CREATE DATABASE IF NOT EXISTS `api_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `api_db`;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` char(10) NOT NULL,
  `course` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `phone`, `course`, `created_at`) VALUES
(1, 'Aarav Sharma', 'aarav.sharma@example.com', '9876500001', 'BCA', '2026-10-14 08:13:53'),
(2, 'Vivaan Patel', 'vivaan.patel@example.com', '9876500002', 'MCA', '2026-10-14 08:13:53'),
(3, 'Aditya Desai', 'aditya.desai@example.com', '9876500003', 'B.Tech', '2026-10-14 08:13:53'),
(4, 'Diya Mehta', 'diya.mehta@example.com', '9876500004', 'BCA', '2026-10-14 08:13:53'),
(5, 'Ishaan Joshi', 'ishaan.joshi@example.com', '9876500005', 'M.Tech', '2026-10-14 08:13:53'),
(6, 'Ananya Shah', 'ananya.shah@example.com', '9876500006', 'BSc IT', '2026-10-14 08:13:53'),
(7, 'Kabir Singh', 'kabir.singh@example.com', '9876500007', 'MCA', '2026-10-14 08:13:53'),
(8, 'Riya Desai', 'riya.desai@example.com', '9876500008', 'B.Tech', '2026-10-14 08:13:53'),
(9, 'Arjun Nair', 'arjun.nair@example.com', '9876500009', 'BCA', '2026-10-14 08:13:53'),
(10, 'Meera Iyer', 'meera.iyer@example.com', '9876500010', 'MCA', '2026-10-14 08:13:53'),
(11, 'Rohan Gupta', 'rohan.gupta@example.com', '9876500011', 'B.Tech', '2026-10-14 08:13:53'),
(12, 'Sneha Kulkarni', 'sneha.kulkarni@example.com', '9876500012', 'BSc IT', '2026-10-14 08:13:53'),
(13, 'Drashti', 'abcd@example.com', '9123456780', 'B.Tech', '2026-10-14 08:20:06'),
(14, 'Test Student', 'abdfvd@example.com', '9876543210', 'BCA', '2026-10-14 09:28:37'),
(15, 'gchv', 'vbc@vhn.com', '9876543211', 'BCA', '2026-10-14 09:29:29'),
(16, 'Meett', 'meet.sharma@example.com', '9869873210', 'MCA', '2026-10-14 09:33:55');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
