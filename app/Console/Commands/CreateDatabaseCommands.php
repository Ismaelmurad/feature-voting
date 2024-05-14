<?php

use App\Services\Database\Connection;
use App\Services\Database\QueryBuilder;

define('ROOT_DIR', dirname(__DIR__, 3));

require(ROOT_DIR . '/vendor/autoload.php');
$dbConfig = require ROOT_DIR . '/config/database.php';

$db = new QueryBuilder(
    Connection::make($dbConfig)
);

$dbName = 'feature_voting';

$db->raw('
    CREATE DATABASE IF NOT EXISTS `feature_voting` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
');

$db->raw(
    '
     CREATE TABLE IF NOT EXISTS `feature_voting`.`customers` (
      `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
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
    ) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4;
'
);

$db->raw('
    CREATE TABLE IF NOT EXISTS `feature_voting`.`feature_categories` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    FULLTEXT KEY `name` (`name`)
    ) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4;
');

$db->raw('
    CREATE TABLE `feature_votings` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `feature_id` bigint(20) DEFAULT NULL,
    `customer_id` bigint(20) DEFAULT NULL,
    `value` tinyint(1) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `comment` text DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4;
');

$db->raw('
    CREATE TABLE IF NOT EXISTS `feature_voting`.`features` (
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
    PRIMARY KEY (`id`),
    FULLTEXT KEY `name` (`name`)
    ) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4;
');

$db->raw('
    CREATE TABLE IF NOT EXISTS `feature_voting`.`users` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `username` varchar(255) NOT NULL,
    `hash` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
');



