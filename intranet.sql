/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : intranet

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2018-02-03 11:13:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for class_games
-- ----------------------------
DROP TABLE IF EXISTS `class_games`;
CREATE TABLE `class_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of class_games
-- ----------------------------
INSERT INTO `class_games` VALUES ('1', 'Clasificatoria');
INSERT INTO `class_games` VALUES ('2', 'Eliminatoria');
INSERT INTO `class_games` VALUES ('3', 'Clasificatoria pro equipos');
INSERT INTO `class_games` VALUES ('4', 'Eliminatoria por equipos');

-- ----------------------------
-- Table structure for games
-- ----------------------------
DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `class` int(11) DEFAULT NULL,
  `max` int(11) DEFAULT NULL,
  `min` int(11) DEFAULT NULL,
  `date_event` datetime DEFAULT NULL,
  `date_max` datetime DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  KEY `class` (`class`),
  CONSTRAINT `games_ibfk_1` FOREIGN KEY (`type`) REFERENCES `type_games` (`id`),
  CONSTRAINT `games_ibfk_2` FOREIGN KEY (`class`) REFERENCES `class_games` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of games
-- ----------------------------
INSERT INTO `games` VALUES ('1', 'UNREAL TOURNEMENT', 'asdd', 'ardilla.jpg', '1', '1', '10', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2018-01-29 23:45:37');
INSERT INTO `games` VALUES ('2', 'asd', 'asd', 'asd', '1', '1', '100', '0', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2018-02-01 13:57:25');

-- ----------------------------
-- Table structure for participants
-- ----------------------------
DROP TABLE IF EXISTS `participants`;
CREATE TABLE `participants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `team` int(11) DEFAULT NULL,
  `game` int(11) NOT NULL,
  `position` int(11) DEFAULT NULL,
  `register_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `team` (`team`),
  KEY `game` (`game`),
  CONSTRAINT `participants_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`),
  CONSTRAINT `participants_ibfk_2` FOREIGN KEY (`team`) REFERENCES `teams_participants` (`id`),
  CONSTRAINT `participants_ibfk_3` FOREIGN KEY (`game`) REFERENCES `games` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of participants
-- ----------------------------
INSERT INTO `participants` VALUES ('1', '1', '2', '1', '1', '2018-02-01 12:13:26');
INSERT INTO `participants` VALUES ('2', '2', '1', '1', '2', '2018-01-29 23:51:42');

-- ----------------------------
-- Table structure for points_games
-- ----------------------------
DROP TABLE IF EXISTS `points_games`;
CREATE TABLE `points_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_game` int(11) DEFAULT NULL,
  `position` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  PRIMARY KEY (`id`,`position`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of points_games
-- ----------------------------
INSERT INTO `points_games` VALUES ('1', '1', '1', '0');
INSERT INTO `points_games` VALUES ('1', '1', '2', '0');
INSERT INTO `points_games` VALUES ('1', '1', '3', '0');
INSERT INTO `points_games` VALUES ('1', '1', '4', '0');
INSERT INTO `points_games` VALUES ('1', '1', '5', '0');
INSERT INTO `points_games` VALUES ('1', '1', '6', '0');
INSERT INTO `points_games` VALUES ('1', '1', '7', '0');
INSERT INTO `points_games` VALUES ('1', '1', '8', '0');
INSERT INTO `points_games` VALUES ('1', '1', '9', '0');
INSERT INTO `points_games` VALUES ('1', '1', '10', '0');
INSERT INTO `points_games` VALUES ('2', '1', '1', '1');
INSERT INTO `points_games` VALUES ('2', '1', '2', '2');
INSERT INTO `points_games` VALUES ('2', '1', '3', '3');
INSERT INTO `points_games` VALUES ('2', '1', '4', '4');
INSERT INTO `points_games` VALUES ('2', '1', '5', '5');
INSERT INTO `points_games` VALUES ('2', '1', '6', '6');
INSERT INTO `points_games` VALUES ('2', '1', '7', '7');
INSERT INTO `points_games` VALUES ('2', '1', '8', '8');
INSERT INTO `points_games` VALUES ('2', '1', '9', '9');
INSERT INTO `points_games` VALUES ('2', '1', '10', '0');
INSERT INTO `points_games` VALUES ('2', '1', '11', '0');
INSERT INTO `points_games` VALUES ('2', '1', '12', '0');
INSERT INTO `points_games` VALUES ('2', '1', '13', '0');
INSERT INTO `points_games` VALUES ('2', '1', '14', '0');
INSERT INTO `points_games` VALUES ('2', '1', '15', '0');
INSERT INTO `points_games` VALUES ('2', '1', '16', '0');
INSERT INTO `points_games` VALUES ('2', '1', '17', '0');
INSERT INTO `points_games` VALUES ('2', '1', '18', '0');
INSERT INTO `points_games` VALUES ('2', '1', '19', '0');
INSERT INTO `points_games` VALUES ('2', '1', '20', '0');
INSERT INTO `points_games` VALUES ('2', '1', '21', '0');
INSERT INTO `points_games` VALUES ('2', '1', '22', '0');
INSERT INTO `points_games` VALUES ('2', '1', '23', '0');
INSERT INTO `points_games` VALUES ('2', '1', '24', '0');
INSERT INTO `points_games` VALUES ('2', '1', '25', '0');
INSERT INTO `points_games` VALUES ('2', '1', '26', '0');
INSERT INTO `points_games` VALUES ('2', '1', '27', '0');
INSERT INTO `points_games` VALUES ('2', '1', '28', '0');
INSERT INTO `points_games` VALUES ('2', '1', '29', '0');
INSERT INTO `points_games` VALUES ('2', '1', '30', '0');
INSERT INTO `points_games` VALUES ('2', '1', '31', '0');
INSERT INTO `points_games` VALUES ('2', '1', '32', '0');
INSERT INTO `points_games` VALUES ('2', '1', '33', '0');
INSERT INTO `points_games` VALUES ('2', '1', '34', '0');
INSERT INTO `points_games` VALUES ('2', '1', '35', '0');
INSERT INTO `points_games` VALUES ('2', '1', '36', '0');
INSERT INTO `points_games` VALUES ('2', '1', '37', '0');
INSERT INTO `points_games` VALUES ('2', '1', '38', '0');
INSERT INTO `points_games` VALUES ('2', '1', '39', '0');
INSERT INTO `points_games` VALUES ('2', '1', '40', '0');
INSERT INTO `points_games` VALUES ('2', '1', '41', '0');
INSERT INTO `points_games` VALUES ('2', '1', '42', '0');
INSERT INTO `points_games` VALUES ('2', '1', '43', '0');
INSERT INTO `points_games` VALUES ('2', '1', '44', '0');
INSERT INTO `points_games` VALUES ('2', '1', '45', '0');
INSERT INTO `points_games` VALUES ('2', '1', '46', '0');
INSERT INTO `points_games` VALUES ('2', '1', '47', '0');
INSERT INTO `points_games` VALUES ('2', '1', '48', '0');
INSERT INTO `points_games` VALUES ('2', '1', '49', '0');
INSERT INTO `points_games` VALUES ('2', '1', '50', '0');
INSERT INTO `points_games` VALUES ('2', '1', '51', '0');
INSERT INTO `points_games` VALUES ('2', '1', '52', '0');
INSERT INTO `points_games` VALUES ('2', '1', '53', '0');
INSERT INTO `points_games` VALUES ('2', '1', '54', '0');
INSERT INTO `points_games` VALUES ('2', '1', '55', '0');
INSERT INTO `points_games` VALUES ('2', '1', '56', '0');
INSERT INTO `points_games` VALUES ('2', '1', '57', '0');
INSERT INTO `points_games` VALUES ('2', '1', '58', '0');
INSERT INTO `points_games` VALUES ('2', '1', '59', '0');
INSERT INTO `points_games` VALUES ('2', '1', '60', '0');
INSERT INTO `points_games` VALUES ('2', '1', '61', '0');
INSERT INTO `points_games` VALUES ('2', '1', '62', '0');
INSERT INTO `points_games` VALUES ('2', '1', '63', '0');
INSERT INTO `points_games` VALUES ('2', '1', '64', '0');
INSERT INTO `points_games` VALUES ('2', '1', '65', '0');
INSERT INTO `points_games` VALUES ('2', '1', '66', '0');
INSERT INTO `points_games` VALUES ('2', '1', '67', '0');
INSERT INTO `points_games` VALUES ('2', '1', '68', '0');
INSERT INTO `points_games` VALUES ('2', '1', '69', '0');
INSERT INTO `points_games` VALUES ('2', '1', '70', '0');
INSERT INTO `points_games` VALUES ('2', '1', '71', '0');
INSERT INTO `points_games` VALUES ('2', '1', '72', '0');
INSERT INTO `points_games` VALUES ('2', '1', '73', '0');
INSERT INTO `points_games` VALUES ('2', '1', '74', '0');
INSERT INTO `points_games` VALUES ('2', '1', '75', '0');
INSERT INTO `points_games` VALUES ('2', '1', '76', '0');
INSERT INTO `points_games` VALUES ('2', '1', '77', '0');
INSERT INTO `points_games` VALUES ('2', '1', '78', '0');
INSERT INTO `points_games` VALUES ('2', '1', '79', '0');
INSERT INTO `points_games` VALUES ('2', '1', '80', '0');
INSERT INTO `points_games` VALUES ('2', '1', '81', '0');
INSERT INTO `points_games` VALUES ('2', '1', '82', '0');
INSERT INTO `points_games` VALUES ('2', '1', '83', '0');
INSERT INTO `points_games` VALUES ('2', '1', '84', '0');
INSERT INTO `points_games` VALUES ('2', '1', '85', '0');
INSERT INTO `points_games` VALUES ('2', '1', '86', '0');
INSERT INTO `points_games` VALUES ('2', '1', '87', '0');
INSERT INTO `points_games` VALUES ('2', '1', '88', '0');
INSERT INTO `points_games` VALUES ('2', '1', '89', '0');
INSERT INTO `points_games` VALUES ('2', '1', '90', '0');
INSERT INTO `points_games` VALUES ('2', '1', '91', '0');
INSERT INTO `points_games` VALUES ('2', '1', '92', '0');
INSERT INTO `points_games` VALUES ('2', '1', '93', '0');
INSERT INTO `points_games` VALUES ('2', '1', '94', '0');
INSERT INTO `points_games` VALUES ('2', '1', '95', '0');
INSERT INTO `points_games` VALUES ('2', '1', '96', '0');
INSERT INTO `points_games` VALUES ('2', '1', '97', '0');
INSERT INTO `points_games` VALUES ('2', '1', '98', '0');
INSERT INTO `points_games` VALUES ('2', '1', '99', '0');
INSERT INTO `points_games` VALUES ('2', '1', '100', '0');

-- ----------------------------
-- Table structure for points_users
-- ----------------------------
DROP TABLE IF EXISTS `points_users`;
CREATE TABLE `points_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  CONSTRAINT `points_users_ibfk_1` FOREIGN KEY (`user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of points_users
-- ----------------------------
INSERT INTO `points_users` VALUES ('1', '1', '0', 'BASE');
INSERT INTO `points_users` VALUES ('2', '2', '0', 'BASE');

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES ('1', 'GOD');
INSERT INTO `roles` VALUES ('2', 'ADMIN');
INSERT INTO `roles` VALUES ('3', 'USER');

-- ----------------------------
-- Table structure for teams_participants
-- ----------------------------
DROP TABLE IF EXISTS `teams_participants`;
CREATE TABLE `teams_participants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of teams_participants
-- ----------------------------
INSERT INTO `teams_participants` VALUES ('1', 'EQUIPO ALFA');
INSERT INTO `teams_participants` VALUES ('2', 'EQUIPO BETA');

-- ----------------------------
-- Table structure for teams_users
-- ----------------------------
DROP TABLE IF EXISTS `teams_users`;
CREATE TABLE `teams_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `color` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of teams_users
-- ----------------------------
INSERT INTO `teams_users` VALUES ('1', 'JAJA', 'c-red');

-- ----------------------------
-- Table structure for type_games
-- ----------------------------
DROP TABLE IF EXISTS `type_games`;
CREATE TABLE `type_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of type_games
-- ----------------------------
INSERT INTO `type_games` VALUES ('1', 'Torneo');
INSERT INTO `type_games` VALUES ('2', 'Producción');
INSERT INTO `type_games` VALUES ('3', 'Minijuego');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nick` varchar(20) NOT NULL,
  `email` varchar(60) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `team` int(11) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `cryp` varchar(64) DEFAULT NULL,
  `date_c` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role`),
  KEY `team` (`team`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`id`),
  CONSTRAINT `users_ibfk_2` FOREIGN KEY (`team`) REFERENCES `teams_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'lxlDanilxl', 'lxlDanilxlpro@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '1', '1', 'f56bb2bec93bc995de59478a9063ce4d', '2018-01-29 22:08:56');
INSERT INTO `users` VALUES ('2', 'pruebas', '', '', '1', '3', null, '2018-01-29 23:50:37');
