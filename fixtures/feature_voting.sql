--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `votes_up` bigint(20) DEFAULT 0,
  `votes_down` bigint(20) DEFAULT 0,
  `votes_total` bigint(20) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_visit_at` timestamp NULL DEFAULT NULL,
  `last_visit_at` timestamp NULL DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `amount_staff` bigint(20) DEFAULT NULL,
  `monthly_sales` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash` (`hash`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `feature_categories`
--

DROP TABLE IF EXISTS `feature_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feature_categories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feature_categories`
--

LOCK TABLES `feature_categories` WRITE;
/*!40000 ALTER TABLE `feature_categories` DISABLE KEYS */;
INSERT INTO `feature_categories` VALUES (2,'Mobile App','2022-11-09 07:54:52','2022-11-09 07:54:52'),(7,'Geräteverwaltung','2022-10-20 05:24:55','2022-11-30 09:42:37'),(8,'Abwesenheitsplaner','2022-10-20 05:25:46',NULL),(9,'GPS Anbindung','2022-10-20 05:27:14',NULL),(39,'Projektverwaltung','2022-11-11 05:05:12',NULL),(57,'Pflege','2022-12-15 05:05:53','2022-12-15 05:05:53'),(58,'Allgemeines','2022-12-15 08:25:20','2022-12-15 08:25:20');
/*!40000 ALTER TABLE `feature_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feature_votings`
--

DROP TABLE IF EXISTS `feature_votings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feature_votings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `feature_id` bigint(20) DEFAULT NULL,
  `customer_id` bigint(20) DEFAULT NULL,
  `value` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `comment` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `features`
--

DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `features` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `feature_category_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `votes_up` bigint(20) DEFAULT 0,
  `votes_down` bigint(20) DEFAULT 0,
  `votes_total` bigint(20) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `voting_ends_at` timestamp NULL DEFAULT NULL,
  `last_visit_at` timestamp NULL DEFAULT NULL,
  `score` bigint(20) unsigned NOT NULL DEFAULT 0,
  `comments` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$cBycCLtc7JWRRNgBz2ypS.X/ikC.vk0BobeD1InxcynkYnKGAZsrW');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
