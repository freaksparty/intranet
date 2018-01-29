/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : intranet

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-01-25 21:59:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ficonpuntos
-- ----------------------------
DROP TABLE IF EXISTS `ficonpuntos`;
CREATE TABLE `ficonpuntos` (
  `ficonpuntos` int(11) DEFAULT '0',
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of ficonpuntos
-- ----------------------------
INSERT INTO `ficonpuntos` VALUES ('99999', '1');
INSERT INTO `ficonpuntos` VALUES ('10', '2');
INSERT INTO `ficonpuntos` VALUES ('1', '3');

-- ----------------------------
-- Table structure for games
-- ----------------------------
DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `title` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `description` varchar(5000) COLLATE utf8_spanish_ci DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `day_week` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL,
  `hour` varchar(5) COLLATE utf8_spanish_ci DEFAULT NULL,
  `max` int(11) NOT NULL DEFAULT '0',
  `min_team` int(11) NOT NULL DEFAULT '1',
  `max_team` int(11) NOT NULL DEFAULT '1',
  `date_end_reg` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of games
-- ----------------------------
INSERT INTO `games` VALUES ('1', 'ardilla.jpg', 'Unreal Tournament', 'BLABLABLABLALBLALBLABLBALABLBALLBALALBBLALBBLBALLBALBLB', '2018-01-13 21:00:00', 'Sábado', '21:00', '32', '1', '1', null);
INSERT INTO `games` VALUES ('2', 'ardilla.jpg', 'Rocket League', 'BLABLALBLALBALBLBALBLABLLBALBLALBALBLBALBLABLABLALBLALBA', '2018-01-13 18:00:00', 'Sábado', '18:00', '32', '2', '2', '2018-01-08 00:00:00');
INSERT INTO `games` VALUES ('3', 'ardilla.jpg', 'Hearthstone', 'BLALBALBLBLABLBALBLALABLLABLA', '2018-01-14 16:00:00', 'Domingo', '16:00', '16', '4', '4', '2018-01-16 00:00:00');

-- ----------------------------
-- Table structure for game_points
-- ----------------------------
DROP TABLE IF EXISTS `game_points`;
CREATE TABLE `game_points` (
  `id_game` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  PRIMARY KEY (`id_game`,`position`),
  CONSTRAINT `game_points_ibfk_1` FOREIGN KEY (`id_game`) REFERENCES `games` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of game_points
-- ----------------------------

-- ----------------------------
-- Table structure for game_users
-- ----------------------------
DROP TABLE IF EXISTS `game_users`;
CREATE TABLE `game_users` (
  `id_game` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_team` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_game`,`id_user`),
  KEY `id_user` (`id_user`),
  KEY `id_team` (`id_team`),
  CONSTRAINT `game_users_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  CONSTRAINT `game_users_ibfk_2` FOREIGN KEY (`id_game`) REFERENCES `games` (`id`),
  CONSTRAINT `game_users_ibfk_3` FOREIGN KEY (`id_team`) REFERENCES `teams` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of game_users
-- ----------------------------
INSERT INTO `game_users` VALUES ('1', '1', null);
INSERT INTO `game_users` VALUES ('3', '1', null);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', 'god');
INSERT INTO `roles` VALUES ('2', 'admin');
INSERT INTO `roles` VALUES ('3', 'user');

-- ----------------------------
-- Table structure for teams
-- ----------------------------
DROP TABLE IF EXISTS `teams`;
CREATE TABLE `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of teams
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `pass` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `role` int(11) DEFAULT NULL,
  `cryp` varchar(64) COLLATE utf8_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role` (`role`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'rgeo', '0f12d74ba9f4ba52b2852f7f0521d845', 'hermidamourelle@gmail.com', '3', 'cf5d56834a71d3bc9fbf5c640fd559fd');
INSERT INTO `users` VALUES ('2', 'lxlDanilxl', '81dc9bdb52d04dc20036dbd8313ed055', 'lxlDanilxlpro@gmail.com', '1', 'f56bb2bec93bc995de59478a9063ce4d');
INSERT INTO `users` VALUES ('3', 'pruebas', null, 'r.d.franco@udc.es', '3', '9a7d3d96b259b721d6b6fa3cabd9e1b2');
INSERT INTO `users` VALUES ('4', 'Julio', null, '', '3', null);

-- ----------------------------
-- Table structure for winners
-- ----------------------------
DROP TABLE IF EXISTS `winners`;
CREATE TABLE `winners` (
  `id_game` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_game`,`id_user`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `winners_ibfk_1` FOREIGN KEY (`id_game`) REFERENCES `games` (`id`),
  CONSTRAINT `winners_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- ----------------------------
-- Records of winners
-- ----------------------------
