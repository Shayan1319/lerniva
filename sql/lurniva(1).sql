-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2025 at 12:19 PM
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
-- Database: `lurniva`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `entity_type` varchar(100) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `class_fee_types`
--

CREATE TABLE `class_fee_types` (
  `id` int(11) NOT NULL,
  `fee_structure_id` int(11) DEFAULT NULL,
  `school_id` int(11) NOT NULL,
  `class_grade` varchar(50) NOT NULL,
  `fee_type_id` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `class_fee_types`
--

INSERT INTO `class_fee_types` (`id`, `fee_structure_id`, `school_id`, `class_grade`, `fee_type_id`, `rate`) VALUES
(10, 4, 1, '5', 4, 100.00),
(11, 4, 1, '5', 1, 1000.00),
(12, 4, 1, '5', 2, 500.00);

-- --------------------------------------------------------

--
-- Table structure for table `class_timetable_details`
--

CREATE TABLE `class_timetable_details` (
  `id` int(11) NOT NULL,
  `timing_meta_id` int(11) NOT NULL,
  `period_number` int(11) NOT NULL,
  `period_name` varchar(50) DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `teacher_id` int(11) DEFAULT NULL,
  `is_break` tinyint(1) DEFAULT 0,
  `period_type` enum('Normal','Lab','Break','Sports','Library') DEFAULT 'Normal',
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `class_timetable_details`
--

INSERT INTO `class_timetable_details` (`id`, `timing_meta_id`, `period_number`, `period_name`, `start_time`, `end_time`, `created_at`, `teacher_id`, `is_break`, `period_type`, `created_by`) VALUES
(1, 5, 1, 'english', '08:00:00', '09:00:00', '0000-00-00 00:00:00', 1, 0, 'Normal', 1),
(2, 5, 2, 'math', '09:00:00', '10:00:00', '0000-00-00 00:00:00', 1, 0, 'Normal', 1);

-- --------------------------------------------------------

--
-- Table structure for table `class_timetable_meta`
--

CREATE TABLE `class_timetable_meta` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `timing_table_id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `section` varchar(10) NOT NULL,
  `total_periods` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_finalized` tinyint(1) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `class_timetable_meta`
--

INSERT INTO `class_timetable_meta` (`id`, `school_id`, `timing_table_id`, `class_name`, `section`, `total_periods`, `created_at`, `is_finalized`, `created_by`) VALUES
(5, 1, 1, '5', 'D', 2, '2025-07-01 07:26:29', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `class_timetable_weekdays`
--

CREATE TABLE `class_timetable_weekdays` (
  `id` int(11) NOT NULL,
  `timetable_id` int(11) NOT NULL,
  `weekday` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
  `assembly_time` time NOT NULL,
  `leave_time` time NOT NULL,
  `total_periods` int(11) NOT NULL,
  `is_half_day` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `class_timetable_weekdays`
--

INSERT INTO `class_timetable_weekdays` (`id`, `timetable_id`, `weekday`, `assembly_time`, `leave_time`, `total_periods`, `is_half_day`, `created_at`) VALUES
(1, 5, 'Friday', '08:00:00', '09:00:00', 1, 1, '2025-07-01 07:26:29');

-- --------------------------------------------------------

--
-- Table structure for table `diary_entries`
--

CREATE TABLE `diary_entries` (
  `id` int(11) NOT NULL,
  `school_id` varchar(255) NOT NULL,
  `class_meta_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `topic` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `deadline` date NOT NULL,
  `parent_approval_required` enum('yes','no') DEFAULT 'no',
  `student_option` enum('all','specific') DEFAULT 'all',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `diary_entries`
--

INSERT INTO `diary_entries` (`id`, `school_id`, `class_meta_id`, `subject`, `teacher_id`, `topic`, `description`, `attachment`, `deadline`, `parent_approval_required`, `student_option`, `created_at`, `updated_at`) VALUES
(1, '1', 5, 'english', 1, 'fdssfsdf', 'sfsfsadfsadfsda', '', '2025-08-15', 'no', 'all', '2025-08-13 22:47:18', '2025-08-13 23:12:55');

-- --------------------------------------------------------

--
-- Table structure for table `diary_students`
--

CREATE TABLE `diary_students` (
  `id` int(11) NOT NULL,
  `diary_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `digital_notices`
--

CREATE TABLE `digital_notices` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `notice_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `issued_by` varchar(255) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `notice_type` varchar(100) DEFAULT NULL,
  `audience` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `digital_notices`
--

INSERT INTO `digital_notices` (`id`, `school_id`, `title`, `notice_date`, `expiry_date`, `issued_by`, `purpose`, `notice_type`, `audience`, `file_path`, `created_at`) VALUES
(3, 1, 'titel', '2025-08-03', '2025-08-13', 'shayan', 'sdfsdfasdfsadf', 'Announcement', 'Everyone', '1754219131.png', '2025-08-03 11:05:31'),
(4, 1, 'titel', '2025-08-12', '2025-08-22', 'shayan', 'jdfkasfa', 'Exam', 'Everyone', 'uploads/notices/1754897582.png', '2025-08-11 07:33:02');

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `campus_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `cnic` varchar(25) NOT NULL,
  `qualification` varchar(100) NOT NULL,
  `subjects` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `joining_date` date NOT NULL,
  `employment_type` enum('Full-time','Part-time','Contractual') NOT NULL,
  `schedule_preference` enum('Morning','Evening') NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `rating` tinyint(3) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `campus_id`, `full_name`, `cnic`, `qualification`, `subjects`, `email`, `password`, `phone`, `address`, `joining_date`, `employment_type`, `schedule_preference`, `photo`, `created_at`, `status`, `rating`) VALUES
(1, 1, 'Abu bakar', '324234234234234234', 'MSC', 'CSIT', 'abubakar1@gmail.com', '$2y$10$7bJ6Os/V40HNR6tCOXOlCef9IY5NdkEaXFLwkELFLKUdHmwl2rivq', '93234324232', '', '2024-05-10', 'Full-time', 'Morning', '1749712148_Screenshot 2025-04-30 064014.png', '2025-06-12 07:09:09', 'pending', NULL),
(2, 1, 'maddad khan', '42343242342', 'MSC', 'CSIT', 'maddad@email.com', '$2y$10$QqVScI5Tm2hgg/kO8jC2iOm/s7YC8WgUESI8nnDyWZ/ElUOECLg.G', '645647657765856', 'jehangira', '2022-06-25', 'Full-time', 'Morning', NULL, '2025-07-24 13:29:26', 'pending', NULL),
(3, 1, 'Shayan', '312423543543534', 'MSC', 'CSIT', 'shayans1215225@gmai.com', '$2y$10$zQtjoebTNJ/fFSEi3Nmqd..80jIevoef2.O8NahHK00yFkJWmFhiC', '+92 3491916166', 'jehangira', '2025-06-01', 'Full-time', 'Morning', '1754663428_Screenshot 2024-10-27 052139.png', '2025-08-08 14:30:29', 'pending', NULL),
(4, 1, 'Shayan', '312423543543534', 'MSC', 'CSIT', 'shayans1215225@gmail.com', '$2y$10$FhdjOTONPHdSzYMQdkM4CeUSDtcSUwxhA0aG51G5U8/G.jg9/0SCS', '+92 3491916166', 'jehangira', '2025-06-01', 'Full-time', 'Morning', '1754663895_Screenshot 2024-10-27 052139.png', '2025-08-08 14:38:15', '1', NULL),
(5, 1, 'Shayan', '312423543543534', 'MSC', 'CSIT', 'shayan34343s1215225@gmail.com', '$2y$10$D1p.B7U170zH.IMJptpJjeyzWblBDT8BLg3oOUKZQ/ZSGzRJ6Hcw6', '+92 3491916166', 'jehangira', '2025-06-01', 'Full-time', 'Morning', '1754664184_Screenshot 2024-10-27 052139.png', '2025-08-08 14:43:05', 'pending', NULL),
(6, 1, 'name', '324234234234234234', 'MSC', 'CSIT', 'teacher@gmail.com', '$2y$10$LwiNpkDRTtrohouZTXuTv.MbUyTL/37tGFoduiuV8qDL39dTTWEbi', '645647657765856', 'jehangira', '2025-07-01', 'Full-time', 'Morning', '1754664366_Screenshot (4).png', '2025-08-08 14:46:06', 'pending', NULL),
(7, 1, 'name', '324234234234234234', 'MSC', 'CSIT', 'teacher@gmail.com', '$2y$10$jREIaGyeH2EnNdY0.EakZO0cYt/dkZwEGLHeh3jwNYHSHt1lSJueK', '645647657765856', 'jehangira', '2025-07-01', 'Full-time', 'Morning', '1754664546_Screenshot (2).png', '2025-08-08 14:49:07', 'pending', NULL),
(8, 1, 'name', '324234234234234234', 'MSC', 'CSIT', 'teacher@gmail.com', '$2y$10$8PJvDu.rHZBnEBuJ2ArUIONs36M.4c1wfmeMYIYE93sseAGHd.ITW', '645647657765856', 'jehangira', '2025-07-01', 'Full-time', 'Morning', '1754664555_Screenshot (2).png', '2025-08-08 14:49:15', 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faculty_attendance`
--

CREATE TABLE `faculty_attendance` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('Present','Absent','Leave') NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `faculty_attendance`
--

INSERT INTO `faculty_attendance` (`id`, `faculty_id`, `school_id`, `attendance_date`, `status`, `remarks`, `created_at`) VALUES
(1, 1, 1, '2025-07-28', 'Present', NULL, '2025-07-28 07:12:27'),
(2, 2, 1, '2025-07-28', 'Absent', NULL, '2025-07-28 07:12:27');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_leaves`
--

CREATE TABLE `faculty_leaves` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `leave_type` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int(11) GENERATED ALWAYS AS (to_days(`end_date`) - to_days(`start_date`) + 1) STORED,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `faculty_leaves`
--

INSERT INTO `faculty_leaves` (`id`, `school_id`, `faculty_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Sick', '2025-08-10', '2025-08-12', 'Fever and rest', 'Approved', NULL, NULL, '2025-08-01 16:38:12', '2025-08-13 10:46:21'),
(2, 1, 1, 'asfsafasdf', '2025-08-13', '2025-08-17', 'fgdfreveve', 'Pending', NULL, NULL, '2025-08-13 11:01:35', '2025-08-13 11:14:26'),
(3, 1, 1, 'csdfaf', '2025-08-20', '2025-08-27', 'dfgdfsv', 'Pending', NULL, NULL, '2025-08-13 11:02:27', '2025-08-13 11:14:16');

-- --------------------------------------------------------

--
-- Table structure for table `fee_periods`
--

CREATE TABLE `fee_periods` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `period_name` varchar(100) NOT NULL,
  `period_type` enum('monthly','quarterly','term','custom') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `fee_periods`
--

INSERT INTO `fee_periods` (`id`, `school_id`, `period_name`, `period_type`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 1, 'Jul,2025', 'monthly', '2025-07-01', '2025-07-31', 1, '2025-07-29 19:08:10'),
(2, 1, 'jun 2025', 'monthly', '2025-06-01', '2025-06-30', 1, '2025-08-05 01:42:50'),
(3, 1, 'Auf 2025', 'monthly', '2025-08-01', '2025-08-31', 1, '2025-08-05 20:42:11'),
(4, 1, 'jan 2025', 'monthly', '2025-01-01', '2025-01-30', 1, '2025-08-11 07:44:59');

-- --------------------------------------------------------

--
-- Table structure for table `fee_slips`
--

CREATE TABLE `fee_slips` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `slip_number` varchar(50) NOT NULL,
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('unpaid','paid','partially_paid','cancelled') DEFAULT 'unpaid',
  `due_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_slip_details`
--

CREATE TABLE `fee_slip_details` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `fee_period_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `scholarship_amount` decimal(10,2) DEFAULT 0.00,
  `net_payable` decimal(10,2) NOT NULL,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `payment_status` enum('unpaid','partial','paid') DEFAULT 'unpaid',
  `payment_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `fee_slip_details`
--

INSERT INTO `fee_slip_details` (`id`, `school_id`, `student_id`, `fee_period_id`, `total_amount`, `scholarship_amount`, `net_payable`, `amount_paid`, `payment_status`, `payment_date`, `created_at`) VALUES
(1, 1, 2, 1, 2100.00, 0.00, 2100.00, 2100.00, 'paid', '2025-08-05', '2025-08-05 20:35:17'),
(2, 1, 1, 2, 2200.00, 440.00, 1760.00, 1760.00, 'paid', '2025-08-11', '2025-08-11 07:43:33');

-- --------------------------------------------------------

--
-- Table structure for table `fee_structures`
--

CREATE TABLE `fee_structures` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `class_grade` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `frequency` enum('monthly','yearly','one_time') DEFAULT 'monthly',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `fee_structures`
--

INSERT INTO `fee_structures` (`id`, `school_id`, `class_grade`, `amount`, `frequency`, `status`, `created_at`) VALUES
(4, 1, '5', 1600.00, 'monthly', 'active', '2025-08-11 07:46:15');

-- --------------------------------------------------------

--
-- Table structure for table `fee_types`
--

CREATE TABLE `fee_types` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `fee_name` varchar(100) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `fee_types`
--

INSERT INTO `fee_types` (`id`, `school_id`, `fee_name`, `status`, `created_at`) VALUES
(1, 1, 'Tuition fee', 'active', '2025-07-14 15:14:41'),
(2, 1, 'Stationary', 'active', '2025-07-14 15:24:48'),
(3, 1, 'Bus Fee', 'active', '2025-07-23 21:56:23'),
(4, 1, 'lab', 'active', '2025-07-27 11:46:00'),
(5, 1, 'App charges', 'active', '2025-07-27 11:55:50'),
(6, 1, 'Fine', 'active', '2025-07-28 02:23:35'),
(7, 1, 'Sport', 'active', '2025-07-28 04:03:28'),
(10, 1, 'data', 'active', '2025-08-03 14:10:40');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_announcements`
--

CREATE TABLE `meeting_announcements` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `meeting_agenda` text DEFAULT NULL,
  `meeting_date` date NOT NULL,
  `meeting_time` time NOT NULL,
  `meeting_person` enum('admin','teacher','parent') NOT NULL,
  `person_id_one` int(11) NOT NULL,
  `meeting_person2` enum('admin','teacher','parent') NOT NULL,
  `person_id_two` int(11) NOT NULL,
  `status` enum('scheduled','cancelled','completed') DEFAULT 'scheduled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `meeting_announcements`
--

INSERT INTO `meeting_announcements` (`id`, `school_id`, `title`, `meeting_agenda`, `meeting_date`, `meeting_time`, `meeting_person`, `person_id_one`, `meeting_person2`, `person_id_two`, `status`, `created_at`) VALUES
(1, 1, 'abc', 'kjdjfasdlkj', '2025-08-12', '08:00:00', 'teacher', 3, 'parent', 1, '', '2025-08-11 07:31:40');

-- --------------------------------------------------------

--
-- Table structure for table `meeting_requests`
--

CREATE TABLE `meeting_requests` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `requested_by` enum('admin','teacher','parent') NOT NULL,
  `requester_id` int(11) NOT NULL,
  `with_meeting` enum('admin','teacher','parent') NOT NULL,
  `id_meeter` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `agenda` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `meeting_requests`
--

INSERT INTO `meeting_requests` (`id`, `school_id`, `requested_by`, `requester_id`, `with_meeting`, `id_meeter`, `title`, `agenda`, `status`, `created_at`) VALUES
(1, 1, 'teacher', 1, 'parent', 1, 'Parent Meeting', 'Discuss progress', 'pending', '2025-07-25 21:59:01'),
(2, 1, 'teacher', 1, 'parent', 1, 'Parent Meeting', 'Discuss progress abctres', 'rejected', '2025-08-14 09:38:01');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `sender_designation` enum('admin','teacher','student') NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_designation` enum('admin','teacher','student') NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `file_attachment` varchar(255) DEFAULT NULL,
  `voice_note` varchar(255) DEFAULT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `status` enum('unread','read') DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `school_id`, `sender_designation`, `sender_id`, `receiver_designation`, `receiver_id`, `message`, `file_attachment`, `voice_note`, `sent_at`, `status`) VALUES
(1, 1, 'teacher', 1, 'admin', 1, 'text', NULL, NULL, '2025-08-09 12:32:57', 'read'),
(2, 1, 'admin', 1, 'teacher', 1, 'hello', NULL, NULL, '2025-08-09 21:43:34', 'read'),
(3, 1, 'admin', 1, 'teacher', 1, 'jelo', NULL, NULL, '2025-08-09 21:46:46', 'read'),
(4, 1, 'admin', 1, 'teacher', 1, 'jjjj', NULL, NULL, '2025-08-09 22:09:48', 'read'),
(5, 1, 'admin', 1, 'teacher', 1, NULL, NULL, 'voice_6897ab165c69d1.85660014.webm', '2025-08-09 22:09:58', 'read'),
(6, 1, 'admin', 1, 'teacher', 1, NULL, NULL, 'voice_6897abe323d590.66559294.webm', '2025-08-09 22:13:23', 'read'),
(7, 1, 'admin', 1, 'teacher', 1, NULL, NULL, 'voice_6897ac35880a65.81355649.webm', '2025-08-09 22:14:45', 'read'),
(8, 1, 'admin', 1, 'teacher', 1, NULL, NULL, 'voice_6897ae33ef2496.64651100.webm', '2025-08-09 22:23:15', 'read'),
(9, 1, 'admin', 1, 'teacher', 1, 'hello', NULL, NULL, '2025-08-09 22:23:23', 'read'),
(10, 1, 'admin', 1, 'teacher', 1, 'Screenshot 2024-10-21 083351.png', 'file_6897aeffd80ed3.68936311.png', NULL, '2025-08-09 22:26:39', 'read'),
(11, 1, 'admin', 1, 'teacher', 1, 'hello', NULL, NULL, '2025-08-11 09:29:29', 'read'),
(12, 1, 'admin', 1, 'teacher', 1, NULL, NULL, 'voice_68999be035b663.39944988.webm', '2025-08-11 09:29:36', 'read'),
(13, 1, 'admin', 1, 'teacher', 1, 'Screenshot 2024-10-21 182550.png', 'file_68999befdac685.97543513.png', NULL, '2025-08-11 09:29:51', 'read'),
(14, 1, 'teacher', 1, 'admin', 1, NULL, NULL, 'voice_689b96fb0ef367.92123528.webm', '2025-08-12 21:33:15', 'unread'),
(15, 1, 'teacher', 1, 'admin', 1, NULL, NULL, 'voice_689b970ec2e814.11168405.webm', '2025-08-12 21:33:34', 'unread'),
(16, 1, 'teacher', 1, 'admin', 1, NULL, NULL, 'voice_689b99d1d5bf57.98826867.webm', '2025-08-12 21:45:21', 'unread'),
(17, 1, 'teacher', 1, 'admin', 1, NULL, NULL, 'voice_689b9a0e581161.44703484.webm', '2025-08-12 21:46:22', 'unread'),
(18, 1, 'teacher', 1, 'admin', 1, NULL, NULL, 'voice_689b9a1ff00822.38506606.webm', '2025-08-12 21:46:39', 'unread'),
(19, 1, 'teacher', 1, 'admin', 1, NULL, NULL, 'voice_689b9a5d53e846.70715864.webm', '2025-08-12 21:47:41', 'unread'),
(20, 1, 'teacher', 1, 'admin', 1, NULL, NULL, 'voice_689b9aa79cfac3.18260913.webm', '2025-08-12 21:48:55', 'unread'),
(21, 1, 'teacher', 1, 'admin', 1, NULL, NULL, 'voice_689b9ab7711df7.20710501.webm', '2025-08-12 21:49:11', 'unread'),
(22, 1, 'teacher', 1, 'admin', 1, 'send', NULL, NULL, '2025-08-12 22:14:54', 'unread'),
(23, 1, 'teacher', 1, 'admin', 1, NULL, NULL, 'voice_689ba0c35bad34.98787034.webm', '2025-08-12 22:14:59', 'unread'),
(24, 1, 'teacher', 1, 'admin', 1, 'Screenshot 2024-10-21 075725.png', 'file_689ba0caabd2f4.19972288.png', NULL, '2025-08-12 22:15:06', 'unread'),
(25, 1, 'teacher', 1, 'admin', 1, NULL, NULL, 'voice_689ba1155d8a95.98843530.webm', '2025-08-12 22:16:21', 'unread');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `slip_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `method` enum('cash','card','online','bank_transfer') DEFAULT 'cash',
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `scholarships`
--

CREATE TABLE `scholarships` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `type` enum('percentage','fixed') DEFAULT 'fixed',
  `amount` decimal(10,2) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` enum('approved','pending','rejected') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `scholarships`
--

INSERT INTO `scholarships` (`id`, `school_id`, `student_id`, `type`, `amount`, `reason`, `status`, `approved_by`, `created_at`) VALUES
(1, 1, 1, 'percentage', 20.00, 'resson', 'approved', NULL, '2025-07-28 11:05:46'),
(2, 1, 2, 'fixed', 339.00, 'it usfgfg', 'pending', NULL, '2025-08-07 07:04:55'),
(3, 1, 2, 'fixed', 400.00, 'kdflkjflk', 'pending', NULL, '2025-08-11 07:49:13');

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `school_type` enum('Public','Private','Charter') DEFAULT NULL,
  `registration_number` varchar(100) DEFAULT NULL,
  `affiliation_board` varchar(100) DEFAULT NULL,
  `school_email` varchar(150) DEFAULT NULL,
  `school_phone` varchar(20) DEFAULT NULL,
  `school_website` varchar(255) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `admin_contact_person` varchar(255) DEFAULT NULL,
  `admin_email` varchar(150) DEFAULT NULL,
  `admin_phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `school_name`, `school_type`, `registration_number`, `affiliation_board`, `school_email`, `school_phone`, `school_website`, `country`, `state`, `city`, `address`, `logo`, `admin_contact_person`, `admin_email`, `admin_phone`, `password`, `created_at`) VALUES
(1, 'Iqra school', 'Private', '1000292', 'bise mardan', 'addmin1215225@gamer.com', '092321774183', 'http://www.iqra.com', 'Pakistan', 'KPK', 'Jehangira', 'Jehangira nowshara KPK pakistan', 'logo_1_1754865238.jpg', '03092984222', 'admin12@gmail.com', '03092984222', '$2y$10$7bJ6Os/V40HNR6tCOXOlCef9IY5NdkEaXFLwkELFLKUdHmwl2rivq', '2025-06-10 09:44:52'),
(2, 'east', 'Private', '283920', 'mardan', 'school12@gamil.com', '092321774183', 'http://www.iqra.com', 'Pakistan', 'KPK', 'Jehangira', 'address', '', '03883889', 'school12@gmail.com', '0183029202', '$2y$10$2We.qsfYtkykGIEiDY33Le2FKf0RQc5Eg9CCtDqr/5h485BH.6aJG', '2025-06-26 15:29:05'),
(3, 'dodo', 'Private', '9283929', 'mardan', 'school2@gamil.com', '9282382932', 'http://www.iqra.com', 'Pakistan', 'KPK', 'Jehangira', 'address', 'logo_685d6b04741dc5.64348530.jpg', '308432948324823', 'school2@gmail.com', '03403940343', '$2y$10$iVOjtw8Yf5hH6uaFKWcGZeVLslmmzWXtRwK7edgIELSi0J0sGXGCm', '2025-06-26 15:45:08');

-- --------------------------------------------------------

--
-- Table structure for table `school_settings`
--

CREATE TABLE `school_settings` (
  `id` int(11) NOT NULL,
  `person` enum('admin','facility','student') NOT NULL,
  `person_id` int(11) NOT NULL,
  `layout` tinyint(1) NOT NULL COMMENT '1=Light, 2=Dark',
  `sidebar_color` tinyint(1) NOT NULL COMMENT '1=Light Sidebar, 2=Dark Sidebar',
  `color_theme` varchar(50) DEFAULT NULL,
  `mini_sidebar` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Off, 1=On',
  `sticky_header` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Off, 1=On',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `school_settings`
--

INSERT INTO `school_settings` (`id`, `person`, `person_id`, `layout`, `sidebar_color`, `color_theme`, `mini_sidebar`, `sticky_header`, `created_at`, `updated_at`) VALUES
(1, 'admin', 1, 1, 1, 'green', 0, 0, '2025-08-11 12:00:07', '2025-08-13 05:18:50'),
(2, 'facility', 1, 1, 1, 'purple', 0, 0, '2025-08-12 12:08:36', '2025-08-13 09:44:20');

-- --------------------------------------------------------

--
-- Table structure for table `school_tasks`
--

CREATE TABLE `school_tasks` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `task_title` varchar(255) NOT NULL,
  `task_description` text NOT NULL,
  `due_date` date NOT NULL,
  `task_completed_percent` decimal(5,2) DEFAULT 0.00,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `school_tasks`
--

INSERT INTO `school_tasks` (`id`, `school_id`, `task_title`, `task_description`, `due_date`, `task_completed_percent`, `created_by`, `created_at`) VALUES
(4, 1, 'task', 'Description', '2025-08-31', 100.00, 1, '2025-08-10 18:09:36'),
(5, 1, 'ksdfjk', 'ljsfkjs', '2025-08-29', 41.00, 1, '2025-08-11 07:36:36'),
(6, 1, 'event', 'dagat', '2025-08-31', 0.00, 1, '2025-08-11 07:38:56');

-- --------------------------------------------------------

--
-- Table structure for table `school_task_assignees`
--

CREATE TABLE `school_task_assignees` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `assigned_to_type` enum('teacher','student') NOT NULL,
  `assigned_to_id` int(11) NOT NULL,
  `status` enum('Active','Not Active') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `school_task_assignees`
--

INSERT INTO `school_task_assignees` (`id`, `school_id`, `task_id`, `assigned_to_type`, `assigned_to_id`, `status`, `created_at`) VALUES
(3, 1, 4, 'student', 2, '', '2025-08-10 13:01:17'),
(4, 1, 4, 'teacher', 1, '', '2025-08-10 20:40:56'),
(5, 1, 5, 'teacher', 3, '', '2025-08-11 02:36:16'),
(6, 1, 5, 'student', 1, '', '2025-08-11 02:36:23'),
(7, 1, 6, 'student', 1, '', '2025-08-11 02:38:29'),
(8, 1, 6, 'student', 2, '', '2025-08-11 02:38:44');

-- --------------------------------------------------------

--
-- Table structure for table `school_timings`
--

CREATE TABLE `school_timings` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `assembly_time` time NOT NULL,
  `leave_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `half_day_config` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`half_day_config`)),
  `is_finalized` tinyint(1) DEFAULT 0,
  `is_preview` tinyint(1) DEFAULT 0,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `school_timings`
--

INSERT INTO `school_timings` (`id`, `school_id`, `assembly_time`, `leave_time`, `created_at`, `half_day_config`, `is_finalized`, `is_preview`, `created_by`) VALUES
(1, 1, '08:00:00', '10:00:00', '2025-07-01 07:26:29', NULL, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `parent_name` varchar(100) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `cnic_formb` varchar(20) DEFAULT NULL,
  `class_grade` varchar(50) DEFAULT NULL,
  `section` varchar(10) DEFAULT NULL,
  `roll_number` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `parent_email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` enum('Active','Inactive','Pending Verification') DEFAULT 'Pending Verification',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `school_id`, `parent_name`, `full_name`, `gender`, `dob`, `cnic_formb`, `class_grade`, `section`, `roll_number`, `address`, `email`, `parent_email`, `phone`, `profile_photo`, `password`, `status`, `created_at`) VALUES
(1, 1, 'shayan khan', 'riayat khan', 'Male', '1999-08-31', '36452847648', '5', 'D', '35', 'jehangira', 'student1@gmail.com', 'perant1@gmail.com', '03462677555', 'Screenshot 2025-04-30 064014.png', '$2y$10$jXW4y2Wb6GFkhbmglSS8BebSTn96rreqNyTY2PcldbCQfn9hGEsRC', 'Active', '2025-07-01 19:37:24'),
(2, 1, 'student', 'Abu bakar', 'Female', '2010-05-31', '36452847648', '5', 'D', '1', 'jehangira', 'student2@gmail.com', 'parent2@gamil.com', '03462677555', 'Screenshot 2025-04-30 064014.png', '$2y$10$xGih15kr5.sEFZXd/mDeXur1ChxlB64vnauEmoa2JHArnTZy4738G', 'Pending Verification', '2025-07-01 20:54:38');

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance`
--

CREATE TABLE `student_attendance` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_meta_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `status` enum('Present','Absent','Leave') NOT NULL,
  `date` date NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_attendance`
--

INSERT INTO `student_attendance` (`id`, `school_id`, `teacher_id`, `class_meta_id`, `student_id`, `status`, `date`, `created_at`) VALUES
(1, 1, 1, 5, 1, 'Absent', '2025-08-13', '2025-08-13 15:18:21'),
(2, 1, 1, 5, 2, 'Present', '2025-08-13', '2025-08-13 15:18:21');

-- --------------------------------------------------------

--
-- Table structure for table `student_fee_plans`
--

CREATE TABLE `student_fee_plans` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `fee_component` int(11) NOT NULL,
  `base_amount` decimal(10,2) NOT NULL,
  `frequency` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_fee_plans`
--

INSERT INTO `student_fee_plans` (`id`, `school_id`, `student_id`, `fee_component`, `base_amount`, `frequency`, `status`, `created_at`) VALUES
(1, 1, 1, 3, 300.00, 'monthly', 'active', '2025-07-24 05:17:07'),
(2, 1, 2, 3, 400.00, 'monthly', 'active', '2025-07-28 09:25:22'),
(3, 1, 2, 4, 100.00, 'monthly', 'active', '2025-07-28 11:05:11');

-- --------------------------------------------------------

--
-- Table structure for table `student_leaves`
--

CREATE TABLE `student_leaves` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `leave_type` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_leaves`
--

INSERT INTO `student_leaves` (`id`, `student_id`, `school_id`, `teacher_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 'sick', '2025-08-14', '2025-08-16', 'ill', 'Approved', '2025-08-13 08:58:34', '2025-08-13 09:22:47');

-- --------------------------------------------------------

--
-- Table structure for table `student_results`
--

CREATE TABLE `student_results` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `marks_obtained` decimal(5,2) DEFAULT 0.00,
  `remarks` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_results`
--

INSERT INTO `student_results` (`id`, `school_id`, `assignment_id`, `student_id`, `marks_obtained`, `remarks`, `attachment`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 90.00, '', '', '2025-08-14 02:09:37', NULL),
(2, 1, 1, 2, 20.00, '', '', '2025-08-14 02:09:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_assignments`
--

CREATE TABLE `teacher_assignments` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `class_meta_id` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `type` enum('Assignment','Test') NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `due_date` date NOT NULL,
  `total_marks` int(5) NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `teacher_assignments`
--

INSERT INTO `teacher_assignments` (`id`, `school_id`, `teacher_id`, `class_meta_id`, `subject`, `type`, `title`, `description`, `due_date`, `total_marks`, `attachment`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 5, 'english', 'Test', 'sfassafadsfsdaf', 'fasfasdfsdfsadfasdfsadf', '2025-08-16', 100, 'assignment_1755116279.png', '2025-08-14 01:17:59', '2025-08-14 01:35:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_fee_types`
--
ALTER TABLE `class_fee_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `fee_type_id` (`fee_type_id`),
  ADD KEY `fk_fee_structure` (`fee_structure_id`);

--
-- Indexes for table `class_timetable_details`
--
ALTER TABLE `class_timetable_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_timetable_meta`
--
ALTER TABLE `class_timetable_meta`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_timetable_weekdays`
--
ALTER TABLE `class_timetable_weekdays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timetable_id` (`timetable_id`);

--
-- Indexes for table `diary_entries`
--
ALTER TABLE `diary_entries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `diary_students`
--
ALTER TABLE `diary_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `diary_id` (`diary_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `digital_notices`
--
ALTER TABLE `digital_notices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faculty_attendance`
--
ALTER TABLE `faculty_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `faculty_leaves`
--
ALTER TABLE `faculty_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_id` (`faculty_id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `fee_periods`
--
ALTER TABLE `fee_periods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `fee_slips`
--
ALTER TABLE `fee_slips`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slip_number` (`slip_number`);

--
-- Indexes for table `fee_slip_details`
--
ALTER TABLE `fee_slip_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `fee_period_id` (`fee_period_id`);

--
-- Indexes for table `fee_structures`
--
ALTER TABLE `fee_structures`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_types`
--
ALTER TABLE `fee_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `meeting_announcements`
--
ALTER TABLE `meeting_announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `meeting_requests`
--
ALTER TABLE `meeting_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scholarships`
--
ALTER TABLE `scholarships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_scholarships_school` (`school_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration_number` (`registration_number`),
  ADD UNIQUE KEY `school_email` (`school_email`);

--
-- Indexes for table `school_settings`
--
ALTER TABLE `school_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_tasks`
--
ALTER TABLE `school_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_school_tasks_school` (`school_id`);

--
-- Indexes for table `school_task_assignees`
--
ALTER TABLE `school_task_assignees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `school_timings`
--
ALTER TABLE `school_timings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `student_attendance`
--
ALTER TABLE `student_attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_fee_plans`
--
ALTER TABLE `student_fee_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `fk_fee_component` (`fee_component`);

--
-- Indexes for table `student_leaves`
--
ALTER TABLE `student_leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_student` (`student_id`),
  ADD KEY `fk_school` (`school_id`),
  ADD KEY `fk_teacher` (`teacher_id`);

--
-- Indexes for table `student_results`
--
ALTER TABLE `student_results`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_submission` (`assignment_id`,`student_id`);

--
-- Indexes for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `class_fee_types`
--
ALTER TABLE `class_fee_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `class_timetable_details`
--
ALTER TABLE `class_timetable_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `class_timetable_meta`
--
ALTER TABLE `class_timetable_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `class_timetable_weekdays`
--
ALTER TABLE `class_timetable_weekdays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `diary_entries`
--
ALTER TABLE `diary_entries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `diary_students`
--
ALTER TABLE `diary_students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `digital_notices`
--
ALTER TABLE `digital_notices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `faculty_attendance`
--
ALTER TABLE `faculty_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faculty_leaves`
--
ALTER TABLE `faculty_leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fee_periods`
--
ALTER TABLE `fee_periods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fee_slips`
--
ALTER TABLE `fee_slips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_slip_details`
--
ALTER TABLE `fee_slip_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `fee_structures`
--
ALTER TABLE `fee_structures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fee_types`
--
ALTER TABLE `fee_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `meeting_announcements`
--
ALTER TABLE `meeting_announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `meeting_requests`
--
ALTER TABLE `meeting_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `scholarships`
--
ALTER TABLE `scholarships`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `school_settings`
--
ALTER TABLE `school_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `school_tasks`
--
ALTER TABLE `school_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `school_task_assignees`
--
ALTER TABLE `school_task_assignees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `school_timings`
--
ALTER TABLE `school_timings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_attendance`
--
ALTER TABLE `student_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_fee_plans`
--
ALTER TABLE `student_fee_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_leaves`
--
ALTER TABLE `student_leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_results`
--
ALTER TABLE `student_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teacher_assignments`
--
ALTER TABLE `teacher_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_fee_types`
--
ALTER TABLE `class_fee_types`
  ADD CONSTRAINT `class_fee_types_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`),
  ADD CONSTRAINT `class_fee_types_ibfk_2` FOREIGN KEY (`fee_type_id`) REFERENCES `fee_types` (`id`),
  ADD CONSTRAINT `fk_fee_structure` FOREIGN KEY (`fee_structure_id`) REFERENCES `fee_structures` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `class_timetable_weekdays`
--
ALTER TABLE `class_timetable_weekdays`
  ADD CONSTRAINT `class_timetable_weekdays_ibfk_1` FOREIGN KEY (`timetable_id`) REFERENCES `class_timetable_meta` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `diary_students`
--
ALTER TABLE `diary_students`
  ADD CONSTRAINT `diary_students_ibfk_1` FOREIGN KEY (`diary_id`) REFERENCES `diary_entries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `diary_students_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faculty_attendance`
--
ALTER TABLE `faculty_attendance`
  ADD CONSTRAINT `faculty_attendance_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `faculty_attendance_ibfk_2` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faculty_leaves`
--
ALTER TABLE `faculty_leaves`
  ADD CONSTRAINT `faculty_leaves_ibfk_1` FOREIGN KEY (`faculty_id`) REFERENCES `faculty` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `faculty_leaves_ibfk_2` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_periods`
--
ALTER TABLE `fee_periods`
  ADD CONSTRAINT `fee_periods_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_slip_details`
--
ALTER TABLE `fee_slip_details`
  ADD CONSTRAINT `fee_slip_details_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_slip_details_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fee_slip_details_ibfk_3` FOREIGN KEY (`fee_period_id`) REFERENCES `fee_periods` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_types`
--
ALTER TABLE `fee_types`
  ADD CONSTRAINT `fee_types_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Constraints for table `meeting_announcements`
--
ALTER TABLE `meeting_announcements`
  ADD CONSTRAINT `meeting_announcements_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Constraints for table `meeting_requests`
--
ALTER TABLE `meeting_requests`
  ADD CONSTRAINT `meeting_requests_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Constraints for table `scholarships`
--
ALTER TABLE `scholarships`
  ADD CONSTRAINT `fk_scholarships_school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `school_tasks`
--
ALTER TABLE `school_tasks`
  ADD CONSTRAINT `fk_school_tasks_school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `school_task_assignees`
--
ALTER TABLE `school_task_assignees`
  ADD CONSTRAINT `school_task_assignees_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `school_tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Constraints for table `student_fee_plans`
--
ALTER TABLE `student_fee_plans`
  ADD CONSTRAINT `fk_fee_component` FOREIGN KEY (`fee_component`) REFERENCES `fee_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_fee_plans_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`),
  ADD CONSTRAINT `student_fee_plans_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`);

--
-- Constraints for table `student_leaves`
--
ALTER TABLE `student_leaves`
  ADD CONSTRAINT `fk_school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `faculty` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
