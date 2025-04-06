-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2025 at 06:34 PM
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
-- Database: `tros`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_form_submissions`
--

CREATE TABLE `contact_form_submissions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile_no` varchar(15) DEFAULT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `service` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_form_submissions`
--

INSERT INTO `contact_form_submissions` (`id`, `name`, `email`, `mobile_no`, `reference`, `service`, `message`, `created_at`) VALUES
(63, 'test', 'test1@gmail.com', '123456789', '', 'electrical', 'helo', '2025-02-08 10:28:52'),
(64, 'test', 'test1@gmail.com', '123456789', '', 'electrical', 'helo', '2025-02-08 10:29:10'),
(65, 'John Doe ', 'johndoe@email.com ', '123-456-7890', 'Website', 'plumbing', 'Need assistance with a leaking pipe.', '2025-02-08 10:31:54'),
(66, 'John Doe ', 'johndoe@email.com ', '123-456-7890', 'Website', 'plumbing', 'Need assistance with a leaking pipe.', '2025-02-08 10:36:52'),
(67, 'naqib', 'naqibsallehh@gmail.com', '0176304240', 'water faucet problem', 'plumbing', 'Hai', '2025-02-08 15:17:29'),
(68, 'john doe', 'johndoe@email.com', '123-456-7890', '', 'renovations', 'Inquiry about renovation', '2025-02-09 06:43:45'),
(69, '', '', '', '', '', '', '2025-02-09 06:44:47'),
(70, 'Goh Yi Qi', 'yiqigoh90@gmail.com', '0198778368', '', 'electrical', 'abc', '2025-02-09 07:40:43'),
(71, 'Naqib', 'naqibsallehh@gmail.com', '0176304240', 'water faucet problem', 'plumbing', 'abc', '2025-02-09 15:11:17'),
(72, 'Goh Yi Qi', 'yiqigoh90@gmail.com', '0198778368', '123', 'plumbing', 'good service', '2025-02-10 04:48:58'),
(73, 'Goh Yi Qi', 'gohyiqi1@gmail.com', '0198778368', '123', 'plumbing', '1243', '2025-03-06 03:25:04'),
(75, 'Cui Ling', 'cuiling345@gmail.com', '0179935279', '', 'plumbing', 'Question', '2025-03-25 08:03:39');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback` text NOT NULL,
  `rating` int(11) NOT NULL,
  `feedback_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `transaction_id`, `user_id`, `feedback`, `rating`, `feedback_image`, `created_at`) VALUES
(4, 'pi_3Qyb5VP0HU7M7BGv01hpZkQB', 1, 'Good service', 4, 'uploads/feedback_images/67a6da0660c8d_67a6d9db17968_lalala123.jpeg', '2025-03-03 08:54:23'),
(5, 'pi_3QyvcAP0HU7M7BGv0q0IH2qo', 1, 'I recently used [Company Name] for a plumbing issue at my home, and I couldn\'t be more satisfied with their service. The technician arrived on time, was very professional, and quickly identified the problem. They explained everything clearly and fixed the issue efficiently.\r\n\r\nNot only was the service top-notch, but they also ensured that everything was cleaned up before leaving. The pricing was fair, with no hidden charges, and the quality of work was outstanding. My plumbing is now working perfectly!\r\n\r\nI highly recommend [Company Name] for anyone in need of reliable and professional plumbing services. Will definitely use them again in the future!', 5, NULL, '2025-03-04 16:43:39'),
(6, 'pi_3QzCamP0HU7M7BGv1KC7NGGu', 1, 'Divyan handsome and smart', 4, 'uploads/feedback_images/67a5fbd3a95ea_Profile picture naqib.jpg', '2025-03-05 07:43:18'),
(7, 'pi_3QzCamP0HU7M7BGv1KC7NGGu', 1, 'Good', 4, NULL, '2025-03-05 14:33:04'),
(8, 'undefined', 1, 'Good', 4, NULL, '2025-03-05 14:53:45'),
(9, 'undefined', 1, 'Good service', 4, 'uploads/feedback_images/Screenshot (5).png', '2025-03-06 03:04:14'),
(10, 'undefined', 1, 'Good', 4, 'uploads/feedback_images/Screenshot 2024-01-08 151628.png', '2025-03-06 03:13:36'),
(11, 'undefined', 1, 'Goh', 4, NULL, '2025-03-06 03:13:48'),
(12, 'undefined', 2, 'Aliff', 4, 'uploads/feedback_images/Screenshot 2024-02-26 154008.png', '2025-03-06 08:35:29'),
(13, 'pi_3QzZspP0HU7M7BGv07Fqq83X', 2, 'Aliff', 4, NULL, '2025-03-06 08:46:54'),
(14, 'pi_3R0jVYP0HU7M7BGv1UXup6Uc', 10, 'Naqib Handsome', 3, 'uploads/feedback_images/ktmb.jpg', '2025-03-09 13:03:42'),
(15, 'pi_3R0xgCP0HU7M7BGv1m8z0kpB', 12, 'Divyan Kumar', 4, 'uploads/feedback_images/signature.png', '2025-03-10 04:12:58'),
(16, 'pi_3R2U6KP0HU7M7BGv1pm34jn8', 10, 'jbjkkj', 4, NULL, '2025-03-14 09:02:55'),
(17, 'pi_3R39b6P0HU7M7BGv1hWbgMOW', 4, 'SYABIL HANDSOME GILA MY IDIOT OPPS TYPO MY IDOL', 5, 'uploads/feedback_images/Screenshot 2024-03-04 185419.png', '2025-03-16 05:27:07'),
(18, 'pi_3R39b6P0HU7M7BGv1hWbgMOW', 4, 'e3e3e', 4, NULL, '2025-03-16 07:54:47'),
(19, 'pi_3R39b6P0HU7M7BGv1hWbgMOW', 4, 'dahdbda', 2, NULL, '2025-03-16 09:15:04'),
(20, 'pi_3R3HQOP0HU7M7BGv0BbzCfR1', 9, 'crfrcxdre', 5, 'uploads/feedback_images/Screenshot (7).png', '2025-03-16 13:40:52'),
(21, 'pi_3R3xbsP0HU7M7BGv0e58jhFX', 9, 'GOOD', 4, NULL, '2025-03-21 10:52:09'),
(22, 'pi_3R3HQOP0HU7M7BGv0BbzCfR1', 9, 'GOOD AND INTERESTING', 4, NULL, '2025-03-21 10:52:26'),
(23, 'pi_3R3EzRP0HU7M7BGv0w5D9EzN', 9, 'WOW', 4, NULL, '2025-03-21 10:52:41'),
(24, 'pi_3R3yE6P0HU7M7BGv0uzi1Vd2', 9, 'AMAZING', 5, NULL, '2025-03-21 10:52:56'),
(25, 'pi_3R3yE6P0HU7M7BGv0uzi1Vd2', 9, 'F', 4, 'uploads/feedback_images/67b1cd3215492_67a987dcdc0b4_67a6d9db17968_lalala123.jpeg', '2025-03-21 10:53:23'),
(26, 'pi_3R3yE6P0HU7M7BGv0uzi1Vd2', 9, 'GOOD', 5, NULL, '2025-03-21 11:14:31'),
(27, 'pi_3R3EzRP0HU7M7BGv0w5D9EzN', 9, 'GOOD', 3, NULL, '2025-03-21 11:20:40'),
(28, 'pi_3R3yE6P0HU7M7BGv0uzi1Vd2', 9, 'SBJD', 4, NULL, '2025-03-21 11:20:48'),
(29, 'pi_3R53h2P0HU7M7BGv1EMHHYIN', 9, 'GOOD', 5, NULL, '2025-03-21 11:25:19'),
(30, 'pi_3R53hxP0HU7M7BGv1h72r3Ud', 9, 'GOOD', 5, NULL, '2025-03-21 11:26:10'),
(31, 'pi_3R5T22P0HU7M7BGv18Sc7iZX', 6, 'nicee', 3, NULL, '2025-03-23 09:53:27');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `payer_email` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'Card',
  `payment_status` varchar(50) DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `booking_time` time DEFAULT NULL,
  `invoice_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `service_type` varchar(255) NOT NULL DEFAULT 'General',
  `status` enum('Pending','Completed','Canceled') NOT NULL DEFAULT 'Pending',
  `technician_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `transaction_id`, `payer_email`, `amount`, `currency`, `payment_method`, `payment_status`, `booking_date`, `booking_time`, `invoice_path`, `created_at`, `service_type`, `status`, `technician_id`) VALUES
(247, 10, 'pi_3R2uzuP0HU7M7BGv0Z1m9hWu', 'gohyiqi1@gmail.com', 107.00, 'MYR', 'Card', 'paid', '2025-03-20', '12:00:00', 'invoices/Invoice_pi_3R2uzuP0HU7M7BGv0Z1m9hWu.pdf', '2025-03-15 13:43:32', 'plumbing', 'Completed', 24),
(248, 10, 'pi_3R2v1pP0HU7M7BGv05Dv7dAR', 'gohyiqi1@gmail.com', 72.00, 'MYR', 'Card', 'paid', '2025-03-20', '12:00:00', 'invoices/Invoice_pi_3R2v1pP0HU7M7BGv05Dv7dAR.pdf', '2025-03-15 13:45:30', 'plumbing', 'Canceled', 18),
(249, 10, 'pi_3R2v52P0HU7M7BGv0fwR8L1Y', 'gohyiqi1@gmail.com', 107.00, 'MYR', 'Card', 'paid', '2025-03-20', '12:00:00', 'invoices/Invoice_pi_3R2v52P0HU7M7BGv0fwR8L1Y.pdf', '2025-03-15 13:48:51', 'plumbing', 'Completed', 21),
(250, 10, 'pi_3R2v5pP0HU7M7BGv0l1xPOA7', 'gohyiqi1@gmail.com', 100.00, 'MYR', 'Card', 'paid', '2025-03-21', '12:00:00', 'invoices/Invoice_pi_3R2v5pP0HU7M7BGv0l1xPOA7.pdf', '2025-03-15 13:49:39', 'plumbing', 'Canceled', 127),
(251, 10, 'pi_3R2vM0P0HU7M7BGv1wPCVV4v', 'gohyiqi1@gmail.com', 95.00, 'MYR', 'Card', 'paid', '2025-03-20', '12:00:00', 'invoices/Invoice_pi_3R2vM0P0HU7M7BGv1wPCVV4v.pdf', '2025-03-15 14:06:22', 'plumbing', 'Completed', 22),
(252, 10, 'pi_3R2vR2P0HU7M7BGv1kb4Ow9d', 'gohyiqi1@gmail.com', 102.00, 'MYR', 'Card', 'paid', '2025-03-20', '12:00:00', 'invoices/Invoice_pi_3R2vR2P0HU7M7BGv1kb4Ow9d.pdf', '2025-03-15 14:11:34', 'plumbing', 'Completed', 20),
(253, 10, 'pi_3R2vSUP0HU7M7BGv10dVaD4o', 'gohyiqi1@gmail.com', 110.00, 'MYR', 'Card', 'paid', '2025-03-21', '12:00:00', 'invoices/Invoice_pi_3R2vSUP0HU7M7BGv10dVaD4o.pdf', '2025-03-15 14:13:05', 'plumbing', 'Canceled', 48),
(254, 10, 'pi_3R2vUIP0HU7M7BGv0JUhTZfl', 'gohyiqi1@gmail.com', 15.00, 'MYR', 'Card', 'paid', '2025-03-19', '12:00:00', 'invoices/Invoice_pi_3R2vUIP0HU7M7BGv0JUhTZfl.pdf', '2025-03-15 14:14:56', 'plumbing', 'Completed', 38),
(255, 10, 'pi_3R2vXFP0HU7M7BGv1Tqzjkpz', 'gohyiqi1@gmail.com', 90.00, 'MYR', 'Card', 'paid', '2025-03-19', '12:00:00', 'invoices/Invoice_pi_3R2vXFP0HU7M7BGv1Tqzjkpz.pdf', '2025-03-15 14:17:59', 'plumbing', 'Completed', 4),
(256, 4, 'pi_3R39b6P0HU7M7BGv1hWbgMOW', 'gohyiqi1@gmail.com', 15.00, 'MYR', 'Card', 'paid', '2025-03-19', '12:00:00', 'invoices/Invoice_pi_3R39b6P0HU7M7BGv1hWbgMOW.pdf', '2025-03-16 05:18:53', 'plumbing', 'Completed', 38),
(257, 4, 'pi_3R3ByjP0HU7M7BGv1CpE7mn0', 'gohyiqi1@gmail.com', 85.00, 'MYR', 'Card', 'paid', '2025-03-21', '12:00:00', 'invoices/Invoice_pi_3R3ByjP0HU7M7BGv1CpE7mn0.pdf', '2025-03-16 07:51:26', 'renovation', 'Completed', 33),
(259, 4, 'pi_3R3CFkP0HU7M7BGv0fTOndON', 'gohyiqi1@gmail.com', 72.00, 'MYR', 'Card', 'paid', '2025-03-18', '12:00:00', NULL, '2025-03-16 08:14:09', 'plumbing', 'Completed', 50),
(260, 4, 'pi_3R3CrEP0HU7M7BGv1ptHSx92', 'gohyiqi1@gmail.com', 87.00, 'MYR', 'Card', 'paid', '2025-03-18', '12:00:00', 'invoices/Invoice_pi_3R3CrEP0HU7M7BGv1ptHSx92.pdf', '2025-03-16 08:47:45', 'electrical', 'Completed', 13),
(261, 9, 'pi_3R3EzRP0HU7M7BGv0w5D9EzN', 'gohyiqi1@gmail.com', 150.00, 'MYR', 'Card', 'paid', '2025-05-16', '12:00:00', 'invoices/Invoice_pi_3R3EzRP0HU7M7BGv0w5D9EzN.pdf', '2025-03-16 11:04:22', 'renovation', 'Completed', 96),
(262, 9, 'pi_3R3HQOP0HU7M7BGv0BbzCfR1', 'gohyiqi1@gmail.com', 72.00, 'MYR', 'Card', 'paid', '2025-03-18', '12:00:00', 'invoices/Invoice_pi_3R3HQOP0HU7M7BGv0BbzCfR1.pdf', '2025-03-16 13:40:21', 'plumbing', 'Completed', 18),
(263, 9, 'pi_3R3xbsP0HU7M7BGv0e58jhFX', 'gohyiqi1@gmail.com', 72.00, 'MYR', 'Card', 'paid', '2025-05-13', '12:00:00', 'invoices/Invoice_pi_3R3xbsP0HU7M7BGv0e58jhFX.pdf', '2025-03-18 10:43:03', 'plumbing', 'Completed', 18),
(264, 9, 'pi_3R3xcuP0HU7M7BGv04TKs6zX', 'gohyiqi1@gmail.com', 80.00, 'MYR', 'Card', 'paid', '2025-05-14', '12:00:00', 'invoices/Invoice_pi_3R3xcuP0HU7M7BGv04TKs6zX.pdf', '2025-03-18 10:44:07', 'plumbing', 'Completed', 7),
(265, 9, 'pi_3R3xouP0HU7M7BGv1UPOLy35', 'gohyiqi1@gmail.com', 93.00, 'MYR', 'Card', 'paid', '2025-04-08', '12:00:00', 'invoices/Invoice_pi_3R3xouP0HU7M7BGv1UPOLy35.pdf', '2025-03-18 10:56:31', 'electrical', 'Completed', 79),
(266, 9, 'pi_3R3yE6P0HU7M7BGv0uzi1Vd2', 'gohyiqi1@gmail.com', 200.00, 'MYR', 'Card', 'paid', '2025-03-20', '12:00:00', 'invoices/Invoice_pi_3R3yE6P0HU7M7BGv0uzi1Vd2.pdf', '2025-03-18 11:22:33', 'renovation', 'Completed', 101),
(267, 9, 'pi_3R3yEsP0HU7M7BGv0lRyIfrB', 'gohyiqi1@gmail.com', 40.00, 'MYR', 'Card', 'paid', '2025-03-20', '12:00:00', 'invoices/Invoice_pi_3R3yEsP0HU7M7BGv0lRyIfrB.pdf', '2025-03-18 11:23:21', 'plumbing', 'Canceled', 138),
(268, 9, 'pi_3R53WdP0HU7M7BGv04WJPUNr', 'gohyiqi1@gmail.com', 105.00, 'MYR', 'Card', 'paid', '2025-03-21', '07:13:00', 'invoices/Invoice_pi_3R53WdP0HU7M7BGv04WJPUNr.pdf', '2025-03-21 11:14:10', 'plumbing', 'Completed', 42),
(269, 9, 'pi_3R53h2P0HU7M7BGv1EMHHYIN', 'gohyiqi1@gmail.com', 160.00, 'MYR', 'Card', 'paid', '2025-03-21', '07:24:00', 'invoices/Invoice_pi_3R53h2P0HU7M7BGv1EMHHYIN.pdf', '2025-03-21 11:24:55', 'electrical', 'Completed', 36),
(270, 9, 'pi_3R53hxP0HU7M7BGv1h72r3Ud', 'gohyiqi1@gmail.com', 150.00, 'MYR', 'Card', 'paid', '2025-03-21', '07:25:00', 'invoices/Invoice_pi_3R53hxP0HU7M7BGv1h72r3Ud.pdf', '2025-03-21 11:25:52', 'renovation', 'Completed', 96),
(271, 6, 'pi_3R5QfpP0HU7M7BGv007eslJq', 'gohyiqi1@gmail.com', 150.00, 'MYR', 'Card', 'paid', '2025-03-29', '10:00:00', 'invoices/Invoice_pi_3R5QfpP0HU7M7BGv007eslJq.pdf', '2025-03-22 11:57:12', 'renovation', 'Completed', 124),
(275, 6, 'pi_3R5QiuP0HU7M7BGv0cQgXIWf', 'gohyiqi1@gmail.com', 95.00, 'MYR', 'Card', 'paid', '2025-03-29', '10:00:00', 'invoices/Invoice_pi_3R5QiuP0HU7M7BGv0cQgXIWf.pdf', '2025-03-22 12:00:23', 'plumbing', 'Completed', 22),
(276, 6, 'pi_3R5QmtP0HU7M7BGv08CH6KRJ', 'gohyiqi1@gmail.com', 80.00, 'MYR', 'Card', 'paid', '2025-03-22', '09:00:00', 'invoices/Invoice_pi_3R5QmtP0HU7M7BGv08CH6KRJ.pdf', '2025-03-22 12:04:30', 'renovation', 'Completed', 97),
(277, 6, 'pi_3R5QsxP0HU7M7BGv08Bagzzo', 'gohyiqi1@gmail.com', 75.00, 'MYR', 'Card', 'paid', '2025-03-22', '09:00:00', 'invoices/Invoice_pi_3R5QsxP0HU7M7BGv08Bagzzo.pdf', '2025-03-22 12:10:45', 'renovation', 'Completed', 112),
(280, 6, 'pi_3R5QwDP0HU7M7BGv1guinjPS', 'gohyiqi1@gmail.com', 82.00, 'MYR', 'Card', 'paid', '2025-03-22', '09:00:00', 'invoices/Invoice_pi_3R5QwDP0HU7M7BGv1guinjPS.pdf', '2025-03-22 12:14:07', 'electrical', 'Completed', 66),
(281, 6, 'pi_3R5SaNP0HU7M7BGv0RRz17Fp', 'gohyiqi1@gmail.com', 80.00, 'MYR', 'Card', 'paid', '2025-03-27', '11:00:00', 'invoices/Invoice_pi_3R5SaNP0HU7M7BGv0RRz17Fp.pdf', '2025-03-22 13:59:42', 'renovation', 'Completed', 117),
(282, 6, 'pi_3R5SkxP0HU7M7BGv0j6MVUWN', 'gohyiqi1@gmail.com', 110.00, 'MYR', 'Card', 'paid', '2025-03-27', '09:00:00', 'invoices/Invoice_pi_3R5SkxP0HU7M7BGv0j6MVUWN.pdf', '2025-03-22 14:10:38', 'electrical', 'Completed', 39),
(283, 6, 'pi_3R5SngP0HU7M7BGv0c1VAMPF', 'gohyiqi1@gmail.com', 80.00, 'MYR', 'Card', 'paid', '2025-03-27', '10:00:00', 'invoices/Invoice_pi_3R5SngP0HU7M7BGv0c1VAMPF.pdf', '2025-03-22 14:13:27', 'renovation', 'Completed', 97),
(284, 6, 'pi_3R5SqUP0HU7M7BGv1yOvPIM2', 'gohyiqi1@gmail.com', 15.00, 'MYR', 'Card', 'paid', '2025-03-26', '11:00:00', 'invoices/Invoice_pi_3R5SqUP0HU7M7BGv1yOvPIM2.pdf', '2025-03-22 14:16:21', 'plumbing', 'Completed', 38),
(286, 6, 'pi_3R5SsNP0HU7M7BGv05wuG0Fi', 'gohyiqi1@gmail.com', 105.00, 'MYR', 'Card', 'paid', '2025-03-29', '11:00:00', 'invoices/Invoice_pi_3R5SsNP0HU7M7BGv05wuG0Fi.pdf', '2025-03-22 14:18:18', 'renovation', 'Completed', 88),
(287, 6, 'pi_3R5St4P0HU7M7BGv1BmsVeEJ', 'gohyiqi1@gmail.com', 80.00, 'MYR', 'Card', 'paid', '2025-03-29', '11:00:00', 'invoices/Invoice_pi_3R5St4P0HU7M7BGv1BmsVeEJ.pdf', '2025-03-22 14:19:01', 'renovation', 'Completed', 117),
(288, 6, 'pi_3R5SvbP0HU7M7BGv0zjqRcOy', 'gohyiqi1@gmail.com', 87.00, 'MYR', 'Card', 'paid', '2025-03-29', '11:00:00', 'invoices/Invoice_pi_3R5SvbP0HU7M7BGv0zjqRcOy.pdf', '2025-03-22 14:21:38', 'electrical', 'Completed', 13),
(289, 6, 'pi_3R5SxjP0HU7M7BGv0ekAupgs', 'gohyiqi1@gmail.com', 100.00, 'MYR', 'Card', 'paid', '2025-03-29', '10:00:00', 'invoices/Invoice_pi_3R5SxjP0HU7M7BGv0ekAupgs.pdf', '2025-03-22 14:23:49', 'renovation', 'Completed', 2025),
(291, 6, 'pi_3R5SyqP0HU7M7BGv1PaEDp5L', 'gohyiqi1@gmail.com', 92.00, 'MYR', 'Card', 'paid', '2025-03-29', '11:00:00', 'invoices/Invoice_pi_3R5SyqP0HU7M7BGv1PaEDp5L.pdf', '2025-03-22 14:24:59', 'plumbing', 'Completed', 2025),
(293, 6, 'pi_3R5T22P0HU7M7BGv18Sc7iZX', 'gohyiqi1@gmail.com', 80.00, 'MYR', 'Card', 'paid', '2025-03-31', '12:00:00', 'invoices/Invoice_pi_3R5T22P0HU7M7BGv18Sc7iZX.pdf', '2025-03-22 14:28:17', 'renovation', 'Completed', 107),
(296, 6, 'pi_3R5T2uP0HU7M7BGv1pNFcjMC', 'gohyiqi1@gmail.com', 100.00, 'MYR', 'Card', 'paid', '2025-03-31', '12:00:00', 'invoices/Invoice_pi_3R5T2uP0HU7M7BGv1pNFcjMC.pdf', '2025-03-22 14:29:11', 'renovation', 'Canceled', 108),
(297, 6, 'pi_3R5T4JP0HU7M7BGv1XRyeA5J', 'gohyiqi1@gmail.com', 80.00, 'MYR', 'Card', 'paid', '2025-03-29', '10:00:00', 'invoices/Invoice_pi_3R5T4JP0HU7M7BGv1XRyeA5J.pdf', '2025-03-22 14:30:37', 'renovation', 'Completed', 107),
(298, 6, 'pi_3R5T7nP0HU7M7BGv1XvA3OEe', 'gohyiqi1@gmail.com', 80.00, 'MYR', 'Card', 'paid', '2025-03-31', '10:00:00', 'invoices/Invoice_pi_3R5T7nP0HU7M7BGv1XvA3OEe.pdf', '2025-03-22 14:34:14', 'renovation', 'Completed', 99),
(299, 6, 'pi_3R5TAhP0HU7M7BGv10HvtIlE', 'gohyiqi1@gmail.com', 87.00, 'MYR', 'Card', 'paid', '2025-03-26', '09:00:00', 'invoices/Invoice_pi_3R5TAhP0HU7M7BGv10HvtIlE.pdf', '2025-03-22 14:37:13', 'electrical', 'Completed', 13),
(300, 6, 'pi_3R5TDwP0HU7M7BGv0oOTEH8r', 'gohyiqi1@gmail.com', 110.00, 'MYR', 'Card', 'paid', '2025-03-27', '11:00:00', 'invoices/Invoice_pi_3R5TDwP0HU7M7BGv0oOTEH8r.pdf', '2025-03-22 14:40:34', 'electrical', 'Canceled', 46),
(302, 6, 'pi_3R5TIhP0HU7M7BGv0ivQivMl', 'gohyiqi1@gmail.com', 75.00, 'MYR', 'Card', 'paid', '2025-03-27', '11:00:00', 'invoices/Invoice_pi_3R5TIhP0HU7M7BGv0ivQivMl.pdf', '2025-03-22 14:45:30', 'renovation', 'Completed', 112),
(303, 6, 'pi_3R5TMHP0HU7M7BGv17WmftLI', 'gohyiqi1@gmail.com', 80.00, 'MYR', 'Card', 'paid', '2025-03-29', '10:00:00', 'invoices/Invoice_pi_3R5TMHP0HU7M7BGv17WmftLI.pdf', '2025-03-22 14:49:11', 'renovation', 'Completed', 111),
(304, 6, 'pi_3R5TSrP0HU7M7BGv0vBY5l75', 'gohyiqi1@gmail.com', 80.00, 'MYR', 'Card', 'paid', '2025-03-27', '10:00:00', 'invoices/Invoice_pi_3R5TSrP0HU7M7BGv0vBY5l75.pdf', '2025-03-22 14:55:59', 'renovation', 'Completed', 99),
(306, 6, 'pi_3R5TWvP0HU7M7BGv0QhtUQcT', 'gohyiqi1@gmail.com', 80.00, 'MYR', 'Card', 'paid', '2025-03-28', '11:00:00', 'invoices/Invoice_pi_3R5TWvP0HU7M7BGv0QhtUQcT.pdf', '2025-03-22 15:00:13', 'renovation', 'Canceled', 107),
(307, 6, 'pi_3R5TzyP0HU7M7BGv1wYWg245', 'gohyiqi1@gmail.com', 82.00, 'MYR', 'Card', 'paid', '2025-03-28', '10:00:00', 'invoices/Invoice_pi_3R5TzyP0HU7M7BGv1wYWg245.pdf', '2025-03-22 15:30:12', 'plumbing', 'Completed', 52),
(308, 6, 'pi_3R5U6bP0HU7M7BGv06LAahiX', 'gohyiqi1@gmail.com', 75.00, 'MYR', 'Card', 'paid', '2025-03-26', '10:00:00', 'invoices/Invoice_pi_3R5U6bP0HU7M7BGv06LAahiX.pdf', '2025-03-22 15:37:04', 'renovation', 'Completed', 100),
(309, 6, 'pi_3R5U8JP0HU7M7BGv0KqsaXqQ', 'yiqigoh90@gmail.com', 95.00, 'MYR', 'Card', 'paid', '2025-03-27', '12:00:00', 'invoices/Invoice_pi_3R5U8JP0HU7M7BGv0KqsaXqQ.pdf', '2025-03-22 15:38:50', 'plumbing', 'Completed', 43),
(310, 6, 'pi_3R5U9GP0HU7M7BGv1FTsODnZ', 'yiqigoh90@gmail.com', 95.00, 'MYR', 'Card', 'paid', '2025-03-26', '12:00:00', 'invoices/Invoice_pi_3R5U9GP0HU7M7BGv1FTsODnZ.pdf', '2025-03-22 15:39:49', 'plumbing', 'Completed', 43),
(311, 6, 'pi_3R5UATP0HU7M7BGv0uDszlmC', 'yiqigoh90@gmail.com', 95.00, 'MYR', 'Card', 'paid', '2025-03-28', '12:00:00', 'invoices/Invoice_pi_3R5UATP0HU7M7BGv0uDszlmC.pdf', '2025-03-22 15:41:03', 'plumbing', 'Canceled', 43),
(312, 6, 'pi_3R5UD2P0HU7M7BGv0EOPfxdu', 'yiqigoh90@gmail.com', 87.00, 'MYR', 'Card', 'paid', '2025-03-27', '10:00:00', 'invoices/Invoice_pi_3R5UD2P0HU7M7BGv0EOPfxdu.pdf', '2025-03-22 15:43:43', 'electrical', 'Completed', 25),
(313, 6, 'pi_3R5UEMP0HU7M7BGv16AB6V8d', 'yiqigoh90@gmail.com', 90.00, 'MYR', 'Card', 'paid', '2025-03-29', '09:00:00', 'invoices/Invoice_pi_3R5UEMP0HU7M7BGv16AB6V8d.pdf', '2025-03-22 15:45:05', 'plumbing', 'Completed', 4),
(316, 6, 'pi_3R5V6IP0HU7M7BGv1mxLFsLn', 'yiqigoh90@gmail.com', 100.00, 'MYR', 'Card', 'paid', '2025-03-29', '09:00:00', 'invoices/Invoice_pi_3R5V6IP0HU7M7BGv1mxLFsLn.pdf', '2025-03-22 16:40:48', 'renovation', 'Canceled', 104),
(317, 6, 'pi_3R5VN0P0HU7M7BGv1ebNLjh4', 'yiqigoh90@gmail.com', 75.00, 'MYR', 'Card', 'paid', '2025-03-26', '10:00:00', 'invoices/Invoice_pi_3R5VN0P0HU7M7BGv1ebNLjh4.pdf', '2025-03-22 16:58:04', 'renovation', 'Completed', 112),
(318, 6, 'pi_3R5iWRP0HU7M7BGv0PxeBi9r', 'gohyiqi1@gmail.com', 80.00, 'MYR', 'Card', 'paid', '2025-03-26', '10:00:00', 'invoices/Invoice_pi_3R5iWRP0HU7M7BGv0PxeBi9r.pdf', '2025-03-23 07:00:42', 'renovation', 'Completed', 99),
(319, 6, 'pi_3R5kpHP0HU7M7BGv1FdZf3zE', 'gohyiqi1@gmail.com', 80.00, 'MYR', 'Card', 'paid', '2025-03-26', '10:00:00', 'invoices/Invoice_pi_3R5kpHP0HU7M7BGv1FdZf3zE.pdf', '2025-03-23 09:28:18', 'renovation', 'Completed', 107),
(320, 6, 'pi_3R5l0PP0HU7M7BGv1j02Fvv5', 'gohyiqi1@gmail.com', 75.00, 'MYR', 'Card', 'paid', '2025-03-25', '12:00:00', 'invoices/Invoice_pi_3R5l0PP0HU7M7BGv1j02Fvv5.pdf', '2025-03-23 09:39:49', 'renovation', 'Completed', 112),
(322, 6, 'pi_3R5l5fP0HU7M7BGv1c82uhy4', 'gohyiqi1@gmail.com', 72.00, 'MYR', 'Card', 'paid', '2025-03-23', '07:00:00', 'invoices/Invoice_pi_3R5l5fP0HU7M7BGv1c82uhy4.pdf', '2025-03-23 09:45:15', 'plumbing', 'Completed', 50),
(324, 6, 'pi_3R5lI8P0HU7M7BGv1RQrVydC', 'gohyiqi1@gmail.com', 157.00, 'MYR', 'Card', 'paid', '2025-03-27', '10:00:00', 'invoices/Invoice_pi_3R5lI8P0HU7M7BGv1RQrVydC.pdf', '2025-03-23 09:58:07', 'renovation', 'Completed', 92),
(326, 6, 'pi_3R5lpAP0HU7M7BGv0hPwyCjx', 'gohyiqi1@gmail.com', 87.00, 'MYR', 'Card', 'paid', '2025-03-26', '09:00:00', 'invoices/Invoice_pi_3R5lpAP0HU7M7BGv0hPwyCjx.pdf', '2025-03-23 10:32:15', 'electrical', 'Completed', 25),
(327, 6, 'pi_3R5pmFP0HU7M7BGv0kdlrVuR', 'gohyiqi1@gmail.com', 87.00, 'MYR', 'Card', 'paid', '2025-03-25', '10:00:00', 'invoices/Invoice_pi_3R5pmFP0HU7M7BGv0kdlrVuR.pdf', '2025-03-23 14:45:31', 'renovation', 'Completed', 99),
(328, 6, 'pi_3R5qxyP0HU7M7BGv0PkmIMk6', 'gohyiqi1@gmail.com', 95.00, 'MYR', 'Card', 'paid', '2025-03-28', '10:00:00', 'invoices/Invoice_pi_3R5qxyP0HU7M7BGv0PkmIMk6.pdf', '2025-03-23 16:01:41', 'plumbing', 'Completed', 19),
(330, 6, 'pi_3R5r1mP0HU7M7BGv11inptYX', 'gohyiqi1@gmail.com', 110.00, 'MYR', 'Card', 'paid', '2025-03-28', '11:00:00', 'invoices/Invoice_pi_3R5r1mP0HU7M7BGv11inptYX.pdf', '2025-03-23 16:05:37', 'electrical', 'Completed', 53),
(331, 6, 'pi_3R61p3P0HU7M7BGv1lfZbUmS', 'gohyiqi1@gmail.com', 90.00, 'MYR', 'Card', 'paid', '2025-03-25', '06:00:00', 'invoices/Invoice_pi_3R61p3P0HU7M7BGv1lfZbUmS.pdf', '2025-03-24 03:37:13', 'renovation', 'Completed', 107),
(332, 6, 'pi_3R61rbP0HU7M7BGv1yreEZC2', 'gohyiqi1@gmail.com', 85.00, 'MYR', 'Card', 'paid', '2025-03-27', '01:00:00', 'invoices/Invoice_pi_3R61rbP0HU7M7BGv1yreEZC2.pdf', '2025-03-24 03:39:57', 'renovation', 'Completed', 33),
(333, 10, 'pi_3R64puP0HU7M7BGv19TINP1i', 'gohyiqi1@gmail.com', 72.00, 'MYR', 'Card', 'paid', '2025-03-28', '10:00:00', 'invoices/Invoice_pi_3R64puP0HU7M7BGv19TINP1i.pdf', '2025-03-24 06:50:18', 'plumbing', 'Completed', 18),
(335, 10, 'pi_3R6S3LP0HU7M7BGv0jj9M7Qe', 'gohyiqi1@gmail.com', 210.00, 'MYR', 'Card', 'paid', '2025-03-29', '10:00:00', 'invoices/Invoice_pi_3R6S3LP0HU7M7BGv0jj9M7Qe.pdf', '2025-03-25 07:37:43', 'renovation', 'Canceled', 110),
(336, 6, 'pi_3R6Z96P0HU7M7BGv0DmVKm8v', 'gohyiqi1@gmail.com', 157.00, 'MYR', 'Card', 'paid', '2025-03-28', '11:00:00', 'invoices/Invoice_pi_3R6Z96P0HU7M7BGv0DmVKm8v.pdf', '2025-03-25 15:12:08', 'renovation', 'Completed', 116),
(337, 6, 'pi_3R6ZMEP0HU7M7BGv1qNczVI1', 'gohyiqi1@gmail.com', 107.00, 'MYR', 'Card', 'paid', '2025-03-28', '09:00:00', 'invoices/Invoice_pi_3R6ZMEP0HU7M7BGv1qNczVI1.pdf', '2025-03-25 15:25:41', 'electrical', 'Completed', 11),
(338, 6, 'pi_3R6ZaZP0HU7M7BGv1XTU4xq1', 'gohyiqi1@gmail.com', 87.00, 'MYR', 'Card', 'paid', '2025-03-28', '11:00:00', 'invoices/Invoice_pi_3R6ZaZP0HU7M7BGv1XTU4xq1.pdf', '2025-03-25 15:40:30', 'renovation', 'Completed', 99),
(339, 6, 'pi_3R6ZcZP0HU7M7BGv0rvs25k7', 'gohyiqi1@gmail.com', 87.00, 'MYR', 'Card', 'paid', '2025-04-04', '09:00:00', 'invoices/Invoice_pi_3R6ZcZP0HU7M7BGv0rvs25k7.pdf', '2025-03-25 15:42:35', 'renovation', 'Completed', 99),
(340, 6, 'pi_3R6ZekP0HU7M7BGv0NtrnLoQ', 'gohyiqi1@gmail.com', 85.00, 'MYR', 'Card', 'paid', '2025-03-29', '10:00:00', 'invoices/Invoice_pi_3R6ZekP0HU7M7BGv0NtrnLoQ.pdf', '2025-03-25 15:44:49', 'renovation', 'Completed', 112),
(341, 6, 'pi_3R6ZfVP0HU7M7BGv07uI0qLn', 'gohyiqi1@gmail.com', 90.00, 'MYR', 'Card', 'paid', '2025-03-29', '11:00:00', 'invoices/Invoice_pi_3R6ZfVP0HU7M7BGv07uI0qLn.pdf', '2025-03-25 15:45:36', 'renovation', 'Canceled', 107),
(342, 6, 'pi_3R6bhoP0HU7M7BGv0DKnDgbe', 'gohyiqi1@gmail.com', 90.00, 'MYR', 'Card', 'paid', '2025-03-28', '10:00:00', 'invoices/Invoice_pi_3R6bhoP0HU7M7BGv0DKnDgbe.pdf', '2025-03-25 17:56:07', 'renovation', 'Completed', 107),
(343, 6, 'pi_3R6bk6P0HU7M7BGv1icV44Kn', 'gohyiqi1@gmail.com', 72.00, 'MYR', 'Card', 'paid', '2025-03-29', '10:00:00', 'invoices/Invoice_pi_3R6bk6P0HU7M7BGv1icV44Kn.pdf', '2025-03-25 17:58:38', 'plumbing', 'Completed', 18),
(344, 6, 'pi_3R6blAP0HU7M7BGv1HkELx6U', 'gohyiqi1@gmail.com', 87.00, 'MYR', 'Card', 'paid', '2025-03-28', '09:00:00', 'invoices/Invoice_pi_3R6blAP0HU7M7BGv1HkELx6U.pdf', '2025-03-25 17:59:35', 'electrical', 'Completed', 25),
(345, 6, 'pi_3R6blxP0HU7M7BGv0DdpZFVF', 'gohyiqi1@gmail.com', 107.00, 'MYR', 'Card', 'paid', '2025-03-28', '08:00:00', 'invoices/Invoice_pi_3R6blxP0HU7M7BGv0DdpZFVF.pdf', '2025-03-25 18:00:24', 'electrical', 'Pending', 64),
(346, 14, 'pi_3R6oS2P0HU7M7BGv049x6wjg', 'gohyiqi1@gmail.com', 207.00, 'MYR', 'Card', 'paid', '2025-03-27', '09:00:00', 'invoices/Invoice_pi_3R6oS2P0HU7M7BGv049x6wjg.pdf', '2025-03-26 07:32:42', 'renovation', 'Pending', 103);

-- --------------------------------------------------------

--
-- Table structure for table `technicians`
--

CREATE TABLE `technicians` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `specialty` enum('Plumbing','Renovations','Electrical') NOT NULL,
  `location` enum('Bukit Bintang','Titiwangsa','Setapak','Kampung Baru','Cheras') NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `image` varchar(255) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_booked` tinyint(1) NOT NULL DEFAULT 0,
  `date_available` varchar(20) DEFAULT NULL,
  `time_start` time DEFAULT '09:00:00',
  `time_end` time DEFAULT '21:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technicians`
--

INSERT INTO `technicians` (`id`, `name`, `specialty`, `location`, `rating`, `image`, `phone_number`, `price`, `is_booked`, `date_available`, `time_start`, `time_end`) VALUES
(1, 'Michaelll', 'Plumbing', 'Titiwangsa', 4.5, '67bacd4c0c335_cheras plumbers 6.png', '013-5678902', 105.00, 0, NULL, '09:00:00', '21:00:00'),
(2, 'Max', 'Electrical', 'Titiwangsa', 5.0, '67bacd60cd1c4_pngtree-power-lineman-electrician-png-image_10213754.png', '014-6529015', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(3, 'Dave', 'Plumbing', 'Titiwangsa', 4.8, '67bacdbede9c8_Alex plumbers cheras.png', '017-2345678', 95.00, 0, NULL, '09:00:00', '21:00:00'),
(4, 'John', 'Plumbing', 'Titiwangsa', 4.4, '67bace20a3673_cheras plumber 8.jpg', '019-8765432', 85.00, 0, NULL, '09:00:00', '21:00:00'),
(5, 'Frank', 'Electrical', 'Titiwangsa', 4.5, '67bace56210d1_black electrician.jpg', '012-7154398', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(6, 'Trevor', 'Electrical', 'Titiwangsa', 4.6, '67bacebae8815_electrician.png', '016-4325297', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(7, 'Tom', 'Plumbing', 'Titiwangsa', 4.4, '67bacf1729888_cheras plumbers 4.jpg', '014-1234567', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(8, 'Jessica', 'Electrical', 'Titiwangsa', 5.0, '67bacf42b4fbf_pngtree-female-electrician-electrician-photo-png-image_13634626.png', '011-3784120', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(9, 'Chris', 'Plumbing', 'Titiwangsa', 4.6, '67bacf6b47a58_cheras plumbers 7.webp', '011-9876543', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(10, 'Luca', 'Electrical', 'Titiwangsa', 4.4, '67bacff968844_electrical-technician-working-switchboard-with-fuses_169016-5517.jpg', '014-8595321', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(11, 'Travis', 'Electrical', 'Setapak', 4.6, '67bad13e14d31_pngtree-portrait-of-a-male-worker-in-hardhat-on-white-background-png-image_11690613.png', '019-4523211', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(12, 'Steve', 'Plumbing', 'Titiwangsa', 4.7, '67bad14cf0789_cheras plumbers 5.webp', '010-1234567', 90.00, 0, NULL, '09:00:00', '21:00:00'),
(13, 'Matthew', 'Electrical', 'Setapak', 4.5, '67bad1e50d442_new electrician.png', '011-7892566', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(14, 'Paul', 'Plumbing', 'Titiwangsa', 4.3, '67bad2ade8696_PLUMBERS 21.png', '012-8901234', 70.00, 0, NULL, '09:00:00', '21:00:00'),
(15, 'Mark', 'Plumbing', 'Titiwangsa', 4.8, '67bad232f1494_cheras plumbers 9.png', '013-4567890', 95.00, 0, NULL, '09:00:00', '21:00:00'),
(16, 'Kyrie', 'Electrical', 'Setapak', 4.5, '67bad2bac14af_Electrician (1).jpg', '0137652100', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(17, 'James', 'Plumbing', 'Setapak', 4.7, '67badc86e4201_pudu plumbers 7.jpg', '016-2345678', 85.00, 0, NULL, '09:00:00', '21:00:00'),
(18, 'Alex', 'Plumbing', 'Setapak', 4.3, '67baddf1e8619_pudu plumbers 6.jpg', '017-9012345', 65.00, 0, NULL, '09:00:00', '21:00:00'),
(19, 'Brian', 'Plumbing', 'Setapak', 4.6, '67bade624d238_plumbers 24.webp', '018-5678901', 88.00, 0, NULL, '09:00:00', '21:00:00'),
(20, 'Kevin', 'Plumbing', 'Setapak', 4.8, '67baded1c5acc_plumbers dwangi 1.webp', '019-3456789', 95.00, 0, NULL, '09:00:00', '21:00:00'),
(21, 'Nick', 'Plumbing', 'Setapak', 4.9, '67badf3bd46ff_pudu plumbers 5.jpg', '011-7890123', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(22, 'Ryan', 'Plumbing', 'Setapak', 4.7, '67bae06e93d7c_pudu plumbers 4.webp', '012-6543210', 88.00, 0, NULL, '09:00:00', '21:00:00'),
(23, 'Joe', 'Plumbing', 'Setapak', 4.5, '67bae114970e4_plumbers27.png', '013-2109876', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(24, 'joseph', 'Plumbing', 'Setapak', 5.0, '67bae1a92c1c1_plumbers 32.webp', '014-8765432', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(25, 'Michael', 'Electrical', 'Setapak', 4.7, '67bb2a6d09e2e_joyful-young-male-engineer-wearing-safety-helmet-uniform-looking-camera-keeping-arms-crossed-isolated-white-background_141793-133082.jpg', '016-2854098', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(26, 'David', 'Electrical', 'Setapak', 4.8, '67bb2b1210d8c_worker electrician.jpg', '011-8925433', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(27, 'Leo', 'Electrical', 'Setapak', 5.0, '67bb2c3a17117_Electrician istock.jpg', '018-4820136', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(28, 'Sanchez', 'Electrical', 'Setapak', 4.7, '67bb2dd245f21_old electrician.png', '012-5437288', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(29, 'Vinicius', 'Electrical', 'Setapak', 4.6, '67bb2e77cdc57_Electrician (TT).jpg', '013-4763829', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(30, 'Miguel', 'Electrical', 'Setapak', 5.0, '67bb2f2bd1942_240_F_102693823_wdBBKcSTGR7THvbd19n6ArroB13QYRJK.jpg', '019-5348272', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(31, 'Dwayne', 'Electrical', 'Titiwangsa', 4.5, '67bb301e44e55_Electrician-Essential-Skills.jpg', '018-5437655', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(32, 'Scott', 'Electrical', 'Titiwangsa', 5.0, '67bb31132be61_electrical technology.jpg', '013-7321908', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(33, 'Jake', 'Renovations', 'Titiwangsa', 4.7, '67bb3153a6a4c_083019_IEC-Blog-Image_Capabilities-Electricians_Approved-1.jpg', '018-5432894', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(34, 'Scott', 'Plumbing', 'Bukit Bintang', 4.7, '67bb32460e2d0_plumbers28.png', '016-1098765', 98.00, 0, NULL, '09:00:00', '21:00:00'),
(35, 'Adam', 'Electrical', 'Titiwangsa', 5.0, '67bb3248c92f2_How-To-Become-An-Electrician.jpg', '013-7285674', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(36, 'Foden', 'Electrical', 'Bukit Bintang', 4.9, '67bb330ada250_ELECTRICAL_Jayden-Wolfe_on-the-job_Feb-2021-e1719971482565.jpg', '012-7893456', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(37, 'Danish', 'Plumbing', 'Bukit Bintang', 4.8, '67bb331c829b5_plumbers29.png', '017-5432109', 95.00, 0, NULL, '09:00:00', '21:00:00'),
(38, 'Eric', 'Plumbing', 'Bukit Bintang', 9.9, '67bb344d2545a_plumbers30.webp', '018-3210987', 5.00, 0, NULL, '09:00:00', '21:00:00'),
(39, 'Sergio', 'Electrical', 'Bukit Bintang', 4.4, '67bb353b90fa9_240_F_102696278_uVNMBW8VUbyV8U0L2PnWyiiVqGGjvCvg.jpg', '015-2098546', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(40, 'Kyle', 'Plumbing', 'Bukit Bintang', 4.5, '67bb34d152f0b_plumbers pudu 2.png', '018-3210987', 95.00, 0, NULL, '09:00:00', '21:00:00'),
(41, 'Jack', 'Electrical', 'Bukit Bintang', 4.5, '67bb3636e5cf3_240_F_76932311_kh3cgHk8k1gxBlX8wcAAEY0DAbE4Z279.jpg', '010-7329543', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(42, 'Matt', 'Plumbing', 'Bukit Bintang', 4.9, '67bb36c8bbe43_plumbers31.webp', '019-9876543', 95.00, 0, NULL, '09:00:00', '21:00:00'),
(43, 'Andy', 'Plumbing', 'Bukit Bintang', 4.4, '67bb371a3b197_plumbers32.webp', '011-4567890', 85.00, 0, NULL, '09:00:00', '21:00:00'),
(44, 'Cristiano', 'Electrical', 'Bukit Bintang', 4.7, '67bb37389cbe5_240_F_483516087_zXb6pVHrioKwFWuehMrouqeJg99379Hq.jpg', '010-7543829', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(45, 'Greg', 'Plumbing', 'Bukit Bintang', 4.3, '67bb37cad1493_plumbers33.jpeg', '011-4567890', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(46, 'Bruce', 'Electrical', 'Bukit Bintang', 4.7, '67bb37db33472_240_F_139873940_A5deYbpUgXaG5D04m8Iu9HrWIxazgXRg.jpg', '017-2227439', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(47, 'Rudiger', 'Electrical', 'Bukit Bintang', 4.8, '67bb3938511a1_240_F_102695126_ny57SodbIb0aqCo92aVCyVTZXsSa2dmf.jpg', '013-4637248', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(48, 'Robbin', 'Plumbing', 'Bukit Bintang', 5.0, '67bb39400b289_plumbers34.jpg', '010-7891234', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(49, 'Franklin', 'Electrical', 'Bukit Bintang', 5.0, '67bb3ac1b8894_9e4a64fa65202bd917aeb5163040015e.png', '019-7374892', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(50, 'Aliff', 'Plumbing', 'Kampung Baru', 4.4, '67bb3c71ca7ac_plumbers36.webp', '012-1472583', 65.00, 0, NULL, '09:00:00', '21:00:00'),
(51, 'Neymar', 'Electrical', 'Bukit Bintang', 4.9, '67bb3c7e09713_pngtree-electrician-png-image_17293528.png', '015-2389645', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(52, 'Sam', 'Plumbing', 'Kampung Baru', 4.5, '67bb3ccd61630_plumbers37.webp', '013-3692580', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(53, 'Lewis', 'Electrical', 'Bukit Bintang', 4.7, '67bb3cdca7a53_man-8889170_1280.jpg', '017-8210284', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(54, 'Tony', 'Plumbing', 'Kampung Baru', 4.7, '67bb3d36f3015_plumbers38.webp', '014-2587410', 85.00, 0, NULL, '09:00:00', '21:00:00'),
(55, 'Figo', 'Electrical', 'Kampung Baru', 4.5, '67bb3e3c84623_ai-generated-8810296_1280.jpg', '019-9016437', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(56, 'Beckham', 'Electrical', 'Kampung Baru', 5.0, '67bb3fd20a78b_179-1799115_scottsdale-electrician-trabajador-de-construccion-feliz-hd-png.png', '019-6723562', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(57, 'Bill', 'Plumbing', 'Kampung Baru', 4.5, '67bb40a29b603_plumbers39.webp', '012-1472583', 85.00, 0, NULL, '09:00:00', '21:00:00'),
(58, 'Luke', 'Plumbing', 'Kampung Baru', 4.7, '67bb437e9d9cd_plumbers40.webp', '013-3692580', 95.00, 0, NULL, '09:00:00', '21:00:00'),
(59, 'Josh', 'Plumbing', 'Kampung Baru', 5.0, '67bb44ff1194d_plumbersdwangi5.jpg', '014-2587410', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(60, 'Roberto', 'Electrical', 'Kampung Baru', 4.7, '67bb4500c93a4_240_F_52997815_sYZNq1MDNqAWgvLzkvVqrbXYh6iXBE1u.jpg', '015-7423593', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(61, 'Kenneth', 'Electrical', 'Kampung Baru', 4.6, '67bb45e33705c_lateffe_wright_electrical_r850x580.jpg', '017-3627492', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(62, 'Harry ', 'Plumbing', 'Kampung Baru', 4.6, '67bb454127e23_pudu plumbers 4.webp', '016-7418520', 85.00, 0, NULL, '09:00:00', '21:00:00'),
(63, 'David', 'Plumbing', 'Kampung Baru', 4.5, '67bb45c71069c_pudu plumbers 8.png', '017-8529630', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(64, 'Thomas', 'Electrical', 'Kampung Baru', 4.7, '67bb465b90b62_Electrical-Engineering-Technology-gtc12.png', '010-7483929', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(65, 'Kristoff', 'Electrical', 'Kampung Baru', 5.0, '67bb46b0dc071_become-an-electrician-2.jpg', '012-6410103', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(66, 'Pogba', 'Electrical', 'Kampung Baru', 4.6, '67bb471b125f2_benefits-of-becoming-an-electrician.jpg', '014-1101526', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(67, 'Thierry', 'Electrical', 'Kampung Baru', 4.8, '67bb478719369_electrician (2).jpg', '016-6482139', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(68, 'Edward', 'Electrical', 'Kampung Baru', 4.6, '67bb49e045289_Apprentice-Opening.jpg', '015-7182910', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(69, 'Oliver', 'Electrical', 'Cheras', 4.7, '67bb4ae880b1e_images.jpg', '012-6582104', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(70, 'Theodore', 'Electrical', 'Cheras', 4.5, '67bbe5967141c_Electrician (TT) 2.jpg', '018-1237583', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(71, 'Walker', 'Electrical', 'Cheras', 4.7, '67bbe60a2634c_istockphoto-1268557129-612x612.jpg', '017-6472913', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(72, 'Finn', 'Electrical', 'Cheras', 5.0, '67bbe67ed32c8_Electrician.jpg', '012-6472842', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(73, 'Calvin', 'Electrical', 'Cheras', 4.9, '67bbe6fa91213_electrician pipol.jpeg', '011-8290138', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(74, 'Gary', 'Plumbing', 'Cheras', 5.0, '67bbe79150cbc_pudu plumbers 9.jpg', '018-9637410', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(75, 'Robbin', 'Electrical', 'Cheras', 4.5, '67bbe7d4dc502_electro people.jpg', '019-2642812', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(76, 'Rick', 'Plumbing', 'Cheras', 4.8, '67bbe80f687df_plumbers pudu 2.png', '019-1479630', 95.00, 0, NULL, '09:00:00', '21:00:00'),
(77, 'Nolan', 'Electrical', 'Cheras', 4.7, '67bbe94e8424f_pngtree-power-lineman-telephone-repairman-electrician-png-image_13381254.png', '010-6482193', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(78, 'Peter', 'Plumbing', 'Cheras', 4.5, '67bbe8db73399_240_F_102693823_wdBBKcSTGR7THvbd19n6ArroB13QYRJK.jpg', '019-1479630', 85.00, 0, NULL, '09:00:00', '21:00:00'),
(79, 'Aurora', 'Electrical', 'Cheras', 4.6, '67bbe9c98e943_beautiful-female-electrician-with-multimeter-on-white-background-2G04G65.jpg', '012-8392040', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(80, 'Harvey', 'Electrical', 'Cheras', 4.8, '67bbea45763aa_workman-with-his-arms-crossed-white-background.jpg', '011-2893940', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(81, 'Sean', 'Plumbing', 'Cheras', 4.8, '67bbea40dd466_plumbers 32.webp', '011-2583690', 120.00, 0, NULL, '09:00:00', '21:00:00'),
(82, 'Bob', 'Renovations', 'Titiwangsa', 4.8, '67bbeadb5aad8_builder-man-construction-vest-safety-helmet-holding-wrench-looking-camera-with-confident-smile-face-standing-orange-background_141793-118646.jpg', '010-3848293', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(83, 'Aaron', 'Renovations', 'Titiwangsa', 4.5, '67bbeb4ad4087_front-view-worker-with-protective-glasses-hard-hat_23-2148773446.jpg', '019-7829472', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(84, 'Gabriel', 'Renovations', 'Titiwangsa', 5.0, '67bbeb84d2f88_close-up-portrait-smiling-construction-worker_23-2148233742.jpg', '012-8479013', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(85, 'Blake', 'Renovations', 'Titiwangsa', 4.6, '67bbebcf9f17a_serious-man-builder-engineer-wears-construction-safety-helmet-uniform-glasses-looks-confidently-ready-work-isolated-pink-wall-self-assured-workman-construction-worker_273609-48699.jpg', '015-3772329', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(86, 'Seth', 'Renovations', 'Titiwangsa', 5.0, '67bbeee283220_young-builder-man-wearing-construction-uniform-safety-helmet-happily-shows-power-with-fists_141793-33636.jpg', '013-7581845', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(87, 'Felix', 'Renovations', 'Titiwangsa', 4.4, '67bbef8cac90c_bearded-builder-man-construction-uniform-safety-helmet-holding-clipboard-with-pen-looking-smiling-confident_141793-111577.jpg', '014-5738293', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(88, 'Draco', 'Renovations', 'Titiwangsa', 4.5, '67bbeff021767_top-view-confident-male-constructor-warning-vest-wearing-safety-helmet-holding-blank-gray-wave-wall_140725-150056.jpg', '018-6401902', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(89, 'Conrad', 'Renovations', 'Titiwangsa', 5.0, '67bbf0504aa13_construction-worker-isolated-white_392895-159160.jpg', '010-2884399', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(90, 'Carl', 'Plumbing', 'Cheras', 4.9, '67bbf1e951b44_plumbers 42.jpg', '016-5827410', 95.00, 0, NULL, '09:00:00', '21:00:00'),
(91, 'Zach', 'Plumbing', 'Cheras', 4.7, '67bbf2d599be8_plumbers 44.jpg', '017-3691478', 88.00, 0, NULL, '09:00:00', '21:00:00'),
(92, 'Ivan', 'Renovations', 'Setapak', 4.7, '67bbf36194418_young-bearded-handsome-engineer-standing-with-crossed-arms-smiling-friendly-isolated-white-wall_141793-15792.jpg', '010-7320100', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(93, 'Bruce', 'Plumbing', 'Cheras', 4.5, '67bbf37530e72_plumbers 45.webp', '6019-258369', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(94, 'Max', 'Plumbing', 'Cheras', 4.9, '67bbf532eede4_plumbers 47.jpg', '011-1478520', 98.00, 0, NULL, '09:00:00', '21:00:00'),
(95, 'Fred', 'Plumbing', 'Cheras', 4.7, '67bbf5e19e461_plumbers49.jpg', '014-9637412', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(96, 'Natlan', 'Renovations', 'Setapak', 4.8, '67bbf7faee1c4_man-wearing-yellow-hard-hat-yellow-vest-with-word-caution-it_1022967-35624.jpg', '019-6371892', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(97, 'Kyle', 'Renovations', 'Setapak', 4.5, '67bbf91a71b1c_workman-with-his-arms-crossed.jpg', '016-7489192', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(98, 'Magnus', 'Renovations', 'Setapak', 5.0, '67bbfa42c6c3d_construction-worker-wearing-white-safety-helmet-vest-with-smile-face-pointing-with-palm-hand-copy-space-blue-isolated_141793-8546.jpg', '011-2879442', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(99, 'Lee', 'Renovations', 'Setapak', 4.5, '67bc1c39b14c2_portrait-man-working-as-engineer_23-2151229980.jpg', '013-9992020', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(100, 'Bucky', 'Renovations', 'Setapak', 4.5, '67bc1d754d6a1_handsome-man-engineer-building-protective-helmet-gray-wall-serious-focused-face_343596-6764.jpg', '014-7291019', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(101, 'Andres', 'Renovations', 'Setapak', 5.0, '67bc1f8ecbddf_front-view-male-builder-uniform-white-wall_140725-146601.jpg', '018-0001929', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(102, 'Stephen', 'Renovations', 'Setapak', 4.8, '67bc204933058_unsure-young-male-engineer-wearing-safety-helmet-uniform-looking-camera-keeping-arms-crossed-isolated-white-background.jpg', '017-1201012', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(103, 'Xavier', 'Renovations', 'Setapak', 5.0, '67bc2105c8661_portrait-young-foreman-orange-work-clothes-yellow-hardhat-with-pencil-ear-thoughtfully-looking-aside-with-scaffolding-background_574295-1775.jpg', '019-7789191', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(104, 'Levi', 'Renovations', 'Bukit Bintang', 4.7, '67bc22ef1893d_young-builder-man-wearing-white-helmet-yellow-vest-looking-aside-holding-clipboard-pen-standing-blue-isolated_141793-8550.jpg', '015-8193891', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(105, 'Garrett', 'Renovations', 'Bukit Bintang', 4.5, '67bc25be5e2d7_architect-reflective-vest-holding-rolls_651396-966.jpg', '019-3939292', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(106, 'Victor', 'Renovations', 'Bukit Bintang', 4.8, '67bc271af087e_young-man-civil-engineer-safety-hat-isolated-white-background.jpg', '019-2848292', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(107, 'Tyler', 'Renovations', 'Bukit Bintang', 4.6, '67bc34aae4c6b_construction-worker-isolated-white_392895-159160.jpg', '017-3998293', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(108, 'Alberto', 'Renovations', 'Bukit Bintang', 4.7, '67bc406b7cded_handsome-young-man-uniform-standing-white-wall-high-quality-photo_114579-41586.jpg', '013-2773725', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(109, 'Arthur', 'Renovations', 'Bukit Bintang', 4.8, '67bc423c06916_young-construction-worker-safety-helmet-glasses_176474-86010.jpg', '016-7372818', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(110, 'Dallas', 'Renovations', 'Bukit Bintang', 5.0, '67bc434a65454_pngtree-african-american-construction-worker-over-isolated-white-background-png-image_14885212.png', '017-6472812', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(111, 'Kendrick', 'Renovations', 'Bukit Bintang', 4.6, '67bc44c133907_240_F_69173178_ZYytIRSgszOfLXZSDc5Hc7oWvNlx89wX.jpg', '017-3627213', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(112, 'Daniel', 'Renovations', 'Bukit Bintang', 4.4, '67bc45f6f1f1c_240_F_52997815_sYZNq1MDNqAWgvLzkvVqrbXYh6iXBE1u.jpg', '019-2928387', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(113, 'Jonathan', 'Renovations', 'Kampung Baru', 4.7, '67bc478531d28_pngtree-cheerful-construction-worker-in-orange-uniform-png-image_14654449.png', '018-5223455', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(114, 'Benjamin', 'Renovations', 'Kampung Baru', 4.7, '67bc4a56b5933_man-wearing-yellow-uniform-with-number-1-it_1022967-35603.jpg', '015-9292672', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(115, 'Nicholas', 'Renovations', 'Kampung Baru', 5.0, '67bc4bc05ec5e_young-builder-man-wearing-construction-uniform-safety-helmet-happy-applauds_141793-33640.jpg', '016-3829292', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(116, 'Ederson', 'Renovations', 'Kampung Baru', 4.8, '67bc4d3eec519_pngtree-construction-worker-engineer-png-image_11917231.png', '010-9382939', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(117, 'Zidane', 'Renovations', 'Kampung Baru', 4.6, '67bc4d960bf53_159-1596209_construction-worker-png-transparent-construction-worker-png-png.png', '014-7382819', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(118, 'Sarah', 'Renovations', 'Kampung Baru', 5.0, '67bc4e244336b_pngtree-young-female-construction-engineer-with-phone-contractor-construction-consultant-png-image_11771457.png', '015-2938291', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(119, 'Laura', 'Renovations', 'Kampung Baru', 4.9, '67bc4e6973655_pngtree-woman-architect-with-architectural-blueprint-in-hands-png-image_14724362.png', '017-2837198', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(120, 'Jennie', 'Renovations', 'Kampung Baru', 4.5, '67bc4ebfeda2b_pngtree-young-professional-on-the-phone-calling-picture-image_13287674.png', '016-3728195', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(121, 'Norman', 'Renovations', 'Kampung Baru', 4.8, '67bc4f77581a3_pngimg.com - industrial_worker_PNG11464.png', '011-3910395', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(122, 'Conan', 'Renovations', 'Cheras', 4.7, '67bc4fbfd72f7_pngtree-industrial-worker-and-oil-rig-png-image_11714329.png', '019-3728829', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(123, 'Noah', 'Plumbing', 'Titiwangsa', 4.8, '67bc4fe7ab077_plumbers50.png', '012-5837419', 85.00, 0, NULL, '09:00:00', '21:00:00'),
(124, 'Alan', 'Renovations', 'Cheras', 4.9, '67bc521eb26a7_construction-card-principal-contractor.png', '013-8382939', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(125, 'Kante', 'Renovations', 'Cheras', 4.6, '67bc528425591_265-2657765_general-contractor-panel-presentation-construction-worker-malaysia.png', '017-4728191', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(126, 'Simon', 'Renovations', 'Cheras', 4.5, '67bc52c694e14_pngtree-construction-worker-driver-photo-png-image_13690451.png', '018-3478284', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(127, 'Darren', 'Plumbing', 'Setapak', 5.0, '67bc52c820049_plumbers53.jpg', '014-3692587', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(128, 'Ethan', 'Plumbing', 'Bukit Bintang', 4.6, '67bc536366dd9_plumbers56.png', '016-8527410', 88.00, 0, NULL, '09:00:00', '21:00:00'),
(129, 'Rodriguez', 'Renovations', 'Cheras', 4.8, '67bc53318972b_pngtree-3d-construction-worker-in-pose-on-transparent-background-png-image_17411560.png', '011-9382929', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(130, 'Bernard', 'Renovations', 'Cheras', 4.7, '67bc537423356_stock-photo-pensive-construction-worker-tool-belt-holding-clipboard-looking-away-isolated.jpg', '016-4824872', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(131, 'Jeffrey', 'Renovations', 'Cheras', 4.6, '67bc53c6e9db3_lovepik-male-construction-worker-png-image_401237797_wh1200.png', '017-4824992', 80.00, 0, NULL, '09:00:00', '21:00:00'),
(132, 'Maverick', 'Renovations', 'Cheras', 5.0, '67bc5436c9c35_pngtree-construction-worker-builder-man-png-image_11930619.png', '013-4796070', 200.00, 0, NULL, '09:00:00', '21:00:00'),
(133, 'Henry', 'Plumbing', 'Kampung Baru', 4.4, '67bc544af09aa_plumbers59.webp', '019-9632584', 75.00, 0, NULL, '09:00:00', '21:00:00'),
(134, 'Niall', 'Renovations', 'Cheras', 4.7, '67bc54d004b29_png-clipart-personal-protective-equipment-architectural-engineering-construction-site-safety-occupational-safety-and-health-construction-worker-building-building-general-contractor.png', '016-5892915', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(135, 'Henry', 'Plumbing', 'Cheras', 4.8, '67bc54db0c571_plumbers58.jpg', '011-3691478', 95.00, 0, NULL, '09:00:00', '21:00:00'),
(136, 'Lily', 'Renovations', 'Titiwangsa', 4.7, '67bd8ce6aed50_Can-Women-Help-Plug-The-Labor-Shortage-in-Construction-Young-woman-construction-manager-directing-traffic-in-front-of-project.-Successful-woman-using-tablet-to-help-manage-crew..jpg', '017-3929794', 100.00, 0, NULL, '09:00:00', '21:00:00'),
(137, 'John', 'Electrical', 'Titiwangsa', 4.9, '67d3dd5d515dd_Electrician (Titiwangsa).png', '016-5348777', 150.00, 0, NULL, '09:00:00', '21:00:00'),
(138, 'nAQIB', 'Plumbing', 'Setapak', 1.0, '67e39e5535211_ERD.drawio (1).png', '0198778368', 10.00, 0, NULL, '09:00:00', '21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `technician_availability`
--

CREATE TABLE `technician_availability` (
  `id` int(11) NOT NULL,
  `technician_id` int(11) NOT NULL,
  `booking_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `technician_availability`
--

INSERT INTO `technician_availability` (`id`, `technician_id`, `booking_date`) VALUES
(121, 64, '2025-03-28'),
(122, 103, '2025-03-27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` date NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'customer',
  `gender` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `phone_number` varchar(11) NOT NULL,
  `otp_code` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `status` enum('inactive','active') NOT NULL DEFAULT 'inactive',
  `verification_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `created_at`, `role`, `gender`, `address`, `profile_picture`, `phone_number`, `otp_code`, `otp_expiry`, `status`, `verification_token`) VALUES
(1, 'Goh Yi Qi', '$2y$10$ikUhz4fy83DIhkXefedMhOcz6/u3CS4Bv9f0APTPJfyabtgQ6vZ0W', 'gohyiqi1@gmail.com', '2025-02-20', 'admin', '', '25,Jalan Ketapang 4 ,Taman Rinting', '67d7ce1d3724b_67b1cd3215492_67a987dcdc0b4_67a6d9db17968_lalala123.jpeg', '0198778368', NULL, NULL, 'active', NULL),
(2, 'Divyan Kumar', '$2y$10$bvqZCLOzbYy2QnpAhvFSguBUq1WffNbasxWSPpk.zQnC/YH3QDDXK', 'harry.idk708@gmail.com', '2025-02-23', 'staff', '', '', '', '018-3891010', NULL, NULL, 'active', NULL),
(3, 'Goh Yi Qi', '$2y$10$U4smZXAf05FE2Cpn2S0YH.2hjQ703oftQw3EoBE4IECiw5x5wA/K2', 'tanresourcesonlineservice@gmail.com', '2025-03-06', 'staff', '', 'Residensi UTM', '67e3ba58881fa_67b1cd3215492_67a987dcdc0b4_67a6d9db17968_lalala123.jpeg', '0127969288', '686587', '0000-00-00 00:00:00', 'active', NULL),
(4, 'Muhammad Naqib', '$2y$10$yhdDMPzYXWVY4jrTSIhR6.touOj4eJsSXYLx7U.4fTLXSyG5t1GhO', 'naqibsallehh22@gmail.com', '2025-03-10', 'customer', '', '', NULL, '', '967717', '2025-03-10 17:51:44', 'active', 'bea7fbc96bf9a3ff59c41d9d937e2a1e5751dcd0e962725b71142b302505f68e'),
(5, 'SyahinSyabil', '$2y$10$PULV9jAeSgqDmt1mZDR0u.Xkg.ArAb1Ld7cYdD0V.Ci76AYxlStGO', 'syahinsyabil@gmail.com', '0000-00-00', 'staff', '', 'Jalan Rahmat 1, Kampung Teras Jernang, Bandar Baru Bangi, Selangor, Malaysia', '67d6508e19f8e_zayn.jpg', '0126934077', NULL, NULL, 'active', NULL),
(7, 'CHUNG SHAN JIE', '$2y$10$jFS1Hta3I6GtZiUinxrhXOgZITmo9hWVGUNnEtAF4W5qJnDY60t6y', 'chungshanjie1810@gmail.com', '2025-03-22', 'customer', '', '', NULL, '', '357789', '2025-03-22 22:50:52', 'active', 'f95dce42243dba5ff43d0b2e7d5eebc50ff660aa51c5af12c25a695b72ce0488'),
(8, 'Divyan Kumar', '$2y$10$EJ71Wl2rSF5n6XwH9BNLc.DRIBbgkf.krAsYWqXAtJ6izbrvtRpQ.', 'divyankumar14@gmail.com', '2025-03-24', 'customer', '', '', NULL, '', NULL, NULL, 'active', 'b04fb03f224481db6e04cf845b69f459cfde2b2ad80f3b92acfbe501fdf1434a'),
(11, 'Divyan Kumar', '$2y$10$4rc77us7n3lMo05EhS.nn.cAW8161M.8yXAqKxLVw2G1ACsb/ktOe', 'divyankumarpro@gmail.com', '2025-03-26', 'customer', '', '', NULL, '', '917672', '2025-03-26 12:11:50', 'active', '91710bb3e971bc14c143dc1e1e635dc8d25be7dfb54e9cab30b901987fd035d2'),
(12, 'harry', '$2y$10$BAL8Z1AbLHY3YxzUgRgEhOZFGD..h3jAB1hJD0QfQOPdtmuxixwt.', 'harry.143708@gmail.com', '2025-03-26', 'customer', '', '', NULL, '', '645908', '2025-03-26 12:34:35', 'active', '38451c3f3e4e5100c666332f3081ae4a0f672176ef1e4bfba3252be55297a648'),
(13, 'Goh', '$2y$10$pqFo1JGdKCN0p98w.belRO9pp.9oh77x0J1Cml4a0OjLV3QJAa/1i', 'gohyiqi1@gmail.com', '0000-00-00', 'staff', '', '', '', '22', NULL, NULL, 'inactive', NULL),
(14, 'Goh Yi Qi', '$2y$10$EDujwKX/9k8udnZoWuaUyuYVUHNL3TykRK515QO7MuSdUFqftR.oa', 'yiqigoh90@gmail.com', '2025-03-26', 'customer', '', '', NULL, '', '004747', '2025-03-26 15:36:14', 'active', 'adde0e88ebbf223a60c60ef5316599dfe44c52b9ccee190dd526119a70cdb2ae'),
(16, 'Muhammad Naqib', '$2y$10$gfj06p2yrWJMervqpYHB4uMzndPUp/idFbueCXLME/CzKrW.1idXO', 'naqibsallehh24@gmail.com', '2025-03-26', 'customer', '', '', NULL, '', NULL, NULL, 'active', 'a72262ee9b8c88ed78005c0b541bc7d4a673482646d2ecea0de6a2c4352f8d37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_form_submissions`
--
ALTER TABLE `contact_form_submissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_transaction_id` (`transaction_id`);

--
-- Indexes for table `technicians`
--
ALTER TABLE `technicians`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `technician_availability`
--
ALTER TABLE `technician_availability`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_technician_date` (`technician_id`,`booking_date`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_form_submissions`
--
ALTER TABLE `contact_form_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=348;

--
-- AUTO_INCREMENT for table `technicians`
--
ALTER TABLE `technicians`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `technician_availability`
--
ALTER TABLE `technician_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `technician_availability`
--
ALTER TABLE `technician_availability`
  ADD CONSTRAINT `technician_availability_ibfk_1` FOREIGN KEY (`technician_id`) REFERENCES `technicians` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
