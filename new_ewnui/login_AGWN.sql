-- MySQL dump 10.13  Distrib 5.5.34, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: login_AGWN
-- ------------------------------------------------------
-- Server version	5.5.34-0ubuntu0.12.04.1

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
-- Table structure for table `logged_User`
--

DROP TABLE IF EXISTS `logged_User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logged_User` (
  `username` varchar(255) NOT NULL,
  `tokenId` varchar(32) NOT NULL,
  `mysalt` bigint(20) NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`username`,`tokenId`,`mysalt`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logged_User`
--

LOCK TABLES `logged_User` WRITE;
/*!40000 ALTER TABLE `logged_User` DISABLE KEYS */;
INSERT INTO `logged_User` VALUES ('riccardo.delgratta@gmail.com','0731f32bcb327c55c1c447c96b85a05f',1389103878,'2014-01-07 14:11:17'),('riccardo.delgratta@gmail.com','1de924d4bf178029285f41de8f8576c7',1389093402,'2014-01-07 11:16:42'),('riccardo.delgratta@gmail.com','2b0afd4830d8290210521b1189a3adbd',1389178810,'2014-01-08 11:00:09'),('riccardo.delgratta@gmail.com','2f5144be4b326dd77bd3cdd943bbcb8a',1388676645,'2014-01-02 15:30:46'),('riccardo.delgratta@gmail.com','56fc81ec9005b19811ec429ec55c94c7',1389268173,'2014-01-09 11:49:33'),('riccardo.delgratta@gmail.com','78a16f0bc0d89cf962cbb59f4b41ca01',1389104988,'2014-01-07 14:29:48'),('riccardo.delgratta@gmail.com','7c009cd5895784925b79e5d808652395',1389095265,'2014-01-07 11:47:44'),('riccardo.delgratta@gmail.com','80f1d7a8680802c9d4fb48921f309b86',1389191404,'2014-01-08 14:30:03'),('riccardo.delgratta@gmail.com','83c0a4f188fde7709e7b0f07db64a4a3',1389177991,'2014-01-08 10:46:30'),('riccardo.delgratta@gmail.com','8c49b38e4e1a8916e144901c7e9aa621',1388678858,'2014-01-02 16:07:37'),('riccardo.delgratta@gmail.com','8fd0203647b137eed863896fcb874ce6',1389177793,'2014-01-08 10:43:13'),('riccardo.delgratta@gmail.com','a4f27a8e7a9b82445ed38a22f139cee4',1389360483,'2014-01-10 13:28:03'),('riccardo.delgratta@gmail.com','ab83d6873f1f5305a5b46d60d1839308',1388682654,'2014-01-02 17:10:54'),('riccardo.delgratta@gmail.com','b1b2ee35bba7cafae97e777927e9bec0',1389177108,'2014-01-08 10:31:48'),('riccardo.delgratta@gmail.com','b67e0d18043b6543f283798a098bddbd',1388682230,'2014-01-02 17:03:49'),('riccardo.delgratta@gmail.com','d4f6855502ec77114136ed7ce66ecf28',1389109635,'2014-01-07 15:47:15'),('riccardo.delgratta@gmail.com','d82d433b1225fb08995d1e60d52ca5e2',1389190292,'2014-01-08 14:11:32'),('riccardo.delgratta@gmail.com','df7a6ac854e1ce9c50eb2a1f787199de',1389179361,'2014-01-08 11:09:20'),('riccardo.delgratta@gmail.com','e62871ee7ff7fba1061a78409088a87f',1389095369,'2014-01-07 11:49:28'),('riccardo.delgratta@gmail.com','e7158ed8f936ef188e7b850db6bb9f8f',1389095336,'2014-01-07 11:48:55'),('riccardo.delgratta@gmail.com','efff4cd00028fe96f461fe10b4a91b3e',1389267751,'2014-01-09 11:42:31'),('riccardo.delgratta@gmail.com','fa5452685bbc830eececb2d37b77f9b3',1389094179,'2014-01-07 11:29:39'),('riccardo.delgratta@gmail.com','fc5671378c3f5d2776a938e32f643bec',1389343692,'2014-01-10 08:48:11');
/*!40000 ALTER TABLE `logged_User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_Activity`
--

DROP TABLE IF EXISTS `user_Activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_Activity` (
  `act_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lang` char(3) NOT NULL,
  `word` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `pos` varchar(3) NOT NULL,
  `sense` bigint(20) NOT NULL,
  `rule` varchar(255) NOT NULL,
  `synsetid` bigint(20) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `value` int(11) NOT NULL,
  `mapping_lang` char(3) NOT NULL,
  `mapping_pos` varchar(3) NOT NULL,
  `mapping_synsetid` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `done` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`act_id`),
  UNIQUE KEY `unique_index` (`lang`,`word`,`pos`,`sense`,`synsetid`,`username`),
  KEY `word` (`word`,`pos`,`sense`),
  KEY `idx_word` (`word`),
  KEY `idx_pos` (`pos`),
  KEY `idx_syn` (`synsetid`),
  KEY `user_idx` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_Activity`
--

LOCK TABLES `user_Activity` WRITE;
/*!40000 ALTER TABLE `user_Activity` DISABLE KEYS */;
INSERT INTO `user_Activity` VALUES (93,'grc','Ï€Î¿Î»ÎµÎ¼Î¯Î¶Ï‰','V',2,'',2001100801125,'Validate',0,'eng','',100801125,'riccardo.delgratta@gmail.com','2014-01-10 11:33:18'),(94,'grc','πόλεμος','N',2,'',1001100801125,'Validate',2,'eng','',100801125,'riccardo.delgratta@gmail.com','2014-01-10 11:34:46'),(95,'grc','δάις','N',1,'',1001100801125,'Validate',2,'eng','',100801125,'riccardo.delgratta@gmail.com','2014-01-10 11:34:46');
/*!40000 ALTER TABLE `user_Activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_grants`
--

DROP TABLE IF EXISTS `user_grants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_grants` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(65) COLLATE utf8_bin NOT NULL,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `target_language` varchar(3) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_grants`
--

LOCK TABLES `user_grants` WRITE;
/*!40000 ALTER TABLE `user_grants` DISABLE KEYS */;
INSERT INTO `user_grants` VALUES (1,'Riccardo Del Gratta','riccardo.delgratta@gmail.com','grc'),(2,'Harry Diakoff','harry.diakoff@gmail.com','grc'),(3,'Federico','federico.boschetti.73@gmail.com','grc'),(4,'Ouafae','ouafaenahli@gmail.com','grc'),(5,'Monica','monica.monachini@gmail.com','grc'),(6,'Yuri','yuri.bizzoni@gmail.com','grc'),(7,'Marion',' marionlame@gmail.com','grc'),(8,'Anas Fahad Khan','anasfkhan81@gmail.com','grc'),(9,'Francescesca Frontini','francescafrontini@gmail.com','grc'),(10,'Gregory Crane','gregory.crane@tufts.edu','grc'),(11,'Neven Jovanovic','neven.jovanovic@ffzg.hr','grc'),(17,'Harry Diakoff','harry.diakoff@gmail.com','lat'),(18,'Federico','federico.boschetti.73@gmail.com','lat'),(19,'Ouafae','ouafaenahli@gmail.com','lat'),(20,'Monica','monica.monachini@gmail.com','lat'),(21,'Yuri','yuri.bizzoni@gmail.com','lat'),(22,'Marion',' marionlame@gmail.com','lat'),(23,'Anas Fahad Khan','anasfkhan81@gmail.com','lat'),(24,'Francescesca Frontini','francescafrontini@gmail.com','lat'),(25,'Gregory Crane','gregory.crane@tufts.edu','lat'),(26,'Neven Jovanovic','neven.jovanovic@ffzg.hr','lat');
/*!40000 ALTER TABLE `user_grants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_synsets_activity`
--

DROP TABLE IF EXISTS `user_synsets_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_synsets_activity` (
  `act_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `synsetid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `pos` char(3) NOT NULL DEFAULT '',
  `lang` char(3) NOT NULL,
  `lexdomainid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `definition` mediumtext,
  `mapped_pos` varchar(3) NOT NULL,
  `mapped_synset` bigint(20) NOT NULL,
  `mapped_lang` char(3) NOT NULL,
  `user` varchar(255) NOT NULL DEFAULT '',
  `activity` varchar(50) NOT NULL,
  `done` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`act_id`),
  UNIQUE KEY `unique_idx` (`synsetid`,`pos`,`user`),
  KEY `user_idx` (`user`),
  KEY `mappedSnIdx` (`mapped_synset`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_synsets_activity`
--

LOCK TABLES `user_synsets_activity` WRITE;
/*!40000 ALTER TABLE `user_synsets_activity` DISABLE KEYS */;
INSERT INTO `user_synsets_activity` VALUES (1,2001100846515,'V','grc',0,'sexual activities (often including sexual intercourse) between two peoplebfdbfdbdfb','n',100846515,'eng','riccardo.delgratta@gmail.com','mgp','2014-01-10 13:38:42');
/*!40000 ALTER TABLE `user_synsets_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_words_activity`
--

DROP TABLE IF EXISTS `user_words_activity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_words_activity` (
  `act_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `lang` char(3) NOT NULL,
  `word` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `pos` varchar(3) NOT NULL,
  `sense` bigint(20) NOT NULL,
  `rule` varchar(255) NOT NULL,
  `synsetid` bigint(20) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `value` int(11) NOT NULL,
  `mapping_lang` char(3) NOT NULL,
  `mapping_pos` varchar(3) NOT NULL,
  `mapping_synsetid` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `done` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`act_id`),
  UNIQUE KEY `unique_index` (`lang`,`word`,`pos`,`sense`,`synsetid`,`username`),
  KEY `word` (`word`,`pos`,`sense`),
  KEY `idx_word` (`word`),
  KEY `idx_pos` (`pos`),
  KEY `idx_syn` (`synsetid`),
  KEY `user_idx` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_words_activity`
--

LOCK TABLES `user_words_activity` WRITE;
/*!40000 ALTER TABLE `user_words_activity` DISABLE KEYS */;
INSERT INTO `user_words_activity` VALUES (1,'grc','ἀγαπάω','V',4,'',2001107543288,'Validate',0,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:18:19'),(2,'grc','φιλοσωματέω','V',4,'',2001107543288,'Validate',1,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:18:19'),(3,'grc','φιλέω','V',4,'',2001107543288,'Validate',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:18:19'),(4,'grc','διαγαπάω','V',4,'',2001107543288,'Validate',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:18:19'),(5,'grc','ἐράω','V',6,'',2001107543288,'Validate',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:18:19'),(6,'grc','στέργω','V',4,'',2001107543288,'Validate',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:18:19'),(7,'grc','ἔραμαι','V',4,'No Rule',2001107543288,'Validate',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:18:19'),(29,'grc','cvcvcv','V',1389360495,'Inf. as N',2001107543288,'Add',1,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:29:01'),(30,'grc','ἀγάπη','N',4,'',1001107543288,'Validate',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:31:21'),(31,'grc','στέργηθρον','N',4,'',1001107543288,'Validate',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:31:21'),(32,'grc','ἄρμα','N',13,'',1001107543288,'Validate',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:31:21'),(33,'grc','φιλότης','N',5,'',1001107543288,'Validate',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:31:21'),(34,'grc','ἔρος','N',6,'',1001107543288,'Validate',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:31:21'),(35,'grc','ἵμερος','N',6,'',1001107543288,'Validate',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:31:21'),(36,'grc','ἔρως','N',4,'',1001107543288,'Validate',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:31:21'),(37,'grc','ἔρασις','N',4,'',1001107543288,'Validate',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:31:21'),(38,'grc','στοργή','N',4,'',1001107543288,'Validate',1,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:31:21'),(39,'grc','μανάκιν','N',1389360642,'',1001107543288,'Add',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:31:21'),(40,'grc','σέρις','N',1389360643,'',1001107543288,'Add',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:31:21'),(41,'grc','βράβυλον','N',1389360645,'',1001107543288,'Add',2,'eng','n',107543288,'riccardo.delgratta@gmail.com','2014-01-10 13:31:21'),(42,'grc','φιλοζωέω','V',1,'No Rule',2001100846515,'Validate',2,'eng','n',100846515,'riccardo.delgratta@gmail.com','2014-01-10 13:33:02'),(43,'grc','ἀγαπάω','V',2,'No Rule',2001100846515,'Validate',2,'eng','n',100846515,'riccardo.delgratta@gmail.com','2014-01-10 13:33:02'),(44,'grc','φιλοσωματέω','V',2,'No Rule',2001100846515,'Validate',2,'eng','n',100846515,'riccardo.delgratta@gmail.com','2014-01-10 13:33:02'),(45,'grc','φιλέω','V',2,'No Rule',2001100846515,'Validate',2,'eng','n',100846515,'riccardo.delgratta@gmail.com','2014-01-10 13:33:02'),(46,'grc','διαγαπάω','V',2,'No Rule',2001100846515,'Validate',2,'eng','n',100846515,'riccardo.delgratta@gmail.com','2014-01-10 13:33:02'),(47,'grc','ἐράω','V',3,'No Rule',2001100846515,'Validate',2,'eng','n',100846515,'riccardo.delgratta@gmail.com','2014-01-10 13:33:02'),(48,'grc','στέργω','V',2,'No Rule',2001100846515,'Validate',2,'eng','n',100846515,'riccardo.delgratta@gmail.com','2014-01-10 13:33:02'),(49,'grc','ἔραμαι','V',2,'No Rule',2001100846515,'Validate',2,'eng','n',100846515,'riccardo.delgratta@gmail.com','2014-01-10 13:33:02');
/*!40000 ALTER TABLE `user_words_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(65) COLLATE utf8_bin NOT NULL,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nickn_idx` (`nickname`),
  UNIQUE KEY `mail_idx` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Riccardo Del Gratta','riccardo.delgratta@gmail.com','e0313327e0dd7d4f91c212ecd20d4d06'),(2,'Harry Diakoff','harry.diakoff@gmail.com','e0313327e0dd7d4f91c212ecd20d4d06'),(3,'Federico','federico.boschetti.73@gmail.com','e0313327e0dd7d4f91c212ecd20d4d06'),(4,'Ouafae','ouafaenahli@gmail.com','e0313327e0dd7d4f91c212ecd20d4d06'),(5,'Monica','monica.monachini@gmail.com','e0313327e0dd7d4f91c212ecd20d4d06'),(6,'Yuri','yuri.bizzoni@gmail.com','e0313327e0dd7d4f91c212ecd20d4d06'),(7,'Marion',' marionlame@gmail.com','e0313327e0dd7d4f91c212ecd20d4d06'),(8,'Anas Fahad Khan','anasfkhan81@gmail.com','e0313327e0dd7d4f91c212ecd20d4d06'),(9,'Francescesca Frontini','francescafrontini@gmail.com','e0313327e0dd7d4f91c212ecd20d4d06'),(10,'Gregory Crane','gregory.crane@tufts.edu','e0313327e0dd7d4f91c212ecd20d4d06'),(11,'Neven Jovanovic','neven.jovanovic@ffzg.hr','e0313327e0dd7d4f91c212ecd20d4d06');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-01-10 14:41:47
