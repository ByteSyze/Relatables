
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
  `password` varchar(255) NOT NULL,
  `cookie_login` varchar(255) NOT NULL,
  `verification` varchar(32) NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `email` varchar(256) NOT NULL,
  `pending_email` varchar(32) NOT NULL,
  `country_id` int(5) NOT NULL DEFAULT '-1',
  `description` varchar(130) NOT NULL,
  `mod_index` int(10) unsigned NOT NULL DEFAULT '0',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`username`, `password`, `cookie_login`, `id`, `joined`, `last_login`, `email`, `pending_email`, `country_id`, `description`, `mod_index`, `flags`) VALUES
('Adam', '3ef5e37d06172b4871766d42ca3e719e', '$2y$10$tRa5V44hR3KRuori32gf/usOSfE629UKMMDgDKnivCOvmPwFie7d.', 7, '2014-05-29 22:00:46', '2015-01-08 21:46:01', 'adamlupka17@gmail.com', '', 40, 'I am the founder of Relatablez.', 13, 1),
('Insomniac10102', '670e7657d16133872f87163576168751', '$2y$10$wqwy3nhlX/nlVFZK96OwmOKvB9HBI1hwF/NtSzn/Wvntn/olZmCFq', 1, '2014-01-21 10:21:44', '2015-01-12 05:05:00', 'tyhckt@yahoo.com', 'ins10102@gmail.com', 236, 'I am the lead developer of Relatablez. Welcome to my profile.', 8, 1),
('Relatablez Staff', '', '', 2, '2014-01-19 05:00:00', '2014-08-08 03:37:40', 'Contact@Relatablez.com', '', -1, '', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
