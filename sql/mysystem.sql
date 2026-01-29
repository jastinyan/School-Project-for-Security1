-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2026 at 06:52 AM
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
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `reset_id` int(11) NOT NULL,
  `id_no` varchar(20) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `is_verified` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `zipcode` int(20) NOT NULL,
  `role` enum('super_admin','admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registeredacc`
--

INSERT INTO `registeredacc` (`id_no`, `f_name`, `m_initial`, `l_name`, `extension`, `birthday`, `age`, `sex`, `username`, `password`, `email`, `purok`, `barangay`, `city`, `province`, `country`, `zipcode`, `role`) VALUES
('0000-0000', 'Aryan', '', 'Calvo', '', '2001-01-30', 24, 'Female', 'superadmin', '$2y$10$o3Q4fLwp8HccGURyGnuNs.XG6eSThw720J.W0VhbAxy.bBO0S17iS', 'justinianmelecio@gmail.com', 'Purok4', 'Mahogany Pob', 'Butuan City', 'Agusan Del Norte', 'Philippines', 8600, 'super_admin'),
('0000-0001', 'Justinian', '', 'Melecio', '', '2004-03-05', 21, 'Female', 'adminuser', '$2y$10$TMwTfExrFlyaRivpIyZE2uhdiw5Hu8zJbi.JlIuj0pwV5lHiOQ9R6', 'justinian.melecio@csucc.edu.ph', 'Purok4', 'Mahogany Pob', 'Butuan City', 'Agusan Del Norte', 'Philippines', 8600, 'admin'),
('2022-0909', 'Justinian', '', 'Melecio', '', '2004-03-05', 21, 'Female', 'username', '$2y$10$.oeRQM4FYU8UKP5bKnKSzu2d7wNMV6iIz6J829y1r8jF0py.T34wW', 'thiannareyes@gmail.com', 'Purok4', 'Mahogany Pob', 'Butuan City', 'Agusan Del Norte', 'Philippines', 8600, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`reset_id`),
  ADD KEY `id_no` (`id_no`);

--
-- Indexes for table `registeredacc`
--
ALTER TABLE `registeredacc`
  ADD PRIMARY KEY (`id_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`id_no`) REFERENCES `registeredacc` (`id_no`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
