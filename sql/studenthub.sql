-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2026 at 11:54 AM
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
-- Database: `studenthub`
--
CREATE DATABASE IF NOT EXISTS `studenthub` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `studenthub`;

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`log_id`, `user_id`, `action`, `ip_address`, `created_at`) VALUES
(1, 1, 'REGISTER_SUCCESS', '::1', '2026-10-11 15:57:50'),
(2, NULL, 'REGISTER_DUPLICATE', '::1', '2026-10-11 16:01:40'),
(3, NULL, 'REGISTER_DUPLICATE', '::1', '2026-10-11 16:01:55'),
(4, 2, 'REGISTER_SUCCESS', '::1', '2026-10-11 16:03:30'),
(5, 3, 'LOGIN_SUCCESS', '::1', '2026-10-11 16:13:11'),
(6, 3, 'LOGOUT', '::1', '2026-10-11 16:13:25'),
(7, 4, 'LOGIN_FAILED', '::1', '2026-10-11 16:14:35'),
(8, 4, 'LOGIN_SUCCESS', '::1', '2026-10-11 16:14:50'),
(9, 4, 'SESSION_TIMEOUT', '::1', '2026-10-11 16:31:22'),
(10, 4, 'LOGIN_SUCCESS', '::1', '2026-10-11 16:32:06'),
(11, 4, 'LOGOUT', '::1', '2026-10-11 16:32:17'),
(12, NULL, 'LOGIN_FAILED', '::1', '2026-10-11 16:32:30'),
(13, NULL, 'LOGIN_FAILED', '::1', '2026-10-11 16:32:45'),
(14, NULL, 'LOGIN_FAILED', '::1', '2026-10-11 16:33:44'),
(15, NULL, 'LOGIN_FAILED', '::1', '2026-10-11 16:34:07'),
(16, 3, 'LOGIN_SUCCESS', '::1', '2026-10-11 16:34:39'),
(17, 4, 'LOGIN_SUCCESS', '::1', '2026-10-12 11:36:17');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` date NOT NULL,
  `venue` varchar(100) NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `title`, `description`, `event_date`, `venue`, `capacity`) VALUES
(1, 'Hackathon 2026', '24-hour coding contest', '2026-11-15', 'Main Auditorium', 100),
(2, 'Tech Talk: AI', 'Guest lecture on AI', '2026-11-20', 'Seminar Hall', 50),
(3, 'Sports Day', 'Annual sports meet', '2026-12-05', 'Ground', 200);

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

DROP TABLE IF EXISTS `registrations`;
CREATE TABLE `registrations` (
  `registration_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`registration_id`, `student_id`, `event_id`, `registered_at`) VALUES
(1, 1, 1, '2026-10-11 15:23:05'),
(2, 1, 2, '2026-10-11 15:23:05'),
(3, 2, 1, '2026-10-11 15:23:05'),
(4, 3, 2, '2026-10-11 15:23:05'),
(5, 4, 3, '2026-10-11 15:23:05'),
(6, 5, 1, '2026-10-11 15:23:05'),
(7, 4, 1, '2026-10-11 15:34:32');

-- --------------------------------------------------------

--
-- Table structure for table `remember_tokens`
--

DROP TABLE IF EXISTS `remember_tokens`;
CREATE TABLE `remember_tokens` (
  `token_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` char(24) NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `full_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` char(10) NOT NULL,
  `course` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `full_name`, `email`, `mobile`, `course`, `created_at`) VALUES
(1, 'Rahul Patel', 'rahul@example.com', '9876543210', 'BCA', '2026-10-11 15:23:05'),
(2, 'Priya Shah', 'priya@example.com', '9123456780', 'BTech', '2026-10-11 15:23:05'),
(3, 'Amit Mehta', 'amit@example.com', '9988776655', 'MCA', '2026-10-11 15:23:05'),
(4, 'Neha Joshi', 'neha@example.com', '9090909090', 'BSc IT', '2026-10-11 15:23:05'),
(5, 'Kunal Desai', 'kunal@example.com', '8765432109', 'BCA', '2026-10-11 15:23:05'),
(6, 'Test User', 'test1791732596@example.com', '9000000000', 'BCA', '2026-10-11 15:29:56'),
(7, 'Test User', 'test1791732606@example.com', '9000000000', 'BCA', '2026-10-11 15:30:06'),
(8, 'Test User', 'test1792144107@example.com', '9000000000', 'BCA', '2026-10-16 09:48:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','student') NOT NULL DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password_hash`, `role`, `created_at`, `last_login`) VALUES
(1, '25dce056', 'vshd@gmail.com', '$2y$10$zY58JeImD5Ko0I4DKu3Wu.I29blnAsoXQgxFrszAZliWOzk08Q9TK', 'student', '2026-10-11 15:57:50', NULL),
(2, '25dce056_D', 'abcd@gmail.com', '$2y$10$KTngFjnpqkWrV8haToo0Xeo7ePYBKI8xDlmRzb3RdIQahWiFQcuCC', 'student', '2026-10-11 16:03:30', NULL),
(3, 'admin', 'admin@studenthub.com', '$2y$10$L2St7KCgBpivi.yKX5lumu2JHc2w3xpWs.GTmef5gfaTSqr85/Gia', 'admin', '2026-10-11 16:07:09', '2026-10-11 22:04:39'),
(4, 'student1', 'student1@studenthub.com', '$2y$10$q9ZlSbuyXmfwA9KOJJjzXuY.mTw1ZstvOvL.TSjCTfvKnBZOmYDRS', 'student', '2026-10-11 16:07:09', '2026-10-12 17:06:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_audit_user` (`user_id`);

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
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`token_id`),
  ADD UNIQUE KEY `selector` (`selector`),
  ADD KEY `fk_token_user` (`user_id`);

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
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `registration_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `fk_reg_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reg_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD CONSTRAINT `fk_token_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
