-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 12, 2023 at 08:07 AM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_grt512`
--

-- --------------------------------------------------------

--
-- Table structure for table `grt_admin_activity_logs`
--

CREATE TABLE `grt_admin_activity_logs` (
  `id` int(11) NOT NULL,
  `text` text,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `grt_admin_activity_logs`
--

INSERT INTO `grt_admin_activity_logs` (`id`, `text`, `timestamp`) VALUES
(58, '<strong>Sujan Shrestha</strong> logged in. <strong>(IP: ::1)</strong>', '2022-06-23 18:27:56'),
(59, '<strong>Sujan Shrestha</strong> logged in. <strong>(IP: ::1)</strong>', '2022-06-27 14:28:21'),
(60, '<strong>Sujan Shrestha</strong> deleted a news <strong>(ID: 8)</strong>', '2022-06-27 14:28:28'),
(61, '<strong>Sujan Shrestha</strong> deleted a news <strong>(ID: 7)</strong>', '2022-06-27 14:28:32'),
(62, '<strong>Sujan Shrestha</strong> deleted a quote <strong>(ID: 3)</strong>', '2022-06-27 14:41:02'),
(63, '<strong>Sujan Shrestha</strong> deleted a quote <strong>(ID: 4)</strong>', '2022-06-27 14:41:05'),
(64, '<strong>Sujan Shrestha</strong> deleted a quote <strong>(ID: 5)</strong>', '2022-06-27 14:41:07'),
(65, '<strong>Sujan Shrestha</strong> posted a news <a href=\"http://localhost/news/some-test-title-idk-what-is-this\" target=\"_blank\">http://localhost/news/some-test-title-idk-what-is-this</a>', '2022-06-27 15:21:42'),
(66, '<strong>Sujan Shrestha</strong> added a new quote <strong>(ID: 6)</strong>', '2022-06-27 15:24:15'),
(67, '<strong>Sujan Shrestha</strong> deleted a quote <strong>(ID: 6)</strong>', '2022-06-27 15:27:38'),
(68, '<strong>Sujan Shrestha</strong> deleted a trading analysis <strong>(ID: 6)</strong>', '2022-06-27 15:27:48'),
(69, '<strong>Sujan Shrestha</strong> logged in. <strong>(IP: ::1)</strong>', '2023-09-12 12:54:00'),
(70, '<strong>Sujan Shrestha</strong> deleted a news <strong>(ID: 9)</strong>', '2023-09-12 12:58:20'),
(71, '<strong>Sujan Shrestha</strong> logged in. <strong>(IP: ::1)</strong>', '2023-09-12 13:01:06'),
(72, '<strong>Sujan Shrestha</strong> logged in. <strong>(IP: ::1)</strong>', '2023-09-12 13:13:25'),
(73, '<strong>Sujan Shrestha</strong> logged in. <strong>(IP: ::1)</strong>', '2023-09-12 13:20:07'),
(74, '<strong>Sujan Shrestha</strong> added a new quote <strong>(ID: 1)</strong>', '2023-09-12 13:25:53'),
(75, '<strong>Sujan Shrestha</strong> deleted a quote <strong>(ID: 1)</strong>', '2023-09-12 13:26:46'),
(76, '<strong>Sujan Shrestha</strong> added a new quote <strong>(ID: 2)</strong>', '2023-09-12 13:27:48'),
(77, '<strong>Sujan Shrestha</strong> deleted a quote <strong>(ID: 2)</strong>', '2023-09-12 13:49:33'),
(78, '<strong>Sujan Shrestha</strong> added a new quote <strong>(ID: 3)</strong>', '2023-09-12 13:49:41'),
(79, '<strong>Sujan Shrestha</strong> deleted a quote <strong>(ID: 3)</strong>', '2023-09-12 13:49:46'),
(80, '<strong>Sujan Shrestha</strong> added a new quote <strong>(ID: 4)</strong>', '2023-09-12 13:49:55'),
(81, '<strong>Sujan Shrestha</strong> added a new quote <strong>(ID: 5)</strong>', '2023-09-12 13:50:33');

-- --------------------------------------------------------

--
-- Table structure for table `grt_news`
--

CREATE TABLE `grt_news` (
  `id` int(11) NOT NULL,
  `title` varchar(128) DEFAULT NULL,
  `url_title` varchar(128) DEFAULT NULL,
  `body` text,
  `posted_date_unix` int(11) DEFAULT '0',
  `author_id` int(11) DEFAULT '0',
  `thumbnail_url` varchar(256) DEFAULT NULL,
  `editor_id` int(11) NOT NULL DEFAULT '0',
  `edited_date_unix` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `grt_payments`
--

CREATE TABLE `grt_payments` (
  `id` varchar(32) NOT NULL,
  `transaction_id` varchar(32) DEFAULT NULL,
  `customer_id` int(11) DEFAULT '0',
  `amount` varchar(5) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `grt_quotes`
--

CREATE TABLE `grt_quotes` (
  `id` int(11) NOT NULL,
  `author` varchar(42) DEFAULT NULL,
  `text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `grt_quotes`
--

INSERT INTO `grt_quotes` (`id`, `author`, `text`) VALUES
(4, 'Yann Martel', 'To choose doubt as a philosophy of life is akin to choosing immobility as a means of transportation'),
(5, 'Angie Thomas', 'What’s the point of having a voice if you’re gonna be silent in those moments you shouldn’t be?');

-- --------------------------------------------------------

--
-- Table structure for table `grt_temp_pages`
--

CREATE TABLE `grt_temp_pages` (
  `id` int(11) NOT NULL,
  `requested_for` varchar(32) DEFAULT NULL,
  `token` varchar(32) DEFAULT NULL,
  `type` varchar(15) DEFAULT NULL,
  `timestamp` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `grt_trading_analysis`
--

CREATE TABLE `grt_trading_analysis` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `url_title` varchar(100) DEFAULT NULL,
  `body` text,
  `posted_date_unix` int(11) NOT NULL DEFAULT '0',
  `author_id` int(11) NOT NULL DEFAULT '0',
  `thumbnail_url` varchar(256) DEFAULT NULL,
  `editor_id` int(11) DEFAULT '0',
  `edited_date_unix` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `grt_users`
--

CREATE TABLE `grt_users` (
  `id` int(11) NOT NULL,
  `uid_token` varchar(32) DEFAULT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(32) DEFAULT NULL,
  `temporary_email` varchar(32) DEFAULT NULL,
  `joined_date_unix` int(11) NOT NULL DEFAULT '0',
  `username` varchar(20) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `avatar_color` varchar(8) DEFAULT NULL,
  `session` varchar(32) DEFAULT NULL,
  `ip` varchar(17) DEFAULT NULL,
  `mentor_id` int(11) NOT NULL DEFAULT '0',
  `last_logged_ip` varchar(17) DEFAULT NULL,
  `last_active_unix` int(11) NOT NULL DEFAULT '0',
  `email_verified` tinyint(4) NOT NULL DEFAULT '0',
  `paid_membership` tinyint(4) NOT NULL DEFAULT '0',
  `email_verification_type` varchar(10) DEFAULT NULL,
  `avatar_url` varchar(256) DEFAULT 'none',
  `is_admin` tinyint(4) NOT NULL DEFAULT '0',
  `is_mentor` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `grt_users`
--

INSERT INTO `grt_users` (`id`, `uid_token`, `first_name`, `last_name`, `email`, `temporary_email`, `joined_date_unix`, `username`, `password`, `avatar_color`, `session`, `ip`, `mentor_id`, `last_logged_ip`, `last_active_unix`, `email_verified`, `paid_membership`, `email_verification_type`, `avatar_url`, `is_admin`, `is_mentor`) VALUES
(15, '9SNc6h5yYmpStJSTtmTG5dDC6wLRph', 'Sujan', 'Shrestha', 'sujan.shrestha199@gmail.com', NULL, 1655888215, 'sjns19', '$2y$10$rLuI9UXHmxDvcmu6b/95RO1UV.6DKVgxp2bBZSZdJ.yGpo5TUU5a.', '#61053e', 'v6vqlj2c24j52ggl32fllgs2jp', '::1', 0, '::1', 1694504107, 1, 0, NULL, 'default-avatar.png', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `grt_user_activity_logs`
--

CREATE TABLE `grt_user_activity_logs` (
  `id` int(11) NOT NULL,
  `text` text,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grt_admin_activity_logs`
--
ALTER TABLE `grt_admin_activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grt_news`
--
ALTER TABLE `grt_news`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `grt_news` ADD FULLTEXT KEY `body` (`body`);
ALTER TABLE `grt_news` ADD FULLTEXT KEY `title` (`title`);
ALTER TABLE `grt_news` ADD FULLTEXT KEY `body_2` (`body`);
ALTER TABLE `grt_news` ADD FULLTEXT KEY `title_2` (`title`);
ALTER TABLE `grt_news` ADD FULLTEXT KEY `title_3` (`title`,`body`);

--
-- Indexes for table `grt_payments`
--
ALTER TABLE `grt_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grt_quotes`
--
ALTER TABLE `grt_quotes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grt_temp_pages`
--
ALTER TABLE `grt_temp_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grt_trading_analysis`
--
ALTER TABLE `grt_trading_analysis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grt_users`
--
ALTER TABLE `grt_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grt_user_activity_logs`
--
ALTER TABLE `grt_user_activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grt_admin_activity_logs`
--
ALTER TABLE `grt_admin_activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `grt_news`
--
ALTER TABLE `grt_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `grt_quotes`
--
ALTER TABLE `grt_quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `grt_temp_pages`
--
ALTER TABLE `grt_temp_pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grt_trading_analysis`
--
ALTER TABLE `grt_trading_analysis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grt_users`
--
ALTER TABLE `grt_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `grt_user_activity_logs`
--
ALTER TABLE `grt_user_activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
