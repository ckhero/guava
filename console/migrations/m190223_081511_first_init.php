<?php

use yii\db\Migration;

/**
 * Class m190223_081511_first_init
 */
class m190223_081511_first_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
DROP TABLE IF EXISTS `lesson`;

CREATE TABLE `lesson` (
  `lesson_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_type` enum('english','math','logic') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'english' COMMENT '课程类型[english:英语;math:数学;logic:逻辑]',
  `lesson_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '课程名字',
  `lesson_sort` tinyint(11) unsigned NOT NULL DEFAULT '1' COMMENT '课程顺序',
  `lesson_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '课程创建时间',
  `lesson_update_at` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01' ON UPDATE CURRENT_TIMESTAMP COMMENT '课程更新时间',
  PRIMARY KEY (`lesson_id`),
  UNIQUE KEY `idx_type_day` (`lesson_type`,`lesson_sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `lesson` WRITE;
/*!40000 ALTER TABLE `lesson` DISABLE KEYS */;

INSERT INTO `lesson` (`lesson_id`, `lesson_type`, `lesson_name`, `lesson_sort`, `lesson_create_at`, `lesson_update_at`)
VALUES
	(2,'english','第二天',2,'2019-02-19 23:03:25','1970-01-01 08:00:01'),
	(4,'english','第三天',3,'2019-02-19 23:03:43','1970-01-01 08:00:01'),
	(5,'math','第一天',1,'2019-02-19 23:03:55','2019-02-20 00:04:00'),
	(6,'math','第二天',2,'2019-02-19 23:04:02','1970-01-01 08:00:01'),
	(7,'math','第三天',3,'2019-02-19 23:04:12','1970-01-01 08:00:01'),
	(8,'logic','第一天',1,'2019-02-19 23:04:23','2019-02-20 00:03:58'),
	(9,'logic','第二天',2,'2019-02-19 23:04:33','1970-01-01 08:00:01'),
	(10,'logic','第三天',3,'2019-02-19 23:04:42','1970-01-01 08:00:01'),
	(11,'english','第一天',1,'2019-02-19 23:03:18','2019-02-20 01:27:57');

/*!40000 ALTER TABLE `lesson` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lesson_data
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lesson_data`;

CREATE TABLE `lesson_data` (
  `lesson_data_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_data_lesson_id` int(11) DEFAULT NULL COMMENT '课程id',
  `lesson_data_type` enum('url') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'url' COMMENT '课程资料类型[url:链接;]',
  `lesson_data_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '课程资料名字',
  `lesson_data_detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '课程资料内容',
  `lesson_data_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '课程资料添加时间',
  `lesson_data_update_at` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01' ON UPDATE CURRENT_TIMESTAMP COMMENT '课程资料更新时间',
  PRIMARY KEY (`lesson_data_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `lesson_data` WRITE;
/*!40000 ALTER TABLE `lesson_data` DISABLE KEYS */;

INSERT INTO `lesson_data` (`lesson_data_id`, `lesson_data_lesson_id`, `lesson_data_type`, `lesson_data_name`, `lesson_data_detail`, `lesson_data_create_at`, `lesson_data_update_at`)
VALUES
	(2,8,'url','课程资料','2222222','2019-02-20 02:14:11','2019-02-20 02:26:05');

/*!40000 ALTER TABLE `lesson_data` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lesson_question
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lesson_question`;

CREATE TABLE `lesson_question` (
  `lesson_question_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_question_lesson_id` int(10) unsigned NOT NULL COMMENT '课程id',
  `lesson_question_sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '题目排序',
  `lesson_question_detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT '题目内容',
  `lesson_question_type` enum('text','img') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'text' COMMENT '题目内容类型[text:文字;img:图片;]',
  `lesson_question_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '题目创建时间',
  `lesson_question_update_at` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01' ON UPDATE CURRENT_TIMESTAMP COMMENT '题目更新时间',
  PRIMARY KEY (`lesson_question_id`),
  KEY `idx_lesson_id_sort` (`lesson_question_lesson_id`,`lesson_question_sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `lesson_question` WRITE;
/*!40000 ALTER TABLE `lesson_question` DISABLE KEYS */;

INSERT INTO `lesson_question` (`lesson_question_id`, `lesson_question_lesson_id`, `lesson_question_sort`, `lesson_question_detail`, `lesson_question_type`, `lesson_question_create_at`, `lesson_question_update_at`)
VALUES
	(1,8,2,'22222222','text','2019-02-20 02:27:12','2019-02-20 02:27:23'),
	(2,8,1,'NULL312312312','text','2019-02-20 02:27:13','2019-02-20 02:27:28');

/*!40000 ALTER TABLE `lesson_question` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table lesson_question_item
# ------------------------------------------------------------

DROP TABLE IF EXISTS `lesson_question_item`;

CREATE TABLE `lesson_question_item` (
  `lesson_question_item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_question_lesson_question_id` int(11) unsigned NOT NULL COMMENT '题目ID',
  `lesson_question_item_option` enum('A','B','C','D','E') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'A' COMMENT '题目选项',
  `lesson_question_item_detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT '选项内容',
  `lesson_question_item_right` enum('yes','no') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'no' COMMENT '选项是否正确',
  `lesson_question_item_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '选项创建时间',
  `lesson_question_item_update_at` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01' ON UPDATE CURRENT_TIMESTAMP COMMENT '选项更新时间',
  PRIMARY KEY (`lesson_question_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `lesson_question_item` WRITE;
/*!40000 ALTER TABLE `lesson_question_item` DISABLE KEYS */;

INSERT INTO `lesson_question_item` (`lesson_question_item_id`, `lesson_question_lesson_question_id`, `lesson_question_item_option`, `lesson_question_item_detail`, `lesson_question_item_right`, `lesson_question_item_create_at`, `lesson_question_item_update_at`)
VALUES
	(1,1,'B','2222222','no','2019-02-20 02:27:57','1970-01-01 08:00:01'),
	(2,1,'A','NULL3123131','no','2019-02-20 02:28:00','2019-02-20 02:35:50');

/*!40000 ALTER TABLE `lesson_question_item` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table level
# ------------------------------------------------------------

DROP TABLE IF EXISTS `level`;

CREATE TABLE `level` (
  `level_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `level_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '用户等级名称',
  `level_min_point` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户等级最小积分',
  `level_max_point` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户等级最大积分',
  `level_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '用户等级创建时间',
  `level_update_at` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01' ON UPDATE CURRENT_TIMESTAMP COMMENT '用户等级更新时间',
  PRIMARY KEY (`level_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `level` WRITE;
/*!40000 ALTER TABLE `level` DISABLE KEYS */;

INSERT INTO `level` (`level_id`, `level_name`, `level_min_point`, `level_max_point`, `level_create_at`, `level_update_at`)
VALUES
	(1,'等级1',0,10,'2019-02-17 11:28:28','2019-02-17 11:28:59'),
	(2,'等级2',10,30,'2019-02-17 11:28:36','2019-02-17 11:29:02'),
	(3,'等级3',30,0,'2019-02-17 11:28:46','2019-02-17 11:29:12');

/*!40000 ALTER TABLE `level` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `order`;

CREATE TABLE `order` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `order_status` enum('init','paying','success','fail') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'init' COMMENT '订单状态[init:订单生成;paying:支付中;success:支付成功;fail:支付失败]',
  `order_no` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '订单编号',
  `order_out_trade_no` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '微信的订单编号',
  `order_amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单金额[单位/分]',
  `order_desc` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT '订单描述',
  `order_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '订单创建时间',
  `order_update_at` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01' ON UPDATE CURRENT_TIMESTAMP COMMENT '订单更新时间',
  PRIMARY KEY (`order_id`),
  KEY `idx_user_id` (`order_user_id`),
  KEY `iidx_order_no` (`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table pay_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pay_log`;

CREATE TABLE `pay_log` (
  `pay_log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pay_log_order_id` int(10) unsigned NOT NULL COMMENT '订单id',
  `pay_log_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci COMMENT '微信支付结果通知内容',
  `pay_log_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '支付日志创建时间',
  PRIMARY KEY (`pay_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



# Dump of table point_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `point_log`;

CREATE TABLE `point_log` (
  `point_log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `point_log_user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `point_log_point` int(11) NOT NULL DEFAULT '0' COMMENT '积分值',
  `point_log_type` enum('question') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'question' COMMENT '积分类型question:答题;',
  `point_log_action_type` enum('add','sub') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'add' COMMENT '积分操作类型sub:减少;add:增加',
  `point_log_desc` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '日志描述',
  `point_log_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '日志创建时间',
  `point_log_update_at` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01' ON UPDATE CURRENT_TIMESTAMP COMMENT '日志更新时间',
  PRIMARY KEY (`point_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `point_log` WRITE;
/*!40000 ALTER TABLE `point_log` DISABLE KEYS */;

INSERT INTO `point_log` (`point_log_id`, `point_log_user_id`, `point_log_point`, `point_log_type`, `point_log_action_type`, `point_log_desc`, `point_log_create_at`, `point_log_update_at`)
VALUES
	(1,5,1,'question','add','','2019-02-17 23:17:07','1970-01-01 08:00:01');

/*!40000 ALTER TABLE `point_log` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_openid` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '微信的openid',
  `user_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '用户昵称',
  `user_phone` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '用户电话号码',
  `user_point` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户积分',
  `user_pay_status` enum('yes','no') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'no' COMMENT '用户支付状态',
  `user_sign_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户签到次数',
  `user_head_img` varchar(258) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '' COMMENT '用户头像',
  `user_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '用户创建时间',
  `user_update_at` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01' ON UPDATE CURRENT_TIMESTAMP COMMENT '用户更新时间',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `idx_openid` (`user_openid`),
  KEY `idx_point` (`user_point`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`user_id`, `user_openid`, `user_name`, `user_phone`, `user_point`, `user_pay_status`, `user_sign_num`, `user_head_img`, `user_create_at`, `user_update_at`)
VALUES
	(1,'2',NULL,'2',22,'no',0,'222','2019-02-14 23:49:06','2019-02-17 22:57:03'),
	(3,'333','33','',222,'no',0,'2','2019-02-15 00:02:20','2019-02-17 23:00:43'),
	(5,'1111111','','',1221,'no',2,'','2019-02-20 01:44:18','2019-02-20 01:44:53');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_lesson
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_lesson`;

CREATE TABLE `user_lesson` (
  `user_lesson_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_lesson_user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `user_lesson_score` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '得分',
  `user_lesson_right_percent` int(10) unsigned NOT NULL COMMENT '正确率',
  `user_lesson_lesson_id` int(10) unsigned NOT NULL COMMENT '学习记录课程ID',
  `user_lesson_status` enum('init','finish','finish_delay') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'init' COMMENT '学习记录状态',
  `user_lesson_options` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '选项',
  `user_lesson_share_status` enum('init','succ','fail') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'init' COMMENT '是否分享[init:未分享;succ:成功;fail:失败;]',
  `user_lesson_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '学习记录创建时间',
  `user_lesson_update_at` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01' ON UPDATE CURRENT_TIMESTAMP COMMENT '学记录更新时间',
  PRIMARY KEY (`user_lesson_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `user_lesson` WRITE;
/*!40000 ALTER TABLE `user_lesson` DISABLE KEYS */;

INSERT INTO `user_lesson` (`user_lesson_id`, `user_lesson_user_id`, `user_lesson_score`, `user_lesson_right_percent`, `user_lesson_lesson_id`, `user_lesson_status`, `user_lesson_options`, `user_lesson_share_status`, `user_lesson_create_at`, `user_lesson_update_at`)
VALUES
	(1,5,0,0,11,'finish','','init','2019-02-20 00:37:59','2019-02-20 01:55:12');

/*!40000 ALTER TABLE `user_lesson` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_sign
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_sign`;

CREATE TABLE `user_sign` (
  `user_sign_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_sign_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `user_sign_sign_at` datetime NOT NULL DEFAULT '1970-01-01 08:00:01' COMMENT '签到时间',
  PRIMARY KEY (`user_sign_id`),
  KEY `idx_user_id` (`user_sign_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `user_sign` WRITE;
/*!40000 ALTER TABLE `user_sign` DISABLE KEYS */;

INSERT INTO `user_sign` (`user_sign_id`, `user_sign_user_id`, `user_sign_sign_at`)
VALUES
	(8,5,'2019-02-17 21:44:05'),
	(9,5,'2019-02-19 22:49:03');

/*!40000 ALTER TABLE `user_sign` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_token
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_token`;

CREATE TABLE `user_token` (
  `user_token_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_token_user_id` int(11) unsigned NOT NULL COMMENT '用户id',
  `user_token_token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '用户登陆的token',
  `user_token_expire_at` datetime NOT NULL DEFAULT '1970-01-01 08:00:01' COMMENT 'token过期时间',
  `user_token_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'token创建时间',
  `user_token_update_at` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01' ON UPDATE CURRENT_TIMESTAMP COMMENT 'token更新时间',
  PRIMARY KEY (`user_token_id`),
  UNIQUE KEY `idx_user_id` (`user_token_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `user_token` WRITE;
/*!40000 ALTER TABLE `user_token` DISABLE KEYS */;

INSERT INTO `user_token` (`user_token_id`, `user_token_user_id`, `user_token_token`, `user_token_expire_at`, `user_token_create_at`, `user_token_update_at`)
VALUES
	(1,1,'2222','2019-02-27 00:24:33','2019-02-15 00:10:38','2019-02-17 00:47:10'),
	(2,4,'8O3wE49nmLbj-ERF_2c0MdykNrCQPj2i','2019-02-18 01:36:48','2019-02-17 01:25:35','2019-02-17 01:36:48'),
	(3,5,'ocdKgInEPg0CQrS_DNFy24klzOv2HNcd','2019-02-20 22:49:02','2019-02-17 01:44:18','2019-02-19 22:49:02'),
	(4,0,'','1970-01-01 08:00:01','2019-02-17 02:02:38','1970-01-01 08:00:01');

/*!40000 ALTER TABLE `user_token` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
SQL;

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190223_081511_first_init cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190223_081511_first_init cannot be reverted.\n";

        return false;
    }
    */
}
