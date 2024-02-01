/*
SQLyog Community
MySQL - 8.0.31 : Database - tidrapportering
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `aktiviteter` */

DROP TABLE IF EXISTS `aktiviteter`;

CREATE TABLE `aktiviteter` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Namn` varchar(50) COLLATE utf8mb3_swedish_ci NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `UIX_Namn` (`Namn`)
) ENGINE=InnoDB AUTO_INCREMENT=1335 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

/*Data for the table `aktiviteter` */

insert  into `aktiviteter`(`ID`,`Namn`) values 
(1183,'Gaming'),
(1182,'Javascript'),
(1181,'PHP'),
(1184,'Workout');

/*Table structure for table `uppgifter` */

DROP TABLE IF EXISTS `uppgifter`;

CREATE TABLE `uppgifter` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Datum` date NOT NULL,
  `Tid` time NOT NULL,
  `Beskrivning` varchar(100) COLLATE utf8mb3_swedish_ci DEFAULT NULL,
  `AktivitetID` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `AktivitetID` (`AktivitetID`),
  CONSTRAINT `uppgifter_ibfk_1` FOREIGN KEY (`AktivitetID`) REFERENCES `aktiviteter` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=339 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_swedish_ci;

/*Data for the table `uppgifter` */

insert  into `uppgifter`(`ID`,`Datum`,`Tid`,`Beskrivning`,`AktivitetID`) values 
(254,'2024-01-29','04:00:00','Jobbade med PHP med Kjell',1181),
(255,'2024-01-29','01:00:00','Kopplade min API Till mina sidor så att det ska fungera',1182),
(256,'2024-01-28','04:00:00','Spelade Valorant',1183),
(257,'2023-06-05','00:30:00','Work on big muscles :)',1184),
(258,'2023-01-27','00:30:00','Worked on Valorant aiming',1183),
(259,'2023-01-19','05:00:00','Minecraft',1183),
(260,'2023-01-16','04:00:00','Minecraft',1183),
(261,'2023-12-05','03:00:00','Leg day',1184),
(262,'2024-01-14','03:00:00','PHP',1181),
(263,'2024-01-21','07:00:00','',1183);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
