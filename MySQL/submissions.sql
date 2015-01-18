
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 12, 2015 at 09:10 PM
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
-- Table structure for table `submissions`
--

CREATE TABLE IF NOT EXISTS `submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(10) unsigned NOT NULL,
  `verification` int(11) NOT NULL,
  `category` varchar(16) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `alone` int(11) unsigned NOT NULL DEFAULT '0',
  `notalone` int(11) unsigned NOT NULL DEFAULT '0',
  `pending` tinyint(1) NOT NULL DEFAULT '1',
  `submission` text NOT NULL,
  `anonymous` tinyint(1) NOT NULL DEFAULT '0',
  `nsfw` mediumint(9) NOT NULL DEFAULT '0',
  `reported` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`),
  KEY `id_3` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `submissions`
--

INSERT INTO `submissions` (`id`, `uid`, `verification`, `category`, `date`, `alone`, `notalone`, `pending`, `submission`, `anonymous`, `nsfw`, `reported`) VALUES
(0, 1, 0, '1', '2014-01-21 05:00:00', 1306, 24126, 0, 'Am I the only one that thinks this website needs work??', 0, 0, -1),
(1, 1, 0, '1', '2014-07-16 07:14:26', 13, 26, 0, 'Am I the only one testing submission functionality?', 0, 0, 1),
(2, 1, 0, '2', '2014-07-23 21:04:45', 2, 9, 1, 'Am I the only one making multiple questions for the sake of science?', 0, 1, 0),
(3, 1, 0, '2', '2014-07-23 22:01:43', 1, 6, 1, 'Am I the only one that needs a third pending post?', 0, 1, 0),
(4, 1, 0, '2', '2014-07-23 22:04:30', 1, 6, 1, 'Am I the only one who uses "who" after "Am I the only one"?', 0, 1, 0),
(5, 1, 0, '1', '2014-07-24 08:25:33', 0, 6, 1, 'Am I the only one who needs another submission for moderating?', 0, 0, 0),
(6, 7, 0, '1', '2014-08-30 23:06:11', 0, 4, 1, 'Am I the only one who wants this to work already?', 0, 0, 0),
(7, 1, 1234, '1', '2014-11-13 08:51:04', 0, 1, 1, 'Am I the only one who is still making these things?', 0, 0, 0),
(8, 1, 1234, '8', '2014-11-13 08:52:37', 1, 0, 1, 'Am I the only one who likes pancakes?', 0, 0, 0),
(9, 1, 1234, '6', '2014-11-13 08:55:40', 0, 1, 1, 'Am I the only one who pees a blue syrup??', 0, 1, 0),
(10, 7, 1234, '2', '2014-11-13 17:00:39', 0, 1, 1, 'Am I the only one who wants to test this thing?', 0, 0, 0),
(11, 7, 1234, '2', '2014-11-13 17:00:40', 0, 1, 1, 'Am I the only one who wants to test this thing?', 0, 0, 0),
(12, 1, 1234, '9', '2014-11-20 09:20:31', 0, 0, 1, 'Am I the only one who needs to test anonymity?', 1, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
