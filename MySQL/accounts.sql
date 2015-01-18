
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 12, 2015 at 09:04 PM
-- Server version: 5.1.61
-- PHP Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `u683362690_rtblz`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `username` varchar(16) NOT NULL,
  `password` varchar(32) NOT NULL,
  `cookie_login` varchar(255) NOT NULL,
  `salt` varchar(16) NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `email` varchar(32) NOT NULL,
  `pending_email` varchar(32) NOT NULL,
  `country_id` int(5) NOT NULL DEFAULT '-1',
  `description` varchar(130) NOT NULL,
  `hidelocation` tinyint(1) NOT NULL DEFAULT '0',
  `hiderelated` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `mod_index` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `id_5` (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`username`, `password`, `cookie_login`, `salt`, `id`, `joined`, `last_login`, `email`, `pending_email`, `country_id`, `description`, `hidelocation`, `hiderelated`, `admin`, `mod_index`) VALUES
('PenisPulley', '112670935a8b1a01c30dc55985fff4d5', '', 'P×_Xä’Úb/tÔ"ô', 4, '2014-05-06 21:13:49', '2014-05-23 12:13:19', 'fcktb4703@gmail.com', '', -1, '', 0, 0, 0, 0),
('valobster', '2838b2752b09799b53658edb5bee1939', '', '7$žzlIì@ZÅÐf\r7øÅ', 5, '2014-05-11 06:36:21', '2014-05-20 05:49:42', 'shelbyhouse727@gmail.com', '', -1, '', 0, 0, 0, 0),
('Adam', '3ef5e37d06172b4871766d42ca3e719e', '$2y$10$tRa5V44hR3KRuori32gf/usOSfE629UKMMDgDKnivCOvmPwFie7d.', '<+\\Î^"çö©æ‚Á}`¿', 7, '2014-05-29 22:00:46', '2015-01-08 21:46:01', 'adamlupka17@gmail.com', '', 40, 'I am the founder of Relatablez.', 0, 0, 1, 13),
('Insomniac10102', '670e7657d16133872f87163576168751', '$2y$10$wqwy3nhlX/nlVFZK96OwmOKvB9HBI1hwF/NtSzn/Wvntn/olZmCFq', 'êÔÝîZGørÁWÑ(G\0Éê', 1, '2014-01-21 10:21:44', '2015-01-12 05:05:00', 'tyhckt@yahoo.com', 'ins10102@gmail.com', 236, 'I am the lead developer of Relatablez. Welcome to my profile.', 0, 0, 1, 8),
('Relatablez Staff', '', '', '', 0, '2014-01-19 05:00:00', '2014-08-08 03:37:40', 'Contact@Relatablez.com', '', -1, '', 0, 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
