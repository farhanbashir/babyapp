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

/*Table structure for table `album_images` */

DROP TABLE IF EXISTS `album_images`;

CREATE TABLE `album_images` (
  `album_image_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `baby_id` int(11) DEFAULT NULL,
  `milestone_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`album_image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `album_images` */

/*Table structure for table `ask_expert` */

DROP TABLE IF EXISTS `ask_expert`;

CREATE TABLE `ask_expert` (
  `ask_expert_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `baby_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`ask_expert_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `ask_expert` */

insert  into `ask_expert`(`ask_expert_id`,`user_id`,`baby_id`,`email`,`subject`,`message`,`date`) values (1,2,1,'farhan.bashir@gmail.com','test subject','hello how are you','2015-04-17');

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
  `gender` tinyint(1) DEFAULT '0' COMMENT '0=male, 1=female',
  PRIMARY KEY (`baby_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `babies` */

insert  into `babies`(`baby_id`,`user_id`,`first_name`,`image`,`dob`,`weight`,`height`,`gender`) values (2,1,'aa','http://localhost/babyapp/images/ami.jpg','2015-01-01',16.00,2.14,1);

/*Table structure for table `baby_milestones` */

DROP TABLE IF EXISTS `baby_milestones`;

CREATE TABLE `baby_milestones` (
  `baby_milestone_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `baby_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `milestone_id` int(11) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`baby_milestone_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `baby_milestones` */

insert  into `baby_milestones`(`baby_milestone_id`,`baby_id`,`date`,`milestone_id`,`caption`,`image`) values (1,1,'2015-04-12',2,'this is test image',NULL),(2,1,'2015-04-12',2,'this is test image','http://localhost/babyapp/images/1/2/beaf.jpg');

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
  `feed` varchar(1000) DEFAULT NULL,
  `intro` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`feed_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

/*Data for the table `feeds` */

insert  into `feeds`(`feed_id`,`from`,`to`,`feed`,`intro`) values (1,0,0,'Here you can track the growth and development of your toddler, save and share important milestones, and ask our experts all of your questions.',''),(2,1,12,'They will be exploring the world around them and discovering the joys of independence. But dont worry if they are not walking yet. Your toddler will start copying what you do too. Its one of the ways they learn new things. ','Say hello to your toddler'),(3,12,13,'But it’s totally fine if they’re still happy with crawling or cruising. They’ll be interested in finding out what happens when they stack or throw things. It’s also around this time that toddlers experience separation anxiety. You’ll soon start hearing their first words (like ‘mummy’ and ‘daddy’) too.','Look out for those first steps'),(4,13,14,'Your toddler will be exploring everything, especially the things you don’t want them to. So cover electric sockets, install stair gates, lock cupboards and make sure you move anything precious or dangerous out of their reach. As they learn to feed themselves, be prepared for messy mealtimes too','Time to make sure your home is toddler-proof'),(5,14,15,'Your toddler’s sense of individuality begins to emerge at this age. Try putting them in front of a mirror and see if they recognise themselves. But this important and exciting milestone comes with a growing tendency to disagree with you. So get ready to hear the word ‘no’.\r\n','Expect to hear the word ‘no’ more often'),(6,15,16,'It’s a hectic time for both of you, as your toddler learns to understand the world around them. Your toddler is growing and their brain is developing rapidly too. So they’ll be working out how to run, talk, play and keep you on your toes. ','Growing fast and learning fast'),(7,16,17,'Their defiant or naughty behaviour might be hard to cope with, but it’s just their way of finding out the rules. So set the boundaries and enforce them consistently and calmly. For a bit of fun, games that involve sorting objects by colour and shape are perfect for this stage.\r\n','Your toddler wants to know your limits\r\n'),(8,17,18,'Life will get more challenging as your toddler becomes more independent and self-assertive. Expect the occasional tantrum, as they test the boundaries of what they can and can’t do. You’ll need to be extra patient with them as they learn to do new things for themselves.','Learning to stand on their own two feet'),(9,18,19,'By now your toddler will be starting to put together basic sentences and will be able to understand most of what you’re saying. So the more you talk to them the better. They’ll also notice when things aren’t the way they should be and will laugh at the results. ','There’s plenty to talk about'),(10,19,20,'They might even be able to climb the stairs – with a little help from you. You’ll also find they can get easily upset over small things. Don’t worry. It’s quite normal for them to have difficulty dealing with disappointment or coping with changes to their routine.\r\n','Get set for your toddler to start running\r\n'),(11,20,21,'Some take to it straight away, others need a little longer. But get them used to seeing a potty in the house. Your toddler can be very stubborn about what they like and don’t like. While you should be clear about what isn’t acceptable, don’t battle over the small things. \r\n','It’s time to start potty-training'),(12,21,22,'As their social skills begin to develop, you’ll find they’re more and more interested in playing with other children. You’ll also start to see your toddler come up with ideas and plans. They’ll get very excited when things work out the way they hoped, but frustrated when they don’t. ','Forming their first friendships'),(13,22,23,'As their brain continues its incredible development, their language skills will become increasingly advanced. So expect to hear simple sentences as you enjoy proper conversations with each other. You can also encourage them to express their growing creative skills with plenty of paint, pencils and paper.','Your first proper conversations'),(14,23,24,'At around two years, some toddlers are ready to use a potty by themselves. But this can take a while, so be patient, give them plenty of encouragement and be prepared for a few accidents. If they’re not ready yet, don’t worry. All toddlers develop at their own pace.\r\n','Happy Birthday to your two-year-old toddler'),(15,24,25,'The more they can do for themselves, the easier life becomes for you. So you’ll be glad to know they’ll be mastering important skills like washing their hands and brushing their own teeth. Playtime is getting more adventurous too, as they hone their ability to jump and throw.\r\n','Their journey towards independence'),(16,25,26,'At this age, toddlers can be particularly stubborn and will often refuse to follow your instructions. It can become very frustrating for both of you, so try to be patient. On the plus side, you’ll hear them speak more two- or three-word sentences as their vocabulary continues to expand. ','The ‘terrible twos’ can be a testing time'),(17,26,27,'You’ll notice that your toddler is now able to concentrate on what they’re doing for longer periods of time. This means they’re much happier getting on with whatever task they’ve set their mind on. However, they won’t like being interrupted when it’s time to stop playing.\r\n','A more focused toddler at play'),(18,27,28,'Tantrums still happen, usually when your toddler is so frustrated or distressed by a situation they have no other way to express themselves. Nobody’s perfect, so don’t expect them to be happy the whole time. Just talk calmly about their feelings and try not to get too exasperated.\r\n','Toddlers and their tempers'),(19,28,29,'You’ll notice your toddler can get dressed with only a little help from you. They’ll be happier playing with other children and might know their names too. Their vocabulary may now include colours and parts of the body. Remember, the more you talk to them, the more they’ll learn.','Learning new words and making new friends'),(20,29,30,'Understanding rules and the normal way of things is a complex skill that your toddler is starting to master. While they may still get annoyed or even upset when things don’t go according to plan, they will begin to learn that life doesn’t always work out that way.','Getting to know the rules'),(21,30,31,'Your child may insist on doing certain things for themselves. Even if they’re not fully capable, they’ll find it frustrating if you interfere. So if possible, it’s best to let them get on with it and learn from their mistakes. This will help to build their self-confidence too.\r\n','Growing in confidence'),(22,31,32,'Now’s a good time to see if your toddler is ready to make the move from potty to toilet. Letting them see you empty their potty into the toilet or watch you using the bathroom is a good way to show them how it should be done.\r\n','It’s time to start using the toilet'),(23,32,33,'As their emotional development accelerates, your toddler will start to pick up on the moods of other people – both happy and sad. So now is a good age to reassure them and explain that emotions are only natural. Their own distinct personality will begin to become more obvious too. \r\n','Their little personality emerges\r\n'),(24,33,34,'As their third birthday draws near, you’ll see that their play becomes more imaginative and sociable, as friendships start to be formed. You’ll find your toddler becomes more and more helpful too. It’s their way of showing how much they love, and want to be like, you.','A helping hand for mum'),(25,34,35,'It’s nearly their third birthday. You’ve come so far together since the start of toddlerhood, and the next exciting stage of life is just around the corner. By now your child should be able to get dressed all by themselves and will have a vocabulary of around 900 words.\r\n','It’s almost time to say goodbye to toddlerhood');

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
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

/*Data for the table `growth` */

insert  into `growth`(`growth_id`,`user_id`,`baby_id`,`date`,`weight`,`height`) values (15,1,2,'2015-03-21',12.25,109.12),(16,1,2,'2015-03-23',12.25,999.99),(17,1,2,'2015-03-23',12.25,1090.12),(18,1,2,'2015-03-23',12.25,109000.12),(19,2,2,'2015-04-12',12.00,2.10),(20,1,2,'2015-04-12',12.00,2.10),(21,1,2,'2015-04-12',12.21,2.10),(22,1,2,'2015-04-12',12.00,2.10),(23,1,2,'2015-04-12',12.00,2.14),(24,1,2,'2015-04-12',12.00,2.14),(25,1,2,'2015-04-12',16.00,2.14);

/*Table structure for table `milestones` */

DROP TABLE IF EXISTS `milestones`;

CREATE TABLE `milestones` (
  `milestone_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `milestone_name` varchar(255) DEFAULT NULL,
  `milestone_description` text,
  PRIMARY KEY (`milestone_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

/*Data for the table `milestones` */

insert  into `milestones`(`milestone_id`,`milestone_name`,`milestone_description`) values (1,'First step',NULL),(2,'First birthday',NULL),(3,'First bike ride',NULL),(4,'First book',NULL),(5,'First use of spoon and fork',NULL),(6,'First potty training',NULL),(7,'First drawing',NULL),(8,'First brush of teeth',NULL),(9,'First friend',NULL),(10,'First swim',NULL);

/*Table structure for table `tracks` */

DROP TABLE IF EXISTS `tracks`;

CREATE TABLE `tracks` (
  `track_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gender` tinyint(1) DEFAULT '0' COMMENT '0=male,1=female',
  `age` float(10,1) DEFAULT NULL COMMENT 'age in month',
  `type` tinyint(1) DEFAULT NULL COMMENT '1=length, 2=height',
  `p25` float(10,5) DEFAULT NULL,
  `p50` float(10,5) DEFAULT NULL,
  `p75` float(10,5) DEFAULT NULL,
  PRIMARY KEY (`track_id`)
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=latin1;

/*Data for the table `tracks` */

insert  into `tracks`(`track_id`,`gender`,`age`,`type`,`p25`,`p50`,`p75`) values (1,1,0.0,1,48.18937,49.98888,51.77126),(2,1,0.5,1,50.97919,52.69598,54.44054),(3,1,1.5,1,54.97910,56.62843,58.35059),(4,1,2.5,1,57.97440,59.60895,61.33788),(5,1,3.5,1,60.43433,62.07700,63.82543),(6,1,4.5,1,62.55409,64.21686,65.99131),(7,1,5.5,1,64.43546,66.12531,67.92935),(8,1,6.5,1,66.13896,67.86018,69.69579),(9,1,7.5,1,67.70375,69.45908,71.32735),(10,1,8.5,1,69.15682,70.94804,72.84947),(11,1,9.5,1,70.51761,72.34586,74.28060),(12,1,10.5,1,71.80065,73.66665,75.63462),(13,1,11.5,1,73.01712,74.92130,76.92224),(14,1,12.5,1,74.17581,76.11838,78.15196),(15,1,13.5,1,75.28380,77.26480,79.33061),(16,1,14.5,1,76.34685,78.36622,80.46380),(17,1,15.5,1,77.36973,79.42734,81.55620),(18,1,16.5,1,78.35646,80.45209,82.61174),(19,1,17.5,1,79.31042,81.44384,83.63377),(20,1,18.5,1,80.23453,82.40544,84.62515),(21,1,19.5,1,81.13131,83.33938,85.58837),(22,1,20.5,1,82.00292,84.24783,86.52562),(23,1,21.5,1,82.85120,85.13270,87.43879),(24,1,22.5,1,83.67811,85.99565,88.32957),(25,1,23.5,1,84.48487,86.83818,89.19948),(26,1,24.5,1,85.27290,87.66161,90.04985),(27,1,25.5,1,86.03703,88.45247,90.87870),(28,1,26.5,1,86.78329,89.22326,91.68468),(29,1,27.5,1,87.51317,89.97549,92.46929),(30,1,28.5,1,88.22788,90.71041,93.23385),(31,1,29.5,1,88.92840,91.42908,93.97951),(32,1,30.5,1,89.61560,92.13242,94.70732),(33,1,31.5,1,90.29020,92.82127,95.41824),(34,1,32.5,1,90.95287,93.49638,96.11319),(35,1,33.5,1,91.60421,94.15847,96.79307),(36,1,34.5,1,92.24482,94.80823,97.45873),(37,1,35.5,1,92.87525,95.44637,98.11108),(38,0,0.0,1,47.68345,49.28640,51.01870),(39,0,0.5,1,50.09686,51.68358,53.36362),(40,0,1.5,1,53.69078,55.28613,56.93136),(41,0,2.5,1,56.47125,58.09382,59.74045),(42,0,3.5,1,58.80346,60.45981,62.12330),(43,0,4.5,1,60.84386,62.53670,64.22507),(44,0,5.5,1,62.67590,64.40633,66.12418),(45,0,6.5,1,64.35005,66.11842,67.86850),(46,0,7.5,1,65.89952,67.70574,69.48975),(47,0,8.5,1,67.34745,69.19124,71.01019),(48,0,9.5,1,68.71070,70.59164,72.44614),(49,0,10.5,1,70.00202,71.91962,73.80997),(50,0,11.5,1,71.23128,73.18501,75.11133),(51,0,12.5,1,72.40633,74.39564,76.35791),(52,0,13.5,1,73.53349,75.55785,77.55594),(53,0,14.5,1,74.61799,76.67686,78.71058),(54,0,15.5,1,75.66416,77.75701,79.82613),(55,0,16.5,1,76.67568,78.80198,80.90623),(56,0,17.5,1,77.65565,79.81492,81.95399),(57,0,18.5,1,78.60678,80.79852,82.97211),(58,0,19.5,1,79.53138,81.75512,83.96292),(59,0,20.5,1,80.43150,82.68679,84.92846),(60,0,21.5,1,81.30893,83.59532,85.87054),(61,0,22.5,1,82.16525,84.48233,86.79077),(62,0,23.5,1,83.00187,85.34924,87.69056),(63,0,24.5,1,83.82007,86.19732,88.57121),(64,0,25.5,1,84.67209,87.09026,89.50562),(65,0,26.5,1,85.50360,87.95714,90.40982),(66,0,27.5,1,86.31151,88.79602,91.28258),(67,0,28.5,1,87.09346,89.60551,92.12313),(68,0,29.5,1,87.84783,90.38477,92.93113),(69,0,30.5,1,88.57362,91.13342,93.70662),(70,0,31.5,1,89.27042,91.85154,94.45005),(71,0,32.5,1,89.93835,92.53964,95.16218),(72,0,33.5,1,90.57795,93.19854,95.84411),(73,0,34.5,1,91.19020,93.82945,96.49721),(74,0,35.5,1,91.77639,94.43382,97.12307),(75,1,0.0,2,3.15061,3.53020,3.87908),(76,1,0.5,2,3.59740,4.00311,4.38742),(77,1,1.5,2,4.42887,4.87953,5.32733),(78,1,2.5,2,5.18338,5.67289,6.17560),(79,1,3.5,2,5.86681,6.39139,6.94222),(80,1,4.5,2,6.48497,7.04184,7.63532),(81,1,5.5,2,7.04363,7.63042,8.26203),(82,1,6.5,2,7.54835,8.16295,8.82879),(83,1,7.5,2,8.00440,8.64483,9.34149),(84,1,8.5,2,8.41672,9.08112,9.80559),(85,1,9.5,2,8.78988,9.47650,10.22612),(86,1,10.5,2,9.12811,9.83531,10.60772),(87,1,11.5,2,9.43528,10.16154,10.95466),(88,1,12.5,2,9.71494,10.45885,11.27087),(89,1,13.5,2,9.97034,10.73063,11.55996),(90,1,14.5,2,10.20442,10.97992,11.82524),(91,1,15.5,2,10.41986,11.20956,12.06973),(92,1,16.5,2,10.61910,11.42207,12.29617),(93,1,17.5,2,10.80433,11.61978,12.50708),(94,1,18.5,2,10.97753,11.80478,12.70473),(95,1,19.5,2,11.14047,11.97897,12.89117),(96,1,20.5,2,11.29477,12.14404,13.06825),(97,1,21.5,2,11.44185,12.30154,13.23765),(98,1,22.5,2,11.58298,12.45283,13.40086),(99,1,23.5,2,11.71930,12.59913,13.55920),(100,1,24.5,2,11.85182,12.74154,13.71386),(101,1,25.5,2,11.98142,12.88102,13.86590),(102,1,26.5,2,12.10889,13.01842,14.01623),(103,1,27.5,2,12.23491,13.15450,14.16567),(104,1,28.5,2,12.36007,13.28990,14.31493),(105,1,29.5,2,12.48490,13.42519,14.46462),(106,1,30.5,2,12.60983,13.56088,14.61527),(107,1,31.5,2,12.73523,13.69738,14.76732),(108,1,32.5,2,12.86144,13.83505,14.92117),(109,1,33.5,2,12.98870,13.97418,15.07711),(110,1,34.5,2,13.11723,14.11503,15.23541),(111,1,35.5,2,13.24721,14.25780,15.39628),(112,1,36.0,2,13.31278,14.32994,15.47772),(113,2,0.0,2,3.06487,3.39919,3.71752),(114,2,0.5,2,3.43763,3.79753,4.14559),(115,2,1.5,2,4.13899,4.54478,4.94677),(116,2,2.5,2,4.78482,5.23058,5.68008),(117,2,3.5,2,5.37914,5.85996,6.35151),(118,2,4.5,2,5.92589,6.43759,6.96652),(119,2,5.5,2,6.42883,6.96785,7.53018),(120,2,6.5,2,6.89153,7.45485,8.04718),(121,2,7.5,2,7.31737,7.90244,8.52188),(122,2,8.5,2,7.70952,8.31418,8.95832),(123,2,9.5,2,8.07093,8.69342,9.36027),(124,2,10.5,2,8.40440,9.04326,9.73119),(125,2,11.5,2,8.71251,9.36659,10.07431),(126,2,12.5,2,8.99769,9.66609,10.39258),(127,2,13.5,2,9.26219,9.94423,10.68874),(128,2,14.5,2,9.50808,10.20329,10.96532),(129,2,15.5,2,9.73733,10.44541,11.22463),(130,2,16.5,2,9.95172,10.67251,11.46878),(131,2,17.5,2,10.15290,10.88639,11.69972),(132,2,18.5,2,10.34241,11.08868,11.91921),(133,2,19.5,2,10.52167,11.28090,12.12887),(134,2,20.5,2,10.69196,11.46440,12.33016),(135,2,21.5,2,10.85446,11.64043,12.52439),(136,2,22.5,2,11.01027,11.81014,12.71277),(137,2,23.5,2,11.16037,11.97454,12.89636),(138,2,24.5,2,11.30567,12.13456,13.07613),(139,2,25.5,2,11.44697,12.29102,13.25293),(140,2,26.5,2,11.58501,12.44469,13.42753),(141,2,27.5,2,11.72047,12.59622,13.60059),(142,2,28.5,2,11.85392,12.74621,13.77271),(143,2,29.5,2,11.98592,12.89517,13.94440),(144,2,30.5,2,12.11692,13.04357,14.11611),(145,2,31.5,2,12.24735,13.19181,14.28822),(146,2,32.5,2,12.37757,13.34023,14.46106),(147,2,33.5,2,12.50791,13.48913,14.63491),(148,2,34.5,2,12.63865,13.63877,14.80998),(149,2,35.5,2,12.77001,13.78937,14.98647),(150,2,36.0,2,12.83600,13.86507,15.07529);

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
