-- phpMyAdmin SQL Dump
-- version 3.3.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 29, 2011 at 12:12 AM
-- Server version: 5.1.50
-- PHP Version: 5.2.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `v_vmrpro`
--

-- --------------------------------------------------------

--
-- Table structure for table `vmr_accounts`
--

DROP TABLE IF EXISTS `vmr_accounts`;
CREATE TABLE IF NOT EXISTS `vmr_accounts` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_username` varchar(255) DEFAULT NULL,
  `account_password` varchar(255) DEFAULT NULL,
  `account_email` varchar(255) DEFAULT NULL,
  `account_birth_date` date DEFAULT NULL,
  `account_tmp_password` varchar(255) DEFAULT NULL,
  `account_tmp_email` varchar(255) DEFAULT NULL,
  `account_tmp_key` varchar(255) DEFAULT NULL,
  `account_create` datetime DEFAULT NULL,
  `account_last_login` datetime DEFAULT NULL,
  `account_last_session` varchar(255) DEFAULT NULL,
  `account_level` int(1) NOT NULL DEFAULT '4' COMMENT '0=super admin, 1=admin, 2=mod, 3=vip, 4=member',
  `account_status` int(1) NOT NULL DEFAULT '0' COMMENT '0=disable, 1=enable',
  `account_paid` int(1) NOT NULL DEFAULT '0' COMMENT '0=no, 1=yes',
  `account_paid_expire` datetime DEFAULT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `vmr_accounts`
--

INSERT INTO `vmr_accounts` (`account_id`, `account_username`, `account_password`, `account_email`, `account_birth_date`, `account_tmp_password`, `account_tmp_email`, `account_tmp_key`, `account_create`, `account_last_login`, `account_last_session`, `account_level`, `account_status`, `account_paid`, `account_paid_expire`) VALUES
(1, 'admin', 'fffe106aed38aff2fac821e12d94fee6', 'no@email.com', NULL, NULL, NULL, NULL, '2011-03-23 10:09:58', '2011-03-28 21:36:20', '71bfe615c1654a27986e88ff8011d792', 0, 1, 0, NULL),
(12, 'someadmin', '0753dd293e13e26fdc715d0acb336368', 'a@none.com', NULL, NULL, NULL, NULL, '2011-03-28 23:10:07', NULL, NULL, 1, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vmr_chapters`
--

DROP TABLE IF EXISTS `vmr_chapters`;
CREATE TABLE IF NOT EXISTS `vmr_chapters` (
  `chapter_id` int(11) NOT NULL AUTO_INCREMENT,
  `story_id` int(11) DEFAULT NULL,
  `chapter_order` decimal(10,1) NOT NULL DEFAULT '1.0',
  `chapter_name` varchar(255) DEFAULT NULL,
  `scanlator` varchar(255) DEFAULT NULL,
  `chapter_uri` varchar(255) DEFAULT NULL,
  `chapter_add` datetime DEFAULT NULL,
  `chapter_update` datetime DEFAULT NULL,
  `chapter_enable` int(1) NOT NULL DEFAULT '0' COMMENT '0=no, 1=yes',
  PRIMARY KEY (`chapter_id`),
  KEY `story_id` (`story_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `vmr_chapters`
--

INSERT INTO `vmr_chapters` (`chapter_id`, `story_id`, `chapter_order`, `chapter_name`, `scanlator`, `chapter_uri`, `chapter_add`, `chapter_update`, `chapter_enable`) VALUES
(1, 1, 1.0, '01 love happened at school', 'jump', '01-love-happened-at-school', '2011-03-27 20:50:54', '2011-03-28 21:54:11', 1),
(2, 1, 2.0, '02 the furious wind', '', '02-the-furious-wind', '2011-03-27 20:51:30', '2011-03-28 21:54:21', 1),
(3, 1, 3.0, '03 shadow&#039;s eye', '', '03-shadows-eye', '2011-03-27 20:51:54', '2011-03-28 21:54:29', 1),
(4, 1, 4.0, '04 a small heart is torn', 'jump', '04-a-small-heart-is-torn', '2011-03-27 20:53:48', '2011-03-28 21:54:37', 1),
(5, 1, 5.0, '05 bury your voice in the ditch', 'jump', '05-bury-your-voice-in-the-ditch', '2011-03-27 20:54:26', '2011-03-28 21:54:43', 1),
(6, 2, 41.0, '41 party', 'evil genius', '41-party', '2011-03-27 21:15:00', '2011-03-28 21:48:58', 1),
(7, 2, 42.0, '42 the next room', 'evil genius', '42-the-next-room', '2011-03-27 21:15:46', '2011-03-28 21:48:53', 1),
(8, 2, 43.0, '43 warrior', 'evil genius', '43-warrior', '2011-03-27 21:16:24', '2011-03-28 21:48:46', 1),
(9, 2, 44.0, '44 orgy', 'evil genius', '44-orgy', '2011-03-27 21:16:54', '2011-03-28 21:48:37', 1),
(10, 3, 1.0, '0001 an incident', '', '0001-an-incident', '2011-03-27 22:24:35', '2011-03-28 21:55:05', 1),
(11, 3, 2.0, '0002 an inexplicable room', '', '0002-an-inexplicable-room', '2011-03-27 22:25:30', '2011-03-28 21:55:10', 1),
(12, 3, 3.0, '0003 the naked suicide girl', '', '0003-the-naked-suicide-girl', '2011-03-27 22:26:13', '2011-03-28 21:55:18', 1),
(13, 3, 4.0, '0004 the black ball&#039;s orders', 'gatsu', '0004-the-black-balls-orders', '2011-03-27 22:27:06', '2011-03-28 22:17:04', 1),
(14, 2, 45.0, '45 cleanly at the end', 'evil genius', '45-cleanly-at-the-end', '2011-03-28 21:46:43', '2011-03-28 21:46:43', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vmr_chapter_images`
--

DROP TABLE IF EXISTS `vmr_chapter_images`;
CREATE TABLE IF NOT EXISTS `vmr_chapter_images` (
  `chapter_image_id` int(11) NOT NULL AUTO_INCREMENT,
  `chapter_id` int(11) DEFAULT NULL,
  `story_id` int(11) DEFAULT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `image_order` int(5) DEFAULT NULL,
  PRIMARY KEY (`chapter_image_id`),
  KEY `chapter_id` (`chapter_id`),
  KEY `story_id` (`story_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=372 ;

--
-- Dumping data for table `vmr_chapter_images`
--

INSERT INTO `vmr_chapter_images` (`chapter_image_id`, `chapter_id`, `story_id`, `image_file`, `image_order`) VALUES
(1, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 Cover.jpg', 1),
(2, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg004.jpg', 2),
(3, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg005.jpg', 3),
(4, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg006.jpg', 4),
(5, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg007.jpg', 5),
(6, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg008.jpg', 6),
(7, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg009.jpg', 7),
(8, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg010.jpg', 8),
(9, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg011.jpg', 9),
(10, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg012.jpg', 10),
(11, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg013.jpg', 11),
(12, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg014.jpg', 12),
(13, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg015.jpg', 13),
(14, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg016.jpg', 14),
(15, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg017.jpg', 15),
(16, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg018.jpg', 16),
(17, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg019.jpg', 17),
(18, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg020.jpg', 18),
(19, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg021.jpg', 19),
(20, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg022.jpg', 20),
(21, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg023.jpg', 21),
(22, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg024.jpg', 22),
(23, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg025.jpg', 23),
(24, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg026.jpg', 24),
(25, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg027.jpg', 25),
(26, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg028.jpg', 26),
(27, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg029.jpg', 27),
(28, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg030.jpg', 28),
(29, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg031.jpg', 29),
(30, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg032.jpg', 30),
(31, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg033.jpg', 31),
(32, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg034.jpg', 32),
(33, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg035.jpg', 33),
(34, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg036.jpg', 34),
(35, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg037.jpg', 35),
(36, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg038.jpg', 36),
(37, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg039.jpg', 37),
(38, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg040.jpg', 38),
(39, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg041.jpg', 39),
(40, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg042.jpg', 40),
(41, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg043.jpg', 41),
(42, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg044.jpg', 42),
(43, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg045.jpg', 43),
(44, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg046.jpg', 44),
(45, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg047.jpg', 45),
(46, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg048.jpg', 46),
(47, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg049.jpg', 47),
(48, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg050-051.jpg', 48),
(49, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg051.jpg', 49),
(50, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg052.jpg', 50),
(51, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg053.jpg', 51),
(52, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg054.jpg', 52),
(53, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg055.jpg', 53),
(54, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg056.jpg', 54),
(55, 1, 1, 'client/manga/is-full-test/01-love-happened-at-school/I''''s Vol01 Ch001 pg057.jpg', 55),
(56, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg058.jpg', 1),
(57, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg059.jpg', 2),
(58, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg060.jpg', 3),
(59, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg061.jpg', 4),
(60, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg062.jpg', 5),
(61, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg063.jpg', 6),
(62, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg064.jpg', 7),
(63, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg065.jpg', 8),
(64, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg066.jpg', 9),
(65, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg067.jpg', 10),
(66, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg068.jpg', 11),
(67, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg069.jpg', 12),
(68, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg070.jpg', 13),
(69, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg071.jpg', 14),
(70, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg072.jpg', 15),
(71, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg073.jpg', 16),
(72, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg074.jpg', 17),
(73, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg075.jpg', 18),
(74, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg076.jpg', 19),
(75, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg077.jpg', 20),
(76, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg078.jpg', 21),
(77, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg079.jpg', 22),
(78, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg080.jpg', 23),
(79, 2, 1, 'client/manga/is-full-test/02-the-furious-wind/I''''s Vol01 Ch002 pg081.jpg', 24),
(80, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg083.jpg', 1),
(81, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg084.jpg', 2),
(82, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg085.jpg', 3),
(83, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg086.jpg', 4),
(84, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg087.jpg', 5),
(85, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg088.jpg', 6),
(86, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg089.jpg', 7),
(87, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg090.jpg', 8),
(88, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg091.jpg', 9),
(89, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg092.jpg', 10),
(90, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg093.jpg', 11),
(91, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg094.jpg', 12),
(92, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg095.jpg', 13),
(93, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg096.jpg', 14),
(94, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg097.jpg', 15),
(95, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg098.jpg', 16),
(96, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg099.jpg', 17),
(97, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg100.jpg', 18),
(98, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg101.jpg', 19),
(99, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg102.jpg', 20),
(100, 3, 1, 'client/manga/is-full-test/03-shadows-eye/I''''s Vol01 Ch003 pg103.jpg', 21),
(101, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''''s Vol01 Ch004 p109-110.jpg', 1),
(102, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p105.jpg', 2),
(103, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p106.jpg', 3),
(104, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p107.jpg', 4),
(105, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p108.jpg', 5),
(106, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p111.jpg', 6),
(107, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p112.jpg', 7),
(108, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p113.jpg', 8),
(109, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p114.jpg', 9),
(110, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p115.jpg', 10),
(111, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p116.jpg', 11),
(112, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p117.jpg', 12),
(113, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p118.jpg', 13),
(114, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p119.jpg', 14),
(115, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p120.jpg', 15),
(116, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p121.jpg', 16),
(117, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p122.jpg', 17),
(118, 4, 1, 'client/manga/is-full-test/04-a-small-heart-is-torn/I''s Vol01 Ch004 p123.jpg', 18),
(119, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p124.jpg', 1),
(120, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p125.jpg', 2),
(121, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p126.jpg', 3),
(122, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p127.jpg', 4),
(123, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p128.jpg', 5),
(124, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p129.jpg', 6),
(125, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p130.jpg', 7),
(126, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p131.jpg', 8),
(127, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p132.jpg', 9),
(128, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p133.jpg', 10),
(129, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p134.jpg', 11),
(130, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p135.jpg', 12),
(131, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p136.jpg', 13),
(132, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p137.jpg', 14),
(133, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p138.jpg', 15),
(134, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p139.jpg', 16),
(135, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p140.jpg', 17),
(136, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p141.jpg', 18),
(137, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p142.jpg', 19),
(138, 5, 1, 'client/manga/is-full-test/05-bury-your-voice-in-the-ditch/I''s Vol01 Ch05 p143.jpg', 20),
(139, 6, 2, 'client/manga/zetman/41-party/note.png', 1),
(140, 6, 2, 'client/manga/zetman/41-party/zetman [41] 01.jpg', 2),
(141, 6, 2, 'client/manga/zetman/41-party/zetman [41] 02.jpg', 3),
(142, 6, 2, 'client/manga/zetman/41-party/zetman [41] 03.jpg', 4),
(143, 6, 2, 'client/manga/zetman/41-party/zetman [41] 04.jpg', 5),
(144, 6, 2, 'client/manga/zetman/41-party/zetman [41] 05.jpg', 6),
(145, 6, 2, 'client/manga/zetman/41-party/zetman [41] 06.jpg', 7),
(146, 6, 2, 'client/manga/zetman/41-party/zetman [41] 07.png', 8),
(147, 6, 2, 'client/manga/zetman/41-party/zetman [41] 08.png', 9),
(148, 6, 2, 'client/manga/zetman/41-party/zetman [41] 09.png', 10),
(149, 6, 2, 'client/manga/zetman/41-party/zetman [41] 10.png', 11),
(150, 6, 2, 'client/manga/zetman/41-party/zetman [41] 11.png', 12),
(151, 6, 2, 'client/manga/zetman/41-party/zetman [41] 12.png', 13),
(152, 6, 2, 'client/manga/zetman/41-party/zetman [41] 13.png', 14),
(153, 6, 2, 'client/manga/zetman/41-party/zetman [41] 14.png', 15),
(154, 6, 2, 'client/manga/zetman/41-party/zetman [41] 15.png', 16),
(155, 6, 2, 'client/manga/zetman/41-party/zetman [41] 16.png', 17),
(156, 6, 2, 'client/manga/zetman/41-party/zetman [41] 17.png', 18),
(157, 6, 2, 'client/manga/zetman/41-party/zetman [41] 18.png', 19),
(158, 6, 2, 'client/manga/zetman/41-party/zetman [41] 19.png', 20),
(159, 6, 2, 'client/manga/zetman/41-party/zetman [41] back.jpg', 21),
(160, 6, 2, 'client/manga/zetman/41-party/zetman [41] cover.jpg', 22),
(161, 6, 2, 'client/manga/zetman/41-party/zetman [41] fold.jpg', 23),
(162, 6, 2, 'client/manga/zetman/41-party/Zetman_credits.png', 24),
(163, 7, 2, 'client/manga/zetman/42-the-next-room/note.png', 1),
(164, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 01.png', 2),
(165, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 02.png', 3),
(166, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 03.png', 4),
(167, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 04.png', 5),
(168, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 05.png', 6),
(169, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 06.png', 7),
(170, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 07.png', 8),
(171, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 08.png', 9),
(172, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 09.png', 10),
(173, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 10.png', 11),
(174, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 11.png', 12),
(175, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 12.png', 13),
(176, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 13.png', 14),
(177, 7, 2, 'client/manga/zetman/42-the-next-room/zetman [42] 14.png', 15),
(178, 7, 2, 'client/manga/zetman/42-the-next-room/Zetman_credits.png', 16),
(179, 8, 2, 'client/manga/zetman/43-warrior/note.png', 1),
(180, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 01.png', 2),
(181, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 02.png', 3),
(182, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 03.png', 4),
(183, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 04.png', 5),
(184, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 05.png', 6),
(185, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 06.png', 7),
(186, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 07.png', 8),
(187, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 08.png', 9),
(188, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 09.png', 10),
(189, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 10.png', 11),
(190, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 11.png', 12),
(191, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 12.png', 13),
(192, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 13.png', 14),
(193, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 14.png', 15),
(194, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 15.png', 16),
(195, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 16.png', 17),
(196, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 17.png', 18),
(197, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 18.png', 19),
(198, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 19.png', 20),
(199, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 20.png', 21),
(200, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 21.png', 22),
(201, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 22.png', 23),
(202, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 23.png', 24),
(203, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 24.png', 25),
(204, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 25.png', 26),
(205, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 26.png', 27),
(206, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 27.png', 28),
(207, 8, 2, 'client/manga/zetman/43-warrior/zetman [43] 28.png', 29),
(208, 8, 2, 'client/manga/zetman/43-warrior/Zetman_credits.png', 30),
(209, 9, 2, 'client/manga/zetman/44-orgy/note.png', 1),
(210, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 01.png', 2),
(211, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 02.png', 3),
(212, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 03.png', 4),
(213, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 04.png', 5),
(214, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 05.png', 6),
(215, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 06.png', 7),
(216, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 07.png', 8),
(217, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 08.png', 9),
(218, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 09.png', 10),
(219, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 10.png', 11),
(220, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 11.png', 12),
(221, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 12.png', 13),
(222, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 13.png', 14),
(223, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 14.png', 15),
(224, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 15.png', 16),
(225, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 16.png', 17),
(226, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 17.png', 18),
(227, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 18.png', 19),
(228, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 19.png', 20),
(229, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 20.png', 21),
(230, 9, 2, 'client/manga/zetman/44-orgy/zetman [44] 21.png', 22),
(231, 9, 2, 'client/manga/zetman/44-orgy/Zetman_credits.png', 23),
(232, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p000a.jpg', 1),
(233, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p000b.jpg', 2),
(234, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p001.jpg', 3),
(235, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p002.jpg', 4),
(236, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p003.jpg', 5),
(237, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p004.jpg', 6),
(238, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p005.jpg', 7),
(239, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p006.jpg', 8),
(240, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p007.jpg', 9),
(241, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p008.jpg', 10),
(242, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p009.jpg', 11),
(243, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p010.jpg', 12),
(244, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p011.jpg', 13),
(245, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p012.jpg', 14),
(246, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p013.jpg', 15),
(247, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p014.jpg', 16),
(248, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p015.jpg', 17),
(249, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p016.jpg', 18),
(250, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p017.jpg', 19),
(251, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p018.jpg', 20),
(252, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p019.jpg', 21),
(253, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p020.jpg', 22),
(254, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p021.jpg', 23),
(255, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p022.jpg', 24),
(256, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p023.jpg', 25),
(257, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p024.jpg', 26),
(258, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p025.jpg', 27),
(259, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p026.jpg', 28),
(260, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p027.jpg', 29),
(261, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p028.jpg', 30),
(262, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p029.jpg', 31),
(263, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p030-31.jpg', 32),
(264, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p032.jpg', 33),
(265, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p033.jpg', 34),
(266, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p034.jpg', 35),
(267, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p035.jpg', 36),
(268, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p036.jpg', 37),
(269, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p037.jpg', 38),
(270, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p038.jpg', 39),
(271, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p039.jpg', 40),
(272, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p040.jpg', 41),
(273, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p041.jpg', 42),
(274, 10, 3, 'client/manga/gantz/0001-an-incident/Gantz_v01c01p042.jpg', 43),
(275, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p043.jpg', 1),
(276, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p044-45.jpg', 2),
(277, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p046.jpg', 3),
(278, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p047.jpg', 4),
(279, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p048.jpg', 5),
(280, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p049.jpg', 6),
(281, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p050.jpg', 7),
(282, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p051.jpg', 8),
(283, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p052.jpg', 9),
(284, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p053.jpg', 10),
(285, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p054.jpg', 11),
(286, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p055.jpg', 12),
(287, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p056.jpg', 13),
(288, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p057.jpg', 14),
(289, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p058.jpg', 15),
(290, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p059.jpg', 16),
(291, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p060.jpg', 17),
(292, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p061.jpg', 18),
(293, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p062.jpg', 19),
(294, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p063.jpg', 20),
(295, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p064.jpg', 21),
(296, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p065.jpg', 22),
(297, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p066.jpg', 23),
(298, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p067.jpg', 24),
(299, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p068.jpg', 25),
(300, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p069.jpg', 26),
(301, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p070.jpg', 27),
(302, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p071.jpg', 28),
(303, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p072.jpg', 29),
(304, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p073.jpg', 30),
(305, 11, 3, 'client/manga/gantz/0002-an-inexplicable-room/Gantz_v01c02p074.jpg', 31),
(306, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p075.jpg', 1),
(307, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p076.jpg', 2),
(308, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p077.jpg', 3),
(309, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p078.jpg', 4),
(310, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p079.jpg', 5),
(311, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p080.jpg', 6),
(312, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p081.jpg', 7),
(313, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p082.jpg', 8),
(314, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p083.jpg', 9),
(315, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p084.jpg', 10),
(316, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p085.jpg', 11),
(317, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p086.jpg', 12),
(318, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p087.jpg', 13),
(319, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p088.jpg', 14),
(320, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p089.jpg', 15),
(321, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p090.jpg', 16),
(322, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p091.jpg', 17),
(323, 12, 3, 'client/manga/gantz/0003-the-naked-suicide-girl/Gantz_v01c03p092.jpg', 18),
(324, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p093.jpg', 1),
(325, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p094.jpg', 2),
(326, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p095.jpg', 3),
(327, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p096.jpg', 4),
(328, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p097.jpg', 5),
(329, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p098.jpg', 6),
(330, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p099.jpg', 7),
(331, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p100.jpg', 8),
(332, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p101.jpg', 9),
(333, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p102.jpg', 10),
(334, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p103.jpg', 11),
(335, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p104.jpg', 12),
(336, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p105.jpg', 13),
(337, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p106.jpg', 14),
(338, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p107.jpg', 15),
(339, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p108.jpg', 16),
(340, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p109.jpg', 17),
(341, 13, 3, 'client/manga/gantz/0004-the-black-balls-orders/Gantz_v01c04p110.jpg', 18),
(342, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/note.png', 1),
(343, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 01.png', 2),
(344, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 02.png', 3),
(345, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 03.png', 4),
(346, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 04.png', 5),
(347, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 05.png', 6),
(348, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 06.png', 7),
(349, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 07.png', 8),
(350, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 08.png', 9),
(351, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 09.png', 10),
(352, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 10.png', 11),
(353, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 11.png', 12),
(354, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 12.png', 13),
(355, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 13.png', 14),
(356, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 14.png', 15),
(357, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 15.png', 16),
(358, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 16.png', 17),
(359, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 17.png', 18),
(360, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 18.png', 19),
(361, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 19.png', 20),
(362, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 20.png', 21),
(363, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 21.png', 22),
(364, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 22.png', 23),
(365, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 23.png', 24),
(366, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 24.png', 25),
(367, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 25.png', 26),
(368, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 26.png', 27),
(369, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 27.png', 28),
(370, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/zetman [45] 28.png', 29),
(371, 14, 2, 'client/manga/zetman/45-cleanly-at-the-end/Zetman_credits.png', 30);

-- --------------------------------------------------------

--
-- Table structure for table `vmr_config`
--

DROP TABLE IF EXISTS `vmr_config`;
CREATE TABLE IF NOT EXISTS `vmr_config` (
  `config_name` varchar(255) NOT NULL,
  `config_value` text,
  `config_detail` varchar(255) DEFAULT NULL,
  `config_core` int(1) NOT NULL DEFAULT '0' COMMENT '0=no, 1=yes. if config core then it cannot delete from db.',
  KEY `config_name` (`config_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vmr_config`
--

INSERT INTO `vmr_config` (`config_name`, `config_value`, `config_detail`, `config_core`) VALUES
('site_name', 'Vee''s manga reader Pro', NULL, 1),
('page_title_separater', ' | ', NULL, 1),
('admin_items_per_page', '40', NULL, 1),
('front_manga_per_page', '30', NULL, 1),
('manga_dir', 'client/manga/', 'start from root dir always end with slash ', 1),
('duplicate_login', 'off', 'allow duplicate login? off = not allow', 1),
('cache', 'on', 'cache on or off', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vmr_genre`
--

DROP TABLE IF EXISTS `vmr_genre`;
CREATE TABLE IF NOT EXISTS `vmr_genre` (
  `genre_id` int(11) NOT NULL AUTO_INCREMENT,
  `genre_name` varchar(255) DEFAULT NULL,
  `genre_description` text,
  `genre_uri` varchar(255) DEFAULT NULL,
  `genre_add` datetime DEFAULT NULL,
  `genre_enable` int(1) NOT NULL DEFAULT '0' COMMENT '0=no, 1=yes',
  PRIMARY KEY (`genre_id`),
  UNIQUE KEY `genre_uri` (`genre_uri`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `vmr_genre`
--

INSERT INTO `vmr_genre` (`genre_id`, `genre_name`, `genre_description`, `genre_uri`, `genre_add`, `genre_enable`) VALUES
(1, 'comedy', '', 'comedy', '2011-03-27 17:48:41', 1),
(2, 'action', '', 'action', '2011-03-27 17:48:49', 1),
(3, 'romantic', '', 'romantic', '2011-03-27 17:49:02', 1),
(4, 'drama', '', 'drama', '2011-03-27 17:49:13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `vmr_genre_story`
--

DROP TABLE IF EXISTS `vmr_genre_story`;
CREATE TABLE IF NOT EXISTS `vmr_genre_story` (
  `genre-story_id` int(11) NOT NULL AUTO_INCREMENT,
  `genre_id` int(11) DEFAULT NULL,
  `story_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`genre-story_id`),
  KEY `genre_id` (`genre_id`),
  KEY `story_id` (`story_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `vmr_genre_story`
--

INSERT INTO `vmr_genre_story` (`genre-story_id`, `genre_id`, `story_id`) VALUES
(10, 1, 1),
(11, 3, 1),
(12, 2, 3),
(13, 2, 2),
(14, 4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `vmr_story`
--

DROP TABLE IF EXISTS `vmr_story`;
CREATE TABLE IF NOT EXISTS `vmr_story` (
  `story_id` int(11) NOT NULL AUTO_INCREMENT,
  `story_name` varchar(255) DEFAULT NULL,
  `story_statinfo` text,
  `story_summary` text,
  `story_author` varchar(255) DEFAULT NULL,
  `story_artist` varchar(255) DEFAULT NULL,
  `story_cover` varchar(255) DEFAULT NULL,
  `story_uri` varchar(255) DEFAULT NULL,
  `story_add` datetime DEFAULT NULL,
  `story_update` datetime DEFAULT NULL,
  `story_enable` int(1) NOT NULL DEFAULT '0' COMMENT '0=disable, 1=enable',
  PRIMARY KEY (`story_id`),
  KEY `story_uri` (`story_uri`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `vmr_story`
--

INSERT INTO `vmr_story` (`story_id`, `story_name`, `story_statinfo`, `story_summary`, `story_author`, `story_artist`, `story_cover`, `story_uri`, `story_add`, `story_update`, `story_enable`) VALUES
(1, 'I&quot;s', '<p><strong>Published&nbsp;by</strong>: <a rel="nofollow" href="http://en.wikipedia.org/wiki/Shueisha">Shueisha</a><br /><strong>Original run</strong>: <span style="white-space: nowrap;">1997</span> &ndash; <span style="white-space: nowrap;">1999<br /></span><strong>Volumes</strong>: 15</p>', '<p>Iori spends her holidays working as an actress, shooting a movie at  the waterside of a lake, which has a mysterious small island called  Lover''s Island at its center. Ichitaka, on a biking trip, coincidentally  makes a stop at the location where Iori is shooting. Meanwhile, Itsuki  returned from her job as a molding artist in the United States and plans  to meet her childhood friend Yosuke to fulfill a promise they made ten  years ago. Walking along the railroad track she, Yosuke and Ichitaka  went along ten years ago, Itsuki is confused when she sees the lake with  the small island in it at one side of the track, as she does not  remember there being a lake. Yosuke, along with three of his <a rel="nofollow" href="http://en.wikipedia.org/wiki/Motorcycle" title="Motorcycle">biker</a> friends, is also on his way to meet with Itsuki. However, the other bikers reveal to him that they are planning to <a rel="nofollow" href="http://en.wikipedia.org/wiki/Rape">rape</a> Itsuki. He drives ahead to save Itsuki from them, but drops from his  motorbike just as he crosses her path. The other bikers close in on him,  stab him and let him drop into the lake (which he can not remember  being there when he was a child). As the bikers spot Itsuki, she runs  away.</p> <p>Yasumasa and his friends want to pick up Iori at the Lake Side Hotel,  but she already left as her shooting finished early. Meanwhile, she  meets Ichitaka and they decide to take a boat and row across the lake to  the island. As they arrive, Ichitaka recognizes the island as the place  he, Yosuke and Itsuki visited as children, although back then there was  no lake around it. Just then, Iori and Ichiitaka hear Itsuki on the  other side of the lake screaming for help as the bikers found her and  now want to rape and kill her. Ichitaka quickly rows over, leaving Iori  alone on the island. He manages to hold back two of the bikers, but  their leader knocks Itsuki out and abducts her on his motorbike. As it  starts to rain heavy, Ichitaka follows them, while Yasumasa and his  friends, who suddenly show up, take out the two bikers. Ichitaka takes a  shortcut across a fragile old plank bridge to catch up with them, but  the bridge collapses. Swinging down on one of the ropes that hold the  bridge, Ichitaka grabs Itsuki from the back of the motorbike. Together,  they drop into a stream and as they regain consciousness, find  themselves at the waterside of the lake with the ghost of their  childhood friend Yosuke standing nearby. Across the lake, they spot Iori  on Lover''s Island, close to drowning as the heavy rain threatens to  flood the tiny island. As no boat is around, Yosuke reminds Ichitaka of a  secret cave they found nearby when they were kids, that leads to the  island. However, the cave is flooded with rain, and Ichitaka has to dive  through the last part and manages to save Iori from <a rel="nofollow" href="http://en.wikipedia.org/wiki/Drowning">drowning</a> at the last second.</p> <p>After saving Iori, everyone is playing beside the dock, and Itsuki  wonders where Yosuke went. The scene after shows the local police  searching for Yosuke''s body in the water, but to no avail. High above  the dam, Yosuke appears on a cliff, smiling, and then his spirit fades  away. It is assumed that he died from the stabbing wound inflicted on  him earlier on in the OVA.</p>', 'Masakazu Katsura', 'Masakazu Katsura', 'client/manga/is-full-test/dcdb8a289d799c18ab5149efd665ed74.jpg', 'is-full-test', '2011-03-27 18:20:10', '2011-03-28 23:01:01', 1),
(2, 'Zetman', '<p><strong>Published&nbsp;by</strong>: <a rel="nofollow" href="http://en.wikipedia.org/wiki/Shueisha">Shueisha</a><br /><strong>Original run</strong>: <span style="white-space: nowrap;">October 31, 2002</span> &ndash; ongoing<br /><strong>Volumes</strong>: 14</p>', '<p>The story starts off with a face-off between two rival heroes, ZET  and Alphasz, and then traces their origins - Jin Kanzaki, a young man  with the ability to transform into a superhuman being known as ZET, and  Kouga Amagi, a young man with a strong sense of justice who uses  technology to fight as Alphasz.</p> <p>The fates of these two men and those around them intertwine as they  fight to protect mankind and destroy monstrous abominations known as  Players, who ironically are the creations of the Amagi Corporation, the  company founded by Kouga''s grandfather, Mitsugai Amagi.</p>', 'Masakazu Katsura', 'Masakazu Katsura', 'client/manga/zetman/14095e956cacbf802c54c8152e473058.jpg', 'zetman', '2011-03-27 21:03:18', '2011-03-28 23:03:03', 1),
(3, 'Gantz', '<p><strong>Published&nbsp;by</strong>: <a rel="nofollow" href="http://en.wikipedia.org/wiki/Shueisha">Shueisha</a><br /><strong>Original run</strong>: <span style="white-space: nowrap;">October 2000</span> &ndash; ongoing<br /><strong>Volumes</strong>: 30</p>', '<p>A pair of high school students, <a rel="nofollow" href="http://en.wikipedia.org/wiki/Kei_Kurono" title="Kei Kurono">Kei Kurono</a> and <a rel="nofollow" href="http://en.wikipedia.org/wiki/Masaru_Kato" title="Masaru Kato">Masaru Kato</a>,  are hit by a subway train in an attempt to save the life of a homeless  drunk who had fallen onto the tracks. Following their deaths, Kurono and  Kato find themselves transported to the interior of an unfurnished <a rel="nofollow" href="http://en.wikipedia.org/wiki/Tokyo">Tokyo</a> apartment. The pair soon realize others are present and find that they  are not able to leave the apartment. At one end of the room there is a  featureless black sphere known as "Gantz".</p> <p>After some time in the room, the Gantz sphere opens up, revealing a  bald naked man with a breathing mask and wires attached to his head, and  three racks protruding from it, that offer various items for them to  use. These include the custom fitting black suits Gantz makes for each  of them, giving them super-human abilities, a controller which acts as a  radar and stealth unit, and three types of guns.</p> <p>When the Gantz sphere opens, green text appears on its surface,  informing those present that their "lives have ended and now belong" to  him. A picture and brief information is shown of some of the <a rel="nofollow" href="http://en.wikipedia.org/wiki/List_of_Gantz_characters#Aliens" title="List of Gantz characters">Gantz Targets</a>,  Gantz ordering them to go and kill them. All but one target shown thus  far, have been aliens living on Earth, which take on a wide variety of  forms. After a period of time which varies between missions, everyone  except Gantz are transported to the location of the mission.</p> <p>Those sent cannot return from the mission until all enemies have been  killed, or the time limit has run out. If they survive a successful  mission, each individual is awarded points for the aliens they have  killed. They are then allowed to leave, and live their lives as they see  fit until Gantz summons them back again for the next mission. The only  way to stop having to participate in the missions is to earn one hundred  points, and choose the option to be freed. Several participants are  killed through the third mission they are given, leaving Kurono as the  only survivor and the new leader from the "Gantz Team". However, as the  series continues, Kurono participates with the objective to revive his  deceased friends with the 100 points he can obtain throughout the  missions.</p> <p>After several missions, Nishi unveils a countdown on Gatz to  "Catastrophe" the other players were unaware of.This is thought to  indicate the human race will be over in a week for an unknown reason,  but it also frees all the participants from the game. A week later, a  massive alien force invades the Earth and begins exterminating the human  race, while Kurono and his companions try their best to make use of  Gantz''s advanced technology and weaponry in order to take a stand  against the alien invasion.</p>', 'Hiroya Oku', 'Hiroya Oku', 'client/manga/gantz/188fac307e0c62cea3c87f391039d189.jpg', 'gantz', '2011-03-27 21:55:24', '2011-03-28 23:01:16', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `vmr_chapters`
--
ALTER TABLE `vmr_chapters`
  ADD CONSTRAINT `vmr_chapters_ibfk_1` FOREIGN KEY (`story_id`) REFERENCES `vmr_story` (`story_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vmr_chapter_images`
--
ALTER TABLE `vmr_chapter_images`
  ADD CONSTRAINT `vmr_chapter_images_ibfk_2` FOREIGN KEY (`story_id`) REFERENCES `vmr_story` (`story_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vmr_chapter_images_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `vmr_chapters` (`chapter_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vmr_genre_story`
--
ALTER TABLE `vmr_genre_story`
  ADD CONSTRAINT `vmr_genre_story_ibfk_2` FOREIGN KEY (`story_id`) REFERENCES `vmr_story` (`story_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vmr_genre_story_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `vmr_genre` (`genre_id`) ON DELETE CASCADE ON UPDATE CASCADE;
