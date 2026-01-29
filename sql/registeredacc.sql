-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2024 at 07:44 PM
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
-- Database: `mysystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `registeredacc`
--

CREATE TABLE `registeredacc` (
  `id_no` varchar(20) NOT NULL,
  `f_name` varchar(50) NOT NULL,
  `m_initial` varchar(30) NOT NULL,
  `l_name` varchar(30) NOT NULL,
  `extension` varchar(30) NOT NULL,
  `birthday` varchar(25) NOT NULL,
  `age` int(5) NOT NULL,
  `sex` varchar(15) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `purok` varchar(50) NOT NULL,
  `barangay` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `province` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `zipcode` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registeredacc`
--

INSERT INTO `registeredacc` (`id_no`, `f_name`, `m_initial`, `l_name`, `extension`, `birthday`, `age`, `sex`, `username`, `password`, `email`, `purok`, `barangay`, `city`, `province`, `country`, `zipcode`) VALUES
('1234-1222', 'Just', 'L', 'Urcula', 'Jr', '2004-01-13', 0, 'Female', 'urcula.mae', '$2y$10$ElHGOjdppsJ2OVsjVL1UM.uB4yI7/jo0uZPhqyEYY4AyF6rnZvWNW', 'urcula@gmail.com', 'Purok', 'Mabini', 'Cabadbaran', 'Agusan', 'Philippines', 9890),
('1234-1234', 'Justinian', '', 'Melecio', '', '03-05-2004', 20, 'Female', 'ating', '$2y$10$ugq6aWctkeb7aoFGvf8zNOKA7ctiALiEvyTn.NL/nHWKZembJebeq', 'justinian@gmail.com', 'Purok 4', 'Mahogany', 'Butuan', 'Agusan', 'Philippines', 8600);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `registeredacc`
--
ALTER TABLE `registeredacc`
  ADD PRIMARY KEY (`id_no`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
