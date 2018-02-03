/*
 Navicat Premium Data Transfer

 Source Server         : mysql
 Source Server Type    : MySQL
 Source Server Version : 100109
 Source Host           : localhost:3306
 Source Schema         : tp5

 Target Server Type    : MySQL
 Target Server Version : 100109
 File Encoding         : 65001

 Date: 03/02/2018 22:48:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for yf_consult
-- ----------------------------
DROP TABLE IF EXISTS `yf_consult`;
CREATE TABLE `yf_consult`  (
  `consult_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '商品咨询id',
  `goods_id` int(11) NULL DEFAULT 0 COMMENT '商品id',
  `username` varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '用户名',
  `add_time` int(11) NULL DEFAULT 0 COMMENT '咨询时间',
  `consult_type` tinyint(1) NULL DEFAULT 1 COMMENT '1 商品咨询 2 支付咨询 3 配送 4 售后',
  `content` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '咨询内容',
  `parent_id` int(11) NULL DEFAULT 0 COMMENT '父id 用于管理员回复',
  `is_show` tinyint(1) NULL DEFAULT 0 COMMENT '是否显示',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '管理员回复状态，0未回复，1已回复',
  PRIMARY KEY (`consult_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 19 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of yf_consult
-- ----------------------------
INSERT INTO `yf_consult` VALUES (1, 49, '阿呆', 1447269213, 1, '你好', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (2, 49, '阿呆', 1451792241, 2, '可以微信支付吗', 0, 0, 0);
INSERT INTO `yf_consult` VALUES (3, 49, '阿呆', 1449120190, 2, '可以微信支付吗', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (4, 49, '阿呆', 1446618998, 3, '怎么还没发货', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (5, 49, '阿呆', 1452490971, 4, '我要退货', 0, 0, 0);
INSERT INTO `yf_consult` VALUES (6, 49, '阿呆', 1440647885, 1, '你好', 0, 0, 0);
INSERT INTO `yf_consult` VALUES (7, 49, '阿呆', 1441791693, 1, '你好', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (8, 49, '阿呆', 1443336811, 1, '你好', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (9, 48, '啊哈哈', 1440647885, 3, '什么时候发货啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (10, 48, '啊哈', 1440647385, 3, '什么时候发货啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (11, 48, '啊哈哈', 1443647885, 3, '什么时候发货啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (12, 48, '哈哈', 1440647585, 3, '什么时候发货啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (13, 48, '哈哈', 1440644885, 3, '什么时候发货啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (14, 48, '啊哈哈', 1456477865, 3, '什么时候发货啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (15, 48, '啊哈', 1440647885, 3, '什么时候发货啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (16, 48, '啊哈哈', 1440647585, 3, '什么时候发货啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (17, 48, '啊哈', 1440644885, 3, '什么时候发货啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊', 0, 1, 0);
INSERT INTO `yf_consult` VALUES (18, 48, '啊哈', 1440647448, 3, '什么时候发货啊啊啊啊啊啊啊啊啊啊啊啊啊啊啊', 0, 1, 0);

SET FOREIGN_KEY_CHECKS = 1;
