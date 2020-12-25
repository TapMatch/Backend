-- MySQL dump 10.13  Distrib 5.7.32, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: tapmatch
-- ------------------------------------------------------
-- Server version	5.7.32-0ubuntu0.18.04.1

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
-- Table structure for table `community`
--

DROP TABLE IF EXISTS `community`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `community` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_open` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `community`
--

LOCK TABLES `community` WRITE;
/*!40000 ALTER TABLE `community` DISABLE KEYS */;
INSERT INTO `community` VALUES (3,'UvA','000007','Amstedram',0),(6,'UCL','006996','London',0),(10,'TUM','120021','Munich',0),(11,'TUM','test',NULL,0),(12,'TUM','test',NULL,0),(13,'TUM','test','Moscow',0),(14,'TUM','test','Moscow',0),(15,'TUM','test','',0),(16,'TUM','test','',0),(17,'TUM','test','',0),(18,'TUM','test','',0),(19,'TUM','test','Moscow',0),(20,'TUM','test','Moscow',0),(21,'TUM','test','Moscow',0),(22,'TUM','test','Moscow',0),(23,'TUM','test','Moscow',0),(24,'TUM','test','Moscow',0);
/*!40000 ALTER TABLE `community` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `community_user`
--

DROP TABLE IF EXISTS `community_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `community_user` (
  `community_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`community_id`,`user_id`),
  KEY `IDX_4CC23C83FDA7B0BF` (`community_id`),
  KEY `IDX_4CC23C83A76ED395` (`user_id`),
  CONSTRAINT `FK_4CC23C83A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_4CC23C83FDA7B0BF` FOREIGN KEY (`community_id`) REFERENCES `community` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `community_user`
--

LOCK TABLES `community_user` WRITE;
/*!40000 ALTER TABLE `community_user` DISABLE KEYS */;
INSERT INTO `community_user` VALUES (3,7),(3,11),(3,17),(6,7),(10,6),(10,7);
/*!40000 ALTER TABLE `community_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20201124150212','2020-12-01 18:24:20',31),('DoctrineMigrations\\Version20201124152726','2020-12-01 18:24:20',35),('DoctrineMigrations\\Version20201125150409','2020-12-01 18:24:20',26),('DoctrineMigrations\\Version20201125151954','2020-12-01 18:24:20',27),('DoctrineMigrations\\Version20201130152741','2020-12-01 18:24:20',25),('DoctrineMigrations\\Version20201203082053','2020-12-03 10:20:59',101),('DoctrineMigrations\\Version20201203112144','2020-12-03 13:22:08',105),('DoctrineMigrations\\Version20201203120750','2020-12-03 14:08:34',41),('DoctrineMigrations\\Version20201203121456','2020-12-03 14:15:02',84),('DoctrineMigrations\\Version20201203122129','2020-12-03 14:21:34',32),('DoctrineMigrations\\Version20201203122515','2020-12-03 14:25:19',200),('DoctrineMigrations\\Version20201203123615','2020-12-03 14:36:20',131),('DoctrineMigrations\\Version20201203123842','2020-12-03 14:38:45',48),('DoctrineMigrations\\Version20201203131141','2020-12-03 15:11:59',104),('DoctrineMigrations\\Version20201203131921','2020-12-03 15:19:34',115),('DoctrineMigrations\\Version20201203132243','2020-12-03 15:22:46',193),('DoctrineMigrations\\Version20201203170144','2020-12-03 19:01:48',68),('DoctrineMigrations\\Version20201203172235','2020-12-03 19:22:37',183),('DoctrineMigrations\\Version20201204100134','2020-12-04 12:02:03',124),('DoctrineMigrations\\Version20201207133509','2020-12-07 15:35:16',86),('DoctrineMigrations\\Version20201208101516','2020-12-08 12:15:33',93),('DoctrineMigrations\\Version20201208101529','2020-12-08 15:46:46',34),('DoctrineMigrations\\Version20201208124516','2020-12-08 15:46:46',134),('DoctrineMigrations\\Version20201209170220','2020-12-09 19:02:31',93),('DoctrineMigrations\\Version20201211145730','2020-12-11 16:57:34',121),('DoctrineMigrations\\Version20201212145246','2020-12-12 16:52:51',88),('DoctrineMigrations\\Version20201225092326','2020-12-25 11:23:51',83),('DoctrineMigrations\\Version20201225093349','2020-12-25 11:33:53',19),('DoctrineMigrations\\Version20201225093824','2020-12-25 11:38:27',80),('DoctrineMigrations\\Version20201225093856','2020-12-25 11:38:58',79);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event`
--

DROP TABLE IF EXISTS `event`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organizer_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coordinates` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:object)',
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `join_limit` int(11) NOT NULL,
  `community_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3BAE0AA7876C4DDA` (`organizer_id`),
  KEY `IDX_3BAE0AA7FDA7B0BF` (`community_id`),
  CONSTRAINT `FK_3BAE0AA7876C4DDA` FOREIGN KEY (`organizer_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3BAE0AA7FDA7B0BF` FOREIGN KEY (`community_id`) REFERENCES `community` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
INSERT INTO `event` VALUES (30,3,'meet number two','2020-12-23 12:40:00','test','a:2:{s:4:\"test\";s:10:\"dlya_testa\";s:5:\"test2\";s:11:\"dlyu_testa2\";}','desc',10,3),(31,3,'meet number two','2020-12-23 12:40:00','test','a:2:{s:4:\"test\";s:10:\"dlya_testa\";s:5:\"test2\";s:11:\"dlyu_testa2\";}','desc',10,3),(32,3,'meet number two','2020-12-23 12:40:00','test','a:2:{s:4:\"test\";s:10:\"dlya_testa\";s:5:\"test2\";s:11:\"dlyu_testa2\";}','desc',10,3),(33,3,'meet number two','2020-12-23 12:40:00','test','a:2:{s:4:\"test\";s:10:\"dlya_testa\";s:5:\"test2\";s:11:\"dlyu_testa2\";}','desc',10,3),(34,3,'meet number two','2020-12-23 12:40:00','test','a:2:{s:4:\"test\";s:10:\"dlya_testa\";s:5:\"test2\";s:11:\"dlyu_testa2\";}','desc',10,3),(35,3,'meet number two','2020-12-23 12:40:00','test','a:2:{s:4:\"test\";s:10:\"dlya_testa\";s:5:\"test2\";s:11:\"dlyu_testa2\";}','desc',10,3),(36,3,'meet number two','2020-12-23 12:40:00','test','a:2:{s:4:\"test\";s:10:\"dlya_testa\";s:5:\"test2\";s:11:\"dlyu_testa2\";}','desc',10,3),(37,3,'meet number two','2020-12-23 12:40:00','test','a:2:{s:4:\"test\";s:10:\"dlya_testa\";s:5:\"test2\";s:11:\"dlyu_testa2\";}','desc',10,3);
/*!40000 ALTER TABLE `event` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_user`
--

DROP TABLE IF EXISTS `event_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_user` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`event_id`,`user_id`),
  KEY `IDX_92589AE271F7E88B` (`event_id`),
  KEY `IDX_92589AE2A76ED395` (`user_id`),
  CONSTRAINT `FK_92589AE271F7E88B` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_92589AE2A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_user`
--

LOCK TABLES `event_user` WRITE;
/*!40000 ALTER TABLE `event_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `event_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `api_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `date_reg` date DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` int(11) NOT NULL,
  `authy_id` int(11) NOT NULL,
  `finished_onboarding` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649444F97DD` (`phone`),
  UNIQUE KEY `UNIQ_8D93D6497BA2F5EB` (`api_token`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (3,'+380954335836','[\"123\"]','a348c9662837356145ff77fe3a84c143','Andrey','2020-12-24 14:15:47',NULL,'/assets/img/avatar/50ccf82b424207604fe254d49f72de26.png',38,319002116,0),(6,'+380964237770','[\"admin\"]','4577df3410e6695faf570b56c9fca908','Valera','2020-12-08 00:00:00',NULL,NULL,38,318694721,1),(7,'+79771278435','[]','b4343457cf89e776fead052b703bf1ac','ffff','2020-12-09 00:00:00',NULL,'93bf83f229c76b5fe9f2f7963c3099a3.jpg',7,321240702,1),(8,'+79771278135','[]',NULL,NULL,NULL,NULL,NULL,7,321741387,0),(9,'+375259586720','[]',NULL,NULL,NULL,NULL,NULL,375,242850442,0),(10,'+79520378969','[]','f01f59ed646d1615999f972ccdfca0a5','Alexey12@8_+_+_;\"!!\"(_(_)_)_(_!$!+_+_+_+','2020-12-08 00:00:00',NULL,'84b851ba93d061a30164a18f93ffe1d6.jpg',7,321759748,1),(11,'+375292103568','[]','f6b638d771fc20c2879201bba9ac0c0f','Egor','2020-12-09 00:00:00',NULL,'481edb2a55ef46cfe5e71075e049c280.jpg',375,321801469,1),(12,'+79913915013','[]',NULL,NULL,NULL,NULL,NULL,7,321813866,0),(13,'+79657298870','[]',NULL,NULL,NULL,NULL,NULL,7,321816112,0),(14,'+79966927580','[]','df83cfb38955da67be12845a7c53ca02','Alexey19&(&;₽:&:@:9:&;8;9;9;&&;&:9;02&,₽','2020-12-08 00:00:00',NULL,'aa533de1babc93ac2960c7090a7e58e9.jpg',7,321816601,1),(15,'+79771228435','[]',NULL,NULL,NULL,NULL,NULL,7,322026502,0),(16,'+491516712764','[]',NULL,NULL,NULL,NULL,NULL,49,322079084,0),(17,'+31639054123','[]','9e2d120a4eb875530a7f157b1020f102','Leon','2020-12-09 00:00:00',NULL,'e676fda194524be6bfbd2af5f08d45d0.jpg',31,322092101,1),(18,'+4915167127644','[]','706aa82d5ec580134b4ed2df5c6b1326','jcfjfuf','2020-12-09 00:00:00',NULL,'e07ac112783a4e72d624009777debffe.jpg',49,322105763,1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-25 12:23:29
