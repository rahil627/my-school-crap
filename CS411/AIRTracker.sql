-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.30


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema airport
--

CREATE DATABASE IF NOT EXISTS airport;
USE airport;

--
-- Definition of table `airport`.`Airplane`
--

DROP TABLE IF EXISTS `airport`.`Airplane`;
CREATE TABLE  `airport`.`Airplane` (
  `aircraft_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cargo_limit` double NOT NULL,
  PRIMARY KEY (`aircraft_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airport`.`Airplane`
--

/*!40000 ALTER TABLE `Airplane` DISABLE KEYS */;
LOCK TABLES `Airplane` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Airplane` ENABLE KEYS */;


--
-- Definition of table `airport`.`Airport`
--

DROP TABLE IF EXISTS `airport`.`Airport`;
CREATE TABLE  `airport`.`Airport` (
  `airport_code` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `airport_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`airport_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airport`.`Airport`
--

/*!40000 ALTER TABLE `Airport` DISABLE KEYS */;
LOCK TABLES `Airport` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Airport` ENABLE KEYS */;


--
-- Definition of table `airport`.`Bag`
--

DROP TABLE IF EXISTS `airport`.`Bag`;
CREATE TABLE  `airport`.`Bag` (
  `bag_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `t_id` bigint(20) unsigned NOT NULL,
  `weight` float NOT NULL,
  PRIMARY KEY (`bag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airport`.`Bag`
--

/*!40000 ALTER TABLE `Bag` DISABLE KEYS */;
LOCK TABLES `Bag` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Bag` ENABLE KEYS */;


--
-- Definition of table `airport`.`Flight`
--

DROP TABLE IF EXISTS `airport`.`Flight`;
CREATE TABLE  `airport`.`Flight` (
  `flight_id` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `airplane_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `gate_no` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `arrival_airport` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `arrival_flight_no` int(11) NOT NULL,
  `arrival_flight_code` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `depart_airport` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `depart_flight_no` int(11) NOT NULL,
  `depart_flight_code` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`flight_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airport`.`Flight`
--

/*!40000 ALTER TABLE `Flight` DISABLE KEYS */;
LOCK TABLES `Flight` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Flight` ENABLE KEYS */;


--
-- Definition of table `airport`.`Traveler`
--

DROP TABLE IF EXISTS `airport`.`Traveler`;
CREATE TABLE  `airport`.`Traveler` (
  `traveler_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  `tflight_id` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`traveler_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airport`.`Traveler`
--

/*!40000 ALTER TABLE `Traveler` DISABLE KEYS */;
LOCK TABLES `Traveler` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Traveler` ENABLE KEYS */;

--
-- Create schema airtracker
--

CREATE DATABASE IF NOT EXISTS airtracker;
USE airtracker;

--
-- Definition of table `airtracker`.`Alert`
--

DROP TABLE IF EXISTS `airtracker`.`Alert`;
CREATE TABLE  `airtracker`.`Alert` (
  `alert_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bag_id` bigint(20) unsigned NOT NULL,
  `last_scanner` int(10) unsigned NOT NULL,
  `alert_occurred` datetime NOT NULL,
  `clear_type` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `clear_occurred` datetime NOT NULL,
  PRIMARY KEY (`alert_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Alert`
--

/*!40000 ALTER TABLE `Alert` DISABLE KEYS */;
LOCK TABLES `Alert` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Alert` ENABLE KEYS */;


--
-- Definition of table `airtracker`.`Alert_Type`
--

DROP TABLE IF EXISTS `airtracker`.`Alert_Type`;
CREATE TABLE  `airtracker`.`Alert_Type` (
  `type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_descript` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Alert_Type`
--

/*!40000 ALTER TABLE `Alert_Type` DISABLE KEYS */;
LOCK TABLES `Alert_Type` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Alert_Type` ENABLE KEYS */;


--
-- Definition of table `airtracker`.`Bag`
--

DROP TABLE IF EXISTS `airtracker`.`Bag`;
CREATE TABLE  `airtracker`.`Bag` (
  `bag_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `flight_id` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `route_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`bag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Bag`
--

/*!40000 ALTER TABLE `Bag` DISABLE KEYS */;
LOCK TABLES `Bag` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Bag` ENABLE KEYS */;


--
-- Definition of table `airtracker`.`Carrier`
--

DROP TABLE IF EXISTS `airtracker`.`Carrier`;
CREATE TABLE  `airtracker`.`Carrier` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bag_id` bigint(20) unsigned NOT NULL,
  `cart_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Carrier`
--

/*!40000 ALTER TABLE `Carrier` DISABLE KEYS */;
LOCK TABLES `Carrier` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Carrier` ENABLE KEYS */;


--
-- Definition of table `airtracker`.`Cart`
--

DROP TABLE IF EXISTS `airtracker`.`Cart`;
CREATE TABLE  `airtracker`.`Cart` (
  `cart_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scanner_id` int(10) unsigned NOT NULL,
  `truck_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Cart`
--

/*!40000 ALTER TABLE `Cart` DISABLE KEYS */;
LOCK TABLES `Cart` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Cart` ENABLE KEYS */;


--
-- Definition of table `airtracker`.`Loader`
--

DROP TABLE IF EXISTS `airtracker`.`Loader`;
CREATE TABLE  `airtracker`.`Loader` (
  `loader_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scanner_id` int(10) unsigned NOT NULL,
  `aircraft_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`loader_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Loader`
--

/*!40000 ALTER TABLE `Loader` DISABLE KEYS */;
LOCK TABLES `Loader` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Loader` ENABLE KEYS */;


--
-- Definition of table `airtracker`.`Pusher_Route`
--

DROP TABLE IF EXISTS `airtracker`.`Pusher_Route`;
CREATE TABLE  `airtracker`.`Pusher_Route` (
  `route_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `destination_gate` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `scanner_1_id` int(10) unsigned NOT NULL,
  `route_valid_1` binary(1) NOT NULL,
  `scanner_2_id` int(10) unsigned NOT NULL,
  `route_valid_2` binary(1) NOT NULL,
  `scanner_3_id` int(10) unsigned NOT NULL,
  `route_valid_3` binary(1) NOT NULL,
  `scanner_4_id` int(10) unsigned NOT NULL,
  `route_valid_4` binary(1) NOT NULL,
  `scanner_5_id` int(10) unsigned NOT NULL,
  `route_valid_5` binary(1) NOT NULL,
  PRIMARY KEY (`route_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Pusher_Route`
--

/*!40000 ALTER TABLE `Pusher_Route` DISABLE KEYS */;
LOCK TABLES `Pusher_Route` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Pusher_Route` ENABLE KEYS */;


--
-- Definition of table `airtracker`.`Pusher_Route_Alt`
--

DROP TABLE IF EXISTS `airtracker`.`Pusher_Route_Alt`;
CREATE TABLE  `airtracker`.`Pusher_Route_Alt` (
  `route_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scanner_id` int(10) unsigned NOT NULL,
  `parent_scanner_id` int(10) unsigned NOT NULL,
  `left_node` int(10) unsigned NOT NULL,
  `right_node` int(10) unsigned NOT NULL,
  PRIMARY KEY (`route_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Pusher_Route_Alt`
--

/*!40000 ALTER TABLE `Pusher_Route_Alt` DISABLE KEYS */;
LOCK TABLES `Pusher_Route_Alt` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Pusher_Route_Alt` ENABLE KEYS */;


--
-- Definition of table `airtracker`.`Scanner`
--

DROP TABLE IF EXISTS `airtracker`.`Scanner`;
CREATE TABLE  `airtracker`.`Scanner` (
  `scanner_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alert_type` int(10) unsigned NOT NULL,
  PRIMARY KEY (`scanner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Scanner`
--

/*!40000 ALTER TABLE `Scanner` DISABLE KEYS */;
LOCK TABLES `Scanner` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Scanner` ENABLE KEYS */;


--
-- Definition of table `airtracker`.`Truck`
--

DROP TABLE IF EXISTS `airtracker`.`Truck`;
CREATE TABLE  `airtracker`.`Truck` (
  `truck_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `aircraft_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`truck_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Truck`
--

/*!40000 ALTER TABLE `Truck` DISABLE KEYS */;
LOCK TABLES `Truck` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `Truck` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
