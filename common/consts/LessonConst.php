<?php
/**
 * Created by PhpStorm.
 * User: ckhero
 * Date: 2019/2/19
 * Time: 11:24 PM
 */

namespace common\consts;


class LessonConst
{
    const TYPE_ENGLISH = 'english';
    const TYPE_MATH = 'math';
    const TYPE_LOGIC = 'logic';

    /**
     * 课程类型映射
     * @var array
     */
    public static $typeToText = [
        self::TYPE_ENGLISH => '英语',
        self::TYPE_MATH => '数学',
        self::TYPE_LOGIC => '逻辑',
    ];

    public static $typeSort = [
        self::TYPE_ENGLISH => '1',
        self::TYPE_MATH => '2',
        self::TYPE_LOGIC => '3',
    ];
}