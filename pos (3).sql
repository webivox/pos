-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 05, 2025 at 05:00 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts_accounts`
--

DROP TABLE IF EXISTS `accounts_accounts`;
CREATE TABLE IF NOT EXISTS `accounts_accounts` (
  `account_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `payment_method` int NOT NULL,
  `closing_balance` decimal(14,4) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts_accounts`
--

INSERT INTO `accounts_accounts` (`account_id`, `name`, `payment_method`, `closing_balance`, `status`) VALUES
(1, 'BACK OFFICE', 0, '430700.0000', 1),
(2, 'CMB CASHIER 01', 1, '208350.0000', 1),
(3, 'SAMPATH BANK', 1, '30000.0000', 1),
(4, 'COMMERCIAL BANK', 1, '7850.0000', 1),
(5, 'DK CASHIER 01', 1, '0.0000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `accounts_adjustments`
--

DROP TABLE IF EXISTS `accounts_adjustments`;
CREATE TABLE IF NOT EXISTS `accounts_adjustments` (
  `adjustment_id` int NOT NULL AUTO_INCREMENT,
  `type` char(10) NOT NULL,
  `account_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`adjustment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts_adjustments`
--

INSERT INTO `accounts_adjustments` (`adjustment_id`, `type`, `account_id`, `location_id`, `user_id`, `added_date`, `amount`, `details`) VALUES
(1, 'Credit', 2, 1, 1, '2025-05-02', '15000.0000', 'OWNER BRING CASH DRAWER AMOUNT'),
(2, 'Credit', 1, 1, 1, '2025-05-02', '500000.0000', 'INVESTOR');

-- --------------------------------------------------------

--
-- Table structure for table `accounts_cheque_transactions`
--

DROP TABLE IF EXISTS `accounts_cheque_transactions`;
CREATE TABLE IF NOT EXISTS `accounts_cheque_transactions` (
  `cheque_id` int NOT NULL AUTO_INCREMENT,
  `reference_id` int NOT NULL,
  `added_date` date NOT NULL,
  `transaction_type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` varchar(10) NOT NULL,
  `bank_code` varchar(32) NOT NULL,
  `cheque_date` date NOT NULL,
  `realized_date` date NOT NULL,
  `cheque_no` varchar(32) NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `remarks` text NOT NULL,
  `deposited_account_id` int NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`cheque_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts_cheque_transactions`
--

INSERT INTO `accounts_cheque_transactions` (`cheque_id`, `reference_id`, `added_date`, `transaction_type`, `type`, `bank_code`, `cheque_date`, `realized_date`, `cheque_no`, `amount`, `remarks`, `deposited_account_id`, `status`) VALUES
(1, 33, '2025-05-04', 'INV', 'Received', '', '0000-00-00', '0000-00-00', '', '0.0000', 'CMB00030', 0, 0),
(2, 34, '2025-05-04', 'INV', 'Received', '', '2025-05-05', '0000-00-00', 'fdfdfdf', '2850.0000', 'CMB00031', 4, 0),
(3, 35, '2025-05-04', 'INV', 'Received', '123', '2025-08-08', '0000-00-00', '12345678', '2850.0000', 'CMB00032', 0, 0),
(4, 36, '2025-05-04', 'INV', 'Received', '987', '2025-10-10', '0000-00-00', '666666', '4150.0000', 'CMB00033', 0, 0),
(9, 4, '2025-05-04', 'AEXP', 'Issued', '1', '2026-06-06', '0000-00-00', '123', '150.0000', 'AEXP-00004', 1, 1),
(8, 5, '2025-05-04', 'AEXP', 'Issued', '1', '2026-05-05', '0000-00-00', 'sdfsdfdsf', '5050.0000', 'AEXP-00005', 1, 0),
(7, 6, '2025-05-04', 'AEXP', 'Issued', '1', '2055-05-05', '0000-00-00', '1256', '100.0000', 'AEXP-00006', 1, 0),
(11, 3, '2025-05-05', 'CSETT', 'Received', '12345', '0000-00-00', '0000-00-00', '12345', '5000.0000', 'CSETT-00003', 0, 0),
(12, 4, '2025-05-05', 'CSETT', 'Received', '12345', '2026-05-05', '2025-05-08', '66565', '5000.0000', 'CSETT-00004', 3, 1),
(16, 2, '2025-05-05', 'SPMNT', 'Issued', '1', '0000-00-00', '0000-00-00', '', '200.0000', 'SPMNT-00002', 1, 0),
(15, 5, '2025-05-05', 'CSETT', 'Received', '12345', '2060-02-20', '0000-00-00', 'sdsdssd', '5000.0000', 'CSETT-00005', 3, 1),
(17, 3, '2025-05-05', 'SPMNT', 'Issued', '1', '0000-00-00', '0000-00-00', '123', '5000.0000', 'SPMNT-00003', 1, 0),
(19, 5, '2025-05-05', 'SPMNT', 'Issued', '3', '2025-01-01', '2025-10-10', '12345', '5000.0000', 'SPMNT-00005', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `accounts_expences`
--

DROP TABLE IF EXISTS `accounts_expences`;
CREATE TABLE IF NOT EXISTS `accounts_expences` (
  `expence_id` int NOT NULL AUTO_INCREMENT,
  `account_id` int NOT NULL,
  `location_id` int NOT NULL,
  `expences_type_id` int NOT NULL,
  `payee_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `cheque_no` varchar(32) NOT NULL,
  `cheque_date` date NOT NULL,
  `details` text NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`expence_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts_expences`
--

INSERT INTO `accounts_expences` (`expence_id`, `account_id`, `location_id`, `expences_type_id`, `payee_id`, `added_date`, `amount`, `cheque_no`, `cheque_date`, `details`, `user_id`) VALUES
(1, 1, 1, 1, 2, '2025-05-02', '5000.0000', '', '0000-00-00', 'SDFSDFD', 1),
(2, 4, 1, 1, 2, '2025-05-04', '500.0000', '', '0000-00-00', 'sdsdsdsds', 1),
(3, 4, 1, 1, 2, '2025-05-04', '500.0000', '', '0000-00-00', 'sdsdsds', 1),
(4, 1, 1, 1, 2, '2025-05-04', '150.0000', '123', '2026-06-06', 'sdsds', 1),
(5, 1, 1, 1, 2, '2025-05-04', '5050.0000', 'sdfsdfdsf', '2026-05-05', 'sdsdsd', 1),
(6, 1, 1, 1, 2, '2025-05-04', '100.0000', '1256', '2055-05-05', 'ssdsssdsdsd', 1);

-- --------------------------------------------------------

--
-- Table structure for table `accounts_expences_types`
--

DROP TABLE IF EXISTS `accounts_expences_types`;
CREATE TABLE IF NOT EXISTS `accounts_expences_types` (
  `expences_type_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`expences_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts_expences_types`
--

INSERT INTO `accounts_expences_types` (`expences_type_id`, `name`, `status`) VALUES
(1, 'UTILITIES', 1);

-- --------------------------------------------------------

--
-- Table structure for table `accounts_payee`
--

DROP TABLE IF EXISTS `accounts_payee`;
CREATE TABLE IF NOT EXISTS `accounts_payee` (
  `payee_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`payee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts_payee`
--

INSERT INTO `accounts_payee` (`payee_id`, `name`, `status`) VALUES
(1, 'NONE', 1),
(2, 'DIALOG', 1);

-- --------------------------------------------------------

--
-- Table structure for table `accounts_transfers`
--

DROP TABLE IF EXISTS `accounts_transfers`;
CREATE TABLE IF NOT EXISTS `accounts_transfers` (
  `transfer_id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `account_from_id` int NOT NULL,
  `account_to_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`transfer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts_transfers`
--

INSERT INTO `accounts_transfers` (`transfer_id`, `location_id`, `user_id`, `account_from_id`, `account_to_id`, `added_date`, `amount`, `details`) VALUES
(1, 1, 1, 1, 4, '2025-05-03', '5000.0000', 'sdfghgdh fsdfdsgfdg'),
(2, 1, 0, 2, 1, '2025-05-03', '100.0000', 'CASH OUT'),
(3, 1, 0, 2, 1, '2025-05-03', '1000.0000', 'CASH OUT'),
(4, 1, 0, 2, 1, '2025-05-03', '50.0000', 'CASH OUT'),
(5, 1, 1, 2, 1, '2025-05-03', '1000.0000', 'CASH OUT'),
(6, 1, 1, 2, 1, '2025-05-03', '1000.0000', 'CASH OUT');

-- --------------------------------------------------------

--
-- Table structure for table `account_transactions`
--

DROP TABLE IF EXISTS `account_transactions`;
CREATE TABLE IF NOT EXISTS `account_transactions` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `account_id` int NOT NULL,
  `reference_id` int NOT NULL,
  `added_date` date NOT NULL,
  `transaction_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `debit` decimal(14,4) NOT NULL,
  `credit` decimal(14,4) NOT NULL,
  `balance` decimal(14,4) NOT NULL,
  `remarks` text NOT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `account_transactions`
--

INSERT INTO `account_transactions` (`transaction_id`, `account_id`, `reference_id`, `added_date`, `transaction_type`, `debit`, `credit`, `balance`, `remarks`) VALUES
(1, 2, 1, '2025-05-02', 'AADJ', '15000.0000', '0.0000', '15000.0000', 'AADJ-00001'),
(2, 1, 2, '2025-05-02', 'AADJ', '500000.0000', '0.0000', '500000.0000', 'AADJ-00002'),
(3, 1, 1, '2025-05-02', 'SPMNT', '0.0000', '53500.0000', '446500.0000', 'SPMNT-00001'),
(4, 1, 1, '2025-05-02', 'CSETT', '10000.0000', '0.0000', '456500.0000', 'CSETT-00001'),
(5, 1, 1, '2025-05-02', 'AEXP', '0.0000', '5000.0000', '451500.0000', 'AEXP-00001'),
(6, 1, 1, '2025-05-03', 'ATRNOUT', '0.0000', '5000.0000', '446500.0000', 'ATRN-00001'),
(7, 4, 1, '2025-05-03', 'ATRNIN', '5000.0000', '0.0000', '5000.0000', 'ATRN-00001'),
(8, 2, 15, '2025-05-03', 'INV', '25000.0000', '0.0000', '40000.0000', 'Array'),
(9, 2, 16, '2025-05-03', 'INV', '25000.0000', '0.0000', '65000.0000', 'CMB00014'),
(10, 3, 18, '2025-05-03', 'INV', '25000.0000', '0.0000', '25000.0000', 'CMB00016'),
(11, 2, 19, '2025-05-03', 'INV', '25000.0000', '0.0000', '90000.0000', 'CMB00017'),
(12, 2, 22, '2025-05-03', 'INV', '25000.0000', '0.0000', '115000.0000', 'CMB00020'),
(13, 2, 23, '2025-05-03', 'INV', '25000.0000', '0.0000', '140000.0000', 'CMB00021'),
(14, 2, 29, '2025-05-03', 'INV', '25000.0000', '0.0000', '165000.0000', 'CMB00027'),
(15, 2, 30, '2025-05-03', 'INV', '25000.0000', '0.0000', '190000.0000', 'CMB00028'),
(16, 2, 32, '2025-05-03', 'INV', '21500.0000', '0.0000', '211500.0000', 'CMB00029'),
(17, 2, 2, '2025-05-03', 'ATRNOUT', '0.0000', '100.0000', '211400.0000', 'ATRN-00002'),
(18, 1, 2, '2025-05-03', 'ATRNIN', '100.0000', '0.0000', '446600.0000', 'ATRN-00002'),
(19, 2, 3, '2025-05-03', 'ATRNOUT', '0.0000', '1000.0000', '210400.0000', 'ATRN-00003'),
(20, 1, 3, '2025-05-03', 'ATRNIN', '1000.0000', '0.0000', '447600.0000', 'ATRN-00003'),
(21, 2, 4, '2025-05-03', 'ATRNOUT', '0.0000', '50.0000', '210350.0000', 'ATRN-00004'),
(22, 1, 4, '2025-05-03', 'ATRNIN', '50.0000', '0.0000', '447650.0000', 'ATRN-00004'),
(23, 2, 5, '2025-05-03', 'ATRNOUT', '0.0000', '1000.0000', '209350.0000', 'ATRN-00005'),
(24, 1, 5, '2025-05-03', 'ATRNIN', '1000.0000', '0.0000', '448650.0000', 'ATRN-00005'),
(25, 2, 6, '2025-05-03', 'ATRNOUT', '0.0000', '1000.0000', '208350.0000', 'ATRN-00006'),
(26, 1, 6, '2025-05-03', 'ATRNIN', '1000.0000', '0.0000', '449650.0000', 'ATRN-00006'),
(27, 4, 2, '2025-05-05', 'CHEQUE', '2850.0000', '0.0000', '7850.0000', 'CMB00031 Cheque Realized'),
(28, 1, 7, '2025-05-05', 'CHEQUE', '0.0000', '100.0000', '444500.0000', 'AEXP-00006 Cheque Realized'),
(29, 1, 5, '2025-05-04', 'AEXP', '0.0000', '5050.0000', '444600.0000', 'AEXP-00005'),
(30, 1, 8, '2025-05-05', 'CHEQUE', '0.0000', '5050.0000', '439450.0000', 'AEXP-00005 Cheque Realized'),
(32, 1, 4, '2025-05-05', 'CHEQUE', '4150.0000', '0.0000', '443600.0000', 'CMB00033 Cheque Realized'),
(33, 0, 4, '0000-00-00', 'AEXP', '0.0000', '0.0000', '0.0000', ''),
(34, 1, 9, '2025-05-05', 'CHEQUE', '0.0000', '150.0000', '443450.0000', 'AEXP-00004 Cheque Realized'),
(35, 1, 8, '2025-05-05', 'CHEQUE', '0.0000', '5050.0000', '438400.0000', 'AEXP-00005 Cheque Realized'),
(36, 1, 7, '2025-05-05', 'CHEQUE', '0.0000', '100.0000', '438300.0000', 'AEXP-00006 Cheque Realized'),
(37, 1, 9, '2025-05-05', 'CHEQUE', '0.0000', '150.0000', '438150.0000', 'AEXP-00004 Cheque Realized'),
(38, 1, 9, '2025-05-05', 'CHEQUE', '0.0000', '150.0000', '438000.0000', 'AEXP-00004 Cheque Realized'),
(39, 1, 9, '2025-05-05', 'CHEQUE', '0.0000', '150.0000', '437850.0000', 'AEXP-00004 Cheque Realized'),
(40, 1, 9, '2025-05-05', 'CHEQUE', '0.0000', '150.0000', '437700.0000', 'AEXP-00004 Cheque Realized'),
(41, 1, 9, '2025-05-05', 'CHEQUE', '0.0000', '150.0000', '437550.0000', 'AEXP-00004 Cheque Realized'),
(42, 1, 9, '2025-05-05', 'CHEQUE', '0.0000', '150.0000', '437400.0000', 'AEXP-00004 Cheque Realized'),
(43, 1, 9, '2025-05-05', 'CHEQUE', '0.0000', '150.0000', '437250.0000', 'AEXP-00004 Cheque Realized'),
(44, 1, 9, '2025-05-05', 'CHEQUE', '0.0000', '150.0000', '437100.0000', 'AEXP-00004 Cheque Realized'),
(45, 1, 9, '2025-05-05', 'CHEQUE', '0.0000', '150.0000', '436950.0000', 'AEXP-00004 Cheque Realized'),
(46, 1, 7, '2025-05-05', 'CHEQUE', '0.0000', '100.0000', '436850.0000', 'AEXP-00006 Cheque Realized'),
(47, 1, 9, '2025-05-05', 'CHEQUE', '0.0000', '150.0000', '436700.0000', 'AEXP-00004 Cheque Realized'),
(83, 3, 19, '2025-10-10', 'CHEQUE', '0.0000', '5000.0000', '30000.0000', 'SPMNT-00005 Cheque Realized'),
(58, 1, 4, '2025-05-05', 'SPMNT', '0.0000', '6000.0000', '430700.0000', 'SPMNT-00004'),
(56, 3, 15, '2025-05-05', 'CHEQUE', '5000.0000', '0.0000', '30000.0000', 'CSETT-00005 Cheque Realized'),
(84, 3, 12, '2025-05-08', 'CHEQUE', '5000.0000', '0.0000', '35000.0000', 'CSETT-00004 Cheque Realized');

-- --------------------------------------------------------

--
-- Table structure for table `customers_credit_notes`
--

DROP TABLE IF EXISTS `customers_credit_notes`;
CREATE TABLE IF NOT EXISTS `customers_credit_notes` (
  `credit_note_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`credit_note_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers_credit_notes`
--

INSERT INTO `customers_credit_notes` (`credit_note_id`, `customer_id`, `location_id`, `user_id`, `added_date`, `amount`, `details`) VALUES
(1, 2, 1, 1, '2025-05-02', '10000.0000', 'fghfghfghfghfg');

-- --------------------------------------------------------

--
-- Table structure for table `customers_customers`
--

DROP TABLE IF EXISTS `customers_customers`;
CREATE TABLE IF NOT EXISTS `customers_customers` (
  `customer_id` int NOT NULL AUTO_INCREMENT,
  `customer_group_id` int NOT NULL,
  `name` varchar(64) NOT NULL,
  `phone_number` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `address` text NOT NULL,
  `credit_limit` decimal(14,2) NOT NULL,
  `settlement_days` int NOT NULL,
  `remarks` text NOT NULL,
  `card_no` varchar(10) NOT NULL,
  `closing_balance` decimal(14,4) NOT NULL,
  `loyalty_points` decimal(14,2) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers_customers`
--

INSERT INTO `customers_customers` (`customer_id`, `customer_group_id`, `name`, `phone_number`, `email`, `address`, `credit_limit`, `settlement_days`, `remarks`, `card_no`, `closing_balance`, `loyalty_points`, `status`) VALUES
(1, 0, 'NONE', '077777777777', '', '', '0.00', 0, '', '', '64250.0000', '97.00', 1),
(2, 1, 'ZAMEER', '0768888999', '', '', '2500000.00', 0, '', '', '85000.0000', '248.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers_debit_notes`
--

DROP TABLE IF EXISTS `customers_debit_notes`;
CREATE TABLE IF NOT EXISTS `customers_debit_notes` (
  `debit_note_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`debit_note_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers_debit_notes`
--

INSERT INTO `customers_debit_notes` (`debit_note_id`, `customer_id`, `location_id`, `user_id`, `added_date`, `amount`, `details`) VALUES
(1, 2, 1, 1, '2025-05-02', '15000.0000', 'fggdfgfd'),
(2, 2, 1, 1, '2025-05-02', '10000.0000', 'dffsd fdsgdsf');

-- --------------------------------------------------------

--
-- Table structure for table `customers_groups`
--

DROP TABLE IF EXISTS `customers_groups`;
CREATE TABLE IF NOT EXISTS `customers_groups` (
  `customer_group_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`customer_group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers_groups`
--

INSERT INTO `customers_groups` (`customer_group_id`, `name`, `status`) VALUES
(1, 'GOLD', 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers_settlements`
--

DROP TABLE IF EXISTS `customers_settlements`;
CREATE TABLE IF NOT EXISTS `customers_settlements` (
  `settlement_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `location_id` int NOT NULL,
  `account_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `bank_code` varchar(32) NOT NULL,
  `cheque_date` date NOT NULL,
  `cheque_no` varchar(32) NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`settlement_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers_settlements`
--

INSERT INTO `customers_settlements` (`settlement_id`, `customer_id`, `location_id`, `account_id`, `user_id`, `added_date`, `amount`, `bank_code`, `cheque_date`, `cheque_no`, `details`) VALUES
(1, 2, 1, 1, 1, '2025-05-02', '10000.0000', '', '0000-00-00', '', 'gdsgdsgdsg'),
(2, 2, 1, 2, 1, '2025-05-05', '4000.0000', '', '0000-00-00', '', 'ssdsdsds'),
(3, 2, 2, 2, 1, '2025-05-05', '5000.0000', '12345', '0000-00-00', '12345', 'sdsdsds'),
(4, 2, 2, 5, 1, '2025-05-05', '5000.0000', '12345', '2026-05-05', '66565', 'sdsfsfs'),
(5, 2, 2, 2, 1, '2025-05-05', '5000.0000', '12345', '2060-02-20', 'sdsdssd', 'sgsgsgsd');

-- --------------------------------------------------------

--
-- Table structure for table `customer_loyalty_transactions`
--

DROP TABLE IF EXISTS `customer_loyalty_transactions`;
CREATE TABLE IF NOT EXISTS `customer_loyalty_transactions` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `reference_id` int NOT NULL,
  `added_date` date NOT NULL,
  `transaction_type` varchar(64) NOT NULL,
  `debit` decimal(14,4) NOT NULL,
  `credit` decimal(14,4) NOT NULL,
  `balance` decimal(14,4) NOT NULL,
  `remarks` text NOT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer_loyalty_transactions`
--

INSERT INTO `customer_loyalty_transactions` (`transaction_id`, `customer_id`, `reference_id`, `added_date`, `transaction_type`, `debit`, `credit`, `balance`, `remarks`) VALUES
(1, 2, 29, '2025-05-03', 'INV', '250.0000', '0.0000', '250.0000', 'CMB00027'),
(2, 2, 30, '2025-05-03', 'INV', '250.0000', '0.0000', '500.0000', 'CMB00028'),
(3, 2, 31, '2025-05-03', 'INV', '0.0000', '500.0000', '0.0000', 'CMB00029'),
(4, 2, 31, '2025-05-03', 'INV', '5.0000', '0.0000', '5.0000', 'CMB00029'),
(5, 2, 32, '2025-05-03', 'INV', '215.0000', '0.0000', '220.0000', 'CMB00029'),
(6, 2, 33, '2025-05-04', 'INV', '28.0000', '0.0000', '248.0000', 'CMB00030'),
(7, 1, 34, '2025-05-04', 'INV', '28.0000', '0.0000', '28.0000', 'CMB00031'),
(8, 1, 35, '2025-05-04', 'INV', '28.0000', '0.0000', '56.0000', 'CMB00032'),
(9, 1, 36, '2025-05-04', 'INV', '41.0000', '0.0000', '97.0000', 'CMB00033');

-- --------------------------------------------------------

--
-- Table structure for table `customer_transactions`
--

DROP TABLE IF EXISTS `customer_transactions`;
CREATE TABLE IF NOT EXISTS `customer_transactions` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `reference_id` int NOT NULL,
  `added_date` date NOT NULL,
  `transaction_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `debit` decimal(14,4) NOT NULL,
  `credit` decimal(14,4) NOT NULL,
  `balance` decimal(14,4) NOT NULL,
  `remarks` text NOT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer_transactions`
--

INSERT INTO `customer_transactions` (`transaction_id`, `customer_id`, `reference_id`, `added_date`, `transaction_type`, `debit`, `credit`, `balance`, `remarks`) VALUES
(1, 2, 1, '2025-05-02', 'CDN', '15000.0000', '0.0000', '15000.0000', 'CDN-00001'),
(2, 2, 2, '2025-05-02', 'CDN', '10000.0000', '0.0000', '25000.0000', 'CDN-00002'),
(3, 2, 1, '2025-05-02', 'CCN', '0.0000', '10000.0000', '15000.0000', 'CCN-00001'),
(4, 2, 1, '2025-05-02', 'CSETT', '0.0000', '10000.0000', '5000.0000', 'CSETT-00001'),
(5, 2, 1, '2025-05-03', 'INV', '22000.0000', '0.0000', '27000.0000', 'CMB00001'),
(6, 2, 1, '2025-05-03', 'INVPMNT', '0.0000', '4000.0000', '23000.0000', 'CMB00001'),
(7, 2, 2, '2025-05-03', 'INVPMNT', '0.0000', '18000.0000', '5000.0000', 'CMB00001'),
(8, 1, 2, '2025-05-03', 'INV', '2850.0000', '0.0000', '2850.0000', 'CMB00002'),
(9, 1, 3, '2025-05-03', 'INV', '2850.0000', '0.0000', '5700.0000', 'CMB00003'),
(10, 1, 4, '2025-05-03', 'INV', '2850.0000', '0.0000', '8550.0000', 'CMB00004'),
(11, 1, 5, '2025-05-03', 'INV', '2850.0000', '0.0000', '11400.0000', 'CMB00005'),
(12, 1, 6, '2025-05-03', 'INV', '2850.0000', '0.0000', '14250.0000', 'CMB00006'),
(13, 1, 7, '2025-05-03', 'INV', '2850.0000', '0.0000', '17100.0000', 'CMB00007'),
(14, 1, 7, '2025-05-03', 'INVPMNT', '0.0000', '500.0000', '16600.0000', 'CMB00007'),
(15, 1, 8, '2025-05-03', 'INVPMNT', '0.0000', '2350.0000', '14250.0000', 'CMB00007'),
(16, 1, 8, '2025-05-03', 'INV', '25000.0000', '0.0000', '39250.0000', 'CMB00008'),
(17, 1, 9, '2025-05-03', 'INVPMNT', '0.0000', '500.0000', '38750.0000', 'CMB00008'),
(18, 1, 10, '2025-05-03', 'INVPMNT', '0.0000', '24500.0000', '14250.0000', 'CMB00008'),
(19, 1, 9, '2025-05-03', 'INV', '25000.0000', '0.0000', '39250.0000', 'CMB00009'),
(20, 1, 11, '2025-05-03', 'INVPMNT', '0.0000', '500.0000', '38750.0000', 'CMB00009'),
(21, 1, 12, '2025-05-03', 'INVPMNT', '0.0000', '24500.0000', '14250.0000', 'CMB00009'),
(22, 1, 10, '2025-05-03', 'INV', '25000.0000', '0.0000', '39250.0000', 'CMB00010'),
(23, 1, 13, '2025-05-03', 'INVPMNT', '0.0000', '500.0000', '38750.0000', 'CMB00010'),
(24, 1, 14, '2025-05-03', 'INVPMNT', '0.0000', '24500.0000', '14250.0000', 'CMB00010'),
(25, 1, 11, '2025-05-03', 'INV', '25000.0000', '0.0000', '39250.0000', 'CMB00011'),
(26, 1, 15, '2025-05-03', 'INVPMNT', '0.0000', '25000.0000', '14250.0000', 'CMB00011'),
(27, 1, 12, '2025-05-03', 'INV', '0.0000', '0.0000', '14250.0000', 'CMB00012'),
(28, 1, 13, '2025-05-03', 'INV', '0.0000', '0.0000', '14250.0000', 'CMB00013'),
(29, 1, 14, '2025-05-03', 'INV', '25000.0000', '0.0000', '39250.0000', 'CMB00014'),
(30, 1, 16, '2025-05-03', 'INVPMNT', '0.0000', '25000.0000', '14250.0000', 'CMB00014'),
(31, 1, 15, '2025-05-03', 'INV', '25000.0000', '0.0000', '39250.0000', 'CMB00015'),
(32, 1, 17, '2025-05-03', 'INVPMNT', '0.0000', '25000.0000', '14250.0000', 'CMB00015'),
(33, 1, 16, '2025-05-03', 'INV', '25000.0000', '0.0000', '39250.0000', 'CMB00016'),
(34, 1, 18, '2025-05-03', 'INVPMNT', '0.0000', '25000.0000', '14250.0000', 'CMB00016'),
(35, 1, 17, '2025-05-03', 'INV', '25000.0000', '0.0000', '39250.0000', 'CMB00017'),
(36, 1, 19, '2025-05-03', 'INVPMNT', '0.0000', '25000.0000', '14250.0000', 'CMB00017'),
(37, 1, 18, '2025-05-03', 'INV', '25000.0000', '0.0000', '39250.0000', 'CMB00018'),
(38, 1, 20, '2025-05-03', 'INVPMNT', '0.0000', '25000.0000', '14250.0000', 'CMB00018'),
(39, 1, 19, '2025-05-03', 'INV', '25000.0000', '0.0000', '39250.0000', 'CMB00019'),
(40, 1, 20, '2025-05-03', 'INV', '25000.0000', '0.0000', '64250.0000', 'CMB00020'),
(41, 1, 22, '2025-05-03', 'INVPMNT', '0.0000', '25000.0000', '39250.0000', 'CMB00020'),
(42, 1, 21, '2025-05-03', 'INV', '25000.0000', '0.0000', '64250.0000', 'CMB00021'),
(43, 1, 23, '2025-05-03', 'INVPMNT', '0.0000', '25000.0000', '39250.0000', 'CMB00021'),
(44, 2, 22, '2025-05-03', 'INV', '25000.0000', '0.0000', '30000.0000', 'CMB00022'),
(45, 2, 23, '2025-05-03', 'INV', '25000.0000', '0.0000', '55000.0000', 'CMB00023'),
(46, 2, 24, '2025-05-03', 'INV', '24000.0000', '0.0000', '79000.0000', 'CMB00024'),
(47, 1, 25, '2025-05-03', 'INV', '25000.0000', '0.0000', '64250.0000', 'CMB00025'),
(48, 1, 26, '2025-05-03', 'INV', '25000.0000', '0.0000', '89250.0000', 'CMB00026'),
(49, 1, 28, '2025-05-03', 'INVPMNT', '0.0000', '25000.0000', '64250.0000', 'CMB00026'),
(50, 2, 27, '2025-05-03', 'INV', '25000.0000', '0.0000', '104000.0000', 'CMB00027'),
(51, 2, 28, '2025-05-03', 'INV', '25000.0000', '0.0000', '129000.0000', 'CMB00028'),
(52, 2, 30, '2025-05-03', 'INVPMNT', '0.0000', '25000.0000', '104000.0000', 'CMB00028'),
(53, 2, 29, '2025-05-03', 'INV', '22000.0000', '0.0000', '126000.0000', 'CMB00029'),
(54, 2, 31, '2025-05-03', 'INVPMNT', '0.0000', '500.0000', '125500.0000', 'CMB00029'),
(55, 2, 32, '2025-05-03', 'INVPMNT', '0.0000', '21500.0000', '104000.0000', 'CMB00029'),
(56, 2, 30, '2025-05-04', 'INV', '2850.0000', '0.0000', '106850.0000', 'CMB00030'),
(57, 2, 33, '2025-05-04', 'INVPMNT', '0.0000', '2850.0000', '104000.0000', 'CMB00030'),
(58, 1, 31, '2025-05-04', 'INV', '2850.0000', '0.0000', '67100.0000', 'CMB00031'),
(59, 1, 34, '2025-05-04', 'INVPMNT', '0.0000', '2850.0000', '64250.0000', 'CMB00031'),
(60, 1, 32, '2025-05-04', 'INV', '2850.0000', '0.0000', '67100.0000', 'CMB00032'),
(61, 1, 35, '2025-05-04', 'INVPMNT', '0.0000', '2850.0000', '64250.0000', 'CMB00032'),
(62, 1, 33, '2025-05-04', 'INV', '4150.0000', '0.0000', '68400.0000', 'CMB00033'),
(63, 1, 36, '2025-05-04', 'INVPMNT', '0.0000', '4150.0000', '64250.0000', 'CMB00033'),
(64, 2, 2, '2025-05-05', 'CSETT', '0.0000', '4000.0000', '100000.0000', 'CSETT-00002'),
(65, 2, 3, '2025-05-05', 'CSETT', '0.0000', '5000.0000', '95000.0000', 'CSETT-00003'),
(66, 2, 4, '2025-05-05', 'CSETT', '0.0000', '5000.0000', '90000.0000', 'CSETT-00004'),
(67, 2, 5, '2025-05-05', 'CSETT', '0.0000', '5000.0000', '85000.0000', 'CSETT-00005');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_adjustment_notes`
--

DROP TABLE IF EXISTS `inventory_adjustment_notes`;
CREATE TABLE IF NOT EXISTS `inventory_adjustment_notes` (
  `adjustment_note_id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `remarks` text NOT NULL,
  `no_of_items` decimal(14,4) NOT NULL,
  `no_of_qty` decimal(14,4) NOT NULL,
  PRIMARY KEY (`adjustment_note_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_adjustment_notes`
--

INSERT INTO `inventory_adjustment_notes` (`adjustment_note_id`, `location_id`, `user_id`, `added_date`, `remarks`, `no_of_items`, `no_of_qty`) VALUES
(1, 1, 1, '2025-05-02', 'sdssa', '0.0000', '0.0000'),
(2, 1, 1, '2025-05-02', 'ghgfh', '0.0000', '0.0000');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_adjustment_note_items`
--

DROP TABLE IF EXISTS `inventory_adjustment_note_items`;
CREATE TABLE IF NOT EXISTS `inventory_adjustment_note_items` (
  `adjustment_note_item_id` int NOT NULL AUTO_INCREMENT,
  `adjustment_note_id` int NOT NULL,
  `item_id` int NOT NULL,
  `type` char(5) NOT NULL,
  `qty` decimal(14,4) NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL,
  PRIMARY KEY (`adjustment_note_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_adjustment_note_items`
--

INSERT INTO `inventory_adjustment_note_items` (`adjustment_note_item_id`, `adjustment_note_id`, `item_id`, `type`, `qty`, `amount`, `total`) VALUES
(1, 1, 1, '+', '1.0000', '950.0000', '950.0000'),
(2, 2, 1, '-', '1.0000', '810.0000', '810.0000');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_brands`
--

DROP TABLE IF EXISTS `inventory_brands`;
CREATE TABLE IF NOT EXISTS `inventory_brands` (
  `brand_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_brands`
--

INSERT INTO `inventory_brands` (`brand_id`, `name`, `status`) VALUES
(1, 'NONE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_category`
--

DROP TABLE IF EXISTS `inventory_category`;
CREATE TABLE IF NOT EXISTS `inventory_category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `parent_category_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_category`
--

INSERT INTO `inventory_category` (`category_id`, `parent_category_id`, `name`, `status`) VALUES
(1, 0, 'PRINTERS', 1),
(2, 0, 'POWER &amp; SUPPLY', 1),
(3, 0, 'MONITORS', 1),
(4, 0, 'NETWORK', 1),
(5, 0, 'ACCESSORIES', 1),
(6, 1, 'Printing Paper', 1),
(7, 1, 'LASOR PRINTER', 1),
(8, 1, 'POS PRINTER', 1),
(9, 2, 'UPS', 1),
(10, 2, 'BATERY', 1),
(11, 3, 'LCD', 1),
(12, 3, 'LED', 1),
(13, 4, 'ROUTER', 1),
(14, 4, 'CABLES', 1),
(15, 5, 'PEN DRIVE', 1),
(16, 5, 'KEYBOARDS', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

DROP TABLE IF EXISTS `inventory_items`;
CREATE TABLE IF NOT EXISTS `inventory_items` (
  `item_id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `brand_id` int NOT NULL,
  `unit_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `barcode` varchar(32) NOT NULL,
  `barcode_name` varchar(32) NOT NULL,
  `selling_price` decimal(14,2) NOT NULL,
  `minimum_selling_price` decimal(14,2) NOT NULL,
  `cost` decimal(14,2) NOT NULL,
  `expiry_date` date NOT NULL,
  `re_order_qty` decimal(14,2) NOT NULL,
  `order_qty` decimal(14,2) NOT NULL,
  `minimum_qty` decimal(14,4) NOT NULL,
  `subtract_stock` int NOT NULL,
  `closing_stocks` decimal(14,2) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_items`
--

INSERT INTO `inventory_items` (`item_id`, `category_id`, `brand_id`, `unit_id`, `supplier_id`, `name`, `description`, `barcode`, `barcode_name`, `selling_price`, `minimum_selling_price`, `cost`, `expiry_date`, `re_order_qty`, `order_qty`, `minimum_qty`, `subtract_stock`, `closing_stocks`, `status`) VALUES
(1, 16, 1, 1, 10, 'Keyboard A4 Tech Km-720 Usb (1y)', 'Keyboard A4 Tech Km-720 Usb (1y)', '12345', 'KBDA4026', '2850.00', '2650.00', '816.67', '0000-00-00', '10.00', '25.00', '0.0000', 0, '18.00', 1),
(2, 16, 1, 1, 10, 'Keyboard A4 Tech Km72620 Usb Combo (1y)', 'Keyboard A4 Tech Km72620 Usb Combo (1y)', 'KBDA8222', 'KBDA8222', '4150.00', '3750.00', '3350.00', '0000-00-00', '5.00', '10.00', '0.0000', 0, '9.00', 1),
(3, 16, 1, 1, 11, 'Keyboard Armaggeddon Mka-11r Rgb (6m)', 'Keyboard Armaggeddon Mka-11r Rgb (6m)', 'KBDA1754', 'KBDA1754', '17400.00', '15000.00', '10000.00', '0000-00-00', '5.00', '10.00', '0.0000', 0, '0.00', 1),
(4, 16, 1, 1, 11, 'Keyboard Asus Rog Falchion Rx W/L (1y)', 'Keyboard Asus Rog Falchion Rx W/L (1y)', 'KBDA4656', 'KBDA4656', '22500.00', '20000.00', '13000.00', '0000-00-00', '5.00', '10.00', '0.0000', 0, '10.00', 1),
(5, 11, 1, 1, 10, 'LCD MONITOR 17', 'LCD MONITOR 17', '55454', '454545', '25000.00', '20000.00', '15000.00', '0000-00-00', '10.00', '10.00', '1.0000', 0, '-17.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items_customer_group_price`
--

DROP TABLE IF EXISTS `inventory_items_customer_group_price`;
CREATE TABLE IF NOT EXISTS `inventory_items_customer_group_price` (
  `iicgp_id` int NOT NULL AUTO_INCREMENT,
  `customer_group_id` int NOT NULL,
  `item_id` int NOT NULL,
  `price` decimal(14,4) NOT NULL,
  PRIMARY KEY (`iicgp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_items_customer_group_price`
--

INSERT INTO `inventory_items_customer_group_price` (`iicgp_id`, `customer_group_id`, `item_id`, `price`) VALUES
(1, 1, 5, '22000.0000');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_quotations`
--

DROP TABLE IF EXISTS `inventory_quotations`;
CREATE TABLE IF NOT EXISTS `inventory_quotations` (
  `quotation_id` int NOT NULL AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `remarks` text NOT NULL,
  `no_of_items` decimal(14,4) NOT NULL,
  `no_of_qty` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL,
  PRIMARY KEY (`quotation_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_quotations`
--

INSERT INTO `inventory_quotations` (`quotation_id`, `customer_id`, `location_id`, `user_id`, `added_date`, `remarks`, `no_of_items`, `no_of_qty`, `total`) VALUES
(1, 2, 1, 1, '2025-05-03', 'sdsdsd', '3.0000', '35.0000', '331000.0000');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_quotation_items`
--

DROP TABLE IF EXISTS `inventory_quotation_items`;
CREATE TABLE IF NOT EXISTS `inventory_quotation_items` (
  `quotation_item_id` int NOT NULL AUTO_INCREMENT,
  `quotation_id` int NOT NULL,
  `item_id` int NOT NULL,
  `qty` decimal(14,4) NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `discount` decimal(14,4) NOT NULL,
  `final_amount` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL,
  PRIMARY KEY (`quotation_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_quotation_items`
--

INSERT INTO `inventory_quotation_items` (`quotation_item_id`, `quotation_id`, `item_id`, `qty`, `amount`, `discount`, `final_amount`, `total`) VALUES
(1, 1, 2, '10.0000', '4150.0000', '0.0000', '4150.0000', '41500.0000'),
(2, 1, 3, '15.0000', '17400.0000', '0.0000', '17400.0000', '261000.0000'),
(3, 1, 1, '10.0000', '2850.0000', '0.0000', '2850.0000', '28500.0000');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_receiving_notes`
--

DROP TABLE IF EXISTS `inventory_receiving_notes`;
CREATE TABLE IF NOT EXISTS `inventory_receiving_notes` (
  `receiving_note_id` int NOT NULL AUTO_INCREMENT,
  `po_id` int NOT NULL,
  `location_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `invoice_no` varchar(32) NOT NULL,
  `due_date` date NOT NULL,
  `remarks` text NOT NULL,
  `total_value` decimal(14,4) NOT NULL,
  `total_saving` decimal(14,4) NOT NULL,
  `no_of_items` decimal(14,4) NOT NULL,
  `no_of_qty` decimal(14,4) NOT NULL,
  PRIMARY KEY (`receiving_note_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_receiving_notes`
--

INSERT INTO `inventory_receiving_notes` (`receiving_note_id`, `po_id`, `location_id`, `supplier_id`, `user_id`, `added_date`, `invoice_no`, `due_date`, `remarks`, `total_value`, `total_saving`, `no_of_items`, `no_of_qty`) VALUES
(1, 0, 1, 10, 1, '2025-05-02', '123', '2025-05-25', 'sdsd', '163500.0000', '0.0000', '2.0000', '20.0000'),
(2, 0, 1, 11, 1, '2025-05-02', '643656', '0000-00-00', 'dfgfhfdhfd', '16200.0000', '380.0000', '2.0000', '20.0000'),
(3, 0, 1, 11, 1, '2025-05-02', 'SDFS', '0000-00-00', 'DSFDSF', '45000.0000', '0.0000', '3.0000', '3.0000');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_receiving_note_items`
--

DROP TABLE IF EXISTS `inventory_receiving_note_items`;
CREATE TABLE IF NOT EXISTS `inventory_receiving_note_items` (
  `receiving_note_item_id` int NOT NULL AUTO_INCREMENT,
  `receiving_note_id` int NOT NULL,
  `item_id` int NOT NULL,
  `qty` decimal(14,0) NOT NULL,
  `price` decimal(14,0) NOT NULL,
  `buying_price` decimal(14,0) NOT NULL,
  `discount` decimal(14,0) NOT NULL,
  `final_price` decimal(14,0) NOT NULL,
  `total` decimal(14,4) NOT NULL,
  `expiriy_date` date NOT NULL,
  PRIMARY KEY (`receiving_note_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_receiving_note_items`
--

INSERT INTO `inventory_receiving_note_items` (`receiving_note_item_id`, `receiving_note_id`, `item_id`, `qty`, `price`, `buying_price`, `discount`, `final_price`, `total`, `expiriy_date`) VALUES
(4, 2, 1, '10', '1000', '900', '10', '810', '8100.0000', '0000-00-00'),
(2, 1, 2, '10', '3350', '3350', '0', '3350', '33500.0000', '0000-00-00'),
(3, 1, 4, '10', '13000', '13000', '0', '13000', '130000.0000', '0000-00-00'),
(5, 2, 1, '10', '1000', '900', '10', '810', '8100.0000', '0000-00-00'),
(6, 3, 5, '1', '10000', '10000', '0', '10000', '10000.0000', '0000-00-00'),
(7, 3, 5, '1', '15000', '15000', '0', '15000', '15000.0000', '0000-00-00'),
(8, 3, 5, '1', '20000', '20000', '0', '20000', '20000.0000', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_return_notes`
--

DROP TABLE IF EXISTS `inventory_return_notes`;
CREATE TABLE IF NOT EXISTS `inventory_return_notes` (
  `return_note_id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rn_no` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `added_date` date NOT NULL,
  `remarks` text NOT NULL,
  `total_value` decimal(14,4) NOT NULL,
  `no_of_items` int NOT NULL,
  `no_of_qty` decimal(14,4) NOT NULL,
  PRIMARY KEY (`return_note_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_return_notes`
--

INSERT INTO `inventory_return_notes` (`return_note_id`, `location_id`, `supplier_id`, `user_id`, `rn_no`, `added_date`, `remarks`, `total_value`, `no_of_items`, `no_of_qty`) VALUES
(1, 1, 11, 1, 'RN-00002', '2025-05-02', 'sdsdsd', '1620.0000', 2, '2.0000'),
(2, 1, 11, 1, 'RN-00002', '2025-05-02', 'ddsf', '2430.0000', 3, '3.0000');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_return_note_items`
--

DROP TABLE IF EXISTS `inventory_return_note_items`;
CREATE TABLE IF NOT EXISTS `inventory_return_note_items` (
  `return_note_item_id` int NOT NULL AUTO_INCREMENT,
  `return_note_id` int NOT NULL,
  `item_id` int NOT NULL,
  `qty` decimal(14,4) NOT NULL,
  `price` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL,
  PRIMARY KEY (`return_note_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_return_note_items`
--

INSERT INTO `inventory_return_note_items` (`return_note_item_id`, `return_note_id`, `item_id`, `qty`, `price`, `total`) VALUES
(1, 1, 1, '1.0000', '810.0000', '810.0000'),
(2, 1, 1, '1.0000', '810.0000', '810.0000'),
(3, 2, 1, '1.0000', '810.0000', '810.0000'),
(4, 2, 1, '1.0000', '810.0000', '810.0000'),
(5, 2, 1, '1.0000', '810.0000', '810.0000');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_stock_transactions`
--

DROP TABLE IF EXISTS `inventory_stock_transactions`;
CREATE TABLE IF NOT EXISTS `inventory_stock_transactions` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `item_id` int NOT NULL,
  `location_id` int NOT NULL,
  `reference_id` int NOT NULL,
  `reference_row_id` int NOT NULL,
  `transaction_type` varchar(64) NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `qty_in` decimal(14,4) NOT NULL,
  `qty_out` decimal(14,4) NOT NULL,
  `stock_balance_locations` varchar(64) NOT NULL,
  `stock_balance` decimal(14,4) NOT NULL,
  `remarks` text NOT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_stock_transactions`
--

INSERT INTO `inventory_stock_transactions` (`transaction_id`, `item_id`, `location_id`, `reference_id`, `reference_row_id`, `transaction_type`, `added_date`, `amount`, `qty_in`, `qty_out`, `stock_balance_locations`, `stock_balance`, `remarks`) VALUES
(4, 1, 1, 2, 4, 'RN', '2025-05-02', '810.0000', '10.0000', '0.0000', '{\"1\":10}', '10.0000', 'RN-00002'),
(2, 2, 1, 1, 2, 'RN', '2025-05-02', '3350.0000', '10.0000', '0.0000', '{\"1\":10}', '10.0000', 'RN-00001'),
(3, 4, 1, 1, 3, 'RN', '2025-05-02', '13000.0000', '10.0000', '0.0000', '{\"1\":10}', '10.0000', 'RN-00001'),
(5, 1, 1, 2, 5, 'RN', '2025-05-02', '810.0000', '10.0000', '0.0000', '{\"1\":20}', '20.0000', 'RN-00002'),
(6, 1, 1, 1, 1, 'RETN', '2025-05-02', '810.0000', '0.0000', '1.0000', '{\"1\":19}', '19.0000', 'RETN-00001'),
(7, 1, 1, 1, 2, 'RETN', '2025-05-02', '810.0000', '0.0000', '1.0000', '{\"1\":18}', '18.0000', 'RETN-00001'),
(8, 1, 1, 2, 3, 'RETN', '2025-05-02', '810.0000', '0.0000', '1.0000', '{\"1\":17}', '17.0000', 'RETN-00002'),
(9, 1, 1, 2, 4, 'RETN', '2025-05-02', '810.0000', '0.0000', '1.0000', '{\"1\":16}', '16.0000', 'RETN-00002'),
(10, 1, 1, 2, 5, 'RETN', '2025-05-02', '810.0000', '0.0000', '1.0000', '{\"1\":15}', '15.0000', 'RETN-00002'),
(11, 1, 1, 1, 1, 'TRNOUT', '2025-05-02', '0.0000', '0.0000', '5.0000', '{\"1\":10}', '10.0000', 'TRN-00001'),
(12, 1, 2, 1, 1, 'TRNIN', '2025-05-02', '0.0000', '5.0000', '0.0000', '{\"2\":5}', '15.0000', 'TRN-00001'),
(13, 1, 1, 1, 1, 'ADJ', '2025-05-02', '950.0000', '1.0000', '0.0000', '{\"1\":11}', '16.0000', 'RN-00001'),
(14, 1, 1, 2, 2, 'ADJ', '2025-05-02', '810.0000', '0.0000', '1.0000', '{\"1\":10}', '15.0000', 'RN-00002'),
(15, 5, 1, 3, 6, 'RN', '2025-05-02', '10000.0000', '1.0000', '0.0000', '{\"1\":1}', '1.0000', 'RN-00003'),
(16, 5, 1, 3, 7, 'RN', '2025-05-02', '15000.0000', '1.0000', '0.0000', '{\"1\":2}', '2.0000', 'RN-00003'),
(17, 5, 1, 3, 8, 'RN', '2025-05-02', '20000.0000', '1.0000', '0.0000', '{\"1\":3}', '3.0000', 'RN-00003'),
(18, 1, 1, 1, 1, 'SRN', '2025-05-03', '816.6700', '10.0000', '0.0000', '{\"1\":20}', '25.0000', 'SRN-00001'),
(23, 1, 1, 6, 6, 'INV', '2025-05-03', '816.6700', '0.0000', '1.0000', '{\"1\":17}', '22.0000', 'CMB00006'),
(24, 1, 1, 7, 7, 'INV', '2025-05-03', '816.6700', '0.0000', '1.0000', '{\"1\":16}', '21.0000', 'CMB00007'),
(21, 1, 1, 4, 4, 'INV', '2025-05-03', '816.6700', '0.0000', '1.0000', '{\"1\":19}', '24.0000', 'CMB00004'),
(22, 1, 1, 5, 5, 'INV', '2025-05-03', '816.6700', '0.0000', '1.0000', '{\"1\":18}', '23.0000', 'CMB00005'),
(25, 5, 1, 8, 8, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":2}', '2.0000', 'CMB00008'),
(26, 5, 1, 9, 9, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":1}', '1.0000', 'CMB00009'),
(27, 5, 1, 10, 10, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":0}', '0.0000', 'CMB00010'),
(28, 5, 1, 11, 11, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-1}', '-1.0000', 'CMB00011'),
(29, 5, 1, 14, 12, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-2}', '-2.0000', 'CMB00014'),
(30, 5, 1, 15, 13, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-3}', '-3.0000', 'CMB00015'),
(31, 5, 1, 16, 14, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-4}', '-4.0000', 'CMB00016'),
(32, 5, 1, 17, 15, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-5}', '-5.0000', 'CMB00017'),
(33, 5, 1, 18, 16, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-6}', '-6.0000', 'CMB00018'),
(34, 5, 1, 19, 17, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-7}', '-7.0000', 'CMB00019'),
(35, 5, 1, 20, 18, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-8}', '-8.0000', 'CMB00020'),
(36, 5, 1, 21, 19, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-9}', '-9.0000', 'CMB00021'),
(37, 5, 1, 22, 20, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-10}', '-10.0000', 'CMB00022'),
(38, 5, 1, 23, 21, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-11}', '-11.0000', 'CMB00023'),
(39, 5, 1, 24, 22, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-12}', '-12.0000', 'CMB00024'),
(40, 5, 1, 25, 23, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-13}', '-13.0000', 'CMB00025'),
(41, 5, 1, 26, 24, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-14}', '-14.0000', 'CMB00026'),
(42, 5, 1, 27, 25, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-15}', '-15.0000', 'CMB00027'),
(43, 5, 1, 28, 26, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-16}', '-16.0000', 'CMB00028'),
(44, 5, 1, 29, 27, 'INV', '2025-05-03', '15000.0000', '0.0000', '1.0000', '{\"1\":-17}', '-17.0000', 'CMB00029'),
(45, 1, 1, 30, 28, 'INV', '2025-05-04', '816.6700', '0.0000', '1.0000', '{\"1\":15}', '20.0000', 'CMB00030'),
(46, 1, 1, 31, 29, 'INV', '2025-05-04', '816.6700', '0.0000', '1.0000', '{\"1\":14}', '19.0000', 'CMB00031'),
(47, 1, 1, 32, 30, 'INV', '2025-05-04', '816.6700', '0.0000', '1.0000', '{\"1\":13}', '18.0000', 'CMB00032'),
(48, 2, 1, 33, 31, 'INV', '2025-05-04', '3350.0000', '0.0000', '1.0000', '{\"1\":9}', '9.0000', 'CMB00033');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transfer_notes`
--

DROP TABLE IF EXISTS `inventory_transfer_notes`;
CREATE TABLE IF NOT EXISTS `inventory_transfer_notes` (
  `transfer_note_id` int NOT NULL AUTO_INCREMENT,
  `location_from_id` int NOT NULL,
  `location_to_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `remarks` text NOT NULL,
  `no_of_items` int NOT NULL,
  `no_of_qty` decimal(14,4) NOT NULL,
  PRIMARY KEY (`transfer_note_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_transfer_notes`
--

INSERT INTO `inventory_transfer_notes` (`transfer_note_id`, `location_from_id`, `location_to_id`, `user_id`, `added_date`, `remarks`, `no_of_items`, `no_of_qty`) VALUES
(1, 1, 2, 1, '2025-05-02', 'dsdsf', 1, '5.0000');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transfer_note_items`
--

DROP TABLE IF EXISTS `inventory_transfer_note_items`;
CREATE TABLE IF NOT EXISTS `inventory_transfer_note_items` (
  `transfer_note_item_id` int NOT NULL AUTO_INCREMENT,
  `transfer_note_id` int NOT NULL,
  `item_id` int NOT NULL,
  `qty` decimal(14,4) NOT NULL,
  PRIMARY KEY (`transfer_note_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_transfer_note_items`
--

INSERT INTO `inventory_transfer_note_items` (`transfer_note_item_id`, `transfer_note_id`, `item_id`, `qty`) VALUES
(1, 1, 1, '5.0000');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_units`
--

DROP TABLE IF EXISTS `inventory_units`;
CREATE TABLE IF NOT EXISTS `inventory_units` (
  `unit_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`unit_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_units`
--

INSERT INTO `inventory_units` (`unit_id`, `name`, `status`) VALUES
(1, 'PCS', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_warranty`
--

DROP TABLE IF EXISTS `inventory_warranty`;
CREATE TABLE IF NOT EXISTS `inventory_warranty` (
  `warranty_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`warranty_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_warranty`
--

INSERT INTO `inventory_warranty` (`warranty_id`, `name`, `status`) VALUES
(1, 'NO WARRANTY', 1);

-- --------------------------------------------------------

--
-- Table structure for table `master`
--

DROP TABLE IF EXISTS `master`;
CREATE TABLE IF NOT EXISTS `master` (
  `master_id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `values` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`master_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `master`
--

INSERT INTO `master` (`master_id`, `key`, `values`) VALUES
(1, 'companyName', 'Webivox Itnernational pvt ltd'),
(2, 'logo', 'company/logo.png'),
(3, 'per_page_results', '5'),
(4, 'loyalty_points', '100=1'),
(5, 'loyalty_points_cash', 'true'),
(6, 'loyalty_points_card', 'true'),
(7, 'loyalty_points_return', 'true'),
(8, 'loyalty_points_gift_card', 'true'),
(9, 'loyalty_points_credit', 'true'),
(10, 'loyalty_points_loyalty', 'true'),
(11, 'loyalty_points_cheque', 'true');

-- --------------------------------------------------------

--
-- Table structure for table `sales_gift_cards`
--

DROP TABLE IF EXISTS `sales_gift_cards`;
CREATE TABLE IF NOT EXISTS `sales_gift_cards` (
  `gift_card_id` int NOT NULL AUTO_INCREMENT,
  `no` varchar(32) NOT NULL,
  `expiry_date` date NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `used_amount` decimal(14,2) NOT NULL,
  `balance_amount` decimal(14,2) NOT NULL,
  PRIMARY KEY (`gift_card_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_gift_cards`
--

INSERT INTO `sales_gift_cards` (`gift_card_id`, `no`, `expiry_date`, `amount`, `used_amount`, `balance_amount`) VALUES
(1, 'abcdefg', '2025-05-10', '5000.00', '1000.00', '4000.00'),
(2, 'hijklm', '2025-05-10', '2500.00', '0.00', '2500.00');

-- --------------------------------------------------------

--
-- Table structure for table `sales_invoices`
--

DROP TABLE IF EXISTS `sales_invoices`;
CREATE TABLE IF NOT EXISTS `sales_invoices` (
  `invoice_id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `cashier_point_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `sales_rep_id` int NOT NULL,
  `invoice_no` varchar(12) NOT NULL,
  `added_date` datetime NOT NULL,
  `total_sale` decimal(14,4) NOT NULL,
  `discount_type` varchar(12) NOT NULL,
  `discount_value` decimal(14,4) NOT NULL,
  `discount_amount` decimal(14,4) NOT NULL,
  `total_sale_cost` decimal(14,4) NOT NULL,
  `total_paid` decimal(14,4) NOT NULL,
  `cash_sales` decimal(14,4) NOT NULL,
  `card_sales` decimal(14,4) NOT NULL,
  `return_sales` decimal(14,4) NOT NULL,
  `gift_card_sales` decimal(14,4) NOT NULL,
  `loyalty_sales` decimal(14,4) NOT NULL,
  `credit_sales` decimal(14,4) NOT NULL,
  `cheque_sales` decimal(14,4) NOT NULL,
  `comments` text NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_invoices`
--

INSERT INTO `sales_invoices` (`invoice_id`, `location_id`, `user_id`, `cashier_point_id`, `customer_id`, `sales_rep_id`, `invoice_no`, `added_date`, `total_sale`, `discount_type`, `discount_value`, `discount_amount`, `total_sale_cost`, `total_paid`, `cash_sales`, `card_sales`, `return_sales`, `gift_card_sales`, `loyalty_sales`, `credit_sales`, `cheque_sales`, `comments`, `status`) VALUES
(1, 1, 1, 1, 2, 1, 'CMB00001', '2025-05-03 20:29:20', '22000.0000', '', '0.0000', '0.0000', '15000.0000', '22000.0000', '0.0000', '0.0000', '18000.0000', '4000.0000', '0.0000', '0.0000', '0.0000', '', 1),
(2, 1, 1, 1, 1, 1, 'CMB00002', '2025-05-03 20:47:36', '2850.0000', '', '0.0000', '0.0000', '816.6700', '2850.0000', '0.0000', '0.0000', '2350.0000', '500.0000', '0.0000', '0.0000', '0.0000', '', 1),
(3, 1, 1, 1, 1, 1, 'CMB00003', '2025-05-03 20:48:13', '2850.0000', '', '0.0000', '0.0000', '816.6700', '2850.0000', '0.0000', '0.0000', '2350.0000', '500.0000', '0.0000', '0.0000', '0.0000', '', 1),
(4, 1, 1, 1, 1, 1, 'CMB00004', '2025-05-03 20:48:49', '2850.0000', '', '0.0000', '0.0000', '816.6700', '2850.0000', '0.0000', '0.0000', '2350.0000', '500.0000', '0.0000', '0.0000', '0.0000', '', 1),
(5, 1, 1, 1, 1, 1, 'CMB00005', '2025-05-03 21:01:22', '2850.0000', '', '0.0000', '0.0000', '816.6700', '2850.0000', '0.0000', '0.0000', '2350.0000', '500.0000', '0.0000', '0.0000', '0.0000', '', 1),
(6, 1, 1, 1, 1, 1, 'CMB00006', '2025-05-03 21:03:18', '2850.0000', '', '0.0000', '0.0000', '816.6700', '2850.0000', '0.0000', '0.0000', '2350.0000', '500.0000', '0.0000', '0.0000', '0.0000', '', 1),
(7, 1, 1, 1, 1, 1, 'CMB00007', '2025-05-03 21:04:20', '2850.0000', '', '0.0000', '0.0000', '816.6700', '2850.0000', '0.0000', '0.0000', '2350.0000', '500.0000', '0.0000', '0.0000', '0.0000', '', 1),
(8, 1, 1, 1, 1, 1, 'CMB00008', '2025-05-03 21:05:59', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '24500.0000', '0.0000', '0.0000', '500.0000', '0.0000', '0.0000', '0.0000', '', 1),
(9, 1, 1, 1, 1, 1, 'CMB00009', '2025-05-03 21:06:52', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '24500.0000', '0.0000', '0.0000', '500.0000', '0.0000', '0.0000', '0.0000', '', 1),
(10, 1, 1, 1, 1, 1, 'CMB00010', '2025-05-03 21:07:18', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '24500.0000', '0.0000', '0.0000', '500.0000', '0.0000', '0.0000', '0.0000', '', 1),
(11, 1, 1, 1, 1, 1, 'CMB00011', '2025-05-03 21:15:09', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '', 1),
(12, 1, 1, 1, 1, 1, 'CMB00012', '2025-05-03 21:15:43', '0.0000', '', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '', 1),
(13, 1, 1, 1, 1, 1, 'CMB00013', '2025-05-03 21:17:22', '0.0000', '', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '', 1),
(14, 1, 1, 1, 1, 1, 'CMB00014', '2025-05-03 21:17:31', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '', 1),
(15, 1, 1, 1, 1, 1, 'CMB00015', '2025-05-03 21:18:06', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '0.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '', 1),
(16, 1, 1, 1, 1, 1, 'CMB00016', '2025-05-03 21:20:01', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '0.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '', 1),
(17, 1, 1, 1, 1, 1, 'CMB00017', '2025-05-03 21:23:30', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '', 1),
(18, 1, 1, 1, 1, 1, 'CMB00018', '2025-05-03 21:24:18', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '25000.0000', '0.0000', '', 1),
(19, 1, 1, 1, 1, 1, 'CMB00019', '2025-05-03 21:25:14', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '25000.0000', '0.0000', '', 1),
(20, 1, 1, 1, 1, 1, 'CMB00020', '2025-05-03 21:38:33', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '', 1),
(21, 1, 1, 1, 1, 1, 'CMB00021', '2025-05-03 21:39:21', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '', 1),
(22, 1, 1, 1, 2, 1, 'CMB00022', '2025-05-03 21:40:07', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '25000.0000', '0.0000', '', 1),
(23, 1, 1, 1, 2, 1, 'CMB00023', '2025-05-03 21:41:49', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '25000.0000', '0.0000', '', 1),
(24, 1, 1, 1, 2, 1, 'CMB00024', '2025-05-03 21:42:51', '24000.0000', '', '0.0000', '0.0000', '15000.0000', '24000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '24000.0000', '0.0000', '', 1),
(25, 1, 1, 1, 1, 1, 'CMB00025', '2025-05-03 21:45:05', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '25000.0000', '', 1),
(26, 1, 1, 1, 1, 1, 'CMB00026', '2025-05-03 21:46:10', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '25000.0000', '', 1),
(27, 1, 1, 1, 2, 1, 'CMB00027', '2025-05-03 22:26:03', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '', 1),
(28, 1, 1, 1, 2, 1, 'CMB00028', '2025-05-03 22:27:09', '25000.0000', '', '0.0000', '0.0000', '15000.0000', '25000.0000', '25000.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '', 1),
(29, 1, 1, 1, 2, 1, 'CMB00029', '2025-05-03 22:28:03', '22000.0000', '', '0.0000', '0.0000', '15000.0000', '22000.0000', '21500.0000', '0.0000', '0.0000', '0.0000', '500.0000', '0.0000', '0.0000', '', 1),
(30, 1, 1, 1, 2, 1, 'CMB00030', '2025-05-04 10:12:23', '2850.0000', '', '0.0000', '0.0000', '816.6700', '2850.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '2850.0000', '', 1),
(31, 1, 1, 1, 1, 1, 'CMB00031', '2025-05-04 10:16:22', '2850.0000', '', '0.0000', '0.0000', '816.6700', '2850.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '2850.0000', '', 1),
(32, 1, 1, 1, 1, 1, 'CMB00032', '2025-05-04 10:17:36', '2850.0000', '', '0.0000', '0.0000', '816.6700', '2850.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '2850.0000', '', 1),
(33, 1, 1, 1, 1, 1, 'CMB00033', '2025-05-04 10:18:26', '4150.0000', '', '0.0000', '0.0000', '3350.0000', '4150.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '4150.0000', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales_invoice_items`
--

DROP TABLE IF EXISTS `sales_invoice_items`;
CREATE TABLE IF NOT EXISTS `sales_invoice_items` (
  `invoice_item_id` int NOT NULL AUTO_INCREMENT,
  `invoice_id` int NOT NULL,
  `item_id` int NOT NULL,
  `cost` decimal(14,4) NOT NULL,
  `master_price` decimal(14,4) NOT NULL,
  `price` decimal(14,4) NOT NULL,
  `discount` decimal(14,4) NOT NULL,
  `unit_price` decimal(14,4) NOT NULL,
  `qty` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`invoice_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_invoice_items`
--

INSERT INTO `sales_invoice_items` (`invoice_item_id`, `invoice_id`, `item_id`, `cost`, `master_price`, `price`, `discount`, `unit_price`, `qty`, `total`, `created_on`) VALUES
(1, 1, 5, '15000.0000', '25000.0000', '22000.0000', '0.0000', '22000.0000', '1.0000', '22000.0000', '2025-05-03 20:24:26'),
(2, 2, 1, '816.6700', '2850.0000', '2850.0000', '0.0000', '2850.0000', '1.0000', '2850.0000', '2025-05-03 20:35:25'),
(3, 3, 1, '816.6700', '2850.0000', '2850.0000', '0.0000', '2850.0000', '1.0000', '2850.0000', '2025-05-03 20:35:25'),
(4, 4, 1, '816.6700', '2850.0000', '2850.0000', '0.0000', '2850.0000', '1.0000', '2850.0000', '2025-05-03 20:35:25'),
(5, 5, 1, '816.6700', '2850.0000', '2850.0000', '0.0000', '2850.0000', '1.0000', '2850.0000', '2025-05-03 20:35:25'),
(6, 6, 1, '816.6700', '2850.0000', '2850.0000', '0.0000', '2850.0000', '1.0000', '2850.0000', '2025-05-03 20:35:25'),
(7, 7, 1, '816.6700', '2850.0000', '2850.0000', '0.0000', '2850.0000', '1.0000', '2850.0000', '2025-05-03 20:35:25'),
(8, 8, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:05:38'),
(9, 9, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:06:30'),
(10, 10, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:07:03'),
(11, 11, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:09:27'),
(12, 14, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:17:28'),
(13, 15, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:17:58'),
(14, 16, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:19:54'),
(15, 17, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:20:53'),
(16, 18, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:24:06'),
(17, 19, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:25:02'),
(18, 20, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:38:24'),
(19, 21, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:38:46'),
(20, 22, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:39:27'),
(21, 23, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:40:57'),
(22, 24, 5, '15000.0000', '25000.0000', '24000.0000', '0.0000', '24000.0000', '1.0000', '24000.0000', '2025-05-03 21:42:21'),
(23, 25, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:44:49'),
(24, 26, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 21:45:48'),
(25, 27, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 22:25:55'),
(26, 28, 5, '15000.0000', '25000.0000', '25000.0000', '0.0000', '25000.0000', '1.0000', '25000.0000', '2025-05-03 22:25:55'),
(27, 29, 5, '15000.0000', '25000.0000', '22000.0000', '0.0000', '22000.0000', '1.0000', '22000.0000', '2025-05-03 22:27:44'),
(28, 30, 1, '816.6700', '2850.0000', '2850.0000', '0.0000', '2850.0000', '1.0000', '2850.0000', '2025-05-04 10:12:01'),
(29, 31, 1, '816.6700', '2850.0000', '2850.0000', '0.0000', '2850.0000', '1.0000', '2850.0000', '2025-05-04 10:15:45'),
(30, 32, 1, '816.6700', '2850.0000', '2850.0000', '0.0000', '2850.0000', '1.0000', '2850.0000', '2025-05-04 10:17:18'),
(31, 33, 2, '3350.0000', '4150.0000', '4150.0000', '0.0000', '4150.0000', '1.0000', '4150.0000', '2025-05-04 10:18:09');

-- --------------------------------------------------------

--
-- Table structure for table `sales_invoice_payments`
--

DROP TABLE IF EXISTS `sales_invoice_payments`;
CREATE TABLE IF NOT EXISTS `sales_invoice_payments` (
  `invoice_payment_id` int NOT NULL AUTO_INCREMENT,
  `invoice_id` int NOT NULL,
  `type` varchar(12) NOT NULL,
  `cardoption_id` int NOT NULL,
  `return_id` int NOT NULL,
  `gift_card_id` int NOT NULL,
  `credit_date` date NOT NULL,
  `cheque_bank` varchar(32) NOT NULL,
  `cheque_date` date NOT NULL,
  `cheque_no` varchar(32) NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `amount_balance` decimal(14,4) NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`invoice_payment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_invoice_payments`
--

INSERT INTO `sales_invoice_payments` (`invoice_payment_id`, `invoice_id`, `type`, `cardoption_id`, `return_id`, `gift_card_id`, `credit_date`, `cheque_bank`, `cheque_date`, `cheque_no`, `amount`, `amount_balance`, `created_on`) VALUES
(1, 1, 'GIFT CARD', 0, 0, 1, '0000-00-00', '', '0000-00-00', '', '4000.0000', '0.0000', '2025-05-03 20:25:18'),
(2, 1, 'RETURN', 0, 1, 0, '0000-00-00', '', '0000-00-00', '', '18000.0000', '0.0000', '2025-05-03 20:26:14'),
(3, 3, 'GIFT CARD', 0, 0, 1, '0000-00-00', '', '0000-00-00', '', '500.0000', '0.0000', '2025-05-03 20:40:14'),
(4, 4, 'GIFT CARD', 0, 0, 1, '0000-00-00', '', '0000-00-00', '', '500.0000', '0.0000', '2025-05-03 20:40:14'),
(5, 5, 'GIFT CARD', 0, 0, 1, '0000-00-00', '', '0000-00-00', '', '500.0000', '0.0000', '2025-05-03 20:40:14'),
(6, 6, 'GIFT CARD', 0, 0, 1, '0000-00-00', '', '0000-00-00', '', '500.0000', '0.0000', '2025-05-03 20:40:14'),
(7, 7, 'GIFT CARD', 0, 0, 1, '0000-00-00', '', '0000-00-00', '', '500.0000', '0.0000', '2025-05-03 20:40:14'),
(8, 7, 'RETURN', 0, 1, 0, '0000-00-00', '', '0000-00-00', '', '2350.0000', '0.0000', '2025-05-03 20:47:32'),
(9, 8, 'GIFT CARD', 0, 0, 1, '0000-00-00', '', '0000-00-00', '', '500.0000', '0.0000', '2025-05-03 21:05:55'),
(10, 8, 'CASH', 0, 0, 0, '0000-00-00', '', '0000-00-00', '', '24500.0000', '0.0000', '2025-05-03 21:05:59'),
(11, 9, 'GIFT CARD', 0, 0, 1, '0000-00-00', '', '0000-00-00', '', '500.0000', '0.0000', '2025-05-03 21:06:47'),
(12, 9, 'CASH', 0, 0, 0, '0000-00-00', '', '0000-00-00', '', '24500.0000', '0.0000', '2025-05-03 21:06:51'),
(13, 10, 'GIFT CARD', 0, 0, 1, '0000-00-00', '', '0000-00-00', '', '500.0000', '0.0000', '2025-05-03 21:07:12'),
(14, 10, 'CASH', 0, 0, 0, '0000-00-00', '', '0000-00-00', '', '24500.0000', '0.0000', '2025-05-03 21:07:17'),
(15, 11, 'CASH', 0, 0, 0, '0000-00-00', '', '0000-00-00', '', '25000.0000', '0.0000', '2025-05-03 21:09:32'),
(16, 14, 'CASH', 0, 0, 0, '0000-00-00', '', '0000-00-00', '', '25000.0000', '0.0000', '2025-05-03 21:17:29'),
(17, 15, 'CARD', 3, 0, 0, '0000-00-00', '', '0000-00-00', '', '25000.0000', '0.0000', '2025-05-03 21:18:03'),
(18, 16, 'CARD', 3, 0, 0, '0000-00-00', '', '0000-00-00', '', '25000.0000', '0.0000', '2025-05-03 21:20:00'),
(19, 17, 'CASH', 0, 0, 0, '0000-00-00', '', '0000-00-00', '', '25000.0000', '0.0000', '2025-05-03 21:23:28'),
(20, 18, 'CREDIT', 0, 0, 0, '2025-07-01', '', '0000-00-00', '', '25000.0000', '0.0000', '2025-05-03 21:24:16'),
(21, 19, 'CREDIT', 0, 0, 0, '2025-06-02', '', '0000-00-00', '', '25000.0000', '0.0000', '2025-05-03 21:25:13'),
(22, 20, 'CASH', 0, 0, 0, '0000-00-00', '', '0000-00-00', '', '25000.0000', '0.0000', '2025-05-03 21:38:32'),
(23, 21, 'CASH', 0, 0, 0, '0000-00-00', '', '0000-00-00', '', '25000.0000', '0.0000', '2025-05-03 21:38:49'),
(24, 22, 'CREDIT', 0, 0, 0, '2026-06-01', '', '0000-00-00', '', '25000.0000', '0.0000', '2025-05-03 21:39:39'),
(25, 23, 'CREDIT', 0, 0, 0, '2026-01-01', '', '0000-00-00', '', '25000.0000', '0.0000', '2025-05-03 21:41:47'),
(26, 24, 'CREDIT', 0, 0, 0, '2026-05-05', '', '0000-00-00', '', '24000.0000', '0.0000', '2025-05-03 21:42:42'),
(27, 25, 'CHEQUE', 0, 0, 0, '0000-00-00', '1', '2026-06-06', '123', '25000.0000', '0.0000', '2025-05-03 21:45:03'),
(28, 26, 'CHEQUE', 0, 0, 0, '0000-00-00', '1', '2026-06-02', '123', '25000.0000', '0.0000', '2025-05-03 21:46:03'),
(29, 27, 'CASH', 0, 0, 0, '0000-00-00', '', '0000-00-00', '', '25000.0000', '0.0000', '2025-05-03 22:25:57'),
(30, 28, 'CASH', 0, 0, 0, '0000-00-00', '', '0000-00-00', '', '25000.0000', '0.0000', '2025-05-03 22:25:57'),
(31, 29, 'LOYALTY', 0, 0, 0, '0000-00-00', '', '0000-00-00', '', '500.0000', '0.0000', '2025-05-03 22:27:58'),
(32, 29, 'CASH', 0, 0, 0, '0000-00-00', '', '0000-00-00', '', '21500.0000', '0.0000', '2025-05-03 22:28:02'),
(33, 30, 'CHEQUE', 0, 0, 0, '0000-00-00', '1', '2025-06-22', '12345', '2850.0000', '0.0000', '2025-05-04 10:12:22'),
(34, 31, 'CHEQUE', 0, 0, 0, '0000-00-00', '1', '2025-05-05', 'fdfdfdf', '2850.0000', '0.0000', '2025-05-04 10:16:21'),
(35, 32, 'CHEQUE', 0, 0, 0, '0000-00-00', '123', '2025-08-08', '12345678', '2850.0000', '0.0000', '2025-05-04 10:17:35'),
(36, 33, 'CHEQUE', 0, 0, 0, '0000-00-00', '987', '2025-10-10', '666666', '4150.0000', '0.0000', '2025-05-04 10:18:25');

-- --------------------------------------------------------

--
-- Table structure for table `sales_pending_invoices`
--

DROP TABLE IF EXISTS `sales_pending_invoices`;
CREATE TABLE IF NOT EXISTS `sales_pending_invoices` (
  `invoice_id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `cashier_point_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `sales_rep_id` int NOT NULL,
  `invoice_no` varchar(12) NOT NULL,
  `added_date` datetime NOT NULL,
  `total_sale` decimal(14,4) NOT NULL,
  `discount_type` varchar(12) NOT NULL,
  `discount_value` decimal(14,4) NOT NULL,
  `discount_amount` decimal(14,4) NOT NULL,
  `total_sale_cost` decimal(14,4) NOT NULL,
  `total_paid` decimal(14,4) NOT NULL,
  `cash_sales` decimal(14,4) NOT NULL,
  `card_sales` decimal(14,4) NOT NULL,
  `return_sales` decimal(14,4) NOT NULL,
  `gift_card_sales` decimal(14,4) NOT NULL,
  `loyalty_sales` decimal(14,4) NOT NULL,
  `credit_sales` decimal(14,4) NOT NULL,
  `cheque_sales` decimal(14,4) NOT NULL,
  `comments` text NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_pending_invoices`
--

INSERT INTO `sales_pending_invoices` (`invoice_id`, `location_id`, `user_id`, `cashier_point_id`, `customer_id`, `sales_rep_id`, `invoice_no`, `added_date`, `total_sale`, `discount_type`, `discount_value`, `discount_amount`, `total_sale_cost`, `total_paid`, `cash_sales`, `card_sales`, `return_sales`, `gift_card_sales`, `loyalty_sales`, `credit_sales`, `cheque_sales`, `comments`, `status`) VALUES
(28, 1, 1, 1, 1, 1, '', '2025-05-04 10:18:26', '0.0000', '', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales_pending_invoice_items`
--

DROP TABLE IF EXISTS `sales_pending_invoice_items`;
CREATE TABLE IF NOT EXISTS `sales_pending_invoice_items` (
  `invoice_item_id` int NOT NULL AUTO_INCREMENT,
  `invoice_id` int NOT NULL,
  `item_id` int NOT NULL,
  `cost` decimal(14,4) NOT NULL,
  `master_price` decimal(14,4) NOT NULL,
  `price` decimal(14,4) NOT NULL,
  `discount` decimal(14,4) NOT NULL,
  `unit_price` decimal(14,4) NOT NULL,
  `qty` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`invoice_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_pending_invoice_payments`
--

DROP TABLE IF EXISTS `sales_pending_invoice_payments`;
CREATE TABLE IF NOT EXISTS `sales_pending_invoice_payments` (
  `invoice_payment_id` int NOT NULL AUTO_INCREMENT,
  `invoice_id` int NOT NULL,
  `type` varchar(12) NOT NULL,
  `cardoption_id` int NOT NULL,
  `return_id` int NOT NULL,
  `gift_card_id` int NOT NULL,
  `credit_date` date NOT NULL,
  `cheque_bank` varchar(32) NOT NULL,
  `cheque_date` date NOT NULL,
  `cheque_no` varchar(32) NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `amount_balance` decimal(14,4) NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`invoice_payment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_rep`
--

DROP TABLE IF EXISTS `sales_rep`;
CREATE TABLE IF NOT EXISTS `sales_rep` (
  `rep_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`rep_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_rep`
--

INSERT INTO `sales_rep` (`rep_id`, `name`, `status`) VALUES
(1, 'NONE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales_return`
--

DROP TABLE IF EXISTS `sales_return`;
CREATE TABLE IF NOT EXISTS `sales_return` (
  `sales_return_id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `invoice_no` varchar(32) NOT NULL,
  `remarks` text NOT NULL,
  `total_cost` decimal(14,4) NOT NULL,
  `total_value` decimal(14,4) NOT NULL,
  `used_value` decimal(14,4) NOT NULL,
  `balance_value` decimal(14,4) NOT NULL,
  `no_of_items` decimal(14,4) NOT NULL,
  `no_of_qty` decimal(14,4) NOT NULL,
  PRIMARY KEY (`sales_return_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_return`
--

INSERT INTO `sales_return` (`sales_return_id`, `location_id`, `customer_id`, `user_id`, `added_date`, `invoice_no`, `remarks`, `total_cost`, `total_value`, `used_value`, `balance_value`, `no_of_items`, `no_of_qty`) VALUES
(1, 1, 2, 1, '2025-05-03', 'sdfsd', 'fsdfdsf', '8166.7000', '28500.0000', '20350.0000', '8150.0000', '1.0000', '10.0000');

-- --------------------------------------------------------

--
-- Table structure for table `sales_return_items`
--

DROP TABLE IF EXISTS `sales_return_items`;
CREATE TABLE IF NOT EXISTS `sales_return_items` (
  `sales_return_item_id` int NOT NULL AUTO_INCREMENT,
  `sales_return_id` int NOT NULL,
  `item_id` int NOT NULL,
  `qty` decimal(14,4) NOT NULL,
  `cost` decimal(14,4) NOT NULL,
  `price` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL,
  `expiriy_date` date NOT NULL,
  PRIMARY KEY (`sales_return_item_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_return_items`
--

INSERT INTO `sales_return_items` (`sales_return_item_id`, `sales_return_id`, `item_id`, `qty`, `cost`, `price`, `total`, `expiriy_date`) VALUES
(1, 1, 1, '10.0000', '816.6700', '2850.0000', '28500.0000', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `sales_shifts`
--

DROP TABLE IF EXISTS `sales_shifts`;
CREATE TABLE IF NOT EXISTS `sales_shifts` (
  `shift_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `cashier_point_id` int NOT NULL,
  `start_on` datetime NOT NULL,
  `end_on` datetime NOT NULL,
  PRIMARY KEY (`shift_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_shifts`
--

INSERT INTO `sales_shifts` (`shift_id`, `user_id`, `cashier_point_id`, `start_on`, `end_on`) VALUES
(1, 1, 1, '2025-05-02 15:21:15', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `secure_users`
--

DROP TABLE IF EXISTS `secure_users`;
CREATE TABLE IF NOT EXISTS `secure_users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `group_id` int NOT NULL,
  `location_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `loginRedirectTo` varchar(32) NOT NULL,
  `token` text NOT NULL,
  `lastLogin` datetime NOT NULL,
  `thisLogin` datetime NOT NULL,
  `cost_show` int NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `secure_users`
--

INSERT INTO `secure_users` (`user_id`, `group_id`, `location_id`, `name`, `username`, `password`, `loginRedirectTo`, `token`, `lastLogin`, `thisLogin`, `cost_show`, `status`) VALUES
(1, 1, 1, 'Shihan', 'shihan', '$2y$10$TpAg7F4fQAaBeGarOqb4VeCq1YuMaEIfxofhcsBiYNnQBaBYLNSxS', 'common/dashboard', 'bdf2a17b7d3c8c5acf30c23f2456be6d6e7eff61088c2282f1d5efa2c99ee4fb', '2025-05-05 10:36:20', '2025-05-05 21:56:06', 1, 1),
(2, 1, 1, 'mazha', 'mazha', '12345678', 'common/dashboard', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 1),
(3, 1, 1, 'zanab', 'zanab', '$2y$10$LGt9dcDN7NyISSAbGLPHqe103Ktj3HDBwF5B4ShSqgdmXWcsk/POa', 'common/dashboard', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `secure_users_groups`
--

DROP TABLE IF EXISTS `secure_users_groups`;
CREATE TABLE IF NOT EXISTS `secure_users_groups` (
  `group_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `secure_users_groups`
--

INSERT INTO `secure_users_groups` (`group_id`, `name`, `status`) VALUES
(1, 'ADMIN', 1);

-- --------------------------------------------------------

--
-- Table structure for table `secure_users_log`
--

DROP TABLE IF EXISTS `secure_users_log`;
CREATE TABLE IF NOT EXISTS `secure_users_log` (
  `log_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `log_datetime` datetime NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=248 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `secure_users_log`
--

INSERT INTO `secure_users_log` (`log_id`, `user_id`, `log_datetime`, `details`) VALUES
(1, 1, '2025-05-02 15:11:11', 'Usergroup Created: ADMIN'),
(2, 1, '2025-05-02 15:12:26', 'Account Created: BACK OFFICE'),
(3, 1, '2025-05-02 15:12:45', 'Account Created: CASHIER 01'),
(4, 1, '2025-05-02 15:13:00', 'Account Created: SAMPATH BANK'),
(5, 1, '2025-05-02 15:13:09', 'Account Created: COMMERCIAL BANK'),
(6, 1, '2025-05-02 15:14:16', 'Location Created: COLOMBO'),
(7, 1, '2025-05-02 15:14:50', 'Location Created: DIKWELLA'),
(8, 1, '2025-05-02 15:16:39', 'Locations Updated: DIKWELLA'),
(9, 1, '2025-05-02 15:17:19', 'Cashier Point Created: CMB CASHIER 001'),
(10, 1, '2025-05-02 15:17:35', 'Accounts Updated: CMB CASHIER 01'),
(11, 1, '2025-05-02 15:17:44', 'Account Created: DK CASHIER 01'),
(12, 1, '2025-05-02 15:18:31', 'Cashier Point Created: DK CASHIER 01'),
(13, 1, '2025-05-02 15:20:53', 'Users Updated: Shihan'),
(14, 1, '2025-05-02 15:21:15', 'Shift Started'),
(15, 1, '2025-05-02 15:27:45', 'Supplier Created: NONE'),
(16, 1, '2025-05-02 15:28:00', 'Customer Created: NONE'),
(17, 1, '2025-05-02 15:28:07', 'Customers Updated: NONE'),
(18, 1, '2025-05-02 15:28:22', 'Rep Created: NONE'),
(19, 1, '2025-05-02 15:28:57', 'Account Adjustment Created: AADJ-00001'),
(20, 1, '2025-05-02 16:39:36', 'Supplier Created: sgdsgsdgsdgd'),
(21, 1, '2025-05-02 17:13:18', 'Supplier Created: zoka'),
(22, 1, '2025-05-02 17:14:01', 'Supplier Created: bana'),
(23, 1, '2025-05-02 17:14:59', 'Supplier Created: ssssdd'),
(24, 1, '2025-05-02 17:17:00', 'Supplier Created: sdfsdfdsf'),
(25, 1, '2025-05-02 17:18:16', 'Supplier Created: fasfasafsaf'),
(26, 1, '2025-05-02 17:19:18', 'Supplier Created: dhfdhdfhdfh'),
(27, 1, '2025-05-02 17:19:34', 'Supplier Created: xcbxcbxcxb'),
(28, 1, '2025-05-02 17:19:48', 'Supplier Created: d4dfggdg'),
(29, 1, '2025-05-02 17:22:21', 'Supplier Created: dsfsdf sdfdsf'),
(30, 1, '2025-05-02 17:31:20', 'Suppliers Updated: LHD IMPORTERS'),
(31, 1, '2025-05-02 17:31:28', 'Suppliers Updated: DB AUTOMATION'),
(32, 1, '2025-05-02 17:31:34', 'Suppliers Updated: DB AUTOMATION'),
(33, 1, '2025-05-02 17:31:55', 'Suppliers Updated: SOFTLOGICS'),
(34, 1, '2025-05-02 17:32:09', 'Suppliers Updated: BARCLAYS'),
(35, 1, '2025-05-02 17:32:22', 'Suppliers Updated: ESOFT'),
(36, 1, '2025-05-02 17:32:29', 'Suppliers Updated: LHD IMPORTERS'),
(37, 1, '2025-05-02 17:32:43', 'Suppliers Updated: SAGONA MANG'),
(38, 1, '2025-05-02 17:32:49', 'Suppliers Updated: SAGONA MANG'),
(39, 1, '2025-05-02 17:32:58', 'Suppliers Updated: K ZONE'),
(40, 1, '2025-05-02 17:33:12', 'Suppliers Updated: TECH ZONE'),
(41, 1, '2025-05-02 17:33:19', 'Suppliers Updated: TECH ZONE'),
(42, 1, '2025-05-02 17:33:28', 'Suppliers Updated: NONE'),
(43, 1, '2025-05-02 17:33:39', 'Suppliers Updated: INFO SYS'),
(44, 1, '2025-05-02 17:33:53', 'Suppliers Updated: ZAKA ENTER'),
(45, 1, '2025-05-02 17:34:06', 'Suppliers Updated: INFO SYS'),
(46, 1, '2025-05-02 17:34:11', 'Suppliers Updated: K ZONE'),
(47, 1, '2025-05-02 18:38:18', 'User signed in successfully.'),
(48, 1, '2025-05-02 18:47:19', 'Category Created: Battery'),
(49, 1, '2025-05-02 18:47:39', 'Category Updated: PRINTERS'),
(50, 1, '2025-05-02 18:47:54', 'Category Created: POWER &amp; SUPPLY'),
(51, 1, '2025-05-02 18:48:10', 'Category Created: MONITORS'),
(52, 1, '2025-05-02 18:48:56', 'Category Created: NETWORK'),
(53, 1, '2025-05-02 18:49:20', 'Category Created: ACCESSORIES'),
(54, 1, '2025-05-02 18:49:44', 'Category Created: Printing Paper'),
(55, 1, '2025-05-02 18:49:57', 'Category Created: LASOR PRINTER'),
(56, 1, '2025-05-02 18:50:13', 'Category Created: POS PRINTER'),
(57, 1, '2025-05-02 18:50:24', 'Category Created: UPS'),
(58, 1, '2025-05-02 18:50:34', 'Category Created: BATERY'),
(59, 1, '2025-05-02 18:50:48', 'Category Created: LCD'),
(60, 1, '2025-05-02 18:50:58', 'Category Created: LED'),
(61, 1, '2025-05-02 18:51:07', 'Category Created: ROUTER'),
(62, 1, '2025-05-02 18:51:18', 'Category Created: CABLES'),
(63, 1, '2025-05-02 18:51:34', 'Category Created: PEN DRIVE'),
(64, 1, '2025-05-02 18:51:48', 'Category Created: KEYBOARDS'),
(65, 1, '2025-05-02 18:52:10', 'Unit Created: PCS'),
(66, 1, '2025-05-02 18:52:23', 'Brand Created: NONE'),
(67, 1, '2025-05-02 18:52:33', 'Warranty Created: NO WARRANTY'),
(68, 1, '2025-05-02 19:13:59', 'Item Created: Keyboard A4 Tech Km-720 Usb (1y)'),
(69, 1, '2025-05-02 19:16:45', 'Item Created: Keyboard A4 Tech Km72620 Usb Combo (1y)'),
(70, 1, '2025-05-02 19:17:37', 'Item Created: Keyboard Armaggeddon Mka-11r Rgb (6m)'),
(71, 1, '2025-05-02 19:18:48', 'Item Created: Keyboard Asus Rog Falchion Rx W/L (1y)'),
(72, 1, '2025-05-02 19:40:40', 'Receivingnotes Updated: RN-00001'),
(73, 1, '2025-05-02 19:42:29', 'Receivingnote Created: RN-00002'),
(74, 1, '2025-05-02 19:47:53', 'Receivingnote Created: RN-00006'),
(75, 1, '2025-05-02 19:48:16', 'Receivingnotes Updated: RN-00006'),
(76, 1, '2025-05-02 19:48:27', 'Receivingnotes Updated: RN-00006'),
(77, 1, '2025-05-02 19:48:37', 'Receivingnotes Updated: RN-00006'),
(78, 1, '2025-05-02 19:51:47', 'Receivingnotes Updated: RN-00006'),
(79, 1, '2025-05-02 19:57:04', 'Receivingnote Created: RN-00001'),
(80, 1, '2025-05-02 20:01:19', 'Receivingnotes Updated: RN-00001'),
(81, 1, '2025-05-02 20:02:20', 'Receivingnotes Updated: RN-00001'),
(82, 1, '2025-05-02 20:03:04', 'Receivingnotes Updated: RN-00001'),
(83, 1, '2025-05-02 20:08:36', 'Receivingnotes Updated: RN-00001'),
(84, 1, '2025-05-02 20:17:21', 'Receivingnotes Updated: RN-00001'),
(85, 1, '2025-05-02 20:19:05', 'Receivingnotes Updated: RN-00001'),
(86, 1, '2025-05-02 20:21:50', 'Receivingnotes Updated: RN-00001'),
(87, 1, '2025-05-02 20:22:07', 'Receivingnotes Updated: RN-00001'),
(88, 1, '2025-05-02 20:30:06', 'Receivingnote Created: RN-00002'),
(89, 1, '2025-05-02 20:33:40', 'Receivingnotes Updated: RN-00002'),
(90, 1, '2025-05-02 20:33:50', 'Receivingnotes Updated: RN-00002'),
(91, 1, '2025-05-02 20:41:33', 'Receivingnotes Updated: RN-00002'),
(92, 1, '2025-05-02 20:42:44', 'Return Note Created: RETN-00001'),
(93, 1, '2025-05-02 20:42:57', 'Return Notes Updated: RETN-00001'),
(94, 1, '2025-05-02 20:44:17', 'Return Notes Updated: RETN-00001'),
(95, 1, '2025-05-02 20:45:06', 'Return Note Created: RETN-00002'),
(96, 1, '2025-05-02 20:46:19', 'Return Notes Updated: RETN-00002'),
(97, 1, '2025-05-02 20:47:17', 'Return Notes Updated: RETN-00002'),
(98, 1, '2025-05-02 20:47:30', 'Return Notes Updated: RETN-00002'),
(99, 1, '2025-05-02 20:52:39', 'Transfer Note Created: TRN-00001'),
(100, 1, '2025-05-02 20:53:25', 'Transfer Notes Updated: TRN-00001'),
(101, 1, '2025-05-02 20:55:26', 'Transfer Notes Updated: TRN-00001'),
(102, 1, '2025-05-02 20:56:53', 'Adjustment Note Created: ADJ-00001'),
(103, 1, '2025-05-02 20:58:03', 'Adjustment Notes Updated: ADJ-00001'),
(104, 1, '2025-05-02 21:01:17', 'Adjustment Note Created: ADJ-00002'),
(105, 1, '2025-05-02 21:05:11', 'Adjustment Notes Updated: ADJ-00002'),
(106, 1, '2025-05-02 21:05:44', 'Adjustment Notes Updated: ADJ-00002'),
(107, 1, '2025-05-02 21:07:23', 'Item Created: LCD MONITOR 17'),
(108, 1, '2025-05-02 21:08:07', 'Receivingnote Created: RN-00003'),
(109, 1, '2025-05-02 22:06:23', 'User signed in successfully.'),
(110, 1, '2025-05-02 22:31:16', 'Account Adjustment Created: AADJ-00002'),
(111, 1, '2025-05-02 22:42:46', 'Supplier Payment Created: SPMNT-00001'),
(112, 1, '2025-05-02 22:49:44', 'Supplier Payment Updated: SPMNT-00001'),
(113, 1, '2025-05-02 22:50:17', 'Supplier Payment Updated: SPMNT-00001'),
(114, 1, '2025-05-02 22:50:43', 'Supplier Payment Updated: SPMNT-00001'),
(115, 1, '2025-05-02 23:02:46', 'Supplier Credit note Created: SCN-00001'),
(116, 1, '2025-05-02 23:03:04', 'Supplier Credit Note Updated: SCN-00001'),
(117, 1, '2025-05-02 23:08:33', 'Supplier Debit note Created: SDN-00001'),
(118, 1, '2025-05-02 23:08:57', 'Supplier Debit Note Updated: SDN-00001'),
(119, 1, '2025-05-02 23:22:15', 'Customer Created: ZAMEER'),
(120, 1, '2025-05-02 23:22:22', 'Customers Updated: ZAMEER'),
(121, 1, '2025-05-02 23:22:49', 'Customer Debit note Created: CDN-00001'),
(122, 1, '2025-05-02 23:23:39', 'Customer Debit note Created: CDN-00002'),
(123, 1, '2025-05-02 23:24:11', 'Customer Debit Note Updated: CDN-00002'),
(124, 1, '2025-05-02 23:27:39', 'Customer Debit Note Updated: CDN-00002'),
(125, 1, '2025-05-02 23:47:58', 'Customer Credit note Created: CCN-00001'),
(126, 1, '2025-05-02 23:48:06', 'Customer Credit Note Updated: CCN-00001'),
(127, 1, '2025-05-02 23:54:43', 'Customer Settlement Created: CSETT-00001'),
(128, 1, '2025-05-02 23:55:24', 'Customer Settlement Updated: CSETT-00001'),
(129, 1, '2025-05-02 23:57:00', 'Payee Created: NONE'),
(130, 1, '2025-05-02 23:57:06', 'Payee Updated: NONE'),
(131, 1, '2025-05-02 23:57:15', 'Payee Created: DIALOG'),
(132, 1, '2025-05-02 23:57:31', 'Expences Type Created: UTILITIES'),
(133, 1, '2025-05-03 00:00:08', 'Account Expence Created: AEXP-00001'),
(134, 1, '2025-05-03 00:00:29', 'Account Expence Updated: AEXP-00001'),
(135, 1, '2025-05-03 00:09:26', 'Account Transfer Created: ATRN-00001'),
(136, 1, '2025-05-03 00:11:20', 'Account Transfer Updated: ATRN-00001'),
(137, 1, '2025-05-03 02:18:17', 'Master Created: loyalty_points'),
(138, 1, '2025-05-03 10:54:45', 'User signed in successfully.'),
(139, 1, '2025-05-03 11:30:02', 'Quotation Created: QTE-00001'),
(140, 1, '2025-05-03 11:31:23', 'Quotations Updated: QTE-00001'),
(141, 1, '2025-05-03 12:48:31', 'Quotations Updated: QTE-00001'),
(142, 1, '2025-05-03 12:50:19', 'Quotations Updated: QTE-00001'),
(143, 1, '2025-05-03 12:50:26', 'Quotations Updated: QTE-00001'),
(144, 1, '2025-05-03 12:54:21', 'Quotations Updated: QTE-00001'),
(145, 1, '2025-05-03 12:54:54', 'Quotations Updated: QTE-00001'),
(146, 1, '2025-05-03 12:55:05', 'Quotations Updated: QTE-00001'),
(147, 1, '2025-05-03 12:55:47', 'Quotations Updated: QTE-00001'),
(148, 1, '2025-05-03 13:04:09', 'Quotations Updated: QTE-00001'),
(149, 1, '2025-05-03 13:05:33', 'Quotations Updated: QTE-00001'),
(150, 1, '2025-05-03 20:03:46', 'User signed in successfully.'),
(151, 1, '2025-05-03 20:04:46', 'Gift Card Created: abcdefg'),
(152, 1, '2025-05-03 20:05:02', 'Gift Card Created: hijklm'),
(153, 1, '2025-05-03 20:12:18', 'Sales Return Created: SRN-00001'),
(154, 1, '2025-05-03 20:14:10', 'Customergroup Created: GOLD'),
(155, 1, '2025-05-03 20:14:44', 'Customers Updated: ZAMEER'),
(156, 1, '2025-05-03 20:14:57', 'Items Group Price Updated: LCD MONITOR 17'),
(157, 1, '2025-05-03 20:20:51', 'Items Updated: LCD MONITOR 17'),
(158, 1, '2025-05-03 21:40:03', 'Customers Updated: ZAMEER'),
(159, 1, '2025-05-03 21:56:22', 'Master Created: LOYALTY_POINTS_CASH'),
(160, 1, '2025-05-03 21:56:43', 'Master Updated: loyalty_points_cash1'),
(161, 1, '2025-05-03 21:56:47', 'Master Updated: loyalty_points_cash'),
(162, 1, '2025-05-03 21:57:03', 'Master Created: loyalty_points_card'),
(163, 1, '2025-05-03 22:15:14', 'Master Created: loyalty_points_return'),
(164, 1, '2025-05-03 22:15:44', 'Master Created: loyalty_points_gift_card'),
(165, 1, '2025-05-03 22:16:13', 'Master Created: loyalty_points_credit'),
(166, 1, '2025-05-03 22:19:38', 'Master Created: loyalty_points_loyalty'),
(167, 1, '2025-05-03 22:23:26', 'Master Created: loyalty_points_cheque'),
(168, 1, '2025-05-03 22:41:10', 'Account Transfer Created: ATRN-00002'),
(169, 1, '2025-05-03 22:44:21', 'Account Transfer Created: ATRN-00003'),
(170, 1, '2025-05-03 22:45:30', 'Account Transfer Created: ATRN-00004'),
(171, 1, '2025-05-03 22:46:26', 'Account Transfer Created: ATRN-00005'),
(172, 1, '2025-05-03 22:51:11', 'Account Transfer Created: ATRN-00006'),
(173, 1, '2025-05-04 09:24:53', 'User signed in successfully.'),
(174, 1, '2025-05-04 11:53:27', 'Account Expence Updated: AEXP-00001'),
(175, 1, '2025-05-04 11:58:30', 'Account Expence Created: AEXP-00002'),
(176, 1, '2025-05-04 11:59:15', 'Account Expence Created: AEXP-00003'),
(177, 1, '2025-05-04 11:59:48', 'Account Expence Created: AEXP-00004'),
(178, 1, '2025-05-04 12:02:33', 'Account Expence Created: AEXP-00005'),
(179, 1, '2025-05-04 12:04:13', 'Account Expence Created: AEXP-00006'),
(180, 1, '2025-05-05 10:36:20', 'User signed in successfully.'),
(181, 1, '2025-05-05 11:29:41', 'Account Expence Updated: AEXP-00005'),
(182, 1, '2025-05-05 11:31:40', 'Account Expence Updated: AEXP-00005'),
(183, 1, '2025-05-05 11:32:06', 'Account Expence Updated: AEXP-00001'),
(184, 1, '2025-05-05 11:32:19', 'Account Expence Updated: AEXP-00005'),
(185, 1, '2025-05-05 11:32:42', 'Account Expence Updated: AEXP-00005'),
(186, 1, '2025-05-05 11:33:10', 'Account Expence Updated: AEXP-00005'),
(187, 1, '2025-05-05 11:33:25', 'Account Expence Updated: AEXP-00005'),
(188, 1, '2025-05-05 11:40:54', 'Account Expence Updated: AEXP-00005'),
(189, 1, '2025-05-05 11:42:00', 'Account Expence Updated: AEXP-00005'),
(190, 1, '2025-05-05 11:44:17', 'Account Expence Updated: AEXP-00005'),
(191, 1, '2025-05-05 11:46:52', 'Account Expence Updated: AEXP-00005'),
(192, 1, '2025-05-05 11:47:34', 'Account Expence Updated: AEXP-00005'),
(193, 1, '2025-05-05 11:48:51', 'Account Expence Updated: AEXP-00005'),
(194, 1, '2025-05-05 11:49:25', 'Account Expence Updated: AEXP-00005'),
(195, 1, '2025-05-05 11:49:50', 'Account Expence Updated: AEXP-00005'),
(196, 1, '2025-05-05 11:53:55', 'Account Expence Updated: AEXP-00005'),
(197, 1, '2025-05-05 11:54:15', 'Account Expence Updated: AEXP-00005'),
(198, 1, '2025-05-05 11:55:34', 'Account Expence Updated: AEXP-00005'),
(199, 1, '2025-05-05 11:57:00', 'Account Expence Updated: AEXP-00005'),
(200, 1, '2025-05-05 11:58:55', 'Account Expence Updated: AEXP-00005'),
(201, 1, '2025-05-05 12:02:29', 'Account Expence Updated: AEXP-00005'),
(202, 1, '2025-05-05 12:02:51', 'Account Expence Updated: AEXP-00005'),
(203, 1, '2025-05-05 12:03:39', 'Account Expence Updated: AEXP-00005'),
(204, 1, '2025-05-05 12:03:48', 'Account Expence Updated: AEXP-00005'),
(205, 1, '2025-05-05 12:04:18', 'Account Expence Updated: AEXP-00005'),
(206, 1, '2025-05-05 12:04:35', 'Account Expence Updated: AEXP-00005'),
(207, 1, '2025-05-05 12:05:14', 'Account Expence Updated: AEXP-00005'),
(208, 1, '2025-05-05 12:05:41', 'Account Expence Updated: AEXP-00005'),
(209, 1, '2025-05-05 12:14:35', 'Account Expence Updated: AEXP-00005'),
(210, 1, '2025-05-05 12:35:14', 'Account Expence Updated: AEXP-00005'),
(211, 1, '2025-05-05 12:35:36', 'Account Expence Updated: AEXP-00005'),
(212, 1, '2025-05-05 12:36:42', 'Account Expence Updated: AEXP-00005'),
(213, 1, '2025-05-05 12:58:12', 'Account Expence Updated: AEXP-00005'),
(214, 1, '2025-05-05 12:58:40', 'Account Expence Updated: AEXP-00005'),
(215, 1, '2025-05-05 12:59:22', 'Account Expence Updated: AEXP-00005'),
(216, 1, '2025-05-05 13:14:05', 'Account Expence Updated: AEXP-00005'),
(217, 1, '2025-05-05 13:14:44', 'Account Expence Updated: AEXP-00005'),
(218, 1, '2025-05-05 13:15:03', 'Account Expence Updated: AEXP-00005'),
(219, 1, '2025-05-05 13:16:08', 'Account Expence Updated: AEXP-00005'),
(220, 1, '2025-05-05 13:17:34', 'Account Expence Updated: AEXP-00005'),
(221, 1, '2025-05-05 13:17:58', 'Account Expence Updated: AEXP-00005'),
(222, 1, '2025-05-05 13:21:49', 'Account Expence Updated: AEXP-00004'),
(223, 1, '2025-05-05 13:22:13', 'Account Expence Updated: AEXP-00004'),
(224, 1, '2025-05-05 13:22:53', 'Account Expence Updated: AEXP-00004'),
(225, 1, '2025-05-05 14:13:49', 'Account Expence Updated: AEXP-00006'),
(226, 1, '2025-05-05 14:14:04', 'Account Expence Updated: AEXP-00006'),
(227, 1, '2025-05-05 19:32:40', 'Customer Settlement Created: CSETT-00002'),
(228, 1, '2025-05-05 19:34:07', 'Customer Settlement Created: CSETT-00003'),
(229, 1, '2025-05-05 19:35:44', 'Customer Settlement Created: CSETT-00004'),
(230, 1, '2025-05-05 19:36:54', 'Customer Settlement Created: CSETT-00005'),
(231, 1, '2025-05-05 20:03:20', 'Customer Settlement Updated: CSETT-00005'),
(232, 1, '2025-05-05 20:03:27', 'Customer Settlement Updated: CSETT-00005'),
(233, 1, '2025-05-05 20:04:18', 'Customer Settlement Updated: CSETT-00005'),
(234, 1, '2025-05-05 20:07:59', 'Customer Settlement Updated: CSETT-00005'),
(235, 1, '2025-05-05 20:14:47', 'Customer Settlement Updated: CSETT-00005'),
(236, 1, '2025-05-05 20:14:54', 'Customer Settlement Updated: CSETT-00005'),
(237, 1, '2025-05-05 20:24:49', 'Customer Settlement Updated: CSETT-00005'),
(238, 1, '2025-05-05 20:25:01', 'Customer Settlement Updated: CSETT-00005'),
(239, 1, '2025-05-05 20:25:50', 'Customer Settlement Updated: CSETT-00005'),
(240, 1, '2025-05-05 20:37:14', 'Customer Settlement Updated: CSETT-00005'),
(241, 1, '2025-05-05 20:43:31', 'Customer Settlement Updated: CSETT-00005'),
(242, 1, '2025-05-05 20:55:31', 'Supplier Payment Created: SPMNT-00002'),
(243, 1, '2025-05-05 20:57:27', 'Supplier Payment Created: SPMNT-00003'),
(244, 1, '2025-05-05 20:58:21', 'Supplier Payment Created: SPMNT-00004'),
(245, 1, '2025-05-05 20:59:53', 'Supplier Payment Updated: SPMNT-00004'),
(246, 1, '2025-05-05 21:19:07', 'Supplier Payment Created: SPMNT-00005'),
(247, 1, '2025-05-05 21:56:06', 'User signed in successfully.');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_credit_notes`
--

DROP TABLE IF EXISTS `suppliers_credit_notes`;
CREATE TABLE IF NOT EXISTS `suppliers_credit_notes` (
  `credit_note_id` int NOT NULL AUTO_INCREMENT,
  `supplier_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`credit_note_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suppliers_credit_notes`
--

INSERT INTO `suppliers_credit_notes` (`credit_note_id`, `supplier_id`, `location_id`, `user_id`, `added_date`, `amount`, `details`) VALUES
(1, 10, 1, 1, '2025-05-02', '15000.0000', 'dfdfdfdfd');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_debit_notes`
--

DROP TABLE IF EXISTS `suppliers_debit_notes`;
CREATE TABLE IF NOT EXISTS `suppliers_debit_notes` (
  `debit_note_id` int NOT NULL AUTO_INCREMENT,
  `supplier_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`debit_note_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suppliers_debit_notes`
--

INSERT INTO `suppliers_debit_notes` (`debit_note_id`, `supplier_id`, `location_id`, `user_id`, `added_date`, `amount`, `details`) VALUES
(1, 10, 1, 1, '2025-05-02', '15000.0000', 'fgdgdfgfdgf');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_payments`
--

DROP TABLE IF EXISTS `suppliers_payments`;
CREATE TABLE IF NOT EXISTS `suppliers_payments` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `supplier_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `account_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `cheque_no` varchar(32) NOT NULL,
  `cheque_date` date NOT NULL,
  `details` text NOT NULL,
  PRIMARY KEY (`payment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suppliers_payments`
--

INSERT INTO `suppliers_payments` (`payment_id`, `supplier_id`, `location_id`, `user_id`, `account_id`, `added_date`, `amount`, `cheque_no`, `cheque_date`, `details`) VALUES
(1, 10, 1, 1, 1, '2025-05-02', '53500.0000', '', '0000-00-00', 'fsdgdsgdsg'),
(2, 11, 1, 1, 1, '2025-05-05', '200.0000', '', '0000-00-00', 'sdsdsd'),
(3, 11, 2, 1, 1, '2025-05-05', '5000.0000', '123', '0000-00-00', 'sdsdsd'),
(4, 11, 1, 1, 1, '2025-05-05', '6000.0000', '', '0000-00-00', 'sfsfsfs'),
(5, 11, 2, 1, 3, '2025-05-05', '5000.0000', '12345', '2025-01-01', 'dfdfd');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_suppliers`
--

DROP TABLE IF EXISTS `suppliers_suppliers`;
CREATE TABLE IF NOT EXISTS `suppliers_suppliers` (
  `supplier_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `contact_person` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `phone_number` varchar(64) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(64) NOT NULL,
  `state` varchar(64) NOT NULL,
  `country` int NOT NULL,
  `payment_terms` varchar(64) NOT NULL,
  `bank_details` text NOT NULL,
  `tax_number` varchar(64) NOT NULL,
  `closing_balance` decimal(14,4) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`supplier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suppliers_suppliers`
--

INSERT INTO `suppliers_suppliers` (`supplier_id`, `name`, `contact_person`, `email`, `phone_number`, `address`, `city`, `state`, `country`, `payment_terms`, `bank_details`, `tax_number`, `closing_balance`, `status`) VALUES
(1, 'NONE', 'NONE', '', '', '', '', '', 0, '', '', '', '0.0000', 1),
(2, 'K ZONE', '', '', '', '', '', '', 0, '', '', '', '0.0000', 1),
(3, 'ZAKA ENTER', '', '', '', '', '', '', 0, '', '', '', '0.0000', 1),
(4, 'LHD IMPORTERS', '', '', '', '', '', '', 0, '', '', '', '0.0000', 1),
(5, 'TECH ZONE', '', '', '', '', '', '', 0, '', '', '', '0.0000', 1),
(6, 'SAGONA MANG', 'SAGONA MANG', '', '', '', '', '', 0, '', '', '', '0.0000', 1),
(7, 'ESOFT', '', '', '', '', '', '', 0, '', '', '', '0.0000', 1),
(8, 'SOFTLOGICS', '', '', '', '', '', '', 0, '', '', '', '0.0000', 1),
(9, 'INFO SYS', '', '', '', '', '', '', 0, '', '', '', '0.0000', 1),
(10, 'DB AUTOMATION', '', '', '', '', '', '', 0, '', '', '', '110000.0000', 1),
(11, 'BARCLAYS', '', '', '', '', '', '', 0, '', '', '', '45000.0000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_transactions`
--

DROP TABLE IF EXISTS `supplier_transactions`;
CREATE TABLE IF NOT EXISTS `supplier_transactions` (
  `transaction_id` int NOT NULL AUTO_INCREMENT,
  `supplier_id` int NOT NULL,
  `reference_id` int NOT NULL,
  `added_date` date NOT NULL,
  `transaction_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `debit` decimal(14,4) NOT NULL,
  `credit` decimal(14,4) NOT NULL,
  `balance` decimal(14,4) NOT NULL,
  `remarks` text NOT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier_transactions`
--

INSERT INTO `supplier_transactions` (`transaction_id`, `supplier_id`, `reference_id`, `added_date`, `transaction_type`, `debit`, `credit`, `balance`, `remarks`) VALUES
(5, 11, 3, '2025-05-02', 'RN', '45000.0000', '0.0000', '61200.0000', 'RN-00003'),
(4, 11, 2, '2025-05-02', 'RN', '16200.0000', '0.0000', '16200.0000', 'RN-00002'),
(3, 10, 1, '2025-05-02', 'RN', '163500.0000', '0.0000', '163500.0000', 'RN-00001'),
(6, 10, 1, '2025-05-02', 'SPMNT', '0.0000', '53500.0000', '110000.0000', 'SPMNT-00001'),
(7, 10, 1, '2025-05-02', 'SCN', '15000.0000', '0.0000', '125000.0000', 'SCN-00001'),
(8, 10, 1, '2025-05-02', 'SDN', '0.0000', '15000.0000', '110000.0000', 'SDN-00001'),
(9, 11, 2, '2025-05-05', 'SPMNT', '0.0000', '200.0000', '61000.0000', 'SPMNT-00002'),
(10, 11, 3, '2025-05-05', 'SPMNT', '0.0000', '5000.0000', '56000.0000', 'SPMNT-00003'),
(11, 11, 4, '2025-05-05', 'SPMNT', '0.0000', '6000.0000', '50000.0000', 'SPMNT-00004'),
(12, 11, 5, '2025-05-05', 'SPMNT', '0.0000', '5000.0000', '45000.0000', 'SPMNT-00005');

-- --------------------------------------------------------

--
-- Table structure for table `system_cashierpoints`
--

DROP TABLE IF EXISTS `system_cashierpoints`;
CREATE TABLE IF NOT EXISTS `system_cashierpoints` (
  `cashierpoint_id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `cash_account_id` int NOT NULL,
  `transfer_account_id` int NOT NULL,
  `card_account_1_name` varchar(32) NOT NULL,
  `card_account_1_id` int NOT NULL,
  `card_account_2_name` varchar(32) NOT NULL,
  `card_account_2_id` int NOT NULL,
  `card_account_3_name` varchar(32) NOT NULL,
  `card_account_3_id` int NOT NULL,
  `card_account_4_name` varchar(32) NOT NULL,
  `card_account_4_id` int NOT NULL,
  `card_account_5_name` varchar(32) NOT NULL,
  `card_account_5_id` int NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`cashierpoint_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `system_cashierpoints`
--

INSERT INTO `system_cashierpoints` (`cashierpoint_id`, `location_id`, `name`, `cash_account_id`, `transfer_account_id`, `card_account_1_name`, `card_account_1_id`, `card_account_2_name`, `card_account_2_id`, `card_account_3_name`, `card_account_3_id`, `card_account_4_name`, `card_account_4_id`, `card_account_5_name`, `card_account_5_id`, `status`) VALUES
(1, 1, 'CMB CASHIER 001', 2, 1, 'VISA', 3, 'AMEX', 4, '', 0, '', 0, '', 0, 1),
(2, 2, 'DK CASHIER 01', 5, 1, 'VISA', 3, 'AMEX', 4, '', 0, '', 0, '', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_locations`
--

DROP TABLE IF EXISTS `system_locations`;
CREATE TABLE IF NOT EXISTS `system_locations` (
  `location_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `manager_id` int NOT NULL,
  `address` text NOT NULL,
  `phone_number` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `invoice_no_start` char(5) NOT NULL,
  `status` int NOT NULL,
  PRIMARY KEY (`location_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `system_locations`
--

INSERT INTO `system_locations` (`location_id`, `name`, `manager_id`, `address`, `phone_number`, `email`, `invoice_no_start`, `status`) VALUES
(1, 'COLOMBO', 0, '94/1E, SURAMYA PLACE, RAJA MAWATHA, RATMALANA', '0777904054', 'HELLO@POS.LK', 'CMB', 1),
(2, 'DIKWELLA', 0, '335, YONAKAPURA, DIKWELLA', '0412255353', 'HELLO@POS.LK', 'DK', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
