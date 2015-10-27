/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : blank

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-08-28 10:14:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for st_category
-- ----------------------------
DROP TABLE IF EXISTS `st_category`;
CREATE TABLE `st_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned DEFAULT NULL,
  `sort` smallint(5) unsigned DEFAULT NULL,
  `priority` tinyint(4) DEFAULT NULL,
  `parent_id` smallint(5) unsigned DEFAULT NULL,
  `is_menu` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent_id`),
  CONSTRAINT `st_category_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `st_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_category
-- ----------------------------
INSERT INTO `st_category` VALUES ('1', '1', '1', '4', null, '1');
INSERT INTO `st_category` VALUES ('2', '1', '1', '1', null, '1');
INSERT INTO `st_category` VALUES ('3', '1', '3', '1', '2', '0');

-- ----------------------------
-- Table structure for st_category_detail
-- ----------------------------
DROP TABLE IF EXISTS `st_category_detail`;
CREATE TABLE `st_category_detail` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` smallint(5) unsigned NOT NULL,
  `language_id` smallint(5) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` tinytext,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `st_category_detail_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `st_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `st_category_detail_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `st_language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_category_detail
-- ----------------------------
INSERT INTO `st_category_detail` VALUES ('1', '1', '1', 'Category 1', 'Category 1');
INSERT INTO `st_category_detail` VALUES ('2', '1', '2', 'Danh mục 1', 'Danh mục 1');
INSERT INTO `st_category_detail` VALUES ('3', '2', '1', 'Category 2', 'Category 2');
INSERT INTO `st_category_detail` VALUES ('4', '2', '2', 'Danh mục 2', 'Danh mục 2');
INSERT INTO `st_category_detail` VALUES ('5', '3', '1', 'Category 3', 'Category 3');
INSERT INTO `st_category_detail` VALUES ('6', '3', '2', 'Danh mục 3', 'Danh mục 3');

-- ----------------------------
-- Table structure for st_language
-- ----------------------------
DROP TABLE IF EXISTS `st_language`;
CREATE TABLE `st_language` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(3) CHARACTER SET utf8 DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `locale` varchar(5) CHARACTER SET utf8 DEFAULT NULL,
  `flag` varchar(100) DEFAULT NULL,
  `date_format` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `time_format` varchar(20) CHARACTER SET utf8 DEFAULT NULL,
  `status` smallint(3) unsigned DEFAULT NULL,
  `currency` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `sort` smallint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of st_language
-- ----------------------------
INSERT INTO `st_language` VALUES ('1', 'en', 'English', 'en_US', '/files/images/flag/en.jpg', 'm-d-Y', 'H:m:i', '2', 'USD', '0');
INSERT INTO `st_language` VALUES ('2', 'vi', 'Tiếng Việt', 'vi_VN', '/files/images/flag/vi.jpg', 'd/m/Y', 'H:m:i', '1', 'VND', '1');

-- ----------------------------
-- Table structure for st_page
-- ----------------------------
DROP TABLE IF EXISTS `st_page`;
CREATE TABLE `st_page` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `datetime_created` datetime DEFAULT NULL,
  `datetime_updated` datetime DEFAULT NULL,
  `datetime_published` datetime DEFAULT NULL,
  `creator` smallint(5) unsigned DEFAULT NULL,
  `updator` smallint(5) unsigned DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `priority` tinyint(4) DEFAULT NULL,
  `layout` varchar(50) DEFAULT NULL,
  `is_menu` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `creator` (`creator`),
  KEY `updator` (`updator`),
  CONSTRAINT `st_page_ibfk_1` FOREIGN KEY (`creator`) REFERENCES `st_user` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `st_page_ibfk_2` FOREIGN KEY (`updator`) REFERENCES `st_user` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_page
-- ----------------------------
INSERT INTO `st_page` VALUES ('1', '2015-08-24 15:23:46', '2015-08-27 15:13:22', '2015-08-27 15:12:57', '1', '1', '1', '4', 'layout/layout', '0');
INSERT INTO `st_page` VALUES ('2', '2015-08-26 17:42:13', '2015-08-27 15:11:49', '2015-08-26 17:35:15', '1', '1', '1', '4', 'layout/layout', '0');
INSERT INTO `st_page` VALUES ('3', '2015-08-26 17:44:51', '2015-08-27 15:11:27', '2015-08-26 17:44:19', '1', '1', '1', '4', 'layout/layout', '0');

-- ----------------------------
-- Table structure for st_page_category
-- ----------------------------
DROP TABLE IF EXISTS `st_page_category`;
CREATE TABLE `st_page_category` (
  `page_id` smallint(5) unsigned NOT NULL,
  `category_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`page_id`,`category_id`),
  KEY `page_id` (`page_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `st_page_category_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `st_page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `st_page_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `st_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_page_category
-- ----------------------------
INSERT INTO `st_page_category` VALUES ('1', '1');
INSERT INTO `st_page_category` VALUES ('1', '2');
INSERT INTO `st_page_category` VALUES ('2', '1');
INSERT INTO `st_page_category` VALUES ('2', '3');
INSERT INTO `st_page_category` VALUES ('3', '3');

-- ----------------------------
-- Table structure for st_page_detail
-- ----------------------------
DROP TABLE IF EXISTS `st_page_detail`;
CREATE TABLE `st_page_detail` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` smallint(5) unsigned NOT NULL,
  `language_id` smallint(5) unsigned NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8,
  `seo_description` varchar(160) CHARACTER SET utf8 DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `page_id` (`page_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `st_page_detail_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `st_page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `st_page_detail_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `st_language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of st_page_detail
-- ----------------------------
INSERT INTO `st_page_detail` VALUES ('1', '1', '1', 'Page 1', '<p>Page 1</p>\r\n', 'Page 1', null);
INSERT INTO `st_page_detail` VALUES ('2', '1', '2', 'SỐT KEM THỊT MUỐI XÔNG KHÓI PANZANI', '<p>Panzani giới thiệu nước sốt Carbonara thơm ngon với nguy&ecirc;n liệu ch&iacute;nh l&agrave; kem, trứng v&agrave; thịt x&ocirc;ng kh&oacute;i! Bạn sẽ kh&ocirc;ng thể cưỡng lại nước sốt kem của Panzani! Chế biến từ nguồn nguy&ecirc;n liệu được lựa chọn cẩn thận, những m&oacute;n ăn chế biến với loại nước sốt n&agrave;y sẽ l&agrave;m h&agrave;i l&ograve;ng bất cứ người s&agrave;nh ăn n&agrave;o đang muốn thay đổi khẩu vị cho bữa ăn h&agrave;ng ng&agrave;y!</p>\r\n', 'Panzani giới thiệu nước sốt Carbonara thơm ngon với nguyên liệu chính là kem, trứng và thịt xông khói! Bạn sẽ không thể cưỡng lại nước sốt kem của Panzani! Chế ', null);
INSERT INTO `st_page_detail` VALUES ('3', '2', '1', 'Page 2', '<p>Page 2</p>\r\n', 'Page 2', null);
INSERT INTO `st_page_detail` VALUES ('4', '2', '2', 'SỐT THỊT CÀ CHUA PANZANI', '<p>H&atilde;y kh&aacute;m ph&aacute; c&ocirc;ng thức nước sốt thịt c&agrave; chua nổi tiếng của &Yacute;. Được chế biến với nguồn rau củ chọn lọc, nước sốt thịt c&agrave; chua sẽ mang lại sự h&agrave;i l&ograve;ng cho bạn cũng như đ&aacute;p ứng được mong chờ của bạn về một loại sốt đ&uacute;ng kiểu Bolognese! Nước sốt đặc biệt c&oacute; cả thịt đi k&egrave;m!</p>\r\n', 'Hãy khám phá công thức nước sốt thịt cà chua nổi tiếng của Ý. Được chế biến với nguồn rau củ chọn lọc, nước sốt thịt cà chua sẽ mang lại sự hài lòng cho bạn cũn', null);
INSERT INTO `st_page_detail` VALUES ('5', '3', '1', 'Page 3', '<p>Page 3</p>\r\n', 'Page 3', null);
INSERT INTO `st_page_detail` VALUES ('6', '3', '2', 'SỐT CÀ CHUA, OLIU VÀ HÚNG QUẾ PANZANI', '<p>Loại sốt c&agrave; chua, oliu v&agrave; h&uacute;ng quế n&agrave;y sẽ mang lại cho bạn hương vị của v&ugrave;ng Địa Trung Hải. Bạn sẽ c&oacute; một bữa ăn thật ngon miệng với loại sốt được chế biến hợp khẩu vị, kh&ocirc;ng chứa chất bảo quản v&agrave; phẩm m&agrave;u.</p>\r\n', 'Loại sốt cà chua, oliu và húng quế này sẽ mang lại cho bạn hương vị của vùng Địa Trung Hải. Bạn sẽ có một bữa ăn thật ngon miệng với loại sốt được chế biến hợp ', null);

-- ----------------------------
-- Table structure for st_page_tag
-- ----------------------------
DROP TABLE IF EXISTS `st_page_tag`;
CREATE TABLE `st_page_tag` (
  `page_id` smallint(5) unsigned NOT NULL,
  `tag_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`page_id`,`tag_id`),
  KEY `page_id` (`page_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `st_page_tag_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `st_page` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `st_page_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `st_tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_page_tag
-- ----------------------------
INSERT INTO `st_page_tag` VALUES ('1', '1');

-- ----------------------------
-- Table structure for st_tag
-- ----------------------------
DROP TABLE IF EXISTS `st_tag`;
CREATE TABLE `st_tag` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) DEFAULT '1',
  `priority` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_tag
-- ----------------------------
INSERT INTO `st_tag` VALUES ('1', '1', '1');

-- ----------------------------
-- Table structure for st_tag_detail
-- ----------------------------
DROP TABLE IF EXISTS `st_tag_detail`;
CREATE TABLE `st_tag_detail` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `tag_id` smallint(5) unsigned NOT NULL,
  `language_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tag_id` (`tag_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `st_tag_detail_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `st_tag` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `st_tag_detail_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `st_language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of st_tag_detail
-- ----------------------------
INSERT INTO `st_tag_detail` VALUES ('1', 'Tag 1', '1', '1');
INSERT INTO `st_tag_detail` VALUES ('2', 'Nhãn 1', '1', '2');

-- ----------------------------
-- Table structure for st_user
-- ----------------------------
DROP TABLE IF EXISTS `st_user`;
CREATE TABLE `st_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `display_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `password` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `token_change_password` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `expired_change_password_time` datetime DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `new_email` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `token_change_email` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  `expired_change_email_time` datetime DEFAULT NULL,
  `role` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `datetime_created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of st_user
-- ----------------------------
INSERT INTO `st_user` VALUES ('1', 'admin', 'Admin', null, '198470e705335caea8e18a6dd9070bd8', null, null, 'admin@starseed.fr', null, null, null, 'admin', '1', '2015-08-13 15:31:09');
INSERT INTO `st_user` VALUES ('2', 'minh', 'Trần Hữu Minh', null, 'a9aca046c1d70077412161921921d433', null, null, 'minh@starseed.fr', null, null, null, 'admin', '1', '2015-08-18 14:03:10');
