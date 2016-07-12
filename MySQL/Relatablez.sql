
-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 08, 2015 at 04:59 PM
-- Server version: 5.1.73
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
  `verification` varchar(255) NOT NULL,
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `joined` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `email` varchar(32) NOT NULL,
  `pending_email` varchar(32) NOT NULL,
  `country_id` int(5) NOT NULL DEFAULT '-1',
  `description` varchar(130) NOT NULL,
  `mod_index` int(10) unsigned NOT NULL DEFAULT '0',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '18',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`username`, `password`, `cookie_login`, `verification`, `id`, `joined`, `last_login`, `email`, `pending_email`, `country_id`, `description`, `mod_index`, `flags`) VALUES
('Adam', '$2y$10$6PGGaIzGL4vfWyyCaXYxcOAEqBhkJoguX/AxrfSqQIFyK6LO3Y0ri', '$2y$10$2QP6sPy3lFnwj/0v2xTsA.5Nc8Bmn3Ulf058HebCPStyAHtTkMrmO', 'n/a', 7, '2014-05-29 22:00:46', '2015-06-08 02:17:44', 'adamlupka17@gmail.com', '', 40, 'Co-Founder of Relatablez', 13, 3),
('Insomniac10102', '$2y$10$A1Sg8EIDDYW.u5BMjOFQ3OC0EpiXcKOMRUW0Q33ba22c7lRT/XYpi', '$2y$10$6USkEjoyxhMipm4gdZ6XVeD8y5yjDgCpsivBzIot88dCgHi08.KYK', 'n/a', 1, '2014-01-21 10:21:44', '2015-06-08 20:55:28', 'ins10102@gmail.com', '', 236, 'I am the lead developer of Relatablez. Welcome to my profile.', 8, 3),
('Relatablez Staff', '', '$2y$10$FhdTUrRJ3CWHnreah3TqFOiWjugOY0tu4fZPXgTb5xIY8jyGOavAC', '', 2, '2014-01-15 05:00:00', '2015-02-18 06:44:49', 'Contact@Relatablez.com', '', -1, '', 0, 0),
('jadensmith', '$2y$10$0kvj6yNgBIXvzSkv/Z0KQek6fxFl7LwJXjpkQ1kCZzcW.3FIwg/Rm', '', '', 9, '2015-04-25 04:43:56', '2015-04-25 04:43:56', 'bla', '', 236, 'How Can Mirrors Be Real If Our Eyes Aren''t Real', 0, 0),
('PantslessDev', '$2y$10$8Q4eaR7Ynabrf7igI.gwiO.TtnMQaZ8WqDFdDaFKRiSXXl0Qrpdbi', '', '', 13, '2015-05-12 00:23:19', '2015-05-12 00:23:19', 'tyhckt@yahoo.com', '', -1, '', 0, 0);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `blog_articles`
--

INSERT INTO `blog_articles` (`id`, `uid`, `title`, `content`, `image`, `created`, `deleted`) VALUES
(1, 1, 'Welcome to the New Relatablez Blog!', '<b>Hello, World!</b><br>\r\n<br>\r\nThis is an example article for the latest addition to our relatable content - blogs!', '/images/2014/9/26/desert.jpg', '2014-09-26 12:06:57', 0),
(2, 5, 'This is a Test Article', 'Welcome to Relatablez!<br>\r\n<br>\r\nBlah Blah Blah..', '/images/2014/9/26/Internet-IPv6.jpg', '2014-09-26 13:20:46', 1),
(3, 7, 'Test', 'dsdsds<br>\r\n<br>\r\nsd<br>\r\ns<br>\r\nds<br>\r\nds<br>\r\n<br>\r\nds<br>\r\nds<br>\r\n', '/images/2014/9/26/Internet-IPv6.jpg', '2014-09-26 13:22:40', 1),
(4, 7, 'Test for image placement', 'I deleted ur amazing horse by accident. plz forgive<br>\r\n<br>\r\n<div style=''color:white;text-align:center;background:#CFCC80;width:100px;height:47px;font-size:40px''><i>BAM</i></div>', '', '2014-09-26 13:31:51', 1),
(5, 1, 'Does This Thing Still Work?!', '<b>Who Knows!</b> It''s been over 7 months since we last used it, and anything could''ve changed since then...<br>\r\n<br>\r\nLike my thingy?', '/images/2015/5/19/tash.jpg', '2015-05-19 03:57:31', 0);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=69 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`pid`, `cid`, `comment`, `uid`, `submitted`, `rid`, `deleted`, `reported`) VALUES
(0, 1, 'hey folks', 1, '2014-09-30 08:37:40', 0, 1, 0),
(0, 2, 'test comment', 0, '2014-10-01 09:08:33', 0, 1, 1),
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
(0, 50, 'Testing comments', 1, '2015-02-26 19:40:46', 0, 0, 0),
(0, 51, 'Ye', 7, '2015-03-20 21:04:42', 0, 0, 0),
(0, 52, 'Ye', 7, '2015-03-20 21:04:44', 0, 0, 0),
(0, 53, 'This is a comment!', 7, '2015-03-26 21:24:24', 0, 0, 0),
(0, 54, 'dasdnasod', 7, '2015-03-26 21:24:41', 0, 0, 0),
(0, 55, '1234512', 7, '2015-03-28 03:01:50', 0, 0, 0),
(0, 56, 'Test', 1, '2015-03-31 02:29:14', 0, 0, 0),
(0, 57, 'Insomniac10102 Hello bro', 7, '2015-04-02 04:34:37', 56, 0, 0),
(0, 63, 'Insomniac10102 u suck', 1, '2015-04-21 05:57:25', 56, 0, 0);

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
(44, 7, -1),
(57, 7, 1),
(56, 7, 100),
(60, 1, 1),
(59, 1, 1),
(62, 1, -1),
(61, 1, 1),
(55, 1, 110),
(66, 7, -1),
(59, 7, -1);

-- --------------------------------------------------------

--
-- Table structure for table `comment_reports`
--

CREATE TABLE IF NOT EXISTS `comment_reports` (
  `uid` bigint(20) unsigned NOT NULL,
  `id` bigint(20) unsigned NOT NULL,
  UNIQUE KEY `c_reports_index` (`uid`,`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `comment_reports`
--

INSERT INTO `comment_reports` (`uid`, `id`) VALUES
(1, 2),
(2, 33);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `country_id` int(5) NOT NULL AUTO_INCREMENT,
  `iso2` char(2) DEFAULT NULL,
  `short_name` varchar(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=251 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_id`, `iso2`, `short_name`) VALUES
(1, 'AF', 'Afghanistan'),
(2, 'AX', 'Aland Islands'),
(3, 'AL', 'Albania'),
(4, 'DZ', 'Algeria'),
(5, 'AS', 'American Samoa'),
(6, 'AD', 'Andorra'),
(7, 'AO', 'Angola'),
(8, 'AI', 'Anguilla'),
(9, 'AQ', 'Antarctica'),
(10, 'AG', 'Antigua and Barbuda'),
(11, 'AR', 'Argentina'),
(12, 'AM', 'Armenia'),
(13, 'AW', 'Aruba'),
(14, 'AU', 'Australia'),
(15, 'AT', 'Austria'),
(16, 'AZ', 'Azerbaijan'),
(17, 'BS', 'Bahamas'),
(18, 'BH', 'Bahrain'),
(19, 'BD', 'Bangladesh'),
(20, 'BB', 'Barbados'),
(21, 'BY', 'Belarus'),
(22, 'BE', 'Belgium'),
(23, 'BZ', 'Belize'),
(24, 'BJ', 'Benin'),
(25, 'BM', 'Bermuda'),
(26, 'BT', 'Bhutan'),
(27, 'BO', 'Bolivia'),
(28, 'BQ', 'Bonaire, Sint Eustatius and Saba'),
(29, 'BA', 'Bosnia and Herzegovina'),
(30, 'BW', 'Botswana'),
(31, 'BV', 'Bouvet Island'),
(32, 'BR', 'Brazil'),
(33, 'IO', 'British Indian Ocean Territory'),
(34, 'BN', 'Brunei'),
(35, 'BG', 'Bulgaria'),
(36, 'BF', 'Burkina Faso'),
(37, 'BI', 'Burundi'),
(38, 'KH', 'Cambodia'),
(39, 'CM', 'Cameroon'),
(40, 'CA', 'Canada'),
(41, 'CV', 'Cape Verde'),
(42, 'KY', 'Cayman Islands'),
(43, 'CF', 'Central African Republic'),
(44, 'TD', 'Chad'),
(45, 'CL', 'Chile'),
(46, 'CN', 'China'),
(47, 'CX', 'Christmas Island'),
(48, 'CC', 'Cocos (Keeling) Islands'),
(49, 'CO', 'Colombia'),
(50, 'KM', 'Comoros'),
(51, 'CG', 'Congo'),
(52, 'CK', 'Cook Islands'),
(53, 'CR', 'Costa Rica'),
(54, 'CI', 'Cote d''ivoire (Ivory Coast)'),
(55, 'HR', 'Croatia'),
(56, 'CU', 'Cuba'),
(57, 'CW', 'Curacao'),
(58, 'CY', 'Cyprus'),
(59, 'CZ', 'Czech Republic'),
(60, 'CD', 'Democratic Republic of the Congo'),
(61, 'DK', 'Denmark'),
(62, 'DJ', 'Djibouti'),
(63, 'DM', 'Dominica'),
(64, 'DO', 'Dominican Republic'),
(65, 'EC', 'Ecuador'),
(66, 'EG', 'Egypt'),
(67, 'SV', 'El Salvador'),
(68, 'GQ', 'Equatorial Guinea'),
(69, 'ER', 'Eritrea'),
(70, 'EE', 'Estonia'),
(71, 'ET', 'Ethiopia'),
(72, 'FK', 'Falkland Islands (Malvinas)'),
(73, 'FO', 'Faroe Islands'),
(74, 'FJ', 'Fiji'),
(75, 'FI', 'Finland'),
(76, 'FR', 'France'),
(77, 'GF', 'French Guiana'),
(78, 'PF', 'French Polynesia'),
(79, 'TF', 'French Southern Territories'),
(80, 'GA', 'Gabon'),
(81, 'GM', 'Gambia'),
(82, 'GE', 'Georgia'),
(83, 'DE', 'Germany'),
(84, 'GH', 'Ghana'),
(85, 'GI', 'Gibraltar'),
(86, 'GR', 'Greece'),
(87, 'GL', 'Greenland'),
(88, 'GD', 'Grenada'),
(89, 'GP', 'Guadaloupe'),
(90, 'GU', 'Guam'),
(91, 'GT', 'Guatemala'),
(92, 'GG', 'Guernsey'),
(93, 'GN', 'Guinea'),
(94, 'GW', 'Guinea-Bissau'),
(95, 'GY', 'Guyana'),
(96, 'HT', 'Haiti'),
(97, 'HM', 'Heard Island and McDonald Islands'),
(98, 'HN', 'Honduras'),
(99, 'HK', 'Hong Kong'),
(100, 'HU', 'Hungary'),
(101, 'IS', 'Iceland'),
(102, 'IN', 'India'),
(103, 'ID', 'Indonesia'),
(104, 'IR', 'Iran'),
(105, 'IQ', 'Iraq'),
(106, 'IE', 'Ireland'),
(107, 'IM', 'Isle of Man'),
(108, 'IL', 'Israel'),
(109, 'IT', 'Italy'),
(110, 'JM', 'Jamaica'),
(111, 'JP', 'Japan'),
(112, 'JE', 'Jersey'),
(113, 'JO', 'Jordan'),
(114, 'KZ', 'Kazakhstan'),
(115, 'KE', 'Kenya'),
(116, 'KI', 'Kiribati'),
(117, 'XK', 'Kosovo'),
(118, 'KW', 'Kuwait'),
(119, 'KG', 'Kyrgyzstan'),
(120, 'LA', 'Laos'),
(121, 'LV', 'Latvia'),
(122, 'LB', 'Lebanon'),
(123, 'LS', 'Lesotho'),
(124, 'LR', 'Liberia'),
(125, 'LY', 'Libya'),
(126, 'LI', 'Liechtenstein'),
(127, 'LT', 'Lithuania'),
(128, 'LU', 'Luxembourg'),
(129, 'MO', 'Macao'),
(130, 'MK', 'Macedonia'),
(131, 'MG', 'Madagascar'),
(132, 'MW', 'Malawi'),
(133, 'MY', 'Malaysia'),
(134, 'MV', 'Maldives'),
(135, 'ML', 'Mali'),
(136, 'MT', 'Malta'),
(137, 'MH', 'Marshall Islands'),
(138, 'MQ', 'Martinique'),
(139, 'MR', 'Mauritania'),
(140, 'MU', 'Mauritius'),
(141, 'YT', 'Mayotte'),
(142, 'MX', 'Mexico'),
(143, 'FM', 'Micronesia'),
(144, 'MD', 'Moldava'),
(145, 'MC', 'Monaco'),
(146, 'MN', 'Mongolia'),
(147, 'ME', 'Montenegro'),
(148, 'MS', 'Montserrat'),
(149, 'MA', 'Morocco'),
(150, 'MZ', 'Mozambique'),
(151, 'MM', 'Myanmar (Burma)'),
(152, 'NA', 'Namibia'),
(153, 'NR', 'Nauru'),
(154, 'NP', 'Nepal'),
(155, 'NL', 'Netherlands'),
(156, 'NC', 'New Caledonia'),
(157, 'NZ', 'New Zealand'),
(158, 'NI', 'Nicaragua'),
(159, 'NE', 'Niger'),
(160, 'NG', 'Nigeria'),
(161, 'NU', 'Niue'),
(162, 'NF', 'Norfolk Island'),
(163, 'KP', 'North Korea'),
(164, 'MP', 'Northern Mariana Islands'),
(165, 'NO', 'Norway'),
(166, 'OM', 'Oman'),
(167, 'PK', 'Pakistan'),
(168, 'PW', 'Palau'),
(169, 'PS', 'Palestine'),
(170, 'PA', 'Panama'),
(171, 'PG', 'Papua New Guinea'),
(172, 'PY', 'Paraguay'),
(173, 'PE', 'Peru'),
(174, 'PH', 'Phillipines'),
(175, 'PN', 'Pitcairn'),
(176, 'PL', 'Poland'),
(177, 'PT', 'Portugal'),
(178, 'PR', 'Puerto Rico'),
(179, 'QA', 'Qatar'),
(180, 'RE', 'Reunion'),
(181, 'RO', 'Romania'),
(182, 'RU', 'Russia'),
(183, 'RW', 'Rwanda'),
(184, 'BL', 'Saint Barthelemy'),
(185, 'SH', 'Saint Helena'),
(186, 'KN', 'Saint Kitts and Nevis'),
(187, 'LC', 'Saint Lucia'),
(188, 'MF', 'Saint Martin'),
(189, 'PM', 'Saint Pierre and Miquelon'),
(190, 'VC', 'Saint Vincent and the Grenadines'),
(191, 'WS', 'Samoa'),
(192, 'SM', 'San Marino'),
(193, 'ST', 'Sao Tome and Principe'),
(194, 'SA', 'Saudi Arabia'),
(195, 'SN', 'Senegal'),
(196, 'RS', 'Serbia'),
(197, 'SC', 'Seychelles'),
(198, 'SL', 'Sierra Leone'),
(199, 'SG', 'Singapore'),
(200, 'SX', 'Sint Maarten'),
(201, 'SK', 'Slovakia'),
(202, 'SI', 'Slovenia'),
(203, 'SB', 'Solomon Islands'),
(204, 'SO', 'Somalia'),
(205, 'ZA', 'South Africa'),
(206, 'GS', 'South Georgia and the South Sandwich Islands'),
(207, 'KR', 'South Korea'),
(208, 'SS', 'South Sudan'),
(209, 'ES', 'Spain'),
(210, 'LK', 'Sri Lanka'),
(211, 'SD', 'Sudan'),
(212, 'SR', 'Suriname'),
(213, 'SJ', 'Svalbard and Jan Mayen'),
(214, 'SZ', 'Swaziland'),
(215, 'SE', 'Sweden'),
(216, 'CH', 'Switzerland'),
(217, 'SY', 'Syria'),
(218, 'TW', 'Taiwan'),
(219, 'TJ', 'Tajikistan'),
(220, 'TZ', 'Tanzania'),
(221, 'TH', 'Thailand'),
(222, 'TL', 'Timor-Leste (East Timor)'),
(223, 'TG', 'Togo'),
(224, 'TK', 'Tokelau'),
(225, 'TO', 'Tonga'),
(226, 'TT', 'Trinidad and Tobago'),
(227, 'TN', 'Tunisia'),
(228, 'TR', 'Turkey'),
(229, 'TM', 'Turkmenistan'),
(230, 'TC', 'Turks and Caicos Islands'),
(231, 'TV', 'Tuvalu'),
(232, 'UG', 'Uganda'),
(233, 'UA', 'Ukraine'),
(234, 'AE', 'United Arab Emirates'),
(235, 'GB', 'United Kingdom'),
(236, 'US', 'United States'),
(237, 'UM', 'United States Minor Outlying Islands'),
(238, 'UY', 'Uruguay'),
(239, 'UZ', 'Uzbekistan'),
(240, 'VU', 'Vanuatu'),
(241, 'VA', 'Vatican City'),
(242, 'VE', 'Venezuela'),
(243, 'VN', 'Vietnam'),
(244, 'VG', 'Virgin Islands, British'),
(245, 'VI', 'Virgin Islands, US'),
(246, 'WF', 'Wallis and Futuna'),
(247, 'EH', 'Western Sahara'),
(248, 'YE', 'Yemen'),
(249, 'ZM', 'Zambia'),
(250, 'ZW', 'Zimbabwe');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` bigint(22) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(22) unsigned NOT NULL,
  `message` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `href` varchar(128) COLLATE latin1_general_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `uid`, `message`, `href`, `created`, `seen`, `deleted`) VALUES
(4, 1, 'Reply from Insomniac10102', '/post/12#c64', '2015-04-21 06:06:41', 1, 0),
(5, 1, 'Reply from Insomniac10102', '/post/12&lc=65', '2015-05-16 07:02:58', 1, 0),
(6, 7, 'Reply from Adam', '/post/12&lc=67', '2015-06-04 00:37:24', 0, 0),
(7, 7, 'Reply from Adam', '/post/12&lc=68', '2015-06-04 00:37:28', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `qotw`
--

CREATE TABLE IF NOT EXISTS `qotw` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `question` varchar(300) COLLATE latin1_general_ci NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `qotw`
--

INSERT INTO `qotw` (`id`, `question`, `created`) VALUES
(1, 'Are you afraid of the dark?', '2014-09-16 10:03:37');

-- --------------------------------------------------------

--
-- Table structure for table `qotw_options`
--

CREATE TABLE IF NOT EXISTS `qotw_options` (
  `qid` mediumint(8) unsigned NOT NULL,
  `id` tinyint(1) NOT NULL COMMENT 'ID is in relation to the qotw this belongs to.',
  `answer` varchar(30) COLLATE latin1_general_ci NOT NULL,
  UNIQUE KEY `unique_index` (`qid`,`answer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `qotw_options`
--

INSERT INTO `qotw_options` (`qid`, `id`, `answer`) VALUES
(1, 0, 'Yes'),
(1, 1, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `qotw_votes`
--

CREATE TABLE IF NOT EXISTS `qotw_votes` (
  `uid` bigint(20) unsigned NOT NULL,
  `v` tinyint(1) NOT NULL,
  `qid` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `qotw_votes_key` (`qid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `qotw_votes`
--

INSERT INTO `qotw_votes` (`uid`, `v`, `qid`) VALUES
(1, 1, 1),
(2, 0, 1),
(7, 0, 1),
(10, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `related`
--

CREATE TABLE IF NOT EXISTS `related` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pid` bigint(20) unsigned NOT NULL,
  `uid` bigint(20) unsigned NOT NULL,
  `alone` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `related_key` (`pid`,`uid`),
  KEY `pid` (`pid`,`uid`),
  KEY `pid_2` (`pid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `related`
--

INSERT INTO `related` (`id`, `pid`, `uid`, `alone`) VALUES
(1, 0, 1, 0),
(2, 0, 3, 0),
(3, 0, 0, 0),
(6, 0, 7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE IF NOT EXISTS `replies` (
  `pid` bigint(20) unsigned NOT NULL,
  `cid` bigint(20) unsigned NOT NULL,
  `rid` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL,
  `ruid` bigint(20) unsigned NOT NULL,
  `comment` varchar(180) COLLATE latin1_general_ci NOT NULL,
  `submitted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `reported` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `rid` (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`pid`, `cid`, `rid`, `uid`, `ruid`, `comment`, `submitted`, `deleted`, `reported`) VALUES
(5, 62, 1, 1, 1, 'Test', '2015-04-08 05:54:07', 0, 0);

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
  `media` varchar(128) DEFAULT NULL,
  `mediatype` varchar(5) NOT NULL DEFAULT 'none',
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
(0, 1, 0, '2', '2014-01-21 05:00:00', 1306, 24126, 0, 'Am I the only one that thinks this website needs work??', 0, 0, -1);

-- --------------------------------------------------------

--
-- Table structure for table `submission_reports`
--

CREATE TABLE IF NOT EXISTS `submission_reports` (
  `uid` bigint(20) unsigned NOT NULL,
  `id` bigint(20) unsigned NOT NULL,
  UNIQUE KEY `s_reports_index` (`id`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Dumping data for table `submission_reports`
--

INSERT INTO `submission_reports` (`uid`, `id`) VALUES
(10, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
