
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 12, 2015 at 09:09 PM
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
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sid` bigint(20) unsigned NOT NULL,
  `uid` bigint(20) unsigned NOT NULL,
  `subject` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `message` varchar(140) COLLATE latin1_general_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nid` (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=34 ;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `sid`, `uid`, `subject`, `message`, `date`, `seen`, `deleted`) VALUES
(1, 10, 1, 'Notification Subject', 'This is a test message that is marked as ''read''. Notice the gray circle :D', '2014-07-25 03:00:07', 1, 1),
(2, 0, 1, 'Surprise!', 'Beep. You and I can sign into this account with no password.', '2014-07-25 08:09:50', 1, 1),
(3, 0, 1, 'beep boop', 'Third message; scroll to your heart''s content <3.', '2014-07-25 11:31:24', 1, 1),
(4, 0, 1, 'New Message', 'New test message for finding a good spot to put a "delete" button. There''s gotta be a good place somewhere...', '2014-07-26 04:16:14', 1, 1),
(5, 0, 1, 'Reply from ', 'You have received a reply from  on <a href=''http://www.relatablez.com/post/''>this post</a>.', '2014-10-04 08:25:15', 1, 0),
(6, 0, 1, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-04 08:27:32', 1, 0),
(7, 0, 7, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-04 08:31:03', 1, 0),
(8, 0, 7, 'Reply from Adam', 'You have received a reply from Adam on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-04 17:03:59', 1, 0),
(9, 0, 7, 'Reply from Adam', 'You have received a reply from Adam on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-04 17:04:01', 1, 0),
(10, 0, 7, 'Reply from Adam', 'You have received a reply from Adam on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-08 02:54:22', 1, 0),
(11, 0, 7, 'Reply from Adam', 'You have received a reply from Adam on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-08 02:54:22', 1, 0),
(12, 0, 1, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-08 12:19:07', 1, 0),
(13, 0, 7, 'Reply from Adam', 'You have received a reply from Adam on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-09 00:32:24', 1, 0),
(14, 0, 7, 'Reply from Adam', 'You have received a reply from Adam on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-15 00:40:29', 1, 0),
(15, 0, 7, 'Reply from Adam', 'You have received a reply from Adam on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-15 00:40:31', 1, 0),
(16, 0, 1, 'Reply from Adam', 'You have received a reply from Adam on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-18 17:03:06', 1, 0),
(17, 0, 1, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-23 12:32:33', 1, 0),
(18, 0, 1, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-23 12:33:06', 1, 0),
(19, 0, 1, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-23 12:38:51', 1, 0),
(20, 0, 1, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-23 12:40:23', 1, 0),
(21, 0, 1, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-23 12:41:03', 1, 0),
(22, 0, 1, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-23 12:42:03', 1, 0),
(23, 0, 1, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-23 12:46:04', 1, 0),
(24, 0, 1, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-23 12:47:08', 1, 0),
(25, 0, 1, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-23 12:48:02', 1, 0),
(26, 0, 1, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-23 12:51:46', 1, 0),
(27, 0, 1, 'Reply from Adam', 'You have received a reply from Adam on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-29 20:12:44', 1, 0),
(28, 0, 1, 'Reply from Adam', 'You have received a reply from Adam on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-29 20:13:08', 1, 0),
(29, 0, 7, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-30 07:58:06', 1, 0),
(30, 0, 1, 'Reply from Adam', 'You have received a reply from Adam on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-10-31 22:51:35', 1, 0),
(31, 0, 7, 'Reply from Adam', 'You have received a reply from Adam on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-11-01 01:21:57', 1, 0),
(32, 0, 7, 'Reply from Adam', 'You have received a reply from Adam on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-11-01 01:22:24', 1, 0),
(33, 0, 1, 'Reply from Insomniac', 'You have received a reply from Insomniac10102 on <a href=''http://www.relatablez.com/post/0''>this post</a>.', '2014-11-05 09:16:36', 1, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
