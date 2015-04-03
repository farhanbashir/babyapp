/*
SQLyog Enterprise - MySQL GUI v7.14 
MySQL - 5.5.34-0ubuntu0.12.10.1 : Database - babyapp
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`babyapp` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `babyapp`;

/*Table structure for table `babies` */

DROP TABLE IF EXISTS `babies`;

CREATE TABLE `babies` (
  `baby_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `weight` float(10,2) DEFAULT NULL,
  `height` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`baby_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `babies` */

insert  into `babies`(`baby_id`,`user_id`,`first_name`,`image`,`dob`,`weight`,`height`) values (2,1,'bbbb','','2015-01-01',12.00,8207.00);

/*Table structure for table `devices` */

DROP TABLE IF EXISTS `devices`;

CREATE TABLE `devices` (
  `device_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '0=iphone,1=android',
  PRIMARY KEY (`device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Data for the table `devices` */

insert  into `devices`(`device_id`,`user_id`,`uid`,`type`) values (4,1,'asfadsfs',0),(5,1,'asfadsfs',0),(6,1,NULL,NULL),(7,1,NULL,NULL),(8,1,NULL,NULL),(9,1,NULL,NULL),(10,1,NULL,NULL),(11,1,NULL,NULL),(12,1,NULL,NULL),(13,1,NULL,NULL),(14,1,NULL,NULL);

/*Table structure for table `feeds` */

DROP TABLE IF EXISTS `feeds`;

CREATE TABLE `feeds` (
  `feed_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `from` int(5) DEFAULT NULL,
  `to` int(5) DEFAULT NULL,
  `feed` text,
  PRIMARY KEY (`feed_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `feeds` */

insert  into `feeds`(`feed_id`,`from`,`to`,`feed`) values (1,90,92,'asdf as  fas fadsf d f');

/*Table structure for table `growth` */

DROP TABLE IF EXISTS `growth`;

CREATE TABLE `growth` (
  `growth_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `baby_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `weight` float(10,2) DEFAULT NULL,
  `height` float(10,2) DEFAULT NULL,
  PRIMARY KEY (`growth_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

/*Data for the table `growth` */

insert  into `growth`(`growth_id`,`user_id`,`baby_id`,`date`,`weight`,`height`) values (15,1,2,'2015-03-21',12.25,109.12),(16,1,2,'2015-03-23',12.25,999.99),(17,1,2,'2015-03-23',12.25,1090.12),(18,1,2,'2015-03-23',12.25,109000.12);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gender` tinyint(1) DEFAULT '0' COMMENT '0=male, 1=female',
  `dob` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1' COMMENT '0=inactive, 1=active',
  `verified` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `users` */

insert  into `users`(`user_id`,`first_name`,`last_name`,`email`,`password`,`image`,`gender`,`dob`,`is_active`,`verified`) values (1,'farhan1','bashir','farhan.bashir2002@gmail.com','f4a5666799f91651381ec4396103ad0d','http://localhost/babyapp/images/logo.png',1,'1999-03-18',1,1);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
