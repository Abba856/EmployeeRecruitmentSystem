-- phpMyAdmin SQL Dump
-- version 5.2.2-1.fc42
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 14, 2025 at 05:15 PM
-- Server version: 10.11.11-MariaDB
-- PHP Version: 8.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `recruitment`
--
CREATE DATABASE IF NOT EXISTS `recruitment` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `recruitment`;

-- --------------------------------------------------------

--
-- Table structure for table `academic`
--

CREATE TABLE `academic` (
  `userid` int(255) NOT NULL,
  `university` varchar(255) NOT NULL,
  `institute` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `degree` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `cpi` float NOT NULL,
  `semester` int(8) NOT NULL,
  `experience` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `academic`
--

INSERT INTO `academic` (`userid`, `university`, `institute`, `branch`, `degree`, `status`, `cpi`, `semester`, `experience`) VALUES
(5, 'GTU', 'Silver Oak', 'Informantion Technology', 'B.E/B.Tech', 'pursuing', 6.8, 5, 0),
(6, 'kkkkkkkkkkkk', 'kkkkkkkkkkkkkkkkkkk', 'Computer science', 'M.E/M.Tech', 'pursuing', 4.05, 6, 0),
(7, 'buk', 'kanopoly', 'Computer science', 'B.E/B.Tech', 'completed', 4.5, 0, 3),
(9, 'buk', 'kanopoly', 'kano', 'B.E/B.Tech', 'completed', 4, 4, 3),
(11, 'abu', 'ivei', 'Computer Science', 'B.E/B.Tech', 'completed', 7, 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `userid` int(255) NOT NULL,
  `post` varchar(255) NOT NULL,
  `resume` varchar(255) NOT NULL,
  `pemail` varchar(255) NOT NULL,
  `semail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`userid`, `post`, `resume`, `pemail`, `semail`, `password`) VALUES
(5, 'DataBase Administrator', 'software_testing.pdf', 'krupalshah1994@gmail.com', 'krupalshah5@yahoo.in', '10101010'),
(6, 'Search Engine Optimizer', 'soft.pdf', 'k@k.com', 'k@sa.com', '10101010'),
(7, 'Web Developer', 'Employee-Recruitment-System-Project-SRS.pdf', 'abdul.abdul@gmail.com', 'abdul.abdul@gmail.com', '12345678'),
(9, 'Web Developer', 'No resume uploaded', 'isa@gmail.com', 'isa@gmail.com', '$2y$12$xfytGCJVpVmwMuB9C7IhGekDRKU2rEIkgFGmeryMwmBGWWSwE0ldu');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminid` int(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminid`, `email`, `password`) VALUES
(3, 'ashish@yahoo.in', '123123123'),
(9, 'krupal@gmail.com', '10101010'),
(11, 'krupal1010@gmail.com', '1010101010');

-- --------------------------------------------------------

--
-- Table structure for table `exam_candidate_assoc`
--

CREATE TABLE `exam_candidate_assoc` (
  `assoc_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `exam_candidate_assoc`
--

INSERT INTO `exam_candidate_assoc` (`assoc_id`, `schedule_id`, `candidate_id`) VALUES
(1, 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `userid` int(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `feedback` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal`
--

CREATE TABLE `personal` (
  `userid` int(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `state` varchar(255) NOT NULL,
  `statespecify` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `cityspecify` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `personal`
--

INSERT INTO `personal` (`userid`, `firstname`, `middlename`, `lastname`, `gender`, `birthdate`, `state`, `statespecify`, `city`, `cityspecify`) VALUES
(5, 'krupal', 'harshadbhai', 'shah', 'male', '1994-01-26', 'Gujarat', '', 'Ahmedabad', ''),
(6, 'krupal', 'kkkkkkkkkkk', 'kkkkkkk', 'male', '2014-05-20', 'Chandigarh', '', 'Mumbai', ''),
(7, 'krupal', 'kkkkkkkkkkk', 'kkkkkkkkkk', 'male', '2014-05-13', 'Gujarat', '', 'Alleppey', ''),
(8, 'abdul', 'abdul', 'ahmad', 'male', '1999-02-03', 'Maharashtra', '', 'Bangalore', ''),
(9, 'ISA', 'ISA', 'ISA', 'male', '2000-02-03', 'Tamil Nadu', '', 'Ahmedabad', ''),
(10, 'a', 'a', 'a', 'male', '2000-02-03', 'Delhi', '', 'Alleppey', ''),
(11, 'ab', 'ab', 'ab', 'male', '2001-02-03', 'Goa', '', 'Chennai', '');

-- --------------------------------------------------------

--
-- Table structure for table `requirement`
--

CREATE TABLE `requirement` (
  `postname` varchar(255) NOT NULL,
  `vacancies` int(255) NOT NULL,
  `reqexperience` int(255) NOT NULL,
  `minsalary` int(255) NOT NULL,
  `maxsalary` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `requirement`
--

INSERT INTO `requirement` (`postname`, `vacancies`, `reqexperience`, `minsalary`, `maxsalary`) VALUES
('Web Developer', 20, 2, 26000, 28000),
('Mobile App Developer', 5, 3, 5000, 10000),
('DataBase Administrator', 2, 5, 21500, 24500),
('Search Engine Optimizer', 5, 4, 3000, 6000),
('Product Manager', 4, 7, 13000, 15000),
('HR Manager', 3, 0, 3000, 6000);

-- --------------------------------------------------------

--
-- Table structure for table `scheduled_exams`
--

CREATE TABLE `scheduled_exams` (
  `schedule_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `exam_datetime` datetime NOT NULL,
  `duration` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `scheduled_exams`
--

INSERT INTO `scheduled_exams` (`schedule_id`, `title`, `type`, `exam_datetime`, `duration`, `location`, `created_at`) VALUES
(1, 'aseR', 'technical', '2025-10-16 03:03:00', 35, 'DFFDGD', '2025-10-14 16:58:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic`
--
ALTER TABLE `academic`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `semail` (`semail`),
  ADD UNIQUE KEY `pemail` (`pemail`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `exam_candidate_assoc`
--
ALTER TABLE `exam_candidate_assoc`
  ADD PRIMARY KEY (`assoc_id`),
  ADD KEY `schedule_id` (`schedule_id`),
  ADD KEY `candidate_id` (`candidate_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `scheduled_exams`
--
ALTER TABLE `scheduled_exams`
  ADD PRIMARY KEY (`schedule_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic`
--
ALTER TABLE `academic`
  MODIFY `userid` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `userid` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminid` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `exam_candidate_assoc`
--
ALTER TABLE `exam_candidate_assoc`
  MODIFY `assoc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal`
--
ALTER TABLE `personal`
  MODIFY `userid` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `scheduled_exams`
--
ALTER TABLE `scheduled_exams`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic`
--
ALTER TABLE `academic`
  ADD CONSTRAINT `academic_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `personal` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `personal` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `exam_candidate_assoc`
--
ALTER TABLE `exam_candidate_assoc`
  ADD CONSTRAINT `exam_candidate_assoc_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `scheduled_exams` (`schedule_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_candidate_assoc_ibfk_2` FOREIGN KEY (`candidate_id`) REFERENCES `personal` (`userid`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `personal` (`userid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
