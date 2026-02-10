-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2026 at 10:32 PM
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
-- Database: `budgettracker`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `name`, `type`, `description`) VALUES
(1, 'Salary_syn', 'Income', 'Monthly salary (synthetic)'),
(2, 'Freelance_syn', 'Income', 'Extra freelance income (synthetic)'),
(3, 'Groceries_syn', 'Expense', 'Food and household groceries (synthetic)'),
(4, 'Rent_syn', 'Expense', 'Monthly rent (synthetic)'),
(5, 'Utilities_syn', 'Expense', 'Electricity, water, gas bills (synthetic)'),
(6, 'Entertainment_syn', 'Expense', 'Movies, games, events (synthetic)'),
(7, 'Transport_syn', 'Expense', 'Fuel, bus, train (synthetic)'),
(8, 'Savings_syn', 'Expense', 'Money set aside for saving (synthetic)');

-- --------------------------------------------------------

--
-- Table structure for table `expense`
--

CREATE TABLE `expense` (
  `expense_id` int(11) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expense`
--

INSERT INTO `expense` (`expense_id`, `user_id`, `category_id`, `amount`, `date`, `description`) VALUES
(1, 'LDBT101syn', 3, 150.00, '2026-01-05', 'Weekly groceries (synthetic)'),
(2, 'LDBT101syn', 4, 700.00, '2026-01-01', 'Monthly rent (synthetic)'),
(3, 'JABT102syn', 3, 180.00, '2026-01-06', 'Groceries (synthetic)'),
(4, 'VGBT103syn', 5, 120.00, '2026-01-03', 'Electricity bill (synthetic)'),
(5, 'MDBT104syn', 6, 50.00, '2026-01-10', 'Movie night (synthetic)');

-- --------------------------------------------------------

--
-- Table structure for table `financial_goal`
--

CREATE TABLE `financial_goal` (
  `goal_id` int(11) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `target_amount` decimal(10,2) DEFAULT NULL,
  `current_amount` decimal(10,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `target_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `financial_goal`
--

INSERT INTO `financial_goal` (`goal_id`, `user_id`, `name`, `target_amount`, `current_amount`, `start_date`, `target_date`, `status`) VALUES
(1, 'LDBT101syn', 'Emergency Fund', 1000.00, 200.00, '2026-01-01', '2026-12-31', 'In Progress'),
(2, 'JABT102syn', 'Holiday', 1500.00, 500.00, '2026-01-01', '2026-12-31', 'In Progress'),
(3, 'VGBT103syn', 'Mattress', 1200.00, 300.00, '2026-01-01', '2026-09-01', 'In Progress');

-- --------------------------------------------------------

--
-- Table structure for table `income`
--

CREATE TABLE `income` (
  `income_id` int(11) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `income`
--

INSERT INTO `income` (`income_id`, `user_id`, `category_id`, `amount`, `date`, `description`) VALUES
(11, 'LDBT101syn', 1, 1800.00, '2026-01-01', 'Monthly salary (synthetic)'),
(12, 'LDBT101syn', 2, 250.00, '2026-01-15', 'Freelance project (synthetic)'),
(13, 'JABT102syn', 1, 2200.00, '2026-01-01', 'Monthly salary (synthetic)'),
(14, 'VGBT103syn', 1, 2000.00, '2026-01-01', 'Monthly salary (synthetic)'),
(15, 'MDBT104syn', 1, 2100.00, '2026-01-01', 'Monthly salary (synthetic)');

-- --------------------------------------------------------

--
-- Table structure for table `reminder`
--

CREATE TABLE `reminder` (
  `reminder_id` int(11) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `reminder_date` date DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reminder`
--

INSERT INTO `reminder` (`reminder_id`, `user_id`, `title`, `description`, `reminder_date`, `is_completed`) VALUES
(1, 'LDBT101syn', 'Close Cash ISA', 'Remember to close Cash ISA (synthetic)', '2026-02-01', 0),
(2, 'JABT102syn', 'Pay Rent', 'Monthly rent due (synthetic)', '2026-02-05', 0),
(3, 'VGBT103syn', 'Submit Payment For Nintendo Switch 2', 'Submit monthly payment for Nintendo Switch 2 (synthetic)', '2026-04-15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `first_name`, `last_name`, `email`, `password`, `address`, `role`) VALUES
('JABT102syn', 'Jake', 'Andrews', 'jakesyn@example.com', 'password123', '45 Oak Avenue', 'User'),
('JABT105syn', 'John', 'Admin', 'adminsyn@example.com', 'adminpass', '1 Admin Street', 'Admin'),
('LDBT101syn', 'Luke', 'Darton', 'lukesyn@example.com', 'password123', '123 Elm Street', 'User'),
('MDBT104syn', 'Mikey', 'Dodson', 'mikeysyn@example.com', 'password123', '12 Maple Lane', 'User'),
('VGBT103syn', 'Valentine', 'Ghiulea', 'valsyn@example.com', 'password123', '78 Pine Road', 'User');

-- --------------------------------------------------------

--
-- Table structure for table `warnings`
--

CREATE TABLE `warnings` (
  `alert_id` int(11) NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `warnings`
--

INSERT INTO `warnings` (`alert_id`, `user_id`, `type`, `message`, `created_date`, `is_read`) VALUES
(1, 'LDBT101syn', 'Interest Limit', 'About to earn more than £1000 interest (synthetic)', '2026-01-15 00:00:00', 0),
(2, 'JABT102syn', 'Budget Limit', 'Monthly budget exceeded (synthetic)', '2026-01-10 00:00:00', 0),
(3, 'VGBT103syn', 'Expense Warning', 'High spending on nintendo switch 2 (synthetic)', '2026-01-12 00:00:00', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `financial_goal`
--
ALTER TABLE `financial_goal`
  ADD PRIMARY KEY (`goal_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`income_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reminder`
--
ALTER TABLE `reminder`
  ADD PRIMARY KEY (`reminder_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `warnings`
--
ALTER TABLE `warnings`
  ADD PRIMARY KEY (`alert_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `expense`
--
ALTER TABLE `expense`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `financial_goal`
--
ALTER TABLE `financial_goal`
  MODIFY `goal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `income`
--
ALTER TABLE `income`
  MODIFY `income_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `reminder`
--
ALTER TABLE `reminder`
  MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `warnings`
--
ALTER TABLE `warnings`
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `expense`
--
ALTER TABLE `expense`
  ADD CONSTRAINT `expense_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `financial_goal`
--
ALTER TABLE `financial_goal`
  ADD CONSTRAINT `financial_goal_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `income`
--
ALTER TABLE `income`
  ADD CONSTRAINT `income_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `income_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `reminder`
--
ALTER TABLE `reminder`
  ADD CONSTRAINT `reminder_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `warnings`
--
ALTER TABLE `warnings`
  ADD CONSTRAINT `warnings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
