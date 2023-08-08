-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: mydb:3306
-- Generation Time: Mar 28, 2023 at 04:36 AM
-- Server version: 8.0.32
-- PHP Version: 8.0.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db3322`
--

-- --------------------------------------------------------

--
-- Table structure for table `courseinfo`
--

CREATE TABLE `courseinfo` (
  `id` int UNSIGNED NOT NULL,
  `uid` int UNSIGNED NOT NULL,
  `course` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `assign` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `score` tinyint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `courseinfo`
--

INSERT INTO `courseinfo` (`id`, `uid`, `course`, `assign`, `score`) VALUES
(1, 101, 'COMP3322B', 'ASS1', 9),
(2, 101, 'COMP3322B', 'ASS2', 8),
(3, 101, 'COMP3322B', 'ASS3', 10),
(4, 101, 'COMP3322B', 'ASS4', 9),
(5, 102, 'COMP3322B', 'ASS1', 6),
(6, 102, 'COMP3322B', 'ASS2', 6),
(7, 102, 'COMP3322B', 'ASS3', 6),
(8, 102, 'COMP3322B', 'ASS4', 8),
(9, 103, 'COMP3234B', 'Prob-set1', 7),
(10, 103, 'COMP3234B', 'Prob-set2', 5),
(11, 103, 'COMP3234B', 'Prob-set3', 7),
(12, 103, 'COMP3234B', 'Prog1', 13),
(13, 103, 'COMP3234B', 'Midterm', 10),
(14, 101, 'COMP3230A', 'Prob-set1', 4),
(15, 101, 'COMP3230A', 'Prob-set2', 5),
(16, 101, 'COMP3230A', 'Prob-set3', 6),
(17, 101, 'COMP3230A', 'Prog1', 10),
(18, 101, 'COMP3230A', 'Prog2', 10),
(19, 101, 'COMP3230A', 'Midterm', 7),
(20, 104, 'COMP3234B', 'Prob-set1', 2),
(21, 104, 'COMP3234B', 'Prob-set2', 3),
(22, 104, 'COMP3234B', 'Prob-set3', 5),
(23, 104, 'COMP3234B', 'Prog1', 8),
(24, 104, 'COMP3234B', 'Midterm', 5),
(25, 101, 'COMP2501A', 'PASS1', 5),
(26, 101, 'COMP2501A', 'ESSAY', 13),
(27, 101, 'COMP2501A', 'PASS2', 5),
(28, 101, 'COMP2501A', 'Midterm', 10),
(29, 101, 'COMP2501A', 'PASS3', 4),
(30, 104, 'COMP2501A', 'PASS1', 6),
(31, 104, 'COMP2501A', 'ESSAY', 10),
(32, 104, 'COMP2501A', 'PASS2', 6),
(33, 104, 'COMP2501A', 'Midterm', 12),
(34, 104, 'COMP2501A', 'PASS3', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courseinfo`
--
ALTER TABLE `courseinfo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courseinfo`
--
ALTER TABLE `courseinfo`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
