
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
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `pid` bigint(20) unsigned NOT NULL,
  `cid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment` varchar(180) COLLATE latin1_general_ci NOT NULL,
  `uid` bigint(20) unsigned NOT NULL,
  `submitted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL,
  `reported` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`),
  UNIQUE KEY `comment_id` (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=48 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`pid`, `cid`, `comment`, `uid`, `submitted`, `rid`, `deleted`, `reported`) VALUES
(0, 1, 'hey folks', 1, '2014-09-30 08:37:40', 0, 1, 0),
(0, 2, 'test comment', 0, '2014-10-01 09:08:33', 0, 0, 1),
(0, 3, 'Insomniac10102 Test reply', 1, '2014-10-08 11:29:57', 1, 1, 0),
(0, 4, 'Insomniac10102 Beep', 1, '2014-10-08 12:19:07', 1, 1, 0),
(0, 5, 'Testing testing, 1 2...123', 7, '2014-10-09 00:32:08', 0, 1, 0),
(0, 6, 'Adam Oh herro mr test!', 7, '2014-10-09 00:32:24', 5, 1, 0),
(0, 7, 'New comment', 7, '2014-10-15 00:39:33', 0, 1, 0),
(0, 8, 'Adam Test m8', 7, '2014-10-15 00:40:29', 7, 1, 0),
(0, 9, 'Adam Test m8', 7, '2014-10-15 00:40:31', 7, 1, 0),
(0, 10, 'Bip', 1, '2014-10-16 05:38:04', 0, 1, 0),
(0, 11, 'I need a comment to delete', 1, '2014-10-16 06:14:01', 0, 1, 0),
(0, 12, 'I need another comment to delete.', 1, '2014-10-16 06:14:42', 0, 1, 0),
(0, 13, 'Comment', 1, '2014-10-16 08:45:51', 0, 0, -1),
(0, 14, 'Comment', 1, '2014-10-16 08:45:54', 0, 0, 1),
(0, 15, 'Comment', 1, '2014-10-16 08:45:55', 0, 0, 1),
(0, 16, 'Comment', 1, '2014-10-16 08:45:55', 0, 0, 1),
(0, 17, 'Comment', 1, '2014-10-16 08:46:06', 0, 1, 1),
(1, 18, 'Bip', 1, '2014-10-17 09:51:09', 0, 0, 1),
(0, 19, 'Insomniac10102 Test reply', 7, '2014-10-18 17:03:06', 17, 1, 1),
(0, 20, 'Insomniac10102 Beep boop', 1, '2014-10-23 12:32:33', 13, 0, 1),
(0, 21, 'Insomniac10102 Bip', 1, '2014-10-23 12:33:06', 13, 0, -1),
(0, 22, 'Insomniac10102 Boop', 1, '2014-10-23 12:38:51', 15, 0, 1),
(0, 23, 'Insomniac10102 Boop', 1, '2014-10-23 12:40:23', 15, 1, 0),
(0, 24, 'Insomniac10102 Test', 1, '2014-10-23 12:41:03', 15, 0, -1),
(0, 25, 'Insomniac10102 Beep beep', 1, '2014-10-23 12:42:03', 15, 0, -1),
(0, 26, 'Insomniac10102 Latest comment, please go to the end!', 1, '2014-10-23 12:46:04', 15, 0, -1),
(0, 27, 'Insomniac10102 Now remove reply box!', 1, '2014-10-23 12:47:08', 15, 0, -1),
(0, 28, 'Insomniac10102 How bout now?', 1, '2014-10-23 12:48:02', 15, 0, -1),
(0, 29, 'Insomniac10102 Once more, for good measure.', 1, '2014-10-23 12:51:46', 15, 0, 1),
(0, 30, 'Want moar communts', 1, '2014-10-26 00:36:35', 0, 0, -1),
(0, 31, 'Moooooooooar', 1, '2014-10-26 00:36:41', 0, 0, -1),
(0, 32, 'Mrrrrroaaaaaaaaaaaaaa', 1, '2014-10-26 00:36:57', 0, 0, -1),
(0, 33, 'Blip blip', 1, '2014-10-26 00:37:06', 0, 0, -1),
(0, 34, 'Insomniac10102 Helloo sir! ', 7, '2014-10-29 20:12:44', 32, 1, 0),
(0, 35, 'Insomniac10102 TEST', 7, '2014-10-29 20:13:08', 33, 1, 0),
(0, 36, 'Adam Testicle ', 1, '2014-10-30 07:58:06', 33, 0, -1),
(0, 37, 'Insomniac10102 Hello, test', 7, '2014-10-31 22:51:35', 33, 0, -1),
(0, 38, 'Hello this is a test for the reporting feature', 7, '2014-11-01 01:20:21', 0, 0, 1),
(0, 39, 'Adam lel', 7, '2014-11-01 01:21:57', 38, 0, -1),
(0, 40, 'Adam Test', 7, '2014-11-01 01:22:24', 38, 0, -1),
(0, 41, 'Beep', 1, '2014-11-04 09:52:30', 0, 0, -1),
(0, 42, 'Test', 1, '2014-11-04 09:57:03', 0, 0, -1),
(0, 43, 'One more test', 1, '2014-11-04 09:58:55', 0, 1, -1),
(0, 44, 'Insomniac10102 Test.', 1, '2014-11-05 09:16:36', 43, 0, -1),
(1, 45, 'Testing 1', 7, '2014-11-19 01:23:43', 0, 1, 1),
(1, 46, 'New comment', 7, '2014-11-19 02:22:27', 0, 0, 1),
(1, 47, 'Test', 7, '2014-12-21 17:23:38', 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
