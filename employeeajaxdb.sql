-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2024 at 05:10 AM
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
-- Database: `employeeajaxdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `employeefile`
--

CREATE TABLE `employeefile` (
  `recid` bigint(20) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `civilstat` varchar(20) NOT NULL,
  `contactnum` varchar(20) NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employeefile`
--

INSERT INTO `employeefile` (`recid`, `fullname`, `address`, `birthdate`, `age`, `gender`, `civilstat`, `contactnum`, `salary`, `isactive`) VALUES
(14, 'Mark Santos', 'Pluto', '2000-05-10', 24, 'Male', 'Single', '09293846571', 30067.00, 0),
(15, 'Mariel Bagunas', 'Sunrise', '2004-10-15', 19, 'Male', 'Single', '0909875645', 40000.00, 1),
(16, 'tumesting ka', 'test', '1111-11-11', 1, 'Male', 'Single', '1', 1.00, 1),
(17, 'testtt', 'ttt', '1111-11-11', 1, 'Male', 'Single', '1', 1.00, 1),
(23, 'testtttt', 'tttt', '5555-05-05', 5, 'Male', 'Single', '55', 5.00, 1),
(24, 'Markkybean', 'Nemic', '2000-05-10', 24, 'Male', 'Single', '09485746274', 40000.00, 1),
(25, 'Testing', 'test', '1111-11-11', 1, 'Male', 'Single', '1', 1.00, 0),
(27, 'Bogart', 'bundok', '1233-11-11', 12, 'Male', 'Single', '24235', 1.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(3, 'marksantos', 'marksantos320@gmail.com', '$2y$10$ch7J2DfhWi6riA3xIJHmPuhYKDQL/eUOG4Ngok7G1I/Zsitg97m8e');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employeefile`
--
ALTER TABLE `employeefile`
  ADD PRIMARY KEY (`recid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employeefile`
--
ALTER TABLE `employeefile`
  MODIFY `recid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
