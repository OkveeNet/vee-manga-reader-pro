-- phpMyAdmin SQL Dump
-- version 3.3.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 29, 2011 at 01:04 AM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `vmr_accounts`
--

INSERT INTO `vmr_accounts` (`account_id`, `account_username`, `account_password`, `account_email`, `account_birth_date`, `account_tmp_password`, `account_tmp_email`, `account_tmp_key`, `account_create`, `account_last_login`, `account_last_session`, `account_level`, `account_status`, `account_paid`, `account_paid_expire`) VALUES
(1, 'admin', 'fffe106aed38aff2fac821e12d94fee6', 'no@email.com', NULL, NULL, NULL, NULL, '2011-03-23 10:09:58', '2011-03-28 21:36:20', '71bfe615c1654a27986e88ff8011d792', 0, 1, 0, NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vmr_chapters`
--


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vmr_chapter_images`
--


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
('cache', 'off', 'cache on or off', 1);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vmr_genre`
--


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vmr_genre_story`
--


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vmr_story`
--


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
