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
-- Definition of table `airport`.`Bag`
--

DROP TABLE IF EXISTS `airport`.`Bag`;
CREATE TABLE  `airport`.`Bag` (
  `bag_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `t_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`bag_id`)
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airport`.`Bag`
--

/*!40000 ALTER TABLE `Bag` DISABLE KEYS */;
LOCK TABLES `Bag` WRITE;
INSERT INTO `airport`.`Bag` VALUES  (1,4),
 (2,4),
 (3,6),
 (4,7),
 (5,7),
 (6,8),
 (7,11),
 (8,11),
 (9,13),
 (10,13),
 (11,14),
 (12,14),
 (13,15),
 (14,15),
 (15,16),
 (16,16),
 (17,18),
 (18,19),
 (19,20),
 (20,21),
 (21,23),
 (22,24),
 (23,25),
 (24,25),
 (25,27),
 (26,27),
 (27,28),
 (28,32),
 (29,38),
 (30,38),
 (31,39),
 (32,39),
 (33,42),
 (34,42),
 (35,43),
 (36,43),
 (37,45),
 (38,46),
 (39,46),
 (40,47),
 (41,48),
 (42,51),
 (43,51),
 (44,52),
 (45,53),
 (46,53),
 (47,55),
 (48,55),
 (49,57),
 (50,58),
 (51,58),
 (52,59),
 (53,60),
 (54,61),
 (55,61),
 (56,63),
 (57,64),
 (58,64),
 (59,65),
 (60,66),
 (61,66),
 (62,69),
 (63,70),
 (64,70),
 (65,71),
 (66,74),
 (67,75),
 (68,75),
 (69,76),
 (70,78),
 (71,80),
 (72,82),
 (73,83),
 (74,83),
 (75,84),
 (76,84),
 (77,85),
 (78,85),
 (79,86),
 (80,86),
 (81,87),
 (82,89),
 (83,91),
 (84,91),
 (85,92),
 (86,92),
 (87,93),
 (88,94),
 (89,94),
 (90,96),
 (91,96),
 (92,97),
 (93,97),
 (94,99),
 (95,99),
 (96,100);
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
  PRIMARY KEY (`flight_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airport`.`Flight`
--

/*!40000 ALTER TABLE `Flight` DISABLE KEYS */;
LOCK TABLES `Flight` WRITE;
INSERT INTO `airport`.`Flight` VALUES  ('ZE1483','N6477E','8'),
 ('RI1584','N8013E','7'),
 ('YJ4842','N1657W','6'),
 ('NU1978','N5556S','5'),
 ('RH3840','N4727Q','4'),
 ('OR2946','N7573A','3'),
 ('NK2704','N1741O','2'),
 ('MO2397','N9229J','1');
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
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airport`.`Traveler`
--

/*!40000 ALTER TABLE `Traveler` DISABLE KEYS */;
LOCK TABLES `Traveler` WRITE;
INSERT INTO `airport`.`Traveler` VALUES  (1,'Arthur','Adams','RI1584'),
 (2,'Angus','Allen','OR2946'),
 (3,'Aiken','Anderson','YJ4842'),
 (4,'Boris','Bailey','RI1584'),
 (5,'Arnold','Baker','YJ4842'),
 (6,'Cary','Barnes','NK2704'),
 (7,'Bowen','Bell','MO2397'),
 (8,'Brooke','Bennett','MO2397'),
 (9,'Broderick','Brooks','ZE1483'),
 (10,'Abner','Brown','ZE1483'),
 (11,'Carver','Butler','RH3840'),
 (12,'Aubrey','Campbell','RH3840'),
 (13,'Axel','Carter','NK2704'),
 (14,'Alston','Clark','YJ4842'),
 (15,'Barrett','Collins','RH3840'),
 (16,'Bert','Cook','RI1584'),
 (17,'Blake','Cooper','OR2946'),
 (18,'Bret','Cox','ZE1483'),
 (19,'Buck','Cruz','RH3840'),
 (20,'Addison','Davis','MO2397'),
 (21,'Brian','Diaz','YJ4842'),
 (22,'Barry','Edwards','MO2397'),
 (23,'Baldwin','Evans','RH3840'),
 (24,'Casey','Fisher','OR2946'),
 (25,'Basil','Flores','RH3840'),
 (26,'Cadman','Foster','NK2704'),
 (27,'Adler','Garcia','ZE1483'),
 (28,'Braden','Gomez','ZE1483'),
 (29,'Alfred','Gonzalez','NU1978'),
 (30,'Bruce','Gray','MO2397'),
 (31,'Arlen','Green','OR2946'),
 (32,'Carroll','Gutierrez','NK2704'),
 (33,'Amos','Hall','OR2946'),
 (34,'Algernon','Harris','ZE1483'),
 (35,'Albern','Hernandez','MO2397'),
 (36,'Atwater','Hill','RH3840'),
 (37,'Brandan','Howard','RI1584'),
 (38,'Bud','Hughes','ZE1483'),
 (39,'Alden','Jackson','NU1978'),
 (40,'Bruno','James','RH3840'),
 (41,'Carney','Jenkins','MO2397'),
 (42,'Abbott','Johnson','OR2946'),
 (43,'Abraham','Jones','NK2704'),
 (44,'Bradley','Kelly','NK2704'),
 (45,'Archer','King','NK2704'),
 (46,'Alfie','Lee','RI1584'),
 (47,'Alton','Lewis','ZE1483'),
 (48,'Byron','Long','NU1978'),
 (49,'Alexander','Lopez','ZE1483'),
 (50,'Albion','Martin','YJ4842'),
 (51,'Aedan','Mart','OR2946'),
 (52,'Adam','Miller','RH3840'),
 (53,'Austin','Mitchell','ZE1483'),
 (54,'Albert','Moore','NU1978'),
 (55,'Caleb','Morales','NK2704'),
 (56,'Blaine','Morgan','RI1584'),
 (57,'Benedict','Morris','YJ4842'),
 (58,'Benton','Murphy','NK2704'),
 (59,'Burton','Myers','ZE1483'),
 (60,'Arvel','Nelson','RH3840'),
 (61,'Benjamin','Nguyen','NK2704'),
 (62,'Carlton','Ortiz','NU1978'),
 (63,'Baron','Parker','MO2397'),
 (64,'Amery','Perez','OR2946'),
 (65,'Carter','Perry','MO2397'),
 (66,'Blair','Peterson','YJ4842'),
 (67,'Baird','Phillips','NK2704'),
 (68,'Calvin','Powell','RI1584'),
 (69,'Burgess','Price','YJ4842'),
 (70,'Atwood','Ramirez','RI1584'),
 (71,'Bond','Reed','RH3840'),
 (72,'Bryant','Reyes','YJ4842'),
 (73,'Brice','Richardson','NK2704'),
 (74,'Bernard','Rivera','NK2704'),
 (75,'Avery','Roberts','MO2397'),
 (76,'Alvin','Robinson','RI1584'),
 (77,'Adley','Rodriguez','OR2946'),
 (78,'Bevis','Rogers','YJ4842'),
 (79,'Caldwell','Ross','MO2397'),
 (80,'Carl','Russell','MO2397'),
 (81,'Ansel','Sanchez','NK2704'),
 (82,'Calvert','Sanders','RI1584'),
 (83,'Archibald','Scott','MO2397'),
 (84,'Aaron','Smith','NU1978'),
 (85,'Bartholomew','Stewart','ZE1483'),
 (86,'Carrick','Sullivan','NK2704'),
 (87,'Alan','Taylor','NU1978'),
 (88,'Alastair','Thomas','NK2704'),
 (89,'Aldis','Thompson','MO2397'),
 (90,'Barnaby','Torres','NK2704'),
 (91,'Barclay','Turner','NK2704'),
 (92,'Ambrose','Walker','MO2397'),
 (93,'Brent','Ward','MO2397'),
 (94,'Brock','Watson','RH3840'),
 (95,'Aldrich','White','NK2704'),
 (96,'Abel','Williams','NU1978'),
 (97,'Adrian','Wilson','OR2946'),
 (98,'Brigham','Wood','NK2704'),
 (99,'Anthony','Wright','RI1584'),
 (100,'Andrew','Young','RI1584');
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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Alert_Type`
--

/*!40000 ALTER TABLE `Alert_Type` DISABLE KEYS */;
LOCK TABLES `Alert_Type` WRITE;
INSERT INTO `airtracker`.`Alert_Type` VALUES  (1,'cart'),
 (2,'loader'),
 (3,'pusher');
UNLOCK TABLES;
/*!40000 ALTER TABLE `Alert_Type` ENABLE KEYS */;


--
-- Definition of table `airtracker`.`Bag`
--

DROP TABLE IF EXISTS `airtracker`.`Bag`;
CREATE TABLE  `airtracker`.`Bag` (
  `bag_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `flight_id` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`bag_id`)
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Bag`
--

/*!40000 ALTER TABLE `Bag` DISABLE KEYS */;
LOCK TABLES `Bag` WRITE;
INSERT INTO `airtracker`.`Bag` VALUES  (1,'RI1584'),
 (2,'RI1584'),
 (3,'NK2704'),
 (4,'MO2397'),
 (5,'MO2397'),
 (6,'MO2397'),
 (7,'RH3840'),
 (8,'RH3840'),
 (9,'NK2704'),
 (10,'NK2704'),
 (11,'YJ4842'),
 (12,'YJ4842'),
 (13,'RH3840'),
 (14,'RH3840'),
 (15,'RI1584'),
 (16,'RI1584'),
 (17,'ZE1483'),
 (18,'RH3840'),
 (19,'MO2397'),
 (20,'YJ4842'),
 (21,'RH3840'),
 (22,'OR2946'),
 (23,'RH3840'),
 (24,'RH3840'),
 (25,'ZE1483'),
 (26,'ZE1483'),
 (27,'ZE1483'),
 (28,'NK2704'),
 (29,'ZE1483'),
 (30,'ZE1483'),
 (31,'NU1978'),
 (32,'NU1978'),
 (33,'OR2946'),
 (34,'OR2946'),
 (35,'NK2704'),
 (36,'NK2704'),
 (37,'NK2704'),
 (38,'RI1584'),
 (39,'RI1584'),
 (40,'ZE1483'),
 (41,'NU1978'),
 (42,'OR2946'),
 (43,'OR2946'),
 (44,'RH3840'),
 (45,'ZE1483'),
 (46,'ZE1483'),
 (47,'NK2704'),
 (48,'NK2704'),
 (49,'YJ4842'),
 (50,'NK2704'),
 (51,'NK2704'),
 (52,'ZE1483'),
 (53,'RH3840'),
 (54,'NK2704'),
 (55,'NK2704'),
 (56,'MO2397'),
 (57,'OR2946'),
 (58,'OR2946'),
 (59,'MO2397'),
 (60,'YJ4842'),
 (61,'YJ4842'),
 (62,'YJ4842'),
 (63,'RI1584'),
 (64,'RI1584'),
 (65,'RH3840'),
 (66,'NK2704'),
 (67,'MO2397'),
 (68,'MO2397'),
 (69,'RI1584'),
 (70,'YJ4842'),
 (71,'MO2397'),
 (72,'RI1584'),
 (73,'MO2397'),
 (74,'MO2397'),
 (75,'NU1978'),
 (76,'NU1978'),
 (77,'ZE1483'),
 (78,'ZE1483'),
 (79,'NK2704'),
 (80,'NK2704'),
 (81,'NU1978'),
 (82,'MO2397'),
 (83,'NK2704'),
 (84,'NK2704'),
 (85,'MO2397'),
 (86,'MO2397'),
 (87,'MO2397'),
 (88,'RH3840'),
 (89,'RH3840'),
 (90,'NU1978'),
 (91,'NU1978'),
 (92,'OR2946'),
 (93,'OR2946'),
 (94,'RI1584'),
 (95,'RI1584'),
 (96,'RI1584');
UNLOCK TABLES;
/*!40000 ALTER TABLE `Bag` ENABLE KEYS */;


--
-- Definition of table `airtracker`.`Carrier`
--

DROP TABLE IF EXISTS `airtracker`.`Carrier`;
CREATE TABLE  `airtracker`.`Carrier` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bag_id` bigint(20) unsigned NOT NULL,
  `cart_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=97 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Carrier`
--

/*!40000 ALTER TABLE `Carrier` DISABLE KEYS */;
LOCK TABLES `Carrier` WRITE;
INSERT INTO `airtracker`.`Carrier` VALUES  (1,1,7),
 (2,2,7),
 (3,3,2),
 (4,4,1),
 (5,5,1),
 (6,6,1),
 (7,7,4),
 (8,8,4),
 (9,9,2),
 (10,10,2),
 (11,11,6),
 (12,12,6),
 (13,13,4),
 (14,14,4),
 (15,15,7),
 (16,16,7),
 (17,17,8),
 (18,18,4),
 (19,19,1),
 (20,20,6),
 (21,21,4),
 (22,22,3),
 (23,23,4),
 (24,24,4),
 (25,25,8),
 (26,26,8),
 (27,27,8),
 (28,28,2),
 (29,29,8),
 (30,30,8),
 (31,31,5),
 (32,32,5),
 (33,33,3),
 (34,34,3),
 (35,35,2),
 (36,36,2),
 (37,37,2),
 (38,38,7),
 (39,39,7),
 (40,40,8),
 (41,41,5),
 (42,42,3),
 (43,43,3),
 (44,44,4),
 (45,45,8),
 (46,46,8),
 (47,47,2),
 (48,48,2),
 (49,49,6),
 (50,50,2),
 (51,51,2),
 (52,52,8),
 (53,53,4),
 (54,54,2),
 (55,55,2),
 (56,56,1),
 (57,57,3),
 (58,58,3),
 (59,59,1),
 (60,60,6),
 (61,61,6),
 (62,62,6),
 (63,63,7),
 (64,64,7),
 (65,65,4),
 (66,66,2),
 (67,67,1),
 (68,68,1),
 (69,69,7),
 (70,70,6),
 (71,71,1),
 (72,72,7),
 (73,73,1),
 (74,74,1),
 (75,75,5),
 (76,76,5),
 (77,77,8),
 (78,78,8),
 (79,79,2),
 (80,80,2),
 (81,81,5),
 (82,82,1),
 (83,83,2),
 (84,84,2),
 (85,85,1),
 (86,86,1),
 (87,87,1),
 (88,88,4),
 (89,89,4),
 (90,90,5),
 (91,91,5),
 (92,92,3),
 (93,93,3),
 (94,94,7),
 (95,95,7),
 (96,96,7);
UNLOCK TABLES;
/*!40000 ALTER TABLE `Carrier` ENABLE KEYS */;


--
-- Definition of table `airtracker`.`Cart`
--

DROP TABLE IF EXISTS `airtracker`.`Cart`;
CREATE TABLE  `airtracker`.`Cart` (
  `cart_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scanner_id` int(10) unsigned NOT NULL,
  `gate_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`cart_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Cart`
--

/*!40000 ALTER TABLE `Cart` DISABLE KEYS */;
LOCK TABLES `Cart` WRITE;
INSERT INTO `airtracker`.`Cart` VALUES  (8,38,'8'),
 (7,37,'7'),
 (6,36,'6'),
 (5,35,'5'),
 (4,34,'4'),
 (3,33,'3'),
 (2,32,'2'),
 (1,31,'1');
UNLOCK TABLES;
/*!40000 ALTER TABLE `Cart` ENABLE KEYS */;


--
-- Definition of table `airtracker`.`Loader`
--

DROP TABLE IF EXISTS `airtracker`.`Loader`;
CREATE TABLE  `airtracker`.`Loader` (
  `loader_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `scanner_id` int(10) unsigned NOT NULL,
  `gate_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`loader_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `airtracker`.`Loader`
--

/*!40000 ALTER TABLE `Loader` DISABLE KEYS */;
LOCK TABLES `Loader` WRITE;
INSERT INTO `airtracker`.`Loader` VALUES  (8,48,'8'),
 (7,47,'7'),
 (6,46,'6'),
 (5,45,'5'),
 (4,44,'4'),
 (3,43,'3'),
 (2,42,'2'),
 (1,41,'1');
UNLOCK TABLES;
/*!40000 ALTER TABLE `Loader` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
