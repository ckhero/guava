<?php

use yii\db\Migration;

/**
 * Class m190223_074020_lesson
 */
class m190223_074020_lesson extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = <<<SQL
CREATE TABLE `lesson` (
  `lesson_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_type` enum('english','math','logic') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'english' COMMENT '课程类型[english:英语;math:数学;logic:逻辑]',
  `lesson_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '课程名字',
  `lesson_sort` tinyint(11) unsigned NOT NULL DEFAULT '1' COMMENT '课程顺序',
  `lesson_create_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '课程创建时间',
  `lesson_update_at` timestamp NOT NULL DEFAULT '1970-01-01 08:00:01' ON UPDATE CURRENT_TIMESTAMP COMMENT '课程更新时间',
  PRIMARY KEY (`lesson_id`),
  UNIQUE KEY `idx_type_day` (`lesson_type`,`lesson_sort`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
SQL;

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190223_074020_lesson cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190223_074020_lesson cannot be reverted.\n";

        return false;
    }
    */
}
