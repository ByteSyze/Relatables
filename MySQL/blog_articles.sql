
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 12, 2015 at 09:07 PM
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
-- Table structure for table `blog_articles`
--

CREATE TABLE IF NOT EXISTS `blog_articles` (
  `id` smallint(16) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL,
  `title` varchar(60) COLLATE latin1_general_ci NOT NULL,
  `content` text COLLATE latin1_general_ci NOT NULL,
  `image` varchar(120) COLLATE latin1_general_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `blog_articles`
--

INSERT INTO `blog_articles` (`id`, `uid`, `title`, `content`, `image`, `created`, `deleted`) VALUES
(1, 1, 'Welcome to the New Relatablez Blog!', '<b>Hello, World!</b><br>\r\n<br>\r\nThis is an example article for the latest addition to our relatable content - blogs!', '/images/2014/9/26/desert.jpg', '2014-09-26 12:06:57', 0),
(2, 5, 'This is a Test Article', 'Welcome to Relatablez!<br>\r\n<br>\r\nBlah Blah Blah..', '/images/2014/9/26/Internet-IPv6.jpg', '2014-09-26 13:20:46', 1),
(3, 7, 'Test', 'dsdsds<br>\r\n<br>\r\nsd<br>\r\ns<br>\r\nds<br>\r\nds<br>\r\n<br>\r\nds<br>\r\nds<br>\r\n', '/images/2014/9/26/Internet-IPv6.jpg', '2014-09-26 13:22:40', 1),
(4, 7, 'Test for image placement', 'I deleted ur amazing horse by accident. plz forgive<br>\r\n<br>\r\n<div style=''color:white;text-align:center;background:#CFCC80;width:100px;height:47px;font-size:40px''><i>BAM</i></div>', '', '2014-09-26 13:31:51', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
