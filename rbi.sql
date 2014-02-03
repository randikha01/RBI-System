-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2014 at 07:38 AM
-- Server version: 5.5.32
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rbi`
--
-- --------------------------------------------------------

--
-- Table structure for table `tbl_adminusers_auth`
--

CREATE TABLE IF NOT EXISTS `tbl_adminusers_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminusers_level_id` int(11) NOT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `username` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `password` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_admingroups_auth` (`adminusers_level_id`,`publish`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `tbl_adminusers_auth`
--

INSERT INTO `tbl_adminusers_auth` (`id`, `adminusers_level_id`, `publish`, `username`, `password`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 1, 'Publish', 'superadmin', '085b127873f972ec64a1f30f6952894e', 1, 1, '2013-11-01 19:16:04', '2012-11-09 18:26:23'),
(2, 2, 'Publish', 'administrator', '63ee4e9b42a4d9afcdc6222d974a240b', 1, 1, '2013-11-01 19:16:04', '2012-11-21 17:54:39');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_adminusers_level`
--

CREATE TABLE IF NOT EXISTS `tbl_adminusers_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `parent_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `tbl_adminusers_level`
--

INSERT INTO `tbl_adminusers_level` (`id`, `title`, `publish`, `parent_id`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 'Level 1', 'Publish', 0, 1, 0, '2014-01-23 22:28:56', '2012-11-12 11:45:10'),
(2, 'Level 2', 'Publish', 0, 1, 0, '2014-01-23 22:29:05', '2012-11-09 17:58:07');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_configs`
--

CREATE TABLE IF NOT EXISTS `tbl_configs` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `meta_title` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `meta_keyword` text COLLATE latin1_general_ci,
  `meta_description` text COLLATE latin1_general_ci,
  `meta_author` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `publish_auth` enum('No','Yes') COLLATE latin1_general_ci NOT NULL DEFAULT 'No',
  `user_id` int(11) NOT NULL,
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_configs`
--

INSERT INTO `tbl_configs` (`id`, `meta_title`, `meta_keyword`, `meta_description`, `meta_author`, `publish_auth`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 'tes', 'tes', 'tes', 'tes', '', 1, 1, '2013-10-29 21:59:48', '2013-10-29 16:59:48');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item_object`
--

CREATE TABLE IF NOT EXISTS `tbl_item_object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_plant_fol_item` int(11) NOT NULL,
  `obj_tag_no` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `management_id` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `desc_` text COLLATE latin1_general_ci NOT NULL,
  `drawing_ref` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `sheet` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `rev` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `id_eq_subclass` int(11) NOT NULL,
  `miss_physical_tag` set('no','yes') COLLATE latin1_general_ci NOT NULL,
  `miss_virtual_tag` set('no','yes') COLLATE latin1_general_ci NOT NULL,
  `id_eq_cat` int(11) NOT NULL,
  `ex_service` set('no','yes') COLLATE latin1_general_ci NOT NULL,
  `id_ex_type` int(11) NOT NULL,
  `cmms_status` int(11) NOT NULL,
  `work_order` set('no','yes') COLLATE latin1_general_ci NOT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_item_object`
--

INSERT INTO `tbl_item_object` (`id`, `id_plant_fol_item`, `obj_tag_no`, `management_id`, `desc_`, `drawing_ref`, `sheet`, `rev`, `id_eq_subclass`, `miss_physical_tag`, `miss_virtual_tag`, `id_eq_cat`, `ex_service`, `id_ex_type`, `cmms_status`, `work_order`, `publish`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 1, 'test obj tag no e', 'test management id e', 'test desc e', 'test drawing e', 'test sheet e', 'test rev e', 0, 'yes', 'no', 2, 'yes', 12, 1, 'no', 'Not Publish', 1, 1, '2014-01-29 08:37:28', '2014-01-29 09:35:10');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu`
--

CREATE TABLE IF NOT EXISTS `tbl_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `uri` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `icon` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `ordered` int(11) NOT NULL,
  `divider` enum('No','Yes') CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL DEFAULT 'No',
  `parent_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=118 ;

--
-- Dumping data for table `tbl_menu`
--

INSERT INTO `tbl_menu` (`id`, `title`, `uri`, `icon`, `publish`, `ordered`, `divider`, `parent_id`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 'home', 'index', 'icons-home', 'Publish', 1, 'No', 0, 1, 1, '2014-01-23 19:50:59', '2012-11-09 14:49:40'),
(2, 'menu', '#', '', 'Publish', 2, 'No', 0, 1, 1, '2014-01-19 23:50:16', '2012-11-09 15:30:44'),
(4, 'Administrative', '#', '', 'Publish', 18, 'No', 0, 1, 1, '2014-01-20 20:48:28', '2012-11-09 15:31:13'),
(6, 'orginize menu', 'menu', '', 'Publish', 12, 'No', 2, 1, 1, '2014-01-23 21:25:32', '2012-11-09 16:18:28'),
(14, 'admins', 'adminusers_auth', '', 'Publish', 9, 'No', 4, 1, 0, '2014-01-20 20:48:33', '2012-11-09 14:25:25'),
(15, 'admins authorize', 'menu_auth', '', 'Publish', 11, 'No', 4, 1, 1, '2014-01-20 20:48:28', '2012-11-09 14:25:53'),
(19, 'Admins Level', 'adminusers_level', '', 'Publish', 10, 'No', 4, 1, 1, '2014-01-20 20:48:33', '2012-11-09 16:33:53'),
(104, 'Library', '#', '', 'Publish', 23, '', 0, 1, 1, '2014-01-23 22:56:25', '2014-01-23 00:43:34'),
(105, 'Pipe Type', 'ref_pipetype', '', 'Publish', 24, '', 106, 1, 1, '2014-01-23 22:57:13', '2014-01-23 16:24:44'),
(106, 'Main Library', '#', '', 'Publish', 25, '', 104, 1, 0, '2014-01-23 22:57:01', '2014-01-23 16:57:01'),
(107, 'Risk Analys', 'ref_risk_analys', '', 'Publish', 26, '', 104, 1, 0, '2014-01-23 22:58:01', '2014-01-23 16:58:01'),
(108, 'Risk Scaling', 'ref_risk_scaling', '', 'Publish', 27, '', 104, 1, 0, '2014-01-23 22:58:33', '2014-01-23 16:58:33'),
(109, 'Objects', 'ref_objects', '', 'Publish', 28, '', 106, 1, 1, '2014-02-03 03:49:10', '2014-01-24 08:21:21'),
(110, 'Plants', '#', '', 'Publish', 29, '', 0, 1, 1, '2014-01-27 04:54:28', '2014-01-24 08:57:36'),
(111, 'Units', 'ref_units', '', 'Publish', 30, '', 106, 1, 1, '2014-02-03 03:38:16', '2014-01-24 09:14:57'),
(112, 'Plant', 'plant', '', 'Publish', 31, '', 110, 1, 0, '2014-01-27 04:54:54', '2014-01-27 05:54:54'),
(113, 'Plant Folder', 'plant_folder', '', 'Publish', 32, '', 110, 1, 0, '2014-01-27 04:55:24', '2014-01-27 05:55:24'),
(114, 'Folder Item', 'plant_fol_item', '', 'Publish', 33, '', 113, 1, 0, '2014-01-27 07:55:42', '2014-01-27 08:55:42'),
(115, 'Object', 'item_object', '', 'Publish', 34, '', 113, 1, 1, '2014-01-29 06:38:00', '2014-01-29 07:36:52'),
(116, 'Ex Type', 'ref_ex_type', '', 'Publish', 35, '', 106, 1, 0, '2014-01-30 04:14:59', '2014-01-30 05:14:59'),
(117, 'Equipment Category', 'ref_equipment_cat', '', 'Publish', 36, '', 106, 1, 0, '2014-01-30 04:23:15', '2014-01-30 05:23:15');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu_auth`
--

CREATE TABLE IF NOT EXISTS `tbl_menu_auth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminusers_level_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=249 ;

--
-- Dumping data for table `tbl_menu_auth`
--

INSERT INTO `tbl_menu_auth` (`id`, `adminusers_level_id`, `menu_id`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 1, 1, 0, 0, '2012-11-13 23:58:40', '2012-11-13 17:58:40'),
(2, 1, 2, 0, 0, '2012-11-13 23:58:40', '2012-11-13 17:58:40'),
(111, 2, 15, 1, 0, '2013-02-13 21:27:19', '2013-02-13 15:27:19'),
(4, 1, 4, 0, 0, '2012-11-13 23:58:40', '2012-11-13 17:58:40'),
(232, 2, 105, 1, 0, '2014-01-23 22:25:00', '2014-01-23 16:25:00'),
(7, 1, 6, 1, 0, '2012-11-14 00:26:12', '2012-11-13 18:26:12'),
(15, 1, 14, 1, 0, '2012-11-14 00:26:12', '2012-11-13 18:26:12'),
(16, 1, 15, 1, 0, '2012-11-14 00:26:12', '2012-11-13 18:26:12'),
(17, 1, 19, 1, 0, '2012-11-14 00:26:12', '2012-11-13 18:26:12'),
(18, 7, 1, 1, 0, '2012-11-14 00:27:14', '2012-11-13 18:27:14'),
(19, 7, 2, 1, 0, '2012-11-14 00:27:14', '2012-11-13 18:27:14'),
(43, 7, 23, 1, 0, '2012-11-21 17:33:50', '2012-11-21 11:33:50'),
(47, 7, 4, 1, 0, '2012-11-26 20:43:14', '2012-11-26 14:43:14'),
(48, 7, 14, 1, 0, '2012-11-26 20:43:14', '2012-11-26 14:43:14'),
(49, 7, 19, 1, 0, '2012-11-26 21:03:31', '2012-11-26 15:03:31'),
(50, 7, 15, 1, 0, '2012-11-26 21:03:31', '2012-11-26 15:03:31'),
(110, 2, 19, 1, 0, '2013-02-13 21:27:19', '2013-02-13 15:27:19'),
(127, 1, 55, 1, 0, '2013-07-03 05:08:59', '2013-07-03 01:08:59'),
(104, 2, 50, 1, 0, '2013-02-05 22:46:33', '2013-02-05 16:46:33'),
(112, 2, 53, 2, 0, '2013-02-28 22:18:07', '2013-02-28 16:18:07'),
(57, 2, 28, 1, 0, '2012-12-15 00:42:51', '2012-12-14 18:42:51'),
(59, 1, 30, 1, 0, '2012-12-15 00:47:48', '2012-12-14 18:47:48'),
(93, 2, 47, 1, 0, '2013-01-10 21:23:06', '2013-01-10 15:23:06'),
(61, 2, 29, 1, 0, '2012-12-15 00:48:08', '2012-12-14 18:48:08'),
(62, 2, 30, 1, 0, '2012-12-15 00:48:08', '2012-12-14 18:48:08'),
(108, 2, 52, 1, 0, '2013-02-13 20:15:52', '2013-02-13 14:15:52'),
(65, 1, 33, 1, 0, '2012-12-18 17:37:52', '2012-12-18 11:37:52'),
(67, 2, 33, 1, 0, '2012-12-18 17:38:23', '2012-12-18 11:38:23'),
(102, 2, 49, 1, 0, '2013-02-05 22:44:55', '2013-02-05 16:44:55'),
(72, 1, 38, 1, 0, '2012-12-18 18:47:08', '2012-12-18 12:47:08'),
(89, 2, 44, 1, 0, '2013-01-10 18:28:43', '2013-01-10 12:28:43'),
(88, 1, 44, 1, 0, '2013-01-10 18:28:31', '2013-01-10 12:28:31'),
(75, 2, 36, 1, 0, '2012-12-18 18:48:10', '2012-12-18 12:48:10'),
(76, 2, 37, 1, 0, '2012-12-18 18:48:10', '2012-12-18 12:48:10'),
(77, 2, 38, 1, 0, '2012-12-18 18:48:10', '2012-12-18 12:48:10'),
(79, 2, 39, 1, 0, '2012-12-19 21:42:13', '2012-12-19 15:42:13'),
(106, 2, 51, 1, 0, '2013-02-05 22:53:17', '2013-02-05 16:53:17'),
(82, 1, 40, 1, 0, '2013-01-10 18:15:23', '2013-01-10 12:15:23'),
(83, 1, 41, 1, 0, '2013-01-10 18:15:23', '2013-01-10 12:15:23'),
(84, 2, 40, 1, 0, '2013-01-10 18:15:41', '2013-01-10 12:15:41'),
(85, 2, 41, 1, 0, '2013-01-10 18:15:41', '2013-01-10 12:15:41'),
(87, 2, 43, 1, 0, '2013-01-10 18:19:01', '2013-01-10 12:19:01'),
(109, 2, 14, 1, 0, '2013-02-13 21:27:19', '2013-02-13 15:27:19'),
(117, 2, 55, 1, 0, '2013-05-07 18:58:32', '2013-05-07 01:58:32'),
(98, 2, 4, 1, 0, '2013-01-12 01:03:23', '2013-01-11 19:03:23'),
(100, 2, 23, 1, 0, '2013-01-12 01:05:10', '2013-01-11 19:05:10'),
(114, 2, 54, 2, 0, '2013-03-14 21:56:25', '2013-03-14 16:56:25'),
(237, 2, 106, 1, 0, '2014-01-23 22:58:56', '2014-01-23 16:58:56'),
(231, 2, 104, 1, 0, '2014-01-23 06:46:54', '2014-01-23 00:46:54'),
(230, 2, 1, 1, 0, '2014-01-23 06:46:54', '2014-01-23 00:46:54'),
(235, 1, 107, 1, 0, '2014-01-23 22:58:46', '2014-01-23 16:58:46'),
(238, 2, 107, 1, 0, '2014-01-23 22:58:56', '2014-01-23 16:58:56'),
(236, 1, 108, 1, 0, '2014-01-23 22:58:46', '2014-01-23 16:58:46'),
(239, 2, 108, 1, 0, '2014-01-23 22:58:56', '2014-01-23 16:58:56'),
(229, 1, 104, 1, 0, '2014-01-23 06:46:45', '2014-01-23 00:46:45'),
(234, 1, 106, 1, 0, '2014-01-23 22:58:46', '2014-01-23 16:58:46'),
(233, 1, 105, 1, 0, '2014-01-23 22:25:05', '2014-01-23 16:25:05'),
(240, 1, 109, 1, 0, '2014-01-24 07:22:30', '2014-01-24 08:22:30'),
(241, 1, 110, 1, 0, '2014-01-24 07:57:52', '2014-01-24 08:57:52'),
(242, 1, 111, 1, 0, '2014-01-24 08:15:10', '2014-01-24 09:15:10'),
(243, 1, 112, 1, 0, '2014-01-27 04:55:49', '2014-01-27 05:55:49'),
(244, 1, 113, 1, 0, '2014-01-27 04:55:49', '2014-01-27 05:55:49'),
(245, 1, 114, 1, 0, '2014-01-27 07:55:58', '2014-01-27 08:55:58'),
(246, 1, 115, 1, 0, '2014-01-29 06:37:06', '2014-01-29 07:37:06'),
(247, 1, 116, 1, 0, '2014-01-30 04:15:10', '2014-01-30 05:15:10'),
(248, 1, 117, 1, 0, '2014-01-30 04:23:26', '2014-01-30 05:23:26');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_plant`
--

CREATE TABLE IF NOT EXISTS `tbl_plant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_ref_units` int(11) NOT NULL,
  `title` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `desc_` text COLLATE latin1_general_ci NOT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_plant`
--

INSERT INTO `tbl_plant` (`id`, `id_ref_units`, `title`, `desc_`, `publish`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 1, 'test', 'test desc', 'Publish', 1, 1, '2014-01-27 03:11:20', '2014-01-27 00:00:00'),
(2, 2, 'Andi Plant Process edit ke platform', 'andi plant process description', 'Publish', 1, 1, '2014-01-27 03:36:25', '2014-01-27 04:34:26'),
(3, 2, 'andi plant platform edit', 'andi plant platform description edit', 'Publish', 1, 1, '2014-01-27 03:36:05', '2014-01-27 04:35:16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_plant_folder`
--

CREATE TABLE IF NOT EXISTS `tbl_plant_folder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_plant` int(11) NOT NULL,
  `title` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `desc_` text COLLATE latin1_general_ci NOT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_plant_folder`
--

INSERT INTO `tbl_plant_folder` (`id`, `id_plant`, `title`, `desc_`, `publish`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 1, 'Test Folder edit', 'test folder description', 'Publish', 1, 1, '2014-01-27 04:59:46', '2014-01-27 05:57:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_plant_fol_item`
--

CREATE TABLE IF NOT EXISTS `tbl_plant_fol_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_plant_folder` int(11) NOT NULL,
  `id_ref_objects` int(11) NOT NULL,
  `title` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `desc_` text COLLATE latin1_general_ci NOT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_plant_fol_item`
--

INSERT INTO `tbl_plant_fol_item` (`id`, `id_plant_folder`, `id_ref_objects`, `title`, `desc_`, `publish`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 1, 206, 'pressure vessel test', 'pressure vessel test', 'Publish', 1, 1, '2014-01-28 03:21:20', '2014-01-27 09:30:14'),
(2, 1, 206, 'PV', 'PV', 'Publish', 1, 1, '2014-01-28 03:22:58', '2014-01-27 09:38:56');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ref_equipment_cat`
--

CREATE TABLE IF NOT EXISTS `tbl_ref_equipment_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `desc_` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_ref_equipment_cat`
--

INSERT INTO `tbl_ref_equipment_cat` (`id`, `title`, `desc_`, `publish`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, '100', 'Mechanical', 'Publish', 1, 0, '2014-01-29 06:46:27', '2014-01-29 00:00:00'),
(2, '300', 'Electrical', 'Publish', 1, 0, '2014-01-29 06:46:27', '2014-01-29 00:00:00'),
(3, '500', 'Instrument', 'Publish', 1, 0, '2014-01-29 06:47:13', '2014-01-29 00:00:00'),
(4, '700', 'Piping', 'Publish', 1, 0, '2014-01-29 06:47:13', '2014-01-29 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ref_ex_type`
--

CREATE TABLE IF NOT EXISTS `tbl_ref_ex_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `desc_` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=19 ;

--
-- Dumping data for table `tbl_ref_ex_type`
--

INSERT INTO `tbl_ref_ex_type` (`id`, `title`, `desc_`, `publish`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 'D', '', 'Publish', 1, 0, '2014-01-29 07:07:38', '2014-01-29 14:07:38'),
(2, 'E', '', 'Publish', 0, 0, '2014-01-29 07:07:38', '2014-01-29 14:07:38'),
(3, 'I', '', 'Publish', 1, 0, '2014-01-29 07:08:05', '2014-01-29 14:08:05'),
(4, 'M', '', 'Publish', 1, 0, '2014-01-29 07:08:05', '2014-01-29 14:08:05'),
(5, 'O', '', 'Publish', 1, 0, '2014-01-29 07:08:34', '2014-01-29 14:08:34'),
(6, 'P', '', 'Publish', 1, 0, '2014-01-29 07:08:34', '2014-01-29 14:08:34'),
(7, 'P', '', 'Publish', 1, 0, '2014-01-29 07:09:10', '2014-01-29 14:09:10'),
(8, 'P1', '', 'Publish', 1, 0, '2014-01-29 07:09:10', '2014-01-29 14:09:10'),
(9, 'Q', '', 'Publish', 1, 0, '2014-01-29 07:09:35', '2014-01-29 14:09:35'),
(10, 'S', '', 'Publish', 1, 0, '2014-01-29 07:09:35', '2014-01-29 14:09:35'),
(11, 'V', '', 'Publish', 1, 0, '2014-01-29 07:10:10', '2014-01-29 14:10:10'),
(12, 'DE', '', 'Publish', 1, 0, '2014-01-29 07:10:10', '2014-01-29 14:10:10'),
(13, 'ED', '', 'Publish', 1, 0, '2014-01-29 07:10:37', '2014-01-29 14:10:37'),
(14, 'IA', '', 'Publish', 1, 0, '2014-01-29 07:10:37', '2014-01-29 14:10:37'),
(15, 'IB', '', 'Publish', 1, 0, '2014-01-29 07:11:01', '2014-01-29 14:11:01'),
(16, 'MA', '', 'Publish', 1, 0, '2014-01-29 07:11:01', '2014-01-29 14:11:01'),
(17, 'MB', '', 'Publish', 1, 0, '2014-01-29 07:11:25', '2014-01-29 14:11:25'),
(18, 'XX', '', 'Publish', 1, 0, '2014-01-29 07:11:25', '2014-01-29 14:11:25');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ref_objects`
--

CREATE TABLE IF NOT EXISTS `tbl_ref_objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_code` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `title` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=322 ;

--
-- Dumping data for table `tbl_ref_objects`
--

INSERT INTO `tbl_ref_objects` (`id`, `ref_code`, `title`, `publish`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 'A FRAME', 'A Frame', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(2, 'ADAPTER', 'Adapter', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(3, 'AIR COMPRESSOR', 'Air Compressor', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(4, 'AIR CYLINDER', 'Air Cylinder', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(5, 'AIR DRYER', 'Air Dryer', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(6, 'AIR RECEIVER', 'Air Receiver', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(7, 'ALARM SYSTEM', 'Alarm System', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(8, 'ANCHOR', 'Anchor', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(9, 'ANCHOR CHAINS', 'Anchor Chains', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(10, 'ANCHOR PILE', 'Anchor Pile', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(11, 'ANODE', 'Anode', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(12, 'ANODE - STRUCTURAL', 'Anode - Platform', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(13, 'BALL VALVE', 'Ball Valve', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(14, 'BATTERY', 'Battery', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(15, 'BATTERY COMPARTMENT', 'Battery Compartment', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(16, 'BEARING', 'Bearing', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(17, 'BELL', 'Bell', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(18, 'BILGE KEEL', 'Bilge Keel', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(19, 'BLOWER/FAN', 'Blower/fan', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(20, 'BOAT BUMPER', 'Boat Bumper', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(21, 'BOAT FENDER', 'Boat Fender', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(22, 'BOAT LANDING', 'Boat Landing', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(23, 'BOILER', 'Boiler', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(24, 'BOLLARD', 'Bollard', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(25, 'BOLLARD FAIRLEAD', 'Bollard Fairlead', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(26, 'BOLT', 'Bolt', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(27, 'BOOM CHAIN', 'Boom Chain', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(28, 'BOP - ANNULAR', 'Bop - Annular', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(29, 'BOP - SHELL', 'Bop - Shell', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(30, 'BOP - STACK', 'Bop - Stack', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(31, 'BOSS', 'Boss', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(32, 'BOTTOM PLUG', 'Bottom Plug', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(33, 'BRACKET SUPPORT', 'Bracket Support', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(34, 'BUNDLE', 'Bundle', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(35, 'BUOY', 'Buoy', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(36, 'CABLE STOPPER', 'Cable Stopper', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(37, 'CABLE TRAY', 'Cable Tray', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(38, 'CAGE', 'Cage', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(39, 'CAISSON', 'Caisson', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(40, 'CAISSON GUIDE', 'Caisson Guide', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(41, 'CALM', 'Calm', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(42, 'CASING', 'Casing', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(43, 'CENTRAL CHAMBER', 'Central Chamber', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(44, 'CENTRIFUGAL PUMP', 'Centrifugal Pump', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(45, 'CHAIN', 'Chain', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(46, 'CHAIN BLOCK', 'Chain Block', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(47, 'CHAIN CONNECTOR', 'Chain Connector', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(48, 'CHAIN HAWSER', 'Chain Hawser', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(49, 'CHAIN STOPPER', 'Chain Stopper', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(50, 'CLAMP', 'Clamp', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(51, 'CLAMP BOLT', 'Clamp Bolt', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(52, 'CLAMP INSULATION', 'Clamp Insulation', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(53, 'COALESCER', 'Coalescer', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(54, 'COLLAR', 'Collar', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(55, 'COLUMN', 'Column', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(56, 'COMMUNICATION', 'Communication', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(57, 'COMMUNICATION EQUIPMENT', 'Communication Equipment', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(58, 'COMMUNICATION SYSTEM', 'Communication System', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(59, 'COMPRESSOR', 'Compressor', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(60, 'CONCRETE BAGGING', 'Concrete Bagging', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(61, 'CONDUCTOR', 'Conductor', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(62, 'CONDUCTOR GUIDE', 'Conductor Guide', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(63, 'CONDUCTOR GUIDE FRAME', 'Conductor Guide Frame', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(64, 'CONE', 'Cone', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(65, 'CONICAL STUB', 'Conical Stub', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(66, 'CONNECTOR-ELECTRICAL', 'Connector-electrical', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(67, 'CONNECTOR-MECHANICAL', 'Connector-mechanical', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(68, 'CONTAINER', 'Container', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(69, 'CONTROL PANEL', 'Control Panel', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(70, 'SUBSEA UMBILICAL', 'Sub Sea Umbilical', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(71, 'CONTROL VAN', 'Control Van', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(72, 'COUPLING', 'Coupling', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(73, 'COUPON', 'Coupon', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(74, 'CRANE', 'Crane', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(75, 'CRANE PEDESTAL', 'Crane Pedestal', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(76, 'CROSSING', 'Crossing', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(77, 'CROSSOVER', 'Crossover', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(78, 'DEBRIS', 'Debris', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(79, 'DECK PLATE', 'Deck Plate', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(80, 'DEHYDRATOR', 'Dehydrator', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(81, 'DERRICK', 'Derrick', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(82, 'DISCHARGE', 'Discharge', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(83, 'DOOR', 'Door', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(84, 'DOORWAY', 'Doorway', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(85, 'DRILLING DERRICK', 'Drilling Derrick', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(86, 'DRUM', 'Drum', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(87, 'DYNAMIC POSITIONING', 'Dynamic Positioning', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(88, 'ELECTRIC MOTOR', 'Electric Motor', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(89, 'ELECTRICAL CABLE', 'Electrical Cable', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(90, 'ERP', 'Erp', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(91, 'EXPANSION LOOP', 'Expansion Loop', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(92, 'EYE', 'Eye', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(93, 'FACE', 'Face', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(94, 'FAIRLEAD', 'Fairlead', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(95, 'FENDER BUOY', 'Fender Buoy', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(96, 'FIELD JOINT', 'Field Joint', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(97, 'FIELD JOINT LOCATION', 'Field Joint Location', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(98, 'FIELD JOINT NUMBER', 'Field Joint Number', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(99, 'FIELD JOINT WRAP', 'Field Joint Wrap', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(100, 'FILTER', 'Filter', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(101, 'FIN STABILISER', 'Fin Stabiliser', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(102, 'FIRE', 'A Frame', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(103, 'FIRE DETECTOR', 'Fire Detector', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(104, 'FIRE MAIN', 'Fire Main', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(105, 'FIRE POINT', 'Fire Point', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(106, 'FIRE WALL', 'Fire Wall', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(107, 'FIXING', 'Fixing', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(108, 'FLANGE', 'Flange', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(109, 'FLARE', 'Flare Column', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(110, 'FLARE BOOM', 'Flare Boom', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(111, 'FLARE TIP', 'Flare Tip', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(112, 'FLOATATION DEVICE', 'Floatation Device', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(113, 'FLOATING HOSE', 'Floating Hose', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(114, 'FLOTATION TANK', 'Flotation Tank', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(115, 'FOG HORN', 'Fog Horn', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(116, 'FPSO PLATE', 'FPSO Plating on Hull Structure', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(117, 'FRAME', 'Frame', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(118, 'FURNACE', 'Furnace', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(119, 'GALLEY', 'Galley', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(120, 'GAS CYLINDER', 'Gas Cylinder', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(121, 'GAS DETECTOR', 'Gas Detector', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(122, 'GASKET', 'Gasket', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(123, 'GATE VALVE', 'Gate Valve', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(124, 'GEARBOX', 'Gearbox', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(125, 'GENERATOR', 'Generator', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(126, 'GLAND', 'Gland', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(127, 'GRATING', 'Grating', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(128, 'GROUND BED', 'Ground Bed', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(129, 'GUIDE', 'Guide', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(130, 'GUIDE BASE', 'Guide Base', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(131, 'GUIDE FRAME', 'Guide Frame', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(132, 'GUIDE POST', 'Guide Post', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(133, 'HABITAT', 'Habitat', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(134, 'HATCH', 'Hatch', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(135, 'HATCH COVER', 'Hatch Cover', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(136, 'HAWSER', 'Hawser', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(137, 'HEAT EXCHANGER', 'Heat Exchanger', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(138, 'HEATER', 'Heater', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(139, 'HELIPAD', 'Helipad', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(140, 'HINGE PIN', 'Hinge Pin', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(141, 'HOIST', 'Hoist', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(142, 'HORIZONTAL DIAGONAL MEMBER', 'Horizontal Diagonal Member', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(143, 'HORIZONTAL MEMBER', 'Horizontal Member', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(144, 'HOSE BREAKAWAY COUPLING', 'Hose Breakaway Coupling', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(145, 'HOSE/FLEXIBLE RISER', 'Hose/flexible Riser', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(146, 'HOUSING', 'Housing', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(147, 'HULL', 'Hull', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(148, 'HULL PLATE', 'Hull Plate', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(149, 'HYDRAULIC LINE', 'Hydraulic Line', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(150, 'HYDRAULIC POD', 'Hydraulic Pod', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(151, 'HYDRAULIC RAM', 'Hydraulic Ram', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(152, 'IMPRESSED CURRENT ANODE', 'Impressed Current Anode', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(153, 'INSPECTION PLATE', 'Inspection Plate', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(154, 'INTAKE', 'Intake', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(155, 'ITEM', 'Item', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(156, 'JOINT', 'Joint', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(157, 'LADDER', 'Ladder', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(158, 'LEG', 'Leg', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(159, 'LIFE BOAT', 'Life Boat', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(160, 'LIFE BOAT DAVIT', 'Life Boat Davit', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(161, 'LIFTING BEAM', 'Lifting Beam', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(162, 'LIFTING HOOK', 'Lifting Hook', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(163, 'LIFTING OBJECT', 'Lifting Object', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(164, 'LIFTING PADEYE', 'Lifting Padeye', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(165, 'LIFTING WEIGHT', 'Lifting Weight', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(166, 'LIGHTING', 'Lighting', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(167, 'LOAD BEARING', 'Load Bearing', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(168, 'LOAD LINE', 'Load Line', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(169, 'LOG', 'Log', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(170, 'MAN RIDING WINCH', 'Man Riding Winch', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(171, 'MANIFOLD', 'Manifold', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(172, 'MANWAY', 'Manway', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(173, 'MARINE GROWTH', 'Marine Growth', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(174, 'MARKER BUOY', 'Marker Buoy', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(175, 'MOON POOL', 'Moon Pool', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(176, 'MOORING ROPE', 'Mooring Rope', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(177, 'MUD PUMP', 'Mud Pump', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(178, 'MUD SHAKER', 'Mud Shaker', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(179, 'MUD TANK', 'Mud Tank', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(180, 'NAVIGATION LIGHT', 'Navigation Light', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(181, 'NIPPLE', 'Nipple', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(182, 'NOZZLE', 'Nozzle', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(183, 'OVERHEAD CRANE', 'Overhead Crane', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(184, 'PADEYE', 'Padeye', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(185, 'PHYSICAL DAMAGE', 'Physical Damage', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(186, 'PIG CATCHER', 'Pig Catcher', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(187, 'PIG LAUNCHER', 'Pig Launcher', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(188, 'PIG RECEIVER', 'Pig Receiver', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(189, 'PILE', 'Pile', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(190, 'PILE GUIDE', 'Pile Guide', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(191, 'PIPE BRIDGE', 'Pipe Bridge', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(192, 'PIPE CLAMP', 'Pipe Clamp', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(193, 'PIPE RACK', 'Pipe Rack', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(194, 'PIPELINE', 'Pipeline', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(195, 'PIPELINE ANCHOR', 'Pipeline Anchor', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(196, 'PIPELINE BEND', 'Pipeline Bend', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(197, 'PIPELINE CROSSING', 'Pipeline Crossing', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(198, 'PIPELINE END MANIFOLD', 'Pipeline End Manifold', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(199, 'PIPELINE REDUCER', 'Pipeline Reducer', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(200, 'PIPEWORK', 'Pipework', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(201, 'PIPING', 'Piping', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(202, 'PLEM', 'Plem', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(203, 'PNEUMATIC MACHINE', 'Pneumatic Machine', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(204, 'POWER PACK', 'Power Pack', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(205, 'PRESSURE SAFETY VALVE', 'Pressure Safety Valve', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(206, 'PRESSURE VESSEL', 'Pressure Vessel', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(207, 'PROPELLER', 'Propeller', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(208, 'PROPELLER NOZZLE', 'Propeller Nozzle', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(209, 'PULL IN', 'Pull In', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(210, 'PULL LIFT', 'Pull Lift', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(211, 'PUMP', 'Pump', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(212, 'PUP PIECE', 'Pup Piece', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(213, 'QUARTER TURN VALVE', 'Quarter Turn Valve', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(214, 'RADAR', 'Radar', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(215, 'RADAR REFLECTOR', 'Radar Reflector', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(216, 'RAILING', 'Railing', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(217, 'RATCHET', 'Ratchet', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(218, 'REACTOR', 'Reactor', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(219, 'RECIPROCATING PUMP', 'Reciprocating Pump', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(220, 'RELIEF VALVE', 'Relief Valve', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(221, 'REPAIR', 'Repair', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(222, 'REPAIR CLAMP', 'Repair Clamp', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(223, 'RIGGING & LIFTING', 'Rigging & Lifting', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(224, 'RISER', 'Riser', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(225, 'RISER ELBOW', 'Riser Elbow', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(226, 'RISER GUARD', 'Riser Guard', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(227, 'RISER SUPPORT BEAM', 'Riser Support Beam', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(228, 'RISER SUPPORT CLAMP', 'Riser Support Clamp', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(229, 'ROPE GUARD', 'Rope Guard', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(230, 'ROV PANEL', 'Rov Panel', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(231, 'RUDDER', 'Rudder', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(232, 'RUDDER STOCK', 'Rudder Stock', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(233, 'SACP ONSHORE', 'Sacp Onshore', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(234, 'SACRIFICIAL ANODE', 'Sacrificial Anode', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(235, 'SADDLE', 'Saddle', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(236, 'SAFETY NET', 'Safety Net', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(237, 'SAMPLE POINT', 'Sample Point', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(238, 'SAND BAGGING', 'Sand Bagging', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(239, 'SCOUR MATTING', 'Scour Matting', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(240, 'SCRUBBER', 'Scrubber', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(241, 'SEACHEST', 'Seachest', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(242, 'SHACKLE', 'Shackle', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(243, 'SHACKLE PIN', 'Shackle Pin', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(244, 'SHAFT', 'Shaft', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(245, 'SHOCK CELL', 'Shock Cell', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(246, 'SKID', 'Skid', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(247, 'SKIRT', 'Skirt', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(248, 'SLING', 'Sling', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(249, 'SMOKE DETECTOR', 'Smoke Detector', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(250, 'SPALLING', 'Spalling', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(251, 'SPAN', 'Span', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(252, 'SPHERE', 'Sphere', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(253, 'SPLIT CLAMP', 'Split Clamp', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(254, 'SPM SKIRT', 'Spm Skirt', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(255, 'SPOOL PIECE', 'Spool Piece', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(256, 'STABBING GUIDE', 'Stabbing Guide', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(257, 'STAIRWAY', 'Stairway', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(258, 'STORAGE TANK', 'Storage Tank', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(259, 'STRUCTURAL NODE', 'Structural Node', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(260, 'STRUCTURE', 'Structure', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(261, 'STUB', 'Stub', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(262, 'STUD', 'Stud', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(263, 'SUBFRAME', 'Subframe', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(264, 'SUBSEA COMPLETION', 'Subsea Completion', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(265, 'SUBSEA HOSE', 'Subsea Hose', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(266, 'SUPPORT', 'Support', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(267, 'SUPPORT STRUCTURE', 'Support Structure', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(268, 'SUPPORT STRUT', 'Support Strut', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(269, 'SWIVEL', 'Swivel', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(270, 'SWIVEL JOINT', 'Swivel Joint', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(271, 'SWIVEL LINK', 'Swivel Link', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(272, 'TANK', 'Tank', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(273, 'TEST POINT', 'A Frame', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(274, 'THRUSTER', 'Thruster', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(275, 'TOOL THREADS', 'Tool Threads', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(276, 'TOP PACKAGE', 'Top Package', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(277, 'TRANSDUCER', 'Transducer', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(278, 'TRANSFORMER', 'Transformer', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(279, 'TRANSFORMER RECTIFIER', 'Transformer Rectifier', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(280, 'TRANSPONDER', 'Transponder', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(281, 'TRASH CAP', 'Trash Cap', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(282, 'TRIP TANK', 'Trip Tank', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(283, 'TURBINE', 'Turbine', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(284, 'UNIVERSAL JOINT', 'Universal Joint', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(285, 'VACUUM EQUIPMENT', 'Vacuum Equipment', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(286, 'VALVE', 'Valve', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(287, 'VERTICAL DIAGONAL MEMBER', 'Vertical Diagonal Member', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(288, 'VERTICAL MEMBER', 'Vertical Member', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(289, 'VESSEL', 'Vessel', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(290, 'VESSEL HULL', 'Vessel Hull', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(291, 'WALKWAY & GANGWAY', 'Walkway & Gangway', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(292, 'WALL / FIRE WALL', 'Wall / Fire Wall', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(293, 'WAVESTAFF', 'Wavestaff', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(294, 'WEIGHT COAT', 'Weight Coat', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(295, 'WELD', 'Weld', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(296, 'WELL HEAD', 'Well Head', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(297, 'WINCH', 'Winch', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(298, 'WIRE ROPE', 'Wire Rope', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(299, 'X NODE', 'X Node', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(300, 'XMAS TREE', 'Xmas Tree', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(301, 'Y PIECE', 'Y Piece', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(302, 'YOKOHAMA BUOY', 'Yokohama Buoy', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(303, 'HORIZONTAL DIAGONAL MEMBER - BRIDGE', 'Horizontal Diagonal Member - Bridge', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(304, 'HORIZONTAL MEMBER - BRIDGE', 'Horizontal Member - Bridge', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(305, 'SUPPORT STRUT - BRIDGE', 'Support Strut - Bridge', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(306, 'VERTICAL DIAGONAL MEMBER - BRIDGE', 'Vertical Diagonal Member - Bridge', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(307, 'VERTICAL MEMBER - BRIDGE', 'Vertical Member - Bridge', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(308, 'TUBE [ J ] / [ I ] - PLATFORM ITEM', 'I / J Tubes - Platform', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(309, 'STRUCTURAL ITEM', 'Structural Item (Decking,etc)', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(310, 'PLATING', 'Plating on Structure', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(311, 'VESSEL CARGO TANKS', 'Vessel Cargo Tanks, etc', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(312, 'MOTOR CONTROL CENTER', 'Motor Control Center - Electrical', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(313, 'POWER GENERATOR', 'Power Generator', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(314, 'SWITCH GEAR', 'Switch Gear', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(315, 'CHOKE VALVE', 'Choke Valve', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(316, 'AGITATOR', 'Agitator Unit - Mixer / Blender within Vessel / Reactor', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(317, 'SUBSEA PIPELINE', 'Subsea Pipeline', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(318, 'SUBSEA FLEXIBLE PIPELINE', 'Subsea Flexible Pipeline', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(319, 'SUBSEA PLEM', 'Subsea PLEM', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(320, 'PACKAGE', 'Equipment Package', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00'),
(321, 'SUB-PACKAGE', 'Equipment Sub-Package', 'Not Publish', 0, 0, '2014-01-24 07:16:18', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ref_pipetype`
--

CREATE TABLE IF NOT EXISTS `tbl_ref_pipetype` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_code` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `ref_title` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=27 ;

--
-- Dumping data for table `tbl_ref_pipetype`
--

INSERT INTO `tbl_ref_pipetype` (`id`, `ref_code`, `ref_title`, `publish`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 'BEND', 'Bend', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(2, 'T-PIECE', 'Tee Piece', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(3, 'REDUCER', 'Reducer', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(4, 'STRAIGHT', 'Straight', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(5, 'ZONE GRID BOXES', 'Zone Grids', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(6, 'SHELL PLATE', 'Tank Shell Plate', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(7, 'PLATE', 'Plate', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(8, 'ROOF PLATE', 'Roof Plate', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(9, 'ELBOW 45', 'Elbow 45', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(10, 'ELBOW 90', 'Elbow 90', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(11, 'DOME', 'Dome', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(12, 'DOME END', 'Dome End', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(13, 'HEAD', 'Head', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(14, 'DISH', 'Dish', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(15, 'DISH END', 'Dish End', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(16, 'NOZZLE', 'Nozzle', 'Publish', 1, 0, '2014-01-23 22:22:56', '2010-05-16 00:00:00'),
(17, 'ELBOW', 'elbow', 'Publish', 1, 0, '2014-01-23 22:22:56', '2011-02-08 00:00:00'),
(18, 'SHELL 1', 'Shell 1', 'Publish', 1, 0, '2014-01-23 22:22:56', '2011-06-05 00:00:00'),
(19, 'SHELL 2', 'Shell 2', 'Publish', 1, 0, '2014-01-23 22:22:56', '2011-06-05 00:00:00'),
(20, 'SHELL 3', 'shell 3', 'Publish', 1, 0, '2014-01-23 22:22:56', '2011-06-05 00:00:00'),
(21, 'SHELL 4', 'shell 4', 'Publish', 1, 0, '2014-01-23 22:22:56', '2011-06-05 00:00:00'),
(22, 'SHELL 5', 'Shell 5', 'Publish', 1, 0, '2014-01-23 22:22:56', '2011-06-05 00:00:00'),
(23, 'HEAD 1', 'Head 1', 'Publish', 1, 0, '2014-01-23 22:22:56', '2011-06-05 00:00:00'),
(24, 'HEAD 2', 'head 2', 'Publish', 1, 0, '2014-01-23 22:22:56', '2011-06-05 00:00:00'),
(25, 'HEAD 3', 'Head 3', 'Publish', 1, 0, '2014-01-23 22:22:56', '2011-06-05 00:00:00'),
(26, 'TUBE', 'Tube', 'Publish', 1, 2, '2014-01-23 17:10:40', '2011-06-05 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ref_units`
--

CREATE TABLE IF NOT EXISTS `tbl_ref_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) COLLATE latin1_general_ci NOT NULL,
  `desc_` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_ref_units`
--

INSERT INTO `tbl_ref_units` (`id`, `title`, `desc_`, `publish`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 'Process', 'Process Desc', 'Publish', 1, 1, '2014-01-24 08:34:04', '2014-01-24 09:29:58'),
(2, 'Platform', 'Platform Desc', 'Publish', 1, 0, '2014-01-24 08:34:32', '2014-01-24 09:34:32');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
