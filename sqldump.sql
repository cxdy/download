-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Oct 03, 2018 at 02:38 AM
-- Server version: 5.6.38
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `download`
--

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE `codes` (
  `id` int(255) NOT NULL,
  `code` varchar(12) NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT '0',
  `ip_address` varchar(25) NOT NULL,
  `link` varchar(255) NOT NULL,
  `downloads` int(225) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `codes`
--

INSERT INTO `codes` (`id`, `code`, `is_used`, `ip_address`, `link`, `downloads`) VALUES
(1, 'abcd1234jeff', 1, '::1', '', 0),
(2, 'jeff4321abcd', 1, '::1', '', 0),
(3, 'namejeff1234', 1, '::1', '2c37654073e5c967889ec1ecdb4a8b89', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `codes`
--
ALTER TABLE `codes`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
