-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: ************
-- Generation Time: Sep 07, 2018 at 12:12 AM
-- Server version: 5.5.52-MariaDB
-- PHP Version: 7.0.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s101918586_db`
--
CREATE DATABASE IF NOT EXISTS `s101918586_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `s101918586_db`;

-- --------------------------------------------------------

--
-- Table structure for table `Employees`
--

CREATE TABLE `Employees` (
  `last_name` varchar(60) NOT NULL,
  `first_name` varchar(60) NOT NULL,
  `address` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` varchar(10) NOT NULL,
  `zip` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_number` int(11) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  `passenger_name` varchar(50) NOT NULL,
  `passenger_phone_number` varchar(50) NOT NULL,
  `pickup_unit_number` varchar(50) DEFAULT NULL,
  `pickup_street_number` varchar(50) NOT NULL,
  `pickup_street_name` varchar(100) NOT NULL,
  `pickup_suburb` varchar(30) NOT NULL,
  `destination_suburb` varchar(30) NOT NULL,
  `pickup_time` datetime NOT NULL,
  `booking_time` datetime NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'unassigned'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `email_address` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `item_number` int(11) NOT NULL,
  `make` varchar(30) NOT NULL,
  `model` varchar(30) NOT NULL,
  `price` double NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_number`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`email_address`(20));

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`item_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_number` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `item_number` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
