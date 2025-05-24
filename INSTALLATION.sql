-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 23, 2025 at 12:10 PM
-- Server version: 8.0.36-cll-lve
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omnissmarthub1_pos659a`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts_accounts`
--

CREATE TABLE `accounts_accounts` (
  `account_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `payment_method` int NOT NULL,
  `closing_balance` decimal(14,4) NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts_accounts`
--

INSERT INTO `accounts_accounts` (`account_id`, `name`, `payment_method`, `closing_balance`, `status`) VALUES
(1, 'BACK OFFICE', 0, 0.0000, 1),
(2, 'CASHIER 01', 1, 0.0000, 1),
(3, 'BANK', 1, 0.0000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `accounts_adjustments`
--

CREATE TABLE `accounts_adjustments` (
  `adjustment_id` int NOT NULL,
  `type` char(10) NOT NULL,
  `account_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `details` text NOT NULL,
  `is_other_income` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accounts_cheque_transactions`
--

CREATE TABLE `accounts_cheque_transactions` (
  `cheque_id` int NOT NULL,
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
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accounts_expences`
--

CREATE TABLE `accounts_expences` (
  `expence_id` int NOT NULL,
  `account_id` int NOT NULL,
  `location_id` int NOT NULL,
  `expences_type_id` int NOT NULL,
  `payee_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `cheque_no` varchar(32) NOT NULL,
  `cheque_date` date NOT NULL,
  `details` text NOT NULL,
  `user_id` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accounts_expences_types`
--

CREATE TABLE `accounts_expences_types` (
  `expences_type_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `accounts_payee`
--

CREATE TABLE `accounts_payee` (
  `payee_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `accounts_payee`
--

INSERT INTO `accounts_payee` (`payee_id`, `name`, `status`) VALUES
(1, 'NONE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `accounts_transfers`
--

CREATE TABLE `accounts_transfers` (
  `transfer_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `account_from_id` int NOT NULL,
  `account_to_id` int NOT NULL,
  `shift_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `details` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_transactions`
--

CREATE TABLE `account_transactions` (
  `transaction_id` int NOT NULL,
  `account_id` int NOT NULL,
  `reference_id` int NOT NULL,
  `added_date` date NOT NULL,
  `transaction_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `debit` decimal(14,4) NOT NULL,
  `credit` decimal(14,4) NOT NULL,
  `balance` decimal(14,4) NOT NULL,
  `remarks` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers_credit_notes`
--

CREATE TABLE `customers_credit_notes` (
  `credit_note_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers_customers`
--

CREATE TABLE `customers_customers` (
  `customer_id` int NOT NULL,
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
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers_customers`
--

INSERT INTO `customers_customers` (`customer_id`, `customer_group_id`, `name`, `phone_number`, `email`, `address`, `credit_limit`, `settlement_days`, `remarks`, `card_no`, `closing_balance`, `loyalty_points`, `status`) VALUES
(1, 0, 'NONE', '1000000000', '', '', 0.00, 0, '', '', 0.0000, 0.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `customers_debit_notes`
--

CREATE TABLE `customers_debit_notes` (
  `debit_note_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `details` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers_groups`
--

CREATE TABLE `customers_groups` (
  `customer_group_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers_settlements`
--

CREATE TABLE `customers_settlements` (
  `settlement_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `location_id` int NOT NULL,
  `account_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `bank_code` varchar(32) NOT NULL,
  `cheque_date` date NOT NULL,
  `cheque_no` varchar(32) NOT NULL,
  `details` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_loyalty_transactions`
--

CREATE TABLE `customer_loyalty_transactions` (
  `transaction_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `reference_id` int NOT NULL,
  `added_date` date NOT NULL,
  `transaction_type` varchar(64) NOT NULL,
  `debit` decimal(14,4) NOT NULL,
  `credit` decimal(14,4) NOT NULL,
  `balance` decimal(14,4) NOT NULL,
  `remarks` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_transactions`
--

CREATE TABLE `customer_transactions` (
  `transaction_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `reference_id` int NOT NULL,
  `added_date` date NOT NULL,
  `transaction_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `debit` decimal(14,4) NOT NULL,
  `credit` decimal(14,4) NOT NULL,
  `balance` decimal(14,4) NOT NULL,
  `remarks` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_adjustment_notes`
--

CREATE TABLE `inventory_adjustment_notes` (
  `adjustment_note_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `remarks` text NOT NULL,
  `no_of_items` decimal(14,4) NOT NULL,
  `no_of_qty` decimal(14,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_adjustment_note_items`
--

CREATE TABLE `inventory_adjustment_note_items` (
  `adjustment_note_item_id` int NOT NULL,
  `adjustment_note_id` int NOT NULL,
  `item_id` int NOT NULL,
  `type` char(5) NOT NULL,
  `qty` decimal(14,4) NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_brands`
--

CREATE TABLE `inventory_brands` (
  `brand_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_brands`
--

INSERT INTO `inventory_brands` (`brand_id`, `name`, `status`) VALUES
(1, 'NONE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_category`
--

CREATE TABLE `inventory_category` (
  `category_id` int NOT NULL,
  `parent_category_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `item_id` int NOT NULL,
  `category_id` int NOT NULL,
  `brand_id` int NOT NULL,
  `unit_id` int NOT NULL,
  `warranty_id` int NOT NULL,
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
  `unique_no` int NOT NULL,
  `subtract_stock` int NOT NULL,
  `closing_stocks` decimal(14,2) NOT NULL,
  `status` int NOT NULL,
  `kot_item` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items_customer_group_price`
--

CREATE TABLE `inventory_items_customer_group_price` (
  `iicgp_id` int NOT NULL,
  `customer_group_id` int NOT NULL,
  `item_id` int NOT NULL,
  `price` decimal(14,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_quotations`
--

CREATE TABLE `inventory_quotations` (
  `quotation_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `remarks` text NOT NULL,
  `no_of_items` decimal(14,4) NOT NULL,
  `no_of_qty` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_quotation_items`
--

CREATE TABLE `inventory_quotation_items` (
  `quotation_item_id` int NOT NULL,
  `quotation_id` int NOT NULL,
  `item_id` int NOT NULL,
  `qty` decimal(14,4) NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `discount` decimal(14,4) NOT NULL,
  `final_amount` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_receiving_notes`
--

CREATE TABLE `inventory_receiving_notes` (
  `receiving_note_id` int NOT NULL,
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
  `no_of_qty` decimal(14,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_receiving_note_items`
--

CREATE TABLE `inventory_receiving_note_items` (
  `receiving_note_item_id` int NOT NULL,
  `receiving_note_id` int NOT NULL,
  `item_id` int NOT NULL,
  `qty` decimal(14,0) NOT NULL,
  `price` decimal(14,0) NOT NULL,
  `buying_price` decimal(14,0) NOT NULL,
  `discount` decimal(14,0) NOT NULL,
  `final_price` decimal(14,0) NOT NULL,
  `total` decimal(14,4) NOT NULL,
  `expiriy_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_return_notes`
--

CREATE TABLE `inventory_return_notes` (
  `return_note_id` int NOT NULL,
  `location_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rn_no` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `added_date` date NOT NULL,
  `remarks` text NOT NULL,
  `total_value` decimal(14,4) NOT NULL,
  `no_of_items` int NOT NULL,
  `no_of_qty` decimal(14,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_return_note_items`
--

CREATE TABLE `inventory_return_note_items` (
  `return_note_item_id` int NOT NULL,
  `return_note_id` int NOT NULL,
  `item_id` int NOT NULL,
  `qty` decimal(14,4) NOT NULL,
  `price` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_stock_transactions`
--

CREATE TABLE `inventory_stock_transactions` (
  `transaction_id` int NOT NULL,
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
  `remarks` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transfer_notes`
--

CREATE TABLE `inventory_transfer_notes` (
  `transfer_note_id` int NOT NULL,
  `location_from_id` int NOT NULL,
  `location_to_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `remarks` text NOT NULL,
  `no_of_items` int NOT NULL,
  `no_of_qty` decimal(14,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transfer_note_items`
--

CREATE TABLE `inventory_transfer_note_items` (
  `transfer_note_item_id` int NOT NULL,
  `transfer_note_id` int NOT NULL,
  `item_id` int NOT NULL,
  `qty` decimal(14,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_unique_nos`
--

CREATE TABLE `inventory_unique_nos` (
  `unique_id` int NOT NULL,
  `item_id` int NOT NULL,
  `used_invoice_id` int NOT NULL,
  `added_date` date NOT NULL,
  `used_date` date NOT NULL,
  `unique_no` varchar(32) NOT NULL,
  `cost` decimal(14,4) NOT NULL,
  `remarks` text NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_units`
--

CREATE TABLE `inventory_units` (
  `unit_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_units`
--

INSERT INTO `inventory_units` (`unit_id`, `name`, `status`) VALUES
(1, 'PCS', 1);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_warranty`
--

CREATE TABLE `inventory_warranty` (
  `warranty_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_warranty`
--

INSERT INTO `inventory_warranty` (`warranty_id`, `name`, `status`) VALUES
(1, 'NO WARRANTY', 1);

-- --------------------------------------------------------

--
-- Table structure for table `master`
--

CREATE TABLE `master` (
  `master_id` int NOT NULL,
  `key` varchar(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `values` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `master`
--

INSERT INTO `master` (`master_id`, `key`, `values`) VALUES
(1, 'companyName', 'COMPANY NAME'),
(2, 'logo', 'company/logo-1747939796.png'),
(3, 'per_page_results', '50'),
(4, 'loyalty_points', '100=1'),
(5, 'loyalty_points_cash', '0'),
(6, 'loyalty_points_card', '0'),
(7, 'loyalty_points_return', '0'),
(8, 'loyalty_points_gift_card', '0'),
(9, 'loyalty_points_credit', '0'),
(10, 'loyalty_points_loyalty', '0'),
(11, 'loyalty_points_cheque', '0'),
(12, 'barcode_no_start', 'BC'),
(13, 'invoice_header', 'HEADER DESCRIPTION GOES HERE'),
(14, 'invoice_footer', 'Hope you liked our products and services, see you again soon !'),
(15, 'invoice_logo_print', '1'),
(16, 'return_print_header', 'HEADER DESCRIPTION GOES HERE'),
(17, 'return_print_footer', 'Hope you liked our products and services, see you again soon !'),
(18, 'users_limit', '10'),
(19, 'locations_limit', '10'),
(20, 'gift_cards', '0'),
(21, 'invoice_limits_per_month', '10000'),
(22, 'address', 'Ratmalana'),
(23, 'email', 'EMAIL@EMAIL.COM'),
(24, 'telephone1', '0777'),
(25, 'telephone2', ''),
(26, 'telephone3', ''),
(27, 'telephone4', ''),
(28, 'telephone5', ''),
(29, 'website', ''),
(30, 'main_store', '1'),
(31, 'invoice_no', 'OM'),
(32, 'invoice_logo', 'company/invoice_logo-1747939796.png');

-- --------------------------------------------------------

--
-- Table structure for table `sales_gift_cards`
--

CREATE TABLE `sales_gift_cards` (
  `gift_card_id` int NOT NULL,
  `added_date` date NOT NULL,
  `no` varchar(32) NOT NULL,
  `expiry_date` date NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `used_amount` decimal(14,2) NOT NULL,
  `balance_amount` decimal(14,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_invoices`
--

CREATE TABLE `sales_invoices` (
  `invoice_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `cashier_point_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `sales_rep_id` int NOT NULL,
  `invoice_no` varchar(12) NOT NULL,
  `added_date` datetime NOT NULL,
  `no_of_items` decimal(14,4) NOT NULL,
  `no_of_qty` decimal(14,4) NOT NULL,
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
  `printed` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_invoice_items`
--

CREATE TABLE `sales_invoice_items` (
  `invoice_item_id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `item_id` int NOT NULL,
  `cost` decimal(14,4) NOT NULL,
  `master_price` decimal(14,4) NOT NULL,
  `price` decimal(14,4) NOT NULL,
  `discount` decimal(14,4) NOT NULL,
  `unit_price` decimal(14,4) NOT NULL,
  `qty` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL,
  `unique_no` varchar(32) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_invoice_payments`
--

CREATE TABLE `sales_invoice_payments` (
  `invoice_payment_id` int NOT NULL,
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
  `created_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_pending_invoices`
--

CREATE TABLE `sales_pending_invoices` (
  `invoice_id` int NOT NULL,
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
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_pending_invoice_items`
--

CREATE TABLE `sales_pending_invoice_items` (
  `invoice_item_id` int NOT NULL,
  `invoice_id` int NOT NULL,
  `item_id` int NOT NULL,
  `cost` decimal(14,4) NOT NULL,
  `master_price` decimal(14,4) NOT NULL,
  `price` decimal(14,4) NOT NULL,
  `discount` decimal(14,4) NOT NULL,
  `unit_price` decimal(14,4) NOT NULL,
  `qty` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL,
  `unique_no` varchar(32) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_pending_invoice_payments`
--

CREATE TABLE `sales_pending_invoice_payments` (
  `invoice_payment_id` int NOT NULL,
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
  `created_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_rep`
--

CREATE TABLE `sales_rep` (
  `rep_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sales_rep`
--

INSERT INTO `sales_rep` (`rep_id`, `name`, `status`) VALUES
(1, 'NONE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `sales_return`
--

CREATE TABLE `sales_return` (
  `sales_return_id` int NOT NULL,
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
  `no_of_qty` decimal(14,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_return_items`
--

CREATE TABLE `sales_return_items` (
  `sales_return_item_id` int NOT NULL,
  `sales_return_id` int NOT NULL,
  `item_id` int NOT NULL,
  `qty` decimal(14,4) NOT NULL,
  `cost` decimal(14,4) NOT NULL,
  `price` decimal(14,4) NOT NULL,
  `total` decimal(14,4) NOT NULL,
  `expiriy_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_shifts`
--

CREATE TABLE `sales_shifts` (
  `shift_id` int NOT NULL,
  `user_id` int NOT NULL,
  `cashier_point_id` int NOT NULL,
  `start_on` datetime NOT NULL,
  `end_on` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `secure_users`
--

CREATE TABLE `secure_users` (
  `user_id` int NOT NULL,
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
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `secure_users`
--

INSERT INTO `secure_users` (`user_id`, `group_id`, `location_id`, `name`, `username`, `password`, `loginRedirectTo`, `token`, `lastLogin`, `thisLogin`, `cost_show`, `status`) VALUES
(1, 1, 1, 'SHIHAN', 'shihan', '$2y$10$TpAg7F4fQAaBeGarOqb4VeCq1YuMaEIfxofhcsBiYNnQBaBYLNSxS', 'common/dashboard', 'ac7d792f30edb080c168521dfc4f4205228ad8b04c74f99ff9c8bf4a59da96ca', '2025-05-23 11:45:53', '2025-05-23 11:58:43', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `secure_users_groups`
--

CREATE TABLE `secure_users_groups` (
  `group_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `permissions` text NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `secure_users_groups`
--

INSERT INTO `secure_users_groups` (`group_id`, `name`, `permissions`, `status`) VALUES
(1, 'ADMIN', '[{\"path\":1,\"permission\":[1,1,1,1]},{\"path\":2,\"permission\":[1,1,1,1]},{\"path\":3,\"permission\":[1,1,1,1]},{\"path\":4,\"permission\":[1,0,0,0]},{\"path\":5,\"permission\":[1,0,0,0]},{\"path\":6,\"permission\":[1,0,0,0]},{\"path\":7,\"permission\":[1,0,0,0]},{\"path\":8,\"permission\":[1,0,0,0]},{\"path\":9,\"permission\":[1,0,0,0]},{\"path\":10,\"permission\":[1,1,1,1]},{\"path\":11,\"permission\":[1,1,1,1]},{\"path\":12,\"permission\":[1,1,1,1]},{\"path\":13,\"permission\":[1,1,1,1]},{\"path\":14,\"permission\":[1,1,1,1]},{\"path\":15,\"permission\":[1,1,1,1]},{\"path\":16,\"permission\":[1,1,1,1]},{\"path\":17,\"permission\":[1,0,0,0]},{\"path\":18,\"permission\":[1,0,0,0]},{\"path\":19,\"permission\":[1,0,0,0]},{\"path\":20,\"permission\":[1,0,0,0]},{\"path\":21,\"permission\":[1,0,0,0]},{\"path\":22,\"permission\":[1,0,0,0]},{\"path\":23,\"permission\":[1,1,1,1]},{\"path\":24,\"permission\":[1,1,1,1]},{\"path\":25,\"permission\":[1,1,1,1]},{\"path\":26,\"permission\":[1,1,1,1]},{\"path\":27,\"permission\":[1,1,1,1]},{\"path\":28,\"permission\":[1,1,1,1]},{\"path\":29,\"permission\":[1,1,1,1]},{\"path\":30,\"permission\":[1,1,1,1]},{\"path\":31,\"permission\":[1,0,0,0]},{\"path\":32,\"permission\":[1,0,0,0]},{\"path\":33,\"permission\":[1,0,0,0]},{\"path\":34,\"permission\":[1,0,0,0]},{\"path\":35,\"permission\":[1,0,0,0]},{\"path\":36,\"permission\":[1,0,0,0]},{\"path\":37,\"permission\":[1,1,1,1]},{\"path\":38,\"permission\":[1,1,1,1]},{\"path\":39,\"permission\":[1,1,1,1]},{\"path\":40,\"permission\":[1,1,1,1]},{\"path\":41,\"permission\":[1,1,1,1]},{\"path\":42,\"permission\":[1,1,1,1]},{\"path\":43,\"permission\":[1,0,0,0]},{\"path\":44,\"permission\":[1,0,0,0]},{\"path\":45,\"permission\":[1,0,0,0]},{\"path\":46,\"permission\":[1,0,0,0]},{\"path\":47,\"permission\":[1,0,0,0]},{\"path\":48,\"permission\":[1,0,0,0]},{\"path\":49,\"permission\":[1,0,0,0]},{\"path\":50,\"permission\":[1,1,1,1]},{\"path\":51,\"permission\":[1,1,1,1]},{\"path\":52,\"permission\":[1,1,1,1]},{\"path\":53,\"permission\":[1,1,1,1]},{\"path\":54,\"permission\":[1,1,1,1]},{\"path\":55,\"permission\":[1,1,1,1]},{\"path\":56,\"permission\":[1,1,1,1]},{\"path\":57,\"permission\":[1,1,1,1]},{\"path\":58,\"permission\":[1,1,1,1]},{\"path\":59,\"permission\":[1,1,1,1]},{\"path\":60,\"permission\":[1,0,0,0]},{\"path\":61,\"permission\":[1,0,0,0]},{\"path\":62,\"permission\":[1,0,0,0]},{\"path\":63,\"permission\":[1,0,0,0]},{\"path\":64,\"permission\":[1,0,0,0]},{\"path\":65,\"permission\":[1,0,0,0]},{\"path\":66,\"permission\":[1,1,1,1]},{\"path\":67,\"permission\":[1,1,1,1]},{\"path\":68,\"permission\":[1,1,1,1]},{\"path\":69,\"permission\":[1,1,1,1]},{\"path\":70,\"permission\":[1,1,1,1]},{\"path\":71,\"permission\":[1,1,1,1]},{\"path\":72,\"permission\":[1,1,1,1]},{\"path\":73,\"permission\":[1,1,1,1]},{\"path\":74,\"permission\":[1,1,1,1]},{\"path\":75,\"permission\":[1,0,0,0]}]', 1);

-- --------------------------------------------------------

--
-- Table structure for table `secure_users_group_paths`
--

CREATE TABLE `secure_users_group_paths` (
  `path_id` int NOT NULL,
  `order_id` int NOT NULL,
  `path` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `access` int NOT NULL,
  `create` int NOT NULL,
  `edit` int NOT NULL,
  `delete` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `secure_users_group_paths`
--

INSERT INTO `secure_users_group_paths` (`path_id`, `order_id`, `path`, `access`, `create`, `edit`, `delete`) VALUES
(1, 0, 'accounts/master_accounts', 1, 1, 1, 1),
(2, 0, 'accounts/master_expencestypes', 1, 1, 1, 1),
(3, 0, 'accounts/master_payee', 1, 1, 1, 1),
(4, 0, 'accounts/r_adjustments', 1, 0, 0, 0),
(5, 0, 'accounts/r_cheques', 1, 0, 0, 0),
(6, 0, 'accounts/r_expences', 1, 0, 0, 0),
(7, 0, 'accounts/r_ledger_listing', 1, 0, 0, 0),
(8, 0, 'accounts/r_pnl', 1, 0, 0, 0),
(9, 0, 'accounts/r_transfers', 1, 0, 0, 0),
(10, 0, 'accounts/transaction_adjustments', 1, 1, 1, 1),
(11, 0, 'accounts/transaction_cheque', 1, 1, 1, 1),
(12, 0, 'accounts/transaction_expences', 1, 1, 1, 1),
(13, 0, 'accounts/transaction_transfers', 1, 1, 1, 1),
(14, 0, 'common/dashboard', 1, 1, 1, 1),
(15, 0, 'customers/master_customergroups', 1, 1, 1, 1),
(16, 0, 'customers/master_customers', 1, 1, 1, 1),
(17, 0, 'customers/r_creditnotes', 1, 0, 0, 0),
(18, 0, 'customers/r_customers', 1, 0, 0, 0),
(19, 0, 'customers/r_debitnotes', 1, 0, 0, 0),
(20, 0, 'customers/r_ledger', 1, 0, 0, 0),
(21, 0, 'customers/r_outstanding', 1, 0, 0, 0),
(22, 0, 'customers/r_settlements', 1, 0, 0, 0),
(23, 0, 'customers/transaction_creditnotes', 1, 1, 1, 1),
(24, 0, 'customers/transaction_debitnotes', 1, 1, 1, 1),
(25, 0, 'customers/transaction_settlements', 1, 1, 1, 1),
(26, 0, 'inventory/master_brands', 1, 1, 1, 1),
(27, 0, 'inventory/master_category', 1, 1, 1, 1),
(28, 0, 'inventory/master_items', 1, 1, 1, 1),
(29, 0, 'inventory/master_units', 1, 1, 1, 1),
(30, 0, 'inventory/master_warranty', 1, 1, 1, 1),
(31, 0, 'inventory/r_adjustment', 1, 0, 0, 0),
(32, 0, 'inventory/r_ledger', 1, 0, 0, 0),
(33, 0, 'inventory/r_receiving', 1, 0, 0, 0),
(34, 0, 'inventory/r_return', 1, 0, 0, 0),
(35, 0, 'inventory/r_stock_listing', 1, 0, 0, 0),
(36, 0, 'inventory/r_transfer', 1, 0, 0, 0),
(37, 0, 'inventory/transaction_adjustmentnotes', 1, 1, 1, 1),
(38, 0, 'inventory/transaction_receivingnotes', 1, 1, 1, 1),
(39, 0, 'inventory/transaction_returnnotes', 1, 1, 1, 1),
(40, 0, 'inventory/transaction_transfernotes', 1, 1, 1, 1),
(41, 0, 'inventory/transaction_uniquenos', 1, 1, 1, 1),
(42, 0, 'sales/master_rep', 1, 1, 1, 1),
(43, 0, 'sales/r_calcelled', 1, 0, 0, 0),
(44, 0, 'sales/r_dailysales', 1, 0, 0, 0),
(45, 0, 'sales/r_giftcard', 1, 0, 0, 0),
(46, 0, 'sales/r_loyalty', 1, 0, 0, 0),
(47, 0, 'sales/r_pos', 1, 0, 0, 0),
(48, 0, 'sales/r_saleslisting', 1, 0, 0, 0),
(49, 0, 'sales/r_salesreturn', 1, 0, 0, 0),
(50, 0, 'sales/screen', 1, 1, 1, 1),
(51, 0, 'sales/transaction_giftcards', 1, 1, 1, 1),
(52, 0, 'sales/transaction_invoices', 1, 1, 1, 1),
(53, 0, 'sales/transaction_kot_print', 1, 1, 1, 1),
(54, 0, 'sales/transaction_quotations', 1, 1, 1, 1),
(55, 0, 'sales/transaction_return', 1, 1, 1, 1),
(56, 0, 'secure/signin', 1, 1, 1, 1),
(57, 0, 'secure/signout', 1, 1, 1, 1),
(58, 0, 'secure/users', 1, 1, 1, 1),
(59, 0, 'suppliers/master_suppliers', 1, 1, 1, 1),
(60, 0, 'suppliers/r_creditnotes', 1, 0, 0, 0),
(61, 0, 'suppliers/r_debitnotes', 1, 0, 0, 0),
(62, 0, 'suppliers/r_ledger', 1, 0, 0, 0),
(63, 0, 'suppliers/r_outstanding', 1, 0, 0, 0),
(64, 0, 'suppliers/r_payments', 1, 0, 0, 0),
(65, 0, 'suppliers/r_suppliers', 1, 0, 0, 0),
(66, 0, 'suppliers/transaction_creditnotes', 1, 1, 1, 1),
(67, 0, 'suppliers/transaction_debitnotes', 1, 1, 1, 1),
(68, 0, 'suppliers/transaction_payments', 1, 1, 1, 1),
(69, 0, 'system/master_cashierpoints', 1, 1, 1, 1),
(70, 0, 'system/master_locations', 1, 1, 1, 1),
(71, 0, 'system/master_master', 1, 1, 1, 1),
(72, 0, 'system/master_usergroups', 1, 1, 1, 1),
(73, 0, 'system/master_usergroups_path', 1, 1, 1, 1),
(74, 0, 'system/master_users', 1, 1, 1, 1),
(75, 0, 'system/setup', 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `secure_users_log`
--

CREATE TABLE `secure_users_log` (
  `log_id` int NOT NULL,
  `user_id` int NOT NULL,
  `log_datetime` datetime NOT NULL,
  `details` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `secure_users_log`
--

INSERT INTO `secure_users_log` (`log_id`, `user_id`, `log_datetime`, `details`) VALUES
(1, 1, '2025-05-23 11:58:43', 'User signed in successfully.'),
(2, 1, '2025-05-23 12:01:40', 'Category Created: NONE'),
(3, 1, '2025-05-23 12:02:48', 'Brand Created: NONE'),
(4, 1, '2025-05-23 12:03:12', 'Payee Created: NONE'),
(5, 1, '2025-05-23 12:03:56', 'Customer Created: NONE'),
(6, 1, '2025-05-23 12:04:19', 'Supplier Created: NONE'),
(7, 1, '2025-05-23 12:06:53', 'Locations Updated: OFFICE 1'),
(8, 1, '2025-05-23 12:08:59', 'Cashier Point Created: CASHIER POINT 01');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_credit_notes`
--

CREATE TABLE `suppliers_credit_notes` (
  `credit_note_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_debit_notes`
--

CREATE TABLE `suppliers_debit_notes` (
  `debit_note_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `details` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_payments`
--

CREATE TABLE `suppliers_payments` (
  `payment_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `location_id` int NOT NULL,
  `user_id` int NOT NULL,
  `account_id` int NOT NULL,
  `added_date` date NOT NULL,
  `amount` decimal(14,4) NOT NULL,
  `cheque_no` varchar(32) NOT NULL,
  `cheque_date` date NOT NULL,
  `details` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers_suppliers`
--

CREATE TABLE `suppliers_suppliers` (
  `supplier_id` int NOT NULL,
  `name` varchar(64) NOT NULL,
  `contact_person` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `phone_number` varchar(64) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(64) NOT NULL,
  `state` varchar(64) NOT NULL,
  `country` char(5) NOT NULL,
  `payment_terms` varchar(64) NOT NULL,
  `bank_details` text NOT NULL,
  `tax_number` varchar(64) NOT NULL,
  `closing_balance` decimal(14,4) NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `suppliers_suppliers`
--

INSERT INTO `suppliers_suppliers` (`supplier_id`, `name`, `contact_person`, `email`, `phone_number`, `address`, `city`, `state`, `country`, `payment_terms`, `bank_details`, `tax_number`, `closing_balance`, `status`) VALUES
(1, 'NONE', 'NONE', '', '', '', '', '', '', '', '', '', 0.0000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_transactions`
--

CREATE TABLE `supplier_transactions` (
  `transaction_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `reference_id` int NOT NULL,
  `added_date` date NOT NULL,
  `transaction_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `debit` decimal(14,4) NOT NULL,
  `credit` decimal(14,4) NOT NULL,
  `balance` decimal(14,4) NOT NULL,
  `remarks` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `system_cashierpoints`
--

CREATE TABLE `system_cashierpoints` (
  `cashierpoint_id` int NOT NULL,
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
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `system_cashierpoints`
--

INSERT INTO `system_cashierpoints` (`cashierpoint_id`, `location_id`, `name`, `cash_account_id`, `transfer_account_id`, `card_account_1_name`, `card_account_1_id`, `card_account_2_name`, `card_account_2_id`, `card_account_3_name`, `card_account_3_id`, `card_account_4_name`, `card_account_4_id`, `card_account_5_name`, `card_account_5_id`, `status`) VALUES
(1, 1, 'CASHIER POINT 01', 2, 1, 'CARDS', 3, '', 0, '', 0, '', 0, '', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_locations`
--

CREATE TABLE `system_locations` (
  `location_id` int NOT NULL,
  `name` varchar(32) NOT NULL,
  `manager_id` int NOT NULL,
  `address` text NOT NULL,
  `phone_number` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `invoice_no_start` char(5) NOT NULL,
  `status` int NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `system_locations`
--

INSERT INTO `system_locations` (`location_id`, `name`, `manager_id`, `address`, `phone_number`, `email`, `invoice_no_start`, `status`) VALUES
(1, 'OFFICE 1', 0, 'COLOMBO', '0100000000', '', 'INV', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts_accounts`
--
ALTER TABLE `accounts_accounts`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `accounts_adjustments`
--
ALTER TABLE `accounts_adjustments`
  ADD PRIMARY KEY (`adjustment_id`);

--
-- Indexes for table `accounts_cheque_transactions`
--
ALTER TABLE `accounts_cheque_transactions`
  ADD PRIMARY KEY (`cheque_id`);

--
-- Indexes for table `accounts_expences`
--
ALTER TABLE `accounts_expences`
  ADD PRIMARY KEY (`expence_id`);

--
-- Indexes for table `accounts_expences_types`
--
ALTER TABLE `accounts_expences_types`
  ADD PRIMARY KEY (`expences_type_id`);

--
-- Indexes for table `accounts_payee`
--
ALTER TABLE `accounts_payee`
  ADD PRIMARY KEY (`payee_id`);

--
-- Indexes for table `accounts_transfers`
--
ALTER TABLE `accounts_transfers`
  ADD PRIMARY KEY (`transfer_id`);

--
-- Indexes for table `account_transactions`
--
ALTER TABLE `account_transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `customers_credit_notes`
--
ALTER TABLE `customers_credit_notes`
  ADD PRIMARY KEY (`credit_note_id`);

--
-- Indexes for table `customers_customers`
--
ALTER TABLE `customers_customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `customers_debit_notes`
--
ALTER TABLE `customers_debit_notes`
  ADD PRIMARY KEY (`debit_note_id`);

--
-- Indexes for table `customers_groups`
--
ALTER TABLE `customers_groups`
  ADD PRIMARY KEY (`customer_group_id`);

--
-- Indexes for table `customers_settlements`
--
ALTER TABLE `customers_settlements`
  ADD PRIMARY KEY (`settlement_id`);

--
-- Indexes for table `customer_loyalty_transactions`
--
ALTER TABLE `customer_loyalty_transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `customer_transactions`
--
ALTER TABLE `customer_transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `inventory_adjustment_notes`
--
ALTER TABLE `inventory_adjustment_notes`
  ADD PRIMARY KEY (`adjustment_note_id`);

--
-- Indexes for table `inventory_adjustment_note_items`
--
ALTER TABLE `inventory_adjustment_note_items`
  ADD PRIMARY KEY (`adjustment_note_item_id`);

--
-- Indexes for table `inventory_brands`
--
ALTER TABLE `inventory_brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `inventory_category`
--
ALTER TABLE `inventory_category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `inventory_items_customer_group_price`
--
ALTER TABLE `inventory_items_customer_group_price`
  ADD PRIMARY KEY (`iicgp_id`);

--
-- Indexes for table `inventory_quotations`
--
ALTER TABLE `inventory_quotations`
  ADD PRIMARY KEY (`quotation_id`);

--
-- Indexes for table `inventory_quotation_items`
--
ALTER TABLE `inventory_quotation_items`
  ADD PRIMARY KEY (`quotation_item_id`);

--
-- Indexes for table `inventory_receiving_notes`
--
ALTER TABLE `inventory_receiving_notes`
  ADD PRIMARY KEY (`receiving_note_id`);

--
-- Indexes for table `inventory_receiving_note_items`
--
ALTER TABLE `inventory_receiving_note_items`
  ADD PRIMARY KEY (`receiving_note_item_id`);

--
-- Indexes for table `inventory_return_notes`
--
ALTER TABLE `inventory_return_notes`
  ADD PRIMARY KEY (`return_note_id`);

--
-- Indexes for table `inventory_return_note_items`
--
ALTER TABLE `inventory_return_note_items`
  ADD PRIMARY KEY (`return_note_item_id`);

--
-- Indexes for table `inventory_stock_transactions`
--
ALTER TABLE `inventory_stock_transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `inventory_transfer_notes`
--
ALTER TABLE `inventory_transfer_notes`
  ADD PRIMARY KEY (`transfer_note_id`);

--
-- Indexes for table `inventory_transfer_note_items`
--
ALTER TABLE `inventory_transfer_note_items`
  ADD PRIMARY KEY (`transfer_note_item_id`);

--
-- Indexes for table `inventory_unique_nos`
--
ALTER TABLE `inventory_unique_nos`
  ADD PRIMARY KEY (`unique_id`);

--
-- Indexes for table `inventory_units`
--
ALTER TABLE `inventory_units`
  ADD PRIMARY KEY (`unit_id`);

--
-- Indexes for table `inventory_warranty`
--
ALTER TABLE `inventory_warranty`
  ADD PRIMARY KEY (`warranty_id`);

--
-- Indexes for table `master`
--
ALTER TABLE `master`
  ADD PRIMARY KEY (`master_id`);

--
-- Indexes for table `sales_gift_cards`
--
ALTER TABLE `sales_gift_cards`
  ADD PRIMARY KEY (`gift_card_id`);

--
-- Indexes for table `sales_invoices`
--
ALTER TABLE `sales_invoices`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `sales_invoice_items`
--
ALTER TABLE `sales_invoice_items`
  ADD PRIMARY KEY (`invoice_item_id`);

--
-- Indexes for table `sales_invoice_payments`
--
ALTER TABLE `sales_invoice_payments`
  ADD PRIMARY KEY (`invoice_payment_id`);

--
-- Indexes for table `sales_pending_invoices`
--
ALTER TABLE `sales_pending_invoices`
  ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `sales_pending_invoice_items`
--
ALTER TABLE `sales_pending_invoice_items`
  ADD PRIMARY KEY (`invoice_item_id`);

--
-- Indexes for table `sales_pending_invoice_payments`
--
ALTER TABLE `sales_pending_invoice_payments`
  ADD PRIMARY KEY (`invoice_payment_id`);

--
-- Indexes for table `sales_rep`
--
ALTER TABLE `sales_rep`
  ADD PRIMARY KEY (`rep_id`);

--
-- Indexes for table `sales_return`
--
ALTER TABLE `sales_return`
  ADD PRIMARY KEY (`sales_return_id`);

--
-- Indexes for table `sales_return_items`
--
ALTER TABLE `sales_return_items`
  ADD PRIMARY KEY (`sales_return_item_id`);

--
-- Indexes for table `sales_shifts`
--
ALTER TABLE `sales_shifts`
  ADD PRIMARY KEY (`shift_id`);

--
-- Indexes for table `secure_users`
--
ALTER TABLE `secure_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `secure_users_groups`
--
ALTER TABLE `secure_users_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `secure_users_group_paths`
--
ALTER TABLE `secure_users_group_paths`
  ADD PRIMARY KEY (`path_id`);

--
-- Indexes for table `secure_users_log`
--
ALTER TABLE `secure_users_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `suppliers_credit_notes`
--
ALTER TABLE `suppliers_credit_notes`
  ADD PRIMARY KEY (`credit_note_id`);

--
-- Indexes for table `suppliers_debit_notes`
--
ALTER TABLE `suppliers_debit_notes`
  ADD PRIMARY KEY (`debit_note_id`);

--
-- Indexes for table `suppliers_payments`
--
ALTER TABLE `suppliers_payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `suppliers_suppliers`
--
ALTER TABLE `suppliers_suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `supplier_transactions`
--
ALTER TABLE `supplier_transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `system_cashierpoints`
--
ALTER TABLE `system_cashierpoints`
  ADD PRIMARY KEY (`cashierpoint_id`);

--
-- Indexes for table `system_locations`
--
ALTER TABLE `system_locations`
  ADD PRIMARY KEY (`location_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts_accounts`
--
ALTER TABLE `accounts_accounts`
  MODIFY `account_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `accounts_adjustments`
--
ALTER TABLE `accounts_adjustments`
  MODIFY `adjustment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `accounts_cheque_transactions`
--
ALTER TABLE `accounts_cheque_transactions`
  MODIFY `cheque_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `accounts_expences`
--
ALTER TABLE `accounts_expences`
  MODIFY `expence_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `accounts_expences_types`
--
ALTER TABLE `accounts_expences_types`
  MODIFY `expences_type_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `accounts_payee`
--
ALTER TABLE `accounts_payee`
  MODIFY `payee_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `accounts_transfers`
--
ALTER TABLE `accounts_transfers`
  MODIFY `transfer_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_transactions`
--
ALTER TABLE `account_transactions`
  MODIFY `transaction_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers_credit_notes`
--
ALTER TABLE `customers_credit_notes`
  MODIFY `credit_note_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers_customers`
--
ALTER TABLE `customers_customers`
  MODIFY `customer_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers_debit_notes`
--
ALTER TABLE `customers_debit_notes`
  MODIFY `debit_note_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers_groups`
--
ALTER TABLE `customers_groups`
  MODIFY `customer_group_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers_settlements`
--
ALTER TABLE `customers_settlements`
  MODIFY `settlement_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_loyalty_transactions`
--
ALTER TABLE `customer_loyalty_transactions`
  MODIFY `transaction_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_transactions`
--
ALTER TABLE `customer_transactions`
  MODIFY `transaction_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_adjustment_notes`
--
ALTER TABLE `inventory_adjustment_notes`
  MODIFY `adjustment_note_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_adjustment_note_items`
--
ALTER TABLE `inventory_adjustment_note_items`
  MODIFY `adjustment_note_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_brands`
--
ALTER TABLE `inventory_brands`
  MODIFY `brand_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory_category`
--
ALTER TABLE `inventory_category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_items_customer_group_price`
--
ALTER TABLE `inventory_items_customer_group_price`
  MODIFY `iicgp_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_quotations`
--
ALTER TABLE `inventory_quotations`
  MODIFY `quotation_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_quotation_items`
--
ALTER TABLE `inventory_quotation_items`
  MODIFY `quotation_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_receiving_notes`
--
ALTER TABLE `inventory_receiving_notes`
  MODIFY `receiving_note_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_receiving_note_items`
--
ALTER TABLE `inventory_receiving_note_items`
  MODIFY `receiving_note_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_return_notes`
--
ALTER TABLE `inventory_return_notes`
  MODIFY `return_note_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_return_note_items`
--
ALTER TABLE `inventory_return_note_items`
  MODIFY `return_note_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_stock_transactions`
--
ALTER TABLE `inventory_stock_transactions`
  MODIFY `transaction_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_transfer_notes`
--
ALTER TABLE `inventory_transfer_notes`
  MODIFY `transfer_note_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_transfer_note_items`
--
ALTER TABLE `inventory_transfer_note_items`
  MODIFY `transfer_note_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_unique_nos`
--
ALTER TABLE `inventory_unique_nos`
  MODIFY `unique_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_units`
--
ALTER TABLE `inventory_units`
  MODIFY `unit_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory_warranty`
--
ALTER TABLE `inventory_warranty`
  MODIFY `warranty_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `master`
--
ALTER TABLE `master`
  MODIFY `master_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `sales_gift_cards`
--
ALTER TABLE `sales_gift_cards`
  MODIFY `gift_card_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_invoices`
--
ALTER TABLE `sales_invoices`
  MODIFY `invoice_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_invoice_items`
--
ALTER TABLE `sales_invoice_items`
  MODIFY `invoice_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_invoice_payments`
--
ALTER TABLE `sales_invoice_payments`
  MODIFY `invoice_payment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_pending_invoices`
--
ALTER TABLE `sales_pending_invoices`
  MODIFY `invoice_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_pending_invoice_items`
--
ALTER TABLE `sales_pending_invoice_items`
  MODIFY `invoice_item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `sales_pending_invoice_payments`
--
ALTER TABLE `sales_pending_invoice_payments`
  MODIFY `invoice_payment_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `sales_rep`
--
ALTER TABLE `sales_rep`
  MODIFY `rep_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sales_return`
--
ALTER TABLE `sales_return`
  MODIFY `sales_return_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_return_items`
--
ALTER TABLE `sales_return_items`
  MODIFY `sales_return_item_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_shifts`
--
ALTER TABLE `sales_shifts`
  MODIFY `shift_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `secure_users`
--
ALTER TABLE `secure_users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `secure_users_groups`
--
ALTER TABLE `secure_users_groups`
  MODIFY `group_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `secure_users_group_paths`
--
ALTER TABLE `secure_users_group_paths`
  MODIFY `path_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `secure_users_log`
--
ALTER TABLE `secure_users_log`
  MODIFY `log_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `suppliers_credit_notes`
--
ALTER TABLE `suppliers_credit_notes`
  MODIFY `credit_note_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers_debit_notes`
--
ALTER TABLE `suppliers_debit_notes`
  MODIFY `debit_note_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers_payments`
--
ALTER TABLE `suppliers_payments`
  MODIFY `payment_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers_suppliers`
--
ALTER TABLE `suppliers_suppliers`
  MODIFY `supplier_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supplier_transactions`
--
ALTER TABLE `supplier_transactions`
  MODIFY `transaction_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_cashierpoints`
--
ALTER TABLE `system_cashierpoints`
  MODIFY `cashierpoint_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `system_locations`
--
ALTER TABLE `system_locations`
  MODIFY `location_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
