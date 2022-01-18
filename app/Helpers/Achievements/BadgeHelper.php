<?php

namespace App\Helpers\Achievements;

abstract class BadgeHelper
{
    /**
     * Achievements to evaluate quantity
     * key is the achievement class, value is the user relationship to count quantity
     */
    protected static $achievements = [
        LessonWatchedHelper::class => 'watched',
        CommentWrittenHelper::class => 'comments',
    ];

    public static function get_achievements()
    {
        return self::$achievements;
    }
}
