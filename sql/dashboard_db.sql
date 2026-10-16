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
-- Database: `dashboard_db`
--
CREATE DATABASE IF NOT EXISTS `dashboard_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `dashboard_db`;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(30) NOT NULL,
  `details` varchar(255) NOT NULL DEFAULT '',
  `ip_address` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`log_id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 1, 'LOGIN_SUCCESS', 'Logged in as admin', '::1', '2026-10-14 10:15:33'),
(2, 1, 'SESSION_TIMEOUT', 'No activity for 600 seconds', '::1', '2026-10-14 11:26:19'),
(3, 1, 'LOGIN_SUCCESS', 'Logged in as admin', '::1', '2026-10-14 11:26:37'),
(4, 2, 'LOGIN_SUCCESS', 'Logged in as student', '::1', '2026-10-14 11:29:09'),
(5, 2, 'UNAUTHORIZED_ACCESS', 'Role \'student\' tried to open dashboard.php', '::1', '2026-10-14 11:30:51'),
(6, 2, 'UNAUTHORIZED_ACCESS', 'Role \'student\' tried to open users.php', '::1', '2026-10-14 11:31:31'),
(7, 2, 'UNAUTHORIZED_ACCESS', 'Role \'student\' tried to open users.php', '::1', '2026-10-14 11:31:34'),
(8, 2, 'LOGOUT', 'User logged out', '::1', '2026-10-14 11:31:43'),
(9, 2, 'LOGIN_FAILED', 'Attempt with: rahul', '::1', '2026-10-14 11:32:38'),
(10, 5, 'LOGIN_BLOCKED', 'Inactive account tried to log in', '::1', '2026-10-14 11:33:00'),
(11, 1, 'USER_DEACTIVATED', 'Admin \'admin\' set user \'priya\' (id 3) to inactive', '::1', '2026-10-14 11:33:36'),
(12, 1, 'USER_ACTIVATED', 'Admin \'admin\' set user \'priya\' (id 3) to active', '::1', '2026-10-14 11:33:49'),
(13, 1, 'USER_DEACTIVATED', 'Admin \'admin\' set user \'priya\' (id 3) to inactive', '::1', '2026-10-14 11:33:58'),
(14, 1, 'USER_ACTIVATED', 'Admin \'admin\' set user \'priya\' (id 3) to active', '::1', '2026-10-14 11:34:45'),
(15, 3, 'LOGIN_SUCCESS', 'Logged in as student', '::1', '2026-10-14 11:35:00'),
(16, 1, 'USER_DEACTIVATED', 'Admin \'admin\' set user \'priya\' (id 3) to inactive', '::1', '2026-10-14 11:35:13'),
(17, 3, 'FORCED_LOGOUT', 'Account is inactive or was removed', '::1', '2026-10-14 11:35:19'),
(18, 1, 'LOGOUT', 'User logged out', '::1', '2026-10-14 11:36:09'),
(19, 1, 'LOGIN_SUCCESS', 'Logged in as admin', '::1', '2026-10-14 11:36:37'),
(20, 1, 'LOGOUT', 'User logged out', '::1', '2026-10-14 11:38:20'),
(21, 1, 'LOGIN_SUCCESS', 'Logged in as admin', '::1', '2026-10-14 11:38:29'),
(22, 1, 'SESSION_TIMEOUT', 'No activity for 20 seconds', '::1', '2026-10-14 11:41:42');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `category` varchar(20) NOT NULL,
  `event_date` date NOT NULL,
  `venue` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `title`, `category`, `event_date`, `venue`) VALUES
(1, 'Hackathon 2026', 'Technical', '2026-11-15', 'Main Auditorium'),
(2, 'Python Workshop', 'Workshop', '2026-09-20', 'Computer Lab 2'),
(3, 'Cultural Night', 'Cultural', '2026-12-02', 'Open Air Theatre'),
(4, 'Cricket Tournament', 'Sports', '2026-10-25', 'College Ground'),
(5, 'AI Seminar', 'Seminar', '2026-08-12', 'Seminar Hall'),
(6, 'Web Dev Workshop', 'Workshop', '2026-05-18', 'Computer Lab 1');

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

DROP TABLE IF EXISTS `registrations`;
CREATE TABLE `registrations` (
  `registration_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registered_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`registration_id`, `student_id`, `event_id`, `registered_at`) VALUES
(1, 1, 6, '2026-03-05 10:15:00'),
(2, 2, 6, '2026-03-07 11:00:00'),
(3, 3, 6, '2026-04-02 09:30:00'),
(4, 4, 6, '2026-04-10 14:20:00'),
(5, 5, 6, '2026-05-01 16:45:00'),
(6, 1, 5, '2026-06-12 12:10:00'),
(7, 2, 5, '2026-06-15 13:00:00'),
(8, 6, 5, '2026-06-20 10:05:00'),
(9, 7, 5, '2026-07-03 15:30:00'),
(10, 8, 5, '2026-07-08 09:50:00'),
(11, 9, 5, '2026-07-22 11:40:00'),
(12, 1, 2, '2026-08-02 10:00:00'),
(13, 3, 2, '2026-08-05 10:30:00'),
(14, 4, 2, '2026-08-11 17:10:00'),
(15, 5, 2, '2026-08-18 12:25:00'),
(16, 10, 2, '2026-08-25 09:15:00'),
(17, 2, 4, '2026-09-02 08:45:00'),
(18, 6, 4, '2026-09-09 14:05:00'),
(19, 7, 4, '2026-09-14 16:20:00'),
(20, 8, 4, '2026-09-21 11:35:00'),
(21, 1, 1, '2026-10-01 10:10:00'),
(22, 2, 1, '2026-10-03 12:00:00'),
(23, 3, 1, '2026-10-06 13:45:00'),
(24, 9, 1, '2026-10-09 15:15:00'),
(25, 4, 3, '2026-10-11 09:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `full_name`, `email`, `course`) VALUES
(1, 'Rahul Patel', 'rahul@example.com', 'BCA'),
(2, 'Priya Shah', 'priya@example.com', 'BTech'),
(3, 'Amit Mehta', 'amit@example.com', 'MCA'),
(4, 'Neha Joshi', 'neha@example.com', 'BSc IT'),
(5, 'Kunal Desai', 'kunal@example.com', 'BCA'),
(6, 'Riya Desai', 'riya@example.com', 'BTech'),
(7, 'Arjun Nair', 'arjun@example.com', 'BCA'),
(8, 'Meera Iyer', 'meera@example.com', 'MCA'),
(9, 'Rohan Gupta', 'rohan@example.com', 'BTech'),
(10, 'Sneha Kulkarni', 'sneha@example.com', 'BSc IT'),
(11, 'Test Student', 'test.student@example.com', 'BCA');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','student') NOT NULL DEFAULT 'student',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `role`, `status`, `last_login`, `created_at`) VALUES
(1, 'admin', 'admin@college.edu', '$2y$10$6Vra3p00ylCAUGZLS1eueO/MG6.cNJFH.sV7PjJTR07RzymPjNGWe', 'admin', 'active', '2026-10-14 17:08:29', '2026-10-14 10:13:43'),
(2, 'rahul', 'rahul@example.com', '$2y$10$Qh/nLI4ysD1sPLygueSvQeZU4q8lgCTdSleJdEadQf9195IXd6mPK', 'student', 'active', '2026-10-14 16:59:09', '2026-10-14 10:13:43'),
(3, 'priya', 'priya@example.com', '$2y$10$oa8NmfzBqr.rTsWE00VVDOWhDgIQi0rgXQjQdFnxLJp/j3LapEYu2', 'student', 'inactive', '2026-10-14 17:05:00', '2026-10-14 10:13:43'),
(4, 'neha', 'neha@example.com', '$2y$10$bIA3mSZp8bW6s9R2bW9jRODy86V8slRtmdCmdu8MM5W2hRf5sM66K', 'student', 'active', NULL, '2026-10-14 10:13:43'),
(5, 'amit', 'amit@example.com', '$2y$10$nUGzbx2SG4nC7pheu9ty4uHMO7NLshnQ6Fn240OkxxuEHhLNOp1AS', 'student', 'inactive', NULL, '2026-10-14 10:13:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_log_user` (`user_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`registration_id`),
  ADD UNIQUE KEY `uq_student_event` (`student_id`,`event_id`),
  ADD KEY `fk_reg_event` (`event_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `fk_reg_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reg_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
