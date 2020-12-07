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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `community`
--

LOCK TABLES `community` WRITE;
/*!40000 ALTER TABLE `community` DISABLE KEYS */;
INSERT INTO `community` VALUES (3,'Амстердам','00007'),(6,'UCL','06996'),(10,'TUM','120021');
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
INSERT INTO `community_user` VALUES (3,3);
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
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20201124150212','2020-12-01 18:24:20',31),('DoctrineMigrations\\Version20201124152726','2020-12-01 18:24:20',35),('DoctrineMigrations\\Version20201125150409','2020-12-01 18:24:20',26),('DoctrineMigrations\\Version20201125151954','2020-12-01 18:24:20',27),('DoctrineMigrations\\Version20201130152741','2020-12-01 18:24:20',25),('DoctrineMigrations\\Version20201203082053','2020-12-03 10:20:59',101),('DoctrineMigrations\\Version20201203112144','2020-12-03 13:22:08',105),('DoctrineMigrations\\Version20201203120750','2020-12-03 14:08:34',41),('DoctrineMigrations\\Version20201203121456','2020-12-03 14:15:02',84),('DoctrineMigrations\\Version20201203122129','2020-12-03 14:21:34',32),('DoctrineMigrations\\Version20201203122515','2020-12-03 14:25:19',200),('DoctrineMigrations\\Version20201203123615','2020-12-03 14:36:20',131),('DoctrineMigrations\\Version20201203123842','2020-12-03 14:38:45',48),('DoctrineMigrations\\Version20201203131141','2020-12-03 15:11:59',104),('DoctrineMigrations\\Version20201203131921','2020-12-03 15:19:34',115),('DoctrineMigrations\\Version20201203132243','2020-12-03 15:22:46',193),('DoctrineMigrations\\Version20201203170144','2020-12-03 19:01:48',68),('DoctrineMigrations\\Version20201203172235','2020-12-03 19:22:37',183),('DoctrineMigrations\\Version20201204100134','2020-12-04 12:02:03',124),('DoctrineMigrations\\Version20201207133509','2020-12-07 15:35:16',86);
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
  `date` date NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coordinates` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `join_limit` int(11) NOT NULL,
  `community_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3BAE0AA7876C4DDA` (`organizer_id`),
  KEY `IDX_3BAE0AA7FDA7B0BF` (`community_id`),
  CONSTRAINT `FK_3BAE0AA7876C4DDA` FOREIGN KEY (`organizer_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_3BAE0AA7FDA7B0BF` FOREIGN KEY (`community_id`) REFERENCES `community` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
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
  `last_login` date DEFAULT NULL,
  `date_reg` date DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` int(11) NOT NULL,
  `authy_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649444F97DD` (`phone`),
  UNIQUE KEY `UNIQ_8D93D6497BA2F5EB` (`api_token`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (3,'+380954335836','[\"123\"]','2b18b7d84cbbeda84e54efdcdb12bc0a','Andrey','2020-12-07',NULL,NULL,38,319002116),(4,'+380964237770','[\"123\"]',NULL,NULL,NULL,NULL,NULL,38,318694721),(5,'+380990981202','[\"terst\", \"zalupa\"]',NULL,'loh',NULL,NULL,NULL,38,319002283);
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

-- Dump completed on 2020-12-07 15:59:27
