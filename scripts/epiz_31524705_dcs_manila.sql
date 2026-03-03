-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql201.epizy.com
-- Generation Time: Apr 20, 2022 at 02:28 AM
-- Server version: 10.3.27-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epiz_31524705_dcs_manila`
--

-- --------------------------------------------------------

--
-- Table structure for table `ac201_record`
--

CREATE TABLE `ac201_record` (
  `ac201_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `personnel_id` int(11) DEFAULT NULL,
  `school` varchar(100) DEFAULT NULL,
  `file_path` varchar(100) DEFAULT NULL,
  `released_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `suffix` varchar(50) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `contact_no` varchar(15) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `employment_date` date NOT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `birthdate`, `contact_no`, `position`, `employment_date`, `avatar_path`, `role_id`, `email`, `password`, `created_date`, `modified_date`) VALUES
(1, 'Super', '', 'Admin', '', '2022-04-15', '', 'Administrator', '2022-04-15', '../../assets/img/avatar.png', 1, 'admin@admin.com', 'admin', '2022-04-15 12:39:07', '2022-04-18 12:27:09'),
(2, 'Ferliene Marielle', 'S.', 'Tan', '', '1990-09-16', '09231148478', 'Administrative Aide VI', '2017-09-04', '../../assets/img/avatar.png', 6, 'ferliene.tan@deped.gov.ph', 'dcsrecords2022', '2022-04-16 11:55:48', '2022-04-17 11:05:52'),
(3, 'John Andrew Mari', 'M.', 'Gutierrez', '', '2022-04-16', '', 'Administrative Aide VI', '2021-10-01', '../../assets/img/avatar.png', 6, 'john.gutierrez@deped.gov.ph', 'dcsrecords2022', '2022-04-16 12:02:34', '2022-04-16 12:02:34'),
(4, 'Rolando', 'G.', 'Banhaw', '', '2022-04-16', '', 'Administrative Aide II', '2021-10-01', '../../assets/img/avatar.png', 5, 'rolando.banhaw@deped.gov.ph', 'dcsrecords2022', '2022-04-16 12:06:48', '2022-04-16 12:06:48'),
(5, 'Elsa', 'P.', 'Portugal', '', '2022-04-16', '', 'Administrative Officer III', '2021-10-01', '../../assets/img/avatar.png', 3, 'elsa.portugal@deped.gov.ph', 'dcsrecords2022', '2022-04-16 12:08:09', '2022-04-16 12:08:09'),
(6, 'Darna', 'C.', 'Evangelista', '', '1983-10-29', '', 'Administrative Officer IV', '2017-12-01', '../../assets/img/upload/DP.jpg', 2, 'jimroy.evangelista@deped.gov.ph', 'dcsrecords2022', '2022-04-16 12:10:30', '2022-04-18 23:22:32'),
(7, 'Maritess', 'O.', 'Gabriel', '', '2022-04-16', '', 'Administrative Assistant II', '2021-10-01', '../../assets/img/avatar.png', 2, 'maritess.gabriel@deped.gov.ph', 'dcsrecords2022', '2022-04-16 12:11:48', '2022-04-16 12:11:48'),
(8, 'Carmita', 'F.', 'Bongcaras', '', '2022-04-16', '', 'Administrative Officer III', '2021-10-01', '../../assets/img/avatar.png', 4, 'carmita.bongcaras@deped.gov.ph', 'dcsrecords2022', '2022-04-16 12:12:51', '2022-04-16 12:12:51'),
(9, 'Rosemarie', 'L.', 'Concepcion', '', '2022-04-16', '', 'Administrative Officer II', '2021-10-01', '../../assets/img/avatar.png', 4, 'rosemarie.concepcion@deped.gov.ph', 'dcsrecords2022', '2022-04-16 12:14:15', '2022-04-16 12:14:15'),
(10, 'Alma', 'A.', 'Makuha', '', '2022-04-16', '', 'Administrative Aide I', '2021-10-01', '../../assets/img/avatar.png', 4, 'alma.makuha@deped.gov.ph', 'dcsrecords2022', '2022-04-16 12:15:17', '2022-04-16 12:15:17'),
(11, 'Victor Michael', 'C.', 'Baso', '', '1982-05-18', '09774008227', 'Administrator', '2007-10-31', '../../assets/img/avatar.png', 1, 'victor.beso@deped.gov.ph', 'dcsrecords2022', '2022-04-18 12:25:03', '2022-04-20 04:10:02');

-- --------------------------------------------------------

--
-- Table structure for table `ai_record`
--

CREATE TABLE `ai_record` (
  `ai_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `personnel_id` int(11) DEFAULT NULL,
  `memorandum_no` varchar(20) DEFAULT NULL,
  `file_path` varchar(100) DEFAULT NULL,
  `released_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ai_record`
--

INSERT INTO `ai_record` (`ai_id`, `record_id`, `status_id`, `personnel_id`, `memorandum_no`, `file_path`, `released_date`, `remarks`, `created_date`, `modified_date`) VALUES
(1, 2, 1, NULL, '2022-001', NULL, NULL, NULL, '2022-04-20 04:13:11', '2022-04-20 04:13:11');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `name`, `created_date`) VALUES
(1, 'Elementary', '2022-04-15 11:34:48'),
(2, 'Highschool', '2022-04-15 11:34:48');

-- --------------------------------------------------------

--
-- Table structure for table `cav_record`
--

CREATE TABLE `cav_record` (
  `cav_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `personnel_id` int(11) DEFAULT NULL,
  `school` varchar(100) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `file_path` varchar(100) DEFAULT NULL,
  `released_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cav_record`
--

INSERT INTO `cav_record` (`cav_id`, `record_id`, `status_id`, `personnel_id`, `school`, `year`, `district`, `file_path`, `released_date`, `remarks`, `created_date`, `modified_date`) VALUES
(1, 4, 3, 1, NULL, NULL, NULL, NULL, NULL, '', '2022-04-18 13:49:47', '2022-04-18 14:12:51'),
(2, 5, 2, 1, NULL, NULL, NULL, NULL, NULL, '', '2022-04-18 13:50:55', '2022-04-18 14:13:03'),
(3, 6, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2022-04-18 14:19:47', '2022-04-18 14:19:47');

-- --------------------------------------------------------

--
-- Table structure for table `control_no`
--

CREATE TABLE `control_no` (
  `control_id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `counter` int(11) DEFAULT 0,
  `value` varchar(20) NOT NULL,
  `modified_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `control_no`
--

INSERT INTO `control_no` (`control_id`, `code`, `counter`, `value`, `modified_date`) VALUES
(1, 'NCR', 2, 'NCR-2022-04-0002', '2022-04-20 04:08:32');

-- --------------------------------------------------------

--
-- Table structure for table `memorandum_no`
--

CREATE TABLE `memorandum_no` (
  `memorandum_id` int(11) NOT NULL,
  `counter` int(11) DEFAULT 0,
  `value` varchar(20) DEFAULT NULL,
  `modified_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `memorandum_no`
--

INSERT INTO `memorandum_no` (`memorandum_id`, `counter`, `value`, `modified_date`) VALUES
(1, 1, '2022-001', '2022-04-20 04:13:11');

-- --------------------------------------------------------

--
-- Table structure for table `num_class`
--

CREATE TABLE `num_class` (
  `class_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `num_class`
--

INSERT INTO `num_class` (`class_id`, `name`, `created_date`) VALUES
(1, 'Communication', '2022-04-15 11:34:48'),
(2, 'Others', '2022-04-15 11:34:48');

-- --------------------------------------------------------

--
-- Table structure for table `num_record`
--

CREATE TABLE `num_record` (
  `num_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `personnel_id` int(11) DEFAULT NULL,
  `school` varchar(100) DEFAULT NULL,
  `classification_no` varchar(20) DEFAULT NULL,
  `file_path` varchar(100) DEFAULT NULL,
  `released_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `class_id` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `office`
--

CREATE TABLE `office` (
  `office_id` int(11) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `office`
--

INSERT INTO `office` (`office_id`, `code`, `name`, `created_date`) VALUES
(1, 'RMS', 'Records Management Services', '2022-04-15 11:34:48'),
(2, 'HRMS', 'Human Resources Management Services', '2022-04-15 11:34:48'),
(3, 'AS', 'Administrative Services', '2022-04-15 11:34:48'),
(4, 'SGOD', 'School Governance and Operations Division', '2022-04-15 11:34:48'),
(5, 'SDS', 'Schools Division Superintendent', '2022-04-15 11:34:48'),
(6, 'ASDS', 'Assistant Schools Division Superintendent', '2022-04-15 11:34:48');

-- --------------------------------------------------------

--
-- Table structure for table `queue_no`
--

CREATE TABLE `queue_no` (
  `queue_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `counter` int(11) DEFAULT 0,
  `value` varchar(25) DEFAULT NULL,
  `modified_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `queue_no`
--

INSERT INTO `queue_no` (`queue_id`, `category_id`, `counter`, `value`, `modified_date`) VALUES
(1, 1, 2, '2', '2022-04-20 04:08:32'),
(2, 2, 0, NULL, '2022-04-20 04:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `queuing`
--

CREATE TABLE `queuing` (
  `queuing_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `queue_no` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `queuing`
--

INSERT INTO `queuing` (`queuing_id`, `record_id`, `queue_no`, `created_date`) VALUES
(1, 4, 1, '2022-04-18 13:36:29'),
(2, 5, 2, '2022-04-18 13:50:34'),
(3, 6, 3, '2022-04-18 14:19:26'),
(4, 7, 4, '2022-04-18 14:24:07'),
(5, 8, 1, '2022-04-19 05:18:38'),
(6, 9, 2, '2022-04-19 05:30:55'),
(7, 10, 3, '2022-04-19 05:33:27'),
(8, 11, 4, '2022-04-19 05:39:19'),
(9, 12, 5, '2022-04-19 05:46:00'),
(10, 13, 6, '2022-04-20 01:47:35'),
(11, 14, 7, '2022-04-20 03:25:08'),
(12, 15, 1, '2022-04-20 03:54:22'),
(13, 1, 1, '2022-04-20 04:08:13'),
(14, 2, 2, '2022-04-20 04:08:32');

-- --------------------------------------------------------

--
-- Table structure for table `record`
--

CREATE TABLE `record` (
  `record_id` int(11) NOT NULL,
  `client_name` varchar(150) NOT NULL,
  `control_no` varchar(20) NOT NULL,
  `category_id` int(11) NOT NULL,
  `details` varchar(255) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `record`
--

INSERT INTO `record` (`record_id`, `client_name`, `control_no`, `category_id`, `details`, `created_date`, `modified_date`) VALUES
(1, 'Arfel Lester F. Bagiwan', 'NCR-2022-04-0001', 1, '', '2022-04-20 04:08:13', '2022-04-20 04:08:13'),
(2, 'Ding Evangelista', 'NCR-2022-04-0002', 1, '', '2022-04-20 04:08:32', '2022-04-20 04:08:32');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `name`, `created_date`) VALUES
(1, 'Administrator', '2022-04-15 11:34:47'),
(2, 'Receiving Routing and Mailing', '2022-04-15 11:34:47'),
(3, 'Administrative Issuance', '2022-04-15 11:34:47'),
(4, 'Appointment and Clearances / 201 Files', '2022-04-15 11:34:47'),
(5, 'Certification Authentication and Verification', '2022-04-15 11:34:47'),
(6, 'Numerical Files', '2022-04-15 11:34:47');

-- --------------------------------------------------------

--
-- Table structure for table `rrm_record`
--

CREATE TABLE `rrm_record` (
  `rrm_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `office_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `personnel_id` int(11) DEFAULT NULL,
  `documents` varchar(255) DEFAULT NULL,
  `released_date` datetime DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rrm_record`
--

INSERT INTO `rrm_record` (`rrm_id`, `record_id`, `status_id`, `office_id`, `section_id`, `personnel_id`, `documents`, `released_date`, `remarks`, `created_date`, `modified_date`) VALUES
(1, 1, 4, 5, NULL, 1, '', NULL, 'Successfully moved to SDS office', '2022-04-20 04:08:13', '2022-04-20 04:16:11'),
(2, 2, 4, 1, 2, 6, 'Division Memorandum 2022', NULL, 'Requested to be forwarded to AI', '2022-04-20 04:08:32', '2022-04-20 04:13:11');

-- --------------------------------------------------------

--
-- Table structure for table `rrm_record_tracking`
--

CREATE TABLE `rrm_record_tracking` (
  `tracking_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `office_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `personnel_id` int(11) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rrm_record_tracking`
--

INSERT INTO `rrm_record_tracking` (`tracking_id`, `record_id`, `status_id`, `office_id`, `section_id`, `personnel_id`, `remarks`, `created_date`) VALUES
(1, 1, 1, 1, 1, NULL, 'Request with Control No. NCR-2022-04-0001 has been created', '2022-04-20 04:08:13'),
(2, 2, 1, 1, 1, NULL, 'Request with Control No. NCR-2022-04-0002 has been created', '2022-04-20 04:08:32'),
(3, 2, 2, 1, 1, NULL, '', '2022-04-20 04:11:44'),
(4, 2, 3, 1, 1, NULL, '', '2022-04-20 04:12:26'),
(5, 2, 4, 1, 2, NULL, 'Requested to be forwarded to AI', '2022-04-20 04:13:11'),
(6, 1, 2, 1, 1, NULL, 'Request for using Tondo High School as a Vaccination site', '2022-04-20 04:15:24'),
(7, 1, 3, 1, 1, NULL, '', '2022-04-20 04:15:44'),
(8, 1, 4, 5, NULL, NULL, 'Successfully moved to SDS office', '2022-04-20 04:16:11');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `section_id` int(11) NOT NULL,
  `code` varchar(10) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`section_id`, `code`, `name`, `created_date`) VALUES
(1, 'RRM', 'Receiving Routing and Mailing', '2022-04-15 11:34:48'),
(2, 'AI', 'Administrative Issuance', '2022-04-15 11:34:48'),
(3, 'AC-201', 'Appointment and Clearances / 201 Files', '2022-04-15 11:34:48'),
(4, 'CAV', 'Certification Authentication and Verification', '2022-04-15 11:34:48'),
(5, 'NUM-COMM', 'Numerical - Communication', '2022-04-15 11:34:48'),
(6, 'NUM-OTHERS', 'Numerical - Others', '2022-04-15 11:34:48');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `name`, `created_date`) VALUES
(1, 'Open', '2022-04-15 11:34:48'),
(2, 'In Progress', '2022-04-15 11:34:48'),
(3, 'Received', '2022-04-15 11:34:48'),
(4, 'Forwarded', '2022-04-15 11:34:48'),
(5, 'Returned', '2022-04-15 11:34:48'),
(6, 'In Review', '2022-04-15 11:34:48'),
(7, 'For Release', '2022-04-15 11:34:48'),
(8, 'Released', '2022-04-15 11:34:48'),
(9, 'On Hold', '2022-04-15 11:34:48'),
(10, 'Cancelled', '2022-04-15 11:34:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ac201_record`
--
ALTER TABLE `ac201_record`
  ADD PRIMARY KEY (`ac201_id`),
  ADD KEY `record_id` (`record_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `ai_record`
--
ALTER TABLE `ai_record`
  ADD PRIMARY KEY (`ai_id`),
  ADD KEY `record_id` (`record_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `cav_record`
--
ALTER TABLE `cav_record`
  ADD PRIMARY KEY (`cav_id`),
  ADD KEY `record_id` (`record_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `control_no`
--
ALTER TABLE `control_no`
  ADD PRIMARY KEY (`control_id`);

--
-- Indexes for table `memorandum_no`
--
ALTER TABLE `memorandum_no`
  ADD PRIMARY KEY (`memorandum_id`);

--
-- Indexes for table `num_class`
--
ALTER TABLE `num_class`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `num_record`
--
ALTER TABLE `num_record`
  ADD PRIMARY KEY (`num_id`),
  ADD KEY `record_id` (`record_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `office`
--
ALTER TABLE `office`
  ADD PRIMARY KEY (`office_id`);

--
-- Indexes for table `queue_no`
--
ALTER TABLE `queue_no`
  ADD PRIMARY KEY (`queue_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `queuing`
--
ALTER TABLE `queuing`
  ADD PRIMARY KEY (`queuing_id`);

--
-- Indexes for table `record`
--
ALTER TABLE `record`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `rrm_record`
--
ALTER TABLE `rrm_record`
  ADD PRIMARY KEY (`rrm_id`),
  ADD KEY `record_id` (`record_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `rrm_record_tracking`
--
ALTER TABLE `rrm_record_tracking`
  ADD PRIMARY KEY (`tracking_id`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`section_id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ac201_record`
--
ALTER TABLE `ac201_record`
  MODIFY `ac201_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ai_record`
--
ALTER TABLE `ai_record`
  MODIFY `ai_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `cav_record`
--
ALTER TABLE `cav_record`
  MODIFY `cav_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `control_no`
--
ALTER TABLE `control_no`
  MODIFY `control_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `memorandum_no`
--
ALTER TABLE `memorandum_no`
  MODIFY `memorandum_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `num_class`
--
ALTER TABLE `num_class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `num_record`
--
ALTER TABLE `num_record`
  MODIFY `num_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `office`
--
ALTER TABLE `office`
  MODIFY `office_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `queue_no`
--
ALTER TABLE `queue_no`
  MODIFY `queue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `queuing`
--
ALTER TABLE `queuing`
  MODIFY `queuing_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `record`
--
ALTER TABLE `record`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rrm_record`
--
ALTER TABLE `rrm_record`
  MODIFY `rrm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rrm_record_tracking`
--
ALTER TABLE `rrm_record_tracking`
  MODIFY `tracking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
