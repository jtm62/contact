-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Joshua T. McCauley
-- Generation Time: Nov 25, 2013 at 11:43 AM
-- Server version: 5.1.62
-- PHP Version: 5.3.5-1ubuntu7.8
--

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `visitor_logs`
--

-- --------------------------------------------------------

--
-- Table structure for table `visit_log`
--

DROP TABLE IF EXISTS `visit_log`;
CREATE TABLE IF NOT EXISTS `visit_log` (
  `SessionID` varchar(255) NOT NULL,
  `VisIP` varchar(15) NOT NULL,
  `VisHost` varchar(255) NOT NULL,
  `VisRef` varchar(255) NOT NULL,
  `VisURL` varchar(255) NOT NULL,
  `VisDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `VisAgent` varchar(255) NOT NULL,
  KEY `VisIP` (`VisIP`),
  KEY `SessionID` (`SessionID`),
  KEY `VisRef` (`VisRef`),
  KEY `VisURL` (`VisURL`),
  KEY `VisAgent` (`VisAgent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visit_log_me`
-- Used to store any visit that resolves from the same domain as the webserver.  I did this to allow from better statistical analysis of the visits.  

DROP TABLE IF EXISTS `visit_log_me`;
CREATE TABLE IF NOT EXISTS `visit_log_me` (
  `SessionID` varchar(255) NOT NULL,
  `VisIP` varchar(15) NOT NULL,
  `VisHost` varchar(255) NOT NULL,
  `VisRef` varchar(255) NOT NULL,
  `VisURL` varchar(255) NOT NULL,
  `VisDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `VisAgent` varchar(255) NOT NULL,
  KEY `VisIP` (`VisIP`),
  KEY `SessionID` (`SessionID`),
  KEY `VisRef` (`VisRef`),
  KEY `VisURL` (`VisURL`),
  KEY `VisAgent` (`VisAgent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;
