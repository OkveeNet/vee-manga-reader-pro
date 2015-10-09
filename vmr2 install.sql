-- phpMyAdmin SQL Dump
-- version 3.3.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 21, 2011 at 06:25 PM
-- Server version: 5.5.15
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `v_vmrpro2`
--

-- --------------------------------------------------------

--
-- Table structure for table `ws_accounts`
--

CREATE TABLE IF NOT EXISTS `ws_accounts` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_username` varchar(255) DEFAULT NULL,
  `account_email` varchar(255) DEFAULT NULL,
  `account_password` varchar(255) DEFAULT NULL,
  `account_fullname` varchar(255) DEFAULT NULL,
  `account_birthdate` date DEFAULT NULL,
  `account_avatar` varchar(255) DEFAULT NULL,
  `account_signature` text,
  `account_create` datetime DEFAULT NULL,
  `account_last_login` datetime DEFAULT NULL,
  `account_online_code` varchar(255) DEFAULT NULL COMMENT 'store session code for check dubplicate log in if enabled.',
  `account_status` int(1) NOT NULL DEFAULT '0' COMMENT '0=disable, 1=enable',
  `account_status_text` varchar(255) DEFAULT NULL,
  `account_new_email` varchar(255) DEFAULT NULL,
  `account_new_password` varchar(255) DEFAULT NULL,
  `account_confirm_code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ws_accounts`
--

INSERT INTO `ws_accounts` (`account_id`, `account_username`, `account_email`, `account_password`, `account_fullname`, `account_birthdate`, `account_avatar`, `account_signature`, `account_create`, `account_last_login`, `account_online_code`, `account_status`, `account_status_text`, `account_new_email`, `account_new_password`, `account_confirm_code`) VALUES
(1, 'admin', 'admin@domain.tld', '75532b919639e2af869c79b44ff0c2afb6b5e0b6', '', NULL, NULL, '', '2011-04-20 19:20:04', '2011-09-21 11:52:26', 'a5dadec9845b56760844d2640f6597dc', 1, '', NULL, 'NULL', 'NULL');

-- --------------------------------------------------------

--
-- Table structure for table `ws_account_level`
--

CREATE TABLE IF NOT EXISTS `ws_account_level` (
  `level_id` int(11) NOT NULL AUTO_INCREMENT,
  `level_group_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  PRIMARY KEY (`level_id`),
  KEY `level_group_id` (`level_group_id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ws_account_level`
--

INSERT INTO `ws_account_level` (`level_id`, `level_group_id`, `account_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ws_account_level_group`
--

CREATE TABLE IF NOT EXISTS `ws_account_level_group` (
  `level_group_id` int(11) NOT NULL AUTO_INCREMENT,
  `level_name` varchar(255) DEFAULT NULL,
  `level_description` text,
  `level_priority` int(5) NOT NULL DEFAULT '1' COMMENT 'lower is more higher priority',
  PRIMARY KEY (`level_group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ws_account_level_group`
--

INSERT INTO `ws_account_level_group` (`level_group_id`, `level_name`, `level_description`, `level_priority`) VALUES
(1, 'Super administrator', 'best for site owner.', 1),
(2, 'Administrator', NULL, 2),
(3, 'Member', 'Just member.', 999);

-- --------------------------------------------------------

--
-- Table structure for table `ws_account_level_permission`
--

CREATE TABLE IF NOT EXISTS `ws_account_level_permission` (
  `permission_id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_page` varchar(255) NOT NULL,
  `params` text,
  PRIMARY KEY (`permission_id`),
  UNIQUE KEY `permission_page` (`permission_page`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `ws_account_level_permission`
--

INSERT INTO `ws_account_level_permission` (`permission_id`, `permission_page`, `params`) VALUES
(1, 'account_account', 'a:3:{i:1;a:5:{s:14:"account_manage";s:1:"0";s:11:"account_add";s:1:"0";s:12:"account_edit";s:1:"0";s:14:"account_delete";s:1:"0";s:19:"account_view_logins";s:1:"0";}i:2;a:5:{s:14:"account_manage";s:1:"0";s:11:"account_add";s:1:"0";s:12:"account_edit";s:1:"0";s:14:"account_delete";s:1:"0";s:19:"account_view_logins";s:1:"0";}i:3;a:5:{s:14:"account_manage";s:1:"0";s:11:"account_add";s:1:"0";s:12:"account_edit";s:1:"0";s:14:"account_delete";s:1:"0";s:19:"account_view_logins";s:1:"0";}}'),
(2, 'account_level', 'a:3:{i:1;a:4:{s:20:"account_manage_level";s:1:"0";s:17:"account_add_level";s:1:"0";s:18:"account_edit_level";s:1:"0";s:20:"account_delete_level";s:1:"0";}i:2;a:4:{s:20:"account_manage_level";s:1:"0";s:17:"account_add_level";s:1:"0";s:18:"account_edit_level";s:1:"0";s:20:"account_delete_level";s:1:"0";}i:3;a:4:{s:20:"account_manage_level";s:1:"0";s:17:"account_add_level";s:1:"0";s:18:"account_edit_level";s:1:"0";s:20:"account_delete_level";s:1:"0";}}'),
(3, 'account_permissions', 'a:3:{i:1;a:1:{s:25:"account_manage_permission";s:1:"0";}i:2;a:1:{s:25:"account_manage_permission";s:1:"0";}i:3;a:1:{s:25:"account_manage_permission";s:1:"0";}}'),
(4, 'chapter_chapter', 'a:3:{i:1;a:4:{s:19:"chapter_perm_manage";s:1:"0";s:16:"chapter_perm_add";s:1:"0";s:17:"chapter_perm_edit";s:1:"0";s:19:"chapter_perm_delete";s:1:"0";}i:2;a:4:{s:19:"chapter_perm_manage";s:1:"0";s:16:"chapter_perm_add";s:1:"0";s:17:"chapter_perm_edit";s:1:"0";s:19:"chapter_perm_delete";s:1:"0";}i:3;a:4:{s:19:"chapter_perm_manage";s:1:"0";s:16:"chapter_perm_add";s:1:"0";s:17:"chapter_perm_edit";s:1:"0";s:19:"chapter_perm_delete";s:1:"0";}}'),
(5, 'admin_global_config', 'a:3:{i:1;a:1:{s:20:"admin_website_config";s:1:"0";}i:2;a:1:{s:20:"admin_website_config";s:1:"0";}i:3;a:1:{s:20:"admin_website_config";s:1:"0";}}'),
(6, 'genre_genre', 'a:3:{i:1;a:4:{s:17:"genre_perm_manage";s:1:"0";s:14:"genre_perm_add";s:1:"0";s:15:"genre_perm_edit";s:1:"0";s:17:"genre_perm_delete";s:1:"0";}i:2;a:4:{s:17:"genre_perm_manage";s:1:"0";s:14:"genre_perm_add";s:1:"0";s:15:"genre_perm_edit";s:1:"0";s:17:"genre_perm_delete";s:1:"0";}i:3;a:4:{s:17:"genre_perm_manage";s:1:"0";s:14:"genre_perm_add";s:1:"0";s:15:"genre_perm_edit";s:1:"0";s:17:"genre_perm_delete";s:1:"0";}}'),
(7, 'account_admin_login', 'a:3:{i:1;a:1:{s:19:"account_admin_login";s:1:"0";}i:2;a:1:{s:19:"account_admin_login";s:1:"0";}i:3;a:1:{s:19:"account_admin_login";s:1:"0";}}'),
(8, 'manga_manga', 'a:3:{i:1;a:4:{s:17:"manga_perm_manage";s:1:"0";s:14:"manga_perm_add";s:1:"0";s:15:"manga_perm_edit";s:1:"0";s:17:"manga_perm_delete";s:1:"0";}i:2;a:4:{s:17:"manga_perm_manage";s:1:"0";s:14:"manga_perm_add";s:1:"0";s:15:"manga_perm_edit";s:1:"0";s:17:"manga_perm_delete";s:1:"0";}i:3;a:4:{s:17:"manga_perm_manage";s:1:"0";s:14:"manga_perm_add";s:1:"0";s:15:"manga_perm_edit";s:1:"0";s:17:"manga_perm_delete";s:1:"0";}}');

-- --------------------------------------------------------

--
-- Table structure for table `ws_account_logins`
--

CREATE TABLE IF NOT EXISTS `ws_account_logins` (
  `account_login_id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `login_ua` varchar(255) DEFAULT NULL,
  `login_os` varchar(255) DEFAULT NULL,
  `login_browser` varchar(255) DEFAULT NULL,
  `login_ip` varchar(255) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `login_attempt` int(1) NOT NULL DEFAULT '0' COMMENT '0=fail, 1=success',
  `login_attempt_text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`account_login_id`),
  KEY `account_id` (`account_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ws_account_logins`
--

INSERT INTO `ws_account_logins` (`account_login_id`, `account_id`, `login_ua`, `login_os`, `login_browser`, `login_ip`, `login_time`, `login_attempt`, `login_attempt_text`) VALUES
(1, 1, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0.2) Gecko/20100101 Firefox/6.0.2', 'Windows', 'Firefox 6.0.2', '127.0.0.1', '2011-09-20 03:03:01', 1, 'success'),
(2, 1, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0.2) Gecko/20100101 Firefox/6.0.2', 'Windows', 'Firefox 6.0.2', '127.0.0.1', '2011-09-20 03:06:28', 1, 'success'),
(3, 1, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0.2) Gecko/20100101 Firefox/6.0.2', 'Windows', 'Firefox 6.0.2', '127.0.0.1', '2011-09-21 09:41:50', 1, 'success'),
(4, 1, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:6.0.2) Gecko/20100101 Firefox/6.0.2', 'Windows', 'Firefox 6.0.2', '127.0.0.1', '2011-09-21 11:52:27', 1, 'success');

-- --------------------------------------------------------

--
-- Table structure for table `ws_chapters`
--

CREATE TABLE IF NOT EXISTS `ws_chapters` (
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
-- Dumping data for table `ws_chapters`
--


-- --------------------------------------------------------

--
-- Table structure for table `ws_chapter_images`
--

CREATE TABLE IF NOT EXISTS `ws_chapter_images` (
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
-- Dumping data for table `ws_chapter_images`
--


-- --------------------------------------------------------

--
-- Table structure for table `ws_config`
--

CREATE TABLE IF NOT EXISTS `ws_config` (
  `config_name` varchar(255) DEFAULT NULL,
  `config_value` varchar(255) DEFAULT NULL,
  `config_core` int(1) DEFAULT '0' COMMENT '0=no, 1=yes. if config core then please do not delete from db.',
  `config_description` text,
  KEY `config_name` (`config_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ws_config`
--

INSERT INTO `ws_config` (`config_name`, `config_value`, `config_core`, `config_description`) VALUES
('site_name', 'Vee''s manga reader Pro 2', 1, 'website name'),
('page_title_separator', ' | ', 1, 'page title separator. eg. site name | page'),
('duplicate_login', 'on', 1, 'allow log in more than 1 place, session? set to on/off to allow/disallow.'),
('allow_avatar', '1', 1, 'set to 1 if use avatar or set to 0 if not use it.'),
('avatar_size', '200', 1, 'set file size in Kilobyte.'),
('avatar_allowed_types', 'gif|jpg|png', 1, 'avatar allowe file types (see reference from codeigniter)\r\neg. gif|jpg|png'),
('admin_items_per_page', '40', 1, 'list items per page in admin'),
('web_items_per_page', '30', 1, 'list items per page in front end.'),
('manga_dir', 'client/manga/', 1, 'manga uploaded directory.\r\nfrom root.'),
('cache_enable', 'no', 1, 'enable cache? yes or no'),
('cache_minute', '10', 1, 'how long to cache?');

-- --------------------------------------------------------

--
-- Table structure for table `ws_genre`
--

CREATE TABLE IF NOT EXISTS `ws_genre` (
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
-- Dumping data for table `ws_genre`
--


-- --------------------------------------------------------

--
-- Table structure for table `ws_genre_story`
--

CREATE TABLE IF NOT EXISTS `ws_genre_story` (
  `genre-story_id` int(11) NOT NULL AUTO_INCREMENT,
  `genre_id` int(11) DEFAULT NULL,
  `story_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`genre-story_id`),
  KEY `genre_id` (`genre_id`),
  KEY `story_id` (`story_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `ws_genre_story`
--


-- --------------------------------------------------------

--
-- Table structure for table `ws_story`
--

CREATE TABLE IF NOT EXISTS `ws_story` (
  `story_id` int(11) NOT NULL AUTO_INCREMENT,
  `story_name` varchar(255) DEFAULT NULL,
  `story_statinfo` text,
  `story_summary` text,
  `story_author` varchar(255) DEFAULT NULL,
  `story_artist` varchar(255) DEFAULT NULL,
  `story_cover` varchar(255) DEFAULT NULL,
  `story_uri` varchar(255) DEFAULT NULL,
  `story_views` int(11) NOT NULL DEFAULT '0',
  `story_add` datetime DEFAULT NULL,
  `story_update` datetime DEFAULT NULL,
  `story_enable` int(1) NOT NULL DEFAULT '0' COMMENT '0=disable, 1=enable',
  PRIMARY KEY (`story_id`),
  KEY `story_uri` (`story_uri`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ws_story`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `ws_account_level`
--
ALTER TABLE `ws_account_level`
  ADD CONSTRAINT `ws_account_level_ibfk_1` FOREIGN KEY (`level_group_id`) REFERENCES `ws_account_level_group` (`level_group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ws_account_level_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `ws_accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ws_account_logins`
--
ALTER TABLE `ws_account_logins`
  ADD CONSTRAINT `ws_account_logins_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `ws_accounts` (`account_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ws_chapters`
--
ALTER TABLE `ws_chapters`
  ADD CONSTRAINT `ws_chapters_ibfk_1` FOREIGN KEY (`story_id`) REFERENCES `ws_story` (`story_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ws_chapter_images`
--
ALTER TABLE `ws_chapter_images`
  ADD CONSTRAINT `ws_chapter_images_ibfk_1` FOREIGN KEY (`chapter_id`) REFERENCES `ws_chapters` (`chapter_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ws_chapter_images_ibfk_2` FOREIGN KEY (`story_id`) REFERENCES `ws_story` (`story_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ws_genre_story`
--
ALTER TABLE `ws_genre_story`
  ADD CONSTRAINT `ws_genre_story_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `ws_genre` (`genre_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ws_genre_story_ibfk_2` FOREIGN KEY (`story_id`) REFERENCES `ws_story` (`story_id`) ON DELETE CASCADE ON UPDATE CASCADE;
