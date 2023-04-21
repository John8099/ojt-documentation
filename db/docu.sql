-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2023 at 07:29 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

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
  `image` varchar(250) NOT NULL,
  `log_type` varchar(2) NOT NULL COMMENT 'AM, PM'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `user_id`, `date`, `time_in`, `time_out`, `activity`, `image`, `log_type`) VALUES
(1, 5, '2023-03-06', '11:41:10', '11:41:17', '<p>awdawdawd</p>', '/media/03062023-114110_webcam.jpg', 'AM');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `short_name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `educational`
--

CREATE TABLE `educational` (
  `educational_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `elementary` varchar(255) NOT NULL,
  `elem_grad` date DEFAULT NULL,
  `secondary` varchar(255) NOT NULL,
  `sec_grad` date DEFAULT NULL,
  `vocational` varchar(255) DEFAULT NULL,
  `voc_grad` date DEFAULT NULL,
  `college` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `educational`
--

INSERT INTO `educational` (`educational_id`, `user_id`, `elementary`, `elem_grad`, `secondary`, `sec_grad`, `vocational`, `voc_grad`, `college`) VALUES
(1, 5, 'Elementary', '2023-02-05', 'Secondary', '2023-02-02', NULL, NULL, 'College');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_data`
--

CREATE TABLE `emergency_data` (
  `emergency_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `relationship` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_data`
--

INSERT INTO `emergency_data` (`emergency_id`, `user_id`, `name`, `relationship`, `address`, `contact`) VALUES
(1, 5, 'Test', 'Mother', 'Incase Address', '09876543121212');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation`
--

CREATE TABLE `evaluation` (
  `evaluation_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `evaluation` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation`
--

INSERT INTO `evaluation` (`evaluation_id`, `admin_id`, `user_id`, `evaluation`, `created_at`) VALUES
(11, 22, 5, '[{\"title\":\"Attends regularly\",\"name\":\"behavior_a\",\"value\":\"1\"},{\"title\":\"Starts the work promptly\",\"name\":\"behavior_b\",\"value\":\"2\"},{\"title\":\"Courteous and Considerate\",\"name\":\"behavior_c\",\"value\":\"3\"},{\"title\":\"Express his/her ideas well\",\"name\":\"behavior_d\",\"value\":\"3\"},{\"title\":\"Listen attentively to trainer\",\"name\":\"behavior_e\",\"value\":\"4\"},{\"title\":\"Display interest in his/her work\",\"name\":\"behavior_f\",\"value\":\"3\"},{\"title\":\"Careful in handling office facilities and equipment\",\"name\":\"behavior_g\",\"value\":\"4\"},{\"title\":\"Works to the best of his/her ability.\",\"name\":\"behavior_h\",\"value\":\"4\"},{\"title\":\"Works to develop a variety of skills.\",\"name\":\"behavior_i\",\"value\":\"4\"},{\"title\":\"Cooperates well with others.\",\"name\":\"behavior_j\",\"value\":\"4\"},{\"title\":\"Is generally  a good follower\",\"name\":\"behavior_k\",\"value\":\"3\"},{\"title\":\"Accepts responsibility\",\"name\":\"behavior_l\",\"value\":\"4\"},{\"title\":\"Volunteers for an assignment\",\"name\":\"behavior_m\",\"value\":\"3\"},{\"title\":\"Makes worth with suggestion\",\"name\":\"behavior_n\",\"value\":\"4\"},{\"title\":\"Exhibits orderly/ safe working habits\",\"name\":\"behavior_o\",\"value\":\"4\"},{\"title\":\"Applies principles to actual work station\",\"name\":\"behavior_p\",\"value\":\"3\"},{\"title\":\"Knowledge in assigned job proceedings\",\"name\":\"behavior_q\",\"value\":\"4\"},{\"title\":\"Ability to plan activities\",\"name\":\"behavior_r\",\"value\":\"5\"},{\"title\":\"Initiative/ resourcefulness\",\"name\":\"behavior_s\",\"value\":\"4\"},{\"title\":\"Judgment and common sense\",\"name\":\"behavior_t\",\"value\":\"5\"},{\"title\":\"Interest and good attitude towards work\",\"name\":\"behavior_u\",\"value\":\"4\"},{\"title\":\"Prepare report accurately\",\"name\":\"behavior_v\",\"value\":\"3\"},{\"title\":\"Submits reports on time\",\"name\":\"behavior_w\",\"value\":\"4\"}]', '2023-03-06 03:35:36');

-- --------------------------------------------------------

--
-- Table structure for table `family_data`
--

CREATE TABLE `family_data` (
  `family_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `father_name` varchar(255) NOT NULL,
  `father_occupation` varchar(255) DEFAULT NULL,
  `mother_name` text NOT NULL,
  `mother_occupation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `family_data`
--

INSERT INTO `family_data` (`family_id`, `user_id`, `father_name`, `father_occupation`, `mother_name`, `mother_occupation`) VALUES
(1, 5, 'Father', 'Father Occupation', 'Mother', 'Mother Occupation');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `form_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `form_type` varchar(255) NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`form_id`, `user_id`, `file_name`, `form_type`, `createdAt`) VALUES
(1, 5, '03032023-012105_2_BSIS3B_GRP4_ThesisAdviserInvitation_ACCEPT.pdf', 'applicationLetter', '2023-03-03 05:20:29'),
(2, 5, '03032023-012500_pdfjs-express-demo.pdf', 'journal', '2023-03-03 05:25:05'),
(3, 5, '03032023-013315_pdfjs-express-demo.pdf', 'journal', '2023-03-03 05:33:17'),
(4, 5, '03062023-115503_pdfjs-express-demo.pdf', 'waiver', '2023-03-03 06:41:50'),
(5, 5, '03032023-024225_pdfjs-express-demo.pdf', 'journal', '2023-03-03 06:42:27'),
(7, 5, '03062023-113724_pdfjs-express-demo.pdf', 'endorsement', '2023-03-06 03:37:24');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `notification` varchar(255) NOT NULL,
  `unread` tinyint(1) NOT NULL COMMENT '0 = false, 1 = true',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `user_id`, `admin_id`, `notification`, `unread`, `createdAt`) VALUES
(1, 5, 1, 'updated Waiver', 1, '2023-03-03 09:41:09'),
(2, 5, 1, 'updated Waiver', 1, '2023-03-03 09:41:09'),
(3, 5, 1, 'updated Waiver', 1, '2023-03-03 09:41:09'),
(4, 5, 1, 'submitted Waiver', 1, '2023-03-03 09:41:09'),
(5, 5, 1, 'updated Waiver', 1, '2023-03-03 09:41:09'),
(6, 5, 1, 'submitted Journal of Daily Activities', 1, '2023-03-03 09:41:09'),
(7, 5, 1, 'updated Waiver', 1, '2023-03-03 09:41:09'),
(8, 5, 22, 'updated Waiver', 1, '2023-03-03 07:58:24'),
(9, 5, 1, 'updated Waiver', 1, '2023-03-03 09:41:09'),
(10, 5, 22, 'updated Waiver', 1, '2023-03-03 08:55:35'),
(11, 5, 1, 'updated Waiver', 1, '2023-03-03 09:41:09'),
(12, 5, 22, 'updated Waiver', 1, '2023-03-03 09:15:28'),
(13, 5, 1, 'updated Waiver', 1, '2023-03-03 01:41:09'),
(14, 5, 22, 'updated Waiver', 1, '2023-03-03 09:16:31'),
(16, 5, 1, 'updated Waiver', 1, '2023-03-03 10:01:45'),
(17, 5, 22, 'updated Waiver', 1, '2023-03-06 03:36:25'),
(18, 5, 1, 'submitted Endorsement Letter', 1, '2023-03-06 03:49:54'),
(19, 5, 22, 'submitted Endorsement Letter', 1, '2023-03-06 03:38:36'),
(20, 5, 1, 'updated Waiver', 1, '2023-03-06 03:49:54'),
(21, 5, 22, 'updated Waiver', 1, '2023-03-06 03:39:36'),
(22, 5, 1, 'updated Waiver', 1, '2023-03-06 03:55:47');

-- --------------------------------------------------------

--
-- Table structure for table `office`
--

CREATE TABLE `office` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `office`
--

INSERT INTO `office` (`id`, `name`) VALUES
(1, 'Buenavista Town Hall'),
(2, 'Guimaras State Collage ICT OFFICE (Salvador Campus)'),
(3, 'Department of Agriculture Office'),
(5, 'Guimaras State College ICT OFFICE (Salvador Campus)'),
(6, 'Guimaras State College ICT OFFICE (Mosqueda Campus)'),
(7, 'Guimaras State College ICT OFFICE (Baterna Campus)');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE `setting` (
  `setting_id` int(11) NOT NULL,
  `hours` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `avatar` varchar(255) DEFAULT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `mname` varchar(100) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `city_address` varchar(255) DEFAULT NULL,
  `provincial_address` varchar(255) DEFAULT NULL,
  `date_of_birth` varchar(255) DEFAULT NULL,
  `place_of_birth` varchar(255) DEFAULT NULL,
  `civil_status` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `special_skills` varchar(255) DEFAULT NULL,
  `physical_disability` varchar(255) DEFAULT NULL,
  `mental_disability` varchar(255) DEFAULT NULL,
  `criminal_liability` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `section` varchar(32) DEFAULT NULL,
  `deployment_id` int(11) DEFAULT NULL,
  `role` enum('student','super-admin','admin','') NOT NULL,
  `isNew` tinyint(1) DEFAULT NULL,
  `office_account_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `avatar`, `fname`, `lname`, `mname`, `contact`, `city_address`, `provincial_address`, `date_of_birth`, `place_of_birth`, `civil_status`, `gender`, `height`, `weight`, `special_skills`, `physical_disability`, `mental_disability`, `criminal_liability`, `email`, `password`, `course_id`, `section`, `deployment_id`, `role`, `isNew`, `office_account_id`) VALUES
(1, '03062023-115722_messages-2.jpg', 'super1', 'admin', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin@email.com', '$argon2i$v=19$m=65536,t=4,p=1$eUwwdk5LYUxTcGgyMzE1eA$acMNjJdFkn5MOuqQrmAAC/Y3z0DIzwNFXbAe1t/Nhqs', NULL, NULL, NULL, 'super-admin', 0, NULL),
(5, '03062023-114545_messages-3.jpg', 'Test', 'Test', 'Test', '098765432', NULL, 'Address', '1997-05-03', 'Boac', 'Single', 'Male', '125', '72', 'Test, Test', 'Test', 'Test', NULL, 'test_student@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$MWlKTEw1b3Bnbld5YTFFTg$5v0lcXhwCrdncrAIYk7wKhCtnMYtv31AQOHZ3vNUHbQ', 1, 'A', 1, 'student', NULL, NULL),
(22, '03032023-040521_messages-3.jpg', 'Test', 'Awd', 'Awd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin@admin.com', '$argon2i$v=19$m=65536,t=4,p=1$ZElaWXk3Z0lQVmZpSVA5aQ$bXr4AicTXCIuN7wVSIr0ZWccxRDWr4YIgy4UDhhB3wQ', NULL, NULL, NULL, 'admin', 0, 2);

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
-- Indexes for table `educational`
--
ALTER TABLE `educational`
  ADD PRIMARY KEY (`educational_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `emergency_data`
--
ALTER TABLE `emergency_data`
  ADD PRIMARY KEY (`emergency_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `evaluation`
--
ALTER TABLE `evaluation`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `family_data`
--
ALTER TABLE `family_data`
  ADD PRIMARY KEY (`family_id`),
  ADD KEY `family_data_ibfk_1` (`user_id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`form_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `deployment_id` (`deployment_id`),
  ADD KEY `office_account_id` (`office_account_id`),
  ADD KEY `course_id` (`course_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `educational`
--
ALTER TABLE `educational`
  MODIFY `educational_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `emergency_data`
--
ALTER TABLE `emergency_data`
  MODIFY `emergency_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `evaluation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `family_data`
--
ALTER TABLE `family_data`
  MODIFY `family_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `form_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `office`
--
ALTER TABLE `office`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `setting`
--
ALTER TABLE `setting`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `educational`
--
ALTER TABLE `educational`
  ADD CONSTRAINT `educational_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `emergency_data`
--
ALTER TABLE `emergency_data`
  ADD CONSTRAINT `emergency_data_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `family_data`
--
ALTER TABLE `family_data`
  ADD CONSTRAINT `family_data_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `forms`
--
ALTER TABLE `forms`
  ADD CONSTRAINT `forms_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`office_account_id`) REFERENCES `office` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`deployment_id`) REFERENCES `office` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
