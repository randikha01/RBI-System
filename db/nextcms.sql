-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 31, 2013 at 07:08 PM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nextcms`
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_adminusers_auth`
--

INSERT INTO `tbl_adminusers_auth` (`id`, `adminusers_level_id`, `publish`, `username`, `password`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 1, 'Publish', 'superadmin', '085b127873f972ec64a1f30f6952894e', 1, 1, '2013-01-14 11:53:42', '2012-11-09 18:26:23'),
(2, 2, 'Publish', 'administrator', '63ee4e9b42a4d9afcdc6222d974a240b', 1, 2, '2013-10-29 15:50:53', '2012-11-21 17:54:39'),
(3, 2, 'Publish', 'randikha', '63ee4e9b42a4d9afcdc6222d974a240b', 2, 1, '2013-10-31 15:00:27', '2013-10-29 12:01:55');

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
(1, 'Super Administrator', 'Publish', 0, 1, 1, '2013-10-31 18:30:09', '2012-11-12 11:45:10'),
(2, 'System Admin', 'Publish', 0, 1, 1, '2013-10-31 18:30:09', '2012-11-09 17:58:07'),
(15, 'Editor', 'Publish', 0, 0, 1, '2013-10-31 18:30:09', '2013-10-29 16:13:53'),
(16, 'Guest', 'Publish', 0, 0, 1, '2013-10-31 18:30:09', '2013-10-31 15:12:59');

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
(1, 'tes', 'tes', 'tes', 'tes', '', 1, 1, '2013-10-29 16:59:48', '2013-10-29 16:59:48');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_contents`
--

CREATE TABLE IF NOT EXISTS `tbl_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `content` text CHARACTER SET latin1 COLLATE latin1_general_ci,
  `file_image` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `publish` enum('Not Publish','Publish') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `featured` enum('No','Yes') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'No',
  `ordered` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tbl_contents`
--

INSERT INTO `tbl_contents` (`id`, `title`, `content`, `file_image`, `type`, `publish`, `featured`, `ordered`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 'Tentang Kami ', '<p><b>DokterDigital.com</b> adalah sebuah sumber informasi kesehatan yang diyakini mampu memenuhi keinginan pembaca di dunia maya. Selain menyajikan informasi kredibel dan beragam referensi tentang kesehatan dari sumber-sumber terpercaya, kami memfasilitasi komunitas pembaca untuk berinteraksi aktif dengan para ahli di bidangnya.</p>\n\n<p>Awak <b>DokterDigital.com</b> adalah para profesional yang ahli dalam bidang jurnalistik, pembuatan konten, layanan komunitas, dan kajian medis demi memberikan pengguna Web beragam cara dan kiat yang dibutuhkan. Kami juga didukung oleh sejumlah dokter spesialis dan pakar yang kredibel di bidang medis dan kesehatan.</p>\n\n<p>Itulah mengapa kami yakin dengan kemampuan kami dalam upaya memuaskan Anda, antara lain dalam hal mengelola:</p>\n\n<ul>\n	<li>Berita dan informasi kesehatan keluarga Anda</li>\n	<li>Membuat dan memelihara database konten referensi medis</li>\n	<li>Mengelola komunitas yang peduli dengan kesehatan dan menghubungkan mereka dengan pelaku kesehatan</li>\n	<li>Berbagi pengalaman antar pembaca (misalnya pembaca dapat mengirimkan tips dan artikel&nbsp; yang akan dimuat di website kami)</li>\n	<li>Interaksi pembaca (pembaca dapat mengirimkan komentar/saran, berkonsultasi langsung dengan pakarnya).</li>\n</ul>\n\n<p>Dengan kekuatan yang kami miliki ini, kami akan selalu berupaya memberikan informasi kesehatan berkualitas dan akan selalu menjaga integritas proses editorial kami. Selain itu, kami juga tak melupakan apa yang dibutuhkan Anda dan pembaca lainnya tentang informasi kesehatan yang dikemas secara menarik, menghibur dan tidak berpihak.</p>\n\n<p>Kami berkomitmen untuk terus meningkatkan kualitas <b>DokterDigital.com</b>, sehingga layanan kami dapat membantu Anda menemukan cara terbaik mengatasi masalah kesehatan, membuat hidup Anda lebih baik, dan membantu Anda merasakan hal yang lebih baik tentang kesehatan Anda dan keluarga.</p>\n\n<p>Misi kami adalah menghantarkan kepada Anda berbagai informasi kesehatan yang objektif, terpercaya dan akurat melalui Internet. Sedangkan tujuan utama kami adalah memastikan <strong>DokterDigital.com</strong> sebagai sumber konten yang relevan dan praktis untuk bidang kesehatan.&nbsp;</p>\n\n<p>Namun, kami mengingatkan bahwa informasi yang tersaji di <b>Dokter Digital</b> bukanlah pengganti bagi layanan kesehatan profesional. Untuk itu, Anda tetap harus berkonsultasi dengan dokter sebelum bertindak atas informasi apapun yang ada di Dokter Digital atau situs lainnya.&nbsp;</p>\n', NULL, 'tentang-kami', 'Publish', 'No', 0, 1, 1, '2013-10-31 18:01:41', '2013-07-16 07:45:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu`
--

CREATE TABLE IF NOT EXISTS `tbl_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `uri` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `ordered` int(11) NOT NULL,
  `divider` enum('No','Yes') CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL DEFAULT 'No',
  `parent_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=78 ;

--
-- Dumping data for table `tbl_menu`
--

INSERT INTO `tbl_menu` (`id`, `title`, `uri`, `publish`, `ordered`, `divider`, `parent_id`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 'home', 'index', 'Publish', 1, 'No', 0, 1, 1, '2013-10-31 16:04:33', '2012-11-09 14:49:40'),
(2, 'menu', '#', 'Publish', 2, 'No', 0, 1, 1, '2013-10-31 16:03:49', '2012-11-09 15:30:44'),
(4, 'Administrative', '#', 'Publish', 8, 'No', 0, 1, 1, '2013-10-31 15:38:27', '2012-11-09 15:31:13'),
(5, 'settings', 'configs', 'Publish', 7, 'No', 0, 1, 1, '2013-10-31 16:03:49', '2012-11-09 15:31:28'),
(6, 'orginize menu', 'menu', 'Publish', 6, 'Yes', 2, 1, 1, '2013-10-31 16:03:49', '2012-11-09 16:18:28'),
(14, 'admins', 'adminusers_auth', 'Publish', 3, 'No', 4, 1, 0, '2013-10-31 16:03:49', '2012-11-09 14:25:25'),
(15, 'admins authorize', 'menu_auth', 'Publish', 5, 'No', 4, 1, 1, '2013-10-31 16:04:33', '2012-11-09 14:25:53'),
(19, 'Admins Level', 'adminusers_level', 'Publish', 4, 'No', 4, 1, 1, '2013-10-31 16:03:49', '2012-11-09 16:33:53'),
(56, 'Ref News', 'ref_news', 'Publish', 9, 'No', 2, 1, 1, '2013-10-29 17:30:51', '2013-07-03 01:00:28'),
(76, 'News', 'news', 'Publish', 10, 'No', 2, 1, 0, '2013-10-29 18:09:09', '2013-10-29 18:09:09'),
(77, 'Contents', 'contents', 'Publish', 11, '', 2, 1, 0, '2013-10-31 17:13:19', '2013-10-31 17:13:19');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=162 ;

--
-- Dumping data for table `tbl_menu_auth`
--

INSERT INTO `tbl_menu_auth` (`id`, `adminusers_level_id`, `menu_id`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 1, 1, 0, 0, '2012-11-13 17:58:40', '2012-11-13 17:58:40'),
(2, 1, 2, 0, 0, '2012-11-13 17:58:40', '2012-11-13 17:58:40'),
(111, 2, 15, 1, 0, '2013-02-13 15:27:19', '2013-02-13 15:27:19'),
(4, 1, 4, 0, 0, '2012-11-13 17:58:40', '2012-11-13 17:58:40'),
(6, 1, 5, 1, 0, '2012-11-13 18:26:12', '2012-11-13 18:26:12'),
(7, 1, 6, 1, 0, '2012-11-13 18:26:12', '2012-11-13 18:26:12'),
(15, 1, 14, 1, 0, '2012-11-13 18:26:12', '2012-11-13 18:26:12'),
(16, 1, 15, 1, 0, '2012-11-13 18:26:12', '2012-11-13 18:26:12'),
(17, 1, 19, 1, 0, '2012-11-13 18:26:12', '2012-11-13 18:26:12'),
(18, 7, 1, 1, 0, '2012-11-13 18:27:14', '2012-11-13 18:27:14'),
(19, 7, 2, 1, 0, '2012-11-13 18:27:14', '2012-11-13 18:27:14'),
(150, 2, 2, 1, 0, '2013-07-16 01:41:43', '2013-07-16 01:41:43'),
(43, 7, 23, 1, 0, '2012-11-21 11:33:50', '2012-11-21 11:33:50'),
(47, 7, 4, 1, 0, '2012-11-26 14:43:14', '2012-11-26 14:43:14'),
(48, 7, 14, 1, 0, '2012-11-26 14:43:14', '2012-11-26 14:43:14'),
(49, 7, 19, 1, 0, '2012-11-26 15:03:31', '2012-11-26 15:03:31'),
(50, 7, 15, 1, 0, '2012-11-26 15:03:31', '2012-11-26 15:03:31'),
(110, 2, 19, 1, 0, '2013-02-13 15:27:19', '2013-02-13 15:27:19'),
(127, 1, 55, 1, 0, '2013-07-03 00:08:59', '2013-07-03 01:08:59'),
(104, 2, 50, 1, 0, '2013-02-05 16:46:33', '2013-02-05 16:46:33'),
(112, 2, 53, 2, 0, '2013-02-28 16:18:07', '2013-02-28 16:18:07'),
(57, 2, 28, 1, 0, '2012-12-14 18:42:51', '2012-12-14 18:42:51'),
(59, 1, 30, 1, 0, '2012-12-14 18:47:48', '2012-12-14 18:47:48'),
(93, 2, 47, 1, 0, '2013-01-10 15:23:06', '2013-01-10 15:23:06'),
(61, 2, 29, 1, 0, '2012-12-14 18:48:08', '2012-12-14 18:48:08'),
(62, 2, 30, 1, 0, '2012-12-14 18:48:08', '2012-12-14 18:48:08'),
(108, 2, 52, 1, 0, '2013-02-13 14:15:52', '2013-02-13 14:15:52'),
(65, 1, 33, 1, 0, '2012-12-18 11:37:52', '2012-12-18 11:37:52'),
(67, 2, 33, 1, 0, '2012-12-18 11:38:23', '2012-12-18 11:38:23'),
(102, 2, 49, 1, 0, '2013-02-05 16:44:55', '2013-02-05 16:44:55'),
(72, 1, 38, 1, 0, '2012-12-18 12:47:08', '2012-12-18 12:47:08'),
(89, 2, 44, 1, 0, '2013-01-10 12:28:43', '2013-01-10 12:28:43'),
(88, 1, 44, 1, 0, '2013-01-10 12:28:31', '2013-01-10 12:28:31'),
(75, 2, 36, 1, 0, '2012-12-18 12:48:10', '2012-12-18 12:48:10'),
(76, 2, 37, 1, 0, '2012-12-18 12:48:10', '2012-12-18 12:48:10'),
(77, 2, 38, 1, 0, '2012-12-18 12:48:10', '2012-12-18 12:48:10'),
(79, 2, 39, 1, 0, '2012-12-19 15:42:13', '2012-12-19 15:42:13'),
(106, 2, 51, 1, 0, '2013-02-05 16:53:17', '2013-02-05 16:53:17'),
(82, 1, 40, 1, 0, '2013-01-10 12:15:23', '2013-01-10 12:15:23'),
(83, 1, 41, 1, 0, '2013-01-10 12:15:23', '2013-01-10 12:15:23'),
(84, 2, 40, 1, 0, '2013-01-10 12:15:41', '2013-01-10 12:15:41'),
(85, 2, 41, 1, 0, '2013-01-10 12:15:41', '2013-01-10 12:15:41'),
(87, 2, 43, 1, 0, '2013-01-10 12:19:01', '2013-01-10 12:19:01'),
(109, 2, 14, 1, 0, '2013-02-13 15:27:19', '2013-02-13 15:27:19'),
(96, 2, 1, 1, 0, '2013-01-11 19:03:23', '2013-01-11 19:03:23'),
(117, 2, 55, 1, 0, '2013-05-07 13:58:32', '2013-05-07 01:58:32'),
(98, 2, 4, 1, 0, '2013-01-11 19:03:23', '2013-01-11 19:03:23'),
(100, 2, 23, 1, 0, '2013-01-11 19:05:10', '2013-01-11 19:05:10'),
(114, 2, 54, 2, 0, '2013-03-14 16:56:25', '2013-03-14 16:56:25'),
(151, 2, 56, 1, 0, '2013-10-09 15:53:48', '2013-10-09 15:53:48'),
(152, 1, 56, 1, 0, '2013-10-09 15:53:53', '2013-10-09 15:53:53'),
(153, 15, 1, 1, 0, '2013-10-29 16:49:13', '2013-10-29 16:49:13'),
(154, 15, 2, 1, 0, '2013-10-29 16:49:13', '2013-10-29 16:49:13'),
(155, 15, 56, 1, 0, '2013-10-29 16:49:13', '2013-10-29 16:49:13'),
(156, 1, 76, 1, 0, '2013-10-29 18:09:22', '2013-10-29 18:09:22'),
(157, 2, 76, 1, 0, '2013-10-29 18:09:28', '2013-10-29 18:09:28'),
(158, 15, 76, 1, 0, '2013-10-29 18:09:33', '2013-10-29 18:09:33'),
(159, 2, 77, 1, 0, '2013-10-31 17:13:36', '2013-10-31 17:13:36'),
(160, 1, 77, 1, 0, '2013-10-31 17:13:42', '2013-10-31 17:13:42'),
(161, 15, 77, 1, 0, '2013-10-31 17:14:09', '2013-10-31 17:14:09');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_news`
--

CREATE TABLE IF NOT EXISTS `tbl_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `content` text COLLATE latin1_general_ci NOT NULL,
  `file_image` varchar(255) COLLATE latin1_general_ci NOT NULL,
  `date1` date NOT NULL,
  `date2` datetime NOT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tbl_news`
--

INSERT INTO `tbl_news` (`id`, `title`, `content`, `file_image`, `date1`, `date2`, `publish`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 'Tes News 1', '<p>tes</p>\r\n', '', '2013-10-29', '2013-10-30 13:40:00', 'Publish', 1, 1, '2013-10-30 11:45:54', '2013-10-29 18:38:29'),
(2, 'Test News 2', '<p>Test 3</p>\r\n', '201310310509191DSC_0051 e copy.JPG', '2013-10-30', '2013-10-31 23:00:00', 'Not Publish', 1, 1, '2013-10-31 19:05:15', '2013-10-31 14:51:33');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_news_ref_news`
--

CREATE TABLE IF NOT EXISTS `tbl_news_ref_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_news_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tbl_news_ref_news`
--

INSERT INTO `tbl_news_ref_news` (`id`, `ref_news_id`, `news_id`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(7, 1, 2, 1, 0, '2013-10-31 19:06:35', '2013-10-31 19:06:35'),
(6, 4, 2, 1, 0, '2013-10-31 19:06:35', '2013-10-31 19:06:35');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_ref_news`
--

CREATE TABLE IF NOT EXISTS `tbl_ref_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref_title` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `publish` enum('Not Publish','Publish') COLLATE latin1_general_ci NOT NULL DEFAULT 'Not Publish',
  `user_id` int(11) NOT NULL COMMENT 'admin created',
  `modify_user_id` int(11) NOT NULL,
  `modify_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `create_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_ref_news`
--

INSERT INTO `tbl_ref_news` (`id`, `ref_title`, `publish`, `user_id`, `modify_user_id`, `modify_date`, `create_date`) VALUES
(1, 'Sample', 'Publish', 1, 1, '2013-10-31 18:35:22', '2013-10-09 16:48:02'),
(4, 'Skin', 'Publish', 1, 1, '2013-10-31 18:39:25', '2013-10-31 18:16:46');
