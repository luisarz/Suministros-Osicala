/*
 Navicat Premium Data Transfer

 Source Server         : LocalHost
 Source Server Type    : MySQL
 Source Server Version : 100428 (10.4.28-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : erp_dte

 Target Server Type    : MySQL
 Target Server Version : 100428 (10.4.28-MariaDB)
 File Encoding         : 65001

 Date: 11/10/2024 00:06:26
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for branches
-- ----------------------------
DROP TABLE IF EXISTS `branches`;
CREATE TABLE `branches`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_id` bigint UNSIGNED NOT NULL,
  `nit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nrc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `departamento_id` bigint UNSIGNED NOT NULL,
  `distrito_id` bigint UNSIGNED NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `economic_activity_id` bigint UNSIGNED NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `web` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prices_by_products` int NOT NULL DEFAULT 2,
  `logo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `branches_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `branches_departamento_id_foreign`(`departamento_id` ASC) USING BTREE,
  INDEX `branches_distrito_id_foreign`(`distrito_id` ASC) USING BTREE,
  INDEX `branches_economic_activity_id_foreign`(`economic_activity_id` ASC) USING BTREE,
  CONSTRAINT `branches_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `branches_departamento_id_foreign` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `branches_distrito_id_foreign` FOREIGN KEY (`distrito_id`) REFERENCES `distritos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `branches_economic_activity_id_foreign` FOREIGN KEY (`economic_activity_id`) REFERENCES `economic_activities` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of branches
-- ----------------------------

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache
-- ----------------------------
INSERT INTO `cache` VALUES ('356a192b7913b04c54574d18c28d46e6395428ab', 'i:2;', 1728624316);
INSERT INTO `cache` VALUES ('356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1728624316;', 1728624316);
INSERT INTO `cache` VALUES ('a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1728611782);
INSERT INTO `cache` VALUES ('a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1728611782;', 1728611782);
INSERT INTO `cache` VALUES ('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:138:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:9:\"view_role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:13:\"view_any_role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:11:\"create_role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:11:\"update_role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:11:\"delete_role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:15:\"delete_any_role\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:11:\"view_branch\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:15:\"view_any_branch\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:13:\"create_branch\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:13:\"update_branch\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:14:\"restore_branch\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:18:\"restore_any_branch\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:16:\"replicate_branch\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:14:\"reorder_branch\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:13:\"delete_branch\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:17:\"delete_any_branch\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:19:\"force_delete_branch\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:23:\"force_delete_any_branch\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:13:\"view_category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:17:\"view_any_category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:15:\"create_category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:15:\"update_category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:16:\"restore_category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:20:\"restore_any_category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:18:\"replicate_category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:16:\"reorder_category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:15:\"delete_category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:19:\"delete_any_category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:21:\"force_delete_category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:25:\"force_delete_any_category\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:12:\"view_company\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:16:\"view_any_company\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:32;a:3:{s:1:\"a\";i:33;s:1:\"b\";s:14:\"create_company\";s:1:\"c\";s:3:\"web\";}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:14:\"update_company\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:15:\"restore_company\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:19:\"restore_any_company\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:17:\"replicate_company\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:15:\"reorder_company\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:14:\"delete_company\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:18:\"delete_any_company\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:20:\"force_delete_company\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:24:\"force_delete_any_company\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:12:\"view_country\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:16:\"view_any_country\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:14:\"create_country\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:14:\"update_country\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:15:\"restore_country\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:19:\"restore_any_country\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:17:\"replicate_country\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:15:\"reorder_country\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:14:\"delete_country\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:51;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:18:\"delete_any_country\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:52;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:20:\"force_delete_country\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:53;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:24:\"force_delete_any_country\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:54;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:17:\"view_departamento\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:55;a:4:{s:1:\"a\";i:56;s:1:\"b\";s:21:\"view_any_departamento\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:56;a:4:{s:1:\"a\";i:57;s:1:\"b\";s:19:\"create_departamento\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:57;a:4:{s:1:\"a\";i:58;s:1:\"b\";s:19:\"update_departamento\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:58;a:4:{s:1:\"a\";i:59;s:1:\"b\";s:20:\"restore_departamento\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:59;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:24:\"restore_any_departamento\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:60;a:4:{s:1:\"a\";i:61;s:1:\"b\";s:22:\"replicate_departamento\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:61;a:4:{s:1:\"a\";i:62;s:1:\"b\";s:20:\"reorder_departamento\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:62;a:4:{s:1:\"a\";i:63;s:1:\"b\";s:19:\"delete_departamento\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:63;a:4:{s:1:\"a\";i:64;s:1:\"b\";s:23:\"delete_any_departamento\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:64;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:25:\"force_delete_departamento\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:65;a:4:{s:1:\"a\";i:66;s:1:\"b\";s:29:\"force_delete_any_departamento\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:66;a:4:{s:1:\"a\";i:67;s:1:\"b\";s:13:\"view_distrito\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:67;a:4:{s:1:\"a\";i:68;s:1:\"b\";s:17:\"view_any_distrito\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:68;a:4:{s:1:\"a\";i:69;s:1:\"b\";s:15:\"create_distrito\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:69;a:4:{s:1:\"a\";i:70;s:1:\"b\";s:15:\"update_distrito\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:70;a:4:{s:1:\"a\";i:71;s:1:\"b\";s:16:\"restore_distrito\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:71;a:4:{s:1:\"a\";i:72;s:1:\"b\";s:20:\"restore_any_distrito\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:72;a:4:{s:1:\"a\";i:73;s:1:\"b\";s:18:\"replicate_distrito\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:73;a:4:{s:1:\"a\";i:74;s:1:\"b\";s:16:\"reorder_distrito\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:74;a:4:{s:1:\"a\";i:75;s:1:\"b\";s:15:\"delete_distrito\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:75;a:4:{s:1:\"a\";i:76;s:1:\"b\";s:19:\"delete_any_distrito\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:76;a:4:{s:1:\"a\";i:77;s:1:\"b\";s:21:\"force_delete_distrito\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:77;a:4:{s:1:\"a\";i:78;s:1:\"b\";s:25:\"force_delete_any_distrito\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:78;a:4:{s:1:\"a\";i:79;s:1:\"b\";s:23:\"view_economic::activity\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:79;a:4:{s:1:\"a\";i:80;s:1:\"b\";s:27:\"view_any_economic::activity\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:80;a:4:{s:1:\"a\";i:81;s:1:\"b\";s:25:\"create_economic::activity\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:81;a:4:{s:1:\"a\";i:82;s:1:\"b\";s:25:\"update_economic::activity\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:82;a:4:{s:1:\"a\";i:83;s:1:\"b\";s:26:\"restore_economic::activity\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:83;a:4:{s:1:\"a\";i:84;s:1:\"b\";s:30:\"restore_any_economic::activity\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:84;a:4:{s:1:\"a\";i:85;s:1:\"b\";s:28:\"replicate_economic::activity\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:85;a:4:{s:1:\"a\";i:86;s:1:\"b\";s:26:\"reorder_economic::activity\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:86;a:4:{s:1:\"a\";i:87;s:1:\"b\";s:25:\"delete_economic::activity\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:87;a:4:{s:1:\"a\";i:88;s:1:\"b\";s:29:\"delete_any_economic::activity\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:88;a:4:{s:1:\"a\";i:89;s:1:\"b\";s:31:\"force_delete_economic::activity\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:89;a:4:{s:1:\"a\";i:90;s:1:\"b\";s:35:\"force_delete_any_economic::activity\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:90;a:4:{s:1:\"a\";i:91;s:1:\"b\";s:10:\"view_marca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:91;a:4:{s:1:\"a\";i:92;s:1:\"b\";s:14:\"view_any_marca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:92;a:4:{s:1:\"a\";i:93;s:1:\"b\";s:12:\"create_marca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:93;a:4:{s:1:\"a\";i:94;s:1:\"b\";s:12:\"update_marca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:94;a:4:{s:1:\"a\";i:95;s:1:\"b\";s:13:\"restore_marca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:95;a:4:{s:1:\"a\";i:96;s:1:\"b\";s:17:\"restore_any_marca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:96;a:4:{s:1:\"a\";i:97;s:1:\"b\";s:15:\"replicate_marca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:97;a:4:{s:1:\"a\";i:98;s:1:\"b\";s:13:\"reorder_marca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:98;a:4:{s:1:\"a\";i:99;s:1:\"b\";s:12:\"delete_marca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:99;a:4:{s:1:\"a\";i:100;s:1:\"b\";s:16:\"delete_any_marca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:100;a:4:{s:1:\"a\";i:101;s:1:\"b\";s:18:\"force_delete_marca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:101;a:4:{s:1:\"a\";i:102;s:1:\"b\";s:22:\"force_delete_any_marca\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:102;a:4:{s:1:\"a\";i:103;s:1:\"b\";s:12:\"view_tribute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:103;a:4:{s:1:\"a\";i:104;s:1:\"b\";s:16:\"view_any_tribute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:104;a:4:{s:1:\"a\";i:105;s:1:\"b\";s:14:\"create_tribute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:105;a:4:{s:1:\"a\";i:106;s:1:\"b\";s:14:\"update_tribute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:106;a:4:{s:1:\"a\";i:107;s:1:\"b\";s:15:\"restore_tribute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:107;a:4:{s:1:\"a\";i:108;s:1:\"b\";s:19:\"restore_any_tribute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:108;a:4:{s:1:\"a\";i:109;s:1:\"b\";s:17:\"replicate_tribute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:109;a:4:{s:1:\"a\";i:110;s:1:\"b\";s:15:\"reorder_tribute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:110;a:4:{s:1:\"a\";i:111;s:1:\"b\";s:14:\"delete_tribute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:111;a:4:{s:1:\"a\";i:112;s:1:\"b\";s:18:\"delete_any_tribute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:112;a:4:{s:1:\"a\";i:113;s:1:\"b\";s:20:\"force_delete_tribute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:113;a:4:{s:1:\"a\";i:114;s:1:\"b\";s:24:\"force_delete_any_tribute\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:114;a:4:{s:1:\"a\";i:115;s:1:\"b\";s:22:\"view_unit::measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:115;a:4:{s:1:\"a\";i:116;s:1:\"b\";s:26:\"view_any_unit::measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:116;a:4:{s:1:\"a\";i:117;s:1:\"b\";s:24:\"create_unit::measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:117;a:4:{s:1:\"a\";i:118;s:1:\"b\";s:24:\"update_unit::measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:118;a:4:{s:1:\"a\";i:119;s:1:\"b\";s:25:\"restore_unit::measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:119;a:4:{s:1:\"a\";i:120;s:1:\"b\";s:29:\"restore_any_unit::measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:120;a:4:{s:1:\"a\";i:121;s:1:\"b\";s:27:\"replicate_unit::measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:121;a:4:{s:1:\"a\";i:122;s:1:\"b\";s:25:\"reorder_unit::measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:122;a:4:{s:1:\"a\";i:123;s:1:\"b\";s:24:\"delete_unit::measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:123;a:4:{s:1:\"a\";i:124;s:1:\"b\";s:28:\"delete_any_unit::measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:124;a:4:{s:1:\"a\";i:125;s:1:\"b\";s:30:\"force_delete_unit::measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:125;a:4:{s:1:\"a\";i:126;s:1:\"b\";s:34:\"force_delete_any_unit::measurement\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:126;a:4:{s:1:\"a\";i:127;s:1:\"b\";s:9:\"view_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:127;a:4:{s:1:\"a\";i:128;s:1:\"b\";s:13:\"view_any_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:128;a:4:{s:1:\"a\";i:129;s:1:\"b\";s:11:\"create_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:129;a:4:{s:1:\"a\";i:130;s:1:\"b\";s:11:\"update_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:130;a:4:{s:1:\"a\";i:131;s:1:\"b\";s:12:\"restore_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:131;a:4:{s:1:\"a\";i:132;s:1:\"b\";s:16:\"restore_any_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:132;a:4:{s:1:\"a\";i:133;s:1:\"b\";s:14:\"replicate_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:133;a:4:{s:1:\"a\";i:134;s:1:\"b\";s:12:\"reorder_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:134;a:4:{s:1:\"a\";i:135;s:1:\"b\";s:11:\"delete_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:135;a:4:{s:1:\"a\";i:136;s:1:\"b\";s:15:\"delete_any_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:136;a:4:{s:1:\"a\";i:137;s:1:\"b\";s:17:\"force_delete_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:137;a:4:{s:1:\"a\";i:138;s:1:\"b\";s:21:\"force_delete_any_user\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:1:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super_admin\";s:1:\"c\";s:3:\"web\";}}}', 1728686076);

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint UNSIGNED NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `categories_parent_id_foreign`(`parent_id` ASC) USING BTREE,
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 52 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES (25, 'ACCESORIOS  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (26, 'ACEITES Y GRASAS  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (27, 'BALEROS  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (28, 'CLUCHT  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (29, 'TRANSMISION  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (30, 'MOTOR  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (31, 'DIFERENCIAL  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (32, 'DIRECCION  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (33, 'ENFRIAMIENTO  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (34, 'FERETERIA  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (35, 'FRENOS  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (36, 'HIDRAULICA  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (37, 'INJECCION  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (38, 'INYECTOR  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (39, 'LUBRICANTES, GRASAS, ADITIVOS Y PEGAMENTOS  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (40, 'MISELANEA  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (41, 'MOTOCICLETA  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (42, 'PIEZAS DE ENCENDIDO  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (43, 'PIEZAS ELECTRICAS  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (44, 'RACK END  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (45, 'RODAMIENTO  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (46, 'SELLO  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (47, 'STIHL  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (48, 'SUSPENCION  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (49, 'TORNILLERIA  ', NULL, 1, NULL, NULL);
INSERT INTO `categories` VALUES (50, 'ACEITES Y GRASAS  ', NULL, 1, NULL, NULL);

-- ----------------------------
-- Table structure for companies
-- ----------------------------
DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nrc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `economic_activity_id` bigint UNSIGNED NOT NULL,
  `country_id` bigint UNSIGNED NOT NULL,
  `departamento_id` bigint UNSIGNED NOT NULL,
  `distrito_id` bigint UNSIGNED NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `web` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `companies_economic_activity_foreign`(`economic_activity_id` ASC) USING BTREE,
  INDEX `companies_country_foreign`(`country_id` ASC) USING BTREE,
  INDEX `companies_departamento_id_foreign`(`departamento_id` ASC) USING BTREE,
  INDEX `companies_distrito_id_foreign`(`distrito_id` ASC) USING BTREE,
  CONSTRAINT `companies_country_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `companies_departamento_id_foreign` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `companies_distrito_id_foreign` FOREIGN KEY (`distrito_id`) REFERENCES `distritos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `companies_economic_activity_foreign` FOREIGN KEY (`economic_activity_id`) REFERENCES `economic_activities` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of companies
-- ----------------------------
INSERT INTO `companies` VALUES (1, 'Suministros de Morazan', '232345-0', '1314-120587-101-4', '79281878', '232', 'svcomputec@gmail.com', '\"configuracion\\/01J9W4CQSAZVG7SF7E9TY1QZ5V.jpg\"', 2, 1, 2, 3, '12', 'conexionesymas.sv', NULL, '2024-10-10 21:12:10', '2024-10-11 02:12:09');

-- ----------------------------
-- Table structure for countries
-- ----------------------------
DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of countries
-- ----------------------------
INSERT INTO `countries` VALUES (1, 'SV', 'Salvador', 1, '2024-10-10 20:56:28', '2024-10-10 20:56:28');

-- ----------------------------
-- Table structure for departamentos
-- ----------------------------
DROP TABLE IF EXISTS `departamentos`;
CREATE TABLE `departamentos`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of departamentos
-- ----------------------------
INSERT INTO `departamentos` VALUES (1, '00', 'Otros', 1, '2024-09-26 16:19:32', '2024-09-26 18:51:29');
INSERT INTO `departamentos` VALUES (2, '01', 'Ahuchapán', 1, '2024-09-26 16:19:38', '2024-09-26 18:52:05');
INSERT INTO `departamentos` VALUES (3, '02', 'Santa Ana', 1, '2024-09-26 16:19:49', '2024-09-26 18:52:18');
INSERT INTO `departamentos` VALUES (4, '03', 'Son Sonate', 1, '2024-09-26 18:50:22', '2024-09-26 18:52:31');
INSERT INTO `departamentos` VALUES (5, '04', 'Chalatenango', 1, '2024-09-26 18:50:28', '2024-09-26 18:52:45');
INSERT INTO `departamentos` VALUES (6, '05', 'La Libertad', 1, '2024-09-26 18:50:34', '2024-09-26 18:52:58');
INSERT INTO `departamentos` VALUES (7, '06', 'San Salvador', 1, '2024-09-26 18:53:05', '2024-09-26 18:53:17');
INSERT INTO `departamentos` VALUES (8, '13', 'Morazán', 1, '2024-10-11 02:20:27', '2024-10-11 02:20:27');

-- ----------------------------
-- Table structure for distritos
-- ----------------------------
DROP TABLE IF EXISTS `distritos`;
CREATE TABLE `distritos`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `departamento_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `distritos_departamento_foreign`(`departamento_id` ASC) USING BTREE,
  CONSTRAINT `distritos_ibfk_1` FOREIGN KEY (`departamento_id`) REFERENCES `departamentos` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of distritos
-- ----------------------------
INSERT INTO `distritos` VALUES (1, '00', 'Otro', 1, '2024-09-26 16:22:22', '2024-09-26 18:54:52');
INSERT INTO `distritos` VALUES (2, '13', 'Ahuchapán Norte', 2, '2024-09-26 16:26:25', '2024-09-26 18:55:11');
INSERT INTO `distritos` VALUES (3, '14', 'Ahuchapán Centro', 2, '2024-09-26 16:26:51', '2024-09-26 18:55:35');
INSERT INTO `distritos` VALUES (4, '23', 'San Miguel', 3, '2024-09-26 16:27:00', '2024-09-26 16:27:00');
INSERT INTO `distritos` VALUES (5, '14', 'Mena', 1, '2024-09-26 18:54:21', '2024-09-26 18:54:21');
INSERT INTO `distritos` VALUES (6, '14', 'Mena', 1, '2024-09-26 18:54:26', '2024-09-26 18:54:26');
INSERT INTO `distritos` VALUES (7, '14', 'Mena', 1, '2024-09-26 18:54:31', '2024-09-26 18:54:31');
INSERT INTO `distritos` VALUES (8, '15', 'Ahuchapán Sur', 2, '2024-09-26 18:55:51', '2024-09-26 18:56:05');
INSERT INTO `distritos` VALUES (9, '27', 'Morazán Norte', 8, '2024-10-11 02:21:08', '2024-10-11 02:21:37');
INSERT INTO `distritos` VALUES (10, '28', 'Morazán Sur', 8, '2024-10-11 02:21:15', '2024-10-11 02:21:29');

-- ----------------------------
-- Table structure for economic_activities
-- ----------------------------
DROP TABLE IF EXISTS `economic_activities`;
CREATE TABLE `economic_activities`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of economic_activities
-- ----------------------------
INSERT INTO `economic_activities` VALUES (1, '01111', 'CULTIVO DE CEREALES EXCEPTO ARROZ', '2024-10-10 20:52:30', '2024-10-10 20:52:30');
INSERT INTO `economic_activities` VALUES (2, '45301', 'Venta de partes, piezas y accesorios nuevos para vehículos automotores', '2024-10-11 02:11:50', '2024-10-11 02:11:50');

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int NULL DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of job_batches
-- ----------------------------

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED NULL DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jobs
-- ----------------------------

-- ----------------------------
-- Table structure for marcas
-- ----------------------------
DROP TABLE IF EXISTS `marcas`;
CREATE TABLE `marcas`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11048 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of marcas
-- ----------------------------
INSERT INTO `marcas` VALUES (49, 'OTROS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (50, 'LAFA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (51, 'MASTER                                            ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (52, 'PROPULSA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (53, 'ASAM  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (54, 'HYG                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (55, 'MULTI PARTS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (56, 'EIKO                                              ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (57, 'HUSHAN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (58, 'AUTO PARTS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (59, 'GENUINO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (60, 'RACING  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (61, 'CAR RADIO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (62, 'LUCID  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (63, 'CRAFTMAN                                          ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (64, 'BOSCH                                             ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (65, 'K-STAR  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (66, 'MIKELS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (67, 'SUN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (68, 'TRIDON  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (69, 'KDC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (70, 'KLY  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (72, 'MIRROR  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (73, 'THM  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (74, 'YTM  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (75, 'YUTO (SPARE)  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (76, 'FORCETEC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (77, 'JKT                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (78, 'JOHNSENS                                          ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (79, 'CHS                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (80, 'DTC                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (81, 'NEYN CHIN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (82, 'NOVITA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (83, 'GENUINE QUALITY                                   ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (84, 'HAOER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (85, 'HELP  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (86, 'SUPER HELP  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (87, 'BBS                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (88, 'SUKI JEP  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (89, 'CAR MATS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (90, 'AUTO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (91, 'ABRO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (92, 'GOOD RUBBER                                       ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (93, 'CKT  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (94, 'NARVA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (95, 'NITRO ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (96, 'TUY CERT  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (97, 'HIGHEST QUALITY  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (98, 'MEGA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (99, 'TYPE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (100, 'SHIMAKU  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (101, 'RU  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (102, 'EAGLEYE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (103, 'PHILIPS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (104, 'PH  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (105, 'NPC                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (106, 'ED                                                ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (107, 'FIRE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (108, 'F&F                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (109, 'DEPO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (110, 'TYC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (111, 'CASP  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (112, 'JUSTAR  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (113, 'AA MOTOR  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (114, 'FJ-TECH                                           ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (115, 'GTR500  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (116, 'NITRO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (117, 'DLAA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (118, 'GENERAL ELECTRIC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (119, 'HYPER-F  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (120, 'L&F  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (121, 'SPEEDMAX  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (122, 'STAA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (123, 'STANLEY  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (124, 'COBRA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (125, 'WAGNER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (126, 'GEL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (127, 'SUPER BRIGHT  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (128, 'TW  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (129, 'CERTIFIED  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (130, 'SEIWA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (131, 'SEIKEN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (132, 'JACK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (133, 'TRUPER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (134, 'PRESTONE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (135, 'SPIRAL JACK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (136, 'TOYO                                              ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (137, 'CK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (138, 'GUIDE WIN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (139, 'STIHL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (140, 'SWITCH  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (141, 'TRI-WIN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (142, 'MITSUBA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (143, 'SBC                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (144, 'DELCO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (145, 'NASAKI                                            ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (146, 'SANKEI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (147, 'SEIBERLING  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (148, 'LUMIACTION  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (149, 'MOTUL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (150, 'MAXX OIL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (151, 'ELF  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (152, 'TEXACO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (153, 'XTRA REV  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (154, 'VALVOLINE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (155, 'CASTROL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (156, 'RAYO                                              ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (157, 'TOTAL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (158, 'PEAK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (159, 'SHELL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (160, 'TRANSGEAR  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (161, 'QAP                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (162, 'CHB  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (163, 'FBJ  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (164, 'NACHI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (165, 'NATIONAL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (166, 'NSK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (167, 'SIA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (168, 'WTW  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (169, 'KOIKE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (170, 'WOS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (171, 'ASCO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (172, 'SOIKON                                            ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (173, 'FIC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (174, 'APC                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (175, 'CLEAR                                             ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (176, 'SQM  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (177, 'X2  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (178, 'YUHOLI                                            ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (179, 'MIYACO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (180, 'MOTOR                                             ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (181, 'FD  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (182, 'SAM  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (183, 'LUCAS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (184, 'NSC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (185, 'HSCO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (186, 'RKY  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (187, 'VARGA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (188, 'NAVCAR  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (189, 'SBK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (190, 'ORIENT                                            ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (191, 'CMB  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (192, 'MRK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (193, 'NSK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (194, 'NTN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (195, 'TIMKEN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (196, 'IVK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (197, 'NGK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (198, 'YUMI                                              ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (199, 'ETENEL Y NIPPAN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (200, 'EXEDY                                             ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (201, 'LUMBER                                            ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (202, 'ACDELCO                                           ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (203, 'DENCKERMANN                                       ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (204, 'SAMGTOS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (205, 'GST                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (206, 'HIGHFIL                                           ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (207, 'SUMWA                                             ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (208, 'KASUKI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (209, 'KIA MOTORS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (210, 'NGP  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (211, 'SECO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (212, 'IPRO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (213, 'OTROS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (214, 'POLIURETANO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (215, 'TEZUKA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (216, 'TRC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (217, 'RITCO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (218, 'CONTROL                                           ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (219, '5826', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (220, 'PREMIER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (221, 'CENTTEC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (222, 'TENNECO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (223, 'TOKICO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (224, '555', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (225, '777', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (226, 'STAR  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (227, 'AUTO NOVA                                         ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (228, 'SHIBUMI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (229, 'DIING  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (230, 'ONNURI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (231, 'BRAND  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (232, 'PARTS MALL                                        ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (233, 'CTR  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (234, 'TERADA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (235, 'SAFETY  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (236, 'ASAHI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (237, 'BAW  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (238, 'CARD-DEX  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (239, 'RBI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (240, 'ONNURI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (241, 'DAI-ICHI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (242, 'GMB  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (243, 'JSP  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (244, 'KURUMA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (245, 'NBW  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (246, 'NPW  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (247, 'PWP  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (248, 'JT                                                ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (249, 'T&T  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (250, 'MAX  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (251, 'BPS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (252, 'HT  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (253, 'THERMOSTAT  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (254, 'EAGLE                                             ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (255, 'TOOLCRAFT  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (256, 'TRAMONTINA PRO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (257, 'PRETUL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (258, 'TRAMONTINA MASTER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (259, 'PUMA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (260, 'HUNTER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (261, 'PERMATEX  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (262, 'VIKINGO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (263, 'ROTTER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (264, 'BGF  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (265, 'SKC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (266, 'NINGBO OUY  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (267, 'GISTORNE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (268, 'RKY  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (269, 'SIA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (270, 'TCIC                                              ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (271, 'AUTOCRAFT                                         ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (272, 'HI-LEX  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (273, 'NAVCAR  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (274, 'HYUNDAI                                           ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (275, 'TSK                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (276, 'TOYOTA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (277, 'JKK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (278, 'KANSAI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (279, 'KASHIMA                                           ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (281, 'TUV  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (282, 'VALEO                                             ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (283, 'FEDERAL MOGUL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (284, 'KTI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (285, 'INDYMETAL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (286, 'POINTER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (287, 'HLC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (288, 'FAIR VIEW  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (289, 'CARLSON  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (290, 'KOYO                                              ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (291, 'PERFORMANCE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (292, 'MAP  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (293, 'MARCA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (294, 'NIPPON  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (295, 'KYOSAN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (296, 'AUTOTEC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (297, 'FACET  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (298, 'DAEWHA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (299, 'B&B  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (300, 'MASTER WIRE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (301, 'TOSHIO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (303, 'SEIWA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (304, 'KEYSTER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (305, 'NAPCO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (306, 'CENTURI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (307, 'QUALITY  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (308, 'SUZUKI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (309, 'YEC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (310, 'YOKOZUNA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (311, 'BALDWIN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (312, 'ESPLUS                                            ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (313, 'FRAM  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (314, 'FUJITOYO                                          ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (315, 'I-PRO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (316, 'GEMINIS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (317, 'HIBARI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (318, 'HP                                                ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (319, 'GENUINE PARTS                                     ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (320, 'LYS                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (321, 'MD  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (322, 'NIPPAN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (323, 'OSK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (324, 'SASAKI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (325, 'TEC SERVICE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (326, 'TOP RACING  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (327, 'TOYO NIC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (328, 'UNITECH  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (329, 'WIX                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (330, 'ISUZU  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (331, 'PUROLATOR                                         ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (332, 'YAKO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (333, 'INTERFIL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (334, 'FLAG  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (335, 'ZEXEL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (336, 'DREIK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (337, 'CHAMPION  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (338, 'DENSO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (339, 'GOLDEN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (340, 'HKT  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (341, 'NGK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (342, 'GLOW PLUG  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (343, 'CHINLANG  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (344, 'FUELGAUGE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (345, 'SHIAN LONG  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (346, 'CYCLO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (347, 'MARVEL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (348, 'BARDAHL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (349, 'PRESTONE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (350, 'CRC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (351, 'FORMULA 1  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (352, 'NDK                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (353, 'FILCA                                             ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (354, 'BLACKS CLUBS                                      ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (355, 'KP                                                ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (356, 'SONAX                                             ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (357, 'LOCTITE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (358, 'PAYEN                                             ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (359, 'PRODIN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (360, 'SUPER HELP  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (361, 'FRESHENER                                         ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (362, 'WD-41  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (363, '3 EN 1  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (364, 'HOLTS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (365, 'POWER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (366, 'PENZOIL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (367, 'TOTAL ELF  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (368, 'GATES  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (369, 'AMGAUGE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (370, 'AG  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (371, 'DURA+  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (372, 'TOYKO                                             ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (373, '3M  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (374, 'AIDLITE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (375, 'RIKS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (376, 'JAPAN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (377, 'NEW STAR  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (379, 'MENPHIS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (380, 'NRP  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (381, 'ZQ  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (382, 'ICHIBAN                                           ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (383, 'CPI                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (384, 'CAMEL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (385, 'JRS  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (386, 'SANLG  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (387, 'SANLG MOTOR  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (388, 'KOSHIYO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (389, 'TOYO POWER                                        ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (390, 'VEE RUBBER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (391, 'KENDA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (392, 'SBK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (393, 'KK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (394, 'SBK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (395, 'PRO GAUGE                                         ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (396, 'SBK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (397, 'TEC                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (398, 'KGK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (399, 'MUSASHI                                           ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (400, 'PEVISA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (401, 'CADA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (402, 'ITE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (403, 'NP  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (404, 'STONE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (405, 'THG  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (406, 'TOWA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (407, 'BKB  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (408, 'NTN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (410, 'MATSUBA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (411, 'TRANS LINK                                        ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (412, 'OSK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (413, 'ZUIKO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (414, 'OSK  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (415, 'NAKAMOTO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (416, 'NPW  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (417, 'OPTIBELT  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (418, 'ROFAN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (419, 'SUPER BELT  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (420, 'FIRST SUPER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (421, 'MATSUBA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (422, 'BANDO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (423, 'DAYCO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (424, 'FUJU  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (425, 'ONNURI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (426, 'ELEMENT  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (427, 'NPPN                                              ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (428, 'TORCO  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (429, 'HSK                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (430, 'GONHER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (431, 'HASTING  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (432, 'JHF  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (433, 'KRALINATOR  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (434, 'LUBER FINER                                       ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (435, 'YAF                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (436, 'TOP GEAR  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (437, 'OSAKA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (438, 'TEC SERVICE  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (439, 'TECFIL  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (440, 'UNION  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (441, 'RECORD                                            ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (442, 'YUILFILTER  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (443, 'SWIC  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (444, 'NEM  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (445, 'SAMURAI  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (446, 'NISSAN  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (447, 'DIFORZA  ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (448, 'EBK                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1454, 'TIRE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1455, 'KOTO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1456, 'NBR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1457, 'KUBE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1459, 'AJUSA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1460, 'THO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1461, 'YSM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1462, 'AKURP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1463, 'NPC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1466, 'TOA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1467, 'RV', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1468, 'TENACITY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1470, 'I&R', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1471, 'NEW-ERA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1472, 'SEAP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1473, 'TEXUSA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1474, 'WAI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1475, 'TEXTRONIC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1476, 'POLLAK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1477, 'CAR SHOW', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1478, 'HM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1479, 'LTCY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1480, 'SEIRA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1481, 'GAUGE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1482, 'FARIA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1483, 'HELIG', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1484, 'NADAKAI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1485, 'GEN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1486, 'EXP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1487, 'UYUSTOLS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1488, 'HI-CORP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1489, 'MAUSER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1490, 'TAMA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1491, 'ECHLIN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1492, 'TRANSPO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1493, 'GENON', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1494, 'UNIPOINT', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1495, 'HKC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1496, 'AMERICAN LASSER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1497, 'CYON', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1498, 'VALLEX FORGE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1499, 'LARRY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1500, 'PNE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1501, 'TCH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1502, 'TICH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1503, 'ZNP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1504, 'FCC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1505, 'FUSI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1506, 'NCC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1507, 'YCC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1508, 'BEST QUAACITY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1509, 'ELECTRIC PARTS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1510, 'NILES', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1511, 'NC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1512, 'FKG', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1513, 'XZSY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1514, 'MBS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1515, 'DAVER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1516, 'NIOHIOKO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1517, 'PRECISION', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1518, 'TOGMS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1519, 'IQ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1520, 'HWC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1521, 'ECV', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1522, 'BOOTELLI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1523, 'NKM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1524, 'SM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1525, 'SUPITER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1526, 'MUSASHI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1527, 'CRI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1528, 'NQK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1529, 'HORIUCHI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1530, 'LYO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1532, 'TOKSEAL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1533, 'INR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1534, 'TCN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1535, 'IY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1536, 'TOMITA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1537, 'IRUMO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1538, 'YULIM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1539, 'ELJIN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1540, 'SH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1541, 'SHIH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1542, 'YOCOMITSO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1543, 'ASHIMORI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1544, 'SIM MAH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1545, 'SPECIALITY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1546, 'SIM MAH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1547, 'IZUMO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1548, 'GABRIEL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1549, 'KAYABA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1550, 'KYB', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1551, 'MONROE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1552, 'RANCHO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1553, 'WDKYB', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1554, 'NUVO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1555, 'UNICORN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1556, 'FR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1562, 'KING', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1563, 'TSW', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1564, 'FKC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1565, 'GLOBE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1566, 'HELMET', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1567, 'UNIVERSAL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1568, 'SUN-FLEX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1569, 'PEERD', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1570, 'TAKASHI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1571, 'PLASTIGOMA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1572, 'STP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1573, 'GW', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1574, 'OEM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1575, 'BENDIX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1576, 'YSK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1577, 'SIM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1578, 'AKURO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1579, 'CARSHOU', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1580, 'SELRA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1581, 'ENZO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1582, 'REGITAR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1583, 'FUJI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1584, 'INTERSTATE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1585, 'MEGA FORCE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1586, '4MOTION', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1587, 'H&T', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1588, 'LEOCH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1589, 'SUPER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1590, 'TMT', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1591, 'CHAMPS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1592, 'TOMBOWS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1593, 'BEST QUALITY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1594, 'GMG', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1595, 'DAVER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1596, 'TOGMS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1597, 'UJOINT', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1598, 'BESCO RUBBER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1599, 'CENTER BEARING', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1600, 'MGR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1601, 'SOWA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1602, 'NISTO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1603, 'CV', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1604, 'CHO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1605, 'JUPITER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1606, 'NICAYO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1607, 'IZUMO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1608, 'CBS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1609, 'OPTILUB', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1610, 'HIFI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1611, 'PORULATOR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1612, 'FT', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1613, 'FULL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1614, 'ETENEL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1615, 'PENSO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1616, 'FLOMAX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1617, 'PYROIL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1618, 'MAZDA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1619, 'REPSOL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1620, 'DELUXE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1621, 'SAKURA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1622, 'HO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1623, 'FILTER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1624, 'MOBIL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1625, 'AMERICAN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1626, 'S/M', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1627, 'LAND', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1628, 'SWA                                               ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1629, 'VIVA KEVONO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1630, 'ALLIED', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1631, 'MASTER BRAKE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1632, 'MITTI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1633, 'OKAMOTO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1634, 'FRITEC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1635, 'ABS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1636, 'ICER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1637, 'HOKAIDO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1638, 'RH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1639, 'BENDIX VALUE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1640, 'FBK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1641, 'KS-1', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1642, 'MHCO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1643, 'YAMAMOTO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1644, 'GRANT', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1645, 'MINTYE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1646, 'DANAHER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1647, 'YOTA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1648, 'MOUNTAIN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1649, 'NEW-YORK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1650, 'PBS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1651, 'NAKAYAMA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1652, 'FORMULA KII', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (1653, 'VALUMAX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2629, 'HASAKI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2630, 'RAYBESTO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2631, 'INTER BRAKE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2632, 'BENDIX GLOBAL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2633, 'BRC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2634, 'HI-Q PLUS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2635, 'ALLSAFE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2636, 'KGC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2637, 'SAIKO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2638, 'HYB', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2639, 'POWER DRIVE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2640, 'BRUTE FORCE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2641, 'DONAISON', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2642, 'YFC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2643, 'NISSEKI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2644, 'GITERS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2645, 'TAIWAN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2646, 'MARILIAN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2647, 'FILBEST', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2648, 'QUALITY PARTS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2649, 'YUHOLI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2652, 'PERFECT', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2653, 'JBS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2654, 'DELTA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2655, 'AKESONO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2656, 'KOBE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2657, 'PRO SPEC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2658, 'II&II', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2659, 'AYOWA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2660, 'VAKO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2661, 'DURO LAST', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2662, 'RUVILLE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2663, 'AUTOLITE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2664, 'CHEVRON', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2665, 'EXTRA REV', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2666, 'SUPER FORCE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2667, 'JKS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2668, 'NUKKO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2669, 'DESPECTRUM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2670, 'COFAP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2671, 'TOP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2672, 'JCC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2673, 'AKEBONO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2675, 'LIQUI MOLI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2676, 'PURE GUARD', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2677, 'DEUSIC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (2678, 'GENERAL FILTERS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3675, 'TECHNO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3676, 'NKK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3677, 'ASCOPS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3678, 'PARAUT ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3679, 'AISIN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3680, 'NAMCCO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3681, 'BWD', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3682, 'MESACO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3683, 'HERCULES ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3684, 'CONTINENTAL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3688, 'FREEZETONE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3689, 'KRONYO ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3690, 'MAXH3', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3691, 'REVOLUB', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3692, 'PRO-LUBE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3693, 'MOBIS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3694, 'AISIN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (3695, 'CONTROIL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (4695, 'WD-40', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (5695, 'PETRUL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (5696, 'YALE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (5697, 'WOLFOX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (5698, 'GROZ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (5699, 'NEAPCO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6699, 'AMALIE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6700, 'DURO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6701, 'FAW', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6702, 'FILTRON', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6703, 'WAN-HSING', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6704, 'BLUE-WAY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6705, 'OIL FILTER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6706, 'W', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6707, 'DJF', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6708, 'PREMIUN PARTS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6709, 'GJEM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6710, 'DEUTSCH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6711, 'LORENZ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6712, 'AMC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6713, 'COJI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6714, 'K&W', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6715, 'LEAK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6716, 'ZAX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6717, 'ATE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6718, 'MARKSMAN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6719, 'MR METAL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6720, 'PRO SEAL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (6721, 'PROSTONE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7720, 'ST', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7721, 'PHC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7722, 'KTC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7723, 'ZEREX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7725, 'TOKAI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7726, 'WALKER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7727, 'G-POWER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7728, 'V-POWER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7729, 'SACHS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7730, 'JOMO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7731, 'KEBO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7732, 'CIB', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7733, 'GSP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7734, 'SHIMAHIDE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7735, 'JSP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7736, 'SAMGTOS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7737, 'KIC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7738, 'MAGICAR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7739, 'FYC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7740, 'SPARK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7741, 'CNASAKI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7742, 'GM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7743, 'NSH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7744, 'MIGHTY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7746, 'GENERAL PARTS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7747, 'TZK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7748, 'SOPORTE RE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7749, 'AKT', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7750, 'STATER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7751, 'DENKI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7753, 'ISAKA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7754, 'PAR NUMBER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7755, 'SLK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7756, 'EUROLEX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7757, 'MITSU', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7758, 'ISO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (7759, 'WARNER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8742, 'UNIPOINT', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8743, 'ZENITH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8744, 'VULKO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8746, 'KENSEI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8747, 'DRIVEWAY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8748, 'CHASE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8749, 'HWANG-YU', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8750, 'HANYONG', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8751, 'CAR-DEX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8752, 'SAMYUNG', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8753, 'VINY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8754, 'DIAMON', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8756, 'FIRESTONE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8757, 'HAIDA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8758, 'HUMHO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8759, 'JK-TYRE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8760, 'TRIANGLE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8761, 'BIGBIZ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8762, 'SUPER TUBO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8763, 'RUBBERMIX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8764, 'KUMHO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8765, 'TOYO-TIRE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8766, 'ORG', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8767, 'ENERMAX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8768, 'LUK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8769, 'SABO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8770, 'STEVAUX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8771, 'NBA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8772, 'HONDA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8773, 'HICKS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8774, 'FXK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8775, 'L&S', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8776, 'NBN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8777, 'SODA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8778, 'WLK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8779, 'HIAN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8780, 'CRF', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8781, 'FSA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8782, 'MOTORTECH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (8783, 'FOCOS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9781, 'AP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9782, 'SEVEN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9783, 'LANDSTAR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9784, 'ERISTIC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9785, 'MIACO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9786, 'FIT', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9787, 'HCSCO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9788, 'SPEACE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9789, 'SGP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9790, 'COOPLUS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9791, 'GNS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9793, 'GSN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9794, 'TL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9795, 'FIRSTCARS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9796, 'CHAHATSU', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9797, 'PNC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9798, 'HSS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9799, 'TKK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9800, 'IKK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9801, 'FPI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9803, 'JILEN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9804, 'HOMO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9805, 'SH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9806, 'DINOCO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9807, 'SANWA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9808, 'NAWCAR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9809, 'MAX PARTS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9810, 'HIK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9811, 'AGS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9812, 'YM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9813, 'TBI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9814, 'YRC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9815, 'IYR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9817, 'FIRST', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9818, 'CARZUUN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9819, 'KAPARS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9820, 'JNPS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9821, 'POLIURET', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9822, 'MARIUCHI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9823, 'AIR HACE CLEAR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9824, 'CAR-SHOW', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9825, 'CHIN-DEN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9826, 'CHEETAH ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9827, 'VF', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9828, 'HELLA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9829, 'CAP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9830, 'VIPAL ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9831, 'TAQUITA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9832, 'X-TRA SEAL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9833, 'BRLEX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9834, 'TUV CER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9837, 'PLEWS LUBRI MATE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9838, 'GROZ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9839, 'TRANSPO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9840, 'REGITAR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9841, 'GEON', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9843, 'MTP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9844, 'AUTOMOBILE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9845, 'APK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9847, 'ACA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9848, 'BRIGETONE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9849, 'POLIWAY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9850, 'JQL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9851, 'UF8', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9852, 'LED ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9853, 'LACER ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9854, 'MOTOCYCLE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9855, 'ITEC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9856, 'XTENZO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9857, 'DLAA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9858, 'FOG LAMP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9859, 'FORCEIEC ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9860, 'HYPER-F ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9862, 'HIGH-FLOW ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9863, 'LD ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9864, 'MDK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9865, 'SCISSON JACK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9866, 'ACD', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9867, 'HLD', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9868, 'TAEKO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9869, 'SOKO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9870, 'IMPERIO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9872, 'DAYANG', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9873, 'TBL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9874, 'SANLG MOTOR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9875, 'SOG', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9876, 'KIYOSHI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9877, 'YONGHE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9878, 'QIAQI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9879, 'ACA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9880, 'VANADIUM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9881, 'VOLTECK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9882, 'LUMIACTION', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9883, 'PROTEC ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9884, 'TACTIX ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9885, 'APOLO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9886, 'HERMEX ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9887, 'JNK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9888, 'TRAPE MARK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9889, 'VF', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9890, 'SMP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9891, 'ACHIMORE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9892, 'KIKI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9893, 'SANWA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9894, 'FLEX OIL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9895, 'RACOR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9896, 'STARRET ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9897, 'SUPER 200', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9898, 'DEWALT ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9899, 'MOTOROLA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9900, 'TYPR ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9901, 'I-SOUND', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9903, 'TBJ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9904, 'CAMELLIA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9905, 'ZUIKO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9906, 'DTS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9907, 'ZEN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9908, 'FEK ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9909, 'EMB', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9910, 'OCEAN WELDI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9911, 'INWELD ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9913, 'MITSUBISHI ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9914, 'YOKOMI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9915, 'JG', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9916, 'ZHEBRA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9917, 'GR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9919, 'AUTO BLUS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9921, 'EH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9922, 'HELLA ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9923, 'PHOENIX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9924, 'BLITS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9925, 'PT', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9926, 'LOVE YOU STAR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9927, 'JCAA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9928, 'SPORT', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9929, 'FORCOD ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9930, 'TYPER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9931, 'SPCC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9932, 'IG', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9933, 'ROPE HODE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9934, 'CLA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9935, 'CEL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9936, 'RTM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9937, 'K2', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9939, 'LG', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9940, 'BRAKE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9941, 'SEIRE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9942, 'GONI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9943, 'TRIDAN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9944, 'LICEN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9945, 'GO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9946, 'FIRE STOP', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9947, 'GARROBO BRAND', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9948, 'DA XIANG', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9949, 'PRO STOCK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9950, 'WINA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9951, 'CNT', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9952, 'IR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9953, 'TH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9954, 'RA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9955, 'LTH', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9956, 'PARCRAF', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9957, 'NBC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9958, 'GOOD YEAR ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9959, 'TTO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9960, 'KINSTONE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9961, 'SEN BEM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9963, 'WHEELEGEN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9964, 'VOICED', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9965, 'HAYATE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9966, 'MIRE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9967, 'NOUYA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9969, 'JSK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9970, 'ZETO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9971, 'VG33', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9972, 'DIERO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9973, 'MAKOKO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9974, 'CARTER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9975, 'FLAMMA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9976, 'TOYO FIL', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9977, 'ZSG', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9978, 'MAKOTO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9979, 'ATTIVO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9980, 'CHEVROLET', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9981, 'BLACK-DECKER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9982, 'HANKOOK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9984, 'NSO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9985, 'GEMMA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9986, 'NASA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9988, 'WANDA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9990, 'KYOTO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9992, 'KOREA START', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9994, 'PRO DRIVEN', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9996, 'MAT SUMA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (9998, 'POWER WHITE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10000, 'KENWOOD', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10002, 'ALZA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10004, 'DIENER', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10005, 'CNHF', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10006, 'SUPER FIRS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10007, 'CEPSA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10008, 'DS ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10010, 'ZUTAKA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10011, 'BIBBI', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10012, 'JINBO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10013, 'IMPERIAL ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10014, 'DYNAMO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10015, 'MAXIMOLUB', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (10016, 'PMC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11016, 'FULIYAMA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11017, 'DMR', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11018, 'FIERRO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11019, 'CARICO ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11020, 'FLAMINGO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11021, 'AUSTONE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11022, 'MAHLE', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11023, 'OSIRIS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11025, 'GX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11026, 'CLK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11027, 'YASAK', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11028, 'CGY', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11029, 'SANWA', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11030, 'EISSLER ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11031, 'ATLASBX', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11032, 'PRIME GUARD', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11033, 'BUFALO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11034, 'CHERRY ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11035, 'MKM', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11036, 'AL-KO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11038, 'SEAHAWKS', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11039, 'SATAN ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11040, 'DAKURO ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11041, 'ZMO', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11042, 'STA LUBE ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11043, 'AKRON ', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11045, 'CARDOC', '', '', 1, NULL, NULL);
INSERT INTO `marcas` VALUES (11047, 'prueba', 'prueba ', NULL, 1, '2024-10-10 19:49:57', '2024-10-10 19:58:39');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (6, '2024_10_10_144729_create_permission_tables', 2);
INSERT INTO `migrations` VALUES (7, '2024_10_10_145910_create_marcas_table', 3);
INSERT INTO `migrations` VALUES (8, '2024_10_10_151258_create_presentations_table', 4);
INSERT INTO `migrations` VALUES (9, '2024_10_10_151752_create_categories_table', 5);
INSERT INTO `migrations` VALUES (12, '2024_10_10_160901_create_economic_activities_table', 6);
INSERT INTO `migrations` VALUES (15, '2024_10_10_161150_create_unit_measurements_table', 7);
INSERT INTO `migrations` VALUES (17, '2024_10_10_161641_create_countries_table', 7);
INSERT INTO `migrations` VALUES (19, '2024_10_10_153921_create_providers_table', 8);
INSERT INTO `migrations` VALUES (23, '2024_10_10_182327_create_companies_table', 9);
INSERT INTO `migrations` VALUES (24, '2024_10_10_183913_create_companies_table', 10);
INSERT INTO `migrations` VALUES (26, '2024_10_10_161423_create_tributes_table', 12);
INSERT INTO `migrations` VALUES (29, '2024_10_10_184515_create_branches_table', 13);
INSERT INTO `migrations` VALUES (30, '2024_10_10_153506_create_products_table', 14);
INSERT INTO `migrations` VALUES (31, '2024_10_11_044646_create_product_tributes_table', 14);

-- ----------------------------
-- Table structure for model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_permissions_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for model_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles`  (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_roles_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_roles
-- ----------------------------
INSERT INTO `model_has_roles` VALUES (1, 'App\\Models\\User', 1);
INSERT INTO `model_has_roles` VALUES (2, 'App\\Models\\User', 1);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 139 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES (1, 'view_role', 'web', '2024-10-10 14:47:46', '2024-10-10 14:47:46');
INSERT INTO `permissions` VALUES (2, 'view_any_role', 'web', '2024-10-10 14:47:46', '2024-10-10 14:47:46');
INSERT INTO `permissions` VALUES (3, 'create_role', 'web', '2024-10-10 14:47:46', '2024-10-10 14:47:46');
INSERT INTO `permissions` VALUES (4, 'update_role', 'web', '2024-10-10 14:47:46', '2024-10-10 14:47:46');
INSERT INTO `permissions` VALUES (5, 'delete_role', 'web', '2024-10-10 14:47:46', '2024-10-10 14:47:46');
INSERT INTO `permissions` VALUES (6, 'delete_any_role', 'web', '2024-10-10 14:47:46', '2024-10-10 14:47:46');
INSERT INTO `permissions` VALUES (7, 'view_branch', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (8, 'view_any_branch', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (9, 'create_branch', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (10, 'update_branch', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (11, 'restore_branch', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (12, 'restore_any_branch', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (13, 'replicate_branch', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (14, 'reorder_branch', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (15, 'delete_branch', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (16, 'delete_any_branch', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (17, 'force_delete_branch', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (18, 'force_delete_any_branch', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (19, 'view_category', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (20, 'view_any_category', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (21, 'create_category', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (22, 'update_category', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (23, 'restore_category', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (24, 'restore_any_category', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (25, 'replicate_category', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (26, 'reorder_category', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (27, 'delete_category', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (28, 'delete_any_category', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (29, 'force_delete_category', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (30, 'force_delete_any_category', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (31, 'view_company', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (32, 'view_any_company', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (33, 'create_company', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (34, 'update_company', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (35, 'restore_company', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (36, 'restore_any_company', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (37, 'replicate_company', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (38, 'reorder_company', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (39, 'delete_company', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (40, 'delete_any_company', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (41, 'force_delete_company', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (42, 'force_delete_any_company', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (43, 'view_country', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (44, 'view_any_country', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (45, 'create_country', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (46, 'update_country', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (47, 'restore_country', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (48, 'restore_any_country', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (49, 'replicate_country', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (50, 'reorder_country', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (51, 'delete_country', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (52, 'delete_any_country', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (53, 'force_delete_country', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (54, 'force_delete_any_country', 'web', '2024-10-10 21:22:47', '2024-10-10 21:22:47');
INSERT INTO `permissions` VALUES (55, 'view_departamento', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (56, 'view_any_departamento', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (57, 'create_departamento', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (58, 'update_departamento', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (59, 'restore_departamento', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (60, 'restore_any_departamento', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (61, 'replicate_departamento', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (62, 'reorder_departamento', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (63, 'delete_departamento', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (64, 'delete_any_departamento', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (65, 'force_delete_departamento', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (66, 'force_delete_any_departamento', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (67, 'view_distrito', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (68, 'view_any_distrito', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (69, 'create_distrito', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (70, 'update_distrito', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (71, 'restore_distrito', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (72, 'restore_any_distrito', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (73, 'replicate_distrito', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (74, 'reorder_distrito', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (75, 'delete_distrito', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (76, 'delete_any_distrito', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (77, 'force_delete_distrito', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (78, 'force_delete_any_distrito', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (79, 'view_economic::activity', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (80, 'view_any_economic::activity', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (81, 'create_economic::activity', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (82, 'update_economic::activity', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (83, 'restore_economic::activity', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (84, 'restore_any_economic::activity', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (85, 'replicate_economic::activity', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (86, 'reorder_economic::activity', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (87, 'delete_economic::activity', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (88, 'delete_any_economic::activity', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (89, 'force_delete_economic::activity', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (90, 'force_delete_any_economic::activity', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (91, 'view_marca', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (92, 'view_any_marca', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (93, 'create_marca', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (94, 'update_marca', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (95, 'restore_marca', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (96, 'restore_any_marca', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (97, 'replicate_marca', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (98, 'reorder_marca', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (99, 'delete_marca', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (100, 'delete_any_marca', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (101, 'force_delete_marca', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (102, 'force_delete_any_marca', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (103, 'view_tribute', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (104, 'view_any_tribute', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (105, 'create_tribute', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (106, 'update_tribute', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (107, 'restore_tribute', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (108, 'restore_any_tribute', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (109, 'replicate_tribute', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (110, 'reorder_tribute', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (111, 'delete_tribute', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (112, 'delete_any_tribute', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (113, 'force_delete_tribute', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (114, 'force_delete_any_tribute', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (115, 'view_unit::measurement', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (116, 'view_any_unit::measurement', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (117, 'create_unit::measurement', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (118, 'update_unit::measurement', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (119, 'restore_unit::measurement', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (120, 'restore_any_unit::measurement', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (121, 'replicate_unit::measurement', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (122, 'reorder_unit::measurement', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (123, 'delete_unit::measurement', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (124, 'delete_any_unit::measurement', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (125, 'force_delete_unit::measurement', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (126, 'force_delete_any_unit::measurement', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (127, 'view_user', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (128, 'view_any_user', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (129, 'create_user', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (130, 'update_user', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (131, 'restore_user', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (132, 'restore_any_user', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (133, 'replicate_user', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (134, 'reorder_user', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (135, 'delete_user', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (136, 'delete_any_user', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (137, 'force_delete_user', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');
INSERT INTO `permissions` VALUES (138, 'force_delete_any_user', 'web', '2024-10-10 21:22:48', '2024-10-10 21:22:48');

-- ----------------------------
-- Table structure for presentations
-- ----------------------------
DROP TABLE IF EXISTS `presentations`;
CREATE TABLE `presentations`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of presentations
-- ----------------------------

-- ----------------------------
-- Table structure for product_tributes
-- ----------------------------
DROP TABLE IF EXISTS `product_tributes`;
CREATE TABLE `product_tributes`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `tribute_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_tributes_product_id_foreign`(`product_id` ASC) USING BTREE,
  INDEX `product_tributes_tribute_id_foreign`(`tribute_id` ASC) USING BTREE,
  CONSTRAINT `product_tributes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `product_tributes_tribute_id_foreign` FOREIGN KEY (`tribute_id`) REFERENCES `tributes` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of product_tributes
-- ----------------------------
INSERT INTO `product_tributes` VALUES (1, 2, 2, NULL, NULL);
INSERT INTO `product_tributes` VALUES (2, 2, 1, NULL, NULL);
INSERT INTO `product_tributes` VALUES (3, 1, 1, NULL, NULL);

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bar_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_service` tinyint(1) NOT NULL DEFAULT 0,
  `category_id` bigint UNSIGNED NOT NULL,
  `marca_id` bigint UNSIGNED NOT NULL,
  `unit_measurement_id` bigint UNSIGNED NOT NULL,
  `tribute_id` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `products_category_id_foreign`(`category_id` ASC) USING BTREE,
  INDEX `products_marca_id_foreign`(`marca_id` ASC) USING BTREE,
  INDEX `products_unit_measurement_id_foreign`(`unit_measurement_id` ASC) USING BTREE,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `products_marca_id_foreign` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `products_unit_measurement_id_foreign` FOREIGN KEY (`unit_measurement_id`) REFERENCES `unit_measurements` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (1, 'BALERO P/ COLLARIN ISUZU CAMION 92-', NULL, 'CT70B-KOY ', 'asd', 0, 28, 290, 1, NULL, '\"products\\/01J9WZX3XR4XTNR7YE4XTB4Q7B.webp\"', 1, NULL, '2024-10-11 05:00:24', '2024-10-11 05:49:23');
INSERT INTO `products` VALUES (2, 'asd', 'asd', 'asd', 'asd', 1, 50, 225, 3, NULL, '\"products\\/01J9X0HYXK0DBJRKW6QK0C2R35.png\"', 1, NULL, '2024-10-11 05:01:15', '2024-10-11 05:46:37');

-- ----------------------------
-- Table structure for providers
-- ----------------------------
DROP TABLE IF EXISTS `providers`;
CREATE TABLE `providers`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nacionality` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `department_id` bigint UNSIGNED NOT NULL,
  `distrito_id` bigint UNSIGNED NOT NULL,
  `direction` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone_one` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone_two` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nrc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `nit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `economic_activity_id` bigint UNSIGNED NOT NULL,
  `condition_payment` enum('Contado','Credito') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `credit_days` int NULL DEFAULT NULL,
  `credit_limit` decimal(10, 2) NULL DEFAULT NULL,
  `balance` decimal(10, 2) NULL DEFAULT NULL,
  `provider_type` enum('Pequeño','Grande','Mediano','Micro') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `contact_seller` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone_seller` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email_seller` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `providers_department_id_foreign`(`department_id` ASC) USING BTREE,
  INDEX `providers_distrito_id_foreign`(`distrito_id` ASC) USING BTREE,
  INDEX `providers_economic_activity_id_foreign`(`economic_activity_id` ASC) USING BTREE,
  CONSTRAINT `providers_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departamentos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `providers_distrito_id_foreign` FOREIGN KEY (`distrito_id`) REFERENCES `distritos` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `providers_economic_activity_id_foreign` FOREIGN KEY (`economic_activity_id`) REFERENCES `economic_activities` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of providers
-- ----------------------------

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`) USING BTREE,
  INDEX `role_has_permissions_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------
INSERT INTO `role_has_permissions` VALUES (1, 1);
INSERT INTO `role_has_permissions` VALUES (2, 1);
INSERT INTO `role_has_permissions` VALUES (3, 1);
INSERT INTO `role_has_permissions` VALUES (4, 1);
INSERT INTO `role_has_permissions` VALUES (5, 1);
INSERT INTO `role_has_permissions` VALUES (6, 1);
INSERT INTO `role_has_permissions` VALUES (7, 1);
INSERT INTO `role_has_permissions` VALUES (8, 1);
INSERT INTO `role_has_permissions` VALUES (9, 1);
INSERT INTO `role_has_permissions` VALUES (10, 1);
INSERT INTO `role_has_permissions` VALUES (11, 1);
INSERT INTO `role_has_permissions` VALUES (12, 1);
INSERT INTO `role_has_permissions` VALUES (13, 1);
INSERT INTO `role_has_permissions` VALUES (14, 1);
INSERT INTO `role_has_permissions` VALUES (15, 1);
INSERT INTO `role_has_permissions` VALUES (16, 1);
INSERT INTO `role_has_permissions` VALUES (17, 1);
INSERT INTO `role_has_permissions` VALUES (18, 1);
INSERT INTO `role_has_permissions` VALUES (19, 1);
INSERT INTO `role_has_permissions` VALUES (20, 1);
INSERT INTO `role_has_permissions` VALUES (21, 1);
INSERT INTO `role_has_permissions` VALUES (22, 1);
INSERT INTO `role_has_permissions` VALUES (23, 1);
INSERT INTO `role_has_permissions` VALUES (24, 1);
INSERT INTO `role_has_permissions` VALUES (25, 1);
INSERT INTO `role_has_permissions` VALUES (26, 1);
INSERT INTO `role_has_permissions` VALUES (27, 1);
INSERT INTO `role_has_permissions` VALUES (28, 1);
INSERT INTO `role_has_permissions` VALUES (29, 1);
INSERT INTO `role_has_permissions` VALUES (30, 1);
INSERT INTO `role_has_permissions` VALUES (31, 1);
INSERT INTO `role_has_permissions` VALUES (32, 1);
INSERT INTO `role_has_permissions` VALUES (34, 1);
INSERT INTO `role_has_permissions` VALUES (35, 1);
INSERT INTO `role_has_permissions` VALUES (36, 1);
INSERT INTO `role_has_permissions` VALUES (37, 1);
INSERT INTO `role_has_permissions` VALUES (38, 1);
INSERT INTO `role_has_permissions` VALUES (39, 1);
INSERT INTO `role_has_permissions` VALUES (40, 1);
INSERT INTO `role_has_permissions` VALUES (41, 1);
INSERT INTO `role_has_permissions` VALUES (42, 1);
INSERT INTO `role_has_permissions` VALUES (43, 1);
INSERT INTO `role_has_permissions` VALUES (44, 1);
INSERT INTO `role_has_permissions` VALUES (45, 1);
INSERT INTO `role_has_permissions` VALUES (46, 1);
INSERT INTO `role_has_permissions` VALUES (47, 1);
INSERT INTO `role_has_permissions` VALUES (48, 1);
INSERT INTO `role_has_permissions` VALUES (49, 1);
INSERT INTO `role_has_permissions` VALUES (50, 1);
INSERT INTO `role_has_permissions` VALUES (51, 1);
INSERT INTO `role_has_permissions` VALUES (52, 1);
INSERT INTO `role_has_permissions` VALUES (53, 1);
INSERT INTO `role_has_permissions` VALUES (54, 1);
INSERT INTO `role_has_permissions` VALUES (55, 1);
INSERT INTO `role_has_permissions` VALUES (56, 1);
INSERT INTO `role_has_permissions` VALUES (57, 1);
INSERT INTO `role_has_permissions` VALUES (58, 1);
INSERT INTO `role_has_permissions` VALUES (59, 1);
INSERT INTO `role_has_permissions` VALUES (60, 1);
INSERT INTO `role_has_permissions` VALUES (61, 1);
INSERT INTO `role_has_permissions` VALUES (62, 1);
INSERT INTO `role_has_permissions` VALUES (63, 1);
INSERT INTO `role_has_permissions` VALUES (64, 1);
INSERT INTO `role_has_permissions` VALUES (65, 1);
INSERT INTO `role_has_permissions` VALUES (66, 1);
INSERT INTO `role_has_permissions` VALUES (67, 1);
INSERT INTO `role_has_permissions` VALUES (68, 1);
INSERT INTO `role_has_permissions` VALUES (69, 1);
INSERT INTO `role_has_permissions` VALUES (70, 1);
INSERT INTO `role_has_permissions` VALUES (71, 1);
INSERT INTO `role_has_permissions` VALUES (72, 1);
INSERT INTO `role_has_permissions` VALUES (73, 1);
INSERT INTO `role_has_permissions` VALUES (74, 1);
INSERT INTO `role_has_permissions` VALUES (75, 1);
INSERT INTO `role_has_permissions` VALUES (76, 1);
INSERT INTO `role_has_permissions` VALUES (77, 1);
INSERT INTO `role_has_permissions` VALUES (78, 1);
INSERT INTO `role_has_permissions` VALUES (79, 1);
INSERT INTO `role_has_permissions` VALUES (80, 1);
INSERT INTO `role_has_permissions` VALUES (81, 1);
INSERT INTO `role_has_permissions` VALUES (82, 1);
INSERT INTO `role_has_permissions` VALUES (83, 1);
INSERT INTO `role_has_permissions` VALUES (84, 1);
INSERT INTO `role_has_permissions` VALUES (85, 1);
INSERT INTO `role_has_permissions` VALUES (86, 1);
INSERT INTO `role_has_permissions` VALUES (87, 1);
INSERT INTO `role_has_permissions` VALUES (88, 1);
INSERT INTO `role_has_permissions` VALUES (89, 1);
INSERT INTO `role_has_permissions` VALUES (90, 1);
INSERT INTO `role_has_permissions` VALUES (91, 1);
INSERT INTO `role_has_permissions` VALUES (92, 1);
INSERT INTO `role_has_permissions` VALUES (93, 1);
INSERT INTO `role_has_permissions` VALUES (94, 1);
INSERT INTO `role_has_permissions` VALUES (95, 1);
INSERT INTO `role_has_permissions` VALUES (96, 1);
INSERT INTO `role_has_permissions` VALUES (97, 1);
INSERT INTO `role_has_permissions` VALUES (98, 1);
INSERT INTO `role_has_permissions` VALUES (99, 1);
INSERT INTO `role_has_permissions` VALUES (100, 1);
INSERT INTO `role_has_permissions` VALUES (101, 1);
INSERT INTO `role_has_permissions` VALUES (102, 1);
INSERT INTO `role_has_permissions` VALUES (103, 1);
INSERT INTO `role_has_permissions` VALUES (104, 1);
INSERT INTO `role_has_permissions` VALUES (105, 1);
INSERT INTO `role_has_permissions` VALUES (106, 1);
INSERT INTO `role_has_permissions` VALUES (107, 1);
INSERT INTO `role_has_permissions` VALUES (108, 1);
INSERT INTO `role_has_permissions` VALUES (109, 1);
INSERT INTO `role_has_permissions` VALUES (110, 1);
INSERT INTO `role_has_permissions` VALUES (111, 1);
INSERT INTO `role_has_permissions` VALUES (112, 1);
INSERT INTO `role_has_permissions` VALUES (113, 1);
INSERT INTO `role_has_permissions` VALUES (114, 1);
INSERT INTO `role_has_permissions` VALUES (115, 1);
INSERT INTO `role_has_permissions` VALUES (116, 1);
INSERT INTO `role_has_permissions` VALUES (117, 1);
INSERT INTO `role_has_permissions` VALUES (118, 1);
INSERT INTO `role_has_permissions` VALUES (119, 1);
INSERT INTO `role_has_permissions` VALUES (120, 1);
INSERT INTO `role_has_permissions` VALUES (121, 1);
INSERT INTO `role_has_permissions` VALUES (122, 1);
INSERT INTO `role_has_permissions` VALUES (123, 1);
INSERT INTO `role_has_permissions` VALUES (124, 1);
INSERT INTO `role_has_permissions` VALUES (125, 1);
INSERT INTO `role_has_permissions` VALUES (126, 1);
INSERT INTO `role_has_permissions` VALUES (127, 1);
INSERT INTO `role_has_permissions` VALUES (128, 1);
INSERT INTO `role_has_permissions` VALUES (129, 1);
INSERT INTO `role_has_permissions` VALUES (130, 1);
INSERT INTO `role_has_permissions` VALUES (131, 1);
INSERT INTO `role_has_permissions` VALUES (132, 1);
INSERT INTO `role_has_permissions` VALUES (133, 1);
INSERT INTO `role_has_permissions` VALUES (134, 1);
INSERT INTO `role_has_permissions` VALUES (135, 1);
INSERT INTO `role_has_permissions` VALUES (136, 1);
INSERT INTO `role_has_permissions` VALUES (137, 1);
INSERT INTO `role_has_permissions` VALUES (138, 1);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'super_admin', 'web', '2024-10-10 14:47:46', '2024-10-10 14:47:46');
INSERT INTO `roles` VALUES (2, 'panel_user', 'web', '2024-10-10 14:51:44', '2024-10-10 14:51:44');

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('8GQvxd9ZkCK8wxLB4jYdKKQRMQlNmBW6S2svI2Vw', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.36', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoiNVJGcHQ3RVdIeXlJOVJkanNKbVZSaGwyTXhQYmdSZVh4dlVsOGxQZCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYWRtaW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkenl3cWJyRFhhNFV4Y2xUUW1YSEppZWFrTEhDZ2FHWE5EZGFIclUzcW5sZTRCVUNNcEJsUXEiO3M6ODoiZmlsYW1lbnQiO2E6MDp7fXM6NjoidGFibGVzIjthOjI6e3M6MzM6Ikxpc3REZXBhcnRhbWVudG9zX3RvZ2dsZWRfY29sdW1ucyI7YToyOntzOjEwOiJjcmVhdGVkX2F0IjtiOjE7czoxMDoidXBkYXRlZF9hdCI7YjowO31zOjI4OiJMaXN0UHJvZHVjdHNfdG9nZ2xlZF9jb2x1bW5zIjthOjU6e3M6MTA6ImlzX3NlcnZpY2UiO2I6MDtzOjk6ImlzX2FjdGl2ZSI7YjowO3M6MTA6ImRlbGV0ZWRfYXQiO2I6MDtzOjEwOiJjcmVhdGVkX2F0IjtiOjA7czoxMDoidXBkYXRlZF9hdCI7YjowO319fQ==', 1728626759);

-- ----------------------------
-- Table structure for tributes
-- ----------------------------
DROP TABLE IF EXISTS `tributes`;
CREATE TABLE `tributes`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_percentage` tinyint(1) NOT NULL DEFAULT 0,
  `rate` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tributes
-- ----------------------------
INSERT INTO `tributes` VALUES (1, '20', 'Valor agregado IVA 13%', 1, 13.00, 1, '2024-10-11 02:08:01', '2024-10-11 02:42:02');
INSERT INTO `tributes` VALUES (2, '59', 'Turismo', 0, 5.00, 1, '2024-10-11 02:08:50', '2024-10-11 02:46:03');

-- ----------------------------
-- Table structure for unit_measurements
-- ----------------------------
DROP TABLE IF EXISTS `unit_measurements`;
CREATE TABLE `unit_measurements`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of unit_measurements
-- ----------------------------
INSERT INTO `unit_measurements` VALUES (1, '59', 'UNIDAD', 1, '2024-10-10 23:39:45', '2024-10-10 23:39:45');
INSERT INTO `unit_measurements` VALUES (2, '57', 'CIENTO', 1, '2024-10-11 01:56:25', '2024-10-11 01:56:25');
INSERT INTO `unit_measurements` VALUES (3, '58', 'DOCENA', 1, '2024-10-11 01:56:40', '2024-10-11 01:56:40');
INSERT INTO `unit_measurements` VALUES (4, '99', 'OTRA', 1, '2024-10-11 01:56:53', '2024-10-11 01:56:53');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Admin', 'admin@admin.com', NULL, '$2y$12$zywqbrDXa4UxclTQmXHJieakLHCgaGXNDdaHrU3qnle4BUCMpBlQq', NULL, '2024-10-10 13:40:43', '2024-10-10 14:56:32');
INSERT INTO `users` VALUES (2, 'test', 'test@test.com', NULL, '$2y$12$1wj/B7Nn6eXZEhTf1WkMVu4MVnMqu.7UOQQ8Lnx3Rh0I9zrq92GlO', NULL, '2024-10-10 14:50:26', '2024-10-10 14:50:26');

SET FOREIGN_KEY_CHECKS = 1;
