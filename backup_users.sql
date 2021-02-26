-- MySQL dump 10.13  Distrib 5.7.33, for Linux (x86_64)
--
-- Host: localhost    Database: tapmatch
-- ------------------------------------------------------
-- Server version	5.7.33-0ubuntu0.18.04.1

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
) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (100,'+380964237770','[]','983200e7786e402340640d0d71270b61','Vladislav','2021-02-12 12:35:00',NULL,'/assets/img/avatar/7fc781913aa64a97c99b6ad05d295131.jpg',38,318694721,1,'78fe29ab-8e68-423f-84da-ad119dde9570','Europe/Zaporozhye'),(102,'+381669166153','[]',NULL,NULL,NULL,NULL,NULL,381,349792998,0,NULL,NULL),(104,'+491623254739','[]',NULL,NULL,NULL,NULL,NULL,49,322178882,0,NULL,NULL),(105,'+4901623254739','[]','af9be07aa39ffca362d8b816fe72fd68','Oscar','2021-02-11 13:10:00',NULL,'/assets/img/avatar/8fa9c5d83d512bcd074a1354441dca82.jpg',49,325154486,1,'928ad578-6fd3-4d67-aeff-35f4813c84fa','Europe/Amsterdam'),(106,'+4915146720339','[]','bf91611bad335519cd22eb9d06cd7cca','Ilai','2021-02-23 19:20:00',NULL,'/assets/img/avatar/c041864bbf78288cf2d10536f7f40f22.jpg',49,318605207,1,'535dfb57-b2c5-4b7d-b650-6e47a50ee725','Europe/Amsterdam'),(111,'+79771278438','[]',NULL,NULL,NULL,NULL,NULL,7,329728299,0,NULL,NULL),(120,'+375292103568','[]','3cdc47ebf8df3f4467288752403a6290','Egor','2021-02-18 14:12:00',NULL,'/assets/img/avatar/575eda11cba3e95375c26efd870776ac.jpg',375,321801469,1,'80e2d97e-563b-4fb8-b7c5-7b85cb7e31ac','Europe/Minsk'),(124,'+79771278435','[]','ae1fdfb180431154d754f45049654f3c','bgb','2021-02-15 08:02:00',NULL,'/assets/img/avatar/87a21878ca297b64e0896f694ba40f06.jpg',7,321240702,1,'59874afa-56dd-45f7-b48f-d8f03b034a12','Europe/Belgrade'),(125,'+375292795088','[]','62423cd115537368764a43c963ff22ad','Savememorn','2021-02-12 12:03:00',NULL,'/assets/img/avatar/d7a05b293b8949455bdbab1fd0cb229c.jpg',375,328142898,1,'f6bb7720-bba6-4192-b261-ce78464169c7','Europe/Minsk'),(126,'+375292795028','[]','36aba35b1bf0e4f55cdd2dc2a27f2ee7','Boba','2021-02-12 12:28:00',NULL,'/assets/img/avatar/b6c060f26b8c2750282627337891a9cb.jpg',375,328721746,1,'5c48f827-2eaf-4f3e-8668-b1afe1711883','Europe/Minsk'),(127,'+447307066640','[]','32fd69b4547e17d64c7992beb0e4873f','Ezra','2021-02-12 12:28:00',NULL,'/assets/img/avatar/0d529b0a79f82c242961a57cc8845e8e.jpg',44,322181824,1,'ece25d88-8b34-42ca-93d4-56de8de18804','Europe/London'),(128,'+447832737334','[]','7f7f916165c48579b058c8b3d4debfb0','Alain','2021-02-12 12:50:00',NULL,'/assets/img/avatar/087dec921275d88ce6a06df68c8f1d35.jpg',44,350315777,1,'a7b68c63-adf3-42d1-a87e-283737a78d02','Europe/London'),(129,'+491713583788','[]',NULL,NULL,NULL,NULL,NULL,49,350317159,0,NULL,NULL),(130,'+359886556327','[]','c803358260322073b0c19c30958b7e11','Denis','2021-02-12 13:22:00',NULL,'/assets/img/avatar/7f877fef33d279ab4c40378c175ee6c1.jpg',359,350325316,1,'6ce421f0-dc2b-475e-8150-7824fa68550a','Europe/Sofia'),(131,'+421903440021','[]','138e78dde73ecbda3305bd92362f56da','Vero','2021-02-12 13:30:00',NULL,'/assets/img/avatar/18956e8b755f03fcd39affcd9e31f84a.jpg',421,350328292,1,'e3df1534-1709-4347-9e71-5d811c75e684','Europe/London'),(132,'+491754188739','[]',NULL,NULL,NULL,NULL,NULL,49,350330389,0,NULL,NULL),(133,'+359887550262','[]','13cfe6942f2ddc48020f2196e29e6ff5','Georgi Z','2021-02-12 13:48:00',NULL,'/assets/img/avatar/47928958cfda08a0db272e6007c0d6cc.jpg',359,350334596,1,'13ad4314-d9f0-434f-a00b-8125de99eb69','Europe/Sofia'),(134,'+491721884508','[]',NULL,NULL,NULL,NULL,NULL,49,350336322,0,NULL,NULL),(135,'+4901713583788','[]','d470fa24d82b3502bf650a852091e31f','John da louz','2021-02-12 14:07:00',NULL,'/assets/img/avatar/b0392f136a93d02434bae3d0f7748fe9.jpg',49,350341132,1,'80a9ba66-3670-42eb-8c79-b4e1b91fb3d5','Europe/Berlin'),(136,'+491714944466','[]',NULL,NULL,NULL,NULL,NULL,49,350424399,0,NULL,NULL),(137,'+491627722440','[]',NULL,NULL,NULL,NULL,NULL,49,341978261,0,NULL,NULL),(138,'+4915122398381','[]','48cb75239f9a7722a80db13720adf962','Daniele','2021-02-13 12:29:00',NULL,NULL,49,350840786,0,'92094535-f453-4eb5-8afa-dca74c91ffd2','Europe/Rome'),(139,'+447538582787','[]','be487732e0a1fb8bcda7a9ad943cc2d9','Sam','2021-02-14 20:21:00',NULL,'/assets/img/avatar/b7eb480a772aafbd55bc76bd46f22d5e.jpg',44,351329326,1,'13c2fa0b-a4d6-4c54-a618-0c79e888081a','Europe/London'),(140,'+33651268094','[]','02f25c12da3a1c526fc8830e61985830','Aurelie','2021-02-15 12:03:00',NULL,'/assets/img/avatar/a5efe776e00fd04e969aba0ed271e9c7.jpg',33,351662225,1,'f058040c-8f99-4c4b-8612-5dbad4f40e82','Europe/Amsterdam'),(141,'+34722341712','[]','5a0fb7214b496c496768d5f926af56e8','Marc Oliveau','2021-02-15 12:23:00',NULL,'/assets/img/avatar/c5bc64a1541d9bd173a4d83f34ae5820.jpg',34,351667670,1,'f71e44e0-8dea-4d82-b5ce-b68133af3352','Europe/Amsterdam'),(142,'+4915167856830','[]','711f076d31d5edc15b3029db5952ccc5','Leon ','2021-02-17 09:11:00',NULL,'/assets/img/avatar/ce008f0331dfd3a0953c6fa3efcf621c.jpg',49,322148476,1,'839750ce-2c4e-47f0-ad0c-fcd8c3bdaacd','Europe/Amsterdam'),(143,'+491713561515','[]',NULL,NULL,NULL,NULL,NULL,49,351749405,0,NULL,NULL),(144,'+310685223541','[]','80703f43a433e755e94096459237cd05','Alix','2021-02-15 17:22:00',NULL,'/assets/img/avatar/3cef6642666d6b72b1d032372d7bd5c8.jpg',31,351776698,1,'55e0fd41-3847-4fbb-9598-7cbf06bfbad1','Europe/Amsterdam'),(145,'+310629193091','[]','6cefee68a43f9fdb621cff0beec7f7f0','Kayna','2021-02-15 17:59:00',NULL,'/assets/img/avatar/983569446c11a2ca707be58e14b78106.jpg',31,351791919,1,'004af882-5a21-45a1-a664-62e7769eacd7','Europe/Amsterdam'),(146,'+31621385597','[]',NULL,NULL,NULL,NULL,NULL,31,351811380,0,NULL,NULL),(147,'+491724333534','[]',NULL,NULL,NULL,NULL,NULL,49,351876947,0,NULL,NULL),(148,'+491726406401','[]',NULL,NULL,NULL,NULL,NULL,49,351880223,0,NULL,NULL),(149,'+491735121241','[]',NULL,NULL,NULL,NULL,NULL,49,352086642,0,NULL,NULL),(150,'+4901622904636','[]','20b36b36073360f178fe524e18108cfb','Ben','2021-02-16 11:26:00',NULL,'/assets/img/avatar/7da6509e8e4ba58f02aad1942b3b60b0.jpg',49,342088891,1,'929123e2-9474-4550-9d64-236a4924405f','Europe/Berlin'),(151,'+4901726406401','[]','273a671a6fdee0d4938a7d71ec1346b7','Gil','2021-02-16 12:10:00',NULL,'/assets/img/avatar/3ea53e37fa5aec87cbf60c2053014bd0.jpg',49,352119288,1,'c3dcdb72-5aff-4c46-822b-7011d58e94a3','Europe/Berlin'),(152,'+31625294747','[]',NULL,NULL,NULL,NULL,NULL,31,352122155,0,NULL,NULL),(153,'+31625297474','[]','d4ccd1a85b5b915ada65fc90527aeac6','Vicky','2021-02-16 12:20:00',NULL,'/assets/img/avatar/79a658e9adcf19e5812dd5d4461ea2d8.jpg',31,352122264,1,'0edb2b7a-f237-49bc-a2b9-53b9808b000c','Europe/Amsterdam'),(154,'+32460225254','[]',NULL,NULL,NULL,NULL,NULL,32,104967214,0,NULL,NULL),(155,'+972552603210','[]','fa6c0f08376535ccf49a3904733d2a66','chcy','2021-02-16 14:16:00',NULL,'/assets/img/avatar/00a0fb934d7ad25cd977a045003e2995.jpg',972,320340760,1,'ca5fd3e1-249b-4424-943a-6bdf7ce62956','Europe/Belgrade'),(156,'+79211679665','[]',NULL,NULL,NULL,NULL,NULL,7,338302176,0,NULL,NULL),(157,'+66953961043','[]','46c96766fb7b25a99776688258fe9373','gfh','2021-02-16 14:19:00',NULL,'/assets/img/avatar/c45ba6d964018c8a7a1624273a45d741.jpg',66,342311822,1,'83acc939-876c-4152-89af-8b45bf3bd46e','Europe/Belgrade'),(158,'+31639054123','[]','cb63e6746e6283d496e2399ea1f7fd4f','Emma','2021-02-16 19:48:00',NULL,'/assets/img/avatar/4073ce83adc33232ab4df4a66f2f58bc.jpg',31,322092101,1,'5fbe2dc4-6433-4b48-b41f-606ae1225c52','Europe/Amsterdam'),(159,'+4901735121241','[]','48a571f052ea9a344a85c56cc81d3b3e','Nic','2021-02-16 22:34:00',NULL,'/assets/img/avatar/cfe92c6174207cccd55af0c630239b77.jpg',49,352436465,1,'838697cd-6c59-4662-b63c-deefd2d76d6b','Europe/London'),(160,'+4901724333534','[]','d1e746a4674969b390e1fa3e3dc37cd1','Sagadat','2021-02-16 22:25:00',NULL,'/assets/img/avatar/60c48e5f5f9f22bb5727bc93b661ae65.jpg',49,352436518,1,'15316268-7a10-4b54-b16c-73e8ae9cb454','Europe/Berlin'),(161,'+31611333320','[]','ba97c7c98851c25fd42641022454948c','Boris','2021-02-17 21:22:00',NULL,'/assets/img/avatar/ecabde2595b4ce9623fcc93a70d8935d.jpg',31,352981399,1,'d78ffea5-8ff0-4b0b-89b3-74358bf42466','Europe/Amsterdam'),(162,'+31637138460','[]','a3645881ad17030fc182767eae6e68c8','Oscar','2021-02-17 21:30:00',NULL,'/assets/img/avatar/7bdc00dbd41957a60b28ea2950ffaa61.jpg',31,352991053,1,'71fc1dd5-1fb1-48ae-9999-637227862187','Europe/Amsterdam'),(163,'+37064648888','[]','d7e2f519c37ae7d3df610e0aa5361451','One','2021-02-17 22:36:00',NULL,'/assets/img/avatar/81cab8df0413f8fac46760d21cfe87dd.jpg',370,353024434,0,'dd843608-3ae8-430b-9120-c13feb8af5b6','Europe/Amsterdam'),(164,'+31643907242','[]','70fbc67ef633d6501bac58dff2e52182','Mohamed','2021-02-18 18:05:00',NULL,'/assets/img/avatar/b804830126aa300003f511d9582fea98.jpg',31,353432246,1,'f9d9992d-147f-4e1b-bc76-0fbfaa22ecb0','Europe/Amsterdam'),(165,'+4901713561515','[]','3f18ea1ba0db4fcd8816dc7413cccc9f','Nicholas','2021-02-19 09:30:00',NULL,'/assets/img/avatar/95a2bca359f2bff841cf7c3d7c5ebd22.jpg',49,353864930,1,'dc3e13a5-fc7b-4b83-9d8e-7998fc79e2bb','Europe/Berlin'),(166,'+4915150000054','[]','b89829f2348015cc7db62459fcfaf837','Mira','2021-02-19 17:42:00',NULL,'/assets/img/avatar/388a6f9b10e7213eab11a0ec5fd346de.jpg',49,354042125,1,'c7425af8-f99c-4e48-b48c-bf6d47af6ca7','Europe/Amsterdam'),(167,'+4917661698117','[]','5f36a185947fc106e3c123b5fedee30c','Angelito','2021-02-19 18:21:00',NULL,'/assets/img/avatar/8918a7be2f41553652983625baa65a1d.jpg',49,354060234,1,'ed14758e-537a-4668-808b-6205ff5b73a0','Europe/Amsterdam'),(168,'+4901754188739','[]','d91661a060f62cb86e0e50425218e286','N','2021-02-20 12:19:00',NULL,'/assets/img/avatar/5dbeee5dc418288ad9d08110dc22bcb3.jpg',49,354388122,1,'9e95a691-71ba-4d54-834f-b9e4f9506372','Europe/Berlin'),(169,'+491727727477','[]',NULL,NULL,NULL,NULL,NULL,49,355058848,0,NULL,NULL),(170,'+490172772747','[]',NULL,NULL,NULL,NULL,NULL,49,355276698,0,NULL,NULL),(171,'+4901727727477','[]','ce071e20a3c9f7779a766f7e4b6e6dff','Carl','2021-02-22 12:18:00',NULL,'/assets/img/avatar/b3982472751342a47f1f24e2a07334b9.jpg',49,355276768,1,'30a6e1d2-c32f-4f50-a04e-def7509cf4d0','Europe/Berlin'),(172,'+48722717428','[]',NULL,NULL,NULL,NULL,NULL,48,321352961,0,NULL,NULL),(173,'+77789490683','[]','b63a9c83a65117eb201092a2ecde7c0a','xhxh','2021-02-23 17:26:00',NULL,'/assets/img/avatar/d85b1f622261559ff251efe2742f4ffc.jpg',7,355425312,1,'83acc939-876c-4152-89af-8b45bf3bd46e','Europe/Belgrade'),(178,'+66886231091','[]','bd9177e3f4b3db3b854402c0843fc27b','jvkcj','2021-02-23 16:12:00',NULL,'/assets/img/avatar/a9242b2aadc19bb1b7a66cfe6caff776.jpg',66,343012484,1,'0430bb71-68ed-4e2c-90ae-68ced4667ee3','Europe/Belgrade'),(179,'+381669166453','[]','cde81ffbc47e62ab735c335a7d568e0c','hgv','2021-02-25 10:14:00',NULL,'/assets/img/avatar/edc03f9054de64cffbf835c598369efb.jpg',381,318732413,1,'','Europe/Belgrade'),(180,'+77789490686','[]','fd3e818f4e7d0c92081eebd86b596984','ccc','2021-02-25 10:08:00',NULL,'/assets/img/avatar/7abc74d9a70908232f9de9174cfe72d2.jpg',7,354349327,1,'83acc939-876c-4152-89af-8b45bf3bd46e','Europe/Belgrade'),(181,'+919462893729','[]','b1fcbbd52fd8d98d6fb238f5efffbfbb','Ashish','2021-02-25 12:03:00',NULL,'/assets/img/avatar/6264f8de9f8f141d588229308e2ead9f.jpg',91,356942117,1,'33967338-4037-4bf0-98c0-ce5b0b5605c1','Asia/Kolkata'),(182,'+447726171611','[]','afe9488847810d435b853fe70a20feff','Pankaj','2021-02-25 12:05:00',NULL,'/assets/img/avatar/fa879f3cfdea84970dedb5fa02cd2a7f.jpg',44,356942656,1,'0bb89843-1585-4d5b-8050-4fb93812477c','Europe/London'),(183,'+31625343866','[]','4a1d9cd849ba9747c283c546ef31f4ea','Paula','2021-02-25 21:38:00',NULL,'/assets/img/avatar/d2bef243ed154ecf2035ce6d583a5b32.jpg',31,357210792,1,'1055b573-d125-48ba-8b5f-b0074849d47c','Europe/Amsterdam'),(184,'+919695222121','[]',NULL,NULL,NULL,NULL,NULL,91,357363767,0,NULL,NULL),(186,'+919694321011','[]','dd3bb703a7ab0c0edf9a02f90adbaa27','Gdfgdfg','2021-02-26 10:52:00',NULL,'/assets/img/avatar/52c312cecf1e404d44bf075a6531506e.jpg',91,357363833,1,'ed408bed-bf8e-4757-9f01-a8dc46a9104f','Asia/Kolkata');
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

-- Dump completed on 2021-02-26 11:51:43
