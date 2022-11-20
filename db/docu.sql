-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2022 at 03:15 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `docu`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time DEFAULT NULL,
  `activity` text DEFAULT NULL,
  `image` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `user_id`, `date`, `time_in`, `time_out`, `activity`, `image`) VALUES
(3, 5, '2022-11-01', '15:03:51', NULL, NULL, '/media/11012022-030351_webcam.jpg'),
(4, 19, '2022-11-12', '09:55:07', NULL, NULL, '/media/11122022-095507_webcam.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `short_name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `name`, `short_name`) VALUES
(1, 'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY', 'BSIT'),
(2, 'BACHELOR OF SCIENCE IN INFORMATION SYSTEM', 'BSIS'),
(3, 'BACHELOR OF SCIENCE IN COMPUTER SCIENCE ', 'BSCS'),
(4, 'BACHELOR OF SCIENCE IN FOOD TECHNOLOGY ', 'BSFT');

-- --------------------------------------------------------

--
-- Table structure for table `office`
--

CREATE TABLE `office` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `office`
--

INSERT INTO `office` (`id`, `name`) VALUES
(1, 'Buenavista Town Hall'),
(2, 'Guimaras State Collage ICT OFFICE (Salvador Campus)'),
(3, 'Department of Agriculture Office');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `setting_id` int(11) NOT NULL,
  `hours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`setting_id`, `hours`) VALUES
(1, 600);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `section` varchar(32) DEFAULT NULL,
  `deployment_id` int(11) DEFAULT NULL,
  `role` enum('student','super-admin','admin','') NOT NULL,
  `isNew` tinyint(1) DEFAULT NULL,
  `office_account_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `mname`, `email`, `password`, `course_id`, `section`, `deployment_id`, `role`, `isNew`, `office_account_id`) VALUES
(1, 'super1', 'admin', '', 'admin@email.com', '$argon2i$v=19$m=65536,t=4,p=1$eUwwdk5LYUxTcGgyMzE1eA$acMNjJdFkn5MOuqQrmAAC/Y3z0DIzwNFXbAe1t/Nhqs', NULL, NULL, NULL, 'super-admin', 0, NULL),
(5, 'test', 'test', 'test', 'test_student@email.com', '$argon2i$v=19$m=65536,t=4,p=1$bGhEckZhaXFsakxVWkIxMQ$7YmZuXWl+l1L2n8mWtBfyU04ZFqCDp/Psl/tM8FZRmA', 1, 'b', 2, 'student', NULL, NULL),
(19, 'test2', 'test', 'test', 'test_student@email.com', '$argon2i$v=19$m=65536,t=4,p=1$bGhEckZhaXFsakxVWkIxMQ$7YmZuXWl+l1L2n8mWtBfyU04ZFqCDp/Psl/tM8FZRmA', 1, 'b', 1, 'student', NULL, NULL),
(20, 'test', 'test', 'test', 'test@email.com', '$argon2i$v=19$m=65536,t=4,p=1$Qkx4TlA1NG1JS3pHb2h1MA$Oz1H0LO2L+urP0Q3ZXSRRXxtmV6Ve1FzY/q1x3U85CA', NULL, NULL, NULL, 'admin', 0, 1),
(21, 'student', 'lname', 'mname', 'test1@email.com', '$argon2i$v=19$m=65536,t=4,p=1$OE5tdjFpL1pQZDlRNzczdg$pwd/59E7cF3NnbvGadTlwtao2F55oS9mDg1Wwl+P3/E', 1, 'b', 0, 'student', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `office`
--
ALTER TABLE `office`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `office`
--
ALTER TABLE `office`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
