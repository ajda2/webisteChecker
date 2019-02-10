-- Adminer 4.7.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `website`;
CREATE TABLE `website` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_failing_test` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `last_check_at` datetime DEFAULT NULL,
  `response_code` int(11) DEFAULT NULL,
  `response_time` float DEFAULT NULL,
  `tests_data` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `last_check_index` (`last_check_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2019-02-10 18:50:10