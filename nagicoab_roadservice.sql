-- phpMyAdmin SQL Dump
-- version 3.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 11, 2011 at 01:25 PM
-- Server version: 5.1.58
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `nagicoab_roadservice`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendee`
--

DROP TABLE IF EXISTS `attendee`;
CREATE TABLE IF NOT EXISTS `attendee` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `s_name` varchar(75) NOT NULL,
  `f_name` varchar(150) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `attendee`
--

INSERT INTO `attendee` (`id`, `s_name`, `f_name`, `active`) VALUES
(1, 'Kenrick', 'Kelly/Kenrick', 1),
(2, 'Danilo', 'Werleman/Danilo', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `description`) VALUES
(1, 'Jumpstart'),
(2, 'Flat Tire'),
(3, 'Towing'),
(4, 'Unlock'),
(5, 'Gas'),
(6, 'Battery Change');

-- --------------------------------------------------------

--
-- Table structure for table `service_req`
--

DROP TABLE IF EXISTS `service_req`;
CREATE TABLE IF NOT EXISTS `service_req` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `car` varchar(125) NOT NULL,
  `a_number` varchar(15) NOT NULL,
  `location` varchar(125) NOT NULL,
  `job` varchar(75) NOT NULL,
  `attendee_id` int(11) NOT NULL,
  `insured` tinyint(4) NOT NULL,
  `notes` text NOT NULL,
  `status` varchar(45) NOT NULL,
  `timestamp` varchar(50) NOT NULL,
  `charged` double NOT NULL,
  `pol` varchar(50) NOT NULL,
  `opendt` varchar(30) NOT NULL,
  `closedt` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `service_req`
--

INSERT INTO `service_req` (`id`, `car`, `a_number`, `location`, `job`, `attendee_id`, `insured`, `notes`, `status`, `timestamp`, `charged`, `pol`, `opendt`, `closedt`) VALUES
(1, 'Ford Mustang', 'A-2222', 'Airport', '1', 1, 1, '', '1', '11-10-2011 13:33:02', 0, '', '', ''),
(2, 'Toyota Yaris', 'A-11111', 'Vondelaan 28', '4', 1, 1, '', '1', '11-10-2011 13:45:35', 0, '', '', ''),
(3, 'Mitsubishi L300', 'A-525', 'Vondelaan 28', '5', 1, 0, 'Ran out of gas again', '1', '11-10-2011 13:50:30', 0, '', '', ''),
(4, 'Lexus IS300', 'A-1', 'Noord', '3', 2, 1, 'Accident', '2', '11-10-2011 13:52:30', 0, '', '', ''),
(5, '', '', '', '6', 2, 0, '', '1', '11-11-2011 15:26:14', 0, '', '', ''),
(6, '', '', '', '6', 2, 0, '', '1', '11-11-2011 15:28:27', 0, '', '', ''),
(7, '', '', '', '6', 2, 0, '', '1', '11-11-2011 15:36:27', 0, '', '', ''),
(8, '', '', '', '6', 2, 0, '', '1', '11-11-2011 15:37:04', 0, '', '', ''),
(9, '', '', '', '6', 2, 0, '', '1', '11-11-2011 15:47:33', 0, '', '', ''),
(10, 'van', 'A-2525', 'San', '2', 2, 0, 'ddddd', '2', '11-11-2011 16:02:46', 65, '', '11-11-2011 15:00', '11-11-2011 16:10');

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(75) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `status`) VALUES
(1, 'Open'),
(2, 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `md5_id` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `full_name` tinytext COLLATE latin1_general_ci NOT NULL,
  `user_name` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `user_email` varchar(220) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `user_level` tinyint(4) NOT NULL DEFAULT '1',
  `pwd` varchar(220) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `address` text COLLATE latin1_general_ci NOT NULL,
  `country` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `tel` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `fax` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `website` text COLLATE latin1_general_ci NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `users_ip` varchar(200) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `approved` int(1) NOT NULL DEFAULT '0',
  `activation_code` int(10) NOT NULL DEFAULT '0',
  `banned` int(1) NOT NULL DEFAULT '0',
  `ckey` varchar(220) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `ctime` varchar(220) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `ccountry` varchar(150) COLLATE latin1_general_ci NOT NULL,
  `agent` varchar(100) COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email` (`user_email`),
  FULLTEXT KEY `idx_search` (`full_name`,`address`,`user_email`,`user_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=81 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `md5_id`, `full_name`, `user_name`, `user_email`, `user_level`, `pwd`, `address`, `country`, `tel`, `fax`, `website`, `date`, `users_ip`, `approved`, `activation_code`, `banned`, `ckey`, `ctime`, `ccountry`, `agent`) VALUES
(54, '', 'Kenrick Kelly', 'admin', 'kenrick@caribbeansmarties.com', 5, '8acf0b4d71be47d5ad62cad67ddf7439359ee325cd5afc5e1', 'admin', '', '5924075', '', '', '2010-05-04', '', 1, 0, 0, 'o246qcf', '1321043032', '', 'Nagico'),
(80, '', 'Test Croes', 'test', '', 2, 'e092116af40673bbf2625ef72d80482524fccb2902243ff64', '', 'Aruba', '', '', '', '2011-11-11', '', 1, 0, 0, '', '', 'Aruba', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
