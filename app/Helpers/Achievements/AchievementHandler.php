<?php

namespace App\Helpers\Achievements;

class AchievementHandler
{
    protected static $achievements = [
        LessonWatchedHelper::class,
    ];

    public static function get_achievements()
    {
        return self::$achievements;
    }
}
