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


DROP TABLE IF EXISTS `website_test_result`;
CREATE TABLE `website_test_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_id` int(10) unsigned NOT NULL,
  `test_code` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_czech_ci NOT NULL,
  `is_success` tinyint(1) unsigned NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_website_test_result_website_idx` (`website_id`),
  CONSTRAINT `fk_website_test_result_website` FOREIGN KEY (`website_id`) REFERENCES `website` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2019-02-10 19:27:13