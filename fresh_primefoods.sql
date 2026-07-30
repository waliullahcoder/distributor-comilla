-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 04, 2024 at 10:45 AM
-- Server version: 10.6.18-MariaDB-cll-lve
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `alifinef_primefoods`
--

-- --------------------------------------------------------

--
-- Table structure for table `abouts`
--

CREATE TABLE `abouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `faq_title` varchar(255) NOT NULL,
  `white_faq_name` varchar(255) NOT NULL,
  `white_faq_description` longtext NOT NULL,
  `black_faq_name` varchar(255) NOT NULL,
  `black_faq_description` longtext NOT NULL,
  `social_work_heading` varchar(255) NOT NULL,
  `social_work_title` varchar(255) NOT NULL,
  `social_work_description` longtext NOT NULL,
  `link` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `abouts`
--

INSERT INTO `abouts` (`id`, `title`, `description`, `faq_title`, `white_faq_name`, `white_faq_description`, `black_faq_name`, `black_faq_description`, `social_work_heading`, `social_work_title`, `social_work_description`, `link`, `created_at`, `updated_at`) VALUES
(1, 'A Brief on Primefoods', '<div>BRB Cable Industries Ltd. a private Limited Company was established with a view to manufacture Wires & Cables in 1978. After successful incorporation, BRB starts its commercial production in 1980. During the year 2000 BRB launched its PVC Cables Plant for producing up to 33KV Cables along with XLPE insulated HT Cables, FRLS Cables and Aluminum Conductors up to 132KV. Moreover, Super Enameled Copper Wire Plant & Instrumentation Cables were launched during this time. At this age BRB introduces C.C.V Plant (Catenary Continuous Vulcanization) for the first time in Bangladesh.</div><div>To meet up increasing demand in the market, the company starts producing AAAC, AAC & ACSR Conductor, XLPE & PVC Insulated LT & HT Cables, FRLS Cables, House and Appliances Wiring Cables, Dry & Jelly filled Telecommunication Cables, Instrumentation Cables, Aluminum Overhead Conductors, special type of Dual Coated Super Enameled Copper Wire, Marine Cables, Optical Fiber Cables (OFC), Miniature Circuit Breaker (MCB) and Ceiling Fan.</div><div>The products are approved by BSTI (Bangladesh Standards and Testing Institution) and certified by the world-renowned internationally reputed individual Testing laboratory CPRI (Central Power Research Institute), India.</div><div>The factory is situated at BSCIC Industrial Estate, 5 KM from Kushtia town and it is well equipped with modern machinery and equipments along with a group of qualified and experienced manpower to ensure the quality of the products and thus the company has earned fame in the country and its product has been approved and being used by major Govt. and Non Govt. organizations such as BPDB, REB, DESA, DESCO, BMDA, PWD, BTMC, BSFIC, T&T, MES, BADC, Bangladesh Port Authority, Bangladesh Railway, Autonomous bodies, Private sector, Industrial and Apartment projects and individuals.</div><div>The Company is marketing its products through more than 150 Sales centers to provide integrated supports to around 20,000 Cable Traders countrywide. It has a group of experienced personnel in its marketing & technical department who are well equipped to render service to its customers.<br></div>', 'From the history since 1978', 'OUR VISION', 'Being a leader in the cable industry of Bangladesh and a<br> global player our vision is to ensure to be constant in<br>  extending the dimensions of our business through transparent <br> business practices and striving towards new heights of excellence<br>  in service along with providing opportunities for growth and <br> enrichment to our employees, our business partners and the<br>  communities which we operate in. Besides of making sure of the<br>  best use of world-class performance of manpower, assets and<br>  investments, we are one of the privileged companies to be focusing <br> on a whole range of energy saving electrical protective devices.', 'OUR MISSION', '<table><tbody><tr><td class=\"line-content\">We are looking forward to continue the practice of <span class=\"html-tag\"><br></span> providing the guaranteed quality products and efficient  <span class=\"html-tag\"><br></span> service experience, which have made us the name that <span class=\"html-tag\"><br></span>  people can rely. Being a self-propelled organization we  <span class=\"html-tag\"><br></span> are not just about to carry our environment friendly steps <span class=\"html-tag\"><br></span>  but the best interest of our stakeholders and customers <span class=\"html-tag\"><br></span>  and their safety as well. We rely on world-class technology  <span class=\"html-tag\"><br></span> while pursuing continuous innovation to ensure the best  <span class=\"html-tag\"><br></span> possible solution practically for any cable need across the  <span class=\"html-tag\"><br></span> world and bring glory to our nation. </td></tr><tr><td class=\"line-number\" value=\"632\"></td><td class=\"line-content\"></td></tr></tbody></table>', 'SOCIAL WORKING', 'COMPANY - ALWAYS, CLOSE TO YOU.', '<div>We are proud to sponsor the Fundación Franlanqui, a non-profit organization founded more than 5 years ago, made up of a group of people dedicated to help other people who have become homeless or low-income people, by the donation of clothes, medicines, food, water or even economic resources needed to handle health issues. You can be a part of this!, Join us!</div><div><br></div>', 'https://salestrackerbd.com/collections', '2023-10-04 03:28:29', '2024-08-03 17:34:43');

-- --------------------------------------------------------

--
-- Table structure for table `access_logs`
--

CREATE TABLE `access_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date_time` timestamp NULL DEFAULT NULL,
  `page` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_transactions`
--

CREATE TABLE `account_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `account_transaction_auto_id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(255) NOT NULL,
  `voucher_type` varchar(20) NOT NULL,
  `voucher_date` date NOT NULL,
  `coa_setup_id` bigint(20) UNSIGNED NOT NULL,
  `coa_head_code` bigint(20) NOT NULL,
  `narration` text NOT NULL,
  `debit_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `credit_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `posted` tinyint(4) NOT NULL DEFAULT 0,
  `approve` tinyint(4) NOT NULL DEFAULT 0,
  `approve_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_transaction_autos`
--

CREATE TABLE `account_transaction_autos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `voucher_no` varchar(255) NOT NULL,
  `voucher_type` varchar(20) NOT NULL,
  `voucher_date` date NOT NULL,
  `coa_setup_id` bigint(20) UNSIGNED NOT NULL,
  `coa_head_code` bigint(20) NOT NULL,
  `narration` text NOT NULL,
  `debit_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `credit_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `posted` tinyint(4) NOT NULL DEFAULT 0,
  `approve` tinyint(4) NOT NULL DEFAULT 0,
  `approve_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_menus`
--

CREATE TABLE `admin_menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `route` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `delete` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_menus`
--

INSERT INTO `admin_menus` (`id`, `permission_id`, `parent_id`, `name`, `route`, `icon`, `order`, `status`, `delete`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Dashboard', 'admin.dashboard', '<i class=\"fad fa-tachometer-alt-fastest\"></i>', 1, 1, 1, '2023-09-19 09:04:28', '2023-09-19 09:04:28'),
(2, 2, NULL, 'System Setting', NULL, '<i class=\"fad fa-users-cog\"></i>', 2, 1, 1, '2023-09-19 09:05:17', '2023-09-19 09:05:17'),
(3, 3, 2, 'Company Setup', 'admin.company.index', NULL, 1, 1, 1, '2023-09-19 09:05:30', '2023-09-19 09:11:55'),
(4, 4, 2, 'Branch Setup', 'admin.branch.index', NULL, 2, 1, 1, '2023-09-19 09:05:39', '2023-09-19 09:12:52'),
(5, 5, 2, 'Role Setup', 'admin.role.index', NULL, 3, 1, 1, '2023-09-19 09:05:48', '2023-09-19 09:13:04'),
(6, 6, 2, 'User Setup', 'admin.user.index', NULL, 4, 1, 1, '2023-09-19 09:05:55', '2023-09-19 09:15:19'),
(7, 7, 2, 'Menu Setup', 'admin.admin-menu.index', NULL, 5, 1, 1, '2023-09-19 09:06:24', '2023-09-19 09:15:35'),
(8, 20, NULL, 'System Setup', NULL, '<i class=\"fad fa-tools\"></i>', 3, 1, 1, '2023-09-19 09:20:32', '2023-09-19 09:20:32'),
(9, 21, 8, 'Sales Group', 'admin.group.index', NULL, 5, 1, 1, '2023-09-19 09:20:46', '2023-09-25 07:50:45'),
(10, 22, 8, 'Store Setup', 'admin.store.index', NULL, 3, 1, 1, '2023-09-19 09:20:59', '2023-09-23 14:49:13'),
(11, 23, 8, 'Product Type', 'admin.category.index', NULL, 8, 1, 1, '2023-09-19 09:21:10', '2023-09-25 05:49:47'),
(12, 24, 8, 'Product Setup', 'admin.product.index', NULL, 9, 1, 1, '2023-09-19 09:21:19', '2023-09-25 14:14:03'),
(13, 25, 8, 'Staff Setup', 'admin.staff.index', NULL, 4, 1, 1, '2023-09-19 09:21:25', '2023-09-23 14:49:31'),
(14, 26, 8, 'Product List', 'admin.product-list.index', NULL, 10, 1, 1, '2023-09-19 09:21:36', '2023-09-30 06:36:17'),
(15, 27, NULL, 'System Configuration', NULL, '<i class=\"fad fa-sliders-h\"></i>', 3, 1, 1, '2023-09-19 09:22:02', '2024-07-31 03:23:21'),
(16, 28, 15, 'Group Sales Target', 'admin.group-target.index', NULL, 1, 1, 1, '2023-09-19 09:22:23', '2023-09-25 15:11:07'),
(17, 29, 15, 'Client Price Setup', 'admin.client-price.index', NULL, 2, 1, 1, '2023-09-19 09:22:33', '2023-09-29 15:44:08'),
(18, 30, NULL, 'Procurement', NULL, '<i class=\"fad fa-cogs\"></i>', 5, 1, 1, '2023-09-19 09:23:13', '2023-09-19 09:23:13'),
(19, 31, 18, 'Transaction', NULL, NULL, 1, 1, 1, '2023-09-19 09:23:22', '2023-09-19 09:23:22'),
(20, 32, 19, 'Vendor Setup', 'admin.vendor.index', NULL, 1, 1, 1, '2023-09-19 09:23:31', '2023-09-25 04:28:11'),
(21, 33, 19, 'Product Purchase', 'admin.lifting.index', NULL, 2, 1, 1, '2023-09-19 09:23:43', '2024-07-31 03:24:18'),
(22, 34, 19, 'Purchase Return', 'admin.lifting-return.index', NULL, 3, 1, 1, '2023-09-19 09:23:55', '2023-10-16 04:46:40'),
(23, 35, 19, 'Vendor Payment', 'admin.vendor-payment.index', NULL, 4, 1, 1, '2023-09-19 09:24:05', '2023-10-16 09:18:55'),
(24, 36, 18, 'Reports', NULL, NULL, 2, 1, 1, '2023-09-19 09:24:13', '2023-09-19 09:24:13'),
(25, 37, 24, 'Purchase History', 'admin.lifting-history.index', NULL, 1, 1, 1, '2023-09-19 09:24:28', '2024-07-31 03:25:52'),
(26, 38, 24, 'Return History', 'admin.lifting-return-history.index', NULL, 2, 1, 1, '2023-09-19 09:24:36', '2024-07-31 03:27:13'),
(27, 39, 24, 'Payment History', 'admin.vendor-payment-history.index', NULL, 3, 1, 1, '2023-09-19 09:24:54', '2024-07-31 03:27:00'),
(28, 40, 24, 'Vendor Statement', 'admin.vendor-statement.index', NULL, 4, 1, 1, '2023-09-19 09:25:06', '2023-10-26 08:58:42'),
(29, 41, NULL, 'Inventory', NULL, '<i class=\"fad fa-analytics\"></i>', 6, 1, 1, '2023-09-19 09:25:29', '2023-09-19 09:25:29'),
(30, 42, 29, 'Transaction', NULL, NULL, 1, 1, 1, '2023-09-19 09:25:38', '2023-09-19 09:25:38'),
(31, 43, 30, 'Product Transfer', 'admin.transfer.index', NULL, 1, 1, 1, '2023-09-19 09:25:55', '2023-10-18 05:08:37'),
(32, 44, 30, 'Transfer Receive', 'admin.transfer-receive.index', NULL, 2, 1, 1, '2023-09-19 09:26:05', '2023-10-18 09:46:37'),
(33, 45, 29, 'Reports', NULL, NULL, 2, 1, 1, '2023-09-19 09:26:14', '2023-09-19 09:26:14'),
(34, 46, 33, 'Closing Stock', NULL, NULL, 1, 0, 1, '2023-09-19 09:26:33', '2023-10-26 15:51:09'),
(35, 47, 33, 'Stock Status', 'admin.stock-status.index', NULL, 2, 1, 1, '2023-09-19 09:26:45', '2023-10-29 17:05:40'),
(36, 48, 33, 'Transfer Log', 'admin.transfer-log.index', NULL, 3, 1, 1, '2023-09-19 09:26:55', '2023-10-26 09:14:26'),
(37, 49, NULL, 'Sales Management', NULL, '<i class=\"fad fa-tasks\"></i>', 7, 1, 1, '2023-09-19 09:28:03', '2023-09-19 09:28:03'),
(38, 50, 37, 'Transaction', NULL, NULL, 1, 1, 1, '2023-09-19 09:28:16', '2023-09-19 09:28:16'),
(39, 51, 8, 'Region Setup', 'admin.region.index', NULL, 0, 1, 1, '2023-09-19 09:28:49', '2023-11-06 13:58:07'),
(40, 52, 8, 'Area Setup', 'admin.area.index', NULL, 1, 1, 1, '2023-09-19 09:29:03', '2023-09-23 14:48:28'),
(41, 53, 8, 'Territory Setup', 'admin.territory.index', NULL, 2, 1, 1, '2023-09-19 09:29:15', '2023-09-23 14:48:48'),
(42, 54, 38, 'Client Setup', 'admin.client.index', NULL, 0, 1, 1, '2023-09-19 09:29:28', '2023-10-13 04:45:32'),
(43, 55, 38, 'Daily Invoice', 'admin.sales.index', NULL, 3, 1, 1, '2023-09-19 09:29:42', '2023-11-20 16:56:35'),
(44, 56, 38, 'Daily Collection', 'admin.collection.index', NULL, 6, 1, 1, '2023-09-19 09:29:54', '2023-10-14 15:58:54'),
(45, 57, 38, 'Bulk Collection', 'admin.bulk-collection.index', NULL, 7, 0, 1, '2023-09-19 09:30:07', '2024-07-31 03:28:05'),
(46, 58, 38, 'Sales Return', 'admin.sales-return.index', NULL, 8, 1, 1, '2023-09-19 09:30:21', '2023-10-15 06:42:46'),
(47, 59, 38, 'Return Approval', 'admin.return-approve.index', NULL, 9, 1, 1, '2023-09-19 09:30:32', '2023-10-18 12:04:00'),
(48, 60, 37, 'Reports', NULL, NULL, 2, 1, 1, '2023-09-19 09:30:42', '2023-09-19 09:30:42'),
(49, 61, 48, 'Sales History', 'admin.sales-history.index', NULL, 1, 1, 1, '2023-09-19 09:30:51', '2024-07-31 03:30:07'),
(50, 62, 48, 'Collection History', 'admin.collection-history.index', NULL, 2, 1, 1, '2023-09-19 09:31:08', '2024-07-31 03:30:14'),
(51, 63, 48, 'Return History', 'admin.return-history.index', NULL, 3, 1, 1, '2023-09-19 09:31:21', '2024-07-31 03:30:22'),
(52, 64, 48, 'Client Statement', 'admin.client-statement.index', NULL, 4, 1, 1, '2023-09-19 09:31:39', '2023-10-26 08:51:35'),
(53, 65, 48, 'Client List', 'admin.client-list.index', NULL, 0, 1, 1, '2023-09-19 09:31:52', '2023-11-14 06:20:12'),
(54, 66, NULL, 'Business Analysis', NULL, '<i class=\"fad fa-chart-pie\"></i>', 9, 1, 1, '2023-09-19 09:33:29', '2023-12-13 12:58:49'),
(55, 67, 54, 'Payment Analysis', 'admin.lifting-realization.index', NULL, 1, 1, 1, '2023-09-19 09:33:39', '2023-10-29 17:06:44'),
(56, 68, 33, 'Stock Valuation', 'admin.stock-valuation.index', NULL, 2, 1, 1, '2023-09-19 09:33:47', '2023-10-29 17:05:51'),
(57, 69, 54, 'Target & Achievement', 'admin.sales-target-achivement.index', NULL, 3, 1, 1, '2023-09-19 09:33:56', '2024-07-31 03:33:39'),
(58, 70, 54, 'Sales Contribution', 'admin.sales-contribution.index', NULL, 4, 1, 1, '2023-09-19 09:34:06', '2023-10-29 17:07:36'),
(59, 71, 54, 'Sales Analysis', 'admin.sales-realization.index', NULL, 5, 1, 1, '2023-09-19 09:34:14', '2024-07-31 03:34:16'),
(60, 72, 54, 'Sales Ageing Report', 'admin.sales-ageing.index', NULL, 6, 1, 1, '2023-09-19 09:34:41', '2023-10-29 17:08:39'),
(61, 73, 54, 'Client Outstanding', 'admin.client-outstanding.index', NULL, 7, 1, 1, '2023-09-19 09:34:53', '2023-10-29 17:09:15'),
(62, 74, 54, 'Product Profit', 'admin.product-wise-profit.index', NULL, 8, 1, 1, '2023-09-19 09:35:10', '2023-11-02 10:03:23'),
(63, 75, NULL, 'Online Order', '#', '<i class=\"fad fa-boxes\"></i>', 10, 1, 1, '2023-09-19 09:36:07', '2023-12-13 12:59:18'),
(64, 76, 38, 'Offline Order', 'admin.offline-order.index', NULL, 1, 1, 1, '2023-09-19 09:36:18', '2023-10-09 03:42:43'),
(66, 78, 38, 'Prepare Gatepass', 'admin.delivery.index', NULL, 4, 1, 1, '2023-09-19 09:38:15', '2023-11-20 16:56:08'),
(68, 80, 48, 'Delivery Statement', 'admin.delivery-statement.index', NULL, 10, 1, 1, '2023-09-19 09:38:34', '2023-10-26 08:52:30'),
(69, 81, 8, 'Client Chain', 'admin.chain-client.index', NULL, 7, 1, 1, '2023-09-23 10:02:09', '2023-09-25 04:14:41'),
(70, 82, 8, 'Client Type', 'admin.client-category.index', NULL, 6, 1, 1, '2023-09-23 10:02:20', '2023-09-23 14:51:25'),
(71, 168, 8, 'Vehicle Setup', 'admin.vehicle.index', NULL, 11, 1, 1, '2023-09-28 06:35:35', '2023-09-28 07:48:02'),
(72, 174, 8, 'Measurement Unit', 'admin.attribute.index', NULL, 12, 1, 1, '2023-09-29 03:11:33', '2023-09-29 03:12:05'),
(73, 180, NULL, 'Website CMS', NULL, '<i class=\"fad fa-window-alt\"></i>', 11, 1, 1, '2023-10-08 15:08:32', '2023-10-08 15:08:32'),
(74, 181, 73, 'Slider', 'admin.slider.index', NULL, 1, 1, 1, '2023-10-08 15:09:27', '2023-10-08 15:14:11'),
(75, 182, 73, 'Site Items', 'admin.site-item.index', NULL, 2, 1, 1, '2023-10-08 15:22:26', '2023-10-08 15:22:26'),
(76, 183, 73, 'Special Item', 'admin.special-food-item.index', NULL, 3, 1, 1, '2023-10-08 15:23:04', '2023-10-18 13:55:44'),
(77, 184, 73, 'Social Working', 'admin.social-working.index', NULL, 4, 1, 1, '2023-10-08 15:23:47', '2023-10-08 15:23:47'),
(78, 185, 73, 'About', 'admin.about.index', NULL, 5, 1, 1, '2023-10-08 15:24:06', '2023-10-08 15:24:06'),
(79, 186, 73, 'Contact', 'admin.contact.index', NULL, 6, 1, 1, '2023-10-08 15:24:27', '2023-10-08 15:24:27'),
(80, 187, 73, 'Testimonial', 'admin.testimonial.index', NULL, 7, 1, 1, '2023-10-08 15:24:57', '2023-10-08 15:24:57'),
(81, 188, 73, 'Client Message', 'admin.client-message.index', NULL, 8, 1, 1, '2023-10-08 15:25:53', '2023-10-08 15:25:53'),
(82, 189, 73, 'Showcase Items', 'admin.showcase-item.index', NULL, 9, 1, 1, '2023-10-08 15:26:21', '2023-10-08 15:26:21'),
(83, 190, 73, 'Details Card', 'admin.details-card.index', NULL, 10, 1, 1, '2023-10-08 15:26:50', '2023-10-08 15:26:50'),
(84, 226, 73, 'Site Settings', 'admin.settings.index', NULL, 11, 1, 1, '2023-10-08 15:50:46', '2023-10-08 15:50:46'),
(85, 291, 63, 'Orders Processing', 'admin.online-order.index', NULL, 1, 1, 1, '2023-11-16 11:58:49', '2023-11-20 12:08:30'),
(86, 292, 63, 'Client Request', 'admin.client-request.index', NULL, 5, 1, 1, '2023-11-16 12:00:00', '2023-11-20 12:11:25'),
(87, 295, 54, 'Access Log', 'admin.access-log.index', NULL, 9, 1, 1, '2023-11-16 12:02:14', '2023-11-16 12:02:56'),
(88, 296, 63, 'Online Dashboard', 'admin.order-dashboard.index', NULL, 0, 1, 1, '2023-11-19 17:02:39', '2023-11-20 12:07:30'),
(89, 299, 63, 'Cancelled Order', 'admin.cancel-order.index', NULL, 4, 1, 1, '2023-11-19 17:06:14', '2023-11-20 12:12:40'),
(90, 301, 63, 'Delivery Gatepass', 'admin.online-order-delivery.index', NULL, 3, 1, 1, '2023-11-19 17:07:36', '2023-11-20 12:10:53'),
(91, 308, 63, 'Delivery Charge', 'admin.delivery-charge.index', NULL, 5, 1, 1, '2023-11-19 17:09:47', '2023-11-19 17:09:47'),
(92, 310, 33, 'Product Statement', 'admin.product-statement.index', NULL, 4, 1, 1, '2023-11-20 16:04:21', '2023-11-20 16:04:21'),
(93, 311, NULL, 'Dashboard', 'client.dashboard.index', '<i class=\"fal fa-tachometer-alt-fast\"></i>', 12, 1, 1, '2023-11-22 17:19:50', '2023-11-22 17:19:50'),
(94, 312, NULL, 'Product Request', 'client.product-request.index', '<i class=\"fas fa-boxes\"></i>', 13, 1, 1, '2023-11-22 17:20:56', '2023-11-22 17:21:48'),
(95, 313, NULL, 'Purchase Log', 'client.purchase-log.index', '<i class=\"fal fa-box-check\"></i>', 14, 1, 1, '2023-11-22 17:22:52', '2023-11-22 17:24:12'),
(96, 314, NULL, 'Return Log', 'client.return-log.index', '<i class=\"fal fa-undo-alt\"></i>', 15, 1, 1, '2023-11-22 17:23:33', '2023-11-22 17:25:29'),
(97, 315, NULL, 'Payment Log', 'client.payment-log.index', '<i class=\"fal fa-receipt\"></i>', 16, 1, 1, '2023-11-22 17:27:46', '2023-11-22 17:27:46'),
(98, 316, NULL, 'Statement', 'client.statement.index', '<i class=\"fal fa-analytics\"></i>', 17, 1, 1, '2023-11-22 17:28:56', '2023-11-22 17:28:56'),
(99, 330, 48, 'Salesman Performance', 'admin.salesman-flowchart.index', NULL, 11, 1, 1, '2023-11-26 11:18:53', '2024-07-31 03:30:34'),
(100, 331, 48, 'Client Sales Flow', 'admin.client-sales-flow.index', NULL, 12, 1, 1, '2023-11-27 08:53:49', '2023-11-27 08:53:49'),
(101, 332, 48, 'Online Sales History', 'admin.online-sales-history.index', NULL, 13, 1, 1, '2023-11-27 11:16:13', '2023-11-27 11:16:13'),
(102, 333, 73, 'Page Setup', 'admin.page.index', NULL, 12, 1, 1, '2023-11-27 14:58:52', '2023-11-27 14:58:52'),
(103, 334, NULL, 'General Accounting', NULL, '<i class=\"fal fa-ballot-check\"></i>', 8, 1, 1, '2023-12-13 12:04:08', '2023-12-13 12:58:24'),
(104, 335, 103, 'Transaction', NULL, NULL, 1, 1, 1, '2023-12-13 12:04:34', '2023-12-13 12:04:34'),
(105, 336, 104, 'Chart of Accounts', 'admin.coa-setup.index', NULL, 1, 1, 1, '2023-12-13 12:05:02', '2023-12-13 12:05:02'),
(106, 337, 104, 'Debit Voucher', 'admin.debit-voucher-entry.index', NULL, 2, 1, 1, '2023-12-13 12:05:41', '2024-07-31 03:32:43'),
(107, 338, 104, 'Credit Voucher', 'admin.credit-voucher-entry.index', NULL, 3, 1, 1, '2023-12-13 12:05:59', '2024-07-31 03:32:48'),
(108, 339, 104, 'Journal Voucher', 'admin.journal-voucher-entry.index', NULL, 4, 1, 1, '2023-12-13 12:06:21', '2024-07-31 03:32:53'),
(109, 340, 104, 'Voucher Approve', 'admin.voucher-approve.index', NULL, 5, 1, 1, '2023-12-13 12:06:45', '2023-12-13 12:06:45'),
(110, 341, 104, 'Voucher Refuse', 'admin.voucher-reject.index', NULL, 6, 1, 1, '2023-12-13 12:07:04', '2023-12-13 12:07:04'),
(111, 342, 104, 'Posting Automation', 'admin.automation-approve.index', NULL, 7, 1, 1, '2023-12-13 12:09:24', '2023-12-13 12:09:24'),
(112, 343, 104, 'Automation Refuse', 'admin.automation-reject.index', NULL, 8, 1, 1, '2023-12-13 12:09:41', '2023-12-13 12:09:41'),
(113, 371, 103, 'Reports', NULL, NULL, 2, 1, 1, '2023-12-13 16:37:50', '2023-12-13 16:37:50'),
(114, 372, 113, 'Chart of Accounts', 'admin.coa-list.index', NULL, 1, 1, 1, '2023-12-13 16:38:13', '2023-12-13 16:38:13'),
(115, 373, 113, 'Daily Voucher List', 'admin.voucher-list.index', NULL, 2, 1, 1, '2023-12-16 06:26:26', '2023-12-16 06:26:26'),
(116, 374, 113, 'Daily Cash Book', 'admin.cash-book.index', NULL, 3, 1, 1, '2023-12-19 14:46:09', '2023-12-19 14:46:09'),
(117, 375, 113, 'Daily Bank Book', 'admin.bank-book.index', NULL, 4, 1, 1, '2023-12-19 14:46:38', '2023-12-19 14:46:38'),
(118, 376, 113, 'Transaction Ledger', 'admin.transaction-ledger.index', NULL, 5, 1, 1, '2023-12-19 14:47:08', '2023-12-19 14:47:08'),
(119, 377, 113, 'Cash Flow Statement', 'admin.cash-flow-statement.index', NULL, 6, 1, 1, '2023-12-19 14:47:27', '2023-12-19 14:47:27'),
(120, 378, 113, 'General Ledger', 'admin.general-ledger.index', NULL, 7, 1, 1, '2023-12-19 14:47:46', '2023-12-19 14:47:46'),
(121, 379, 113, 'Trial Balance', 'admin.trial-balance.index', NULL, 8, 1, 1, '2023-12-30 16:33:07', '2023-12-30 16:33:07'),
(122, 380, 113, 'Income Statement', 'admin.income-statement.index', NULL, 9, 1, 1, '2023-12-30 16:37:11', '2023-12-30 16:37:11'),
(123, 381, 73, 'Subscribers', 'admin.subscription.index', NULL, 13, 1, 1, '2024-01-23 04:24:23', '2024-01-23 04:24:23'),
(124, 382, 2, 'Admin Settings', 'admin.admin-settings.index', NULL, 6, 1, 1, '2024-02-27 10:29:25', '2024-02-27 10:29:25'),
(125, 383, 113, 'Balance Sheet', 'admin.balance-sheet.index', NULL, 10, 1, 1, '2024-02-28 15:09:20', '2024-02-28 15:09:20'),
(126, 384, NULL, 'Clear cache', 'admin.cache.clear', '<i class=\"fad fa-broom\"></i>', 18, 1, 1, '2024-03-03 04:17:45', '2024-06-06 13:23:51'),
(127, 385, 19, 'Generate Barcode', 'admin.generate-barcode.index', NULL, 5, 1, 1, '2024-03-12 08:42:09', '2024-03-12 08:42:09'),
(128, 386, 38, 'POS Sale', 'admin.pos-sales.index', NULL, 3, 0, 1, '2024-03-12 15:46:43', '2024-07-31 03:27:54'),
(129, 392, 54, 'Invoice Profit Loss', 'admin.profit-loss.index', NULL, 10, 1, 1, '2024-04-08 09:56:46', '2024-07-31 03:34:55'),
(130, 393, 8, 'Sizes', 'admin.size.index', NULL, 15, 0, 1, '2024-04-16 16:16:55', '2024-05-01 05:24:22'),
(131, 394, 8, 'Colors', 'admin.color.index', NULL, 16, 0, 1, '2024-04-16 16:17:26', '2024-05-01 05:24:16'),
(132, 406, 19, 'Lifestyle Product Lifting', 'admin.lifting-fashion-product.index', NULL, 2, 1, 1, '2024-06-01 10:33:55', '2024-06-02 03:17:06'),
(133, 412, 38, 'Lifestyle Product Sales', 'admin.sales-lifestyle-product.index', NULL, 10, 1, 1, '2024-06-04 10:47:16', '2024-06-04 10:47:16'),
(134, 418, 38, 'Lifestyle Product Return', 'admin.lifestyle-product-return.index', NULL, 11, 1, 1, '2024-06-05 06:00:31', '2024-06-05 06:00:31'),
(135, 427, 73, 'Menu Setup', 'admin.menu.index', NULL, 14, 1, 1, '2024-06-23 04:18:38', '2024-06-23 04:18:38'),
(136, 438, 73, 'Homepage Section', 'admin.sections.index', NULL, 15, 1, 1, '2024-06-23 12:14:58', '2024-06-23 12:14:58'),
(138, 450, 38, 'Running Sales', 'admin.running-sales.index', NULL, 12, 1, 1, '2024-07-13 12:38:18', '2024-07-13 12:38:18'),
(139, 457, NULL, 'Dashboard', 'investor.dashboard', '<i class=\"fad fa-analytics\"></i>', 19, 1, 1, '2024-07-16 16:19:08', '2024-07-16 16:19:08'),
(140, 458, NULL, 'Product Statement', 'investor.product-statement.index', '<i class=\"fas fa-th-list\"></i>', 20, 1, 1, '2024-07-16 16:33:48', '2024-07-16 16:34:30'),
(141, 459, NULL, 'Investment Profit', 'investor.product-wise-profit.index', '<i class=\"fas fa-sack-dollar\"></i>', 21, 1, 1, '2024-07-17 05:04:11', '2024-08-03 10:32:48'),
(142, 460, NULL, 'Settings', 'investor.settings', '<i class=\"fad fa-cogs\"></i>', 24, 1, 1, '2024-07-17 10:32:53', '2024-08-03 09:25:37'),
(143, 461, NULL, 'Payment Request', 'investor.payment.index', '<i class=\"fal fa-credit-card\"></i>', 22, 1, 1, '2024-07-17 12:14:36', '2024-08-03 09:24:55'),
(147, 484, NULL, 'Transaction Statement', 'investor.statement.index', '<i class=\"fal fa-ballot-check\"></i>', 23, 1, 1, '2024-07-23 17:34:34', '2024-08-03 09:25:17'),
(148, 485, 151, 'Payment Request', 'admin.approve-payment.index', NULL, 9, 1, 1, '2024-07-23 17:56:48', '2024-07-30 09:06:53'),
(149, 486, 151, 'Invest Closing', 'admin.investor-sattlement.index', NULL, 10, 1, 1, '2024-07-23 17:59:13', '2024-07-30 09:08:20'),
(150, 493, NULL, 'Investor', NULL, '<i class=\"fas fa-user-tie\"></i>', 4, 1, 1, '2024-07-29 02:19:56', '2024-07-31 03:22:55'),
(151, 494, 150, 'Transaction', NULL, NULL, 1, 1, 1, '2024-07-29 02:20:43', '2024-07-29 02:20:43'),
(152, 495, 151, 'Investor Setup', 'admin.investor.index', NULL, 1, 1, 1, '2024-07-29 02:22:59', '2024-07-29 02:22:59'),
(153, 502, 151, 'Invest Process', 'admin.invest.index', NULL, 2, 1, 1, '2024-07-29 02:30:32', '2024-07-30 09:01:25'),
(154, 508, 151, 'Approve Invest', 'admin.approve-invest.index', NULL, 3, 1, 1, '2024-07-29 02:35:31', '2024-07-29 02:35:31'),
(155, 511, 151, 'Profit Distribution', 'admin.profit-distribute.index', NULL, 4, 1, 1, '2024-07-29 02:39:35', '2024-07-29 02:39:35'),
(156, 517, 150, 'Reports', NULL, NULL, 2, 1, 1, '2024-07-30 09:08:40', '2024-07-30 09:08:40'),
(157, 518, 156, 'Investment Profit Sheet', 'admin.profit-sheet.index', NULL, 1, 1, 1, '2024-07-30 09:09:24', '2024-07-30 09:09:24'),
(158, 519, 156, 'Investment Register', 'admin.investor-statement.index', NULL, 2, 1, 1, '2024-07-30 09:10:13', '2024-08-01 04:12:42'),
(159, 520, 156, 'Profit Due List', 'admin.profit-due-list.index', NULL, 3, 1, 1, '2024-07-30 09:11:00', '2024-07-30 09:11:00'),
(160, 521, 151, 'Investor Payment', 'admin.payment.index', NULL, 4, 1, 1, '2024-08-01 05:19:16', '2024-08-01 05:19:16'),
(161, 527, NULL, 'Product Status', 'investor.product-status.index', '<i class=\"fas fa-chart-pie\"></i>', 20, 1, 1, '2024-08-03 09:19:58', '2024-08-03 09:19:58');

-- --------------------------------------------------------

--
-- Table structure for table `admin_menu_actions`
--

CREATE TABLE `admin_menu_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `admin_menu_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `route` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_menu_actions`
--

INSERT INTO `admin_menu_actions` (`id`, `permission_id`, `admin_menu_id`, `name`, `route`, `status`, `created_at`, `updated_at`) VALUES
(1, 8, 7, 'Create', 'admin.admin-menu.create', 1, '2023-09-19 09:16:56', '2023-09-19 09:16:56'),
(2, 9, 7, 'store', 'admin.admin-menu.store', 1, '2023-09-19 09:17:06', '2023-09-19 09:17:06'),
(3, 10, 7, 'edit', 'admin.admin-menu.edit', 1, '2023-09-19 09:17:10', '2023-09-19 09:17:10'),
(4, 11, 7, 'update', 'admin.admin-menu.update', 1, '2023-09-19 09:17:20', '2023-09-19 09:17:20'),
(5, 12, 7, 'delete', 'admin.admin-menu.destroy', 1, '2023-09-19 09:17:25', '2023-09-19 09:17:25'),
(7, 14, 7, 'action view', 'admin.admin-menuAction.index', 1, '2023-09-19 09:18:18', '2023-09-19 09:18:18'),
(8, 15, 7, 'action create', 'admin.admin-menuAction.create', 1, '2023-09-19 09:18:26', '2023-09-19 09:18:26'),
(9, 16, 7, 'action store', 'admin.admin-menuAction.store', 1, '2023-09-19 09:18:33', '2023-09-19 09:18:33'),
(10, 17, 7, 'action edit', 'admin.admin-menuAction.edit', 1, '2023-09-19 09:18:41', '2023-09-19 09:18:41'),
(11, 18, 7, 'action update', 'admin.admin-menuAction.update', 1, '2023-09-19 09:18:49', '2023-09-19 09:18:49'),
(12, 19, 7, 'action delete', 'admin.admin-menuAction.destroy', 1, '2023-09-19 09:19:02', '2023-09-19 09:19:02'),
(13, 83, 70, 'create', 'admin.client-category.create', 1, '2023-09-25 06:58:14', '2023-09-25 06:58:14'),
(14, 84, 70, 'store', 'admin.client-category.store', 1, '2023-09-25 06:58:24', '2023-09-25 06:58:24'),
(15, 85, 70, 'edit', 'admin.client-category.edit', 1, '2023-09-25 06:58:31', '2023-09-25 06:58:31'),
(16, 86, 70, 'update', 'admin.client-category.update', 1, '2023-09-25 06:58:41', '2023-09-25 06:58:41'),
(17, 87, 70, 'delete', 'admin.client-category.destroy', 1, '2023-09-25 06:58:49', '2023-09-25 06:58:49'),
(18, 88, 69, 'create', 'admin.chain-client.create', 1, '2023-09-25 06:59:35', '2023-09-25 06:59:35'),
(19, 89, 69, 'store', 'admin.chain-client.store', 1, '2023-09-25 06:59:50', '2023-09-25 06:59:50'),
(20, 90, 69, 'edit', 'admin.chain-client.edit', 1, '2023-09-25 06:59:55', '2023-09-25 06:59:55'),
(21, 91, 69, 'update', 'admin.chain-client.update', 1, '2023-09-25 07:00:02', '2023-09-25 07:00:02'),
(22, 92, 69, 'delete', 'admin.chain-client.destroy', 1, '2023-09-25 07:00:19', '2023-09-25 07:00:19'),
(23, 93, 41, 'create', 'admin.territory.create', 1, '2023-09-25 07:00:48', '2023-09-25 07:00:48'),
(24, 94, 41, 'store', 'admin.territory.store', 1, '2023-09-25 07:00:54', '2023-09-25 07:00:54'),
(25, 95, 41, 'edit', 'admin.territory.edit', 1, '2023-09-25 07:00:58', '2023-09-25 07:00:58'),
(26, 96, 41, 'update', 'admin.territory.update', 1, '2023-09-25 07:01:03', '2023-09-25 07:01:03'),
(27, 97, 41, 'delete', 'admin.territory.destroy', 1, '2023-09-25 07:01:09', '2023-09-25 07:01:09'),
(28, 98, 40, 'create', 'admin.area.create', 1, '2023-09-25 07:01:36', '2023-09-25 07:01:36'),
(29, 99, 40, 'store', 'admin.area.store', 1, '2023-09-25 07:01:43', '2023-09-25 07:01:43'),
(30, 100, 40, 'edit', 'admin.area.edit', 1, '2023-09-25 07:01:47', '2023-09-25 07:01:47'),
(31, 101, 40, 'update', 'admin.area.update', 1, '2023-09-25 07:01:52', '2023-09-25 07:01:52'),
(32, 102, 40, 'delete', 'admin.area.destroy', 1, '2023-09-25 07:01:57', '2023-09-25 07:01:57'),
(33, 103, 39, 'create', 'admin.region.create', 1, '2023-09-25 07:02:16', '2023-09-25 07:02:16'),
(34, 104, 39, 'store', 'admin.region.store', 1, '2023-09-25 07:02:22', '2023-09-25 07:02:22'),
(35, 105, 39, 'edit', 'admin.region.edit', 1, '2023-09-25 07:02:26', '2023-09-25 07:02:26'),
(36, 106, 39, 'update', 'admin.region.update', 1, '2023-09-25 07:02:33', '2023-09-25 07:02:33'),
(37, 107, 39, 'delete', 'admin.region.destroy', 1, '2023-09-25 07:02:39', '2023-09-25 07:02:39'),
(38, 108, 20, 'create', 'admin.vendor.create', 1, '2023-09-25 07:03:08', '2023-09-25 07:03:08'),
(39, 109, 20, 'store', 'admin.vendor.store', 1, '2023-09-25 07:03:14', '2023-09-25 07:03:14'),
(40, 110, 20, 'edit', 'admin.vendor.edit', 1, '2023-09-25 07:03:19', '2023-09-25 07:03:19'),
(41, 111, 20, 'update', 'admin.vendor.update', 1, '2023-09-25 07:03:24', '2023-09-25 07:03:24'),
(42, 112, 20, 'delete', 'admin.vendor.destroy', 1, '2023-09-25 07:03:29', '2023-09-25 07:03:29'),
(43, 113, 13, 'create', 'admin.staff.create', 1, '2023-09-25 07:03:49', '2023-09-25 07:03:49'),
(44, 114, 13, 'store', 'admin.staff.store', 1, '2023-09-25 07:03:54', '2023-09-25 07:03:54'),
(45, 115, 13, 'edit', 'admin.staff.edit', 1, '2023-09-25 07:03:58', '2023-09-25 07:03:58'),
(46, 116, 13, 'update', 'admin.staff.update', 1, '2023-09-25 07:04:04', '2023-09-25 07:04:04'),
(47, 117, 13, 'delete', 'admin.staff.destroy', 1, '2023-09-25 07:04:10', '2023-09-25 07:04:10'),
(48, 118, 11, 'create', 'admin.category.create', 1, '2023-09-25 07:04:55', '2023-09-25 07:04:55'),
(49, 119, 11, 'store', 'admin.category.store', 1, '2023-09-25 07:04:59', '2023-09-25 07:04:59'),
(50, 120, 11, 'edit', 'admin.category.edit', 1, '2023-09-25 07:05:03', '2023-09-25 07:05:03'),
(51, 121, 11, 'update', 'admin.category.update', 1, '2023-09-25 07:05:10', '2023-09-25 07:05:10'),
(52, 122, 11, 'delete', 'admin.category.destroy', 1, '2023-09-25 07:05:15', '2023-09-25 07:05:15'),
(53, 123, 10, 'create', 'admin.store.create', 1, '2023-09-25 07:05:43', '2023-09-25 07:05:43'),
(54, 124, 10, 'store', 'admin.store.store', 1, '2023-09-25 07:05:51', '2023-09-25 07:05:51'),
(55, 125, 10, 'edit', 'admin.store.edit', 1, '2023-09-25 07:05:55', '2023-09-25 07:05:55'),
(56, 126, 10, 'update', 'admin.store.update', 1, '2023-09-25 07:06:00', '2023-09-25 07:06:00'),
(57, 127, 10, 'delete', 'admin.store.destroy', 1, '2023-09-25 07:06:05', '2023-09-25 07:06:05'),
(58, 128, 6, 'create', 'admin.user.create', 1, '2023-09-25 07:06:29', '2023-09-25 07:06:29'),
(59, 129, 6, 'store', 'admin.user.store', 1, '2023-09-25 07:06:33', '2023-09-25 07:06:33'),
(60, 130, 6, 'edit', 'admin.user.edit', 1, '2023-09-25 07:06:37', '2023-09-25 07:06:37'),
(61, 131, 6, 'update', 'admin.user.update', 1, '2023-09-25 07:06:42', '2023-09-25 07:06:42'),
(62, 132, 6, 'delete', 'admin.user.destroy', 1, '2023-09-25 07:06:47', '2023-09-25 07:06:47'),
(63, 133, 5, 'create', 'admin.role.create', 1, '2023-09-25 07:07:07', '2023-09-25 07:07:07'),
(64, 134, 5, 'store', 'admin.role.store', 1, '2023-09-25 07:07:12', '2023-09-25 07:07:12'),
(65, 135, 5, 'edit', 'admin.role.edit', 1, '2023-09-25 07:07:17', '2023-09-25 07:07:17'),
(66, 136, 5, 'update', 'admin.role.update', 1, '2023-09-25 07:07:23', '2023-09-25 07:07:23'),
(67, 137, 5, 'delete', 'admin.role.destroy', 1, '2023-09-25 07:07:28', '2023-09-25 07:07:28'),
(68, 138, 4, 'create', 'admin.branch.create', 1, '2023-09-25 07:07:44', '2023-09-25 07:07:44'),
(69, 139, 4, 'store', 'admin.branch.store', 1, '2023-09-25 07:08:29', '2023-09-25 07:08:29'),
(70, 140, 4, 'edit', 'admin.branch.edit', 1, '2023-09-25 07:08:34', '2023-09-25 07:08:34'),
(71, 141, 4, 'update', 'admin.branch.update', 1, '2023-09-25 07:08:39', '2023-09-25 07:08:39'),
(72, 142, 4, 'delete', 'admin.branch.destroy', 1, '2023-09-25 07:08:44', '2023-09-25 07:08:44'),
(73, 143, 3, 'create', 'admin.company.create', 1, '2023-09-25 07:09:19', '2023-09-25 07:09:19'),
(74, 144, 3, 'store', 'admin.company.store', 1, '2023-09-25 07:09:24', '2023-09-25 07:09:24'),
(75, 145, 3, 'edit', 'admin.company.edit', 1, '2023-09-25 07:09:31', '2023-09-25 07:09:31'),
(76, 146, 3, 'update', 'admin.company.update', 1, '2023-09-25 07:09:36', '2023-09-25 07:09:36'),
(77, 147, 3, 'delete', 'admin.company.destroy', 1, '2023-09-25 07:09:41', '2023-09-25 07:09:41'),
(78, 148, 9, 'create', 'admin.group.create', 1, '2023-09-25 07:51:05', '2023-09-25 07:51:05'),
(79, 149, 9, 'store', 'admin.group.store', 1, '2023-09-25 07:51:10', '2023-09-25 07:51:10'),
(80, 150, 9, 'edit', 'admin.group.edit', 1, '2023-09-25 07:51:14', '2023-09-25 07:51:14'),
(81, 151, 9, 'update', 'admin.group.update', 1, '2023-09-25 07:51:20', '2023-09-25 07:51:20'),
(82, 152, 9, 'delete', 'admin.group.destroy', 1, '2023-09-25 07:51:28', '2023-09-25 07:51:28'),
(83, 153, 42, 'create', 'admin.client.create', 1, '2023-09-25 10:09:28', '2023-09-25 10:09:28'),
(84, 154, 42, 'store', 'admin.client.store', 1, '2023-09-25 10:09:33', '2023-09-25 10:09:33'),
(85, 155, 42, 'edit', 'admin.client.edit', 1, '2023-09-25 10:09:38', '2023-09-25 10:09:38'),
(86, 156, 42, 'update', 'admin.client.update', 1, '2023-09-25 10:09:42', '2023-09-25 10:09:42'),
(87, 157, 42, 'delete', 'admin.client.destroy', 1, '2023-09-25 10:09:49', '2023-09-25 10:09:49'),
(88, 158, 12, 'create', 'admin.product.create', 1, '2023-09-25 14:14:40', '2023-09-25 14:14:40'),
(89, 159, 12, 'store', 'admin.product.store', 1, '2023-09-25 14:14:46', '2023-09-25 14:14:46'),
(90, 160, 12, 'edit', 'admin.product.edit', 1, '2023-09-25 14:14:50', '2023-09-25 14:14:50'),
(91, 161, 12, 'update', 'admin.product.update', 1, '2023-09-25 14:14:55', '2023-09-25 14:14:55'),
(92, 162, 12, 'delete', 'admin.product.destroy', 1, '2023-09-25 14:15:02', '2023-09-25 14:15:02'),
(93, 163, 16, 'create', 'admin.group-target.create', 1, '2023-09-25 15:12:45', '2023-09-25 15:12:45'),
(94, 164, 16, 'store', 'admin.group-target.store', 1, '2023-09-25 15:12:50', '2023-09-25 15:12:50'),
(95, 165, 16, 'edit', 'admin.group-target.edit', 1, '2023-09-25 15:12:55', '2023-09-25 15:12:55'),
(96, 166, 16, 'update', 'admin.group-target.update', 1, '2023-09-25 15:13:02', '2023-09-25 15:13:02'),
(97, 167, 16, 'delete', 'admin.group-target.destroy', 1, '2023-09-25 15:13:32', '2023-09-25 15:13:32'),
(98, 169, 71, 'create', 'admin.vehicle.create', 1, '2023-09-28 06:49:24', '2023-09-28 06:49:24'),
(99, 170, 71, 'store', 'admin.vehicle.store', 1, '2023-09-28 06:49:29', '2023-09-28 06:49:29'),
(100, 171, 71, 'edit', 'admin.vehicle.edit', 1, '2023-09-28 06:49:33', '2023-09-28 06:49:33'),
(101, 172, 71, 'update', 'admin.vehicle.update', 1, '2023-09-28 06:49:38', '2023-09-28 06:49:38'),
(102, 173, 71, 'delete', 'admin.vehicle.destroy', 1, '2023-09-28 06:49:44', '2023-09-28 06:49:44'),
(103, 175, 21, 'create', 'admin.lifting.create', 1, '2023-09-30 11:29:14', '2023-09-30 11:29:14'),
(104, 176, 21, 'store', 'admin.lifting.store', 1, '2023-09-30 11:29:19', '2023-09-30 11:29:19'),
(105, 177, 21, 'edit', 'admin.lifting.edit', 1, '2023-09-30 11:29:23', '2023-09-30 11:29:23'),
(106, 178, 21, 'update', 'admin.lifting.update', 1, '2023-09-30 11:29:28', '2023-09-30 11:29:28'),
(107, 179, 21, 'delete', 'admin.lifting.destroy', 1, '2023-09-30 11:29:33', '2023-09-30 11:29:33'),
(108, 191, 74, 'create', 'admin.slider.create', 1, '2023-10-08 15:28:49', '2023-10-08 15:28:49'),
(109, 192, 74, 'store', 'admin.slider.store', 1, '2023-10-08 15:28:54', '2023-10-08 15:28:54'),
(110, 193, 74, 'edit', 'admin.slider.edit', 1, '2023-10-08 15:28:59', '2023-10-08 15:28:59'),
(111, 194, 74, 'update', 'admin.slider.update', 1, '2023-10-08 15:29:07', '2023-10-08 15:29:07'),
(112, 195, 74, 'delete', 'admin.slider.destroy', 1, '2023-10-08 15:29:15', '2023-10-08 15:29:15'),
(113, 196, 76, 'create', 'admin.special-food-item.create', 1, '2023-10-08 15:30:28', '2023-10-08 15:30:28'),
(114, 197, 76, 'store', 'admin.special-food-item.store', 1, '2023-10-08 15:30:37', '2023-10-08 15:30:37'),
(115, 198, 76, 'edit', 'admin.special-food-item.edit', 1, '2023-10-08 15:30:41', '2023-10-08 15:30:41'),
(116, 199, 76, 'update', 'admin.special-food-item.update', 1, '2023-10-08 15:30:47', '2023-10-08 15:30:47'),
(117, 200, 76, 'delete', 'admin.special-food-item.destroy', 1, '2023-10-08 15:30:51', '2023-10-08 15:30:51'),
(118, 201, 77, 'create', 'admin.social-working.create', 1, '2023-10-08 15:31:27', '2023-10-08 15:31:27'),
(119, 202, 77, 'store', 'admin.social-working.store', 1, '2023-10-08 15:31:31', '2023-10-08 15:31:31'),
(120, 203, 77, 'edit', 'admin.social-working.edit', 1, '2023-10-08 15:31:36', '2023-10-08 15:31:36'),
(121, 204, 77, 'update', 'admin.social-working.update', 1, '2023-10-08 15:31:41', '2023-10-08 15:31:41'),
(122, 205, 77, 'delete', 'admin.social-working.destroy', 1, '2023-10-08 15:31:45', '2023-10-08 15:31:45'),
(123, 206, 80, 'create', 'admin.testimonial.create', 1, '2023-10-08 15:33:23', '2023-10-08 15:33:23'),
(124, 207, 80, 'store', 'admin.testimonial.store', 1, '2023-10-08 15:33:28', '2023-10-08 15:33:28'),
(125, 208, 80, 'edit', 'admin.testimonial.edit', 1, '2023-10-08 15:33:32', '2023-10-08 15:33:32'),
(126, 209, 80, 'update', 'admin.testimonial.update', 1, '2023-10-08 15:33:37', '2023-10-08 15:33:37'),
(127, 210, 80, 'delete', 'admin.testimonial.destroy', 1, '2023-10-08 15:34:02', '2023-10-08 15:34:02'),
(128, 211, 81, 'create', 'admin.client-message.create', 1, '2023-10-08 15:34:31', '2023-10-08 15:34:31'),
(129, 212, 81, 'store', 'admin.client-message.store', 1, '2023-10-08 15:34:36', '2023-10-08 15:34:36'),
(130, 213, 81, 'edit', 'admin.client-message.edit', 1, '2023-10-08 15:34:40', '2023-10-08 15:34:40'),
(131, 214, 81, 'update', 'admin.client-message.update', 1, '2023-10-08 15:34:44', '2023-10-08 15:34:44'),
(132, 215, 81, 'delete', 'admin.client-message.destroy', 1, '2023-10-08 15:34:49', '2023-10-08 15:34:49'),
(133, 216, 82, 'create', 'admin.showcase-item.create', 1, '2023-10-08 15:35:20', '2023-10-08 15:35:20'),
(134, 217, 82, 'store', 'admin.showcase-item.store', 1, '2023-10-08 15:35:24', '2023-10-08 15:35:24'),
(135, 218, 82, 'edit', 'admin.showcase-item.edit', 1, '2023-10-08 15:35:28', '2023-10-08 15:35:28'),
(136, 219, 82, 'update', 'admin.showcase-item.update', 1, '2023-10-08 15:35:33', '2023-10-08 15:35:33'),
(137, 220, 82, 'delete', 'admin.showcase-item.destroy', 1, '2023-10-08 15:35:39', '2023-10-08 15:35:39'),
(138, 221, 83, 'create', 'admin.details-card.create', 1, '2023-10-08 15:36:04', '2023-10-08 15:36:04'),
(139, 222, 83, 'store', 'admin.details-card.store', 1, '2023-10-08 15:36:09', '2023-10-08 15:36:09'),
(140, 223, 83, 'edit', 'admin.details-card.edit', 1, '2023-10-08 15:36:13', '2023-10-08 15:36:13'),
(141, 224, 83, 'update', 'admin.details-card.update', 1, '2023-10-08 15:36:17', '2023-10-08 15:36:17'),
(142, 225, 83, 'delete', 'admin.details-card.destroy', 1, '2023-10-08 15:36:22', '2023-10-08 15:36:22'),
(143, 227, 64, 'create', 'admin.offline-order.create', 1, '2023-10-09 03:43:02', '2023-10-09 03:43:02'),
(144, 228, 64, 'store', 'admin.offline-order.store', 1, '2023-10-09 03:43:07', '2023-10-09 03:43:07'),
(145, 229, 64, 'edit', 'admin.offline-order.edit', 1, '2023-10-09 03:43:11', '2023-10-09 03:43:11'),
(146, 230, 64, 'update', 'admin.offline-order.update', 1, '2023-10-09 03:43:19', '2023-10-09 03:43:19'),
(147, 231, 64, 'delete', 'admin.offline-order.destroy', 1, '2023-10-09 03:43:25', '2023-10-09 03:43:25'),
(148, 232, 64, 'print', 'admin.offline-order.show', 1, '2023-10-10 14:19:37', '2023-10-10 14:19:37'),
(149, 233, 43, 'create', 'admin.sales.create', 1, '2023-10-11 08:10:17', '2023-10-11 08:10:17'),
(150, 234, 43, 'store', 'admin.sales.store', 1, '2023-10-11 08:10:22', '2023-10-11 08:10:22'),
(151, 235, 43, 'edit', 'admin.sales.edit', 1, '2023-10-11 08:10:27', '2023-10-11 08:10:27'),
(152, 236, 43, 'update', 'admin.sales.update', 1, '2023-10-11 08:10:32', '2023-10-11 08:10:32'),
(153, 237, 43, 'delete', 'admin.sales.destroy', 1, '2023-10-11 08:10:37', '2023-10-11 08:10:37'),
(154, 238, 44, 'create', 'admin.collection.create', 1, '2023-10-14 16:50:47', '2023-10-14 16:50:47'),
(155, 239, 44, 'store', 'admin.collection.store', 1, '2023-10-14 16:50:51', '2023-10-14 16:50:51'),
(156, 240, 44, 'edit', 'admin.collection.edit', 1, '2023-10-14 16:50:56', '2023-10-14 16:50:56'),
(157, 241, 44, 'update', 'admin.collection.update', 1, '2023-10-14 16:51:00', '2023-10-14 16:51:00'),
(158, 242, 44, 'delete', 'admin.collection.destroy', 1, '2023-10-14 16:51:05', '2023-10-14 16:51:05'),
(159, 243, 46, 'create', 'admin.sales-return.create', 1, '2023-10-15 06:43:17', '2023-10-15 06:43:17'),
(160, 244, 46, 'store', 'admin.sales-return.store', 1, '2023-10-15 06:43:24', '2023-10-15 06:43:24'),
(161, 245, 46, 'edit', 'admin.sales-return.edit', 1, '2023-10-15 06:43:29', '2023-10-15 06:43:29'),
(162, 246, 46, 'update', 'admin.sales-return.update', 1, '2023-10-15 06:43:34', '2023-10-15 06:43:34'),
(163, 247, 46, 'delete', 'admin.sales-return.destroy', 1, '2023-10-15 06:43:39', '2023-10-15 06:43:39'),
(164, 248, 22, 'create', 'admin.lifting-return.create', 1, '2023-10-16 04:47:09', '2023-10-16 04:47:09'),
(165, 249, 22, 'store', 'admin.lifting-return.store', 1, '2023-10-16 04:47:15', '2023-10-16 04:47:15'),
(166, 250, 22, 'edit', 'admin.lifting-return.edit', 1, '2023-10-16 04:47:19', '2023-10-16 04:47:19'),
(167, 251, 22, 'update', 'admin.lifting-return.update', 1, '2023-10-16 04:47:28', '2023-10-16 04:47:28'),
(168, 252, 22, 'delete', 'admin.lifting-return.destroy', 1, '2023-10-16 04:47:34', '2023-10-16 04:47:34'),
(169, 253, 23, 'create', 'admin.vendor-payment.create', 1, '2023-10-16 09:19:38', '2023-10-16 09:19:38'),
(170, 254, 23, 'store', 'admin.vendor-payment.store', 1, '2023-10-16 09:19:42', '2023-10-16 09:19:42'),
(171, 255, 23, 'edit', 'admin.vendor-payment.edit', 1, '2023-10-16 09:19:46', '2023-10-16 09:19:46'),
(172, 256, 23, 'update', 'admin.vendor-payment.update', 1, '2023-10-16 09:19:51', '2023-10-16 09:19:51'),
(173, 257, 23, 'delete', 'admin.vendor-payment.destroy', 1, '2023-10-16 09:20:00', '2023-10-16 09:20:00'),
(174, 258, 31, 'create', 'admin.transfer.create', 1, '2023-10-18 05:08:51', '2023-10-18 05:08:51'),
(175, 259, 31, 'store', 'admin.transfer.store', 1, '2023-10-18 05:09:01', '2023-10-18 05:09:01'),
(176, 260, 31, 'edit', 'admin.transfer.edit', 1, '2023-10-18 05:09:05', '2023-10-18 05:09:05'),
(177, 261, 31, 'update', 'admin.transfer.update', 1, '2023-10-18 05:09:22', '2023-10-18 05:09:22'),
(178, 262, 31, 'delete', 'admin.transfer.destroy', 1, '2023-10-18 05:09:29', '2023-10-18 05:09:29'),
(179, 263, 32, 'view', 'admin.transfer-receive.show', 1, '2023-10-18 09:47:14', '2023-10-18 09:47:14'),
(180, 264, 32, 'approve', 'admin.transfer-receive.edit', 1, '2023-10-18 09:47:49', '2023-10-18 09:47:49'),
(181, 265, 32, 'reject', 'admin.transfer-receive.destroy', 1, '2023-10-18 09:48:01', '2023-10-18 09:48:01'),
(182, 266, 47, 'View', 'admin.return-approve.show', 1, '2023-10-18 12:04:48', '2023-10-18 12:04:48'),
(183, 267, 47, 'Approve', 'admin.return-approve.edit', 1, '2023-10-18 12:05:03', '2023-10-18 12:05:03'),
(184, 268, 47, 'Reject', 'admin.return-approve.destroy', 1, '2023-10-18 12:05:12', '2023-10-18 12:05:12'),
(185, 269, 66, 'edit', 'admin.delivery.edit', 1, '2023-11-07 06:58:42', '2023-11-07 06:58:42'),
(186, 270, 66, 'store', 'admin.delivery.store', 1, '2023-11-07 07:01:19', '2023-11-07 07:01:19'),
(187, 271, 66, 'update', 'admin.delivery.update', 1, '2023-11-07 07:01:31', '2023-11-07 07:01:31'),
(188, 272, 66, 'delete', 'admin.delivery.destroy', 1, '2023-11-07 07:01:37', '2023-11-07 07:01:37'),
(189, 273, 66, 'create', 'admin.delivery.create', 1, '2023-11-07 08:01:58', '2023-11-07 08:01:58'),
(192, 276, 5, 'Permission Edit', 'admin.rolePermission.edit', 1, '2023-11-09 04:05:15', '2023-11-09 04:05:15'),
(193, 277, 5, 'Permission Update', 'admin.rolePermission.update', 1, '2023-11-09 04:05:28', '2023-11-09 04:05:28'),
(198, 282, 72, 'store', 'admin.attribute.store', 1, '2023-11-10 13:05:41', '2023-11-10 13:05:41'),
(199, 283, 72, 'edit', 'admin.attribute.edit', 1, '2023-11-10 13:05:54', '2023-11-10 13:05:54'),
(200, 284, 72, 'update', 'admin.attribute.update', 1, '2023-11-10 13:06:02', '2023-11-10 13:06:02'),
(201, 285, 72, 'delete', 'admin.attribute.destroy', 1, '2023-11-10 13:06:09', '2023-11-10 13:06:09'),
(202, 286, 17, 'create', 'admin.client-price.create', 1, '2023-11-10 13:15:05', '2023-11-10 13:15:05'),
(203, 287, 17, 'store', 'admin.client-price.store', 1, '2023-11-10 13:15:12', '2023-11-10 13:15:12'),
(204, 288, 17, 'edit', 'admin.client-price.edit', 1, '2023-11-10 13:15:40', '2023-11-10 13:15:40'),
(205, 289, 17, 'update', 'admin.client-price.update', 1, '2023-11-10 13:15:44', '2023-11-10 13:15:44'),
(206, 290, 17, 'delete', 'admin.client-price.destroy', 1, '2023-11-10 13:15:50', '2023-11-10 13:15:50'),
(207, 293, 85, 'view', 'admin.online-order.edit', 1, '2023-11-16 12:01:31', '2023-11-16 12:01:31'),
(208, 294, 85, 'print', 'admin.online-order.show', 1, '2023-11-16 12:01:39', '2023-11-16 12:01:39'),
(209, 297, 88, 'Edit Status', 'admin.order-dashboard.edit', 1, '2023-11-19 17:04:37', '2023-11-19 17:04:37'),
(210, 298, 88, 'Update Status', 'admin.order-dashboard.update', 1, '2023-11-19 17:04:50', '2023-11-19 17:04:50'),
(211, 300, 89, 'Approve', 'admin.cancel-order.approve', 1, '2023-11-19 17:06:43', '2023-11-19 17:06:43'),
(212, 302, 90, 'create', 'admin.online-order-delivery.create', 1, '2023-11-19 17:08:03', '2023-11-19 17:08:03'),
(213, 303, 90, 'store', 'admin.online-order-delivery.store', 1, '2023-11-19 17:08:08', '2023-11-19 17:08:08'),
(214, 304, 90, 'edit', 'admin.online-order-delivery.edit', 1, '2023-11-19 17:08:12', '2023-11-19 17:08:12'),
(215, 305, 90, 'update', 'admin.online-order-delivery.update', 1, '2023-11-19 17:08:22', '2023-11-19 17:08:22'),
(216, 306, 90, 'delete', 'admin.online-order-delivery.destroy', 1, '2023-11-19 17:08:29', '2023-11-19 17:08:29'),
(217, 307, 90, 'print', 'admin.online-order-delivery.show', 1, '2023-11-19 17:09:04', '2023-11-19 17:09:04'),
(218, 309, 91, 'Update', 'admin.delivery-charge.update', 1, '2023-11-19 17:10:16', '2023-11-19 17:10:16'),
(219, 317, 94, 'create', 'client.product-request.create', 1, '2023-11-22 17:30:15', '2023-11-22 17:30:15'),
(220, 318, 94, 'store', 'client.product-request.store', 1, '2023-11-22 17:30:23', '2023-11-22 17:30:23'),
(221, 319, 94, 'edit', 'client.product-request.edit', 1, '2023-11-22 17:30:30', '2023-11-22 17:30:30'),
(222, 320, 94, 'update', 'client.product-request.update', 1, '2023-11-22 17:30:38', '2023-11-22 17:30:38'),
(223, 321, 94, 'delete', 'client.product-request.destroy', 1, '2023-11-22 17:30:48', '2023-11-22 17:30:48'),
(224, 322, 94, 'print voucher', 'client.product-request.show', 1, '2023-11-22 17:31:01', '2023-11-22 17:31:01'),
(225, 323, 45, 'create', 'admin.bulk-collection.create', 1, '2023-11-23 04:57:03', '2023-11-23 04:57:03'),
(226, 324, 45, 'store', 'admin.bulk-collection.store', 1, '2023-11-23 04:57:09', '2023-11-23 04:57:09'),
(227, 325, 45, 'edit', 'admin.bulk-collection.edit', 1, '2023-11-23 04:57:14', '2023-11-23 04:57:14'),
(228, 326, 45, 'update', 'admin.bulk-collection.update', 1, '2023-11-23 04:57:20', '2023-11-23 04:57:20'),
(229, 327, 45, 'delete', 'admin.bulk-collection.destroy', 1, '2023-11-23 04:57:29', '2023-11-23 04:57:29'),
(230, 328, 6, 'edit password', 'admin.user.password', 1, '2023-11-26 05:57:38', '2023-11-26 05:57:38'),
(231, 329, 6, 'update password', 'admin.user.password-update', 1, '2023-11-26 05:58:21', '2023-11-26 05:58:21'),
(232, 344, 105, 'store', 'admin.coa-setup.store', 1, '2023-12-13 12:12:03', '2023-12-13 12:12:03'),
(233, 345, 105, 'edit', 'admin.coa-setup.edit', 1, '2023-12-13 12:12:10', '2023-12-13 12:12:10'),
(234, 346, 105, 'update', 'admin.coa-setup.update', 1, '2023-12-13 12:12:17', '2023-12-13 12:12:17'),
(235, 347, 105, 'delete', 'admin.coa-setup.destroy', 1, '2023-12-13 12:12:25', '2023-12-13 12:12:25'),
(236, 348, 106, 'create', 'admin.debit-voucher-entry.create', 1, '2023-12-13 12:13:07', '2023-12-13 12:13:07'),
(237, 349, 106, 'store', 'admin.debit-voucher-entry.store', 1, '2023-12-13 12:13:16', '2023-12-13 12:13:16'),
(238, 350, 106, 'edit', 'admin.debit-voucher-entry.edit', 1, '2023-12-13 12:13:24', '2023-12-13 12:13:24'),
(239, 351, 106, 'update', 'admin.debit-voucher-entry.update', 1, '2023-12-13 12:13:31', '2023-12-13 12:13:31'),
(240, 352, 106, 'delete', 'admin.debit-voucher-entry.destroy', 1, '2023-12-13 12:13:38', '2023-12-13 12:13:38'),
(241, 353, 107, 'create', 'admin.credit-voucher-entry.create', 1, '2023-12-13 12:13:58', '2023-12-13 12:13:58'),
(242, 354, 107, 'store', 'admin.credit-voucher-entry.store', 1, '2023-12-13 12:14:08', '2023-12-13 12:14:08'),
(243, 355, 107, 'edit', 'admin.credit-voucher-entry.edit', 1, '2023-12-13 12:14:14', '2023-12-13 12:14:14'),
(244, 356, 107, 'update', 'admin.credit-voucher-entry.update', 1, '2023-12-13 12:14:21', '2023-12-13 12:14:21'),
(245, 357, 107, 'delete', 'admin.credit-voucher-entry.destroy', 1, '2023-12-13 12:14:27', '2023-12-13 12:14:27'),
(246, 358, 108, 'create', 'admin.journal-voucher-entry.create', 1, '2023-12-13 12:14:55', '2023-12-13 12:14:55'),
(247, 359, 108, 'store', 'admin.journal-voucher-entry.store', 1, '2023-12-13 12:15:01', '2023-12-13 12:15:01'),
(248, 360, 108, 'edit', 'admin.journal-voucher-entry.edit', 1, '2023-12-13 12:15:06', '2023-12-13 12:15:06'),
(249, 361, 108, 'update', 'admin.journal-voucher-entry.update', 1, '2023-12-13 12:15:12', '2023-12-13 12:15:12'),
(250, 362, 108, 'delete', 'admin.journal-voucher-entry.destroy', 1, '2023-12-13 12:15:18', '2023-12-13 12:15:18'),
(251, 363, 109, 'view', 'admin.voucher-approve.show', 1, '2023-12-13 12:16:48', '2023-12-13 12:16:48'),
(252, 364, 109, 'approve', 'admin.voucher-approve.edit', 1, '2023-12-13 12:16:56', '2023-12-13 12:16:56'),
(253, 365, 110, 'View', 'admin.voucher-reject.show', 1, '2023-12-13 12:17:48', '2023-12-13 12:17:48'),
(254, 366, 110, 'Refuse', 'admin.voucher-reject.edit', 1, '2023-12-13 12:18:00', '2023-12-13 12:18:00'),
(255, 367, 111, 'view', 'admin.automation-approve.show', 1, '2023-12-13 12:19:10', '2023-12-13 12:19:10'),
(256, 368, 111, 'approve', 'admin.automation-approve.edit', 1, '2023-12-13 12:19:19', '2023-12-13 12:19:19'),
(257, 369, 112, 'view', 'admin.automation-reject.show', 1, '2023-12-13 12:19:47', '2023-12-13 12:19:47'),
(258, 370, 112, 'Refuse', 'admin.automation-reject.edit', 1, '2023-12-13 12:19:54', '2023-12-13 12:20:22'),
(259, 387, 128, 'create', 'admin.pos-sales.create', 1, '2024-03-12 22:55:34', '2024-03-12 22:55:34'),
(260, 388, 128, 'store', 'admin.pos-sales.store', 1, '2024-03-12 22:55:40', '2024-03-12 22:55:40'),
(261, 389, 128, 'edit', 'admin.pos-sales.edit', 1, '2024-03-12 22:55:46', '2024-03-12 22:55:46'),
(262, 390, 128, 'update', 'admin.pos-sales.update', 1, '2024-03-12 22:55:57', '2024-03-12 22:55:57'),
(263, 391, 128, 'delete', 'admin.pos-sales.destroy', 1, '2024-03-12 22:56:05', '2024-03-12 22:56:05'),
(264, 395, 130, 'create', 'admin.size.create', 1, '2024-04-16 16:44:38', '2024-04-16 16:44:38'),
(265, 396, 130, 'store', 'admin.size.store', 1, '2024-04-16 16:44:43', '2024-04-16 16:44:43'),
(266, 397, 130, 'edit', 'admin.size.edit', 1, '2024-04-16 16:44:50', '2024-04-16 16:44:50'),
(267, 398, 130, 'update', 'admin.size.update', 1, '2024-04-16 16:44:55', '2024-04-16 16:44:55'),
(268, 399, 130, 'delete', 'admin.size.destroy', 1, '2024-04-16 16:45:02', '2024-04-16 16:45:02'),
(269, 400, 131, 'create', 'admin.color.create', 1, '2024-04-16 16:47:21', '2024-04-16 16:47:21'),
(270, 401, 131, 'store', 'admin.color.store', 1, '2024-04-16 16:47:28', '2024-04-16 16:47:28'),
(271, 402, 131, 'edit', 'admin.color.edit', 1, '2024-04-16 16:47:32', '2024-04-16 16:47:32'),
(272, 403, 131, 'update', 'admin.color.update', 1, '2024-04-16 16:47:38', '2024-04-16 16:47:38'),
(273, 404, 131, 'delete', 'admin.color.destroy', 1, '2024-04-16 16:47:45', '2024-04-16 16:47:45'),
(274, 405, 72, 'values', 'admin.attribute-value.index', 1, '2024-05-01 05:25:48', '2024-05-01 05:25:48'),
(275, 407, 132, 'create', 'admin.lifting-fashion-product.create', 1, '2024-06-01 10:36:09', '2024-06-01 10:36:09'),
(276, 408, 132, 'store', 'admin.lifting-fashion-product.store', 1, '2024-06-01 10:36:14', '2024-06-01 10:36:14'),
(277, 409, 132, 'edit', 'admin.lifting-fashion-product.edit', 1, '2024-06-01 10:36:19', '2024-06-01 10:36:19'),
(278, 410, 132, 'update', 'admin.lifting-fashion-product.update', 1, '2024-06-01 10:36:24', '2024-06-01 10:36:24'),
(279, 411, 132, 'delete', 'admin.lifting-fashion-product.destroy', 1, '2024-06-01 10:36:29', '2024-06-01 10:36:29'),
(280, 413, 133, 'create', 'admin.sales-lifestyle-product.create', 1, '2024-06-04 10:47:40', '2024-06-04 10:47:40'),
(281, 414, 133, 'store', 'admin.sales-lifestyle-product.store', 1, '2024-06-04 10:47:45', '2024-06-04 10:47:45'),
(282, 415, 133, 'edit', 'admin.sales-lifestyle-product.edit', 1, '2024-06-04 10:47:49', '2024-06-04 10:47:49'),
(283, 416, 133, 'update', 'admin.sales-lifestyle-product.update', 1, '2024-06-04 10:47:54', '2024-06-04 10:47:54'),
(284, 417, 133, 'delete', 'admin.sales-lifestyle-product.destroy', 1, '2024-06-04 10:48:00', '2024-06-04 10:48:00'),
(285, 419, 133, 'search edit', 'admin.sales-lifestyle-product.search-edit', 1, '2024-06-05 06:02:52', '2024-06-05 06:02:52'),
(286, 420, 134, 'create', 'admin.lifestyle-product-return.create', 1, '2024-06-05 06:04:38', '2024-06-05 06:04:38'),
(287, 421, 134, 'store', 'admin.lifestyle-product-return.store', 1, '2024-06-05 06:04:48', '2024-06-05 06:04:48'),
(288, 422, 134, 'edit', 'admin.lifestyle-product-return.edit', 1, '2024-06-05 06:04:52', '2024-06-05 06:04:52'),
(289, 423, 134, 'update', 'admin.lifestyle-product-return.update', 1, '2024-06-05 06:05:10', '2024-06-05 06:05:10'),
(290, 424, 134, 'delete', 'admin.lifestyle-product-return.destroy', 1, '2024-06-05 06:05:18', '2024-06-05 06:05:18'),
(291, 425, 43, 'search edit', 'admin.sales.search-edit', 1, '2024-06-05 06:15:09', '2024-06-05 06:15:09'),
(292, 426, 66, 'vehicle start', 'admin.delivery.delivered', 1, '2024-06-06 13:22:34', '2024-06-06 13:22:34'),
(293, 428, 135, 'create', 'admin.menu.create', 1, '2024-06-23 04:19:24', '2024-06-23 04:19:24'),
(294, 429, 135, 'store', 'admin.menu.store', 1, '2024-06-23 04:19:31', '2024-06-23 04:19:31'),
(295, 430, 135, 'edit', 'admin.menu.edit', 1, '2024-06-23 04:19:36', '2024-06-23 04:19:36'),
(296, 431, 135, 'update', 'admin.menu.update', 1, '2024-06-23 04:19:43', '2024-06-23 04:19:43'),
(297, 432, 135, 'delete', 'admin.menu.destroy', 1, '2024-06-23 04:19:51', '2024-06-23 04:19:51'),
(298, 433, 135, 'view items', 'admin.menu-item.index', 1, '2024-06-23 04:25:39', '2024-06-23 04:25:39'),
(299, 434, 135, 'update items', 'admin.menu-item.update', 1, '2024-06-23 04:25:47', '2024-06-23 04:25:47'),
(300, 435, 135, 'serialize items', 'admin.menu-item.serialize', 1, '2024-06-23 04:27:02', '2024-06-23 04:27:02'),
(301, 436, 135, 'delete item', 'admin.menu-item.destroy', 1, '2024-06-23 04:27:40', '2024-06-23 04:27:40'),
(302, 437, 12, 'update attribute', 'admin.product.attributes', 1, '2024-06-23 11:11:49', '2024-06-23 11:11:49'),
(303, 439, 136, 'create', 'admin.sections.create', 1, '2024-06-23 12:15:14', '2024-06-23 12:15:14'),
(304, 440, 136, 'store', 'admin.sections.store', 1, '2024-06-23 12:15:18', '2024-06-23 12:15:18'),
(305, 441, 136, 'edit', 'admin.sections.edit', 1, '2024-06-23 12:15:22', '2024-06-23 12:15:22'),
(306, 442, 136, 'update', 'admin.sections.update', 1, '2024-06-23 12:15:27', '2024-06-23 12:15:27'),
(307, 443, 136, 'delete', 'admin.sections.destroy', 1, '2024-06-23 12:15:31', '2024-06-23 12:15:31'),
(313, 451, 138, 'create', 'admin.running-sales.create', 1, '2024-07-13 12:39:23', '2024-07-13 12:39:23'),
(314, 452, 138, 'store', 'admin.running-sales.store', 1, '2024-07-13 12:39:29', '2024-07-13 12:39:29'),
(315, 453, 138, 'edit', 'admin.running-sales.edit', 1, '2024-07-13 12:39:40', '2024-07-13 12:39:40'),
(316, 454, 138, 'update', 'admin.running-sales.update', 1, '2024-07-13 12:40:12', '2024-07-13 12:40:12'),
(317, 455, 138, 'delete', 'admin.running-sales.destroy', 1, '2024-07-13 12:40:19', '2024-07-13 12:40:19'),
(319, 462, 143, 'create', 'investor.payment.create', 1, '2024-07-17 12:25:21', '2024-07-17 12:25:21'),
(320, 463, 143, 'store', 'investor.payment.store', 1, '2024-07-17 12:25:26', '2024-07-17 12:25:26'),
(321, 464, 143, 'edit', 'investor.payment.edit', 1, '2024-07-17 12:25:31', '2024-07-17 12:25:31'),
(322, 465, 143, 'update', 'investor.payment.update', 1, '2024-07-17 12:25:37', '2024-07-17 12:25:37'),
(323, 466, 143, 'delete', 'investor.payment.destroy', 1, '2024-07-17 12:25:44', '2024-07-17 12:25:44'),
(324, 467, 143, 'view', 'investor.payment.show', 1, '2024-07-17 12:47:17', '2024-07-17 12:47:17'),
(339, 488, 149, 'create', 'admin.investor-sattlement.create', 1, '2024-07-24 05:20:55', '2024-07-24 05:20:55'),
(340, 489, 149, 'store', 'admin.investor-sattlement.store', 1, '2024-07-24 05:20:59', '2024-07-24 05:20:59'),
(341, 490, 149, 'edit', 'admin.investor-sattlement.edit', 1, '2024-07-24 05:21:07', '2024-07-24 05:21:07'),
(342, 491, 149, 'update', 'admin.investor-sattlement.update', 1, '2024-07-24 05:21:12', '2024-07-24 05:21:12'),
(343, 492, 149, 'delete', 'admin.investor-sattlement.destroy', 1, '2024-07-24 05:21:17', '2024-07-24 05:21:17'),
(344, 496, 152, 'create', 'admin.investor.create', 1, '2024-07-29 02:26:07', '2024-07-29 02:26:07'),
(345, 497, 152, 'store', 'admin.investor.store', 1, '2024-07-29 02:26:21', '2024-07-29 02:26:21'),
(346, 498, 152, 'edit', 'admin.investor.edit', 1, '2024-07-29 02:26:30', '2024-07-29 02:26:30'),
(347, 499, 152, 'update', 'admin.investor.update', 1, '2024-07-29 02:26:37', '2024-07-29 02:26:37'),
(348, 500, 152, 'delete', 'admin.investor.destroy', 1, '2024-07-29 02:26:44', '2024-07-29 02:26:44'),
(349, 501, 152, 'update product', 'admin.investor-product.edit', 1, '2024-07-29 02:26:58', '2024-07-29 02:26:58'),
(350, 503, 153, 'create', 'admin.invest.create', 1, '2024-07-29 02:31:04', '2024-07-29 02:31:04'),
(351, 504, 153, 'store', 'admin.invest.store', 1, '2024-07-29 02:31:16', '2024-07-29 02:31:16'),
(352, 505, 153, 'edit', 'admin.invest.edit', 1, '2024-07-29 02:31:27', '2024-07-29 02:31:27'),
(353, 506, 153, 'update', 'admin.invest.update', 1, '2024-07-29 02:31:34', '2024-07-29 02:31:34'),
(354, 507, 153, 'delete', 'admin.invest.destroy', 1, '2024-07-29 02:31:42', '2024-07-29 02:31:42'),
(355, 509, 154, 'Approve', 'admin.approve-invest.edit', 1, '2024-07-29 02:36:05', '2024-07-29 02:36:05'),
(356, 510, 154, 'View', 'admin.approve-invest.show', 1, '2024-07-29 02:36:22', '2024-07-29 02:36:22'),
(357, 512, 155, 'create', 'admin.profit-distribute.create', 1, '2024-07-29 02:39:52', '2024-07-29 02:39:52'),
(358, 513, 155, 'store', 'admin.profit-distribute.store', 1, '2024-07-29 02:40:16', '2024-07-29 02:40:16'),
(359, 514, 155, 'edit', 'admin.profit-distribute.edit', 1, '2024-07-29 02:40:21', '2024-07-29 02:40:21'),
(360, 515, 155, 'update', 'admin.profit-distribute.update', 1, '2024-07-29 02:40:26', '2024-07-29 02:40:26'),
(361, 516, 155, 'delete', 'admin.profit-distribute.destroy', 1, '2024-07-29 02:40:31', '2024-07-29 02:40:31'),
(362, 522, 160, 'create', 'admin.payment.create', 1, '2024-08-01 07:45:12', '2024-08-01 07:45:12'),
(363, 523, 160, 'store', 'admin.payment.store', 1, '2024-08-01 07:45:16', '2024-08-01 07:45:16'),
(364, 524, 160, 'edit', 'admin.payment.edit', 1, '2024-08-01 07:45:20', '2024-08-01 07:45:20'),
(365, 525, 160, 'update', 'admin.payment.update', 1, '2024-08-01 07:45:26', '2024-08-01 07:45:26'),
(366, 526, 160, 'delete', 'admin.payment.destroy', 1, '2024-08-01 07:45:32', '2024-08-01 07:45:32');

-- --------------------------------------------------------

--
-- Table structure for table `admin_settings`
--

CREATE TABLE `admin_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `logo` varchar(255) NOT NULL,
  `favicon` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `footer_text` varchar(255) NOT NULL,
  `secondary_color` varchar(255) DEFAULT NULL,
  `primary_color` varchar(255) DEFAULT NULL,
  `compare_chart_start` date DEFAULT NULL,
  `compare_chart_end` date DEFAULT NULL,
  `accounting` tinyint(4) NOT NULL DEFAULT 0,
  `facebook` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `google` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_settings`
--

INSERT INTO `admin_settings` (`id`, `logo`, `favicon`, `title`, `footer_text`, `secondary_color`, `primary_color`, `compare_chart_start`, `compare_chart_end`, `accounting`, `facebook`, `twitter`, `linkedin`, `whatsapp`, `google`, `created_at`, `updated_at`) VALUES
(1, 'media/admin-setting/2024-07-07-AsM1pGPyY1KHZcGtATlUcYaW1iTSVZPck364wzUH.webp', 'media/admin-setting/2024-07-07-hbPTw0f19yyapwcXwMrRfzQozaOurSBwoT8hConO.webp', 'Prime Foods', '© 2024 Developed by <a target=\"_blank\" href=\"http://www.technoparkbd.com/\">Techno Park Bangladesh</a>', '#7d1c1f', '#a92924', '2024-07-01', '2024-08-31', 1, NULL, NULL, NULL, NULL, NULL, '2023-09-19 09:03:18', '2024-07-07 12:24:28');

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `region_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `incharge_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `company_id`, `region_id`, `code`, `name`, `incharge_name`, `phone`, `email`, `address`, `status`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(15, 1, 1, 'ADS', 'Dhaka South', NULL, NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, '2023-12-24 05:00:49', '2023-12-24 05:00:49'),
(16, 1, 10, 'ADN', 'Dhaka North', NULL, NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, '2023-12-24 05:01:08', '2023-12-24 05:01:08'),
(17, 1, 13, 'PF', 'Area One', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2024-07-13 12:44:28', '2024-07-13 12:44:28');

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'Consumer',
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `company_id`, `name`, `type`, `status`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 1, 'Pcs', 'Consumer', 1, 1, NULL, NULL, NULL, '2023-09-29 03:29:41', '2023-09-29 03:29:41'),
(11, 1, 'KG', 'Consumer', 1, 2, NULL, NULL, NULL, '2024-07-25 05:18:31', '2024-07-25 05:18:31');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_values`
--

CREATE TABLE `attribute_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attribute_id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `vat` varchar(255) DEFAULT NULL,
  `tin` varchar(255) DEFAULT NULL,
  `trade_license` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `company_id`, `prefix`, `name`, `contact_person`, `email`, `phone`, `fax`, `website`, `vat`, `tin`, `trade_license`, `address`, `status`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'AHQ', 'Head Office', 'Mamunur Rashid', 'sales@salestrackerbd.com', '01844000103', NULL, 'www.alifinefoods.com', NULL, NULL, 'zz', 'Dhaka', 1, 1, 1, 1, NULL, '2023-09-23 06:52:33', '2024-02-04 05:52:40'),
(2, 1, 'ATS', 'Store and Operational Office', 'xyz', 'sales@salestrackerbd.com', '01844000104', NULL, NULL, NULL, NULL, '12345', 'Dhaka', 1, 2, 1, NULL, NULL, '2023-11-07 07:50:49', '2024-02-04 05:52:53');

-- --------------------------------------------------------

--
-- Table structure for table `bulk_collections`
--

CREATE TABLE `bulk_collections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `payment_type` varchar(255) NOT NULL DEFAULT 'Cash',
  `coa_setup_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulk_collection_lists`
--

CREATE TABLE `bulk_collection_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bulk_collection_id` bigint(20) UNSIGNED NOT NULL,
  `collection_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `sales_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_amount` decimal(16,2) NOT NULL,
  `paid_amount` decimal(16,2) NOT NULL,
  `money_receipt` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `meta_title` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `featured` tinyint(4) NOT NULL DEFAULT 0,
  `order` bigint(20) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `show_frontend` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `company_id`, `parent_id`, `name`, `slug`, `image`, `meta_title`, `meta_keyword`, `meta_description`, `featured`, `order`, `status`, `show_frontend`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(31, 1, NULL, 'Chicken Item', 'chicken-item', NULL, 'Poultry', NULL, NULL, 0, 0, 1, 1, 1, 1, NULL, NULL, '2024-07-07 11:44:44', '2024-07-25 06:26:03'),
(43, 1, NULL, 'Frozen Foods', 'frozen-foods', NULL, 'Protein Foods', NULL, NULL, 0, 0, 1, 1, 1, 2, NULL, NULL, '2024-07-07 12:21:19', '2024-07-28 07:11:33'),
(44, 1, NULL, 'Dairy Item', 'dairy-item', NULL, 'Dairy', NULL, NULL, 0, 0, 1, 1, 1, 1, NULL, NULL, '2024-07-07 12:21:32', '2024-07-25 07:04:41'),
(46, 1, 31, 'Sub Category', 'sub-category', NULL, NULL, NULL, NULL, 0, 0, 1, 1, 1, NULL, 2, '2024-07-25 10:21:01', '2024-07-25 07:20:11', '2024-07-25 10:21:01');

-- --------------------------------------------------------

--
-- Table structure for table `category_vendors`
--

CREATE TABLE `category_vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_vendors`
--

INSERT INTO `category_vendors` (`id`, `category_id`, `vendor_id`, `created_at`, `updated_at`) VALUES
(26, 9, 1, '2023-10-19 06:38:23', '2023-10-19 06:38:23'),
(77, 31, 1, '2024-07-25 06:26:03', '2024-07-25 06:26:03'),
(78, 44, 1, '2024-07-25 07:04:41', '2024-07-25 07:04:41'),
(79, 43, 1, '2024-07-28 07:11:33', '2024-07-28 07:11:33');

-- --------------------------------------------------------

--
-- Table structure for table `chain_clients`
--

CREATE TABLE `chain_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chain_clients`
--

INSERT INTO `chain_clients` (`id`, `company_id`, `name`, `contact_person`, `email`, `phone`, `address`, `status`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'KFC', NULL, NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, '2023-11-09 13:50:00', '2023-11-09 13:50:00'),
(2, 1, 'mr. Alamgir', 'Mr. Delower Hussain', NULL, '01715622915', '089796564', 1, 2, NULL, NULL, NULL, '2023-12-21 07:33:34', '2023-12-21 07:33:34');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `coa_setup_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference_by` bigint(20) UNSIGNED DEFAULT NULL,
  `client_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `area_id` bigint(20) UNSIGNED NOT NULL,
  `territory_id` bigint(20) UNSIGNED NOT NULL,
  `code` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `credit_limit` decimal(16,2) DEFAULT NULL,
  `bin_no` bigint(20) DEFAULT NULL,
  `chain_client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `is_chain` tinyint(4) NOT NULL DEFAULT 0,
  `is_vat` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `company_id`, `user_id`, `coa_setup_id`, `reference_by`, `client_category_id`, `area_id`, `territory_id`, `code`, `name`, `contact_person`, `phone`, `email`, `address`, `credit_limit`, `bin_no`, `chain_client_id`, `discount`, `status`, `is_chain`, `is_vat`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3000, 1, 43, 94, 50, NULL, 15, 50, 2003, 'AK Cup Chaa (Basabo)', 'Owner', '01748343864', NULL, NULL, 150000.00, NULL, NULL, 0.00, 1, 0, 0, 2, 2, 1, NULL, '2023-12-26 15:29:43', '2024-07-27 10:53:34'),
(3002, 1, 45, 51, 50, NULL, 15, 50, 2005, 'Alhamdaullah Food Corner', 'Owner', '01580786943', NULL, NULL, 2500.00, NULL, NULL, 0.00, 1, 0, 0, 2, 2, NULL, NULL, '2023-12-26 15:38:25', '2024-07-28 09:43:33'),
(3008, 1, 51, 64, 50, 0, 15, 50, 2011, 'AK Cup Chaa (Mugda)', NULL, '01619541489', NULL, NULL, 50000.00, NULL, NULL, 0.00, 1, 0, 0, 2, 2, NULL, NULL, '2023-12-27 12:07:46', '2024-07-23 12:02:45'),
(3028, 1, 76, 65, 51, 0, 16, 51, 2031, 'Bonton Foods Limited', 'Mr. Rafiqul Islam', '017', NULL, 'Mogbazar, Dhaka', 500000.00, NULL, NULL, 0.00, 1, 0, 0, 2, 2, NULL, NULL, '2024-02-03 11:06:50', '2024-07-23 12:04:58');

-- --------------------------------------------------------

--
-- Table structure for table `client_categories`
--

CREATE TABLE `client_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_categories`
--

INSERT INTO `client_categories` (`id`, `company_id`, `name`, `status`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'RETAILER', 1, 1, NULL, 2, '2024-05-12 04:55:39', '2023-09-23 15:05:44', '2024-05-12 04:55:39'),
(2, 1, 'DISTRIBUTOR', 1, 1, NULL, 2, '2024-05-12 04:55:39', '2023-09-23 15:05:10', '2024-05-12 04:55:39'),
(3, 1, 'HOTEL', 1, 1, 2, 2, '2024-05-12 04:55:39', '2023-09-23 15:05:27', '2024-05-12 04:55:39'),
(4, 1, 'CORPORATE', 1, 1, NULL, 2, '2024-05-12 04:55:39', '2023-09-23 15:05:59', '2024-05-12 04:55:39'),
(5, 1, 'OTHERS', 1, 1, NULL, 2, '2024-05-12 04:55:39', '2023-09-23 15:06:18', '2024-05-12 04:55:39'),
(6, 1, 'RESTAURANT', 1, 2, NULL, 2, '2024-05-12 04:55:39', '2023-11-07 08:19:04', '2024-05-12 04:55:39'),
(7, 1, 'Hospital', 1, 2, NULL, 2, '2024-05-12 04:55:39', '2023-11-07 08:19:33', '2024-05-12 04:55:39'),
(8, 1, 'Club', 1, 2, NULL, 2, '2024-05-12 04:55:39', '2023-11-13 04:32:44', '2024-05-12 04:55:39'),
(9, 1, 'Super Shop', 1, 2, NULL, 2, '2024-05-12 04:55:39', '2023-12-27 18:39:54', '2024-05-12 04:55:39');

-- --------------------------------------------------------

--
-- Table structure for table `client_messages`
--

CREATE TABLE `client_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_prices`
--

CREATE TABLE `client_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `default_price` decimal(16,2) NOT NULL,
  `client_price` decimal(16,2) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coa_setups`
--

CREATE TABLE `coa_setups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `head_code` bigint(20) NOT NULL,
  `head_name` varchar(255) NOT NULL,
  `transaction` tinyint(4) NOT NULL DEFAULT 0,
  `general` tinyint(4) NOT NULL DEFAULT 0,
  `head_type` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `updateable` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coa_setups`
--

INSERT INTO `coa_setups` (`id`, `company_id`, `parent_id`, `head_code`, `head_name`, `transaction`, `general`, `head_type`, `status`, `updateable`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, 'Assets', 0, 0, 'A', 1, 0, 1, NULL, NULL, NULL, '2023-12-07 07:14:30', '2023-12-07 07:14:30'),
(2, 1, NULL, 2, 'Liabilities', 0, 0, 'L', 1, 0, 1, 1, NULL, NULL, '2023-12-07 07:14:59', '2023-12-07 10:24:11'),
(3, 1, NULL, 3, 'Income', 0, 0, 'I', 1, 0, 1, NULL, NULL, NULL, '2023-12-07 07:17:15', '2023-12-07 07:17:15'),
(4, 1, NULL, 4, 'Expense', 0, 0, 'E', 1, 0, 1, 2, NULL, NULL, '2023-12-07 07:17:44', '2024-02-29 13:56:50'),
(18, 1, 1, 101, 'Current Asset', 0, 0, 'A', 1, 0, 1, 2, NULL, NULL, '2023-12-12 14:10:35', '2024-02-28 15:15:38'),
(19, 1, 18, 10101, 'Cash Receivable', 0, 1, 'A', 1, 0, 1, 2, NULL, NULL, '2023-12-12 14:12:01', '2024-02-03 11:21:41'),
(21, 1, 3, 301, 'Direct Income', 0, 0, 'I', 1, 0, 1, 2, NULL, NULL, '2023-12-12 15:55:05', '2023-12-30 06:50:11'),
(23, 1, 18, 10102, 'Cash In Hand', 0, 1, 'A', 1, 0, 1, 2, NULL, NULL, '2023-12-13 03:34:03', '2024-02-28 15:19:15'),
(24, 1, 18, 10103, 'Cash at Bank', 0, 1, 'A', 1, 0, 1, 2, NULL, NULL, '2023-12-13 12:55:18', '2024-02-29 13:26:34'),
(26, 1, 23, 1010201, 'Cash at Fattah', 1, 0, 'A', 1, 1, 1, 2, NULL, NULL, '2023-12-13 12:57:46', '2024-07-28 07:41:42'),
(27, 1, 4, 401, 'Administrative Exp', 0, 0, 'E', 1, 1, 1, NULL, NULL, NULL, '2023-12-13 13:10:45', '2023-12-13 13:10:45'),
(28, 1, 27, 40101, 'Salary Expence', 0, 1, 'E', 1, 1, 1, 2, NULL, NULL, '2023-12-13 13:11:22', '2024-08-04 04:27:12'),
(30, 1, 2, 201, 'Cash Payable', 0, 1, 'L', 1, 0, 1, 2, NULL, NULL, '2023-12-13 13:12:36', '2024-02-28 15:23:17'),
(32, 1, 21, 30102, 'Sales Return', 1, 1, 'I', 1, 0, 1, 1, NULL, NULL, '2023-12-13 15:39:24', '2023-12-13 15:39:36'),
(34, 1, 2, 202, 'Share Capital', 0, 1, 'L', 1, 1, 2, NULL, NULL, NULL, '2023-12-30 06:40:56', '2023-12-30 06:40:56'),
(36, 1, 1, 102, 'Fixed Asset', 0, 0, 'A', 1, 0, 2, NULL, NULL, NULL, '2023-12-30 06:44:47', '2023-12-30 06:44:47'),
(37, 1, 36, 10201, 'Furniture', 1, 1, 'A', 1, 1, 2, 2, NULL, NULL, '2023-12-30 06:45:34', '2024-02-29 13:27:12'),
(42, 1, 3, 302, 'Indirect Income', 0, 1, 'I', 1, 1, 2, NULL, NULL, NULL, '2023-12-30 06:49:13', '2023-12-30 06:49:13'),
(43, 1, 27, 40102, 'Convince Bill', 0, 1, 'E', 1, 1, 2, 2, NULL, NULL, '2023-12-30 06:52:44', '2024-02-29 13:57:04'),
(44, 1, 43, 4010201, 'Convene Mukit Vai', 1, 0, 'E', 1, 1, 2, 2, NULL, NULL, '2023-12-30 06:53:12', '2024-02-28 15:28:09'),
(46, 1, 36, 10202, 'IT Infrastructure', 0, 1, 'A', 1, 1, 2, NULL, NULL, NULL, '2023-12-30 07:23:04', '2023-12-30 07:23:04'),
(47, 1, 46, 1020201, 'Management Software', 1, 0, 'A', 1, 1, 2, 2, NULL, NULL, '2023-12-30 07:23:21', '2024-07-29 02:33:47'),
(52, 1, 46, 1020202, 'Compurter / Accessories', 1, 0, 'A', 1, 1, 2, 2, NULL, NULL, '2024-02-03 11:28:30', '2024-08-04 04:38:30'),
(60, 1, 2, 203, 'Short Term Loan', 0, 0, 'L', 1, 1, 2, 2, NULL, NULL, '2024-07-22 04:55:32', '2024-07-25 11:20:20'),
(72, 1, 60, 20301, 'Product Investment', 0, 1, 'L', 1, 1, 2, NULL, NULL, NULL, '2024-07-25 11:20:33', '2024-07-25 11:20:33'),
(73, 1, 60, 20302, 'Business Loan', 0, 0, 'L', 1, 1, 2, NULL, NULL, NULL, '2024-07-25 11:21:19', '2024-07-25 11:21:19'),
(74, 1, 72, 2030101, 'Sazidul Islam Sumon', 1, 0, 'L', 1, 1, 2, 2, NULL, NULL, '2024-07-25 11:21:54', '2024-07-28 07:44:17'),
(75, 1, 72, 2030102, 'MD. Hasan', 1, 0, 'L', 1, 1, 2, 2, NULL, NULL, '2024-07-25 11:22:05', '2024-07-27 09:17:03'),
(76, 1, 73, 2030201, 'Bank Loan', 0, 1, 'L', 1, 1, 2, NULL, NULL, NULL, '2024-07-25 11:22:27', '2024-07-25 11:22:27'),
(77, 1, 73, 2030202, 'Personal Loan', 0, 1, 'L', 1, 1, 2, NULL, NULL, NULL, '2024-07-25 11:22:41', '2024-07-25 11:22:41'),
(78, 1, 21, 30103, 'Whole Sale', 1, 1, 'I', 1, 1, 2, 2, NULL, NULL, '2024-07-25 11:24:17', '2024-07-25 11:25:04'),
(79, 1, 21, 30104, 'Retail Sale', 1, 1, 'I', 1, 1, 2, NULL, NULL, NULL, '2024-07-25 11:24:36', '2024-07-25 11:24:36'),
(80, 1, 21, 30105, 'Online Sale', 1, 1, 'I', 1, 1, 2, 2, NULL, NULL, '2024-07-25 11:24:50', '2024-07-25 11:24:54'),
(84, 1, 4, 402, 'Operational Expence', 0, 1, 'E', 1, 1, 2, 2, NULL, NULL, '2024-07-25 11:27:14', '2024-08-04 04:28:24'),
(85, 1, 4, 403, 'Business Expence', 0, 0, 'E', 1, 1, 2, 2, NULL, NULL, '2024-07-25 11:27:30', '2024-08-04 04:28:02'),
(86, 1, 27, 40103, 'Software/IT Service', 1, 0, 'E', 1, 1, 2, NULL, NULL, NULL, '2024-07-25 11:27:56', '2024-07-25 11:27:56'),
(88, 1, 85, 40301, 'Product Purchase', 0, 1, 'E', 1, 1, 2, NULL, NULL, NULL, '2024-07-25 11:29:07', '2024-07-25 11:29:07'),
(89, 1, 85, 40302, 'Investment Profit', 0, 1, 'E', 1, 1, 2, NULL, NULL, NULL, '2024-07-25 11:29:46', '2024-07-25 11:29:46'),
(90, 1, 89, 4030201, 'Sazid Alam Sumon', 1, 0, 'E', 1, 1, 2, NULL, NULL, NULL, '2024-07-25 11:30:10', '2024-07-25 11:30:10'),
(91, 1, 42, 30201, 'Scrap Sales', 1, 0, 'I', 1, 1, 2, NULL, NULL, NULL, '2024-07-25 11:32:49', '2024-07-25 11:32:49'),
(92, 1, 42, 30202, 'Investment Profit', 1, 0, 'I', 1, 1, 2, NULL, NULL, NULL, '2024-07-25 11:33:25', '2024-07-25 11:33:25'),
(93, 1, 42, 30203, 'Bank Interest', 1, 0, 'I', 1, 1, 2, NULL, NULL, NULL, '2024-07-25 11:33:33', '2024-07-25 11:33:33'),
(95, 1, 72, 2030103, 'Dr. Emran', 1, 0, 'L', 1, 1, 2, NULL, NULL, NULL, '2024-07-30 14:09:29', '2024-07-30 14:09:29'),
(96, 1, 72, 2030104, 'Fattah', 1, 0, 'L', 1, 1, 2, NULL, NULL, NULL, '2024-07-30 14:09:39', '2024-07-30 14:09:39'),
(97, 1, 72, 2030105, 'Korban', 1, 0, 'L', 1, 1, 2, NULL, NULL, NULL, '2024-07-30 14:09:48', '2024-07-30 14:09:48'),
(98, 1, 34, 20201, 'Mamunur Rashid Fattah', 1, 0, 'L', 1, 1, 2, NULL, NULL, NULL, '2024-08-04 04:17:56', '2024-08-04 04:17:56'),
(99, 1, 34, 20202, 'Kurban', 1, 0, 'L', 1, 1, 2, NULL, NULL, NULL, '2024-08-04 04:18:23', '2024-08-04 04:18:23'),
(100, 1, 34, 20203, 'Hasan', 1, 0, 'L', 1, 1, 2, NULL, NULL, NULL, '2024-08-04 04:18:32', '2024-08-04 04:18:32');

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

CREATE TABLE `collections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `payment_no` varchar(255) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_type` varchar(255) NOT NULL,
  `collection_type` varchar(255) NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `sales_id` bigint(20) UNSIGNED DEFAULT NULL,
  `on_return` tinyint(4) NOT NULL DEFAULT 0,
  `approved` tinyint(4) NOT NULL DEFAULT 0,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `collection_data`
--

CREATE TABLE `collection_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `collection_id` bigint(20) UNSIGNED NOT NULL,
  `sales_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `prefix` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `vat` varchar(255) DEFAULT NULL,
  `tin` varchar(255) DEFAULT NULL,
  `trade_license` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `prefix`, `name`, `username`, `email`, `phone`, `fax`, `website`, `vat`, `tin`, `trade_license`, `address`, `logo`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'PF', 'Prime Foods', 'primefoods', 'info@primefoods.com', '01844000103', '01844000103', 'Website: www.primefoods.com', '5', '654', '6464565', 'House # 10/B Road No 6, Dhaka 1205', 'media/company/2024-03-14-m3q9yih4Cv0p9iPCuz4dMBsvMB2L9jQQeNoxNEPS.webp', 1, 2, NULL, NULL, '2023-09-23 06:52:33', '2024-07-30 05:52:34');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `heading` varchar(255) NOT NULL,
  `title` text DEFAULT NULL,
  `address` text NOT NULL,
  `work_time` text NOT NULL,
  `primary_mobile` varchar(255) NOT NULL,
  `primary_email` varchar(255) NOT NULL,
  `secondary_mobile` varchar(255) DEFAULT NULL,
  `secondary_email` varchar(255) NOT NULL,
  `map_url` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `heading`, `title`, `address`, `work_time`, `primary_mobile`, `primary_email`, `secondary_mobile`, `secondary_email`, `map_url`, `created_at`, `updated_at`) VALUES
(1, 'GET IN TOUCH', 'Don’t hesitate to contact us directly so that we can think together about a solution.', '<p style=\"margin-right: 0px; margin-bottom: 0px; margin-left: 0px; border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / .5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, \" segoe=\"\" ui\",=\"\" roboto,=\"\" \"helvetica=\"\" neue\",=\"\" arial,=\"\" \"noto=\"\" sans\",=\"\" sans-serif,=\"\" \"apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" emoji\";=\"\" font-size:=\"\" medium;\"=\"\">৩১৬/২, ৬-বি, ডিআইটি রোড,  পূর্ব রামপুরা, ঢাকা-১২১৯।<br></p>', '<span segoe=\"\" ui\",=\"\" roboto,=\"\" \"helvetica=\"\" neue\",=\"\" arial,=\"\" \"noto=\"\" sans\",=\"\" sans-serif,=\"\" \"apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" emoji\";=\"\" font-size:=\"\" medium;\"=\"\" style=\"border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / .5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; color: rgb(71, 85, 105); font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, \"Noto Sans\", sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol\", \"Noto Color Emoji\"; font-size: medium;\">Monday to Friday</span><br segoe=\"\" ui\",=\"\" roboto,=\"\" \"helvetica=\"\" neue\",=\"\" arial,=\"\" \"noto=\"\" sans\",=\"\" sans-serif,=\"\" \"apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" emoji\";=\"\" font-size:=\"\" medium;\"=\"\" style=\"border-width: 0px; border-style: solid; border-color: currentcolor; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; color: rgb(71, 85, 105); font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, \"Noto Sans\", sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol\", \"Noto Color Emoji\"; font-size: medium;\"><span segoe=\"\" ui\",=\"\" roboto,=\"\" \"helvetica=\"\" neue\",=\"\" arial,=\"\" \"noto=\"\" sans\",=\"\" sans-serif,=\"\" \"apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" emoji\";=\"\" font-size:=\"\" medium;\"=\"\" style=\"border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / .5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; color: rgb(71, 85, 105); font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, \"Noto Sans\", sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol\", \"Noto Color Emoji\"; font-size: medium;\">7 a.m. 12 p.m. – 1 p.m. 4 p.m.</span><br segoe=\"\" ui\",=\"\" roboto,=\"\" \"helvetica=\"\" neue\",=\"\" arial,=\"\" \"noto=\"\" sans\",=\"\" sans-serif,=\"\" \"apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" emoji\";=\"\" font-size:=\"\" medium;\"=\"\" style=\"border-width: 0px; border-style: solid; border-color: currentcolor; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; color: rgb(71, 85, 105); font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, \"Noto Sans\", sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol\", \"Noto Color Emoji\"; font-size: medium;\"><br segoe=\"\" ui\",=\"\" roboto,=\"\" \"helvetica=\"\" neue\",=\"\" arial,=\"\" \"noto=\"\" sans\",=\"\" sans-serif,=\"\" \"apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" emoji\";=\"\" font-size:=\"\" medium;\"=\"\" style=\"border-width: 0px; border-style: solid; border-color: currentcolor; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; color: rgb(71, 85, 105); font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, \"Noto Sans\", sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol\", \"Noto Color Emoji\"; font-size: medium;\"><span segoe=\"\" ui\",=\"\" roboto,=\"\" \"helvetica=\"\" neue\",=\"\" arial,=\"\" \"noto=\"\" sans\",=\"\" sans-serif,=\"\" \"apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" emoji\";=\"\" font-size:=\"\" medium;\"=\"\" style=\"border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / .5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; color: rgb(71, 85, 105); font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, \"Noto Sans\", sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol\", \"Noto Color Emoji\"; font-size: medium;\">Saturday</span><br segoe=\"\" ui\",=\"\" roboto,=\"\" \"helvetica=\"\" neue\",=\"\" arial,=\"\" \"noto=\"\" sans\",=\"\" sans-serif,=\"\" \"apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" emoji\";=\"\" font-size:=\"\" medium;\"=\"\" style=\"border-width: 0px; border-style: solid; border-color: currentcolor; --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; color: rgb(71, 85, 105); font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, \"Noto Sans\", sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol\", \"Noto Color Emoji\"; font-size: medium;\"><span segoe=\"\" ui\",=\"\" roboto,=\"\" \"helvetica=\"\" neue\",=\"\" arial,=\"\" \"noto=\"\" sans\",=\"\" sans-serif,=\"\" \"apple=\"\" color=\"\" emoji\",=\"\" \"segoe=\"\" ui=\"\" symbol\",=\"\" emoji\";=\"\" font-size:=\"\" medium;\"=\"\" style=\"border-width: 0px; border-style: solid; border-color: rgb(229, 231, 235); --tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / .5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; color: rgb(71, 85, 105); font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, \"Helvetica Neue\", Arial, \"Noto Sans\", sans-serif, \"Apple Color Emoji\", \"Segoe UI Emoji\", \"Segoe UI Symbol\", \"Noto Color Emoji\"; font-size: medium;\">8 a.m. 2 p.m.</span>', '01916304877', 'info@primefoodsbd.com', NULL, 'sales@primefoodsbd.com', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.6713598084884!2d90.41308638555972!3d23.759096033445807!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755b8792521cc35%3A0xd5aaf741fec33d8e!2s316%2C%206%20DIT%20Rd%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1722152464023!5m2!1sen!2sbd', '2024-02-27 09:09:56', '2024-07-28 07:39:59');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_man_id` bigint(20) UNSIGNED NOT NULL,
  `serial_no` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(16,2) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `delivered` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_charges`
--

CREATE TABLE `delivery_charges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inside_charge` decimal(8,2) NOT NULL DEFAULT 0.00,
  `outside_charge` decimal(8,2) NOT NULL DEFAULT 0.00,
  `upto` decimal(8,2) NOT NULL DEFAULT 0.00,
  `charge_for_extra` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_charges`
--

INSERT INTO `delivery_charges` (`id`, `inside_charge`, `outside_charge`, `upto`, `charge_for_extra`, `created_at`, `updated_at`) VALUES
(1, 70.00, 150.00, 0.00, 0.00, '2023-11-18 04:06:32', '2023-11-18 04:10:47');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_lists`
--

CREATE TABLE `delivery_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `sales_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `rate` decimal(16,2) NOT NULL,
  `qty` decimal(16,2) NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `sales_list_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `details_cards`
--

CREATE TABLE `details_cards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `serial` bigint(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `details_cards`
--

INSERT INTO `details_cards` (`id`, `title`, `description`, `serial`, `status`, `created_at`, `updated_at`) VALUES
(1, 'President’s Award for Industrial Development, 2014', '<div style=\"text-align: justify;\"><span style=\"background-color: var(--bs-white); text-align: var(--bs-body-text-align); font-size: 16px;\"><font color=\"#334155\" face=\"-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Oxygen-Sans, Ubuntu, Cantarell, Helvetica Neue, sans-serif\">BRB Cable Industries Ltd. Has been awarded the President Award for Industrial Development, 2014 in recognition of remarkable contribution in Industrialization, Manufacturing and creating Employment Opportunity at the Private sector in Bangladesh. Honorable President of the People’s Republic of Bangladesh handed over the “President Award for Industrial Development Trophy-2014” to Mr. Mozibar Rahman, Chairman, BRB Cable Industries Ltd. at Osmani Smriti Milonayoton in Dhaka on 30th March, 2016.</font></span></div>', 5, 1, '2023-10-03 13:07:42', '2024-05-13 06:16:01'),
(2, 'National Productivity and Quality Excellence Award 2018', '<div style=\"text-align: justify;\"><font color=\"#334155\" face=\"-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Oxygen-Sans, Ubuntu, Cantarell, Helvetica Neue, sans-serif\"><span style=\"font-size: 16px;\">BRB Cable Industries Ltd. has been awarded the National Productivity and Quality Excellence Award 2018 by The National Productivity Organization (NPO), under Ministry of Industry, in recognition of it’s remarkable contribution in improving the quality of National Industrial Productivity and the Products as well. The Managing Director Md. Parvez Rahman, BRB Cable Industries Ltd., receives the Trophy and the Certificate from Honorable Minister Mr. Nurul Majid Mahmud Humayun, MP, Ministry of Industry, Government of the People’s Republic of Bangladesh, at Institution of Diploma Engineers Bangladesh, IDEB Bhaban, Kakrail, on 28 July, 2019.</span></font><br></div>', 2, 1, '2023-10-03 13:08:10', '2024-05-13 06:18:55'),
(3, 'President’s Award for Industrial Development, 2016', '<p style=\"text-align: center; \">BRB Polymer Ltd. Has been awarded ‘President’s Award for Industrial Development-2016’ in recognition of remarkable contribution in Industrialization, Manufacturing and creating Employment Opportunity at the Private sector in Bangladesh. In favor of the Managing Director of BRB Polymer Md. Mozibar Rahman, Md. Parvez Rahman, Director of BRB Polymer Ltd., receives the award from Honorable President H.E Mr. Md. Abdul Hamid, Peoples Republic of Bangladesh at Osmani Memorial Auditorium, Dhaka on 22 May, 2018<br></p>', 3, 1, '2023-10-03 13:08:26', '2024-05-13 06:17:51');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `team_leader` bigint(20) UNSIGNED NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `company_id`, `name`, `team_leader`, `status`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(6, 1, 'new group', 42, 1, 2, NULL, NULL, NULL, '2024-03-20 04:52:01', '2024-03-20 04:52:01');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`id`, `group_id`, `staff_id`, `created_at`, `updated_at`) VALUES
(17, 6, 53, '2024-03-20 04:52:01', '2024-03-20 04:52:01'),
(18, 6, 42, '2024-03-20 04:52:01', '2024-03-20 04:52:01'),
(19, 6, 50, '2024-03-20 04:52:01', '2024-03-20 04:52:01');

-- --------------------------------------------------------

--
-- Table structure for table `group_sales_targets`
--

CREATE TABLE `group_sales_targets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `year` varchar(255) NOT NULL,
  `month` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `total_target` decimal(16,2) NOT NULL,
  `total_target_amount` decimal(16,2) NOT NULL,
  `target_type` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_sales_target_categories`
--

CREATE TABLE `group_sales_target_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_sales_target_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `target` decimal(16,2) NOT NULL,
  `target_amount` decimal(16,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `home_sections`
--

CREATE TABLE `home_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `banner_link` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_sections`
--

INSERT INTO `home_sections` (`id`, `category_id`, `banner`, `banner_link`, `order`, `status`, `created_at`, `updated_at`) VALUES
(7, 31, NULL, NULL, 1, 1, '2024-07-25 06:57:35', '2024-07-25 06:57:35'),
(8, 44, NULL, NULL, 2, 0, '2024-07-25 06:58:39', '2024-07-28 07:09:54'),
(9, 43, NULL, NULL, 3, 1, '2024-07-25 06:58:45', '2024-07-25 06:58:45');

-- --------------------------------------------------------

--
-- Table structure for table `home_section_sub_categories`
--

CREATE TABLE `home_section_sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `home_section_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_section_sub_categories`
--

INSERT INTO `home_section_sub_categories` (`id`, `home_section_id`, `category_id`, `created_at`, `updated_at`) VALUES
(19, 7, 46, '2024-07-25 07:20:20', '2024-07-25 07:20:20');

-- --------------------------------------------------------

--
-- Table structure for table `investors`
--

CREATE TABLE `investors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `coa_setup_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `nid` varchar(255) DEFAULT NULL,
  `bkash` varchar(255) DEFAULT NULL,
  `rocket` varchar(255) DEFAULT NULL,
  `nagad` varchar(255) DEFAULT NULL,
  `bank` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `account_no` varchar(255) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investors`
--

INSERT INTO `investors` (`id`, `company_id`, `user_id`, `coa_setup_id`, `name`, `image`, `email`, `phone`, `address`, `nid`, `bkash`, `rocket`, `nagad`, `bank`, `branch`, `account_name`, `account_no`, `document`, `status`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 133, 75, 'MD. Hasan', NULL, 'mdhasan@gmail.com', '01824772535', 'Shonir Akhra, Dhaka', NULL, '01997316188', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, 2, NULL, NULL, '2024-07-27 09:24:57', '2024-07-30 14:14:40'),
(2, 1, 134, 74, 'Sazidul Islam Sumon', NULL, 'sazidulislamsumon@gmail.com', '01829755459', 'Askona, Dhaka', NULL, '01829755459', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, 2, NULL, NULL, '2024-07-28 07:44:02', '2024-07-30 14:14:20'),
(3, 1, 135, 95, 'Dr. Emran', NULL, 'dr.emran@gmail.com', '01788777848', 'Gendariya, Dhaka', NULL, '01788777848', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, 2, NULL, NULL, '2024-07-30 14:10:39', '2024-07-30 14:14:00'),
(4, 1, 136, 96, 'Fattah', NULL, 'alfattah@gmail.com', '01916304877', 'Shantobag, Dhaka', NULL, '01916304877', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, 2, NULL, NULL, '2024-07-30 14:10:59', '2024-07-30 14:13:33'),
(5, 1, 137, 97, 'Korban', NULL, 'korban@gmail.com', '01722353089', 'Rampura, Dhaka', NULL, '01722353089', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2, 2, NULL, NULL, '2024-07-30 14:11:23', '2024-07-30 14:13:15');

-- --------------------------------------------------------

--
-- Table structure for table `investor_payments`
--

CREATE TABLE `investor_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `investor_id` bigint(20) UNSIGNED NOT NULL,
  `payment_no` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(16,2) NOT NULL,
  `deposit_type` varchar(255) NOT NULL,
  `bkash` varchar(255) DEFAULT NULL,
  `rocket` varchar(255) DEFAULT NULL,
  `nagad` varchar(255) DEFAULT NULL,
  `bank_account` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investor_products`
--

CREATE TABLE `investor_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `investor_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `shared_profit` decimal(16,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `investor_products`
--

INSERT INTO `investor_products` (`id`, `investor_id`, `product_id`, `shared_profit`, `created_at`, `updated_at`) VALUES
(6, 5, 162, 0.00, '2024-07-30 14:11:46', '2024-07-30 14:11:46'),
(7, 4, 159, 0.00, '2024-07-30 14:12:13', '2024-07-30 14:12:13'),
(8, 3, 152, 0.00, '2024-07-30 14:13:49', '2024-07-30 14:13:49'),
(9, 3, 157, 0.00, '2024-07-30 14:13:49', '2024-07-30 14:13:49'),
(10, 2, 161, 0.00, '2024-07-30 14:14:13', '2024-07-30 14:14:13'),
(11, 1, 150, 0.00, '2024-07-30 14:15:03', '2024-07-30 14:15:03'),
(12, 1, 152, 0.00, '2024-07-30 14:15:03', '2024-07-30 14:15:03');

-- --------------------------------------------------------

--
-- Table structure for table `investor_profits`
--

CREATE TABLE `investor_profits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `serial_no` bigint(20) NOT NULL,
  `year` int(11) NOT NULL,
  `month` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(16,2) NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investor_profit_lists`
--

CREATE TABLE `investor_profit_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `investor_profit_id` bigint(20) UNSIGNED NOT NULL,
  `investor_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `total_profit` decimal(16,2) NOT NULL,
  `profit_percentage` decimal(16,2) NOT NULL,
  `investor_part` decimal(16,2) NOT NULL,
  `total_share` int(11) NOT NULL,
  `individual_share` int(11) NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `deposited` tinyint(4) NOT NULL DEFAULT 0,
  `deposit_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investor_sattlements`
--

CREATE TABLE `investor_sattlements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `investor_id` bigint(20) UNSIGNED NOT NULL,
  `serial_no` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `amount` decimal(16,2) NOT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investor_sattlement_lists`
--

CREATE TABLE `investor_sattlement_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `investor_sattlement_id` bigint(20) UNSIGNED NOT NULL,
  `invest_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invests`
--

CREATE TABLE `invests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `investor_id` bigint(20) UNSIGNED NOT NULL,
  `invest_no` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `deposit_type` varchar(255) DEFAULT NULL,
  `bkash` varchar(255) DEFAULT NULL,
  `rocket` varchar(255) DEFAULT NULL,
  `nagad` varchar(255) DEFAULT NULL,
  `bank_account` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `sattled` tinyint(4) NOT NULL DEFAULT 0,
  `coa_setup_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `liftings`
--

CREATE TABLE `liftings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `product_type` varchar(255) NOT NULL DEFAULT 'Consumer',
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `lifting_no` varchar(255) NOT NULL,
  `payment_type` varchar(255) NOT NULL DEFAULT 'credit',
  `voucher_no` varchar(255) NOT NULL,
  `lifting_date` date NOT NULL,
  `total_cost` decimal(16,2) NOT NULL,
  `discount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total_paid` decimal(16,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lifting_documents`
--

CREATE TABLE `lifting_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lifting_id` bigint(20) UNSIGNED NOT NULL,
  `document` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lifting_products`
--

CREATE TABLE `lifting_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lifting_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_type` varchar(255) NOT NULL DEFAULT 'Consumer',
  `lifting_price` decimal(16,2) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `qty` decimal(16,2) NOT NULL DEFAULT 1.00,
  `total_amount` decimal(16,2) NOT NULL,
  `discount` decimal(16,2) NOT NULL,
  `total_paid` decimal(16,2) NOT NULL DEFAULT 0.00,
  `return_qty` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lifting_returns`
--

CREATE TABLE `lifting_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `product_type` varchar(255) NOT NULL DEFAULT 'Consumer',
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `return_no` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lifting_return_lists`
--

CREATE TABLE `lifting_return_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `lifting_return_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `lifting_product_id` bigint(20) UNSIGNED NOT NULL,
  `product_type` varchar(255) NOT NULL DEFAULT 'Consumer',
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lifting_price` decimal(16,2) NOT NULL,
  `qty` decimal(16,2) NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `lifting_discount` decimal(16,2) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `delivery_charge` int(11) NOT NULL DEFAULT 0,
  `district` tinyint(4) NOT NULL DEFAULT 0,
  `thana` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `parent_id`, `name`, `delivery_charge`, `district`, `thana`, `created_at`, `updated_at`) VALUES
(13, NULL, 'Dhaka', 60, 0, 0, '2023-02-02 09:23:07', '2023-07-29 09:19:33'),
(14, NULL, 'Rajshahi', 120, 0, 0, '2023-02-02 09:23:30', '2023-07-29 09:20:36'),
(15, NULL, 'Rangpur', 120, 0, 0, '2023-02-02 09:23:45', '2023-07-29 09:20:54'),
(16, NULL, 'Mymensingh', 120, 0, 0, '2023-02-02 09:23:54', '2023-07-29 09:20:03'),
(17, NULL, 'Sylhet', 120, 0, 0, '2023-02-02 09:24:12', '2023-07-29 09:20:13'),
(18, NULL, 'Khulna', 120, 0, 0, '2023-02-02 09:24:30', '2023-07-29 09:19:50'),
(19, NULL, 'Barisal', 120, 0, 0, '2023-02-02 09:24:39', '2023-07-29 09:21:06'),
(20, NULL, 'Chittagong', 120, 0, 0, '2023-02-02 09:24:49', '2023-07-29 09:18:42'),
(28, 19, 'Barguna', 0, 1, 0, NULL, NULL),
(29, 19, 'Barisal', 0, 1, 0, NULL, NULL),
(30, 19, 'Bhola', 0, 1, 0, NULL, NULL),
(31, 19, 'Jhalokati', 0, 1, 0, NULL, NULL),
(32, 19, 'Patuakhali', 0, 1, 0, NULL, NULL),
(33, 19, 'Pirojpur', 0, 1, 0, NULL, NULL),
(34, 20, 'Bandarban', 0, 1, 0, NULL, NULL),
(35, 20, 'Brahmanbaria', 0, 1, 0, NULL, NULL),
(36, 20, 'Chandpur', 0, 1, 0, NULL, NULL),
(37, 20, 'Chittagong', 0, 1, 0, NULL, NULL),
(38, 20, 'Comilla', 0, 1, 0, NULL, NULL),
(39, 20, 'Cox\'s Bazar', 0, 1, 0, NULL, NULL),
(40, 20, 'Feni', 0, 1, 0, NULL, NULL),
(41, 20, 'Khagrachhari', 0, 1, 0, NULL, NULL),
(42, 20, 'Lakshmipur', 0, 1, 0, NULL, NULL),
(43, 20, 'Noakhali', 0, 1, 0, NULL, NULL),
(44, 20, 'Rangamati', 0, 1, 0, NULL, NULL),
(45, 13, 'Dhaka', 0, 1, 0, NULL, NULL),
(46, 13, 'Faridpur', 0, 1, 0, NULL, NULL),
(47, 13, 'Gazipur', 0, 1, 0, NULL, NULL),
(48, 13, 'Gopalganj', 0, 1, 0, NULL, NULL),
(49, 13, 'Jamalpur', 0, 1, 0, NULL, NULL),
(50, 13, 'Kishoreganj', 0, 1, 0, NULL, NULL),
(51, 13, 'Madaripur', 0, 1, 0, NULL, NULL),
(52, 13, 'Manikganj', 0, 1, 0, NULL, NULL),
(53, 13, 'Munshiganj', 0, 1, 0, NULL, NULL),
(54, 13, 'Mymensingh', 0, 1, 0, NULL, NULL),
(55, 13, 'Narayanganj', 0, 1, 0, NULL, NULL),
(56, 13, 'Narsingdi', 0, 1, 0, NULL, NULL),
(57, 13, 'Netrokona', 0, 1, 0, NULL, NULL),
(58, 13, 'Rajbari', 0, 1, 0, NULL, NULL),
(59, 13, 'Shariatpur', 0, 1, 0, NULL, NULL),
(60, 13, 'Sherpur', 0, 1, 0, NULL, NULL),
(61, 13, 'Tangail', 0, 1, 0, NULL, NULL),
(62, 18, 'Bagerhat', 0, 1, 0, NULL, NULL),
(63, 18, 'Chuadanga', 0, 1, 0, NULL, NULL),
(64, 18, 'Jessore', 0, 1, 0, NULL, NULL),
(65, 18, 'Jhenaidah', 0, 1, 0, NULL, NULL),
(66, 18, 'Khulna', 0, 1, 0, NULL, NULL),
(67, 18, 'Kushtia', 0, 1, 0, NULL, NULL),
(68, 18, 'Magura', 0, 1, 0, NULL, NULL),
(69, 18, 'Meherpur', 0, 1, 0, NULL, NULL),
(70, 18, 'Narail', 0, 1, 0, NULL, NULL),
(71, 18, 'Satkhira', 0, 1, 0, NULL, NULL),
(72, 14, 'Bogra', 0, 1, 0, NULL, NULL),
(73, 14, 'Joypurhat', 0, 1, 0, NULL, NULL),
(74, 14, 'Naogaon', 0, 1, 0, NULL, NULL),
(75, 14, 'Natore', 0, 1, 0, NULL, NULL),
(76, 14, 'Nawabganj', 0, 1, 0, NULL, NULL),
(77, 14, 'Pabna', 0, 1, 0, NULL, NULL),
(78, 14, 'Rajshahi', 0, 1, 0, NULL, NULL),
(79, 14, 'Sirajganj', 0, 1, 0, NULL, NULL),
(80, 15, 'Dinajpur', 0, 1, 0, NULL, NULL),
(81, 15, 'Gaibandha', 0, 1, 0, NULL, NULL),
(82, 15, 'Kurigram', 0, 1, 0, NULL, NULL),
(83, 15, 'Lalmonirhat', 0, 1, 0, NULL, NULL),
(84, 15, 'Nilphamari', 0, 1, 0, NULL, NULL),
(85, 15, 'Panchagarh', 0, 1, 0, NULL, NULL),
(86, 15, 'Rangpur', 0, 1, 0, NULL, NULL),
(87, 15, 'Thakurgaon', 0, 1, 0, NULL, NULL),
(88, 17, 'Habiganj', 0, 1, 0, NULL, NULL),
(89, 17, 'Moulvibazar', 0, 1, 0, NULL, NULL),
(90, 17, 'Sunamganj', 0, 1, 0, NULL, NULL),
(91, 17, 'Sylhet', 0, 1, 0, NULL, NULL),
(92, 73, 'Akkelpur', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(93, 73, 'Joypurhat Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(94, 73, 'Kalai ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(95, 73, 'Khetlal ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(96, 73, 'Panchbibi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(97, 72, 'Adamdighi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(98, 72, 'Bogra Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(99, 72, 'Dhunat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(100, 72, 'Dhupchanchia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(101, 72, 'Gabtali ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(102, 72, 'Kahaloo ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(103, 72, 'Nandigram ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(104, 72, 'Sariakandi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(105, 72, 'Shajahanpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(106, 72, 'Sherpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(107, 72, 'Shibganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(108, 72, 'Sonatola ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(109, 74, 'Atrai ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(110, 74, 'Badalgachhi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(111, 74, 'Manda ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(112, 74, 'Dhamoirhat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(113, 74, 'Mohadevpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(114, 74, 'Naogaon Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(115, 74, 'Niamatpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(116, 74, 'Patnitala ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(117, 74, 'Porsha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(118, 74, 'Raninagar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(119, 74, 'Sapahar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(120, 75, 'Bagatipara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(121, 75, 'Baraigram ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(122, 75, 'Gurudaspur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(123, 75, 'Lalpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(124, 75, 'Natore Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(125, 75, 'Singra ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(126, 75, 'Naldanga ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(127, 76, 'Bholahat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(128, 76, 'Gomastapur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(129, 76, 'Nachole ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(130, 76, 'Nawabganj Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(131, 76, 'Shibganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(132, 77, 'Ataikula ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(133, 77, 'Atgharia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(134, 77, 'Bera ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(135, 77, 'Bhangura ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(136, 77, 'Chatmohar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(137, 77, 'Faridpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(138, 77, 'Ishwardi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(139, 77, 'Pabna Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(140, 77, 'Santhia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(141, 77, 'Sujanagar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(142, 79, 'Belkuchi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(143, 79, 'Chauhali ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(144, 79, 'Kamarkhanda ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(145, 79, 'Kazipur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(146, 79, 'Raiganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(147, 79, 'Shahjadpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(148, 79, 'Sirajganj Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(149, 79, 'Tarash ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(150, 79, 'Ullahpara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(151, 78, 'Bagha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(152, 78, 'Bagmara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(153, 78, 'Charghat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(154, 78, 'Durgapur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(155, 78, 'Godagari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(156, 78, 'Mohanpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(157, 78, 'Paba ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(158, 78, 'Puthia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(159, 78, 'Tanore ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(160, 78, 'Boalia Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(161, 78, 'Matihar Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(162, 78, 'Rajpara Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(163, 78, 'Shah Mokdum Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(164, 80, 'Birampur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(165, 80, 'Birganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(166, 80, 'Biral ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(167, 80, 'Bochaganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(168, 80, 'Chirirbandar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(169, 80, 'Phulbari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(170, 80, 'Ghoraghat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(171, 80, 'Hakimpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(172, 80, 'Kaharole ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(173, 80, 'Khansama ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(174, 80, 'Dinajpur Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(175, 80, 'Nawabganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(176, 80, 'Parbatipur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(177, 81, 'Phulchhari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(178, 81, 'Gaibandha Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(179, 81, 'Gobindaganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(180, 81, 'Palashbari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(181, 81, 'Sadullapur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(182, 81, 'Sughatta ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(183, 81, 'Sundarganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(184, 82, 'Bhurungamari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(185, 82, 'Char Rajibpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(186, 82, 'Chilmari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(187, 82, 'Phulbari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(188, 82, 'Kurigram Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(189, 82, 'Nageshwari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(190, 82, 'Rajarhat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(191, 82, 'Raomari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(192, 82, 'Ulipur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(193, 83, 'Aditmari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(194, 83, 'Hatibandha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(195, 83, 'Kaliganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(196, 83, 'Lalmonirhat Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(197, 83, 'Patgram ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(198, 84, 'Dimla ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(199, 84, 'Domar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(200, 84, 'Jaldhaka ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(201, 84, 'Kishoreganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(202, 84, 'Nilphamari Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(203, 84, 'Saidpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(204, 85, 'Atwari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(205, 85, 'Boda ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(206, 85, 'Debiganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(207, 85, 'Panchagarh Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(208, 85, 'Tetulia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(209, 86, 'Badarganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(210, 86, 'Gangachhara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(211, 86, 'Kaunia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(212, 86, 'Rangpur Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(213, 86, 'Mithapukur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(214, 86, 'Pirgachha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(215, 86, 'Pirganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(216, 86, 'Taraganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(217, 87, 'Baliadangi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(218, 87, 'Haripur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(219, 87, 'Pirganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(220, 87, 'Ranisankail ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(221, 87, 'Thakurgaon Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(222, 28, 'Amtali ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(223, 28, 'Bamna ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(224, 28, 'Barguna Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(225, 28, 'Betagi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(226, 28, 'Patharghata ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(227, 28, 'Taltoli ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(228, 29, 'Agailjhara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(229, 29, 'Babuganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(230, 29, 'Bakerganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(231, 29, 'Banaripara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(232, 29, 'Gaurnadi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(233, 29, 'Hizla ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(234, 29, 'Barisal Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(235, 29, 'Mehendiganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(236, 29, 'Muladi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(237, 29, 'Wazirpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(238, 30, 'Bhola Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(239, 30, 'Burhanuddin ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(240, 30, 'Char Fasson ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(241, 30, 'Daulatkhan ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(242, 30, 'Lalmohan ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(243, 30, 'Manpura ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(244, 30, 'Tazumuddin ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(245, 31, 'Jhalokati Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(246, 31, 'Kathalia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(247, 31, 'Nalchity ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(248, 31, 'Rajapur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(249, 32, 'Bauphal ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(250, 32, 'Dashmina ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(251, 32, 'Galachipa ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(252, 32, 'Kalapara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(253, 32, 'Mirzaganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(254, 32, 'Patuakhali Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(255, 32, 'Rangabali ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(256, 32, 'Dumki ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(257, 33, 'Bhandaria ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(258, 33, 'Kawkhali ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(259, 33, 'Mathbaria ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(260, 33, 'Nazirpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(261, 33, 'Pirojpur Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(262, 33, 'Nesarabad (Swarupkati) ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(263, 33, 'Zianagor ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(264, 34, 'Ali Kadam ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(265, 34, 'Bandarban Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(266, 34, 'Lama ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(267, 34, 'Naikhongchhari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(268, 34, 'Rowangchhari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(269, 34, 'Ruma ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(270, 34, 'Thanchi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(271, 35, 'Akhaura ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(272, 35, 'Bancharampur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(273, 35, 'Brahmanbaria Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(274, 35, 'Kasba ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(275, 35, 'Nabinagar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(276, 35, 'Nasirnagar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(277, 35, 'Sarail ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(278, 35, 'Ashuganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(279, 35, 'Bijoynagar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(280, 36, 'Chandpur Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(281, 36, 'Faridganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(282, 36, 'Haimchar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(283, 36, 'Haziganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(284, 36, 'Kachua ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(285, 36, 'Matlab Dakshin ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(286, 36, 'Matlab Uttar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(287, 36, 'Shahrasti ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(288, 37, 'Anwara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(289, 37, 'Banshkhali ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(290, 37, 'Boalkhali ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(291, 37, 'Chandanaish ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(292, 37, 'Fatikchhari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(293, 37, 'Hathazari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(294, 37, 'Lohagara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(295, 37, 'Mirsharai ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(296, 37, 'Patiya ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(297, 37, 'Rangunia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(298, 37, 'Raozan ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(299, 37, 'Sandwip ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(300, 37, 'Satkania ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(301, 37, 'Sitakunda ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(302, 37, 'Bandor (Chittagong Port) Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(303, 37, 'Chandgaon Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(304, 37, 'Double Mooring Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(305, 37, 'Kotwali Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(306, 37, 'Pahartali Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(307, 37, 'Panchlaish Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(308, 38, 'Barura ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(309, 38, 'Brahmanpara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(310, 38, 'Burichang ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(311, 38, 'Chandina ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(312, 38, 'Chauddagram ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(313, 38, 'Daudkandi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(314, 38, 'Debidwar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(315, 38, 'Homna ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(316, 38, 'Laksam ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(317, 38, 'Muradnagar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(318, 38, 'Nangalkot ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(319, 38, 'Comilla Adarsha Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(320, 38, 'Meghna ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(321, 38, 'Titas ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(322, 38, 'Monohargonj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(323, 38, 'Comilla Sadar Dakshin ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(324, 39, 'Chakaria ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(325, 39, 'Cox\'s Bazar Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(326, 39, 'Kutubdia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(327, 39, 'Maheshkhali ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(328, 39, 'Ramu ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(329, 39, 'Teknaf ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(330, 39, 'Ukhia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(331, 39, 'Pekua ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(332, 40, 'Chhagalnaiya ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(333, 40, 'Daganbhuiyan ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(334, 40, 'Feni Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(335, 40, 'Parshuram ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(336, 40, 'Sonagazi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(337, 40, 'Fulgazi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(338, 41, 'Dighinala ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(339, 41, 'Khagrachhari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(340, 41, 'Lakshmichhari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(341, 41, 'Mahalchhari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(342, 41, 'Manikchhari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(343, 41, 'Matiranga ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(344, 41, 'Panchhari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(345, 41, 'Ramgarh ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(346, 42, 'Lakshmipur Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(347, 42, 'Raipur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(348, 42, 'Ramganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(349, 42, 'Ramgati ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(350, 42, 'Kamalnagar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(351, 43, 'Begumganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(352, 43, 'Noakhali Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(353, 43, 'Chatkhil ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(354, 43, 'Companiganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(355, 43, 'Hatiya ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(356, 43, 'Senbagh ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(357, 43, 'Sonaimuri ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(358, 43, 'Subarnachar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(359, 43, 'Kabirhat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(360, 44, 'Bagaichhari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(361, 44, 'Barkal ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(362, 44, 'Kawkhali (Betbunia) ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(363, 44, 'Belaichhari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(364, 44, 'Kaptai ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(365, 44, 'Juraichhari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(366, 44, 'Langadu ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(367, 44, 'Naniyachar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(368, 44, 'Rajasthali ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(369, 44, 'Rangamati Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(370, 45, 'Dhamrai ', 100, 0, 1, NULL, '2023-08-14 07:55:43'),
(371, 45, 'Dohar ', 100, 0, 1, NULL, '2023-08-14 07:55:43'),
(372, 45, 'Dhaka City Corporation Area', 80, 0, 1, NULL, '2023-08-14 07:55:43'),
(373, 45, 'Keraniganj ', 100, 0, 1, NULL, '2023-08-14 07:55:43'),
(374, 45, 'Nawabganj ', 100, 0, 1, NULL, '2023-08-14 07:55:43'),
(375, 45, 'Savar ', 100, 0, 1, NULL, '2023-08-14 07:55:43'),
(376, 46, 'Alfadanga ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(377, 46, 'Bhanga ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(378, 46, 'Boalmari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(379, 46, 'Charbhadrasan ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(380, 46, 'Faridpur Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(381, 46, 'Madhukhali ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(382, 46, 'Nagarkanda ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(383, 46, 'Sadarpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(384, 46, 'Saltha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(385, 47, 'Gazipur Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(386, 47, 'Kaliakair ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(387, 47, 'Kaliganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(388, 47, 'Kapasia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(389, 47, 'Sreepur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(390, 48, 'Gopalganj Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(391, 48, 'Kashiani ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(392, 48, 'Kotalipara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(393, 48, 'Muksudpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(394, 48, 'Tungipara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(395, 49, 'Baksiganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(396, 49, 'Dewanganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(397, 49, 'Islampur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(398, 49, 'Jamalpur Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(399, 49, 'Madarganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(400, 49, 'Melandaha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(401, 49, 'Sarishabari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(402, 50, 'Astagram ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(403, 50, 'Bajitpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(404, 50, 'Bhairab ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(405, 50, 'Hossainpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(406, 50, 'Itna ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(407, 50, 'Karimganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(408, 50, 'Katiadi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(409, 50, 'Kishoreganj Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(410, 50, 'Kuliarchar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(411, 50, 'Mithamain ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(412, 50, 'Nikli ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(413, 50, 'Pakundia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(414, 50, 'Tarail ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(415, 51, 'Rajoir ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(416, 51, 'Madaripur Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(417, 51, 'Kalkini ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(418, 51, 'Shibchar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(419, 52, 'Daulatpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(420, 52, 'Ghior ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(421, 52, 'Harirampur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(422, 52, 'Manikgonj Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(423, 52, 'Saturia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(424, 52, 'Shivalaya ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(425, 52, 'Singair ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(426, 53, 'Gazaria ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(427, 53, 'Lohajang ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(428, 53, 'Munshiganj Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(429, 53, 'Sirajdikhan ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(430, 53, 'Sreenagar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(431, 53, 'Tongibari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(432, 54, 'Bhaluka ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(433, 54, 'Dhobaura ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(434, 54, 'Fulbaria ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(435, 54, 'Gaffargaon ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(436, 54, 'Gauripur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(437, 54, 'Haluaghat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(438, 54, 'Ishwarganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(439, 54, 'Mymensingh Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(440, 54, 'Muktagachha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(441, 54, 'Nandail ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(442, 54, 'Phulpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(443, 54, 'Trishal ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(444, 54, 'Tara Khanda ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(445, 55, 'Araihazar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(446, 55, 'Bandar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(447, 55, 'Narayanganj Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(448, 55, 'Rupganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(449, 55, 'Sonargaon ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(450, 57, 'Atpara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(451, 57, 'Barhatta ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(452, 57, 'Durgapur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(453, 57, 'Khaliajuri ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(454, 57, 'Kalmakanda ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(455, 57, 'Kendua ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(456, 57, 'Madan ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(457, 57, 'Mohanganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(458, 57, 'Netrokona Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(459, 57, 'Purbadhala ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(460, 58, 'Baliakandi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(461, 58, 'Goalandaghat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(462, 58, 'Pangsha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(463, 58, 'Rajbari Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(464, 58, 'Kalukhali ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(465, 59, 'Bhedarganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(466, 59, 'Damudya ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(467, 59, 'Gosairhat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(468, 59, 'Naria ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(469, 59, 'Shariatpur Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(470, 59, 'Zanjira ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(471, 59, 'Shakhipur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(472, 60, 'Jhenaigati ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(473, 60, 'Nakla ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(474, 60, 'Nalitabari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(475, 60, 'Sherpur Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(476, 60, 'Sreebardi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(477, 61, 'Gopalpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(478, 61, 'Basail ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(479, 61, 'Bhuapur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(480, 61, 'Delduar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(481, 61, 'Ghatail ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(482, 61, 'Kalihati ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(483, 61, 'Madhupur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(484, 61, 'Mirzapur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(485, 61, 'Nagarpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(486, 61, 'Sakhipur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(487, 61, 'Dhanbari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(488, 61, 'Tangail Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(489, 56, 'Narsingdi Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(490, 56, 'Belabo ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(491, 56, 'Monohardi ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(492, 56, 'Palash ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(493, 56, 'Raipura ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(494, 56, 'Shibpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(495, 62, 'Bagerhat Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(496, 62, 'Chitalmari ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(497, 62, 'Fakirhat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(498, 62, 'Kachua ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(499, 62, 'Mollahat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(500, 62, 'Mongla ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(501, 62, 'Morrelganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(502, 62, 'Rampal ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(503, 62, 'Sarankhola ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(504, 63, 'Alamdanga ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(505, 63, 'Chuadanga Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(506, 63, 'Damurhuda ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(507, 63, 'Jibannagar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(508, 64, 'Abhaynagar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(509, 64, 'Bagherpara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(510, 64, 'Chaugachha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(511, 64, 'Jhikargachha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(512, 64, 'Keshabpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(513, 64, 'Jessore Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(514, 64, 'Manirampur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(515, 64, 'Sharsha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(516, 65, 'Harinakunda ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(517, 65, 'Jhenaidah Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(518, 65, 'Kaliganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(519, 65, 'Kotchandpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(520, 65, 'Maheshpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(521, 65, 'Shailkupa ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(522, 66, 'Batiaghata ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(523, 66, 'Dacope ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(524, 66, 'Dumuria ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(525, 66, 'Dighalia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(526, 66, 'Koyra ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(527, 66, 'Paikgachha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(528, 66, 'Phultala ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(529, 66, 'Rupsha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(530, 66, 'Terokhada ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(531, 66, 'Daulatpur Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(532, 66, 'Khalishpur Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(533, 66, 'Khan Jahan Ali Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(534, 66, 'Kotwali Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(535, 66, 'Sonadanga Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(536, 66, 'Harintana Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(537, 67, 'Bheramara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(538, 67, 'Daulatpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(539, 67, 'Khoksa ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(540, 67, 'Kumarkhali ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(541, 67, 'Kushtia Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(542, 67, 'Mirpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(543, 67, 'Shekhpara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(544, 68, 'Magura Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(545, 68, 'Mohammadpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(546, 68, 'Shalikha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(547, 68, 'Sreepur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(548, 69, 'Gangni ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(549, 69, 'Meherpur Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(550, 69, 'Mujibnagar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(551, 70, 'Kalia ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(552, 70, 'Lohagara ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(553, 70, 'Narail Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(554, 70, 'Naragati Thana', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(555, 71, 'Assasuni ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(556, 71, 'Debhata ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(557, 71, 'Kalaroa ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(558, 71, 'Kaliganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(559, 71, 'Satkhira Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(560, 71, 'Shyamnagar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(561, 71, 'Tala ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(562, 88, 'Ajmiriganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(563, 88, 'Bahubal ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(564, 88, 'Baniyachong ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(565, 88, 'Chunarughat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(566, 88, 'Habiganj Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(567, 88, 'Lakhai ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(568, 88, 'Madhabpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(569, 88, 'Nabiganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(570, 89, 'Barlekha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(571, 89, 'Kamalganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(572, 89, 'Kulaura ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(573, 89, 'Moulvibazar Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(574, 89, 'Rajnagar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(575, 89, 'Sreemangal ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(576, 89, 'Juri ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(577, 90, 'Bishwamvarpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(578, 90, 'Chhatak ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(579, 90, 'Derai ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(580, 90, 'Dharampasha ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(581, 90, 'Dowarabazar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(582, 90, 'Jagannathpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(583, 90, 'Jamalganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(584, 90, 'Sullah ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(585, 90, 'Sunamganj Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(586, 90, 'Tahirpur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(587, 90, 'South Sunamganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(588, 91, 'Balaganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(589, 91, 'Beanibazar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(590, 91, 'Bishwanath ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(591, 91, 'Companigonj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(592, 91, 'Fenchuganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(593, 91, 'Golapganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(594, 91, 'Gowainghat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(595, 91, 'Jaintiapur ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(596, 91, 'Kanaighat ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(597, 91, 'Sylhet Sadar ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(598, 91, 'Zakiganj ', 120, 0, 1, NULL, '2023-08-14 07:55:43'),
(599, 91, 'South Shurma ', 120, 0, 1, NULL, '2023-08-14 07:55:43');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL DEFAULT 'header',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `position`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Header Menu', 'header', 1, '2024-06-23 04:28:30', '2024-06-23 04:28:30'),
(2, 'Sidebar Menu', 'sidebar', 1, '2024-06-23 05:32:14', '2024-06-23 05:32:14'),
(3, 'Information', 'footer', 1, '2024-06-23 11:57:52', '2024-06-23 11:57:52'),
(4, 'Support and services', 'footer', 1, '2024-06-23 11:58:17', '2024-06-23 11:58:17');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `page_id` bigint(20) UNSIGNED DEFAULT NULL,
  `custom_page` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `menu_id`, `parent_id`, `category_id`, `page_id`, `custom_page`, `order`, `status`, `created_at`, `updated_at`) VALUES
(117, 2, NULL, 31, NULL, NULL, 3, 1, '2024-07-07 12:22:14', '2024-07-07 12:22:14'),
(123, 2, NULL, 43, NULL, NULL, 9, 1, '2024-07-07 12:22:14', '2024-07-07 12:22:14'),
(124, 2, NULL, 44, NULL, NULL, 10, 1, '2024-07-07 12:22:14', '2024-07-07 12:22:14'),
(126, 1, NULL, 31, NULL, NULL, 11, 1, '2024-07-25 06:47:28', '2024-07-25 06:47:28'),
(127, 1, NULL, 43, NULL, NULL, 12, 1, '2024-07-25 06:47:28', '2024-07-25 06:47:28'),
(128, 1, NULL, 44, NULL, NULL, 13, 1, '2024-07-25 06:47:28', '2024-07-25 06:47:28');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_04_26_070249_create_permission_tables', 1),
(6, '2023_04_29_104538_create_admin_menus_table', 1),
(7, '2023_04_30_095422_create_jobs_table', 1),
(8, '2023_06_04_103415_create_admin_menu_actions_table', 1),
(9, '2023_07_16_193339_create_settings_table', 1),
(11, '2023_08_05_220022_create_menus_table', 1),
(12, '2023_08_05_220028_create_menu_items_table', 1),
(13, '2023_08_08_095304_create_admin_settings_table', 1),
(14, '2023_08_09_115019_create_categories_table', 1),
(15, '2023_08_09_161545_create_products_table', 1),
(16, '2023_08_09_163637_create_brands_table', 1),
(17, '2023_08_09_175745_create_product_prices_table', 1),
(20, '2023_08_23_083356_create_portfolios_table', 1),
(21, '2023_08_30_114536_create_home_product_sections_table', 1),
(22, '2023_09_02_152658_create_attributes_table', 1),
(23, '2023_09_02_153152_create_attribute_values_table', 1),
(25, '2023_09_06_182742_create_shipping_addresses_table', 1),
(26, '2023_09_09_111028_create_locations_table', 1),
(27, '2023_09_09_183847_create_wishlists_table', 1),
(28, '2023_09_12_122146_create_flashdeals_table', 1),
(30, '2023_09_13_175745_create_reviews_table', 1),
(31, '2023_09_19_135706_create_companies_table', 1),
(32, '2023_09_19_141057_create_branches_table', 1),
(33, '2023_09_19_141630_create_stores_table', 1),
(34, '2023_09_20_105200_create_sliders_table', 1),
(35, '2023_09_23_101053_create_staff_table', 1),
(36, '2023_09_23_111539_create_regions_table', 1),
(37, '2023_09_23_123145_create_areas_table', 1),
(38, '2023_09_23_131123_create_territories_table', 1),
(39, '2023_09_23_181613_create_client_categories_table', 1),
(40, '2023_09_25_100436_create_chain_clients_table', 1),
(41, '2023_09_25_103202_create_vendors_table', 1),
(42, '2023_09_25_122043_create_category_vendors_table', 1),
(43, '2023_09_25_133234_create_groups_table', 1),
(44, '2023_09_25_133955_create_group_members_table', 1),
(45, '2023_09_25_155205_create_clients_table', 1),
(48, '2023_09_28_123659_create_vehicles_table', 1),
(53, '2023_10_03_165212_create_static_site_items_table', 1),
(54, '2023_10_03_172718_create_special_food_items_table', 1),
(55, '2023_10_03_182643_create_details_cards_table', 1),
(56, '2023_10_03_213106_create_showcase_items_table', 1),
(57, '2023_10_03_222638_create_client_messages_table', 1),
(58, '2023_10_03_224230_create_contacts_table', 1),
(59, '2023_10_04_081821_create_testimonials_table', 1),
(60, '2023_10_04_085027_create_abouts_table', 1),
(61, '2023_10_04_093333_create_social_works_table', 1),
(101, '2023_09_05_104150_create_product_stocks_table', 22),
(102, '2023_09_12_122242_create_flashdeal_products_table', 23),
(103, '2023_09_25_203513_create_group_sales_targets_table', 24),
(104, '2023_09_25_203644_create_group_sales_target_categories_table', 25),
(105, '2023_09_29_213551_create_client_prices_table', 26),
(106, '2023_09_30_152737_create_liftings_table', 27),
(107, '2023_09_30_152744_create_lifting_products_table', 28),
(108, '2023_09_30_162737_create_lifting_documents_table', 29),
(109, '2023_10_11_125908_create_sales_table', 30),
(110, '2023_10_11_125939_create_sales_lists_table', 31),
(111, '2023_10_12_175550_create_sales_product_data_table', 32),
(112, '2023_10_11_130353_create_collections_table', 33),
(113, '2023_10_15_090533_create_collection_data_table', 34),
(114, '2023_10_15_125909_create_sales_returns_table', 35),
(115, '2023_10_15_131506_create_sales_return_lists_table', 36),
(116, '2023_10_16_103106_create_lifting_returns_table', 37),
(117, '2023_10_16_103114_create_lifting_return_lists_table', 38),
(118, '2023_10_16_144752_create_vendor_payments_table', 39),
(119, '2023_10_16_145441_create_vendor_payment_data_table', 40),
(120, '2023_10_17_213643_create_deliveries_table', 41),
(121, '2023_10_17_213739_create_delivery_lists_table', 42),
(124, '2023_10_18_102801_create_transfers_table', 45),
(125, '2023_10_18_102810_create_transfer_products_table', 46),
(126, '2023_10_31_182322_create_transfer_data_table', 47),
(127, '2023_08_10_115126_create_orders_table', 48),
(128, '2023_08_10_115133_create_order_products_table', 49),
(129, '2023_11_01_182317_create_order_data_table', 50),
(130, '2023_08_02_000217_create_pages_table', 51),
(131, '2023_10_11_126050_create_sales_product_data_table', 52),
(132, '2024_04_16_222142_create_sizes_table', 53),
(133, '2024_04_16_222147_create_colors_table', 54),
(134, '2024_04_16_222206_create_product_sizes_table', 55),
(135, '2024_04_16_222214_create_product_colors_table', 56),
(136, '2023_11_15_110009_create_access_logs_table', 57),
(137, '2023_11_18_093933_create_delivery_charges_table', 57),
(138, '2023_11_18_181012_create_online_deliveries_table', 57),
(139, '2023_11_18_181300_create_online_delivery_lists_table', 57),
(140, '2023_11_21_101830_create_bulk_collections_table', 57),
(141, '2023_11_21_102651_create_bulk_collection_lists_table', 57),
(142, '2023_12_07_101641_create_coa_setups_table', 57),
(143, '2023_12_10_095150_create_account_transaction_autos_table', 57),
(144, '2023_12_10_095155_create_account_transactions_table', 57),
(145, '2024_01_17_171931_create_subscriptions_table', 57),
(146, '2024_05_01_125604_create_product_skus_table', 58),
(147, '2024_06_22_193709_create_menus_table', 59),
(148, '2024_06_22_193715_create_menu_items_table', 59),
(149, '2024_06_23_182216_create_home_sections_table', 60),
(150, '2024_06_23_182258_create_home_section_sub_categories_table', 60),
(151, '2024_07_11_113556_create_investors_table', 61),
(152, '2024_07_11_113626_create_investor_products_table', 61),
(154, '2024_07_17_181743_create_investor_payments_table', 62),
(159, '2024_07_20_104132_create_invests_table', 63),
(161, '2024_07_20_124050_create_wallets_table', 64),
(167, '2024_07_24_110633_create_investor_sattlements_table', 66),
(168, '2024_07_24_110638_create_investor_sattlement_lists_table', 66),
(169, '2024_07_22_144650_create_investor_profits_table', 67),
(170, '2024_07_23_090935_create_investor_profit_lists_table', 68);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 2),
(1, 'App\\Models\\User', 75),
(4, 'App\\Models\\User', 8),
(5, 'App\\Models\\User', 67),
(8, 'App\\Models\\User', 68),
(10, 'App\\Models\\User', 1),
(11, 'App\\Models\\User', 26),
(11, 'App\\Models\\User', 41),
(11, 'App\\Models\\User', 42),
(11, 'App\\Models\\User', 43),
(11, 'App\\Models\\User', 44),
(11, 'App\\Models\\User', 45),
(11, 'App\\Models\\User', 46),
(11, 'App\\Models\\User', 47),
(11, 'App\\Models\\User', 48),
(11, 'App\\Models\\User', 49),
(11, 'App\\Models\\User', 50),
(11, 'App\\Models\\User', 51),
(11, 'App\\Models\\User', 52),
(11, 'App\\Models\\User', 53),
(11, 'App\\Models\\User', 54),
(11, 'App\\Models\\User', 55),
(11, 'App\\Models\\User', 56),
(11, 'App\\Models\\User', 57),
(11, 'App\\Models\\User', 58),
(11, 'App\\Models\\User', 59),
(11, 'App\\Models\\User', 60),
(11, 'App\\Models\\User', 61),
(11, 'App\\Models\\User', 62),
(11, 'App\\Models\\User', 63),
(11, 'App\\Models\\User', 64),
(11, 'App\\Models\\User', 65),
(11, 'App\\Models\\User', 66),
(11, 'App\\Models\\User', 69),
(11, 'App\\Models\\User', 70),
(11, 'App\\Models\\User', 71),
(11, 'App\\Models\\User', 72),
(11, 'App\\Models\\User', 76),
(11, 'App\\Models\\User', 77),
(11, 'App\\Models\\User', 78),
(11, 'App\\Models\\User', 79),
(12, 'App\\Models\\User', 128),
(12, 'App\\Models\\User', 129),
(12, 'App\\Models\\User', 131),
(12, 'App\\Models\\User', 133),
(12, 'App\\Models\\User', 134),
(12, 'App\\Models\\User', 135),
(12, 'App\\Models\\User', 136),
(12, 'App\\Models\\User', 137);

-- --------------------------------------------------------

--
-- Table structure for table `online_deliveries`
--

CREATE TABLE `online_deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `driver_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_man_id` bigint(20) UNSIGNED NOT NULL,
  `serial_no` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `online_delivery_lists`
--

CREATE TABLE `online_delivery_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `online_delivery_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `discount` decimal(16,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_code` varchar(255) NOT NULL,
  `store_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_phone` varchar(255) NOT NULL,
  `shipping_address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `shipping_charge` decimal(16,2) DEFAULT NULL,
  `sub_total` decimal(16,2) NOT NULL,
  `discount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total` decimal(16,2) NOT NULL,
  `paid` decimal(16,2) NOT NULL,
  `due` decimal(16,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cod',
  `coupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `order_type` varchar(255) NOT NULL DEFAULT 'offline',
  `pending_at` timestamp NULL DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `processing_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `successed_at` timestamp NULL DEFAULT NULL,
  `canceled_at` timestamp NULL DEFAULT NULL,
  `return_at` timestamp NULL DEFAULT NULL,
  `order_note` text DEFAULT NULL,
  `cancel_approve` tinyint(4) NOT NULL DEFAULT 0,
  `gate_pass` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE `order_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `choose_options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`choose_options`)),
  `sku` varchar(255) DEFAULT NULL,
  `discount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `sale_price` decimal(16,2) NOT NULL,
  `subtotal` decimal(16,2) NOT NULL DEFAULT 0.00,
  `quantity` decimal(16,2) NOT NULL,
  `delivered` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sub_title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `sub_title`, `slug`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Terms & Conditions', 'TERMS & CONDITIONS', 'terms-conditions', '<p>BRB Cable Industries Ltd. a private Limited Company was established with a view to manufacture Wires &amp; Cables in 1978. After successful incorporation, BRB starts its commercial production in 1980. During the year 2000 BRB launched its PVC Cables Plant for producing up to 33KV Cables along with XLPE insulated HT Cables, FRLS Cables and Aluminum Conductors up to 132KV. Moreover, Super Enameled Copper Wire Plant &amp; Instrumentation Cables were launched during this time. At this age BRB introduces C.C.V Plant (Catenary Continuous Vulcanization) for the first time in Bangladesh<br></p>', 1, '2023-11-27 15:00:21', '2024-05-13 06:28:16'),
(2, 'Privacy Policy', 'Privacy Policy', 'privacy-policy', '<p style=\"outline: 0px; padding: 0px; margin-right: 0px; margin-bottom: 10px; margin-left: 0px;\">BRB Cable Industries Ltd. a private Limited Company was established with a view to manufacture Wires &amp; Cables in 1978. After successful incorporation, BRB starts its commercial production in 1980. During the year 2000 BRB launched its PVC Cables Plant for producing up to 33KV Cables along with XLPE insulated HT Cables, FRLS Cables and Aluminum Conductors up to 132KV. Moreover, Super Enameled Copper Wire Plant &amp; Instrumentation Cables were launched during this time. At this age BRB introduces C.C.V Plant (Catenary Continuous Vulcanization) for the first time in Bangladesh<br></p>', 1, '2023-11-27 15:11:23', '2024-05-13 06:28:28');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Dashboard', 'web', '2023-09-19 09:04:28', '2023-09-19 09:04:28'),
(2, 'System Setting', 'web', '2023-09-19 09:05:17', '2023-09-19 09:05:17'),
(3, 'Company Setup', 'web', '2023-09-19 09:05:30', '2023-09-19 09:05:30'),
(4, 'Branch Setup', 'web', '2023-09-19 09:05:39', '2023-09-19 09:05:39'),
(5, 'Role Setup', 'web', '2023-09-19 09:05:48', '2023-09-19 09:05:48'),
(6, 'User Setup', 'web', '2023-09-19 09:05:54', '2023-09-19 09:05:54'),
(7, 'Menu Setup', 'web', '2023-09-19 09:06:24', '2023-09-19 09:06:24'),
(8, 'admin.admin-menu.create', 'web', '2023-09-19 09:16:56', '2023-09-19 09:16:56'),
(9, 'admin.admin-menu.store', 'web', '2023-09-19 09:17:06', '2023-09-19 09:17:06'),
(10, 'admin.admin-menu.edit', 'web', '2023-09-19 09:17:10', '2023-09-19 09:17:10'),
(11, 'admin.admin-menu.update', 'web', '2023-09-19 09:17:20', '2023-09-19 09:17:20'),
(12, 'admin.admin-menu.destroy', 'web', '2023-09-19 09:17:25', '2023-09-19 09:17:25'),
(14, 'admin.admin-menuAction.index', 'web', '2023-09-19 09:18:18', '2023-09-19 09:18:18'),
(15, 'admin.admin-menuAction.create', 'web', '2023-09-19 09:18:26', '2023-09-19 09:18:26'),
(16, 'admin.admin-menuAction.store', 'web', '2023-09-19 09:18:33', '2023-09-19 09:18:33'),
(17, 'admin.admin-menuAction.edit', 'web', '2023-09-19 09:18:41', '2023-09-19 09:18:41'),
(18, 'admin.admin-menuAction.update', 'web', '2023-09-19 09:18:49', '2023-09-19 09:18:49'),
(19, 'admin.admin-menuAction.destroy', 'web', '2023-09-19 09:19:02', '2023-09-19 09:19:02'),
(20, 'System Setup', 'web', '2023-09-19 09:20:32', '2023-09-19 09:20:32'),
(21, 'Sales Group', 'web', '2023-09-19 09:20:46', '2023-09-23 14:50:24'),
(22, 'Store Setup', 'web', '2023-09-19 09:20:59', '2023-09-19 09:20:59'),
(23, 'Product Type', 'web', '2023-09-19 09:21:10', '2023-09-23 14:53:14'),
(24, 'Product Setup', 'web', '2023-09-19 09:21:19', '2023-09-19 09:21:19'),
(25, 'Staff Setup', 'web', '2023-09-19 09:21:25', '2023-09-19 09:21:25'),
(26, 'Product List', 'web', '2023-09-19 09:21:36', '2023-09-19 09:21:36'),
(27, 'System Configuration', 'web', '2023-09-19 09:22:02', '2023-09-19 09:22:02'),
(28, 'Group Sales Target', 'web', '2023-09-19 09:22:23', '2023-09-19 09:22:23'),
(29, 'Client Price Setup', 'web', '2023-09-19 09:22:33', '2023-09-19 09:22:33'),
(30, 'Procurement', 'web', '2023-09-19 09:23:13', '2023-09-19 09:23:13'),
(31, 'Transaction', 'web', '2023-09-19 09:23:22', '2023-09-19 09:23:22'),
(32, 'Vendor Setup', 'web', '2023-09-19 09:23:31', '2023-09-19 09:23:31'),
(33, 'admin.lifting.index', 'web', '2023-09-19 09:23:43', '2024-07-31 03:24:18'),
(34, 'Purchase Return', 'web', '2023-09-19 09:23:55', '2023-09-19 09:23:55'),
(35, 'Vendor Payment', 'web', '2023-09-19 09:24:05', '2023-09-19 09:24:05'),
(36, 'Reports', 'web', '2023-09-19 09:24:13', '2023-09-19 09:24:13'),
(37, 'admin.lifting-history.index', 'web', '2023-09-19 09:24:28', '2024-07-31 03:25:52'),
(38, 'admin.lifting-return-history.index', 'web', '2023-09-19 09:24:36', '2024-07-31 03:27:13'),
(39, 'admin.vendor-payment-history.index', 'web', '2023-09-19 09:24:54', '2024-07-31 03:27:00'),
(40, 'Vendor Statement', 'web', '2023-09-19 09:25:06', '2023-09-19 09:25:06'),
(41, 'Inventory', 'web', '2023-09-19 09:25:29', '2023-09-19 09:25:29'),
(42, 'Transaction 2', 'web', '2023-09-19 09:25:38', '2023-09-19 09:25:38'),
(43, 'Product Transfer', 'web', '2023-09-19 09:25:55', '2023-09-19 09:25:55'),
(44, 'Transfer Receive', 'web', '2023-09-19 09:26:05', '2023-09-19 09:26:05'),
(45, 'Reports 2', 'web', '2023-09-19 09:26:14', '2023-09-19 09:26:14'),
(46, 'Closing Stock', 'web', '2023-09-19 09:26:33', '2023-09-19 09:26:33'),
(47, 'Stock Status', 'web', '2023-09-19 09:26:45', '2023-09-19 09:26:45'),
(48, 'Transfer Log', 'web', '2023-09-19 09:26:55', '2023-09-19 09:26:55'),
(49, 'Sales Management', 'web', '2023-09-19 09:28:03', '2023-09-19 09:28:03'),
(50, 'Transaction 3', 'web', '2023-09-19 09:28:16', '2023-09-19 09:28:16'),
(51, 'Region Setup', 'web', '2023-09-19 09:28:49', '2023-09-19 09:28:49'),
(52, 'Area Setup', 'web', '2023-09-19 09:29:03', '2023-09-19 09:29:03'),
(53, 'Territory Setup', 'web', '2023-09-19 09:29:15', '2023-09-19 09:29:15'),
(54, 'Client Setup', 'web', '2023-09-19 09:29:28', '2023-09-19 09:29:28'),
(55, 'Daily Invoice', 'web', '2023-09-19 09:29:42', '2023-11-02 13:07:13'),
(56, 'Daily Collection', 'web', '2023-09-19 09:29:54', '2023-09-19 09:29:54'),
(57, 'Bulk Collection', 'web', '2023-09-19 09:30:07', '2023-09-19 09:30:07'),
(58, 'Sales Return', 'web', '2023-09-19 09:30:21', '2023-09-19 09:30:21'),
(59, 'Return Approval', 'web', '2023-09-19 09:30:32', '2023-09-19 09:30:32'),
(60, 'Reports 3', 'web', '2023-09-19 09:30:42', '2023-09-19 09:30:42'),
(61, 'admin.sales-history.index', 'web', '2023-09-19 09:30:51', '2024-07-31 03:30:07'),
(62, 'admin.collection-history.index', 'web', '2023-09-19 09:31:08', '2024-07-31 03:30:14'),
(63, 'admin.return-history.index', 'web', '2023-09-19 09:31:21', '2024-07-31 03:30:21'),
(64, 'Client Statement', 'web', '2023-09-19 09:31:39', '2023-09-19 09:31:39'),
(65, 'Client List', 'web', '2023-09-19 09:31:52', '2023-09-19 09:31:52'),
(66, 'Business Analysis', 'web', '2023-09-19 09:33:29', '2023-09-19 09:33:29'),
(67, 'Payment Analysis', 'web', '2023-09-19 09:33:39', '2023-09-19 09:33:39'),
(68, 'Stock Valuation', 'web', '2023-09-19 09:33:47', '2023-09-19 09:33:47'),
(69, 'admin.sales-target-achivement.index', 'web', '2023-09-19 09:33:56', '2024-07-31 03:33:39'),
(70, 'Sales Contribution', 'web', '2023-09-19 09:34:06', '2023-09-19 09:34:06'),
(71, 'admin.sales-realization.index', 'web', '2023-09-19 09:34:14', '2024-07-31 03:34:16'),
(72, 'Sales Ageing Report', 'web', '2023-09-19 09:34:41', '2023-09-19 09:34:41'),
(73, 'Client Outstanding', 'web', '2023-09-19 09:34:53', '2023-10-29 17:09:15'),
(74, 'Product Profit', 'web', '2023-09-19 09:35:10', '2023-11-02 10:03:23'),
(75, 'Online Order', 'web', '2023-09-19 09:36:07', '2023-09-19 09:36:07'),
(76, 'Offline Order', 'web', '2023-09-19 09:36:18', '2023-09-23 10:00:01'),
(78, 'Prepare Gatepass', 'web', '2023-09-19 09:38:15', '2023-10-18 13:57:45'),
(80, 'Delivery Statement', 'web', '2023-09-19 09:38:34', '2023-10-18 13:58:25'),
(81, 'Client Chain', 'web', '2023-09-23 10:02:09', '2023-09-23 14:46:48'),
(82, 'Client Type', 'web', '2023-09-23 10:02:20', '2023-09-23 14:45:32'),
(83, 'admin.client-category.create', 'web', '2023-09-25 06:58:14', '2023-09-25 06:58:14'),
(84, 'admin.client-category.store', 'web', '2023-09-25 06:58:24', '2023-09-25 06:58:24'),
(85, 'admin.client-category.edit', 'web', '2023-09-25 06:58:31', '2023-09-25 06:58:31'),
(86, 'admin.client-category.update', 'web', '2023-09-25 06:58:40', '2023-09-25 06:58:40'),
(87, 'admin.client-category.destroy', 'web', '2023-09-25 06:58:49', '2023-09-25 06:58:49'),
(88, 'admin.chain-client.create', 'web', '2023-09-25 06:59:35', '2023-09-25 06:59:35'),
(89, 'admin.chain-client.store', 'web', '2023-09-25 06:59:50', '2023-09-25 06:59:50'),
(90, 'admin.chain-client.edit', 'web', '2023-09-25 06:59:55', '2023-09-25 06:59:55'),
(91, 'admin.chain-client.update', 'web', '2023-09-25 07:00:02', '2023-09-25 07:00:02'),
(92, 'admin.chain-client.destroy', 'web', '2023-09-25 07:00:19', '2023-09-25 07:00:19'),
(93, 'admin.territory.create', 'web', '2023-09-25 07:00:48', '2023-09-25 07:00:48'),
(94, 'admin.territory.store', 'web', '2023-09-25 07:00:54', '2023-09-25 07:00:54'),
(95, 'admin.territory.edit', 'web', '2023-09-25 07:00:58', '2023-09-25 07:00:58'),
(96, 'admin.territory.update', 'web', '2023-09-25 07:01:03', '2023-09-25 07:01:03'),
(97, 'admin.territory.destroy', 'web', '2023-09-25 07:01:09', '2023-09-25 07:01:09'),
(98, 'admin.area.create', 'web', '2023-09-25 07:01:36', '2023-09-25 07:01:36'),
(99, 'admin.area.store', 'web', '2023-09-25 07:01:43', '2023-09-25 07:01:43'),
(100, 'admin.area.edit', 'web', '2023-09-25 07:01:47', '2023-09-25 07:01:47'),
(101, 'admin.area.update', 'web', '2023-09-25 07:01:52', '2023-09-25 07:01:52'),
(102, 'admin.area.destroy', 'web', '2023-09-25 07:01:57', '2023-09-25 07:01:57'),
(103, 'admin.region.create', 'web', '2023-09-25 07:02:16', '2023-09-25 07:02:16'),
(104, 'admin.region.store', 'web', '2023-09-25 07:02:22', '2023-09-25 07:02:22'),
(105, 'admin.region.edit', 'web', '2023-09-25 07:02:26', '2023-09-25 07:02:26'),
(106, 'admin.region.update', 'web', '2023-09-25 07:02:33', '2023-09-25 07:02:33'),
(107, 'admin.region.destroy', 'web', '2023-09-25 07:02:39', '2023-09-25 07:02:39'),
(108, 'admin.vendor.create', 'web', '2023-09-25 07:03:08', '2023-09-25 07:03:08'),
(109, 'admin.vendor.store', 'web', '2023-09-25 07:03:14', '2023-09-25 07:03:14'),
(110, 'admin.vendor.edit', 'web', '2023-09-25 07:03:19', '2023-09-25 07:03:19'),
(111, 'admin.vendor.update', 'web', '2023-09-25 07:03:24', '2023-09-25 07:03:24'),
(112, 'admin.vendor.destroy', 'web', '2023-09-25 07:03:29', '2023-09-25 07:03:29'),
(113, 'admin.staff.create', 'web', '2023-09-25 07:03:49', '2023-09-25 07:03:49'),
(114, 'admin.staff.store', 'web', '2023-09-25 07:03:54', '2023-09-25 07:03:54'),
(115, 'admin.staff.edit', 'web', '2023-09-25 07:03:58', '2023-09-25 07:03:58'),
(116, 'admin.staff.update', 'web', '2023-09-25 07:04:04', '2023-09-25 07:04:04'),
(117, 'admin.staff.destroy', 'web', '2023-09-25 07:04:10', '2023-09-25 07:04:10'),
(118, 'admin.category.create', 'web', '2023-09-25 07:04:55', '2023-09-25 07:04:55'),
(119, 'admin.category.store', 'web', '2023-09-25 07:04:59', '2023-09-25 07:04:59'),
(120, 'admin.category.edit', 'web', '2023-09-25 07:05:03', '2023-09-25 07:05:03'),
(121, 'admin.category.update', 'web', '2023-09-25 07:05:10', '2023-09-25 07:05:10'),
(122, 'admin.category.destroy', 'web', '2023-09-25 07:05:15', '2023-09-25 07:05:15'),
(123, 'admin.store.create', 'web', '2023-09-25 07:05:43', '2023-09-25 07:05:43'),
(124, 'admin.store.store', 'web', '2023-09-25 07:05:51', '2023-09-25 07:05:51'),
(125, 'admin.store.edit', 'web', '2023-09-25 07:05:55', '2023-09-25 07:05:55'),
(126, 'admin.store.update', 'web', '2023-09-25 07:06:00', '2023-09-25 07:06:00'),
(127, 'admin.store.destroy', 'web', '2023-09-25 07:06:05', '2023-09-25 07:06:05'),
(128, 'admin.user.create', 'web', '2023-09-25 07:06:29', '2023-09-25 07:06:29'),
(129, 'admin.user.store', 'web', '2023-09-25 07:06:33', '2023-09-25 07:06:33'),
(130, 'admin.user.edit', 'web', '2023-09-25 07:06:37', '2023-09-25 07:06:37'),
(131, 'admin.user.update', 'web', '2023-09-25 07:06:42', '2023-09-25 07:06:42'),
(132, 'admin.user.destroy', 'web', '2023-09-25 07:06:47', '2023-09-25 07:06:47'),
(133, 'admin.role.create', 'web', '2023-09-25 07:07:07', '2023-09-25 07:07:07'),
(134, 'admin.role.store', 'web', '2023-09-25 07:07:12', '2023-09-25 07:07:12'),
(135, 'admin.role.edit', 'web', '2023-09-25 07:07:17', '2023-09-25 07:07:17'),
(136, 'admin.role.update', 'web', '2023-09-25 07:07:23', '2023-09-25 07:07:23'),
(137, 'admin.role.destroy', 'web', '2023-09-25 07:07:28', '2023-09-25 07:07:28'),
(138, 'admin.branch.create', 'web', '2023-09-25 07:07:44', '2023-09-25 07:07:44'),
(139, 'admin.branch.store', 'web', '2023-09-25 07:08:29', '2023-09-25 07:08:29'),
(140, 'admin.branch.edit', 'web', '2023-09-25 07:08:34', '2023-09-25 07:08:34'),
(141, 'admin.branch.update', 'web', '2023-09-25 07:08:39', '2023-09-25 07:08:39'),
(142, 'admin.branch.destroy', 'web', '2023-09-25 07:08:44', '2023-09-25 07:08:44'),
(143, 'admin.company.create', 'web', '2023-09-25 07:09:19', '2023-09-25 07:09:19'),
(144, 'admin.company.store', 'web', '2023-09-25 07:09:24', '2023-09-25 07:09:24'),
(145, 'admin.company.edit', 'web', '2023-09-25 07:09:31', '2023-09-25 07:09:31'),
(146, 'admin.company.update', 'web', '2023-09-25 07:09:36', '2023-09-25 07:09:36'),
(147, 'admin.company.destroy', 'web', '2023-09-25 07:09:41', '2023-09-25 07:09:41'),
(148, 'admin.group.create', 'web', '2023-09-25 07:51:05', '2023-09-25 07:51:05'),
(149, 'admin.group.store', 'web', '2023-09-25 07:51:10', '2023-09-25 07:51:10'),
(150, 'admin.group.edit', 'web', '2023-09-25 07:51:14', '2023-09-25 07:51:14'),
(151, 'admin.group.update', 'web', '2023-09-25 07:51:20', '2023-09-25 07:51:20'),
(152, 'admin.group.destroy', 'web', '2023-09-25 07:51:28', '2023-09-25 07:51:28'),
(153, 'admin.client.create', 'web', '2023-09-25 10:09:28', '2023-09-25 10:09:28'),
(154, 'admin.client.store', 'web', '2023-09-25 10:09:33', '2023-09-25 10:09:33'),
(155, 'admin.client.edit', 'web', '2023-09-25 10:09:38', '2023-09-25 10:09:38'),
(156, 'admin.client.update', 'web', '2023-09-25 10:09:42', '2023-09-25 10:09:42'),
(157, 'admin.client.destroy', 'web', '2023-09-25 10:09:49', '2023-09-25 10:09:49'),
(158, 'admin.product.create', 'web', '2023-09-25 14:14:40', '2023-09-25 14:14:40'),
(159, 'admin.product.store', 'web', '2023-09-25 14:14:46', '2023-09-25 14:14:46'),
(160, 'admin.product.edit', 'web', '2023-09-25 14:14:50', '2023-09-25 14:14:50'),
(161, 'admin.product.update', 'web', '2023-09-25 14:14:55', '2023-09-25 14:14:55'),
(162, 'admin.product.destroy', 'web', '2023-09-25 14:15:02', '2023-09-25 14:15:02'),
(163, 'admin.group-target.create', 'web', '2023-09-25 15:12:45', '2023-09-25 15:12:45'),
(164, 'admin.group-target.store', 'web', '2023-09-25 15:12:50', '2023-09-25 15:12:50'),
(165, 'admin.group-target.edit', 'web', '2023-09-25 15:12:55', '2023-09-25 15:12:55'),
(166, 'admin.group-target.update', 'web', '2023-09-25 15:13:02', '2023-09-25 15:13:02'),
(167, 'admin.group-target.destroy', 'web', '2023-09-25 15:13:32', '2023-09-25 15:13:32'),
(168, 'Vehicle Setup', 'web', '2023-09-28 06:35:35', '2023-09-28 06:35:35'),
(169, 'admin.vehicle.create', 'web', '2023-09-28 06:49:24', '2023-09-28 06:49:24'),
(170, 'admin.vehicle.store', 'web', '2023-09-28 06:49:29', '2023-09-28 06:49:29'),
(171, 'admin.vehicle.edit', 'web', '2023-09-28 06:49:33', '2023-09-28 06:49:33'),
(172, 'admin.vehicle.update', 'web', '2023-09-28 06:49:38', '2023-09-28 06:49:38'),
(173, 'admin.vehicle.destroy', 'web', '2023-09-28 06:49:44', '2023-09-28 06:49:44'),
(174, 'Measurement Unit', 'web', '2023-09-29 03:11:33', '2023-09-29 03:11:33'),
(175, 'admin.lifting.create', 'web', '2023-09-30 11:29:14', '2023-09-30 11:29:14'),
(176, 'admin.lifting.store', 'web', '2023-09-30 11:29:19', '2023-09-30 11:29:19'),
(177, 'admin.lifting.edit', 'web', '2023-09-30 11:29:23', '2023-09-30 11:29:23'),
(178, 'admin.lifting.update', 'web', '2023-09-30 11:29:27', '2023-09-30 11:29:27'),
(179, 'admin.lifting.destroy', 'web', '2023-09-30 11:29:33', '2023-09-30 11:29:33'),
(180, 'Website CMS', 'web', '2023-10-08 15:08:32', '2023-10-08 15:08:32'),
(181, 'Slider', 'web', '2023-10-08 15:09:27', '2023-10-08 15:14:11'),
(182, 'Site Items', 'web', '2023-10-08 15:22:26', '2023-10-08 15:22:26'),
(183, 'Special Item', 'web', '2023-10-08 15:23:03', '2023-10-18 13:55:44'),
(184, 'Social Working', 'web', '2023-10-08 15:23:47', '2023-10-08 15:23:47'),
(185, 'About', 'web', '2023-10-08 15:24:06', '2023-10-08 15:24:06'),
(186, 'Contact', 'web', '2023-10-08 15:24:27', '2023-10-08 15:24:27'),
(187, 'Testimonial', 'web', '2023-10-08 15:24:57', '2023-10-08 15:24:57'),
(188, 'Client Message', 'web', '2023-10-08 15:25:53', '2023-10-08 15:25:53'),
(189, 'Showcase Items', 'web', '2023-10-08 15:26:21', '2023-10-08 15:26:21'),
(190, 'Details Card', 'web', '2023-10-08 15:26:50', '2023-10-08 15:26:50'),
(191, 'admin.slider.create', 'web', '2023-10-08 15:28:49', '2023-10-08 15:28:49'),
(192, 'admin.slider.store', 'web', '2023-10-08 15:28:54', '2023-10-08 15:28:54'),
(193, 'admin.slider.edit', 'web', '2023-10-08 15:28:59', '2023-10-08 15:28:59'),
(194, 'admin.slider.update', 'web', '2023-10-08 15:29:07', '2023-10-08 15:29:07'),
(195, 'admin.slider.destroy', 'web', '2023-10-08 15:29:15', '2023-10-08 15:29:15'),
(196, 'admin.special-food-item.create', 'web', '2023-10-08 15:30:28', '2023-10-08 15:30:28'),
(197, 'admin.special-food-item.store', 'web', '2023-10-08 15:30:37', '2023-10-08 15:30:37'),
(198, 'admin.special-food-item.edit', 'web', '2023-10-08 15:30:41', '2023-10-08 15:30:41'),
(199, 'admin.special-food-item.update', 'web', '2023-10-08 15:30:47', '2023-10-08 15:30:47'),
(200, 'admin.special-food-item.destroy', 'web', '2023-10-08 15:30:51', '2023-10-08 15:30:51'),
(201, 'admin.social-working.create', 'web', '2023-10-08 15:31:27', '2023-10-08 15:31:27'),
(202, 'admin.social-working.store', 'web', '2023-10-08 15:31:31', '2023-10-08 15:31:31'),
(203, 'admin.social-working.edit', 'web', '2023-10-08 15:31:36', '2023-10-08 15:31:36'),
(204, 'admin.social-working.update', 'web', '2023-10-08 15:31:41', '2023-10-08 15:31:41'),
(205, 'admin.social-working.destroy', 'web', '2023-10-08 15:31:45', '2023-10-08 15:31:45'),
(206, 'admin.testimonial.create', 'web', '2023-10-08 15:33:23', '2023-10-08 15:33:23'),
(207, 'admin.testimonial.store', 'web', '2023-10-08 15:33:28', '2023-10-08 15:33:28'),
(208, 'admin.testimonial.edit', 'web', '2023-10-08 15:33:32', '2023-10-08 15:33:32'),
(209, 'admin.testimonial.update', 'web', '2023-10-08 15:33:37', '2023-10-08 15:33:37'),
(210, 'admin.testimonial.destroy', 'web', '2023-10-08 15:34:02', '2023-10-08 15:34:02'),
(211, 'admin.client-message.create', 'web', '2023-10-08 15:34:31', '2023-10-08 15:34:31'),
(212, 'admin.client-message.store', 'web', '2023-10-08 15:34:36', '2023-10-08 15:34:36'),
(213, 'admin.client-message.edit', 'web', '2023-10-08 15:34:40', '2023-10-08 15:34:40'),
(214, 'admin.client-message.update', 'web', '2023-10-08 15:34:44', '2023-10-08 15:34:44'),
(215, 'admin.client-message.destroy', 'web', '2023-10-08 15:34:49', '2023-10-08 15:34:49'),
(216, 'admin.showcase-item.create', 'web', '2023-10-08 15:35:20', '2023-10-08 15:35:20'),
(217, 'admin.showcase-item.store', 'web', '2023-10-08 15:35:24', '2023-10-08 15:35:24'),
(218, 'admin.showcase-item.edit', 'web', '2023-10-08 15:35:28', '2023-10-08 15:35:28'),
(219, 'admin.showcase-item.update', 'web', '2023-10-08 15:35:33', '2023-10-08 15:35:33'),
(220, 'admin.showcase-item.destroy', 'web', '2023-10-08 15:35:39', '2023-10-08 15:35:39'),
(221, 'admin.details-card.create', 'web', '2023-10-08 15:36:04', '2023-10-08 15:36:04'),
(222, 'admin.details-card.store', 'web', '2023-10-08 15:36:09', '2023-10-08 15:36:09'),
(223, 'admin.details-card.edit', 'web', '2023-10-08 15:36:13', '2023-10-08 15:36:13'),
(224, 'admin.details-card.update', 'web', '2023-10-08 15:36:17', '2023-10-08 15:36:17'),
(225, 'admin.details-card.destroy', 'web', '2023-10-08 15:36:22', '2023-10-08 15:36:22'),
(226, 'Site Settings', 'web', '2023-10-08 15:50:45', '2023-10-08 15:50:45'),
(227, 'admin.offline-order.create', 'web', '2023-10-09 03:43:02', '2023-10-09 03:43:02'),
(228, 'admin.offline-order.store', 'web', '2023-10-09 03:43:07', '2023-10-09 03:43:07'),
(229, 'admin.offline-order.edit', 'web', '2023-10-09 03:43:11', '2023-10-09 03:43:11'),
(230, 'admin.offline-order.update', 'web', '2023-10-09 03:43:19', '2023-10-09 03:43:19'),
(231, 'admin.offline-order.destroy', 'web', '2023-10-09 03:43:25', '2023-10-09 03:43:25'),
(232, 'admin.offline-order.show', 'web', '2023-10-10 14:19:37', '2023-10-10 14:19:37'),
(233, 'admin.sales.create', 'web', '2023-10-11 08:10:17', '2023-10-11 08:10:17'),
(234, 'admin.sales.store', 'web', '2023-10-11 08:10:22', '2023-10-11 08:10:22'),
(235, 'admin.sales.edit', 'web', '2023-10-11 08:10:27', '2023-10-11 08:10:27'),
(236, 'admin.sales.update', 'web', '2023-10-11 08:10:32', '2023-10-11 08:10:32'),
(237, 'admin.sales.destroy', 'web', '2023-10-11 08:10:37', '2023-10-11 08:10:37'),
(238, 'admin.collection.create', 'web', '2023-10-14 16:50:47', '2023-10-14 16:50:47'),
(239, 'admin.collection.store', 'web', '2023-10-14 16:50:51', '2023-10-14 16:50:51'),
(240, 'admin.collection.edit', 'web', '2023-10-14 16:50:56', '2023-10-14 16:50:56'),
(241, 'admin.collection.update', 'web', '2023-10-14 16:51:00', '2023-10-14 16:51:00'),
(242, 'admin.collection.destroy', 'web', '2023-10-14 16:51:05', '2023-10-14 16:51:05'),
(243, 'admin.sales-return.create', 'web', '2023-10-15 06:43:17', '2023-10-15 06:43:17'),
(244, 'admin.sales-return.store', 'web', '2023-10-15 06:43:24', '2023-10-15 06:43:24'),
(245, 'admin.sales-return.edit', 'web', '2023-10-15 06:43:29', '2023-10-15 06:43:29'),
(246, 'admin.sales-return.update', 'web', '2023-10-15 06:43:34', '2023-10-15 06:43:34'),
(247, 'admin.sales-return.destroy', 'web', '2023-10-15 06:43:39', '2023-10-15 06:43:39'),
(248, 'admin.lifting-return.create', 'web', '2023-10-16 04:47:09', '2023-10-16 04:47:09'),
(249, 'admin.lifting-return.store', 'web', '2023-10-16 04:47:15', '2023-10-16 04:47:15'),
(250, 'admin.lifting-return.edit', 'web', '2023-10-16 04:47:19', '2023-10-16 04:47:19'),
(251, 'admin.lifting-return.update', 'web', '2023-10-16 04:47:28', '2023-10-16 04:47:28'),
(252, 'admin.lifting-return.destroy', 'web', '2023-10-16 04:47:34', '2023-10-16 04:47:34'),
(253, 'admin.vendor-payment.create', 'web', '2023-10-16 09:19:38', '2023-10-16 09:19:38'),
(254, 'admin.vendor-payment.store', 'web', '2023-10-16 09:19:42', '2023-10-16 09:19:42'),
(255, 'admin.vendor-payment.edit', 'web', '2023-10-16 09:19:46', '2023-10-16 09:19:46'),
(256, 'admin.vendor-payment.update', 'web', '2023-10-16 09:19:51', '2023-10-16 09:19:51'),
(257, 'admin.vendor-payment.destroy', 'web', '2023-10-16 09:20:00', '2023-10-16 09:20:00'),
(258, 'admin.transfer.create', 'web', '2023-10-18 05:08:51', '2023-10-18 05:08:51'),
(259, 'admin.transfer.store', 'web', '2023-10-18 05:09:01', '2023-10-18 05:09:01'),
(260, 'admin.transfer.edit', 'web', '2023-10-18 05:09:05', '2023-10-18 05:09:05'),
(261, 'admin.transfer.update', 'web', '2023-10-18 05:09:22', '2023-10-18 05:09:22'),
(262, 'admin.transfer.destroy', 'web', '2023-10-18 05:09:29', '2023-10-18 05:09:29'),
(263, 'admin.transfer-receive.show', 'web', '2023-10-18 09:47:14', '2023-10-18 09:47:14'),
(264, 'admin.transfer-receive.edit', 'web', '2023-10-18 09:47:49', '2023-10-18 09:47:49'),
(265, 'admin.transfer-receive.destroy', 'web', '2023-10-18 09:48:01', '2023-10-18 09:48:01'),
(266, 'admin.return-approve.show', 'web', '2023-10-18 12:04:48', '2023-10-18 12:04:48'),
(267, 'admin.return-approve.edit', 'web', '2023-10-18 12:05:03', '2023-10-18 12:05:03'),
(268, 'admin.return-approve.destroy', 'web', '2023-10-18 12:05:12', '2023-10-18 12:05:12'),
(269, 'admin.delivery.edit', 'web', '2023-11-07 06:58:42', '2023-11-07 06:58:42'),
(270, 'admin.delivery.store', 'web', '2023-11-07 07:01:19', '2023-11-07 07:01:19'),
(271, 'admin.delivery.update', 'web', '2023-11-07 07:01:31', '2023-11-07 07:01:31'),
(272, 'admin.delivery.destroy', 'web', '2023-11-07 07:01:37', '2023-11-07 07:01:37'),
(273, 'admin.delivery.create', 'web', '2023-11-07 08:01:58', '2023-11-07 08:01:58'),
(276, 'admin.rolePermission.edit', 'web', '2023-11-09 04:05:15', '2023-11-09 04:05:15'),
(277, 'admin.rolePermission.update', 'web', '2023-11-09 04:05:28', '2023-11-09 04:05:28'),
(282, 'admin.attribute.store', 'web', '2023-11-10 13:05:41', '2023-11-10 13:05:41'),
(283, 'admin.attribute.edit', 'web', '2023-11-10 13:05:54', '2023-11-10 13:05:54'),
(284, 'admin.attribute.update', 'web', '2023-11-10 13:06:02', '2023-11-10 13:06:02'),
(285, 'admin.attribute.destroy', 'web', '2023-11-10 13:06:09', '2023-11-10 13:06:09'),
(286, 'admin.client-price.create', 'web', '2023-11-10 13:15:05', '2023-11-10 13:15:05'),
(287, 'admin.client-price.store', 'web', '2023-11-10 13:15:12', '2023-11-10 13:15:12'),
(288, 'admin.client-price.edit', 'web', '2023-11-10 13:15:40', '2023-11-10 13:15:40'),
(289, 'admin.client-price.update', 'web', '2023-11-10 13:15:44', '2023-11-10 13:15:44'),
(290, 'admin.client-price.destroy', 'web', '2023-11-10 13:15:50', '2023-11-10 13:15:50'),
(291, 'Orders Processing', 'web', '2023-11-16 11:58:49', '2023-11-20 12:08:30'),
(292, 'Client Request', 'web', '2023-11-16 12:00:00', '2023-11-16 12:00:00'),
(293, 'admin.online-order.edit', 'web', '2023-11-16 12:01:31', '2023-11-16 12:01:31'),
(294, 'admin.online-order.show', 'web', '2023-11-16 12:01:39', '2023-11-16 12:01:39'),
(295, 'Access Log', 'web', '2023-11-16 12:02:14', '2023-11-16 12:02:14'),
(296, 'Online Dashboard', 'web', '2023-11-19 17:02:39', '2023-11-20 12:07:30'),
(297, 'admin.order-dashboard.edit', 'web', '2023-11-19 17:04:37', '2023-11-19 17:04:37'),
(298, 'admin.order-dashboard.update', 'web', '2023-11-19 17:04:50', '2023-11-19 17:04:50'),
(299, 'Cancelled Order', 'web', '2023-11-19 17:06:14', '2023-11-20 12:12:40'),
(300, 'admin.cancel-order.approve', 'web', '2023-11-19 17:06:43', '2023-11-19 17:06:43'),
(301, 'Delivery Gatepass', 'web', '2023-11-19 17:07:36', '2023-11-20 12:10:17'),
(302, 'admin.online-order-delivery.create', 'web', '2023-11-19 17:08:03', '2023-11-19 17:08:03'),
(303, 'admin.online-order-delivery.store', 'web', '2023-11-19 17:08:08', '2023-11-19 17:08:08'),
(304, 'admin.online-order-delivery.edit', 'web', '2023-11-19 17:08:12', '2023-11-19 17:08:12'),
(305, 'admin.online-order-delivery.update', 'web', '2023-11-19 17:08:22', '2023-11-19 17:08:22'),
(306, 'admin.online-order-delivery.destroy', 'web', '2023-11-19 17:08:29', '2023-11-19 17:08:29'),
(307, 'admin.online-order-delivery.show', 'web', '2023-11-19 17:09:04', '2023-11-19 17:09:04'),
(308, 'Delivery Charge', 'web', '2023-11-19 17:09:47', '2023-11-19 17:09:47'),
(309, 'admin.delivery-charge.update', 'web', '2023-11-19 17:10:16', '2023-11-19 17:10:16'),
(310, 'Product Statement', 'web', '2023-11-20 16:04:21', '2023-11-20 16:04:21'),
(311, 'Dashboard 2', 'web', '2023-11-22 17:19:50', '2023-11-22 17:19:50'),
(312, 'Product Request', 'web', '2023-11-22 17:20:56', '2023-11-22 17:20:56'),
(313, 'Purchase Log', 'web', '2023-11-22 17:22:52', '2023-11-22 17:22:52'),
(314, 'Return Log 2', 'web', '2023-11-22 17:23:33', '2023-11-22 17:23:33'),
(315, 'Payment Log', 'web', '2023-11-22 17:27:46', '2023-11-22 17:27:46'),
(316, 'Statement', 'web', '2023-11-22 17:28:55', '2023-11-22 17:28:55'),
(317, 'client.product-request.create', 'web', '2023-11-22 17:30:15', '2023-11-22 17:30:15'),
(318, 'client.product-request.store', 'web', '2023-11-22 17:30:23', '2023-11-22 17:30:23'),
(319, 'client.product-request.edit', 'web', '2023-11-22 17:30:30', '2023-11-22 17:30:30'),
(320, 'client.product-request.update', 'web', '2023-11-22 17:30:38', '2023-11-22 17:30:38'),
(321, 'client.product-request.destroy', 'web', '2023-11-22 17:30:48', '2023-11-22 17:30:48'),
(322, 'client.product-request.show', 'web', '2023-11-22 17:31:01', '2023-11-22 17:31:01'),
(323, 'admin.bulk-collection.create', 'web', '2023-11-23 04:57:03', '2023-11-23 04:57:03'),
(324, 'admin.bulk-collection.store', 'web', '2023-11-23 04:57:09', '2023-11-23 04:57:09'),
(325, 'admin.bulk-collection.edit', 'web', '2023-11-23 04:57:14', '2023-11-23 04:57:14'),
(326, 'admin.bulk-collection.update', 'web', '2023-11-23 04:57:20', '2023-11-23 04:57:20'),
(327, 'admin.bulk-collection.destroy', 'web', '2023-11-23 04:57:29', '2023-11-23 04:57:29'),
(328, 'admin.user.password', 'web', '2023-11-26 05:57:38', '2023-11-26 05:57:38'),
(329, 'admin.user.password-update', 'web', '2023-11-26 05:58:21', '2023-11-26 05:58:21'),
(330, 'admin.salesman-flowchart.index', 'web', '2023-11-26 11:18:53', '2024-07-31 03:30:34'),
(331, 'Client Sales Flow', 'web', '2023-11-27 08:53:49', '2023-11-27 08:53:49'),
(332, 'Online Sales History', 'web', '2023-11-27 11:16:13', '2023-11-27 11:16:13'),
(333, 'Page Setup', 'web', '2023-11-27 14:58:52', '2023-11-27 14:58:52'),
(334, 'General Accounting', 'web', '2023-12-13 12:04:08', '2023-12-13 12:04:08'),
(335, 'Transaction 4', 'web', '2023-12-13 12:04:34', '2023-12-13 12:04:34'),
(336, 'Chart of Accounts', 'web', '2023-12-13 12:05:02', '2023-12-13 12:05:02'),
(337, 'admin.debit-voucher-entry.index', 'web', '2023-12-13 12:05:40', '2024-07-31 03:32:43'),
(338, 'admin.credit-voucher-entry.index', 'web', '2023-12-13 12:05:59', '2024-07-31 03:32:48'),
(339, 'admin.journal-voucher-entry.index', 'web', '2023-12-13 12:06:21', '2024-07-31 03:32:53'),
(340, 'Voucher Approve', 'web', '2023-12-13 12:06:45', '2023-12-13 12:06:45'),
(341, 'Voucher Refuse', 'web', '2023-12-13 12:07:04', '2023-12-13 12:07:04'),
(342, 'Posting Automation', 'web', '2023-12-13 12:09:24', '2023-12-13 12:09:24'),
(343, 'Automation Refuse', 'web', '2023-12-13 12:09:41', '2023-12-13 12:09:41'),
(344, 'admin.coa-setup.store', 'web', '2023-12-13 12:12:03', '2023-12-13 12:12:03'),
(345, 'admin.coa-setup.edit', 'web', '2023-12-13 12:12:10', '2023-12-13 12:12:10'),
(346, 'admin.coa-setup.update', 'web', '2023-12-13 12:12:17', '2023-12-13 12:12:17'),
(347, 'admin.coa-setup.destroy', 'web', '2023-12-13 12:12:25', '2023-12-13 12:12:25'),
(348, 'admin.debit-voucher-entry.create', 'web', '2023-12-13 12:13:07', '2023-12-13 12:13:07'),
(349, 'admin.debit-voucher-entry.store', 'web', '2023-12-13 12:13:16', '2023-12-13 12:13:16'),
(350, 'admin.debit-voucher-entry.edit', 'web', '2023-12-13 12:13:24', '2023-12-13 12:13:24'),
(351, 'admin.debit-voucher-entry.update', 'web', '2023-12-13 12:13:30', '2023-12-13 12:13:30'),
(352, 'admin.debit-voucher-entry.destroy', 'web', '2023-12-13 12:13:38', '2023-12-13 12:13:38'),
(353, 'admin.credit-voucher-entry.create', 'web', '2023-12-13 12:13:58', '2023-12-13 12:13:58'),
(354, 'admin.credit-voucher-entry.store', 'web', '2023-12-13 12:14:08', '2023-12-13 12:14:08'),
(355, 'admin.credit-voucher-entry.edit', 'web', '2023-12-13 12:14:14', '2023-12-13 12:14:14'),
(356, 'admin.credit-voucher-entry.update', 'web', '2023-12-13 12:14:21', '2023-12-13 12:14:21'),
(357, 'admin.credit-voucher-entry.destroy', 'web', '2023-12-13 12:14:27', '2023-12-13 12:14:27'),
(358, 'admin.journal-voucher-entry.create', 'web', '2023-12-13 12:14:55', '2023-12-13 12:14:55'),
(359, 'admin.journal-voucher-entry.store', 'web', '2023-12-13 12:15:01', '2023-12-13 12:15:01'),
(360, 'admin.journal-voucher-entry.edit', 'web', '2023-12-13 12:15:06', '2023-12-13 12:15:06'),
(361, 'admin.journal-voucher-entry.update', 'web', '2023-12-13 12:15:12', '2023-12-13 12:15:12'),
(362, 'admin.journal-voucher-entry.destroy', 'web', '2023-12-13 12:15:18', '2023-12-13 12:15:18'),
(363, 'admin.voucher-approve.show', 'web', '2023-12-13 12:16:48', '2023-12-13 12:16:48'),
(364, 'admin.voucher-approve.edit', 'web', '2023-12-13 12:16:56', '2023-12-13 12:16:56'),
(365, 'admin.voucher-reject.show', 'web', '2023-12-13 12:17:48', '2023-12-13 12:17:48'),
(366, 'admin.voucher-reject.edit', 'web', '2023-12-13 12:18:00', '2023-12-13 12:18:00'),
(367, 'admin.automation-approve.show', 'web', '2023-12-13 12:19:10', '2023-12-13 12:19:10'),
(368, 'admin.automation-approve.edit', 'web', '2023-12-13 12:19:19', '2023-12-13 12:19:19'),
(369, 'admin.automation-reject.show', 'web', '2023-12-13 12:19:47', '2023-12-13 12:19:47'),
(370, 'admin.automation-reject.edit', 'web', '2023-12-13 12:19:54', '2023-12-13 12:20:22'),
(371, 'Reports 4', 'web', '2023-12-13 16:37:50', '2023-12-13 16:37:50'),
(372, 'Chart of Accounts 2', 'web', '2023-12-13 16:38:13', '2023-12-13 16:38:13'),
(373, 'Daily Voucher List', 'web', '2023-12-16 06:26:26', '2023-12-16 06:26:26'),
(374, 'Daily Cash Book', 'web', '2023-12-19 14:46:09', '2023-12-19 14:46:09'),
(375, 'Daily Bank Book', 'web', '2023-12-19 14:46:38', '2023-12-19 14:46:38'),
(376, 'Transaction Ledger', 'web', '2023-12-19 14:47:08', '2023-12-19 14:47:08'),
(377, 'Cash Flow Statement', 'web', '2023-12-19 14:47:26', '2023-12-19 14:47:26'),
(378, 'General Ledger', 'web', '2023-12-19 14:47:45', '2023-12-19 14:47:45'),
(379, 'Trial Balance', 'web', '2023-12-30 16:33:07', '2023-12-30 16:33:07'),
(380, 'Income Statement', 'web', '2023-12-30 16:37:10', '2023-12-30 16:37:10'),
(381, 'Subscribers', 'web', '2024-01-23 04:24:23', '2024-01-23 04:24:23'),
(382, 'Admin Settings', 'web', '2024-02-27 10:29:24', '2024-02-27 10:29:24'),
(383, 'Balance Sheet', 'web', '2024-02-28 15:09:20', '2024-02-28 15:09:20'),
(384, 'Clear cache', 'web', '2024-03-03 04:17:45', '2024-03-03 04:17:45'),
(385, 'Generate Barcode', 'web', '2024-03-12 08:42:09', '2024-03-12 08:42:09'),
(386, 'POS Sale', 'web', '2024-03-12 15:46:43', '2024-03-12 15:46:43'),
(387, 'admin.pos-sales.create', 'web', '2024-03-12 22:55:34', '2024-03-12 22:55:34'),
(388, 'admin.pos-sales.store', 'web', '2024-03-12 22:55:40', '2024-03-12 22:55:40'),
(389, 'admin.pos-sales.edit', 'web', '2024-03-12 22:55:46', '2024-03-12 22:55:46'),
(390, 'admin.pos-sales.update', 'web', '2024-03-12 22:55:57', '2024-03-12 22:55:57'),
(391, 'admin.pos-sales.destroy', 'web', '2024-03-12 22:56:05', '2024-03-12 22:56:05'),
(392, 'admin.profit-loss.index', 'web', '2024-04-08 09:56:46', '2024-07-31 03:34:55'),
(393, 'Sizes', 'web', '2024-04-16 16:16:55', '2024-04-16 16:16:55'),
(394, 'Colors', 'web', '2024-04-16 16:17:26', '2024-04-16 16:17:26'),
(395, 'admin.size.create', 'web', '2024-04-16 16:44:38', '2024-04-16 16:44:38'),
(396, 'admin.size.store', 'web', '2024-04-16 16:44:43', '2024-04-16 16:44:43'),
(397, 'admin.size.edit', 'web', '2024-04-16 16:44:50', '2024-04-16 16:44:50'),
(398, 'admin.size.update', 'web', '2024-04-16 16:44:55', '2024-04-16 16:44:55'),
(399, 'admin.size.destroy', 'web', '2024-04-16 16:45:02', '2024-04-16 16:45:02'),
(400, 'admin.color.create', 'web', '2024-04-16 16:47:21', '2024-04-16 16:47:21'),
(401, 'admin.color.store', 'web', '2024-04-16 16:47:28', '2024-04-16 16:47:28'),
(402, 'admin.color.edit', 'web', '2024-04-16 16:47:32', '2024-04-16 16:47:32'),
(403, 'admin.color.update', 'web', '2024-04-16 16:47:38', '2024-04-16 16:47:38'),
(404, 'admin.color.destroy', 'web', '2024-04-16 16:47:45', '2024-04-16 16:47:45'),
(405, 'admin.attribute-value.index', 'web', '2024-05-01 05:25:48', '2024-05-01 05:25:48'),
(406, 'Lifestyle Product Lifting', 'web', '2024-06-01 10:33:55', '2024-06-02 03:17:06'),
(407, 'admin.lifting-fashion-product.create', 'web', '2024-06-01 10:36:09', '2024-06-01 10:36:09'),
(408, 'admin.lifting-fashion-product.store', 'web', '2024-06-01 10:36:14', '2024-06-01 10:36:14'),
(409, 'admin.lifting-fashion-product.edit', 'web', '2024-06-01 10:36:19', '2024-06-01 10:36:19'),
(410, 'admin.lifting-fashion-product.update', 'web', '2024-06-01 10:36:24', '2024-06-01 10:36:24'),
(411, 'admin.lifting-fashion-product.destroy', 'web', '2024-06-01 10:36:29', '2024-06-01 10:36:29'),
(412, 'Lifestyle Product Sales', 'web', '2024-06-04 10:47:16', '2024-06-04 10:47:16'),
(413, 'admin.sales-lifestyle-product.create', 'web', '2024-06-04 10:47:40', '2024-06-04 10:47:40'),
(414, 'admin.sales-lifestyle-product.store', 'web', '2024-06-04 10:47:45', '2024-06-04 10:47:45'),
(415, 'admin.sales-lifestyle-product.edit', 'web', '2024-06-04 10:47:49', '2024-06-04 10:47:49'),
(416, 'admin.sales-lifestyle-product.update', 'web', '2024-06-04 10:47:54', '2024-06-04 10:47:54'),
(417, 'admin.sales-lifestyle-product.destroy', 'web', '2024-06-04 10:48:00', '2024-06-04 10:48:00'),
(418, 'Lifestyle Product Return', 'web', '2024-06-05 06:00:31', '2024-06-05 06:00:31'),
(419, 'admin.sales-lifestyle-product.search-edit', 'web', '2024-06-05 06:02:52', '2024-06-05 06:02:52'),
(420, 'admin.lifestyle-product-return.create', 'web', '2024-06-05 06:04:38', '2024-06-05 06:04:38'),
(421, 'admin.lifestyle-product-return.store', 'web', '2024-06-05 06:04:47', '2024-06-05 06:04:47'),
(422, 'admin.lifestyle-product-return.edit', 'web', '2024-06-05 06:04:52', '2024-06-05 06:04:52'),
(423, 'admin.lifestyle-product-return.update', 'web', '2024-06-05 06:05:10', '2024-06-05 06:05:10'),
(424, 'admin.lifestyle-product-return.destroy', 'web', '2024-06-05 06:05:18', '2024-06-05 06:05:18'),
(425, 'admin.sales.search-edit', 'web', '2024-06-05 06:15:09', '2024-06-05 06:15:09'),
(426, 'admin.delivery.delivered', 'web', '2024-06-06 13:22:34', '2024-06-06 13:22:34'),
(427, 'Menu Setup 2', 'web', '2024-06-23 04:18:38', '2024-06-23 04:18:38'),
(428, 'admin.menu.create', 'web', '2024-06-23 04:19:24', '2024-06-23 04:19:24'),
(429, 'admin.menu.store', 'web', '2024-06-23 04:19:31', '2024-06-23 04:19:31'),
(430, 'admin.menu.edit', 'web', '2024-06-23 04:19:36', '2024-06-23 04:19:36'),
(431, 'admin.menu.update', 'web', '2024-06-23 04:19:43', '2024-06-23 04:19:43'),
(432, 'admin.menu.destroy', 'web', '2024-06-23 04:19:51', '2024-06-23 04:19:51'),
(433, 'admin.menu-item.index', 'web', '2024-06-23 04:25:39', '2024-06-23 04:25:39'),
(434, 'admin.menu-item.update', 'web', '2024-06-23 04:25:47', '2024-06-23 04:25:47'),
(435, 'admin.menu-item.serialize', 'web', '2024-06-23 04:27:02', '2024-06-23 04:27:02'),
(436, 'admin.menu-item.destroy', 'web', '2024-06-23 04:27:40', '2024-06-23 04:27:40'),
(437, 'admin.product.attributes', 'web', '2024-06-23 11:11:49', '2024-06-23 11:11:49'),
(438, 'Homepage Section', 'web', '2024-06-23 12:14:58', '2024-06-23 12:14:58'),
(439, 'admin.sections.create', 'web', '2024-06-23 12:15:14', '2024-06-23 12:15:14'),
(440, 'admin.sections.store', 'web', '2024-06-23 12:15:18', '2024-06-23 12:15:18'),
(441, 'admin.sections.edit', 'web', '2024-06-23 12:15:22', '2024-06-23 12:15:22'),
(442, 'admin.sections.update', 'web', '2024-06-23 12:15:26', '2024-06-23 12:15:26'),
(443, 'admin.sections.destroy', 'web', '2024-06-23 12:15:31', '2024-06-23 12:15:31'),
(450, 'Running Sales', 'web', '2024-07-13 12:38:18', '2024-07-13 12:38:18'),
(451, 'admin.running-sales.create', 'web', '2024-07-13 12:39:23', '2024-07-13 12:39:23'),
(452, 'admin.running-sales.store', 'web', '2024-07-13 12:39:29', '2024-07-13 12:39:29'),
(453, 'admin.running-sales.edit', 'web', '2024-07-13 12:39:40', '2024-07-13 12:39:40'),
(454, 'admin.running-sales.update', 'web', '2024-07-13 12:40:12', '2024-07-13 12:40:12'),
(455, 'admin.running-sales.destroy', 'web', '2024-07-13 12:40:19', '2024-07-13 12:40:19'),
(457, 'Dashboard 3', 'web', '2024-07-16 16:19:08', '2024-07-16 16:19:08'),
(458, 'Product Statement 2', 'web', '2024-07-16 16:33:48', '2024-07-16 16:33:48'),
(459, 'investor.product-wise-profit.index', 'web', '2024-07-17 05:04:11', '2024-08-03 10:32:48'),
(460, 'Settings', 'web', '2024-07-17 10:32:53', '2024-07-17 10:32:53'),
(461, 'Payment Request', 'web', '2024-07-17 12:14:36', '2024-07-17 12:14:36'),
(462, 'investor.payment.create', 'web', '2024-07-17 12:25:21', '2024-07-17 12:25:21'),
(463, 'investor.payment.store', 'web', '2024-07-17 12:25:26', '2024-07-17 12:25:26'),
(464, 'investor.payment.edit', 'web', '2024-07-17 12:25:31', '2024-07-17 12:25:31'),
(465, 'investor.payment.update', 'web', '2024-07-17 12:25:37', '2024-07-17 12:25:37'),
(466, 'investor.payment.destroy', 'web', '2024-07-17 12:25:44', '2024-07-17 12:25:44'),
(467, 'investor.payment.show', 'web', '2024-07-17 12:47:17', '2024-07-17 12:47:17'),
(484, 'investor.statement.index', 'web', '2024-07-23 17:34:34', '2024-08-03 09:07:59'),
(485, 'admin.approve-payment.index', 'web', '2024-07-23 17:56:48', '2024-07-30 09:06:53'),
(486, 'admin.investor-sattlement.index', 'web', '2024-07-23 17:59:13', '2024-07-30 09:08:20'),
(488, 'admin.investor-sattlement.create', 'web', '2024-07-24 05:20:55', '2024-07-24 05:20:55'),
(489, 'admin.investor-sattlement.store', 'web', '2024-07-24 05:20:59', '2024-07-24 05:20:59'),
(490, 'admin.investor-sattlement.edit', 'web', '2024-07-24 05:21:07', '2024-07-24 05:21:07'),
(491, 'admin.investor-sattlement.update', 'web', '2024-07-24 05:21:12', '2024-07-24 05:21:12'),
(492, 'admin.investor-sattlement.destroy', 'web', '2024-07-24 05:21:17', '2024-07-24 05:21:17'),
(493, 'Investor 9', 'web', '2024-07-29 02:19:56', '2024-07-29 02:19:56'),
(494, 'Transaction 6', 'web', '2024-07-29 02:20:43', '2024-07-29 02:20:43'),
(495, 'Investor Setup', 'web', '2024-07-29 02:22:59', '2024-07-29 02:22:59'),
(496, 'admin.investor.create', 'web', '2024-07-29 02:26:07', '2024-07-29 02:26:07'),
(497, 'admin.investor.store', 'web', '2024-07-29 02:26:21', '2024-07-29 02:26:21'),
(498, 'admin.investor.edit', 'web', '2024-07-29 02:26:30', '2024-07-29 02:26:30'),
(499, 'admin.investor.update', 'web', '2024-07-29 02:26:37', '2024-07-29 02:26:37'),
(500, 'admin.investor.destroy', 'web', '2024-07-29 02:26:44', '2024-07-29 02:26:44'),
(501, 'admin.investor-product.edit', 'web', '2024-07-29 02:26:58', '2024-07-29 02:26:58'),
(502, 'Invest Process', 'web', '2024-07-29 02:30:32', '2024-07-30 09:01:25'),
(503, 'admin.invest.create', 'web', '2024-07-29 02:31:04', '2024-07-29 02:31:04'),
(504, 'admin.invest.store', 'web', '2024-07-29 02:31:16', '2024-07-29 02:31:16'),
(505, 'admin.invest.edit', 'web', '2024-07-29 02:31:27', '2024-07-29 02:31:27'),
(506, 'admin.invest.update', 'web', '2024-07-29 02:31:34', '2024-07-29 02:31:34'),
(507, 'admin.invest.destroy', 'web', '2024-07-29 02:31:42', '2024-07-29 02:31:42'),
(508, 'Approve Invest 2', 'web', '2024-07-29 02:35:31', '2024-07-29 02:35:31'),
(509, 'admin.approve-invest.edit', 'web', '2024-07-29 02:36:05', '2024-07-29 02:36:05'),
(510, 'admin.approve-invest.show', 'web', '2024-07-29 02:36:22', '2024-07-29 02:36:22'),
(511, 'Profit Distribution', 'web', '2024-07-29 02:39:35', '2024-07-29 02:39:35'),
(512, 'admin.profit-distribute.create', 'web', '2024-07-29 02:39:52', '2024-07-29 02:39:52'),
(513, 'admin.profit-distribute.store', 'web', '2024-07-29 02:40:16', '2024-07-29 02:40:16'),
(514, 'admin.profit-distribute.edit', 'web', '2024-07-29 02:40:21', '2024-07-29 02:40:21'),
(515, 'admin.profit-distribute.update', 'web', '2024-07-29 02:40:26', '2024-07-29 02:40:26'),
(516, 'admin.profit-distribute.destroy', 'web', '2024-07-29 02:40:31', '2024-07-29 02:40:31'),
(517, 'Reports 5', 'web', '2024-07-30 09:08:40', '2024-07-30 09:08:40'),
(518, 'Investment Profit Sheet', 'web', '2024-07-30 09:09:24', '2024-07-30 09:09:24'),
(519, 'admin.investor-statement.index', 'web', '2024-07-30 09:10:13', '2024-08-01 04:12:42'),
(520, 'Profit Due List', 'web', '2024-07-30 09:11:00', '2024-07-30 09:11:00'),
(521, 'Investor Payment', 'web', '2024-08-01 05:19:16', '2024-08-01 05:19:16'),
(522, 'admin.payment.create', 'web', '2024-08-01 07:45:11', '2024-08-01 07:45:11'),
(523, 'admin.payment.store', 'web', '2024-08-01 07:45:16', '2024-08-01 07:45:16'),
(524, 'admin.payment.edit', 'web', '2024-08-01 07:45:20', '2024-08-01 07:45:20'),
(525, 'admin.payment.update', 'web', '2024-08-01 07:45:26', '2024-08-01 07:45:26'),
(526, 'admin.payment.destroy', 'web', '2024-08-01 07:45:32', '2024-08-01 07:45:32'),
(527, 'Product Status', 'web', '2024-08-03 09:19:58', '2024-08-03 09:19:58');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `video_id` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `client` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `technologies` varchar(255) DEFAULT NULL,
  `budget` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `product_type` varchar(255) NOT NULL DEFAULT 'Consumer',
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `attribute_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `more_images` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `additional_info` longtext DEFAULT NULL,
  `meta_title` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `alert_quantity` bigint(20) DEFAULT NULL,
  `min_order` bigint(20) NOT NULL DEFAULT 1,
  `max_order` bigint(20) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `video_id` varchar(255) DEFAULT NULL,
  `ctn_size` float NOT NULL DEFAULT 0,
  `attributes` text NOT NULL DEFAULT '\'[]\'',
  `choice_options` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `trending` tinyint(4) NOT NULL DEFAULT 0,
  `featured` tinyint(4) NOT NULL DEFAULT 0,
  `top_rated` tinyint(4) NOT NULL DEFAULT 0,
  `best_selling` tinyint(4) NOT NULL DEFAULT 0,
  `serial` int(10) NOT NULL DEFAULT 0,
  `allowed_investor` int(10) NOT NULL DEFAULT 1,
  `shared_profit` decimal(16,2) DEFAULT NULL,
  `show_on_website` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `company_id`, `product_type`, `vendor_id`, `attribute_id`, `category_id`, `brand_id`, `name`, `code`, `slug`, `thumbnail`, `more_images`, `short_description`, `description`, `additional_info`, `meta_title`, `meta_description`, `meta_keyword`, `alert_quantity`, `min_order`, `max_order`, `video`, `video_id`, `ctn_size`, `attributes`, `choice_options`, `status`, `trending`, `featured`, `top_rated`, `best_selling`, `serial`, `allowed_investor`, `shared_profit`, `show_on_website`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(150, 1, 'Consumer', 1, 11, 31, NULL, 'Chicken Ball', 'chickenball', 'chicken-ball', 'media/product//2024-07-25-LmYmMtLTBrToDKt743pM8R5yyknPRnQdts48iZai.webp', 'media/product//2024-07-25-lFfXMlITBThmEDEGGEi4NxJ7AUTo9SqDagRpDq8e.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 1, 2, 20.00, 1, 2, 1, NULL, NULL, '2024-07-25 05:18:36', '2024-07-31 03:57:56'),
(151, 1, 'Consumer', 1, 11, 31, NULL, 'Chicken Naget', 'CN54766', 'chicken-naget', 'media/product//2024-07-25-Karg2AVKPCApikp5wgJHz7SUf000R87XbEki8grl.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 50, 1, 30.00, 1, 2, 2, NULL, NULL, '2024-07-25 05:23:18', '2024-07-28 07:01:33'),
(152, 1, 'Consumer', 1, 11, 31, NULL, 'Soses', 'S2142', 'soses', 'media/product//2024-07-25-tICS5aVj3JgW6f344KCsslcusrp96MD4cieHINE8.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 2, 2, 20.00, 1, 2, 1, NULL, NULL, '2024-07-25 05:27:33', '2024-07-31 03:58:00'),
(153, 1, 'Consumer', 1, 11, 31, NULL, 'Chicken Momo', 'CM554', 'chicken-momo', 'media/product//2024-07-25-4Kxs1M8rasp0T6z3bkHfZvSQ1EiyeOsAMybllOPN.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 50, 1, NULL, 1, 2, 2, NULL, NULL, '2024-07-25 05:29:47', '2024-07-28 07:01:27'),
(154, 1, 'Consumer', 1, 11, 31, NULL, 'Chicken Salami', 'CS001', 'chicken-salami', 'media/product//2024-07-25-LTUa1NXFA3qJuVdUkcbPhsXVmtrbdAaLekgztgTk.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 50, 1, 30.00, 1, 2, 2, NULL, NULL, '2024-07-25 05:33:38', '2024-07-28 07:01:37'),
(155, 1, 'Consumer', 1, 11, 43, NULL, 'Unthon Pati', NULL, 'unthon-pati', 'media/product//2024-07-25-rkPWHy3n1xVmbMMrPNLLIFRoh8aZ5Gnp3uiJwo5T.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 8, 1, 30.00, 1, 2, 2, NULL, NULL, '2024-07-25 05:38:22', '2024-07-28 07:11:15'),
(156, 1, 'Consumer', 1, 11, 31, NULL, 'Ready Unthon', NULL, 'ready-unthon', 'media/product//2024-07-25-EqP3Tkf7jnphhdoR3SfZvgs33QHNc1fAKkGAOkuA.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 50, 7, NULL, 1, 2, 2, NULL, NULL, '2024-07-25 05:42:28', '2024-07-28 07:06:53'),
(157, 1, 'Consumer', 1, 11, 43, NULL, 'Cheese', 'CSE544', 'cheese', 'media/product//2024-07-25-gh2iEKcZGQsA2AKpcVZemIsYbONldflmsLvad2Ek.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 3, 1, 25.00, 1, 2, 1, NULL, NULL, '2024-07-25 05:50:29', '2024-07-31 03:59:02'),
(158, 1, 'Consumer', 1, 11, 44, NULL, 'Ghee', 'GH547', 'ghee', 'media/product//2024-07-25-4u2a1RW3iaKOSI8wG8okZznpDWOU2m9uD5LevBTr.webp', 'media/product//2024-07-25-jgmH2G5GlJzJ0eOCm3OZClSvuMwANGOcZhX6MFfn.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 50, 1, 30.00, 1, 2, 2, NULL, NULL, '2024-07-25 05:53:24', '2024-07-28 07:01:55'),
(159, 1, 'Consumer', 1, 11, 43, NULL, 'Frozzen Porata', 'FP001', 'frozzen-porata', 'media/product//2024-07-25-uDUDqMKYm0HbWH7cAVEBS9wyU6y6zMTccma0KpdW.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 4, 1, 25.00, 1, 2, 1, NULL, NULL, '2024-07-25 05:58:07', '2024-07-31 03:58:39'),
(160, 1, 'Consumer', 1, 11, 31, NULL, 'Chicken Somucha', NULL, 'chicken-somucha', 'media/product//2024-07-25-trCMLj8yxLUOEWmrVRScVsAnzLCv4z4OtRRbxk19.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 50, 1, 30.00, 1, 2, 2, NULL, NULL, '2024-07-25 06:00:27', '2024-07-28 07:01:42'),
(161, 1, 'Consumer', 1, 11, 43, NULL, 'Ready To Cook Beef', 'RCB001', 'ready-to-cook-beef', 'media/product//2024-07-25-mEC52ytr569oiH8A2KxElGOCSG7r8i6mjtPcEWps.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 5, 3, 30.00, 1, 2, 2, NULL, NULL, '2024-07-25 06:04:04', '2024-07-28 07:05:38'),
(162, 1, 'Consumer', 1, 11, 44, NULL, 'Liquid Milk', 'LM001', 'liquid-milk', 'media/product//2024-07-25-a7wNxerWxtnnOeuGbceHSTxrCNMAdppjwHr6UzZY.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 50, 1, 30.00, 1, 2, 1, NULL, NULL, '2024-07-25 06:06:55', '2024-07-31 03:57:21'),
(163, 1, 'Consumer', 1, 2, 43, NULL, 'Friench Fry', 'FF001', 'friench-fry', 'media/product//2024-07-25-HYn4j5opVicJbSGkiKsjE1Y5dHm3nqYLgdyVgogF.webp', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, 0, '[]', '[]', 1, 1, 0, 0, 0, 6, 1, 30.00, 1, 2, 2, NULL, NULL, '2024-07-25 06:09:16', '2024-07-28 07:06:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_prices`
--

CREATE TABLE `product_prices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `lifting_price` decimal(16,2) NOT NULL,
  `sale_price` decimal(16,2) NOT NULL,
  `online_price` decimal(8,2) DEFAULT NULL,
  `discount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discount_tk` decimal(16,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_prices`
--

INSERT INTO `product_prices` (`id`, `product_id`, `lifting_price`, `sale_price`, `online_price`, `discount`, `discount_tk`, `created_at`, `updated_at`) VALUES
(50, 153, 250.00, 340.00, 380.00, 0.00, 0.00, '2024-07-28 07:01:27', '2024-07-28 07:01:27'),
(51, 151, 400.00, 450.00, 500.00, 0.00, 0.00, '2024-07-28 07:01:33', '2024-07-28 07:01:33'),
(52, 154, 350.00, 400.00, 450.00, 0.00, 0.00, '2024-07-28 07:01:37', '2024-07-28 07:01:37'),
(53, 160, 250.00, 280.00, 300.00, 0.00, 0.00, '2024-07-28 07:01:42', '2024-07-28 07:01:42'),
(56, 158, 600.00, 680.00, 730.00, 0.00, 0.00, '2024-07-28 07:01:55', '2024-07-28 07:01:55'),
(61, 161, 750.00, 840.00, 860.00, 0.00, 0.00, '2024-07-28 07:05:38', '2024-07-28 07:05:38'),
(62, 163, 0.00, 0.00, 0.00, 0.00, 0.00, '2024-07-28 07:06:37', '2024-07-28 07:06:37'),
(63, 156, 375.00, 450.00, 490.00, 0.00, 0.00, '2024-07-28 07:06:53', '2024-07-28 07:06:53'),
(65, 155, 75.00, 82.50, 88.00, 0.00, 0.00, '2024-07-28 07:11:15', '2024-07-28 07:11:15'),
(70, 162, 65.00, 90.00, 90.00, 0.00, 0.00, '2024-07-31 03:57:21', '2024-07-31 03:57:21'),
(71, 150, 300.00, 340.00, 380.00, 0.00, 0.00, '2024-07-31 03:57:56', '2024-07-31 03:57:56'),
(72, 152, 300.00, 330.00, 360.00, 0.00, 0.00, '2024-07-31 03:58:00', '2024-07-31 03:58:00'),
(73, 159, 135.00, 180.00, 200.00, 0.00, 0.00, '2024-07-31 03:58:39', '2024-07-31 03:58:39'),
(74, 157, 600.00, 700.00, 780.00, 0.00, 0.00, '2024-07-31 03:59:02', '2024-07-31 03:59:02');

-- --------------------------------------------------------

--
-- Table structure for table `product_skus`
--

CREATE TABLE `product_skus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant` varchar(255) NOT NULL,
  `lifting_price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `price` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discount_tk` decimal(16,2) NOT NULL DEFAULT 0.00,
  `sku` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `incharge_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `company_id`, `code`, `name`, `incharge_name`, `phone`, `email`, `address`, `status`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'ADS', 'Dhaka South', 'xxxxx', '1111', 'xxxxx@gmail.com', 'address will goes here', 1, 1, 2, NULL, NULL, '2023-09-23 06:53:22', '2023-12-21 05:24:46'),
(6, 1, 'CTGR', 'Chattogram', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2023-09-24 04:09:44', '2023-09-24 04:09:44'),
(10, 1, 'ADN', 'Dhaka North', NULL, NULL, NULL, NULL, 1, 2, 2, NULL, NULL, '2023-11-06 09:24:54', '2023-12-21 05:24:36'),
(13, 1, 'PF', 'Region One', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2024-07-13 12:44:10', '2024-07-13 12:44:10');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `star` tinyint(4) NOT NULL DEFAULT 1,
  `title` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `images` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `company_id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, NULL, 'System Admin', 'web', '2023-10-24 06:03:09', '2023-10-24 06:03:09'),
(4, 1, 'CRM', 'web', '2023-11-07 03:50:58', '2023-11-07 03:50:58'),
(5, 1, 'MANAGEMENT', 'web', '2023-11-07 03:51:19', '2023-11-07 04:11:24'),
(6, 1, 'MIS', 'web', '2023-11-07 03:51:30', '2023-11-07 03:51:30'),
(7, 1, 'STORE', 'web', '2023-11-07 03:57:58', '2023-11-07 03:57:58'),
(8, 1, 'AFM', 'web', '2023-11-07 03:58:06', '2023-11-07 03:58:06'),
(9, 1, 'SALES MANAGEMENT', 'web', '2023-11-07 03:58:19', '2023-11-07 03:58:19'),
(10, NULL, 'Software Admin', 'web', '2023-11-09 03:59:06', '2023-11-09 03:59:06'),
(11, NULL, 'Client', 'web', '2023-11-22 17:17:13', '2023-11-22 17:17:13'),
(12, NULL, 'Investor', 'web', '2024-07-11 06:16:55', '2024-07-11 06:16:55');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(2, 1),
(2, 5),
(2, 6),
(2, 10),
(3, 1),
(3, 5),
(3, 6),
(3, 10),
(4, 1),
(4, 5),
(4, 6),
(4, 10),
(5, 1),
(5, 5),
(5, 6),
(5, 10),
(6, 1),
(6, 5),
(6, 6),
(6, 10),
(7, 10),
(8, 10),
(9, 10),
(10, 10),
(11, 10),
(12, 10),
(14, 10),
(15, 10),
(16, 10),
(17, 10),
(18, 10),
(19, 10),
(20, 1),
(20, 5),
(20, 6),
(20, 10),
(21, 1),
(21, 5),
(21, 6),
(21, 10),
(22, 1),
(22, 5),
(22, 6),
(22, 10),
(23, 1),
(23, 5),
(23, 6),
(23, 10),
(24, 1),
(24, 5),
(24, 6),
(24, 10),
(25, 1),
(25, 5),
(25, 6),
(25, 10),
(26, 1),
(26, 5),
(26, 6),
(26, 10),
(27, 1),
(27, 5),
(27, 6),
(27, 10),
(28, 1),
(28, 5),
(28, 6),
(28, 10),
(29, 1),
(29, 5),
(29, 6),
(29, 10),
(30, 1),
(30, 5),
(30, 6),
(30, 8),
(30, 10),
(31, 1),
(31, 5),
(31, 6),
(31, 8),
(31, 10),
(32, 1),
(32, 5),
(32, 6),
(32, 8),
(32, 10),
(33, 1),
(33, 5),
(33, 6),
(33, 8),
(33, 10),
(34, 1),
(34, 5),
(34, 6),
(34, 8),
(34, 10),
(35, 1),
(35, 5),
(35, 6),
(35, 8),
(35, 10),
(36, 1),
(36, 5),
(36, 6),
(36, 8),
(36, 10),
(37, 1),
(37, 5),
(37, 6),
(37, 8),
(37, 10),
(38, 1),
(38, 5),
(38, 6),
(38, 8),
(38, 10),
(39, 1),
(39, 5),
(39, 6),
(39, 8),
(39, 10),
(40, 1),
(40, 5),
(40, 6),
(40, 8),
(40, 10),
(41, 1),
(41, 4),
(41, 5),
(41, 6),
(41, 7),
(41, 8),
(41, 9),
(41, 10),
(42, 1),
(42, 5),
(42, 6),
(42, 7),
(42, 8),
(42, 10),
(43, 1),
(43, 5),
(43, 6),
(43, 7),
(43, 8),
(43, 10),
(44, 1),
(44, 5),
(44, 6),
(44, 7),
(44, 8),
(44, 10),
(45, 1),
(45, 4),
(45, 5),
(45, 6),
(45, 7),
(45, 8),
(45, 9),
(45, 10),
(47, 1),
(47, 4),
(47, 5),
(47, 6),
(47, 7),
(47, 8),
(47, 9),
(47, 10),
(48, 1),
(48, 4),
(48, 5),
(48, 6),
(48, 7),
(48, 8),
(48, 9),
(48, 10),
(49, 1),
(49, 4),
(49, 5),
(49, 6),
(49, 7),
(49, 8),
(49, 9),
(49, 10),
(50, 1),
(50, 4),
(50, 5),
(50, 6),
(50, 7),
(50, 8),
(50, 9),
(50, 10),
(51, 1),
(51, 5),
(51, 6),
(51, 10),
(52, 1),
(52, 5),
(52, 6),
(52, 10),
(53, 1),
(53, 5),
(53, 6),
(53, 10),
(54, 1),
(54, 4),
(54, 5),
(54, 6),
(54, 8),
(54, 9),
(54, 10),
(55, 1),
(55, 4),
(55, 5),
(55, 6),
(55, 8),
(55, 9),
(55, 10),
(56, 1),
(56, 4),
(56, 5),
(56, 6),
(56, 8),
(56, 9),
(56, 10),
(57, 4),
(57, 5),
(57, 6),
(57, 8),
(57, 9),
(57, 10),
(58, 1),
(58, 4),
(58, 5),
(58, 6),
(58, 8),
(58, 9),
(58, 10),
(59, 1),
(59, 4),
(59, 5),
(59, 6),
(59, 7),
(59, 8),
(59, 10),
(60, 1),
(60, 4),
(60, 5),
(60, 6),
(60, 7),
(60, 8),
(60, 9),
(60, 10),
(61, 1),
(61, 4),
(61, 5),
(61, 6),
(61, 7),
(61, 8),
(61, 9),
(61, 10),
(62, 1),
(62, 4),
(62, 5),
(62, 6),
(62, 7),
(62, 8),
(62, 9),
(62, 10),
(63, 1),
(63, 4),
(63, 5),
(63, 6),
(63, 7),
(63, 8),
(63, 9),
(63, 10),
(64, 1),
(64, 4),
(64, 5),
(64, 6),
(64, 7),
(64, 8),
(64, 9),
(64, 10),
(65, 1),
(65, 4),
(65, 5),
(65, 6),
(65, 7),
(65, 8),
(65, 9),
(65, 10),
(66, 1),
(66, 4),
(66, 5),
(66, 6),
(66, 7),
(66, 8),
(66, 9),
(66, 10),
(67, 1),
(67, 4),
(67, 5),
(67, 6),
(67, 7),
(67, 8),
(67, 9),
(67, 10),
(68, 1),
(68, 4),
(68, 5),
(68, 6),
(68, 7),
(68, 8),
(68, 9),
(68, 10),
(69, 1),
(69, 4),
(69, 5),
(69, 6),
(69, 7),
(69, 8),
(69, 9),
(69, 10),
(70, 1),
(70, 4),
(70, 5),
(70, 6),
(70, 7),
(70, 8),
(70, 9),
(70, 10),
(71, 1),
(71, 4),
(71, 5),
(71, 6),
(71, 7),
(71, 8),
(71, 9),
(71, 10),
(72, 1),
(72, 4),
(72, 5),
(72, 6),
(72, 7),
(72, 8),
(72, 9),
(72, 10),
(73, 1),
(73, 4),
(73, 5),
(73, 6),
(73, 7),
(73, 8),
(73, 9),
(73, 10),
(74, 1),
(74, 4),
(74, 5),
(74, 6),
(74, 7),
(74, 8),
(74, 9),
(74, 10),
(75, 1),
(75, 6),
(75, 9),
(75, 10),
(76, 1),
(76, 4),
(76, 5),
(76, 6),
(76, 8),
(76, 9),
(76, 10),
(78, 1),
(78, 4),
(78, 5),
(78, 6),
(78, 8),
(78, 10),
(80, 1),
(80, 4),
(80, 5),
(80, 6),
(80, 7),
(80, 8),
(80, 9),
(80, 10),
(81, 1),
(81, 5),
(81, 6),
(81, 10),
(82, 1),
(82, 5),
(82, 6),
(82, 10),
(83, 1),
(83, 5),
(83, 6),
(83, 10),
(84, 1),
(84, 5),
(84, 6),
(84, 10),
(85, 1),
(85, 5),
(85, 6),
(85, 10),
(86, 1),
(86, 5),
(86, 6),
(86, 10),
(87, 1),
(87, 10),
(88, 1),
(88, 5),
(88, 6),
(88, 10),
(89, 1),
(89, 5),
(89, 6),
(89, 10),
(90, 1),
(90, 5),
(90, 6),
(90, 10),
(91, 1),
(91, 5),
(91, 6),
(91, 10),
(92, 1),
(92, 10),
(93, 1),
(93, 5),
(93, 6),
(93, 10),
(94, 1),
(94, 5),
(94, 6),
(94, 10),
(95, 1),
(95, 5),
(95, 6),
(95, 10),
(96, 1),
(96, 5),
(96, 6),
(96, 10),
(97, 1),
(97, 10),
(98, 1),
(98, 5),
(98, 6),
(98, 10),
(99, 1),
(99, 5),
(99, 6),
(99, 10),
(100, 1),
(100, 5),
(100, 6),
(100, 10),
(101, 1),
(101, 5),
(101, 6),
(101, 10),
(102, 1),
(102, 10),
(103, 1),
(103, 5),
(103, 6),
(103, 10),
(104, 1),
(104, 5),
(104, 6),
(104, 10),
(105, 1),
(105, 5),
(105, 6),
(105, 10),
(106, 1),
(106, 5),
(106, 6),
(106, 10),
(107, 1),
(107, 10),
(108, 1),
(108, 5),
(108, 6),
(108, 8),
(108, 10),
(109, 1),
(109, 5),
(109, 6),
(109, 8),
(109, 10),
(110, 1),
(110, 5),
(110, 6),
(110, 8),
(110, 10),
(111, 1),
(111, 5),
(111, 6),
(111, 10),
(112, 1),
(112, 10),
(113, 1),
(113, 5),
(113, 6),
(113, 10),
(114, 1),
(114, 5),
(114, 6),
(114, 10),
(115, 1),
(115, 5),
(115, 6),
(115, 10),
(116, 1),
(116, 5),
(116, 6),
(116, 10),
(117, 1),
(117, 10),
(118, 1),
(118, 5),
(118, 6),
(118, 10),
(119, 1),
(119, 5),
(119, 6),
(119, 10),
(120, 1),
(120, 5),
(120, 6),
(120, 10),
(121, 1),
(121, 5),
(121, 6),
(121, 10),
(122, 1),
(122, 10),
(123, 1),
(123, 5),
(123, 6),
(123, 10),
(124, 1),
(124, 5),
(124, 6),
(124, 10),
(125, 1),
(125, 5),
(125, 6),
(125, 10),
(126, 1),
(126, 5),
(126, 6),
(126, 10),
(127, 1),
(127, 10),
(128, 1),
(128, 5),
(128, 6),
(128, 10),
(129, 1),
(129, 5),
(129, 6),
(129, 10),
(130, 1),
(130, 5),
(130, 6),
(130, 10),
(131, 1),
(131, 5),
(131, 6),
(131, 10),
(132, 1),
(132, 10),
(133, 1),
(133, 5),
(133, 6),
(133, 10),
(134, 1),
(134, 5),
(134, 6),
(134, 10),
(135, 1),
(135, 5),
(135, 6),
(135, 10),
(136, 1),
(136, 5),
(136, 6),
(136, 10),
(137, 1),
(137, 10),
(138, 1),
(138, 5),
(138, 6),
(138, 10),
(139, 1),
(139, 5),
(139, 6),
(139, 10),
(140, 1),
(140, 5),
(140, 6),
(140, 10),
(141, 1),
(141, 5),
(141, 6),
(141, 10),
(142, 1),
(142, 10),
(143, 10),
(144, 10),
(145, 1),
(145, 5),
(145, 6),
(145, 10),
(146, 1),
(146, 5),
(146, 6),
(146, 10),
(147, 10),
(148, 1),
(148, 5),
(148, 6),
(148, 10),
(149, 1),
(149, 5),
(149, 6),
(149, 10),
(150, 1),
(150, 5),
(150, 6),
(150, 10),
(151, 1),
(151, 5),
(151, 6),
(151, 10),
(152, 1),
(152, 10),
(153, 1),
(153, 4),
(153, 5),
(153, 6),
(153, 8),
(153, 9),
(153, 10),
(154, 1),
(154, 4),
(154, 5),
(154, 6),
(154, 8),
(154, 9),
(154, 10),
(155, 1),
(155, 4),
(155, 5),
(155, 6),
(155, 8),
(155, 9),
(155, 10),
(156, 1),
(156, 4),
(156, 5),
(156, 6),
(156, 8),
(156, 9),
(156, 10),
(157, 1),
(157, 10),
(158, 1),
(158, 5),
(158, 6),
(158, 10),
(159, 1),
(159, 5),
(159, 6),
(159, 10),
(160, 1),
(160, 5),
(160, 6),
(160, 10),
(161, 1),
(161, 5),
(161, 6),
(161, 10),
(162, 1),
(162, 10),
(163, 1),
(163, 5),
(163, 6),
(163, 10),
(164, 1),
(164, 5),
(164, 6),
(164, 10),
(165, 1),
(165, 5),
(165, 6),
(165, 10),
(166, 1),
(166, 5),
(166, 6),
(166, 10),
(167, 1),
(167, 10),
(168, 1),
(168, 5),
(168, 6),
(168, 10),
(169, 1),
(169, 5),
(169, 6),
(169, 10),
(170, 1),
(170, 5),
(170, 6),
(170, 10),
(171, 1),
(171, 5),
(171, 6),
(171, 10),
(172, 1),
(172, 5),
(172, 6),
(172, 10),
(173, 1),
(173, 10),
(174, 1),
(174, 5),
(174, 6),
(174, 10),
(175, 1),
(175, 5),
(175, 6),
(175, 8),
(175, 10),
(176, 1),
(176, 5),
(176, 6),
(176, 8),
(176, 10),
(177, 1),
(177, 5),
(177, 6),
(177, 10),
(178, 1),
(178, 5),
(178, 6),
(178, 10),
(179, 1),
(179, 10),
(180, 1),
(180, 6),
(180, 10),
(181, 1),
(181, 6),
(181, 10),
(182, 10),
(183, 10),
(184, 10),
(185, 1),
(185, 6),
(185, 10),
(186, 1),
(186, 6),
(186, 10),
(187, 10),
(188, 1),
(188, 6),
(188, 10),
(189, 10),
(190, 10),
(191, 1),
(191, 6),
(191, 10),
(192, 1),
(192, 6),
(192, 10),
(193, 1),
(193, 6),
(193, 10),
(194, 1),
(194, 6),
(194, 10),
(195, 1),
(195, 10),
(196, 10),
(197, 10),
(198, 10),
(199, 10),
(200, 10),
(201, 10),
(202, 10),
(203, 10),
(204, 10),
(205, 10),
(206, 10),
(207, 10),
(208, 10),
(209, 10),
(210, 10),
(211, 1),
(211, 6),
(211, 10),
(212, 1),
(212, 6),
(212, 10),
(213, 1),
(213, 6),
(213, 10),
(214, 1),
(214, 6),
(214, 10),
(215, 1),
(215, 10),
(216, 10),
(217, 10),
(218, 10),
(219, 10),
(220, 10),
(221, 10),
(222, 10),
(223, 10),
(224, 10),
(225, 10),
(226, 1),
(226, 6),
(226, 10),
(227, 1),
(227, 4),
(227, 5),
(227, 6),
(227, 8),
(227, 9),
(227, 10),
(228, 1),
(228, 4),
(228, 5),
(228, 6),
(228, 8),
(228, 9),
(228, 10),
(229, 1),
(229, 4),
(229, 5),
(229, 6),
(229, 8),
(229, 9),
(229, 10),
(230, 1),
(230, 4),
(230, 5),
(230, 6),
(230, 8),
(230, 9),
(230, 10),
(231, 1),
(231, 10),
(232, 1),
(232, 4),
(232, 5),
(232, 6),
(232, 8),
(232, 9),
(232, 10),
(233, 1),
(233, 4),
(233, 5),
(233, 6),
(233, 8),
(233, 9),
(233, 10),
(234, 1),
(234, 4),
(234, 5),
(234, 6),
(234, 8),
(234, 9),
(234, 10),
(235, 1),
(235, 5),
(235, 6),
(235, 8),
(235, 9),
(235, 10),
(236, 1),
(236, 5),
(236, 6),
(236, 8),
(236, 9),
(236, 10),
(237, 1),
(237, 10),
(238, 1),
(238, 4),
(238, 5),
(238, 6),
(238, 8),
(238, 9),
(238, 10),
(239, 1),
(239, 4),
(239, 5),
(239, 6),
(239, 8),
(239, 9),
(239, 10),
(240, 1),
(240, 5),
(240, 6),
(240, 8),
(240, 9),
(240, 10),
(241, 1),
(241, 5),
(241, 6),
(241, 8),
(241, 9),
(241, 10),
(242, 1),
(242, 10),
(243, 1),
(243, 4),
(243, 5),
(243, 6),
(243, 8),
(243, 9),
(243, 10),
(244, 1),
(244, 4),
(244, 5),
(244, 6),
(244, 8),
(244, 9),
(244, 10),
(245, 1),
(245, 5),
(245, 6),
(245, 8),
(245, 10),
(246, 1),
(246, 5),
(246, 6),
(246, 8),
(246, 10),
(247, 1),
(247, 10),
(248, 1),
(248, 5),
(248, 6),
(248, 8),
(248, 10),
(249, 1),
(249, 5),
(249, 6),
(249, 8),
(249, 10),
(250, 1),
(250, 5),
(250, 6),
(250, 10),
(251, 1),
(251, 5),
(251, 6),
(251, 10),
(252, 1),
(252, 10),
(253, 1),
(253, 5),
(253, 6),
(253, 8),
(253, 10),
(254, 1),
(254, 5),
(254, 6),
(254, 8),
(254, 10),
(255, 1),
(255, 5),
(255, 6),
(255, 10),
(256, 1),
(256, 5),
(256, 6),
(256, 10),
(257, 1),
(257, 10),
(258, 1),
(258, 5),
(258, 6),
(258, 7),
(258, 8),
(258, 10),
(259, 1),
(259, 5),
(259, 6),
(259, 7),
(259, 8),
(259, 10),
(260, 1),
(260, 5),
(260, 6),
(260, 8),
(260, 10),
(261, 1),
(261, 5),
(261, 6),
(261, 8),
(261, 10),
(262, 1),
(262, 10),
(263, 1),
(263, 5),
(263, 6),
(263, 7),
(263, 8),
(263, 10),
(264, 1),
(264, 5),
(264, 6),
(264, 8),
(264, 10),
(265, 1),
(265, 10),
(266, 1),
(266, 4),
(266, 5),
(266, 6),
(266, 7),
(266, 8),
(266, 10),
(267, 1),
(267, 4),
(267, 5),
(267, 6),
(267, 7),
(267, 8),
(267, 10),
(268, 1),
(268, 10),
(269, 1),
(269, 10),
(270, 1),
(270, 10),
(271, 1),
(271, 10),
(272, 1),
(272, 10),
(273, 1),
(273, 10),
(276, 1),
(276, 10),
(277, 1),
(277, 10),
(282, 1),
(282, 10),
(283, 1),
(283, 10),
(284, 1),
(284, 10),
(285, 1),
(285, 10),
(286, 1),
(286, 10),
(287, 1),
(287, 10),
(288, 1),
(288, 10),
(289, 1),
(289, 10),
(290, 1),
(290, 10),
(291, 1),
(291, 6),
(291, 10),
(292, 1),
(292, 6),
(292, 10),
(293, 1),
(293, 6),
(293, 10),
(294, 1),
(294, 6),
(294, 10),
(295, 1),
(295, 5),
(295, 10),
(296, 1),
(296, 6),
(296, 10),
(297, 1),
(297, 6),
(297, 10),
(298, 1),
(298, 6),
(298, 10),
(299, 1),
(299, 6),
(299, 10),
(300, 1),
(300, 6),
(300, 10),
(301, 1),
(301, 6),
(301, 10),
(302, 1),
(302, 6),
(302, 10),
(303, 1),
(303, 6),
(303, 10),
(304, 1),
(304, 6),
(304, 10),
(305, 1),
(305, 6),
(305, 10),
(306, 1),
(306, 10),
(307, 1),
(307, 6),
(307, 10),
(308, 1),
(308, 6),
(308, 10),
(309, 1),
(309, 6),
(309, 10),
(310, 1),
(310, 4),
(310, 6),
(310, 8),
(310, 10),
(311, 10),
(311, 11),
(312, 10),
(312, 11),
(313, 10),
(313, 11),
(314, 10),
(314, 11),
(315, 10),
(315, 11),
(316, 10),
(316, 11),
(317, 10),
(317, 11),
(318, 10),
(318, 11),
(319, 10),
(319, 11),
(320, 10),
(320, 11),
(321, 10),
(321, 11),
(322, 10),
(322, 11),
(323, 10),
(324, 10),
(325, 10),
(326, 10),
(327, 10),
(328, 1),
(328, 10),
(329, 1),
(329, 10),
(330, 1),
(330, 10),
(331, 1),
(331, 10),
(332, 1),
(332, 10),
(333, 1),
(333, 10),
(334, 1),
(334, 5),
(334, 8),
(334, 10),
(335, 1),
(335, 5),
(335, 8),
(335, 10),
(336, 1),
(336, 5),
(336, 8),
(336, 10),
(337, 1),
(337, 5),
(337, 8),
(337, 10),
(338, 1),
(338, 5),
(338, 8),
(338, 10),
(339, 1),
(339, 5),
(339, 8),
(339, 10),
(340, 1),
(340, 5),
(340, 8),
(340, 10),
(341, 1),
(341, 5),
(341, 8),
(341, 10),
(342, 1),
(342, 5),
(342, 8),
(342, 10),
(343, 1),
(343, 5),
(343, 8),
(343, 10),
(344, 1),
(344, 5),
(344, 8),
(344, 10),
(345, 1),
(345, 5),
(345, 8),
(345, 10),
(346, 1),
(346, 5),
(346, 8),
(346, 10),
(347, 1),
(347, 10),
(348, 1),
(348, 5),
(348, 8),
(348, 10),
(349, 1),
(349, 5),
(349, 8),
(349, 10),
(350, 1),
(350, 5),
(350, 8),
(350, 10),
(351, 1),
(351, 5),
(351, 8),
(351, 10),
(352, 1),
(352, 10),
(353, 1),
(353, 5),
(353, 8),
(353, 10),
(354, 1),
(354, 5),
(354, 8),
(354, 10),
(355, 1),
(355, 5),
(355, 8),
(355, 10),
(356, 1),
(356, 5),
(356, 8),
(356, 10),
(357, 1),
(357, 10),
(358, 1),
(358, 5),
(358, 8),
(358, 10),
(359, 1),
(359, 5),
(359, 8),
(359, 10),
(360, 1),
(360, 5),
(360, 8),
(360, 10),
(361, 1),
(361, 5),
(361, 8),
(361, 10),
(362, 1),
(362, 10),
(363, 1),
(363, 5),
(363, 8),
(363, 10),
(364, 1),
(364, 5),
(364, 8),
(364, 10),
(365, 1),
(365, 5),
(365, 8),
(365, 10),
(366, 1),
(366, 5),
(366, 8),
(366, 10),
(367, 1),
(367, 5),
(367, 8),
(367, 10),
(368, 1),
(368, 5),
(368, 8),
(368, 10),
(369, 1),
(369, 5),
(369, 8),
(369, 10),
(370, 1),
(370, 5),
(370, 8),
(370, 10),
(371, 1),
(371, 5),
(371, 8),
(371, 10),
(372, 1),
(372, 5),
(372, 8),
(372, 10),
(373, 1),
(373, 5),
(373, 8),
(373, 10),
(374, 1),
(374, 5),
(374, 8),
(374, 10),
(375, 1),
(375, 5),
(375, 8),
(375, 10),
(376, 1),
(376, 5),
(376, 8),
(376, 10),
(377, 1),
(377, 5),
(377, 8),
(377, 10),
(378, 1),
(378, 5),
(378, 8),
(378, 10),
(379, 1),
(379, 10),
(380, 1),
(380, 10),
(381, 1),
(381, 10),
(382, 10),
(383, 1),
(383, 10),
(384, 1),
(384, 10),
(385, 1),
(385, 10),
(386, 4),
(386, 10),
(387, 4),
(387, 10),
(388, 4),
(388, 10),
(389, 4),
(389, 10),
(390, 4),
(390, 10),
(391, 10),
(392, 1),
(392, 10),
(393, 10),
(394, 10),
(395, 10),
(396, 10),
(397, 10),
(398, 10),
(399, 10),
(400, 10),
(401, 10),
(402, 10),
(403, 10),
(404, 10),
(405, 1),
(405, 10),
(406, 10),
(407, 10),
(408, 10),
(409, 10),
(410, 10),
(411, 10),
(412, 10),
(413, 10),
(414, 10),
(415, 10),
(416, 10),
(417, 10),
(418, 10),
(419, 10),
(420, 10),
(421, 10),
(422, 10),
(423, 10),
(424, 10),
(425, 1),
(425, 10),
(426, 1),
(426, 10),
(427, 1),
(427, 10),
(428, 1),
(428, 10),
(429, 1),
(429, 10),
(430, 1),
(430, 10),
(431, 1),
(431, 10),
(432, 1),
(432, 10),
(433, 1),
(433, 10),
(434, 1),
(434, 10),
(435, 1),
(435, 10),
(436, 1),
(436, 10),
(437, 1),
(437, 10),
(438, 1),
(438, 10),
(439, 1),
(439, 10),
(440, 1),
(440, 10),
(441, 1),
(441, 10),
(442, 1),
(442, 10),
(443, 1),
(443, 10),
(450, 10),
(451, 10),
(452, 10),
(453, 10),
(454, 10),
(455, 10),
(457, 10),
(457, 12),
(458, 10),
(458, 12),
(459, 10),
(459, 12),
(460, 10),
(460, 12),
(461, 10),
(461, 12),
(462, 10),
(462, 12),
(463, 10),
(463, 12),
(464, 10),
(464, 12),
(465, 10),
(465, 12),
(466, 10),
(466, 12),
(467, 10),
(467, 12),
(475, 10),
(484, 10),
(484, 12),
(485, 1),
(485, 10),
(486, 1),
(486, 10),
(487, 10),
(488, 1),
(488, 10),
(489, 1),
(489, 10),
(490, 1),
(490, 10),
(491, 1),
(491, 10),
(492, 1),
(492, 10),
(493, 1),
(493, 10),
(494, 1),
(494, 10),
(495, 1),
(495, 10),
(496, 1),
(496, 10),
(497, 1),
(497, 10),
(498, 1),
(498, 10),
(499, 1),
(499, 10),
(500, 1),
(500, 10),
(501, 1),
(501, 10),
(502, 1),
(502, 10),
(503, 1),
(503, 10),
(504, 1),
(504, 10),
(505, 1),
(505, 10),
(506, 1),
(506, 10),
(507, 1),
(507, 10),
(508, 1),
(508, 10),
(509, 1),
(509, 10),
(510, 1),
(510, 10),
(511, 1),
(511, 10),
(512, 1),
(512, 10),
(513, 1),
(513, 10),
(514, 1),
(514, 10),
(515, 1),
(515, 10),
(516, 1),
(516, 10),
(517, 1),
(517, 10),
(518, 1),
(518, 10),
(519, 1),
(519, 10),
(520, 1),
(520, 10),
(521, 1),
(521, 10),
(522, 1),
(522, 10),
(523, 1),
(523, 10),
(524, 1),
(524, 10),
(525, 1),
(525, 10),
(526, 1),
(526, 10),
(527, 10),
(527, 12);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `product_type` varchar(255) NOT NULL DEFAULT 'Consumer',
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `invoice` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `sales_type` varchar(255) NOT NULL,
  `total_amount` decimal(16,2) NOT NULL,
  `discount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total_paid` decimal(16,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_lists`
--

CREATE TABLE `sales_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `product_type` varchar(255) NOT NULL DEFAULT 'Consumer',
  `sales_id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rate` decimal(16,2) NOT NULL,
  `qty` decimal(16,2) NOT NULL,
  `returned_qty` decimal(16,2) NOT NULL DEFAULT 0.00,
  `returned_amount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `amount` decimal(16,2) NOT NULL,
  `is_return` tinyint(4) NOT NULL DEFAULT 0,
  `delivery_status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_product_data`
--

CREATE TABLE `sales_product_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_id` bigint(20) UNSIGNED NOT NULL,
  `data` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_returns`
--

CREATE TABLE `sales_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `product_type` varchar(255) NOT NULL DEFAULT 'Consumer',
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `return_no` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `approve` tinyint(4) NOT NULL DEFAULT 0,
  `approve_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reject` tinyint(4) NOT NULL DEFAULT 0,
  `reject_by` bigint(20) UNSIGNED DEFAULT NULL,
  `accounts_approve` tinyint(4) NOT NULL DEFAULT 0,
  `accounts_approve_by` bigint(20) UNSIGNED DEFAULT NULL,
  `collection_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_return_lists`
--

CREATE TABLE `sales_return_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `product_type` varchar(255) NOT NULL DEFAULT 'Consumer',
  `sales_return_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `sales_list_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `store_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(16,2) NOT NULL,
  `qty` decimal(16,2) NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `sales_discount` decimal(16,2) NOT NULL DEFAULT 0.00,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `primary_mobile` varchar(255) DEFAULT NULL,
  `secondary_mobile` varchar(255) DEFAULT NULL,
  `primary_email` varchar(255) DEFAULT NULL,
  `secondary_email` varchar(255) DEFAULT NULL,
  `office_time` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keyword` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_image` text DEFAULT NULL,
  `google_map` text DEFAULT NULL,
  `favicon` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `footer_logo` varchar(255) DEFAULT NULL,
  `placeholder` varchar(255) DEFAULT NULL,
  `facebook_page` varchar(255) DEFAULT NULL,
  `facebook_group` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL,
  `google` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `pinterest` varchar(255) DEFAULT NULL,
  `banner_one` varchar(255) DEFAULT NULL,
  `banner_one_link` varchar(255) DEFAULT NULL,
  `banner_two` varchar(255) DEFAULT NULL,
  `banner_two_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `title`, `primary_mobile`, `secondary_mobile`, `primary_email`, `secondary_email`, `office_time`, `address`, `description`, `meta_title`, `meta_keyword`, `meta_description`, `meta_image`, `google_map`, `favicon`, `logo`, `footer_logo`, `placeholder`, `facebook_page`, `facebook_group`, `youtube`, `twitter`, `linkedin`, `google`, `whatsapp`, `instagram`, `pinterest`, `banner_one`, `banner_one_link`, `banner_two`, `banner_two_link`, `created_at`, `updated_at`) VALUES
(1, 'Prime Foods', '01916304877', NULL, 'info@primefoodsbd.com', 'sales@primefoodsbd.com', NULL, '৩১৬/২, ৬-বি, ডিআইটি রোড,  পূর্ব রামপুরা, ঢাকা-১২১৯।', NULL, NULL, NULL, NULL, 'media/default//2024-07-07-04fWmRNUOkTJ1QUXDjuqHPWSVIospkc5TsmocpYE.webp', NULL, 'media/default//2024-07-07-IcwjnpDc9sr1aS2KwlJ5DAcmDOmOnrQUdzH7tKou.webp', 'media/default//2024-07-07-uiBocPQ7DqHZnujzbod4zGpR5T5tlp2kxANJGega.webp', 'media/default//2024-07-07-5HgEcktsI84zgJS2RGiYJajMVu1phtAVBISIU3ux.webp', 'media/default//2024-07-07-Q6QV3YSEluu2WiaC4Mi8vq8uCkjNpBQ1vaOJAGQn.webp', 'https://facebook.com', NULL, 'https://www.youtube.com', 'https://twitter.com/', 'https://linkedin.com', 'https://www.google.com/', 'https://whatsapp.com', NULL, NULL, NULL, NULL, NULL, NULL, '2023-10-08 16:05:10', '2024-07-28 07:35:41');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_addresses`
--

CREATE TABLE `shipping_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `address_type` varchar(255) NOT NULL DEFAULT 'home',
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `division_id` bigint(20) UNSIGNED DEFAULT NULL,
  `district_id` bigint(20) UNSIGNED DEFAULT NULL,
  `upozila_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_addresses`
--

INSERT INTO `shipping_addresses` (`id`, `user_id`, `address_type`, `name`, `email`, `phone`, `street`, `address`, `division_id`, `district_id`, `upozila_id`, `created_at`, `updated_at`) VALUES
(1, 119, 'office', 'Mosharraf Hossain', 'mohsarrafhossain801@gmail.com', '01997316189', 'Muktagacha, Mymensingh', 'Muktagacha, Mymensingh, Kachua , Chandpur, Chittagong', 20, 36, 284, '2024-05-13 05:05:46', '2024-06-25 17:32:29'),
(2, 2, 'home', 'Fattah', NULL, '01916304877', 'Shantobag', 'Shantobag, Dhaka City Corporation Area, Dhaka, Dhaka', 13, 45, 372, '2024-05-13 07:05:03', '2024-05-13 07:57:46'),
(3, 120, 'home', 'Shahadat Hossain', 'shahadat@gmail.com', '01883240722', 'address', 'address, Damurhuda , Chuadanga, Khulna', 18, 63, 506, '2024-05-13 07:43:13', '2024-05-13 07:43:13'),
(4, 121, 'home', 'Nazmul Hasan', 'nazmul553@gmail.com', '01973112180', 'address', 'address, Kushtia Sadar , Kushtia, Khulna', 18, 67, 541, '2024-05-13 07:44:57', '2024-05-13 07:44:57'),
(5, 122, 'home', 'mamun', NULL, '01552344239', 'ssss', 'ssss, Daulatkhan , Bhola, Barisal', 19, 30, 241, '2024-05-13 07:58:55', '2024-05-13 07:58:55'),
(6, 123, 'home', 'mamunur arashid', NULL, '01917304877', 'aaaa', 'aaaa, Bagherpara , Jessore, Khulna', 18, 64, 509, '2024-05-13 11:42:42', '2024-05-13 11:42:42'),
(7, 124, 'home', 'xxx', NULL, '12345678', 'dddd', 'dddd, Rangunia , Chittagong, Chittagong', 20, 37, 297, '2024-05-13 12:27:13', '2024-05-13 12:27:13'),
(8, 132, 'home', 'Mosharraf Hossain', 'mohsarrafhossain801@gmail.com', '01997316189', 'Muktagacha, Mymensingh', 'Muktagacha, Mymensingh, Bakerganj , Barisal, Barisal', 19, 29, 230, '2024-07-25 15:36:33', '2024-07-25 15:36:33');

-- --------------------------------------------------------

--
-- Table structure for table `showcase_items`
--

CREATE TABLE `showcase_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `short_description` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `serial` bigint(20) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `showcase_items`
--

INSERT INTO `showcase_items` (`id`, `title`, `thumbnail`, `short_description`, `slug`, `serial`, `link`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Indoor / Domestic Cables', 'media/showcase_items//2024-05-13-gMxsfMlZpOefZOz6cTpPtckxDDN9sCTeaNq2wsjj.webp', '<p><span style=\"color: rgb(31, 31, 31); font-family: monospace; font-size: 12px; white-space-collapse: preserve;\">Indoor / Domestic Cables</span><br></p>', 'indoor-domestic-cables', 1, 'https://salestrackerbd.com/products/cables', 1, '2023-10-03 16:00:30', '2024-05-13 07:03:39'),
(8, 'Super Enamelled Coper Wire', 'media/showcase_items//2024-05-13-dvKdPTCfsgEbaJPcSQ87pKyf1rBUwNItdYJvNrRg.webp', '<p>Super Enamelled Coper Wire</p>', 'super-enamelled-coper-wire', 1, 'https://salestrackerbd.com/products/cables', 1, '2024-01-02 02:29:36', '2024-05-13 07:03:49'),
(9, 'Telecommunication Cable', 'media/showcase_items//2024-05-13-gWwfvYDuwIS03Tvr84BimNzBWopJ9L6hhrnOSW5F.webp', '<p><font color=\"#1f1f1f\" face=\"monospace\"><span style=\"font-size: 12px; white-space-collapse: preserve;\">Telecommunication Cable</span></font><br></p>', 'telecommunication-cable', 1, '#https://salestrackerbd.com/products/cables', 1, '2024-02-27 10:37:51', '2024-05-13 07:03:59');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `show_btn` tinyint(4) NOT NULL DEFAULT 1,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `heading`, `title`, `image`, `show_btn`, `status`, `created_at`, `updated_at`) VALUES
(4, NULL, NULL, 'media/slider//2024-07-07-a7A0jYpg6k14o0izWlWtDKCr2xcu9EzuVg8D8PNl.webp', 1, 1, '2023-12-30 18:33:26', '2024-07-07 11:39:12'),
(11, NULL, NULL, 'media/slider//2024-07-07-1yDdpBVwiT3VzQLMSBG4A3EOODfc0n4w13LoCpg4.webp', 1, 1, '2024-07-07 11:39:40', '2024-07-07 11:39:40');

-- --------------------------------------------------------

--
-- Table structure for table `social_works`
--

CREATE TABLE `social_works` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` text NOT NULL,
  `serial` bigint(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `special_food_items`
--

CREATE TABLE `special_food_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `serial` bigint(20) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `special_food_items`
--

INSERT INTO `special_food_items` (`id`, `name`, `image`, `serial`, `link`, `status`, `created_at`, `updated_at`) VALUES
(5, 'Super Enameled Copper Wire', 'media/special_food_items//2024-05-13-ycMJHUyJDINvf7PKEfJZ5zeOcWGk3hpaQTdNLLPS.webp', 1, 'https://salestrackerbd.com/products/cables', 1, '2024-02-27 10:24:49', '2024-05-13 06:29:51'),
(6, 'uPVC Pipe & Fittings', 'media/special_food_items//2024-05-13-QGtmOPjxZktZXhjCIYZzctkxAe1i3TZWml97sgLK.webp', 1, 'https://salestrackerbd.com/products/fittings', 1, '2024-02-27 10:31:13', '2024-05-13 06:30:53'),
(7, 'MINIATURE CIRCUIT BREAKER', 'media/special_food_items//2024-05-13-tilLup1YEIEKliegZnUBdCMk3qfIwPPsnfnVUS4E.webp', 1, 'https://salestrackerbd.com/products/miniature-circuit-breaker', 1, '2024-02-27 10:31:44', '2024-05-13 06:31:24'),
(8, 'INDOOR DOMESTIC CABLES', 'media/special_food_items//2024-05-13-cLxclOv6KE7bO55DBBZA0il0uqlXTZK6vCeQTD9e.webp', 1, 'https://salestrackerbd.com/products/cables', 1, '2024-02-27 10:32:15', '2024-05-13 06:30:17');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `national_id` varchar(255) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `ac_no` varchar(255) DEFAULT NULL,
  `ac_branch` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `company_id`, `branch_id`, `code`, `name`, `short_name`, `designation`, `phone`, `address`, `email`, `national_id`, `joining_date`, `ac_no`, `ac_branch`, `type`, `status`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(52, 1, 1, '10013', 'Office Sale', 'Office Sale', NULL, '017', NULL, NULL, NULL, '2024-02-03', NULL, NULL, 'sales', 1, 2, NULL, NULL, NULL, '2024-02-03 11:09:05', '2024-02-03 11:09:05');

-- --------------------------------------------------------

--
-- Table structure for table `static_site_items`
--

CREATE TABLE `static_site_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_description` text NOT NULL,
  `banner_title` varchar(255) NOT NULL,
  `testimonial_title` varchar(255) NOT NULL,
  `details_video_url` varchar(255) DEFAULT NULL,
  `products_title` varchar(255) NOT NULL,
  `header_bg_image` varchar(255) NOT NULL,
  `banner_image` varchar(255) NOT NULL,
  `welcome_image` varchar(255) NOT NULL,
  `testimonial_image` varchar(255) NOT NULL,
  `x_separator_image` varchar(255) NOT NULL,
  `y_separator_image` varchar(255) NOT NULL,
  `shop_button_link` varchar(255) DEFAULT NULL,
  `contact_button_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `static_site_items`
--

INSERT INTO `static_site_items` (`id`, `title`, `short_description`, `banner_title`, `testimonial_title`, `details_video_url`, `products_title`, `header_bg_image`, `banner_image`, `welcome_image`, `testimonial_image`, `x_separator_image`, `y_separator_image`, `shop_button_link`, `contact_button_link`, `created_at`, `updated_at`) VALUES
(1, 'WELCOME TO BRB CABLES LIMITED', 'You are in the right place where you will find what you need for your restaurant at the best prices. The freshest products with the highest quality and excellent service. Explore our product collection and discover for yourself.', 'SHOP WITH US, RESTOCK YOUR INVENTORY QUICK & EASY.', 'ALWAYS, CLOSE TO YOU.', NULL, 'OUR PRODUCTS', 'media/site-items//2024-05-13-EzZzrErnc9GDJQIJXYQvsyE2VD4Epcdtk6NmWZNS.webp', 'media/site-items/2023-10-03-Ks3KztP2FkaALesdqsMBEf2YhsUoMmub42ehW07T.webp', 'media/site-items//2024-05-13-2nfk568XtmNOoWYq2AmAr7GsATwRG7Tv2yCSvfMd.webp', 'media/site-items//2024-05-13-lYUM0NgpW4qKHQXzX60OuajvQRNXfu5CkTqGWOnQ.webp', 'media/site-items/2023-10-03-8OfkG7MbLfPEphOtkQhn94iNtXQABRDrYLYiC13B.webp', 'media/site-items/2023-10-03-UqkuKHIPv4Q4l5ILV0Wc2kVRzpx8oPwRwYvJ1cF6.webp', 'http://salestrackerbd.com/collections', 'http://salestrackerbd.com/contact', '2023-10-03 10:59:56', '2024-05-13 06:10:25');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `company_id`, `branch_id`, `code`, `type`, `name`, `address`, `remarks`, `status`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 'BES-2', 'Product Stock', 'Dhaka Store', 'Dhaka', 'Dry Store', 1, 1, 2, NULL, NULL, '2023-10-24 09:26:05', '2024-05-12 16:04:01'),
(6, 1, 2, 'BRBC', 'Product Stock', 'Chittagong Store', 'Chittagong', NULL, 1, 2, 2, NULL, NULL, '2023-11-07 07:50:49', '2024-05-12 16:04:39'),
(8, 1, 1, 'DST', 'Product Stock', 'Bread Pit', 'Tejgaon', 'Bakery Store', 1, 67, NULL, 2, '2024-05-12 16:03:26', '2023-12-30 05:57:43', '2024-05-12 16:03:26'),
(9, 1, 2, 'BRBM', 'Product Stock,Damage Stock', 'Mymensingh Store', 'Mymensingh', 'remarks', 1, 2, NULL, NULL, NULL, '2024-05-12 16:05:34', '2024-05-12 16:05:34');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `territories`
--

CREATE TABLE `territories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `area_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `incharge_name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `territories`
--

INSERT INTO `territories` (`id`, `company_id`, `area_id`, `code`, `name`, `incharge_name`, `phone`, `email`, `address`, `status`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(48, 1, 16, 'D-11', 'Mirpur', 'Md. Deluar Hussain', NULL, NULL, NULL, 1, 2, 2, NULL, NULL, '2023-12-24 05:02:42', '2023-12-24 05:12:58'),
(49, 1, 16, 'D-12', 'Uttara', NULL, NULL, NULL, NULL, 1, 2, 2, NULL, NULL, '2023-12-24 05:03:16', '2023-12-24 05:14:06'),
(50, 1, 15, 'D-13', 'Khilgaon', 'Md. Ibrahim', NULL, NULL, NULL, 1, 2, 2, NULL, NULL, '2023-12-24 05:04:01', '2023-12-24 05:13:11'),
(51, 1, 16, 'D-14', 'Gulshan', NULL, NULL, NULL, NULL, 1, 2, 2, NULL, NULL, '2023-12-24 05:05:27', '2023-12-24 05:13:23'),
(52, 1, 15, 'D-15', 'Dhanmondi', NULL, NULL, NULL, NULL, 1, 2, 2, NULL, NULL, '2023-12-24 05:05:52', '2023-12-24 05:13:36'),
(53, 1, 15, 'D-16', 'Mohammadpur', NULL, NULL, NULL, NULL, 1, 2, 2, NULL, NULL, '2023-12-24 05:06:42', '2023-12-24 05:12:22'),
(54, 1, 16, 'D-17', 'Vatara', NULL, NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, '2023-12-24 05:11:52', '2023-12-24 05:11:52'),
(55, 1, 15, 'D-18', 'Old Dhaka', NULL, NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, '2023-12-24 05:12:11', '2023-12-24 05:12:11'),
(56, 1, 16, '1208', 'Tejgaon', NULL, NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, '2024-02-03 11:49:32', '2024-02-03 11:49:32'),
(57, 1, 17, 'PF', 'Territory One', NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2024-07-13 12:44:50', '2024-07-13 12:44:50');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumbnail` text NOT NULL,
  `short_description` text NOT NULL,
  `slug` varchar(255) NOT NULL,
  `serial` bigint(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `title`, `thumbnail`, `short_description`, `slug`, `serial`, `status`, `created_at`, `updated_at`) VALUES
(1, 'QUALITY', 'media/testimonials/2023-10-04-Ku233uiWdHkItYEOM4K8wJinlnskfpbvwWxv0j5h.webp', '<p><span style=\"color: rgb(51, 65, 85); font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: medium;\">We deliver the best. All our clients have the security and confidence that we deliver high-quality fresh products.</span><br></p>', 'quality', 1, 1, '2023-10-04 02:35:30', '2023-10-04 02:35:30'),
(2, 'ETHIC', 'media/testimonials/2023-10-04-WbHWwdQfvA57P6kGBP9LigHOKSRYaOg1eFDksdjw.webp', '<p><span style=\"color: rgb(51, 65, 85); font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: medium;\">We deliver the best. All our clients have the security and confidence that we deliver high-quality fresh products.</span><br></p>', 'ethic', 2, 1, '2023-10-04 02:37:06', '2023-10-04 02:37:06'),
(3, 'TRUST', 'media/testimonials/2023-10-04-ypygqZgp2UAWAlBfhNDJVHBlIEPcw3mrZCEfO3RD.webp', '<p><span style=\"color: rgb(51, 65, 85); font-family: Figtree, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, &quot;Noto Sans&quot;, sans-serif, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: medium;\">We deliver the best. All our clients have the security and confidence that we deliver high-quality fresh products.</span><br></p>', 'trust', 3, 1, '2023-10-04 02:38:08', '2023-10-04 02:38:08');

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `product_type` varchar(255) NOT NULL DEFAULT 'Consumer',
  `transfer_no` varchar(255) NOT NULL,
  `date` date DEFAULT NULL,
  `host_id` bigint(20) UNSIGNED NOT NULL,
  `destination_id` bigint(20) UNSIGNED NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `approve` tinyint(4) NOT NULL DEFAULT 0,
  `approve_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reject` tinyint(4) NOT NULL DEFAULT 0,
  `reject_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_products`
--

CREATE TABLE `transfer_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `product_type` varchar(255) NOT NULL DEFAULT 'Consumer',
  `transfer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `qty` decimal(16,2) NOT NULL,
  `is_back` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 0,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `area_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `branch_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `store_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `is_staff` tinyint(4) NOT NULL DEFAULT 0,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `otp` int(10) DEFAULT NULL,
  `otp_expire` datetime NOT NULL DEFAULT current_timestamp(),
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `company_id`, `name`, `user_name`, `email`, `phone`, `address`, `image`, `cover_image`, `area_id`, `branch_id`, `store_id`, `status`, `is_staff`, `staff_id`, `email_verified_at`, `otp`, `otp_expire`, `password`, `remember_token`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Admin', 'admin', 'admin@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, '2023-11-24 11:04:22', '$2y$10$u4DYPnT9DrDMicRbTOBvU.7yaUhEal3jXqSZjpLrZnt.uHc4k/5yO', NULL, NULL, 1, NULL, NULL, '2023-10-24 06:03:09', '2023-11-09 04:12:25'),
(2, 1, 1, 'Prime Foods', 'primefoods', 'info@primefoods.com', '01916304878', NULL, 'backend/images/avatar/profile-sczbWzfVl8xylDElQ7H7JbXPaLgZPkcnmjtWLb3t.jpg', 'backend/images/avatar/cover-ql6TwYK8sVoYKDv4sndwNL1QnqdeAtPNmeOonbyX.jpg', NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, '2023-11-24 11:04:22', '$2y$10$l3fTCYCPb32YoVMTZz4fcOoW8nSgJJ9Ybe2JY.v2D5uwD7X8ccSFK', NULL, 1, 1, NULL, NULL, '2023-09-23 06:52:33', '2024-07-03 08:44:54'),
(133, 3, 1, 'MD. Hasan', '01824772535', 'mdhasan@gmail.com', '01824772535', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, '2024-07-27 15:24:57', '$2y$10$NOemO3S3KLI4oxfcRdOVB.VmYQUgjNdtkeUJcvjJvCBWe/P0xGmX6', NULL, 2, 2, NULL, NULL, '2024-07-27 09:24:57', '2024-07-30 14:14:40'),
(134, 3, 1, 'Sazidul Islam Sumon', '01829755459', 'sazidulislamsumon@gmail.com', '01829755459', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, '2024-07-28 13:44:02', '$2y$10$8fOiHvRw9p03saCXPb5ycO.nc.T3yjPRKq/rc83i1IARQEFXpHjIK', NULL, 2, 2, NULL, NULL, '2024-07-28 07:44:02', '2024-07-30 14:14:20'),
(135, 3, 1, 'Dr. Emran', '01788777848', 'dr.emran@gmail.com', '01788777848', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, '2024-07-30 20:10:39', '$2y$10$Sqwjg7zm3k0b5xbyQ9zjUOS/0AqexlhwqeutdwOb76E0.SALkJWW2', NULL, 2, 2, NULL, NULL, '2024-07-30 14:10:39', '2024-07-30 14:14:00'),
(136, 3, 1, 'Fattah', '01916304877', 'alfattah@gmail.com', '01916304877', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, '2024-07-30 20:10:59', '$2y$10$XQ4oVkD6ZDidVRTKgUJuIOh9KpSrDU807qZb0mU7VMrZcYpO/7b3S', NULL, 2, 2, NULL, NULL, '2024-07-30 14:10:59', '2024-07-30 14:13:33'),
(137, 3, 1, 'Korban', '01722353089', 'korban@gmail.com', '01722353089', NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, '2024-07-30 20:11:23', '$2y$10$gYgyNpQ/k/kURPgbkR5t1.fcyz5zpRy.c3xko9Qm7DDL.fzgBl5SW', NULL, 2, 2, NULL, NULL, '2024-07-30 14:11:23', '2024-07-30 14:13:15');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `registration_no` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `company_id`, `type`, `registration_no`, `status`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(11, 1, 'Frozen Pickup', 'Dhaka Metro Sha 11-1969', 1, 2, NULL, NULL, NULL, '2024-06-08 08:59:24', '2024-06-08 08:59:24'),
(12, 1, 'Frozen Pickup', 'Dhaka Metro Sha 11-3080', 1, 2, NULL, NULL, NULL, '2024-06-08 08:59:39', '2024-06-08 08:59:39');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `coa_setup_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `company_id`, `coa_setup_id`, `code`, `name`, `contact_person`, `email`, `phone`, `address`, `status`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 31, 'PF', 'Prime Foods', 'Mr. Maksudur Rahman', 'primefoods@gmail.com', '01844000104', 'House # 10/B Road No 6, Dhaka 1205', 1, 1, 2, 1, NULL, '2023-09-25 05:24:30', '2024-07-04 10:17:49');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments`
--

CREATE TABLE `vendor_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `lifting_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_no` varchar(255) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_type` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `amount` decimal(16,2) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT 0,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payment_data`
--

CREATE TABLE `vendor_payment_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_payment_id` bigint(20) UNSIGNED NOT NULL,
  `lifting_id` bigint(20) UNSIGNED NOT NULL,
  `paid` decimal(16,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_client_collections`
-- (See below for the actual view)
--
CREATE TABLE `view_client_collections` (
`client_id` bigint(20) unsigned
,`payment_date` date
,`collection_type` varchar(255)
,`amount` decimal(16,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_client_returns`
-- (See below for the actual view)
--
CREATE TABLE `view_client_returns` (
`company_id` bigint(20) unsigned
,`client_id` bigint(20) unsigned
,`client_category_id` bigint(20) unsigned
,`client_category_name` varchar(255)
,`date` date
,`amount` decimal(17,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_client_sales`
-- (See below for the actual view)
--
CREATE TABLE `view_client_sales` (
`company_id` bigint(20) unsigned
,`client_id` bigint(20) unsigned
,`client_category_id` bigint(20) unsigned
,`client_category_name` varchar(255)
,`client_name` varchar(255)
,`client_phone` varchar(255)
,`client_code` bigint(20)
,`region_id` bigint(20) unsigned
,`region_name` varchar(255)
,`area_id` bigint(20) unsigned
,`area_name` varchar(255)
,`territory_id` bigint(20) unsigned
,`territory_name` varchar(255)
,`date` date
,`amount` decimal(40,2)
,`total_paid` decimal(16,2)
,`staff_id` bigint(20) unsigned
,`staff_name` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_collectionable_sales`
-- (See below for the actual view)
--
CREATE TABLE `view_collectionable_sales` (
`id` bigint(20) unsigned
,`company_id` bigint(20) unsigned
,`store_id` bigint(20) unsigned
,`client_id` bigint(20) unsigned
,`client_name` varchar(255)
,`invoice` varchar(255)
,`date` date
,`total_amount` decimal(16,2)
,`total_paid` decimal(16,2)
,`discount` decimal(16,2)
,`returned_amount` decimal(38,2)
,`collectionable_amount` decimal(41,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_collection_history`
-- (See below for the actual view)
--
CREATE TABLE `view_collection_history` (
`company_id` bigint(20) unsigned
,`collection_type` varchar(255)
,`payment_no` varchar(255)
,`payment_type` varchar(255)
,`amount` decimal(16,2)
,`date` date
,`client_id` bigint(20) unsigned
,`client_name` varchar(255)
,`client_category_name` varchar(255)
,`region_id` bigint(20) unsigned
,`area_id` bigint(20) unsigned
,`area_name` varchar(255)
,`territory_id` bigint(20) unsigned
,`territory_name` varchar(255)
,`staff_id` bigint(20) unsigned
,`staff_name` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_fashion_product_liftings`
-- (See below for the actual view)
--
CREATE TABLE `view_fashion_product_liftings` (
`company_id` bigint(20) unsigned
,`product_id` bigint(20) unsigned
,`sku_id` bigint(20) unsigned
,`sku` varchar(255)
,`sale_price` decimal(16,2)
,`name` varchar(255)
,`category_id` bigint(20) unsigned
,`category_name` varchar(255)
,`qty` decimal(16,2)
,`amount` decimal(17,2)
,`date` date
,`store_id` bigint(20) unsigned
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_fashion_product_lifting_returns`
-- (See below for the actual view)
--
CREATE TABLE `view_fashion_product_lifting_returns` (
`company_id` bigint(20) unsigned
,`product_id` bigint(20) unsigned
,`sku_id` bigint(20) unsigned
,`sku` varchar(255)
,`name` varchar(255)
,`category_id` bigint(20) unsigned
,`category_name` varchar(255)
,`qty` decimal(16,2)
,`amount` decimal(17,2)
,`date` date
,`store_id` bigint(20) unsigned
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_liftings`
-- (See below for the actual view)
--
CREATE TABLE `view_liftings` (
`company_id` bigint(20) unsigned
,`store_id` bigint(20) unsigned
,`date` date
,`product_type` varchar(255)
,`product_id` bigint(20) unsigned
,`name` varchar(255)
,`code` varchar(255)
,`shared_profit` decimal(16,2)
,`variant_id` bigint(20) unsigned
,`attribute_name` varchar(255)
,`category_id` bigint(20) unsigned
,`category_name` varchar(255)
,`sale_price` decimal(16,2)
,`sku_id` bigint(20) unsigned
,`sku` varchar(255)
,`variant_price` decimal(16,2)
,`qty` decimal(16,2)
,`amount` decimal(17,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_lifting_and_returns`
-- (See below for the actual view)
--
CREATE TABLE `view_lifting_and_returns` (
`id` bigint(20) unsigned
,`company_id` bigint(20) unsigned
,`lifting_no` varchar(255)
,`date` date
,`store_id` bigint(20) unsigned
,`vendor_id` bigint(20) unsigned
,`total_amount` decimal(38,2)
,`discount` decimal(38,2)
,`payable_amount` decimal(39,2)
,`return_amount` decimal(48,2)
,`total_paid` decimal(16,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_lifting_returns`
-- (See below for the actual view)
--
CREATE TABLE `view_lifting_returns` (
`company_id` bigint(20) unsigned
,`store_id` bigint(20) unsigned
,`date` date
,`product_type` varchar(255)
,`product_id` bigint(20) unsigned
,`sku_id` bigint(20) unsigned
,`sku` varchar(255)
,`qty` decimal(16,2)
,`amount` decimal(17,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_online_sales`
-- (See below for the actual view)
--
CREATE TABLE `view_online_sales` (
`company_id` bigint(20) unsigned
,`product_type` varchar(255)
,`product_id` bigint(20) unsigned
,`sku_id` bigint(20) unsigned
,`category_id` bigint(20) unsigned
,`name` varchar(255)
,`code` varchar(255)
,`qty` decimal(16,2)
,`amount` decimal(16,2)
,`date` date
,`store_id` bigint(20) unsigned
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_product_returns`
-- (See below for the actual view)
--
CREATE TABLE `view_product_returns` (
`company_id` bigint(20) unsigned
,`product_id` bigint(20) unsigned
,`category_id` bigint(20) unsigned
,`name` varchar(255)
,`code` varchar(255)
,`date` date
,`qty` decimal(16,2)
,`amount` decimal(17,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_product_sales`
-- (See below for the actual view)
--
CREATE TABLE `view_product_sales` (
`company_id` bigint(20) unsigned
,`product_id` bigint(20) unsigned
,`category_id` bigint(20) unsigned
,`category_name` varchar(255)
,`name` varchar(255)
,`code` varchar(255)
,`date` date
,`staff_id` bigint(20) unsigned
,`staff_name` varchar(255)
,`qty` decimal(16,2)
,`amount` decimal(17,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_sales`
-- (See below for the actual view)
--
CREATE TABLE `view_sales` (
`company_id` bigint(20) unsigned
,`store_id` bigint(20) unsigned
,`date` date
,`product_type` varchar(255)
,`sales_type` varchar(255)
,`product_id` bigint(20) unsigned
,`name` varchar(255)
,`category_id` bigint(20) unsigned
,`category_name` varchar(255)
,`sku_id` bigint(20) unsigned
,`sku` varchar(255)
,`qty` decimal(16,2)
,`amount` decimal(17,2)
,`returned_qty` decimal(16,2)
,`returned_amount` decimal(16,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_sales_history`
-- (See below for the actual view)
--
CREATE TABLE `view_sales_history` (
`company_id` bigint(20) unsigned
,`product_id` bigint(20) unsigned
,`sales_id` bigint(20) unsigned
,`product_name` varchar(255)
,`product_code` varchar(255)
,`category_id` bigint(20) unsigned
,`ctn_size` float
,`product_vendor_name` varchar(255)
,`category_name` varchar(255)
,`attribute_name` varchar(255)
,`rate` decimal(16,2)
,`qty` decimal(16,2)
,`amount` decimal(17,2)
,`store_id` bigint(20) unsigned
,`staff_id` bigint(20) unsigned
,`staff_name` varchar(255)
,`client_id` bigint(20) unsigned
,`client_name` varchar(255)
,`client_category_name` varchar(255)
,`region_id` bigint(20) unsigned
,`area_name` varchar(255)
,`area_id` bigint(20) unsigned
,`territory_id` bigint(20) unsigned
,`territory_name` varchar(255)
,`invoice` varchar(255)
,`date` date
,`sales_type` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_sales_returns`
-- (See below for the actual view)
--
CREATE TABLE `view_sales_returns` (
`company_id` bigint(20) unsigned
,`store_id` bigint(20) unsigned
,`date` date
,`product_type` varchar(255)
,`product_id` bigint(20) unsigned
,`name` varchar(255)
,`category_id` bigint(20) unsigned
,`category_name` varchar(255)
,`sku_id` bigint(20) unsigned
,`sku` varchar(255)
,`qty` decimal(16,2)
,`amount` decimal(17,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_transfers`
-- (See below for the actual view)
--
CREATE TABLE `view_transfers` (
`company_id` bigint(20) unsigned
,`host_id` bigint(20) unsigned
,`destination_id` bigint(20) unsigned
,`date` date
,`product_type` varchar(255)
,`product_id` bigint(20) unsigned
,`name` varchar(255)
,`category_id` bigint(20) unsigned
,`category_name` varchar(255)
,`sku_id` bigint(20) unsigned
,`sku` varchar(255)
,`qty` decimal(16,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_trial_balance`
-- (See below for the actual view)
--
CREATE TABLE `view_trial_balance` (
`voucher_type` varchar(20)
,`voucher_no` varchar(255)
,`voucher_date` date
,`coa_setup_id` bigint(20) unsigned
,`coa_head_code` bigint(20)
,`debit_amount` decimal(16,2)
,`credit_amount` decimal(16,2)
,`transaction` tinyint(4)
,`general` tinyint(4)
,`parent_id` bigint(20) unsigned
,`head_type` varchar(255)
,`head_name` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `investor_id` bigint(20) UNSIGNED NOT NULL,
  `invest_id` bigint(20) UNSIGNED DEFAULT NULL,
  `investor_profit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `investor_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sattlement_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` date DEFAULT NULL,
  `amount_in` decimal(16,2) NOT NULL DEFAULT 0.00,
  `amount_out` decimal(16,2) NOT NULL DEFAULT 0.00,
  `type` varchar(255) NOT NULL,
  `approved` tinyint(4) NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `investor_id`, `invest_id`, `investor_profit_id`, `investor_payment_id`, `sattlement_id`, `product_id`, `date`, `amount_in`, `amount_out`, `type`, `approved`, `created_by`, `updated_by`, `deleted_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(28, 1, 1, NULL, NULL, NULL, 150, '2024-07-01', 80000.00, 0.00, 'Invest', 1, 2, NULL, NULL, NULL, '2024-07-30 14:37:00', '2024-07-30 14:37:00'),
(29, 1, 2, NULL, NULL, NULL, 152, '2024-07-30', 80000.00, 0.00, 'Invest', 1, 2, NULL, NULL, NULL, '2024-07-30 14:37:44', '2024-07-30 14:37:44'),
(30, 2, 3, NULL, NULL, NULL, 161, '2024-07-01', 150000.00, 0.00, 'Invest', 1, 2, NULL, NULL, NULL, '2024-07-30 14:38:09', '2024-07-30 15:23:16'),
(31, 3, 4, NULL, NULL, NULL, 152, '2024-07-01', 80000.00, 0.00, 'Invest', 1, 2, NULL, NULL, NULL, '2024-07-30 14:39:58', '2024-07-30 15:23:11'),
(32, 3, 5, NULL, NULL, NULL, 157, '2024-07-01', 80000.00, 0.00, 'Invest', 1, 2, NULL, NULL, NULL, '2024-07-30 14:40:15', '2024-07-30 15:23:05'),
(33, 4, 6, NULL, NULL, NULL, 159, '2024-07-01', 50000.00, 0.00, 'Invest', 1, 2, NULL, NULL, NULL, '2024-07-30 14:40:36', '2024-07-30 15:23:01'),
(34, 5, 7, NULL, NULL, NULL, 162, '2024-07-01', 20000.00, 0.00, 'Invest', 1, 2, NULL, NULL, NULL, '2024-07-30 14:40:51', '2024-07-30 15:22:51'),
(35, 1, 8, NULL, NULL, NULL, 150, '2024-07-05', 80000.00, 0.00, 'Invest', 1, 2, NULL, NULL, NULL, '2024-07-31 05:50:44', '2024-07-31 05:51:41'),
(64, 3, NULL, 3, NULL, NULL, 152, '2024-07-31', 3210.00, 0.00, 'Profit', 1, 2, NULL, NULL, NULL, '2024-07-31 15:34:38', '2024-07-31 15:34:38'),
(65, 3, NULL, 3, NULL, NULL, 157, '2024-07-31', 6750.00, 0.00, 'Profit', 1, 2, NULL, NULL, NULL, '2024-07-31 15:34:38', '2024-07-31 15:34:38'),
(66, 4, NULL, 3, NULL, NULL, 159, '2024-07-31', 7425.00, 0.00, 'Profit', 1, 2, NULL, NULL, NULL, '2024-07-31 15:34:38', '2024-07-31 15:34:38'),
(67, 5, NULL, 3, NULL, NULL, 162, '2024-07-31', 4650.00, 0.00, 'Profit', 1, 2, NULL, NULL, NULL, '2024-07-31 15:34:38', '2024-07-31 15:34:38'),
(68, 1, NULL, 3, NULL, NULL, 150, '2024-07-31', 4320.00, 0.00, 'Profit', 1, 2, NULL, NULL, NULL, '2024-07-31 15:34:38', '2024-07-31 15:34:38'),
(69, 1, NULL, 3, NULL, NULL, 152, '2024-07-31', 3210.00, 0.00, 'Profit', 1, 2, NULL, NULL, NULL, '2024-07-31 15:34:38', '2024-07-31 15:34:38'),
(70, 2, NULL, 3, NULL, NULL, 161, '2024-07-31', 11070.00, 0.00, 'Profit', 1, 2, NULL, NULL, NULL, '2024-07-31 15:34:38', '2024-07-31 15:34:38'),
(71, 5, NULL, NULL, 3, NULL, NULL, '2024-07-31', 0.00, 4650.00, 'Payment', 1, 137, NULL, NULL, NULL, '2024-07-31 15:59:41', '2024-07-31 16:00:48'),
(72, 1, NULL, NULL, NULL, 3, NULL, '2024-07-31', 0.00, 240000.00, 'Sattlement', 1, 2, 2, NULL, NULL, '2024-07-31 16:03:11', '2024-07-31 16:04:50'),
(73, 1, NULL, NULL, 4, NULL, NULL, '2024-07-31', 0.00, 7530.00, 'Payment', 1, 133, NULL, NULL, NULL, '2024-07-31 16:13:04', '2024-07-31 16:13:30'),
(74, 4, NULL, NULL, 5, NULL, NULL, '2024-08-01', 0.00, 7000.00, 'Payment', 1, 136, NULL, NULL, NULL, '2024-08-01 05:12:44', '2024-08-01 05:15:27'),
(77, 4, NULL, NULL, 8, NULL, NULL, '2024-08-01', 0.00, 425.00, 'Payment', 1, 2, NULL, NULL, NULL, '2024-08-01 11:30:29', '2024-08-01 11:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 132, 161, '2024-07-25 15:36:51', '2024-07-25 15:36:51');

-- --------------------------------------------------------

--
-- Structure for view `view_client_collections`
--
DROP TABLE IF EXISTS `view_client_collections`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_client_collections`  AS SELECT `clients`.`id` AS `client_id`, `collections`.`payment_date` AS `payment_date`, `collections`.`collection_type` AS `collection_type`, `collections`.`amount` AS `amount` FROM (`clients` left join `collections` on(`clients`.`id` = `collections`.`client_id` and `collections`.`collection_type` <> 'adjust' and `collections`.`deleted_at` is null)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_client_returns`
--
DROP TABLE IF EXISTS `view_client_returns`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_client_returns`  AS SELECT `clients`.`company_id` AS `company_id`, `clients`.`id` AS `client_id`, `client_categories`.`id` AS `client_category_id`, `client_categories`.`name` AS `client_category_name`, `sales_returns`.`date` AS `date`, `sales_return_lists`.`amount`- `sales_return_lists`.`sales_discount` AS `amount` FROM (((`clients` left join `sales_returns` on(`clients`.`id` = `sales_returns`.`client_id` and `sales_returns`.`deleted_at` is null)) left join `client_categories` on(`client_categories`.`id` = `clients`.`client_category_id` and `client_categories`.`deleted_at` is null)) left join `sales_return_lists` on(`sales_returns`.`id` = `sales_return_lists`.`sales_return_id`)) WHERE `clients`.`deleted_at` is null ;

-- --------------------------------------------------------

--
-- Structure for view `view_client_sales`
--
DROP TABLE IF EXISTS `view_client_sales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_client_sales`  AS SELECT `sales_lists`.`company_id` AS `company_id`, `sales_lists`.`client_id` AS `client_id`, `client_categories`.`id` AS `client_category_id`, `client_categories`.`name` AS `client_category_name`, `clients`.`name` AS `client_name`, `clients`.`phone` AS `client_phone`, `clients`.`code` AS `client_code`, `regions`.`id` AS `region_id`, `regions`.`name` AS `region_name`, `areas`.`id` AS `area_id`, `areas`.`name` AS `area_name`, `territories`.`id` AS `territory_id`, `territories`.`name` AS `territory_name`, `sales`.`date` AS `date`, sum(`sales_lists`.`amount`) - sum(`sales_lists`.`discount`) - sum(`sales_lists`.`returned_amount`) AS `amount`, `sales`.`total_paid` AS `total_paid`, `staff`.`id` AS `staff_id`, `staff`.`name` AS `staff_name` FROM (((((((`sales_lists` left join `sales` on(`sales`.`id` = `sales_lists`.`sales_id`)) left join `clients` on(`clients`.`id` = `sales`.`client_id`)) left join `staff` on(`staff`.`id` = `sales`.`staff_id` and `staff`.`deleted_at` is null)) left join `client_categories` on(`client_categories`.`id` = `clients`.`client_category_id` and `client_categories`.`deleted_at` is null)) left join `areas` on(`areas`.`id` = `clients`.`area_id` and `areas`.`deleted_at` is null)) left join `regions` on(`regions`.`id` = `areas`.`region_id` and `regions`.`deleted_at` is null)) left join `territories` on(`territories`.`id` = `clients`.`territory_id` and `territories`.`deleted_at` is null)) WHERE `sales`.`deleted_at` is null AND `clients`.`deleted_at` is null GROUP BY `sales_lists`.`sales_id` ;

-- --------------------------------------------------------

--
-- Structure for view `view_collectionable_sales`
--
DROP TABLE IF EXISTS `view_collectionable_sales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_collectionable_sales`  AS SELECT `sales`.`id` AS `id`, `sales`.`company_id` AS `company_id`, `sales`.`store_id` AS `store_id`, `sales`.`client_id` AS `client_id`, `clients`.`name` AS `client_name`, `sales`.`invoice` AS `invoice`, `sales`.`date` AS `date`, `sales`.`total_amount` AS `total_amount`, `sales`.`total_paid` AS `total_paid`, `sales`.`discount` AS `discount`, sum(`sales_lists`.`returned_amount`) AS `returned_amount`, `sales`.`total_amount`- sum(`sales_lists`.`returned_amount`) - `sales`.`discount` - `sales`.`total_paid` AS `collectionable_amount` FROM ((`sales_lists` left join `clients` on(`clients`.`id` = `sales_lists`.`client_id`)) left join `sales` on(`sales`.`id` = `sales_lists`.`sales_id`)) WHERE `sales`.`deleted_at` is null GROUP BY `sales`.`id` ;

-- --------------------------------------------------------

--
-- Structure for view `view_collection_history`
--
DROP TABLE IF EXISTS `view_collection_history`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_collection_history`  AS SELECT `collections`.`company_id` AS `company_id`, `collections`.`collection_type` AS `collection_type`, `collections`.`payment_no` AS `payment_no`, `collections`.`payment_type` AS `payment_type`, `collections`.`amount` AS `amount`, `collections`.`payment_date` AS `date`, `collections`.`client_id` AS `client_id`, `clients`.`name` AS `client_name`, `client_categories`.`name` AS `client_category_name`, `areas`.`region_id` AS `region_id`, `clients`.`area_id` AS `area_id`, `areas`.`name` AS `area_name`, `clients`.`territory_id` AS `territory_id`, `territories`.`name` AS `territory_name`, `staff`.`id` AS `staff_id`, `staff`.`name` AS `staff_name` FROM (((((`collections` left join `clients` on(`clients`.`id` = `collections`.`client_id`)) left join `client_categories` on(`client_categories`.`id` = `clients`.`client_category_id`)) left join `areas` on(`areas`.`id` = `clients`.`area_id`)) left join `territories` on(`territories`.`id` = `clients`.`territory_id`)) left join `staff` on(`staff`.`id` = `collections`.`staff_id`)) WHERE `collections`.`deleted_at` is null ;

-- --------------------------------------------------------

--
-- Structure for view `view_fashion_product_liftings`
--
DROP TABLE IF EXISTS `view_fashion_product_liftings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_fashion_product_liftings`  AS SELECT `products`.`company_id` AS `company_id`, `products`.`id` AS `product_id`, `product_skus`.`id` AS `sku_id`, `product_skus`.`sku` AS `sku`, `product_skus`.`price` AS `sale_price`, `products`.`name` AS `name`, `categories`.`id` AS `category_id`, `categories`.`name` AS `category_name`, `lifting_products`.`qty` AS `qty`, `lifting_products`.`total_amount`- `lifting_products`.`discount` AS `amount`, `liftings`.`lifting_date` AS `date`, `lifting_products`.`store_id` AS `store_id` FROM ((((`product_skus` left join `products` on(`products`.`id` = `product_skus`.`product_id`)) left join `categories` on(`categories`.`id` = `products`.`category_id`)) left join `lifting_products` on(`lifting_products`.`variant_id` = `product_skus`.`id`)) left join `liftings` on(`liftings`.`id` = `lifting_products`.`lifting_id` and `liftings`.`deleted_at` is null)) WHERE `products`.`deleted_at` is null AND `products`.`status` = 1 AND `lifting_products`.`product_type` = 'Fashion' ;

-- --------------------------------------------------------

--
-- Structure for view `view_fashion_product_lifting_returns`
--
DROP TABLE IF EXISTS `view_fashion_product_lifting_returns`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_fashion_product_lifting_returns`  AS SELECT `products`.`company_id` AS `company_id`, `products`.`id` AS `product_id`, `product_skus`.`id` AS `sku_id`, `product_skus`.`sku` AS `sku`, `products`.`name` AS `name`, `categories`.`id` AS `category_id`, `categories`.`name` AS `category_name`, `lifting_return_lists`.`qty` AS `qty`, `lifting_return_lists`.`amount`- `lifting_return_lists`.`lifting_discount` AS `amount`, `lifting_returns`.`date` AS `date`, `lifting_products`.`store_id` AS `store_id` FROM (((((`lifting_return_lists` left join `product_skus` on(`product_skus`.`id` = `lifting_return_lists`.`variant_id`)) left join `products` on(`products`.`id` = `product_skus`.`product_id`)) left join `categories` on(`categories`.`id` = `products`.`category_id`)) left join `lifting_products` on(`lifting_products`.`id` = `lifting_return_lists`.`lifting_product_id`)) left join `lifting_returns` on(`lifting_returns`.`id` = `lifting_return_lists`.`lifting_return_id` and `lifting_returns`.`deleted_at` is null)) WHERE `products`.`deleted_at` is null AND `products`.`status` = 1 AND `lifting_return_lists`.`product_type` = 'Fashion' ;

-- --------------------------------------------------------

--
-- Structure for view `view_liftings`
--
DROP TABLE IF EXISTS `view_liftings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_liftings`  AS SELECT `liftings`.`company_id` AS `company_id`, `liftings`.`store_id` AS `store_id`, `liftings`.`lifting_date` AS `date`, `liftings`.`product_type` AS `product_type`, `lifting_products`.`product_id` AS `product_id`, `products`.`name` AS `name`, `products`.`code` AS `code`, `products`.`shared_profit` AS `shared_profit`, `lifting_products`.`variant_id` AS `variant_id`, `attributes`.`name` AS `attribute_name`, `categories`.`id` AS `category_id`, `categories`.`name` AS `category_name`, `product_prices`.`sale_price` AS `sale_price`, `product_skus`.`id` AS `sku_id`, `product_skus`.`sku` AS `sku`, `product_skus`.`price` AS `variant_price`, `lifting_products`.`qty` AS `qty`, `lifting_products`.`total_amount`- `lifting_products`.`discount` AS `amount` FROM ((((((`lifting_products` left join `products` on(`products`.`id` = `lifting_products`.`product_id`)) left join `product_prices` on(`product_prices`.`product_id` = `products`.`id`)) left join `attributes` on(`attributes`.`id` = `products`.`attribute_id`)) left join `categories` on(`categories`.`id` = `products`.`category_id`)) left join `product_skus` on(`product_skus`.`id` = `lifting_products`.`variant_id`)) left join `liftings` on(`liftings`.`id` = `lifting_products`.`lifting_id`)) WHERE `liftings`.`deleted_at` is null AND `products`.`deleted_at` is null AND `products`.`status` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `view_lifting_and_returns`
--
DROP TABLE IF EXISTS `view_lifting_and_returns`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_lifting_and_returns`  AS SELECT `liftings`.`id` AS `id`, `liftings`.`company_id` AS `company_id`, `liftings`.`lifting_no` AS `lifting_no`, `liftings`.`lifting_date` AS `date`, `liftings`.`store_id` AS `store_id`, `liftings`.`vendor_id` AS `vendor_id`, sum(`lifting_products`.`total_amount`) AS `total_amount`, sum(`lifting_products`.`discount`) AS `discount`, sum(`lifting_products`.`total_amount`) - sum(`lifting_products`.`discount`) AS `payable_amount`, round(sum((`lifting_products`.`total_amount` - `lifting_products`.`discount`) / `lifting_products`.`qty` * `lifting_products`.`return_qty`),2) AS `return_amount`, `liftings`.`total_paid` AS `total_paid` FROM (`lifting_products` left join `liftings` on(`liftings`.`id` = `lifting_products`.`lifting_id`)) WHERE `liftings`.`deleted_at` is null GROUP BY `lifting_products`.`lifting_id` ;

-- --------------------------------------------------------

--
-- Structure for view `view_lifting_returns`
--
DROP TABLE IF EXISTS `view_lifting_returns`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_lifting_returns`  AS SELECT `lifting_returns`.`company_id` AS `company_id`, `lifting_returns`.`store_id` AS `store_id`, `lifting_returns`.`date` AS `date`, `lifting_returns`.`product_type` AS `product_type`, `lifting_return_lists`.`product_id` AS `product_id`, `product_skus`.`id` AS `sku_id`, `product_skus`.`sku` AS `sku`, `lifting_return_lists`.`qty` AS `qty`, `lifting_return_lists`.`amount`- `lifting_return_lists`.`lifting_discount` AS `amount` FROM (((`lifting_return_lists` left join `products` on(`products`.`id` = `lifting_return_lists`.`product_id`)) left join `product_skus` on(`product_skus`.`id` = `lifting_return_lists`.`variant_id`)) left join `lifting_returns` on(`lifting_returns`.`id` = `lifting_return_lists`.`lifting_return_id`)) WHERE `lifting_returns`.`deleted_at` is null AND `products`.`deleted_at` is null AND `products`.`status` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `view_online_sales`
--
DROP TABLE IF EXISTS `view_online_sales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_online_sales`  AS SELECT `products`.`company_id` AS `company_id`, `products`.`product_type` AS `product_type`, `order_products`.`product_id` AS `product_id`, `order_products`.`variant_id` AS `sku_id`, `products`.`category_id` AS `category_id`, `products`.`name` AS `name`, `products`.`code` AS `code`, `order_products`.`quantity` AS `qty`, `order_products`.`subtotal` AS `amount`, `orders`.`date` AS `date`, `orders`.`store_id` AS `store_id` FROM ((`order_products` left join `products` on(`products`.`id` = `order_products`.`product_id`)) left join `orders` on(`orders`.`id` = `order_products`.`order_id`)) WHERE `order_products`.`delivered` = 1 AND `orders`.`deleted_at` is null AND `orders`.`order_type` = 'online' AND `products`.`deleted_at` is null AND `products`.`status` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `view_product_returns`
--
DROP TABLE IF EXISTS `view_product_returns`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_product_returns`  AS SELECT `products`.`company_id` AS `company_id`, `products`.`id` AS `product_id`, `products`.`category_id` AS `category_id`, `products`.`name` AS `name`, `products`.`code` AS `code`, `sales_returns`.`date` AS `date`, `sales_return_lists`.`qty` AS `qty`, `sales_return_lists`.`amount`- `sales_return_lists`.`sales_discount` AS `amount` FROM ((`products` left join `sales_return_lists` on(`products`.`id` = `sales_return_lists`.`product_id`)) left join `sales_returns` on(`sales_returns`.`id` = `sales_return_lists`.`sales_return_id` and `sales_returns`.`deleted_at` is null)) WHERE `products`.`deleted_at` is null AND `products`.`status` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `view_product_sales`
--
DROP TABLE IF EXISTS `view_product_sales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_product_sales`  AS SELECT `products`.`company_id` AS `company_id`, `products`.`id` AS `product_id`, `products`.`category_id` AS `category_id`, `categories`.`name` AS `category_name`, `products`.`name` AS `name`, `products`.`code` AS `code`, `sales`.`date` AS `date`, `staff`.`id` AS `staff_id`, `staff`.`name` AS `staff_name`, `sales_lists`.`qty` AS `qty`, `sales_lists`.`amount`- `sales_lists`.`discount` AS `amount` FROM ((((`products` left join `sales_lists` on(`products`.`id` = `sales_lists`.`product_id`)) left join `categories` on(`categories`.`id` = `products`.`category_id`)) left join `sales` on(`sales`.`id` = `sales_lists`.`sales_id` and `sales`.`deleted_at` is null)) left join `staff` on(`staff`.`id` = `sales`.`staff_id` and `staff`.`deleted_at` is null)) WHERE `products`.`deleted_at` is null AND `products`.`status` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `view_sales`
--
DROP TABLE IF EXISTS `view_sales`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_sales`  AS SELECT `sales`.`company_id` AS `company_id`, `sales`.`store_id` AS `store_id`, `sales`.`date` AS `date`, `sales`.`product_type` AS `product_type`, `sales`.`sales_type` AS `sales_type`, `sales_lists`.`product_id` AS `product_id`, `products`.`name` AS `name`, `categories`.`id` AS `category_id`, `categories`.`name` AS `category_name`, `product_skus`.`id` AS `sku_id`, `product_skus`.`sku` AS `sku`, `sales_lists`.`qty` AS `qty`, `sales_lists`.`amount`- `sales_lists`.`discount` AS `amount`, `sales_lists`.`returned_qty` AS `returned_qty`, `sales_lists`.`returned_amount` AS `returned_amount` FROM ((((`sales_lists` left join `products` on(`products`.`id` = `sales_lists`.`product_id`)) left join `categories` on(`categories`.`id` = `products`.`category_id`)) left join `product_skus` on(`product_skus`.`id` = `sales_lists`.`variant_id`)) left join `sales` on(`sales`.`id` = `sales_lists`.`sales_id`)) WHERE `sales`.`deleted_at` is null AND `products`.`deleted_at` is null AND `products`.`status` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `view_sales_history`
--
DROP TABLE IF EXISTS `view_sales_history`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_sales_history`  AS SELECT `sales_lists`.`company_id` AS `company_id`, `sales_lists`.`product_id` AS `product_id`, `sales_lists`.`sales_id` AS `sales_id`, `products`.`name` AS `product_name`, `products`.`code` AS `product_code`, `products`.`category_id` AS `category_id`, `products`.`ctn_size` AS `ctn_size`, `vendors`.`name` AS `product_vendor_name`, `categories`.`name` AS `category_name`, `attributes`.`name` AS `attribute_name`, `sales_lists`.`rate` AS `rate`, `sales_lists`.`qty` AS `qty`, `sales_lists`.`amount`- `sales_lists`.`discount` AS `amount`, `sales`.`store_id` AS `store_id`, `sales`.`staff_id` AS `staff_id`, `staff`.`name` AS `staff_name`, `sales`.`client_id` AS `client_id`, `clients`.`name` AS `client_name`, `client_categories`.`name` AS `client_category_name`, `areas`.`region_id` AS `region_id`, `areas`.`name` AS `area_name`, `clients`.`area_id` AS `area_id`, `clients`.`territory_id` AS `territory_id`, `territories`.`name` AS `territory_name`, `sales`.`invoice` AS `invoice`, `sales`.`date` AS `date`, `sales`.`sales_type` AS `sales_type` FROM ((((((((((`sales_lists` left join `sales` on(`sales`.`id` = `sales_lists`.`sales_id` and `sales`.`deleted_at` is null)) left join `staff` on(`staff`.`id` = `sales`.`staff_id`)) left join `clients` on(`clients`.`id` = `sales_lists`.`client_id`)) left join `areas` on(`areas`.`id` = `clients`.`area_id`)) left join `products` on(`products`.`id` = `sales_lists`.`product_id`)) left join `categories` on(`categories`.`id` = `products`.`category_id`)) left join `client_categories` on(`client_categories`.`id` = `clients`.`client_category_id`)) left join `attributes` on(`attributes`.`id` = `products`.`attribute_id`)) left join `vendors` on(`vendors`.`id` = `products`.`vendor_id`)) left join `territories` on(`territories`.`id` = `clients`.`territory_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_sales_returns`
--
DROP TABLE IF EXISTS `view_sales_returns`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_sales_returns`  AS SELECT `sales_returns`.`company_id` AS `company_id`, `sales_returns`.`store_id` AS `store_id`, `sales_returns`.`date` AS `date`, `sales_returns`.`product_type` AS `product_type`, `sales_return_lists`.`product_id` AS `product_id`, `products`.`name` AS `name`, `categories`.`id` AS `category_id`, `categories`.`name` AS `category_name`, `product_skus`.`id` AS `sku_id`, `product_skus`.`sku` AS `sku`, `sales_return_lists`.`qty` AS `qty`, `sales_return_lists`.`amount`- `sales_return_lists`.`sales_discount` AS `amount` FROM ((((`sales_return_lists` left join `products` on(`products`.`id` = `sales_return_lists`.`product_id`)) left join `categories` on(`categories`.`id` = `products`.`category_id`)) left join `product_skus` on(`product_skus`.`id` = `sales_return_lists`.`variant_id`)) left join `sales_returns` on(`sales_returns`.`id` = `sales_return_lists`.`sales_return_id`)) WHERE `sales_returns`.`deleted_at` is null AND `sales_returns`.`approve` = 1 AND `sales_returns`.`reject` = 0 AND `products`.`deleted_at` is null AND `products`.`status` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `view_transfers`
--
DROP TABLE IF EXISTS `view_transfers`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_transfers`  AS SELECT `transfers`.`company_id` AS `company_id`, `transfers`.`host_id` AS `host_id`, `transfers`.`destination_id` AS `destination_id`, `transfers`.`date` AS `date`, `transfers`.`product_type` AS `product_type`, `transfer_products`.`product_id` AS `product_id`, `products`.`name` AS `name`, `categories`.`id` AS `category_id`, `categories`.`name` AS `category_name`, `product_skus`.`id` AS `sku_id`, `product_skus`.`sku` AS `sku`, `transfer_products`.`qty` AS `qty` FROM ((((`transfer_products` left join `products` on(`products`.`id` = `transfer_products`.`product_id`)) left join `categories` on(`categories`.`id` = `products`.`category_id`)) left join `product_skus` on(`product_skus`.`id` = `transfer_products`.`variant_id`)) left join `transfers` on(`transfers`.`id` = `transfer_products`.`transfer_id`)) WHERE `transfers`.`deleted_at` is null AND `transfers`.`reject` = 0 AND `transfer_products`.`is_back` = 0 AND `products`.`deleted_at` is null AND `products`.`status` = 1 ;

-- --------------------------------------------------------

--
-- Structure for view `view_trial_balance`
--
DROP TABLE IF EXISTS `view_trial_balance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`alifinef`@`localhost` SQL SECURITY DEFINER VIEW `view_trial_balance`  AS SELECT `account_transactions`.`voucher_type` AS `voucher_type`, `account_transactions`.`voucher_no` AS `voucher_no`, `account_transactions`.`voucher_date` AS `voucher_date`, `account_transactions`.`coa_setup_id` AS `coa_setup_id`, `account_transactions`.`coa_head_code` AS `coa_head_code`, `account_transactions`.`debit_amount` AS `debit_amount`, `account_transactions`.`credit_amount` AS `credit_amount`, `coa_setups`.`transaction` AS `transaction`, `coa_setups`.`general` AS `general`, `coa_setups`.`parent_id` AS `parent_id`, `coa_setups`.`head_type` AS `head_type`, `coa_setups`.`head_name` AS `head_name` FROM (`account_transactions` left join `coa_setups` on(`coa_setups`.`id` = `account_transactions`.`coa_setup_id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `abouts`
--
ALTER TABLE `abouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `access_logs`
--
ALTER TABLE `access_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_transactions`
--
ALTER TABLE `account_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_transactions_account_transaction_auto_id_foreign` (`account_transaction_auto_id`);

--
-- Indexes for table `account_transaction_autos`
--
ALTER TABLE `account_transaction_autos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_menus`
--
ALTER TABLE `admin_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_menu_actions`
--
ALTER TABLE `admin_menu_actions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_settings`
--
ALTER TABLE `admin_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_collections`
--
ALTER TABLE `bulk_collections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bulk_collection_lists`
--
ALTER TABLE `bulk_collection_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bulk_collection_lists_bulk_collection_id_foreign` (`bulk_collection_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_vendors`
--
ALTER TABLE `category_vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chain_clients`
--
ALTER TABLE `chain_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_categories`
--
ALTER TABLE `client_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_messages`
--
ALTER TABLE `client_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_prices`
--
ALTER TABLE `client_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_prices_client_id_foreign` (`client_id`),
  ADD KEY `client_prices_product_id_foreign` (`product_id`);

--
-- Indexes for table `coa_setups`
--
ALTER TABLE `coa_setups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collections`
--
ALTER TABLE `collections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `collection_data`
--
ALTER TABLE `collection_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `collection_data_collection_id_foreign` (`collection_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `deliveries_serial_no_unique` (`serial_no`);

--
-- Indexes for table `delivery_charges`
--
ALTER TABLE `delivery_charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_lists`
--
ALTER TABLE `delivery_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_lists_delivery_id_foreign` (`delivery_id`);

--
-- Indexes for table `details_cards`
--
ALTER TABLE `details_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_members_group_id_foreign` (`group_id`);

--
-- Indexes for table `group_sales_targets`
--
ALTER TABLE `group_sales_targets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_sales_targets_group_id_foreign` (`group_id`);

--
-- Indexes for table `group_sales_target_categories`
--
ALTER TABLE `group_sales_target_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_sales_target_categories_group_sales_target_id_foreign` (`group_sales_target_id`);

--
-- Indexes for table `home_sections`
--
ALTER TABLE `home_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_section_sub_categories`
--
ALTER TABLE `home_section_sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `home_section_sub_categories_home_section_id_foreign` (`home_section_id`);

--
-- Indexes for table `investors`
--
ALTER TABLE `investors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investors_user_id_foreign` (`user_id`);

--
-- Indexes for table `investor_payments`
--
ALTER TABLE `investor_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investor_payments_investor_id_foreign` (`investor_id`);

--
-- Indexes for table `investor_products`
--
ALTER TABLE `investor_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investor_products_investor_id_foreign` (`investor_id`),
  ADD KEY `investor_products_product_id_foreign` (`product_id`);

--
-- Indexes for table `investor_profits`
--
ALTER TABLE `investor_profits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `investor_profit_lists`
--
ALTER TABLE `investor_profit_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investor_profit_lists_investor_profit_id_foreign` (`investor_profit_id`),
  ADD KEY `investor_profit_lists_investor_id_foreign` (`investor_id`);

--
-- Indexes for table `investor_sattlements`
--
ALTER TABLE `investor_sattlements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investor_sattlements_investor_id_foreign` (`investor_id`);

--
-- Indexes for table `investor_sattlement_lists`
--
ALTER TABLE `investor_sattlement_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `investor_sattlement_lists_investor_sattlement_id_foreign` (`investor_sattlement_id`);

--
-- Indexes for table `invests`
--
ALTER TABLE `invests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invests_investor_id_foreign` (`investor_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `liftings`
--
ALTER TABLE `liftings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `liftings_company_id_foreign` (`company_id`);

--
-- Indexes for table `lifting_documents`
--
ALTER TABLE `lifting_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lifting_documents_lifting_id_foreign` (`lifting_id`);

--
-- Indexes for table `lifting_products`
--
ALTER TABLE `lifting_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lifting_products_lifting_id_foreign` (`lifting_id`);

--
-- Indexes for table `lifting_returns`
--
ALTER TABLE `lifting_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lifting_return_lists`
--
ALTER TABLE `lifting_return_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lifting_return_lists_lifting_return_id_foreign` (`lifting_return_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_menu_id_foreign` (`menu_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `online_deliveries`
--
ALTER TABLE `online_deliveries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `online_deliveries_serial_no_unique` (`serial_no`);

--
-- Indexes for table `online_delivery_lists`
--
ALTER TABLE `online_delivery_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `online_delivery_lists_online_delivery_id_foreign` (`online_delivery_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`);

--
-- Indexes for table `order_products`
--
ALTER TABLE `order_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_products_order_id_foreign` (`order_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_name_unique` (`name`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`);

--
-- Indexes for table `product_prices`
--
ALTER TABLE `product_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_prices_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_skus`
--
ALTER TABLE `product_skus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_skus_product_id_foreign` (`product_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_lists`
--
ALTER TABLE `sales_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_lists_sales_id_foreign` (`sales_id`);

--
-- Indexes for table `sales_product_data`
--
ALTER TABLE `sales_product_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_product_data_sales_id_foreign` (`sales_id`);

--
-- Indexes for table `sales_returns`
--
ALTER TABLE `sales_returns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_return_lists`
--
ALTER TABLE `sales_return_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_return_lists_sales_return_id_foreign` (`sales_return_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `showcase_items`
--
ALTER TABLE `showcase_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_works`
--
ALTER TABLE `social_works`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `special_food_items`
--
ALTER TABLE `special_food_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `static_site_items`
--
ALTER TABLE `static_site_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscriptions_email_unique` (`email`);

--
-- Indexes for table `territories`
--
ALTER TABLE `territories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transfers_transfer_no_unique` (`transfer_no`);

--
-- Indexes for table `transfer_products`
--
ALTER TABLE `transfer_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transfer_products_transfer_id_foreign` (`transfer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_user_name_unique` (`user_name`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicles_company_id_foreign` (`company_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_payment_data`
--
ALTER TABLE `vendor_payment_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_payment_data_vendor_payment_id_foreign` (`vendor_payment_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallets_investor_id_foreign` (`investor_id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `abouts`
--
ALTER TABLE `abouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `access_logs`
--
ALTER TABLE `access_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_transactions`
--
ALTER TABLE `account_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_transaction_autos`
--
ALTER TABLE `account_transaction_autos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_menus`
--
ALTER TABLE `admin_menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `admin_menu_actions`
--
ALTER TABLE `admin_menu_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=367;

--
-- AUTO_INCREMENT for table `admin_settings`
--
ALTER TABLE `admin_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `attribute_values`
--
ALTER TABLE `attribute_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bulk_collections`
--
ALTER TABLE `bulk_collections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bulk_collection_lists`
--
ALTER TABLE `bulk_collection_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `category_vendors`
--
ALTER TABLE `category_vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `chain_clients`
--
ALTER TABLE `chain_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3032;

--
-- AUTO_INCREMENT for table `client_categories`
--
ALTER TABLE `client_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `client_messages`
--
ALTER TABLE `client_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_prices`
--
ALTER TABLE `client_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `coa_setups`
--
ALTER TABLE `coa_setups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `collections`
--
ALTER TABLE `collections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collection_data`
--
ALTER TABLE `collection_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_charges`
--
ALTER TABLE `delivery_charges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_lists`
--
ALTER TABLE `delivery_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `details_cards`
--
ALTER TABLE `details_cards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `group_sales_targets`
--
ALTER TABLE `group_sales_targets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `group_sales_target_categories`
--
ALTER TABLE `group_sales_target_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `home_sections`
--
ALTER TABLE `home_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `home_section_sub_categories`
--
ALTER TABLE `home_section_sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `investors`
--
ALTER TABLE `investors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `investor_payments`
--
ALTER TABLE `investor_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investor_products`
--
ALTER TABLE `investor_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `investor_profits`
--
ALTER TABLE `investor_profits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investor_profit_lists`
--
ALTER TABLE `investor_profit_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investor_sattlements`
--
ALTER TABLE `investor_sattlements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investor_sattlement_lists`
--
ALTER TABLE `investor_sattlement_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invests`
--
ALTER TABLE `invests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `liftings`
--
ALTER TABLE `liftings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lifting_documents`
--
ALTER TABLE `lifting_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lifting_products`
--
ALTER TABLE `lifting_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lifting_returns`
--
ALTER TABLE `lifting_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lifting_return_lists`
--
ALTER TABLE `lifting_return_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=600;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=171;

--
-- AUTO_INCREMENT for table `online_deliveries`
--
ALTER TABLE `online_deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `online_delivery_lists`
--
ALTER TABLE `online_delivery_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_products`
--
ALTER TABLE `order_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=529;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `portfolios`
--
ALTER TABLE `portfolios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `product_prices`
--
ALTER TABLE `product_prices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `product_skus`
--
ALTER TABLE `product_skus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_lists`
--
ALTER TABLE `sales_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_product_data`
--
ALTER TABLE `sales_product_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_returns`
--
ALTER TABLE `sales_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_return_lists`
--
ALTER TABLE `sales_return_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `showcase_items`
--
ALTER TABLE `showcase_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `social_works`
--
ALTER TABLE `social_works`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `special_food_items`
--
ALTER TABLE `special_food_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `static_site_items`
--
ALTER TABLE `static_site_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `territories`
--
ALTER TABLE `territories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer_products`
--
ALTER TABLE `transfer_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_payment_data`
--
ALTER TABLE `vendor_payment_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_transactions`
--
ALTER TABLE `account_transactions`
  ADD CONSTRAINT `account_transactions_account_transaction_auto_id_foreign` FOREIGN KEY (`account_transaction_auto_id`) REFERENCES `account_transaction_autos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bulk_collection_lists`
--
ALTER TABLE `bulk_collection_lists`
  ADD CONSTRAINT `bulk_collection_lists_bulk_collection_id_foreign` FOREIGN KEY (`bulk_collection_id`) REFERENCES `bulk_collections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_prices`
--
ALTER TABLE `client_prices`
  ADD CONSTRAINT `client_prices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_prices_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `collection_data`
--
ALTER TABLE `collection_data`
  ADD CONSTRAINT `collection_data_collection_id_foreign` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_lists`
--
ALTER TABLE `delivery_lists`
  ADD CONSTRAINT `delivery_lists_delivery_id_foreign` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_sales_targets`
--
ALTER TABLE `group_sales_targets`
  ADD CONSTRAINT `group_sales_targets_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_sales_target_categories`
--
ALTER TABLE `group_sales_target_categories`
  ADD CONSTRAINT `group_sales_target_categories_group_sales_target_id_foreign` FOREIGN KEY (`group_sales_target_id`) REFERENCES `group_sales_targets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `home_section_sub_categories`
--
ALTER TABLE `home_section_sub_categories`
  ADD CONSTRAINT `home_section_sub_categories_home_section_id_foreign` FOREIGN KEY (`home_section_id`) REFERENCES `home_sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investors`
--
ALTER TABLE `investors`
  ADD CONSTRAINT `investors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investor_payments`
--
ALTER TABLE `investor_payments`
  ADD CONSTRAINT `investor_payments_investor_id_foreign` FOREIGN KEY (`investor_id`) REFERENCES `investors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investor_products`
--
ALTER TABLE `investor_products`
  ADD CONSTRAINT `investor_products_investor_id_foreign` FOREIGN KEY (`investor_id`) REFERENCES `investors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `investor_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investor_profit_lists`
--
ALTER TABLE `investor_profit_lists`
  ADD CONSTRAINT `investor_profit_lists_investor_id_foreign` FOREIGN KEY (`investor_id`) REFERENCES `investors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `investor_profit_lists_investor_profit_id_foreign` FOREIGN KEY (`investor_profit_id`) REFERENCES `investor_profits` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investor_sattlements`
--
ALTER TABLE `investor_sattlements`
  ADD CONSTRAINT `investor_sattlements_investor_id_foreign` FOREIGN KEY (`investor_id`) REFERENCES `investors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `investor_sattlement_lists`
--
ALTER TABLE `investor_sattlement_lists`
  ADD CONSTRAINT `investor_sattlement_lists_investor_sattlement_id_foreign` FOREIGN KEY (`investor_sattlement_id`) REFERENCES `investor_sattlements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invests`
--
ALTER TABLE `invests`
  ADD CONSTRAINT `invests_investor_id_foreign` FOREIGN KEY (`investor_id`) REFERENCES `investors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `liftings`
--
ALTER TABLE `liftings`
  ADD CONSTRAINT `liftings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lifting_documents`
--
ALTER TABLE `lifting_documents`
  ADD CONSTRAINT `lifting_documents_lifting_id_foreign` FOREIGN KEY (`lifting_id`) REFERENCES `liftings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lifting_products`
--
ALTER TABLE `lifting_products`
  ADD CONSTRAINT `lifting_products_lifting_id_foreign` FOREIGN KEY (`lifting_id`) REFERENCES `liftings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lifting_return_lists`
--
ALTER TABLE `lifting_return_lists`
  ADD CONSTRAINT `lifting_return_lists_lifting_return_id_foreign` FOREIGN KEY (`lifting_return_id`) REFERENCES `lifting_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `online_delivery_lists`
--
ALTER TABLE `online_delivery_lists`
  ADD CONSTRAINT `online_delivery_lists_online_delivery_id_foreign` FOREIGN KEY (`online_delivery_id`) REFERENCES `online_deliveries` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_products`
--
ALTER TABLE `order_products`
  ADD CONSTRAINT `order_products_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_skus`
--
ALTER TABLE `product_skus`
  ADD CONSTRAINT `product_skus_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_lists`
--
ALTER TABLE `sales_lists`
  ADD CONSTRAINT `sales_lists_sales_id_foreign` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_product_data`
--
ALTER TABLE `sales_product_data`
  ADD CONSTRAINT `sales_product_data_sales_id_foreign` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_return_lists`
--
ALTER TABLE `sales_return_lists`
  ADD CONSTRAINT `sales_return_lists_sales_return_id_foreign` FOREIGN KEY (`sales_return_id`) REFERENCES `sales_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transfer_products`
--
ALTER TABLE `transfer_products`
  ADD CONSTRAINT `transfer_products_transfer_id_foreign` FOREIGN KEY (`transfer_id`) REFERENCES `transfers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_payment_data`
--
ALTER TABLE `vendor_payment_data`
  ADD CONSTRAINT `vendor_payment_data_vendor_payment_id_foreign` FOREIGN KEY (`vendor_payment_id`) REFERENCES `vendor_payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_investor_id_foreign` FOREIGN KEY (`investor_id`) REFERENCES `investors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
