-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Joshua T. McCauley
-- Generation Time: Nov 25, 2013 at 11:42 AM
-- Server version: 5.1.62
-- PHP Version: 5.3.5-1ubuntu7.8

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `messaging_logger`
--

-- --------------------------------------------------------

--
-- Table structure for table `message_info`
--

DROP TABLE IF EXISTS `message_info`;
CREATE TABLE IF NOT EXISTS `message_info` (
  `messageID` int(11) NOT NULL AUTO_INCREMENT,
  `pageLoad` int(11) DEFAULT NULL,
  `messageSubmit` int(11) DEFAULT NULL,
  `timeElapse` int(11) DEFAULT NULL,
  `referrer` varchar(100) DEFAULT NULL,
  `agent` varchar(100) DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `session` varchar(25) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `contact` varchar(30) DEFAULT NULL,
  `message` tinytext,
  `contactType` varchar(5) DEFAULT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '0',
  `logTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`messageID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=0 ;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
