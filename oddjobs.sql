-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2024 at 06:23 PM
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
-- Database: `oddjobs`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `job_type` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `work_mode` varchar(255) DEFAULT NULL,
  `posted_by` int(11) NOT NULL,
  `date_posted` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT 0,
  `pending` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `title`, `description`, `category`, `job_type`, `location`, `work_mode`, `posted_by`, `date_posted`, `status`, `pending`) VALUES
(4, 'Test 1', 'Test 1 description', 'all', 'all', 'USA', 'onsite', 5, '2024-06-02 18:30:00', 0, 0),
(6, 'Test 3', 'Test 3 description', 'all', 'all', 'Mumbai', 'onsite', 5, '2024-06-03 18:30:00', 0, 0),
(7, 'Test 4', 'Test 4 description', 'all', 'all', 'Dharamshala', 'onsite', 5, '2024-06-02 18:30:00', 0, 0),
(9, 'Test 6', 'Test 6 description', 'all', 'all', 'Ludhiana', 'hybrid', 5, '2024-06-02 18:30:00', 0, 0),
(10, 'Test 7', 'Test 7 description', 'all', 'all', 'Chandigarh', 'onsite', 5, '2024-06-04 18:30:00', 0, 0),
(11, 'Test 8', 'Test 8 description', 'all', 'all', 'Russia', 'onsite', 1, '2024-06-05 18:30:00', 0, 0),
(12, 'Test 9', 'Test 9 description', 'animation', 'part-time', 'Kangra', 'onsite', 1, '2024-06-05 18:30:00', 0, 0),
(13, 'Test 10', 'Test 10 description', 'animation', 'full-time', 'Kangra', 'hybrid', 5, '2024-06-05 18:30:00', 0, 0),
(14, 'Test 11', 'Test 11 description', 'academic writing', 'full-time', 'Kullu', 'onsite', 5, '2024-06-06 05:52:54', 0, 0),
(17, 'Test 12', 'Test 12 description', 'all', 'all', 'Canada', 'onsite', 5, '2024-06-06 10:33:31', 0, 0),
(21, 'Tester', 'Description of tester goes here', 'academic writing', 'contract', 'Kullu', 'hybrid', 27, '2024-06-11 06:55:35', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `application_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text NOT NULL,
  `bid_amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `days` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`application_id`, `job_id`, `user_id`, `application_date`, `description`, `bid_amount`, `currency`, `days`) VALUES
(15, 13, 25, '2024-06-09 14:33:35', 'Applying for test 10', 4000.00, 'INR', 1),
(16, 12, 5, '2024-06-09 15:03:22', 'Applying for test 9', 400.00, 'USD', 3),
(17, 17, 27, '2024-06-11 06:58:09', 'Desc', 400.00, 'INR', 2),
(18, 10, 1, '2024-07-01 10:36:33', 'Test Dheeraj', 200.00, 'INR', 2);

-- --------------------------------------------------------

--
-- Table structure for table `job_images`
--

CREATE TABLE `job_images` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `job_images`
--

INSERT INTO `job_images` (`id`, `job_id`, `image_path`) VALUES
(5, 9, 'uploads/image_666082484d18d.JPG'),
(6, 9, 'uploads/image_666082484e2b5.JPG'),
(7, 9, 'uploads/image_666082484eb9e.JPG'),
(8, 9, 'uploads/image_666082484f5af.JPG'),
(9, 13, 'uploads/image_66617c4221c44.jpeg'),
(10, 13, 'uploads/image_66617c4222f66.png'),
(11, 13, 'uploads/image_66617c4223078.png'),
(12, 14, 'uploads/image_66617fee364c2.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `profile_image` varchar(255) DEFAULT 'default_profile.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `token`, `verified`, `profile_image`) VALUES
(1, 'dheeraj', 'dheeraj@gmail.com', '$2y$10$SVhfZ0uG9PR0rR4JIuZzeunmbG9eGbkwDZtxrnFrx5J3h29aqe.gK', '', 1, 'default_profile.png'),
(3, 'kartik', 'kartikdhiman123@gmail.com', '$2y$10$.70NXKYYzxPLve9o5raoJu0HB8Ej8nvKJDJ4lb7crmBOiunz.gysa', '', 0, 'default_profile.png'),
(4, 'Kartik', 'kartik123@gmail.com', '$2y$10$jeqtI3jPY7x.qY3WaEOgOOIonBz36UoTcWZG9Fbq5ZeGzeBedBvbW', '', 0, 'default_profile.png'),
(5, 'Kartik Dhiman', 'kartikdhiman0711@gmail.com', '$2y$10$0pRE9BUAeCPJyBqOatxSHussP/ZuxR/nTQvCRhME5CmH0CjJcXjw6', 'ff468ee1c1aabb0f263906b77e843125', 1, 'WhatsApp Image 2024-03-05 at 11.49.56_b3b0f41a.jpg'),
(25, 'Kartik', 'kartikdhiman@gmail.com', '$2y$10$/nV9Pq2uS4j3JM/sa9CW7Ofu7gffD/MtZzIn3SKd0/7GFJLXhKAcS', '62080079cf9527cba29ac94915f1528bad6b0d3e9ef0864e7de81f865122a96821b02a7456ee9c3628c8fc3639baa2be4321', 1, 'default_profile.png'),
(27, 'Kartik Dhiman', 'kartikdhiman2611@gmail.com', '$2y$10$lcbEDL7ovhGen4H8a4HrXOqUXsaxeuijtS/Z0Y6HWajJazqScytpG', '', 1, 'default_profile.png'),
(28, 'Kartik Dhiman', 'kartikdhiman2903@gmail.com', '$2y$10$NPR1pxb4slXABId93zCO6.dIhTVD.LDQD.nxmiObnN2b0pcP6h0xS', '', 1, 'default_profile.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `posted_by` (`posted_by`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `job_images`
--
ALTER TABLE `job_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`);

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
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `job_images`
--
ALTER TABLE `job_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD CONSTRAINT `job_applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_applications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_images`
--
ALTER TABLE `job_images`
  ADD CONSTRAINT `job_images_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
