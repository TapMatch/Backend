-- MySQL dump 10.13  Distrib 5.7.33, for Linux (x86_64)
--
-- Host: localhost    Database: tapmatch
-- ------------------------------------------------------
-- Server version	5.7.31-0ubuntu0.18.04.1

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `community`
--

LOCK TABLES `community` WRITE;
/*!40000 ALTER TABLE `community` DISABLE KEYS */;
INSERT INTO `community` VALUES (3,'UvA','000007','Amsterdam',0),(6,'UCL','006996','London',0),(10,'TUM','120021','Munich',0),(15,'Open World',NULL,'',1);
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
INSERT INTO `community_user` VALUES (3,3),(3,11),(3,17),(3,19),(3,22),(3,28),(3,29),(3,71),(3,83),(3,92),(6,11),(6,22),(6,28),(6,92),(6,93),(10,6),(10,11),(10,22),(10,28),(10,43),(10,44),(10,46),(10,71),(10,92),(15,6),(15,10),(15,11),(15,18),(15,19),(15,22),(15,28),(15,29),(15,33),(15,34),(15,38),(15,39),(15,43),(15,46),(15,48),(15,52),(15,53),(15,63),(15,69),(15,71),(15,74),(15,75),(15,76),(15,77),(15,78),(15,79),(15,80),(15,83),(15,92),(15,93),(15,95),(15,96);
/*!40000 ALTER TABLE `community_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cron`
--

DROP TABLE IF EXISTS `cron`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cron`
--

LOCK TABLES `cron` WRITE;
/*!40000 ALTER TABLE `cron` DISABLE KEYS */;
INSERT INTO `cron` VALUES (1,'2021-01-06 16:32:01'),(2,'2021-01-06 16:33:01'),(3,'2021-01-06 16:34:02'),(4,'2021-01-06 16:35:01'),(5,'2021-01-06 16:36:01'),(6,'2021-01-06 16:37:01'),(7,'2021-01-06 16:38:02'),(8,'2021-01-06 16:39:01'),(9,'2021-01-06 16:40:01'),(10,'2021-01-06 16:41:02'),(11,'2021-01-06 16:42:01'),(12,'2021-01-06 16:43:01'),(13,'2021-01-06 16:44:01'),(14,'2021-01-06 16:45:01'),(15,'2021-01-06 16:46:01');
/*!40000 ALTER TABLE `cron` ENABLE KEYS */;
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
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20201124150212','2020-12-01 18:24:20',31),('DoctrineMigrations\\Version20201124152726','2020-12-01 18:24:20',35),('DoctrineMigrations\\Version20201125150409','2020-12-01 18:24:20',26),('DoctrineMigrations\\Version20201125151954','2020-12-01 18:24:20',27),('DoctrineMigrations\\Version20201130152741','2020-12-01 18:24:20',25),('DoctrineMigrations\\Version20201203082053','2020-12-03 10:20:59',101),('DoctrineMigrations\\Version20201203112144','2020-12-03 13:22:08',105),('DoctrineMigrations\\Version20201203120750','2020-12-03 14:08:34',41),('DoctrineMigrations\\Version20201203121456','2020-12-03 14:15:02',84),('DoctrineMigrations\\Version20201203122129','2020-12-03 14:21:34',32),('DoctrineMigrations\\Version20201203122515','2020-12-03 14:25:19',200),('DoctrineMigrations\\Version20201203123615','2020-12-03 14:36:20',131),('DoctrineMigrations\\Version20201203123842','2020-12-03 14:38:45',48),('DoctrineMigrations\\Version20201203131141','2020-12-03 15:11:59',104),('DoctrineMigrations\\Version20201203131921','2020-12-03 15:19:34',115),('DoctrineMigrations\\Version20201203132243','2020-12-03 15:22:46',193),('DoctrineMigrations\\Version20201203170144','2020-12-03 19:01:48',68),('DoctrineMigrations\\Version20201203172235','2020-12-03 19:22:37',183),('DoctrineMigrations\\Version20201204100134','2020-12-04 12:02:03',124),('DoctrineMigrations\\Version20201207133509','2020-12-07 15:35:16',86),('DoctrineMigrations\\Version20201208101516','2020-12-08 12:15:33',93),('DoctrineMigrations\\Version20201208101529','2020-12-08 15:46:46',34),('DoctrineMigrations\\Version20201208124516','2020-12-08 15:46:46',134),('DoctrineMigrations\\Version20201209170220','2020-12-09 19:02:31',93),('DoctrineMigrations\\Version20201211145730','2020-12-25 14:36:09',225),('DoctrineMigrations\\Version20201212145246','2020-12-25 14:36:09',69),('DoctrineMigrations\\Version20201225092326','2020-12-25 14:36:09',260),('DoctrineMigrations\\Version20201225093349','2020-12-25 14:36:09',17),('DoctrineMigrations\\Version20201225093824','2020-12-25 14:36:10',257),('DoctrineMigrations\\Version20201225093856','2020-12-25 14:36:10',141),('DoctrineMigrations\\Version20210106072937','2021-01-06 16:09:00',1083),('DoctrineMigrations\\Version20210106132608','2021-01-06 16:31:25',124),('DoctrineMigrations\\Version20210127155649','2021-01-27 18:56:54',1173);
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
  `organizer_id` int(11) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=259 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event`
--

LOCK TABLES `event` WRITE;
/*!40000 ALTER TABLE `event` DISABLE KEYS */;
INSERT INTO `event` VALUES (41,43,'gdhdhdhhdhdhdhdh','2022-05-12 13:07:00','Moscow, Russia','a:2:{s:9:\"longitude\";d:37.6173;s:8:\"latitude\";d:55.755826;}','84+&+_+₽+_',1,15),(42,34,'races','2021-11-01 14:00:00','Minsk, Belarus','a:2:{s:9:\"longitude\";d:27.60569851845503;s:8:\"latitude\";d:53.94269470162468;}','ahvdh',10,15),(86,63,'hdhdh','2021-08-01 14:30:00','vulica Pimiena Pančanki, Minsk, Belarus','a:2:{s:9:\"longitude\";d:27.4153733;s:8:\"latitude\";d:53.8885939;}','vdbdbdb',24,15),(89,63,'vga','2021-11-01 15:18:00','vulica Kižavatava, Minsk, Belarus','a:2:{s:9:\"longitude\";d:27.5363894;s:8:\"latitude\";d:53.8502486;}','gahhs\n',2,15),(90,63,'mele','2021-11-01 15:25:00','vulica Mielieža 1, Minsk, Belarus','a:2:{s:9:\"longitude\";d:27.6013316;s:8:\"latitude\";d:53.93952700000001;}','jdjfhfhfhf',2,15),(157,83,'Fub','2021-02-14 16:07:00','1510 O\'Farrell St, San Francisco, CA 94115, USA','a:4:{s:8:\"latitude\";d:37.78415744505745;s:9:\"longitude\";d:-122.43004437536001;s:13:\"latitudeDelta\";d:0.015;s:14:\"longitudeDelta\";d:0.0121;}','Fyv',2,15),(253,22,'Dinner','2021-02-10 04:18:00','Aiblingerstraße 8, 80639 München, Germany','a:4:{s:8:\"latitude\";d:48.15500229910919;s:9:\"longitude\";d:11.524172561781679;s:13:\"latitudeDelta\";d:0.015;s:14:\"longitudeDelta\";d:0.0121;}','Bmbring boozs',19,6);
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
INSERT INTO `event_user` VALUES (41,43),(42,34),(42,43),(42,52),(42,71),(86,43),(86,63),(86,69),(86,93),(89,63),(90,63),(90,71),(157,83),(253,11),(253,22);
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
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649444F97DD` (`phone`),
  UNIQUE KEY `UNIQ_8D93D6497BA2F5EB` (`api_token`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (3,'+380954335836','[\"123\"]','2b18b7d84cbbeda84e54efdcdb12bc0a','Andrey','2020-12-07 00:00:00',NULL,'7e13450187ab09f2aec376c70859c5c8.png',38,319002116,0,NULL,NULL),(6,'+380964237770','[\"admin\"]','60ce63da4343247bd1fe6a02e2e0eade','Vladislav','2021-01-27 19:51:00',NULL,'/assets/img/avatar/c2dde63738a71fa39216bf0874a79409.jpg',38,318694721,1,'78fe29ab-8e68-423f-84da-ad119dde9570','Europe/Zaporozhye'),(8,'+79771278135','[]',NULL,NULL,NULL,NULL,NULL,7,321741387,0,NULL,NULL),(9,'+375259586720','[]',NULL,NULL,NULL,NULL,NULL,375,242850442,0,NULL,NULL),(10,'+79520378969','[]','f01f59ed646d1615999f972ccdfca0a5','Alexey12@8_+_+_;\"!!\"(_(_)_)_(_!$!+_+_+_+','2020-12-08 00:00:00',NULL,'84b851ba93d061a30164a18f93ffe1d6.jpg',7,321759748,1,NULL,NULL),(11,'+375292103568','[]','3403401c1ca9b39272ad8c88ad5e4d8f','Egor','2021-02-08 20:00:00',NULL,'/assets/img/avatar/5a535c31634ad28ed0125cbc7dee6ae4.jpg',375,321801469,1,'0903c1a1-79db-427a-98d4-16f4e5857a04','Europe/Minsk'),(12,'+79913915013','[]',NULL,NULL,NULL,NULL,NULL,7,321813866,0,NULL,NULL),(13,'+79657298870','[]',NULL,NULL,NULL,NULL,NULL,7,321816112,0,NULL,NULL),(14,'+79966927580','[]','df83cfb38955da67be12845a7c53ca02','Alexey19&(&;₽:&:@:9:&;8;9;9;&&;&:9;02&,₽','2020-12-08 00:00:00',NULL,'aa533de1babc93ac2960c7090a7e58e9.jpg',7,321816601,1,NULL,NULL),(15,'+79771228435','[]',NULL,NULL,NULL,NULL,NULL,7,322026502,0,NULL,'Europe/Zaporozhye'),(16,'+491516712764','[]',NULL,NULL,NULL,NULL,NULL,49,322079084,0,NULL,NULL),(17,'+31639054123','[]','9e2d120a4eb875530a7f157b1020f102','Leon','2020-12-09 00:00:00',NULL,'e676fda194524be6bfbd2af5f08d45d0.jpg',31,322092101,1,NULL,NULL),(18,'+4915167127644','[]','79f9daae61d1c063acc80be18b8518fd','Egor','2020-12-09 00:00:00',NULL,'b3570de79891c3d5a65c3fd6fcfda80f.jpg',49,322105763,1,NULL,NULL),(19,'+4915167856830','[]','038c9795d1584346047ee429774bf1e9','Leon','2021-01-03 15:59:29',NULL,'/assets/img/avatar/164a25b38ddce5e682d1bce7d946c675.jpg',49,322148476,1,'839750ce-2c4e-47f0-ad0c-fcd8c3bdaacd','Europe/Amsterdam'),(20,'+4915176126744','[]',NULL,NULL,NULL,NULL,NULL,49,322168368,0,NULL,NULL),(21,'+491623254739','[]',NULL,NULL,NULL,NULL,NULL,49,322178882,0,NULL,NULL),(22,'+447307066640','[]','2a7ba357d5362e48e24e5c0c6f15b800','Ezra','2021-01-28 22:39:00',NULL,'/assets/img/avatar/4e1acdc12c663fbfe5196e6f028f8602.jpg',44,322181824,1,'08de563f-7b39-4dc4-8584-032e01a6196c','Europe/London'),(23,'+380991634918','[]',NULL,NULL,NULL,NULL,NULL,380,322222935,0,NULL,NULL),(24,'+79771278453','[]',NULL,NULL,NULL,NULL,NULL,7,324279490,0,NULL,NULL),(26,'+7669166453','[]',NULL,NULL,NULL,NULL,NULL,7,324706272,0,NULL,NULL),(27,'+4915146720336','[]',NULL,NULL,NULL,NULL,NULL,49,325125639,0,NULL,NULL),(28,'+4915146720339','[]','885cb01205c49a0835bdfc1122d46ea5','Ilai','2021-01-19 21:39:32',NULL,'/assets/img/avatar/1a449432e5248505308e89d3b6f8c17c.jpg',49,318605207,1,'d624e2fd-9336-4090-8b45-761a79c94d11','Europe/Amsterdam'),(29,'+4901623254739','[]','84db450c7765b693c2bdfbfbe1c6b1e1','Oscar','2021-02-03 17:02:00',NULL,'/assets/img/avatar/594d75fc9836def92b16862c895bb84c.jpg',49,325154486,1,'42d7d2d4-f574-4a64-98fd-ee115f96d729','Europe/Amsterdam'),(30,'+381669466453','[]',NULL,NULL,NULL,NULL,NULL,381,327457963,0,NULL,NULL),(31,'+3819771278435','[]',NULL,NULL,NULL,NULL,NULL,381,327464053,0,NULL,NULL),(32,'+376856','[]',NULL,NULL,NULL,NULL,NULL,376,327515683,0,NULL,NULL),(33,'+375292795088','[]','7a0810e0b8a38837da40b4875a4311c6','Tester','2021-02-02 19:05:00',NULL,'/assets/img/avatar/f5fe08dc32545cef7f0c21f6cc0a1c7d.jpg',375,328142898,1,'ba42029b-52fd-4fc9-87fe-9fe035a2782d','Europe/Minsk'),(34,'+79915101427','[]','ce7bc4f808cbea48cbb1277facef8d1f','Egor','2020-12-27 15:54:32',NULL,'/assets/img/avatar/e6073425d5056a358ed520559c1d990b.jpg',7,328006441,1,NULL,NULL),(35,'+37622222','[]',NULL,NULL,NULL,NULL,NULL,376,328302359,0,NULL,NULL),(36,'+79771248435','[]',NULL,NULL,NULL,NULL,NULL,7,321375038,0,NULL,NULL),(37,'+376292795088','[]',NULL,NULL,NULL,NULL,NULL,376,328416180,0,NULL,NULL),(38,'+79253732813','[]','c73b7653a691ff587dbcbb0530edf196','Bdhdh','2020-12-29 13:24:19',NULL,'/assets/img/avatar/e3f8a26702574ecb090248da65ef0f55.jpg',7,327240990,0,NULL,NULL),(39,'+79657296362','[]','406804269231a1f21c6da48272f59470','Gdhdhd','2020-12-29 13:34:04',NULL,'/assets/img/avatar/661a43b5a4cd09b91032652111c52e5d.jpg',7,328426671,0,NULL,NULL),(40,'+79652296233','[]',NULL,NULL,NULL,NULL,NULL,7,326484367,0,NULL,NULL),(41,'+375257489371','[]',NULL,NULL,NULL,NULL,NULL,375,242850343,0,NULL,NULL),(42,'+380975277326','[]',NULL,NULL,NULL,NULL,NULL,380,327928125,0,NULL,NULL),(43,'+375292795028','[]','e880899ea6f83c55a7d2f05b9b3cb310','Bcb','2021-02-02 19:22:00',NULL,'/assets/img/avatar/6fe5029e0fe8542fd92b08ea65c5161d.jpg',375,328721746,1,'fc3247d9-ee64-4a0b-805f-bc798e7d95f1','Europe/Minsk'),(44,'+79777707035','[]','8f5f038ec6300619c099351f7b4b65e1','gjcvj ','2020-12-28 17:26:09',NULL,'/assets/img/avatar/4512b185ef164fcafa2f00707f2f1c24.jpg',7,328775186,0,NULL,NULL),(46,'+79055643748','[]','cf909d00d07e4ba2c5b186d2d8f6a11d','mcmckfkf','2020-12-28 21:01:59',NULL,'/assets/img/avatar/1d90bbf62e6b2c7926ca55fca86dbca2.jpg',7,328865029,1,NULL,NULL),(47,'+79916635354','[]',NULL,NULL,NULL,NULL,NULL,7,329131079,0,NULL,NULL),(48,'+79652308638','[]','6a9968515a08a97fb92bd969c5905e69','Go','2020-12-29 13:26:59',NULL,'/assets/img/avatar/35bd96f4d887cec031bf63f0bb63f25c.jpg',7,327387745,1,NULL,NULL),(49,'+79645139748','[]','a1fee322edcb1195a61d822a43664de9','Bxbxbx','2020-12-30 19:05:35',NULL,'/assets/img/avatar/37d511529afc42d364a480ee38efe53a.jpg',7,329614513,0,NULL,NULL),(50,'+79035483374','[]','e7f589e0e050b9af37fedce9274057fa','Tester','2020-12-30 19:13:50',NULL,'/assets/img/avatar/92d8b5ba0a3f566edf881b7941520b51.jpg',7,329684565,0,NULL,NULL),(51,'+79771278438','[]',NULL,NULL,NULL,NULL,NULL,7,329728299,0,NULL,NULL),(52,'+79035482021','[]','45f11cf844bd382dd3f87f17c534c470','Retrik','2021-01-04 17:22:02',NULL,'/assets/img/avatar/e8fffdb0972099bf467743b7cef53de3.jpg',7,329739436,1,NULL,NULL),(53,'+12513090696','[]','eade284daf19c4cac667ed1f5a4153e9','Hop','2021-01-19 14:25:19',NULL,'/assets/img/avatar/eb4921185556a3b27edf0f3a042088b2.jpg',1,329740083,1,'ba42029b-52fd-4fc9-87fe-9fe035a2782d',NULL),(54,'+380969422043','[]','7819e8d2f47bc403e2b7506a977579b6','Gdhdhd','2020-12-30 21:17:34',NULL,'/assets/img/avatar/451b4472d147a37271476ebcee7494db.jpg',380,329740990,0,NULL,NULL),(57,'+79060556556','[]',NULL,NULL,NULL,NULL,NULL,7,331695050,0,NULL,NULL),(62,'+79910361077','[]',NULL,NULL,NULL,NULL,NULL,7,333204654,0,NULL,NULL),(63,'+79910360832','[]','52034a12eabf63572bf3e39c9b6da942','Got','2021-01-08 12:36:49',NULL,'/assets/img/avatar/8e35277de178ed844592b4abe7ef4b92.jpg',7,333721443,1,'ba42029b-52fd-4fc9-87fe-9fe035a2782d',NULL),(64,'+79771278132','[]',NULL,NULL,NULL,NULL,NULL,7,335487541,0,NULL,NULL),(65,'+380990981202','[]',NULL,NULL,NULL,NULL,NULL,38,319002283,0,NULL,NULL),(69,'+79680336366','[]','0017a9f39de23e4af82d95c68359841b','Hok','2021-01-15 21:00:00',NULL,'/assets/img/avatar/bd0b89b581b61164d6ed7b8b3cee0900.jpg',7,336468639,1,'a882d142-33ce-4923-9471-48fe1cd7451d',NULL),(71,'+381669166453','[]','029f320ec2f82e13e4ad6ae6e2d42f49','hdhd','2021-02-08 15:00:00',NULL,'/assets/img/avatar/7bed3d0b1d4f18698813326a4343c8e9.jpg',381,318732413,1,'83acc939-876c-4152-89af-8b45bf3bd46e','Europe/Moscow'),(72,'+37527950','[]',NULL,NULL,NULL,NULL,NULL,375,337441185,0,NULL,NULL),(73,'+46736748027','[]',NULL,NULL,NULL,NULL,NULL,46,337443899,0,NULL,NULL),(74,'+46736784027','[]','b9da3e21053d2f68961fdd1cfee9f7ac','Bil','2021-01-16 13:41:56',NULL,'/assets/img/avatar/9253b1eda1af7589d98cd375816d5b66.jpg',46,337444005,1,'eb7d2a9c-4b82-4515-902e-92b5d85789f7',NULL),(75,'+34632359444','[]','90a205cae211ffffb9328a2447f9bbbd','Hop','2021-01-16 13:44:21',NULL,'/assets/img/avatar/13b2a20431889981ab4929618406c2a7.jpg',34,337444435,1,'92a500b0-14ad-4412-b686-e890aa88f6ec',NULL),(76,'+380961041191','[]','3f78198bb1a9b652c855ffc27fae5c8b','Bil','2021-01-16 13:46:27',NULL,'/assets/img/avatar/53627e7eb4c325557044868c3b7dba11.jpg',380,335958210,1,'4fd5cb00-1dde-4e6f-941d-6abf462b449f',NULL),(77,'+380687967663','[]','b11833a0a87f1435a99729a9d4416f15','Fill','2021-01-19 14:27:39',NULL,'/assets/img/avatar/18a867537bbb891d5bc377578d8e890e.jpg',380,338523946,1,'ba42029b-52fd-4fc9-87fe-9fe035a2782d',NULL),(78,'+79094146654','[]','59c5c3620a4809a16585ba48db056dbb','Bol','2021-01-19 14:29:52',NULL,'/assets/img/avatar/e31dce273bf7177e26e7b0338ff74f89.jpg',7,338524354,1,'ba42029b-52fd-4fc9-87fe-9fe035a2782d',NULL),(79,'+79915101995','[]','72c24503d5e1e3ae3efa0377ecb65632','Cvc','2021-01-19 14:31:21',NULL,'/assets/img/avatar/0836718010987873b25454eef32b8f31.jpg',7,338524762,1,'ba42029b-52fd-4fc9-87fe-9fe035a2782d',NULL),(80,'+79094100328','[]','272c43eeb816b7abda4bcf7ec9d93b7b','Hh','2021-01-19 14:32:46',NULL,'/assets/img/avatar/55575e3c3cea61276c93c56bbe7c8343.jpg',7,338525157,1,'ba42029b-52fd-4fc9-87fe-9fe035a2782d',NULL),(81,'+79771278735','[]',NULL,NULL,NULL,NULL,NULL,7,339103360,0,NULL,NULL),(82,'+14084766514','[]',NULL,NULL,NULL,NULL,NULL,1,25797976,1,NULL,NULL),(83,'+71234567890','[\"super_admin\"]','10f1e06eefeae2b42ff7fce571dd53a2','jeob','2021-01-27 15:22:43',NULL,'/assets/img/avatar/7bebeea02fdbcdb44a7f4a78b85113e6.jpg',1,1,1,'f89dbd7f-71d9-4eaa-92a7-b52e82b3215d',NULL),(92,'+79771278435','[]','fcb9e1950ba4f2562f964b34cfd132af','арлд','2021-02-08 19:15:00',NULL,'/assets/img/avatar/a439837fc13b2af1351e3934ee303c26.jpg',7,321240702,1,'dbbf9d0e-3467-4c25-8f93-86ac57700db2','Europe/Moscow'),(93,'+79093060499','[]','9f05d4e66cea94578630d7946d9dedf2','Hh','2021-01-26 19:26:42',NULL,'/assets/img/avatar/370c48388b16b0ae047242034dd32d10.jpg',7,339600458,1,'4b9a8f78-e97b-494e-83b3-1e8e96605385',NULL),(94,'+491627722440','[]',NULL,NULL,NULL,NULL,NULL,49,341978261,0,NULL,NULL),(95,'+4901622904636','[]','6cd937191aab3b7966e219bcfd984012','Ben','2021-01-27 19:07:00',NULL,'/assets/img/avatar/89c8125e0f214ecbcb0ffd65c0bb68eb.jpg',49,342088891,1,'929123e2-9474-4550-9d64-236a4924405f','Europe/Berlin'),(96,'+79683550450','[]','50aaae225a14f293302a891313228211','Tester','2021-01-28 17:08:00',NULL,'/assets/img/avatar/5f595dba74df03e779129e5541d1fbbd.jpg',7,339832800,1,'26bdda89-9941-4843-a555-a17e43afa093','Europe/Minsk'),(97,'+79645149346','[]',NULL,NULL,NULL,NULL,NULL,7,345088934,0,NULL,NULL),(98,'+16462863573','[]',NULL,NULL,NULL,NULL,NULL,1,339420161,0,NULL,NULL),(99,'+48792476077','[]',NULL,NULL,NULL,NULL,NULL,48,344652941,0,NULL,NULL);
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

-- Dump completed on 2021-02-09 15:46:29
