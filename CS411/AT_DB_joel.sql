-- Definition of table `airport`.`Airport
CREATE DATABASE IF NOT EXISTS airtracker;
USE airtracker;

-- Definition of table `airtracker`.`Alert`
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

-- Definition of table `airtracker`.`Alert_Type`
DROP TABLE IF EXISTS `airtracker`.`Alert_Type`;
CREATE TABLE  `airtracker`.`Alert_Type` (
  `type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_descript` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Definition of table `airtracker`.`Bag`
DROP TABLE IF EXISTS `airtracker`.`Bag`;
CREATE TABLE  `airtracker`.`Bag` (
  `bag_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `flight_id` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `route_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`bag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Definition of table `airtracker`.`Carrier`
DROP TABLE IF EXISTS `airtracker`.`Carrier`;
CREATE TABLE  `airtracker`.`Carrier` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bag_id` bigint(20) unsigned NOT NULL,
  `cart_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Definition of table `airtracker`.`Cart`
DROP TABLE IF EXISTS `airtracker`.`Cart`;
CREATE TABLE  `airtracker`.`Cart` (
  `cart_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scanner_id` int(10) unsigned NOT NULL,
  `truck_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Definition of table `airtracker`.`Loader`
DROP TABLE IF EXISTS `airtracker`.`Loader`;
CREATE TABLE  `airtracker`.`Loader` (
  `loader_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scanner_id` int(10) unsigned NOT NULL,
  `aircraft_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`loader_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Definition of table `airtracker`.`Pusher_Route`
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

-- Definition of table `airtracker`.`Pusher_Route_Alt`
DROP TABLE IF EXISTS `airtracker`.`Pusher_Route_Alt`;
CREATE TABLE  `airtracker`.`Pusher_Route_Alt` (
  `route_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scanner_id` int(10) unsigned NOT NULL,
  `parent_scanner_id` int(10) unsigned NOT NULL,
  `left_node` int(10) unsigned NOT NULL,
  `right_node` int(10) unsigned NOT NULL,
  PRIMARY KEY (`route_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Definition of table `airtracker`.`Scanner`
DROP TABLE IF EXISTS `airtracker`.`Scanner`;
CREATE TABLE  `airtracker`.`Scanner` (
  `scanner_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alert_type` int(10) unsigned NOT NULL,
  PRIMARY KEY (`scanner_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Definition of table `airtracker`.`Truck`
DROP TABLE IF EXISTS `airtracker`.`Truck`;
CREATE TABLE  `airtracker`.`Truck` (
  `truck_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `aircraft_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`truck_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
