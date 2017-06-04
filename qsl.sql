-- MySQL dump 10.15  Distrib 10.0.30-MariaDB, for debian-linux-gnueabihf (armv7l)
--
-- Host: localhost    Database: qsl
-- ------------------------------------------------------
-- Server version	10.0.30-MariaDB-0+deb8u2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `qsl`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `qsl` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `qsl`;

--
-- Table structure for table `loadlog`
--

-- DROP TABLE IF EXISTS `loadlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `loadlog` (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `loadcall` char(15) NOT NULL,
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `transid` char(64) DEFAULT NULL,
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `qsos`
--

-- DROP TABLE IF EXISTS `qsos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qsos` (
  `qsoid` int(11) NOT NULL AUTO_INCREMENT,
  `callsign` char(20) NOT NULL,
  `band` char(20) NOT NULL,
  `freq` char(30) NOT NULL,
  `rstrcvd` char(10) DEFAULT NULL,
  `qsodate` char(14) DEFAULT NULL,
  `timeon` char(14) DEFAULT NULL,
  `operator` char(10) NOT NULL,
  `station` char(10) NOT NULL,
  `mode` char(10) DEFAULT NULL,
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `logid` int(11) NOT NULL,
  `county` char(30) DEFAULT NULL,
  PRIMARY KEY (`qsoid`),
  KEY `callsign_idx` (`callsign`)
) ENGINE=InnoDB AUTO_INCREMENT=1883 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `trans`
--

--DROP TABLE IF EXISTS `trans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trans` (
  `transid` char(64) NOT NULL,
  `transdata` longtext,
  `transtimet` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-06-04 16:01:21
