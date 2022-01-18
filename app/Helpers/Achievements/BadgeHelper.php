<?php

namespace App\Helpers\Achievements;

use App\Models\User;
use Illuminate\Support\Facades\Log;

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

    protected static $badges = [
        10 => 'Master',
        8 => 'Advanced',
        4 => 'Intermediate',
        0 => 'Beginner',
    ];

    public static function get_achievements()
    {
        return self::$achievements;
    }

    /**
     * @param User $user
     * @return int
     */
    public static function getUserAchievementsQuantity(User $user)
    {
        $total_unlocked = 0;
        foreach (self::$achievements as $class => $relationship) {
            $achievement = new $class();
            $total_unlocked += $achievement->unlockedAchievements(count($user->$relationship));
        }

        return $total_unlocked;
    }

    /**
     * @param User $user
     * @return string
     */
    public static function getUserUnlockedAchievements(User $user)
    {
        $total_unlocked = [];
        foreach (self::$achievements as $class => $relationship) {
            $achievement = new $class();
            $total_unlocked[] = implode(", ", $achievement->unlockedAchievementsList(count($user->$relationship)));
        }

        return $total_unlocked;
    }

    /**
     * @param User $user
     * @return string
     */
    public static function getUserNextAchievements(User $user)
    {
        $next_list = [];
        foreach (self::$achievements as $class => $relationship) {
            $achievement = new $class();
            $next_list[] = $achievement->nextAchievement(count($user->$relationship));
        }

        return $next_list;
    }

    /**
     * @param User $user
     * @param bool $return_remaining Whether return the badge name or the remaining achievements quantity to get it
     * @return string|int
     */
    public static function getUserNextBadgeLevel(User $user, $return_remaining = false)
    {
        $current_level = self::getUserBadgeLevel($user, false);

        $levels = array_keys(self::$badges);
        $current_level = array_search($current_level, $levels);
        $next_level = "";
        if(isset($levels[$current_level - 1])) {
            $next_level = $levels[$current_level - 1];
            if(! $return_remaining) $next_level = self::$badges[$next_level];
            else $next_level = $next_level - self::getUserAchievementsQuantity($user);
        }

        return $next_level;

    }

    /**
     * @param User $user
     * @param bool $return_name Whether return the badge name or the required achievements quantity
     * @return string|int
     */
    public static function getUserBadgeLevel(User $user, $return_name = true)
    {
        $total_unlocked = self::getUserAchievementsQuantity($user);

        return self::getBadgeLevel($total_unlocked, $return_name);

    }


    /**
     * Calculate the level of a badge. Example: if quantity is 5, then user is Intermediate
     * @param int $quantity
     * @param bool $return_name Whether return badge name or minimum quantity required
     * @return int|string $level
     */
    public static function getBadgeLevel($quantity, $return_name = true)
    {
        $level = 0;
        foreach (self::$badges as $required => $name) {
            if($quantity >= $required) {
                if($return_name) $level = $name;
                else $level = $required;
                break;
            }
        }

        return $level;
    }


    /**
     * Check if a quantity of achievements unlocks a new badge
     * @param int $quantity
     * @return bool
     */
    public static function justUnlockedBadge($quantity)
    {
        return isset(self::$badges[$quantity]);
    }

}
