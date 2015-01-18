
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 12, 2015 at 09:08 PM
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
-- Table structure for table `comment_ratings`
--

CREATE TABLE IF NOT EXISTS `comment_ratings` (
  `cid` bigint(20) unsigned NOT NULL,
  `uid` bigint(20) unsigned NOT NULL,
  `vote` tinyint(1) NOT NULL,
  UNIQUE KEY `cr_index` (`cid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `comment_ratings`
--

INSERT INTO `comment_ratings` (`cid`, `uid`, `vote`) VALUES
(1, 1, 1),
(7, 1, 1),
(7, 7, 1),
(5, 7, 1),
(3, 1, 1),
(2, 1, 1),
(8, 1, -1),
(16, 7, -1),
(19, 7, -1),
(19, 1, 1),
(33, 1, -1),
(33, 7, 1),
(35, 7, 1),
(36, 7, 1),
(38, 7, 1),
(38, 1, 1),
(43, 1, 1),
(42, 1, 1),
(36, 1, 1),
(44, 7, -1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
