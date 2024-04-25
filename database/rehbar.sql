-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2024 at 06:00 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rehbar`
--

-- --------------------------------------------------------

--
-- Table structure for table `service_provider_users`
--

CREATE TABLE `service_provider_users` (
  `Name` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `service_provider_users`
--

INSERT INTO `service_provider_users` (`Name`, `Username`, `Password`) VALUES
('Abdullah Sheikh', 'Coursehero256@gmail.com', '1234'),
('Abdullah Sheikh', 'Coursehero256@gmail.com', '1234'),
('Abdullah Sheikh', 'Coursehero256@gmail.com', '1234'),
('Abdullah Sheikh', 'Coursehero256@gmail.com', '1234'),
('Abdullah 122', 'bse203018@cust.pk', '1122');

-- --------------------------------------------------------

--
-- Table structure for table `tourist_users`
--

CREATE TABLE `tourist_users` (
  `Name` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tourist_users`
--

INSERT INTO `tourist_users` (`Name`, `Username`, `Password`) VALUES
('SHEIKH RAHAT ALI hashim', 'abdullah@gmail.com', '1111');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
