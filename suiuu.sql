/*
 Navicat MySQL Data Transfer

 Source Server         : Suiuu
 Source Server Version : 50623
 Source Host           : localhost
 Source Database       : suiuu

 Target Server Version : 50623
 File Encoding         : utf-8

 Date: 08/13/2015 11:23:32 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `Publihser_order_message`
-- ----------------------------
DROP TABLE IF EXISTS `Publihser_order_message`;
CREATE TABLE `Publihser_order_message` (
  `messageId` int(11) NOT NULL,
  `orderId` int(11) DEFAULT NULL,
  `tripId` int(11) DEFAULT NULL,
  `publihserId` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`messageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `Sys_Module`
-- ----------------------------
DROP TABLE IF EXISTS `Sys_Module`;
CREATE TABLE `Sys_Module` (
  `moduleId` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `logo` varchar(50) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
  `forder` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `parentId` int(11) DEFAULT NULL,
  `path` varchar(500) DEFAULT NULL,
  `info` varchar(200) DEFAULT NULL,
  `hasChild` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`moduleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `Sys_dept`
-- ----------------------------
DROP TABLE IF EXISTS `Sys_dept`;
CREATE TABLE `Sys_dept` (
  `deptId` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `forder` int(11) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `isDel` tinyint(4) DEFAULT NULL,
  `path` varchar(50) DEFAULT NULL,
  `parentId` int(11) DEFAULT NULL,
  `manaerId` int(11) DEFAULT NULL,
  `hasChild` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`deptId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `Sys_dictionary`
-- ----------------------------
DROP TABLE IF EXISTS `Sys_dictionary`;
CREATE TABLE `Sys_dictionary` (
  `dictionaryId` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `value` varchar(50) DEFAULT NULL,
  `forder` int(11) DEFAULT NULL,
  PRIMARY KEY (`dictionaryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `Sys_log`
-- ----------------------------
DROP TABLE IF EXISTS `Sys_log`;
CREATE TABLE `Sys_log` (
  `logId` int(11) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `logTime` datetime DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `url` varchar(50) DEFAULT NULL,
  `content` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `Sys_power`
-- ----------------------------
DROP TABLE IF EXISTS `Sys_power`;
CREATE TABLE `Sys_power` (
  `powerId` int(11) NOT NULL,
  `kind` int(11) DEFAULT NULL,
  `kindId` int(11) DEFAULT NULL,
  PRIMARY KEY (`powerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `Sys_role`
-- ----------------------------
DROP TABLE IF EXISTS `Sys_role`;
CREATE TABLE `Sys_role` (
  `roleId` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `forder` int(11) DEFAULT NULL,
  PRIMARY KEY (`roleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='ϵͳ??ɫ??';

-- ----------------------------
--  Table structure for `Sys_role_power`
-- ----------------------------
DROP TABLE IF EXISTS `Sys_role_power`;
CREATE TABLE `Sys_role_power` (
  `rolePowerId` int(11) NOT NULL,
  `roleId` int(11) DEFAULT NULL,
  `powerId` int(11) DEFAULT NULL,
  PRIMARY KEY (`rolePowerId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `Sys_user_dept`
-- ----------------------------
DROP TABLE IF EXISTS `Sys_user_dept`;
CREATE TABLE `Sys_user_dept` (
  `userDeptId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `deptId` int(11) DEFAULT NULL,
  PRIMARY KEY (`userDeptId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `Sys_user_role`
-- ----------------------------
DROP TABLE IF EXISTS `Sys_user_role`;
CREATE TABLE `Sys_user_role` (
  `userRoleId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `roleId` int(11) DEFAULT NULL,
  PRIMARY KEY (`userRoleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `Travel_destination_trip`
-- ----------------------------
DROP TABLE IF EXISTS `Travel_destination_trip`;
CREATE TABLE `Travel_destination_trip` (
  `destinationTripId` int(11) NOT NULL,
  `destinationId` int(11) DEFAULT NULL,
  `tripId` int(11) DEFAULT NULL,
  PRIMARY KEY (`destinationTripId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `User_base_validate`
-- ----------------------------
DROP TABLE IF EXISTS `User_base_validate`;
CREATE TABLE `User_base_validate` (
  `userId` int(11) NOT NULL,
  `phone` tinyint(4) DEFAULT NULL,
  `email` tinyint(4) DEFAULT NULL,
  `idCard` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `User_favorite`
-- ----------------------------
DROP TABLE IF EXISTS `User_favorite`;
CREATE TABLE `User_favorite` (
  `favouriteId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `relateId` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`favouriteId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `all_totalize`
-- ----------------------------
DROP TABLE IF EXISTS `all_totalize`;
CREATE TABLE `all_totalize` (
  `totalId` int(11) NOT NULL AUTO_INCREMENT,
  `totalize` int(50) DEFAULT NULL,
  `tType` int(11) DEFAULT NULL,
  `rId` int(11) DEFAULT NULL,
  PRIMARY KEY (`totalId`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `article_comment`
-- ----------------------------
DROP TABLE IF EXISTS `article_comment`;
CREATE TABLE `article_comment` (
  `commentId` int(11) NOT NULL AUTO_INCREMENT,
  `articleId` int(11) NOT NULL,
  `userSign` varchar(50) NOT NULL,
  `content` longtext NOT NULL,
  `replayCommentId` int(11) DEFAULT NULL,
  `supportCount` int(11) DEFAULT NULL,
  `opposeCount` int(11) DEFAULT NULL,
  `cTime` datetime DEFAULT NULL,
  `rTitle` varchar(250) DEFAULT NULL,
  `rUserSign` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`commentId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `article_info`
-- ----------------------------
DROP TABLE IF EXISTS `article_info`;
CREATE TABLE `article_info` (
  `articleId` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `titleImg` varchar(200) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `content` text,
  `createUserId` int(11) DEFAULT NULL,
  `createTime` datetime DEFAULT NULL,
  `lastUpdateTime` datetime NOT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`articleId`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `circle_article`
-- ----------------------------
DROP TABLE IF EXISTS `circle_article`;
CREATE TABLE `circle_article` (
  `articleId` int(11) NOT NULL AUTO_INCREMENT,
  `cId` int(11) DEFAULT NULL COMMENT '圈子主题id',
  `aTitle` varchar(255) DEFAULT NULL,
  `aContent` text,
  `aImg` varchar(255) DEFAULT NULL,
  `aCmtCount` int(11) DEFAULT '0' COMMENT '评论总数',
  `aSupportCount` int(11) DEFAULT '0',
  `aCreateUserSign` varchar(50) DEFAULT NULL,
  `aCreateTime` datetime DEFAULT NULL,
  `aLastUpdateTime` datetime DEFAULT NULL,
  `aStatus` int(1) DEFAULT '1' COMMENT '1正常2删除',
  `aAddr` varchar(255) DEFAULT NULL,
  `aImgList` text,
  `aType` int(11) DEFAULT NULL COMMENT '发帖类型， 随问，随记，随拍',
  `cAddrId` int(11) DEFAULT NULL COMMENT '圈子地点id',
  PRIMARY KEY (`articleId`)
) ENGINE=InnoDB AUTO_INCREMENT=145 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `circle_article_comment`
-- ----------------------------
DROP TABLE IF EXISTS `circle_article_comment`;
CREATE TABLE `circle_article_comment` (
  `commentId` int(11) NOT NULL AUTO_INCREMENT,
  `userSign` varchar(50) NOT NULL,
  `content` longtext,
  `relativeCommentId` int(11) DEFAULT NULL,
  `supportCount` int(11) DEFAULT NULL,
  `opposeCount` int(11) DEFAULT NULL,
  `cTime` datetime DEFAULT NULL,
  `cStatus` int(1) DEFAULT '1' COMMENT '2删除1正常',
  `cLastTime` datetime DEFAULT NULL,
  `articleId` int(11) DEFAULT NULL,
  `rUserSign` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`commentId`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `city`
-- ----------------------------
DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(255) DEFAULT NULL,
  `ename` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `countryId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4166 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `country`
-- ----------------------------
DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(255) DEFAULT NULL,
  `ename` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `areaCode` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4129 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `destination_info`
-- ----------------------------
DROP TABLE IF EXISTS `destination_info`;
CREATE TABLE `destination_info` (
  `destinationId` int(11) NOT NULL AUTO_INCREMENT,
  `countryId` int(11) DEFAULT NULL,
  `cityId` int(11) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `titleImg` varchar(200) DEFAULT NULL,
  `intro` text NOT NULL,
  `createUserId` int(11) DEFAULT NULL,
  `createTime` datetime DEFAULT NULL,
  `lastUpdateTime` datetime NOT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`destinationId`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `destination_scenic`
-- ----------------------------
DROP TABLE IF EXISTS `destination_scenic`;
CREATE TABLE `destination_scenic` (
  `scenicId` int(11) NOT NULL AUTO_INCREMENT,
  `destinationId` int(11) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `titleImg` varchar(200) DEFAULT NULL,
  `intro` text NOT NULL,
  `beginTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL,
  `lon` varchar(255) NOT NULL,
  `lat` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  PRIMARY KEY (`scenicId`)
) ENGINE=InnoDB AUTO_INCREMENT=234 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `recommend_list`
-- ----------------------------
DROP TABLE IF EXISTS `recommend_list`;
CREATE TABLE `recommend_list` (
  `recommendId` int(11) NOT NULL AUTO_INCREMENT,
  `relativeId` int(11) NOT NULL COMMENT '相对id ',
  `relativeType` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '是否启用1启用2关闭',
  `rImg` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`recommendId`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `search`
-- ----------------------------
DROP TABLE IF EXISTS `search`;
CREATE TABLE `search` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Table structure for `sysFunction`
-- ----------------------------
DROP TABLE IF EXISTS `sysFunction`;
CREATE TABLE `sysFunction` (
  `functionId` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `page` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`functionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `sys_circle_sort`
-- ----------------------------
DROP TABLE IF EXISTS `sys_circle_sort`;
CREATE TABLE `sys_circle_sort` (
  `cId` int(11) NOT NULL AUTO_INCREMENT,
  `cType` int(1) DEFAULT NULL,
  `cName` varchar(255) DEFAULT NULL,
  `cpic` varchar(255) DEFAULT NULL,
  `cStatus` int(1) DEFAULT NULL,
  PRIMARY KEY (`cId`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `sys_user`
-- ----------------------------
DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE `sys_user` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `registerTime` datetime DEFAULT NULL,
  `registerIp` varchar(50) NOT NULL,
  `lastLoginTime` datetime DEFAULT NULL,
  `lastLoginIp` varchar(50) DEFAULT NULL,
  `sex` int(1) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `isAdmin` tinyint(4) DEFAULT NULL,
  `isEnabled` tinyint(4) DEFAULT NULL,
  `isDelete` tinyint(4) DEFAULT NULL,
  `userSign` varchar(32) NOT NULL,
  `errorCount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userId`),
  UNIQUE KEY `uq_username` (`username`),
  KEY `uq_userSign` (`userSign`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `tag_list`
-- ----------------------------
DROP TABLE IF EXISTS `tag_list`;
CREATE TABLE `tag_list` (
  `tId` int(11) NOT NULL AUTO_INCREMENT,
  `tName` varchar(50) NOT NULL,
  `tType` int(1) NOT NULL,
  PRIMARY KEY (`tId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `travel_trip`
-- ----------------------------
DROP TABLE IF EXISTS `travel_trip`;
CREATE TABLE `travel_trip` (
  `tripId` int(11) NOT NULL AUTO_INCREMENT,
  `createPublisherId` varchar(50) DEFAULT NULL,
  `createTime` datetime DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `titleImg` varchar(200) DEFAULT NULL,
  `countryId` int(50) DEFAULT NULL,
  `cityId` int(50) DEFAULT NULL,
  `lon` varchar(255) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `basePrice` decimal(10,2) DEFAULT NULL,
  `basePriceType` int(11) NOT NULL DEFAULT '1',
  `maxUserCount` int(11) DEFAULT NULL,
  `isAirplane` tinyint(4) NOT NULL DEFAULT '0',
  `isHotel` tinyint(4) DEFAULT NULL,
  `score` float DEFAULT NULL,
  `tripCount` int(11) NOT NULL,
  `startTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL,
  `travelTime` int(11) DEFAULT NULL,
  `travelTimeType` int(11) DEFAULT NULL,
  `intro` varchar(500) NOT NULL,
  `info` text,
  `tags` varchar(200) DEFAULT NULL,
  `status` int(11) NOT NULL,
  `commentCount` int(11) NOT NULL DEFAULT '0',
  `collectCount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tripId`,`isAirplane`)
) ENGINE=InnoDB AUTO_INCREMENT=216 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `travel_trip_apply`
-- ----------------------------
DROP TABLE IF EXISTS `travel_trip_apply`;
CREATE TABLE `travel_trip_apply` (
  `applyId` int(11) NOT NULL AUTO_INCREMENT,
  `tripId` int(11) DEFAULT NULL,
  `publisherId` int(11) NOT NULL,
  `sendTime` datetime NOT NULL,
  `info` text,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`applyId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `travel_trip_comment`
-- ----------------------------
DROP TABLE IF EXISTS `travel_trip_comment`;
CREATE TABLE `travel_trip_comment` (
  `commentId` int(11) NOT NULL AUTO_INCREMENT,
  `tripId` int(11) DEFAULT NULL,
  `userSign` varchar(255) DEFAULT NULL,
  `content` longtext,
  `replayCommentId` int(11) DEFAULT NULL,
  `supportCount` int(11) DEFAULT NULL,
  `opposeCount` int(11) DEFAULT NULL,
  `isTravel` tinyint(4) DEFAULT NULL,
  `rTitle` varchar(255) DEFAULT NULL,
  `cTime` datetime DEFAULT NULL,
  `rUserSign` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`commentId`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `travel_trip_detail`
-- ----------------------------
DROP TABLE IF EXISTS `travel_trip_detail`;
CREATE TABLE `travel_trip_detail` (
  `detailId` int(11) NOT NULL AUTO_INCREMENT,
  `tripId` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`detailId`)
) ENGINE=InnoDB AUTO_INCREMENT=2650 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `travel_trip_highlight`
-- ----------------------------
DROP TABLE IF EXISTS `travel_trip_highlight`;
CREATE TABLE `travel_trip_highlight` (
  `hlId` int(11) NOT NULL AUTO_INCREMENT,
  `tripId` int(11) NOT NULL,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY (`hlId`)
) ENGINE=InnoDB AUTO_INCREMENT=928 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `travel_trip_picture`
-- ----------------------------
DROP TABLE IF EXISTS `travel_trip_picture`;
CREATE TABLE `travel_trip_picture` (
  `picId` int(11) NOT NULL AUTO_INCREMENT,
  `tripId` int(11) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `url` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`picId`)
) ENGINE=InnoDB AUTO_INCREMENT=4391 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `travel_trip_price`
-- ----------------------------
DROP TABLE IF EXISTS `travel_trip_price`;
CREATE TABLE `travel_trip_price` (
  `priceId` int(11) NOT NULL AUTO_INCREMENT,
  `tripId` int(11) DEFAULT NULL,
  `minCount` int(11) DEFAULT NULL,
  `maxCount` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`priceId`)
) ENGINE=InnoDB AUTO_INCREMENT=566 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `travel_trip_publisher`
-- ----------------------------
DROP TABLE IF EXISTS `travel_trip_publisher`;
CREATE TABLE `travel_trip_publisher` (
  `tripPublisherId` int(11) NOT NULL AUTO_INCREMENT,
  `tripId` int(11) DEFAULT NULL,
  `publisherId` int(11) DEFAULT NULL,
  PRIMARY KEY (`tripPublisherId`)
) ENGINE=InnoDB AUTO_INCREMENT=249 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `travel_trip_recommend`
-- ----------------------------
DROP TABLE IF EXISTS `travel_trip_recommend`;
CREATE TABLE `travel_trip_recommend` (
  `recommendId` int(11) NOT NULL AUTO_INCREMENT,
  `tripId` int(11) NOT NULL,
  `userId` varchar(50) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`recommendId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `travel_trip_scenic`
-- ----------------------------
DROP TABLE IF EXISTS `travel_trip_scenic`;
CREATE TABLE `travel_trip_scenic` (
  `scenicId` int(11) NOT NULL AUTO_INCREMENT,
  `tripId` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `lon` varchar(255) NOT NULL,
  `lat` varchar(255) NOT NULL,
  PRIMARY KEY (`scenicId`)
) ENGINE=InnoDB AUTO_INCREMENT=1850 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `travel_trip_service`
-- ----------------------------
DROP TABLE IF EXISTS `travel_trip_service`;
CREATE TABLE `travel_trip_service` (
  `serviceId` int(11) NOT NULL AUTO_INCREMENT,
  `tripId` int(11) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`serviceId`)
) ENGINE=InnoDB AUTO_INCREMENT=503 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `travel_trip_special`
-- ----------------------------
DROP TABLE IF EXISTS `travel_trip_special`;
CREATE TABLE `travel_trip_special` (
  `specialId` int(11) NOT NULL AUTO_INCREMENT,
  `tripId` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `info` varchar(500) NOT NULL,
  `picUrl` varchar(200) NOT NULL,
  PRIMARY KEY (`specialId`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `user_access`
-- ----------------------------
DROP TABLE IF EXISTS `user_access`;
CREATE TABLE `user_access` (
  `accessId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(50) NOT NULL,
  `openId` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`accessId`),
  UNIQUE KEY `openId` (`openId`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_account`
-- ----------------------------
DROP TABLE IF EXISTS `user_account`;
CREATE TABLE `user_account` (
  `accountId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(50) NOT NULL,
  `account` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `type` int(11) NOT NULL,
  `createTime` datetime NOT NULL,
  `updateTime` datetime DEFAULT NULL,
  `isDel` tinyint(4) NOT NULL,
  PRIMARY KEY (`accountId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_account_record`
-- ----------------------------
DROP TABLE IF EXISTS `user_account_record`;
CREATE TABLE `user_account_record` (
  `accountRecordId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(50) NOT NULL,
  `type` int(11) NOT NULL,
  `relateId` int(11) DEFAULT NULL,
  `info` varchar(200) DEFAULT NULL,
  `money` decimal(10,2) NOT NULL,
  `recordTime` datetime NOT NULL,
  PRIMARY KEY (`accountRecordId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_aptitude`
-- ----------------------------
DROP TABLE IF EXISTS `user_aptitude`;
CREATE TABLE `user_aptitude` (
  `aptitudeId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(50) NOT NULL,
  `applyTime` datetime NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`aptitudeId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `user_attention`
-- ----------------------------
DROP TABLE IF EXISTS `user_attention`;
CREATE TABLE `user_attention` (
  `attentionId` int(11) NOT NULL AUTO_INCREMENT,
  `relativeId` int(11) DEFAULT NULL COMMENT '关注的相对id',
  `relativeType` int(11) DEFAULT NULL COMMENT '相对类型，1 关注用户 2 关注圈子 3关注圈子文章 4 关注随游文章',
  `status` int(1) DEFAULT NULL,
  `addTime` datetime DEFAULT NULL COMMENT '取消关注时间',
  `deleteTime` datetime DEFAULT NULL COMMENT '取消关注时间',
  `userSign` varchar(50) DEFAULT NULL COMMENT '关注用户',
  PRIMARY KEY (`attentionId`)
) ENGINE=InnoDB AUTO_INCREMENT=200 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_base`
-- ----------------------------
DROP TABLE IF EXISTS `user_base`;
CREATE TABLE `user_base` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `surname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `areaCode` varchar(50) DEFAULT NULL,
  `sex` int(11) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `headImg` varchar(200) DEFAULT NULL,
  `hobby` varchar(100) DEFAULT NULL,
  `profession` varchar(50) DEFAULT NULL,
  `school` varchar(50) DEFAULT NULL,
  `qq` varchar(50) DEFAULT NULL,
  `wechat` varchar(50) DEFAULT NULL,
  `intro` varchar(50) DEFAULT NULL,
  `info` longtext,
  `travelCount` int(11) DEFAULT NULL,
  `registerIp` varchar(50) DEFAULT NULL,
  `registerTime` datetime DEFAULT NULL,
  `lastLoginIp` varchar(50) DEFAULT NULL,
  `lastLoginTime` datetime DEFAULT NULL,
  `userSign` varchar(32) NOT NULL,
  `isPublisher` tinyint(4) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `countryId` int(11) DEFAULT NULL,
  `cityId` int(11) DEFAULT NULL,
  `lon` varchar(255) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `version` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userId`),
  UNIQUE KEY `uq_userSign` (`userSign`),
  UNIQUE KEY `uq_phone` (`phone`),
  UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=528 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_card`
-- ----------------------------
DROP TABLE IF EXISTS `user_card`;
CREATE TABLE `user_card` (
  `cardId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(255) NOT NULL,
  `number` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `img` varchar(500) NOT NULL,
  `updateTime` datetime NOT NULL,
  `authHistory` text NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cardId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `user_cash_record`
-- ----------------------------
DROP TABLE IF EXISTS `user_cash_record`;
CREATE TABLE `user_cash_record` (
  `cashId` int(11) NOT NULL AUTO_INCREMENT,
  `cashNumber` varchar(100) DEFAULT NULL,
  `userId` varchar(50) NOT NULL,
  `money` decimal(10,2) NOT NULL,
  `balance` decimal(10,2) NOT NULL,
  `account` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `type` int(11) NOT NULL,
  `createTime` datetime NOT NULL,
  `finishTime` datetime DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`cashId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `user_feedback`;
CREATE TABLE `user_feedback` (
  `feedbackId` int(11) NOT NULL AUTO_INCREMENT,
  `userSign` varchar(100) DEFAULT NULL,
  `content` text,
  `imgList` text,
  `createTime` datetime DEFAULT NULL,
  `fType` int(11) DEFAULT NULL,
  `fLevel` int(1) DEFAULT NULL,
  `fResult` int(1) DEFAULT NULL,
  `fName` varchar(250) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `fAddr` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`feedbackId`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_message`
-- ----------------------------
DROP TABLE IF EXISTS `user_message`;
CREATE TABLE `user_message` (
  `messageId` int(11) NOT NULL AUTO_INCREMENT,
  `sessionkey` varchar(50) NOT NULL,
  `receiveId` varchar(50) DEFAULT NULL,
  `senderId` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT '',
  `content` longtext,
  `sendTime` datetime DEFAULT NULL,
  `readTime` datetime DEFAULT NULL,
  `isRead` tinyint(4) DEFAULT NULL,
  `isShield` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`messageId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_message_remind`
-- ----------------------------
DROP TABLE IF EXISTS `user_message_remind`;
CREATE TABLE `user_message_remind` (
  `remindId` int(11) NOT NULL AUTO_INCREMENT,
  `relativeId` int(11) DEFAULT NULL,
  `relativeUserSign` varchar(50) DEFAULT NULL COMMENT '相对用户',
  `relativeType` int(11) DEFAULT NULL,
  `createUserSign` varchar(50) DEFAULT NULL,
  `createTime` datetime DEFAULT NULL,
  `rStatus` int(1) DEFAULT NULL COMMENT '状态',
  `readTime` datetime DEFAULT NULL,
  `rType` int(1) DEFAULT NULL,
  `content` varchar(200) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`remindId`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_message_session`
-- ----------------------------
DROP TABLE IF EXISTS `user_message_session`;
CREATE TABLE `user_message_session` (
  `sessionId` int(11) NOT NULL AUTO_INCREMENT,
  `sessionKey` varchar(50) NOT NULL,
  `userId` varchar(50) NOT NULL,
  `relateId` varchar(50) NOT NULL,
  `lastConcatTime` datetime NOT NULL,
  `lastContentInfo` longtext NOT NULL,
  `isRead` tinyint(4) NOT NULL,
  PRIMARY KEY (`sessionId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_message_setting`
-- ----------------------------
DROP TABLE IF EXISTS `user_message_setting`;
CREATE TABLE `user_message_setting` (
  `settingId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(50) DEFAULT NULL,
  `status` int(4) DEFAULT NULL,
  `shieldIds` text,
  PRIMARY KEY (`settingId`),
  UNIQUE KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_order_comment`
-- ----------------------------
DROP TABLE IF EXISTS `user_order_comment`;
CREATE TABLE `user_order_comment` (
  `orderCommentId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(50) DEFAULT NULL,
  `publisherId` int(11) DEFAULT NULL,
  `tripId` int(11) NOT NULL,
  `orderId` int(11) DEFAULT NULL,
  `content` text,
  `commentTime` datetime NOT NULL,
  `tripScore` float DEFAULT NULL,
  `publisherScore` float NOT NULL,
  PRIMARY KEY (`orderCommentId`),
  UNIQUE KEY `orderId` (`orderId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_order_info`
-- ----------------------------
DROP TABLE IF EXISTS `user_order_info`;
CREATE TABLE `user_order_info` (
  `orderId` int(11) NOT NULL AUTO_INCREMENT,
  `orderNumber` varchar(50) DEFAULT NULL,
  `userId` varchar(50) DEFAULT NULL,
  `tripId` int(11) DEFAULT NULL,
  `personCount` int(11) DEFAULT NULL,
  `beginDate` date NOT NULL,
  `startTime` time NOT NULL,
  `basePrice` decimal(10,2) DEFAULT NULL,
  `servicePrice` decimal(10,2) DEFAULT NULL,
  `totalPrice` decimal(10,2) DEFAULT NULL,
  `serviceInfo` varchar(1000) NOT NULL,
  `tripJsonInfo` text NOT NULL,
  `createTime` datetime NOT NULL,
  `status` int(11) DEFAULT NULL,
  `isDel` tinyint(4) NOT NULL,
  PRIMARY KEY (`orderId`)
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_order_publisher`
-- ----------------------------
DROP TABLE IF EXISTS `user_order_publisher`;
CREATE TABLE `user_order_publisher` (
  `orderPublisherId` int(11) NOT NULL AUTO_INCREMENT,
  `publisherId` int(11) DEFAULT NULL,
  `orderId` int(11) DEFAULT NULL,
  `isFinished` tinyint(4) DEFAULT NULL,
  `createTime` datetime NOT NULL,
  `finishTime` datetime DEFAULT NULL,
  PRIMARY KEY (`orderPublisherId`),
  UNIQUE KEY `orderId` (`orderId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_order_publisher_cancel`
-- ----------------------------
DROP TABLE IF EXISTS `user_order_publisher_cancel`;
CREATE TABLE `user_order_publisher_cancel` (
  `publisherCancelId` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) NOT NULL,
  `publisherId` int(11) NOT NULL,
  `cancelTime` datetime NOT NULL,
  `content` text NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`publisherCancelId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_order_publisher_ignore`
-- ----------------------------
DROP TABLE IF EXISTS `user_order_publisher_ignore`;
CREATE TABLE `user_order_publisher_ignore` (
  `ignoreId` int(11) NOT NULL AUTO_INCREMENT,
  `publisherId` int(11) NOT NULL,
  `orderId` int(11) NOT NULL,
  PRIMARY KEY (`ignoreId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_order_refund`
-- ----------------------------
DROP TABLE IF EXISTS `user_order_refund`;
CREATE TABLE `user_order_refund` (
  `refundId` int(11) NOT NULL AUTO_INCREMENT,
  `refundNumber` varchar(50) DEFAULT NULL,
  `accountInfo` varchar(100) NOT NULL,
  `userId` varchar(50) DEFAULT NULL,
  `orderId` int(11) DEFAULT NULL,
  `tripId` int(11) NOT NULL,
  `applyId` int(11) NOT NULL,
  `refundTime` datetime DEFAULT NULL,
  `money` decimal(10,2) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`refundId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_order_refund_apply`
-- ----------------------------
DROP TABLE IF EXISTS `user_order_refund_apply`;
CREATE TABLE `user_order_refund_apply` (
  `refundApplyId` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) NOT NULL,
  `userId` varchar(50) NOT NULL,
  `tripId` int(11) NOT NULL,
  `applyContent` text NOT NULL,
  `applyTime` datetime NOT NULL,
  `replyTime` datetime DEFAULT NULL,
  `replyContent` text,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`refundApplyId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_pay_record`
-- ----------------------------
DROP TABLE IF EXISTS `user_pay_record`;
CREATE TABLE `user_pay_record` (
  `payId` int(11) NOT NULL AUTO_INCREMENT,
  `orderId` int(11) DEFAULT NULL,
  `payNumber` varchar(50) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT NULL,
  `payTime` datetime NOT NULL,
  PRIMARY KEY (`payId`),
  UNIQUE KEY `orderId` (`orderId`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_photo`
-- ----------------------------
DROP TABLE IF EXISTS `user_photo`;
CREATE TABLE `user_photo` (
  `photoId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(50) NOT NULL,
  `url` varchar(200) NOT NULL,
  `createTime` datetime NOT NULL,
  PRIMARY KEY (`photoId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `user_publisher`
-- ----------------------------
DROP TABLE IF EXISTS `user_publisher`;
CREATE TABLE `user_publisher` (
  `userPublisherId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(50) DEFAULT NULL,
  `countryId` int(11) DEFAULT NULL,
  `cityId` int(11) DEFAULT NULL,
  `lon` varchar(255) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `idCard` varchar(50) DEFAULT NULL,
  `idCardImg` varchar(200) DEFAULT NULL,
  `kind` varchar(50) DEFAULT NULL,
  `tripCount` int(11) DEFAULT NULL,
  `leadCount` int(11) DEFAULT NULL,
  `registerTime` datetime NOT NULL,
  `lastUpdateTime` datetime NOT NULL,
  `score` float NOT NULL,
  PRIMARY KEY (`userPublisherId`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `user_recommend`
-- ----------------------------
DROP TABLE IF EXISTS `user_recommend`;
CREATE TABLE `user_recommend` (
  `userRecommendId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(50) NOT NULL,
  PRIMARY KEY (`userRecommendId`),
  UNIQUE KEY `userId` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `wechat_news_list`
-- ----------------------------
DROP TABLE IF EXISTS `wechat_news_list`;
CREATE TABLE `wechat_news_list` (
  `newsId` int(100) NOT NULL AUTO_INCREMENT,
  `nTid` int(11) NOT NULL,
  `nTitle` varchar(255) NOT NULL,
  `nIntro` varchar(255) NOT NULL,
  `nCover` varchar(255) NOT NULL,
  `nContent` text NOT NULL,
  `nAntistop` varchar(255) NOT NULL,
  `nUrl` varchar(255) NOT NULL,
  `nType` int(11) NOT NULL,
  `nStatus` int(10) NOT NULL,
  PRIMARY KEY (`newsId`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `wechat_order_list`
-- ----------------------------
DROP TABLE IF EXISTS `wechat_order_list`;
CREATE TABLE `wechat_order_list` (
  `wOrderId` int(11) NOT NULL AUTO_INCREMENT,
  `wOrderSite` text,
  `wOrderTimeList` text,
  `wOrderContent` text,
  `wUserSign` varchar(255) DEFAULT NULL,
  `wStatus` int(11) DEFAULT NULL,
  `wRelativeSign` varchar(50) DEFAULT NULL,
  `wCreateTime` datetime DEFAULT NULL,
  `wLastTime` datetime DEFAULT NULL,
  `wOrderNumber` varchar(50) NOT NULL,
  `wUserNumber` int(10) NOT NULL,
  `wDetails` text,
  `wPhone` varchar(20) NOT NULL,
  `openId` varchar(50) NOT NULL,
  `wMoney` decimal(10,2) DEFAULT NULL,
  `isDel` tinyint(4) NOT NULL,
  PRIMARY KEY (`wOrderId`),
  UNIQUE KEY `wOrderNumber` (`wOrderNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `wechat_order_refund`
-- ----------------------------
DROP TABLE IF EXISTS `wechat_order_refund`;
CREATE TABLE `wechat_order_refund` (
  `refundId` int(11) NOT NULL AUTO_INCREMENT,
  `refundReason` text,
  `userSign` varchar(50) DEFAULT NULL,
  `orderNumber` varchar(50) DEFAULT NULL,
  `refundTime` datetime DEFAULT NULL,
  `money` decimal(10,0) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `updateUserSign` varchar(50) DEFAULT NULL,
  `lastTime` datetime DEFAULT NULL,
  `updateReason` text,
  `isDel` int(1) DEFAULT NULL,
  PRIMARY KEY (`refundId`),
  UNIQUE KEY `rderNumber` (`orderNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `wechat_pay_record`
-- ----------------------------
DROP TABLE IF EXISTS `wechat_pay_record`;
CREATE TABLE `wechat_pay_record` (
  `payId` int(11) NOT NULL AUTO_INCREMENT,
  `orderNumber` varchar(50) DEFAULT NULL,
  `payNumber` varchar(50) NOT NULL,
  `type` int(11) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT NULL,
  `payTime` datetime NOT NULL,
  PRIMARY KEY (`payId`),
  UNIQUE KEY `orderId` (`orderNumber`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Table structure for `wechat_user_info`
-- ----------------------------
DROP TABLE IF EXISTS `wechat_user_info`;
CREATE TABLE `wechat_user_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openId` varchar(255) DEFAULT NULL,
  `userSign` varchar(100) DEFAULT NULL,
  `unionID` varchar(200) DEFAULT NULL,
  `v_nickname` varchar(200) DEFAULT NULL,
  `v_sex` int(1) DEFAULT NULL,
  `v_city` varchar(200) DEFAULT NULL,
  `v_country` varchar(200) DEFAULT NULL,
  `v_province` varchar(200) DEFAULT NULL,
  `v_language` varchar(50) DEFAULT NULL,
  `v_headimgurl` varchar(255) DEFAULT NULL,
  `v_subscribe_time` int(50) DEFAULT NULL,
  `v_remark` varchar(250) DEFAULT NULL,
  `v_groupid` int(11) DEFAULT NULL,
  `v_createTime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=249 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Procedure structure for `pay_order`
-- ----------------------------
DROP PROCEDURE IF EXISTS `pay_order`;
delimiter ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pay_order`(IN oNumber varchar(50), IN pNumber varchar(50), IN type int, IN state int, OUT rst int)
    DETERMINISTIC
BEGIN 

	DECLARE CONTINUE HANDLER FOR SQLEXCEPTION SET rst=-1;

	SET rst=1;			/** 返回结果 **/
	SET @updateCount=0; /** 更新条数 **/
	/*
	 * 执行流程
	 *1.修改订单状态
	 *2.添加用户付款记录
	 */
	UPDATE user_order_info SET status=state WHERE orderNumber=oNumber;
	SELECT ROW_COUNT() INTO @updateCount;
	
	IF @updateCount=1 THEN

		INSERT INTO user_pay_record (orderId,payNumber,type,money,payTime) 	
		SELECT orderId,pNumber,type,totalPrice,NOW() FROM user_order_info 
		WHERE orderNumber=oNumber;

	ELSE 
		SET rst=-2;
	END IF;


	IF rst=1 THEN
		COMMIT;
	ELSE 
		ROLLBACK;
	END IF;


END
 ;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
