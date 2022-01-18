<?php

namespace App\Helpers\Achievements;

class AchievementHelper
{
    /**
     * Levels for the given achievement
     * Add elements to this array (level => description) with descending order to evaluate it within a foreach. If order is not descending (Keys), you might have to order the array.
     * This was built this way to write it faster and without sorting logic.
     */
    protected static $achievementLevels = [];


    /**
     * Retrieve achievement according to a given quantity
     * @param int $quantity
     * @param int $return_key Whether return achievement level key, or value (a.k.a achievement_name)
     * @return int|string $level
     */
    public static function getAchievementLevel($quantity, $return_key = true)
    {
        return self::computeLevel($quantity, self::$achievementLevels, $return_key);
    }

    /**
     * Check if a quantity unlocks a new achievement
     * @param int $quantity
     * @return bool
     */
    public static function justUnlockedAchievement($quantity)
    {
        return self::levelInKeys($quantity, self::$achievementLevels);
    }


    /**
     * Calculate the level for a given achievement. Example: if highest level is '50' and quantity is 54, then this level will be returned.
     * @param int $needle
     * @param array $haystack
     * @param bool $return_key Whether return achievement level key, or value (a.k.a description)
     * @return int|string $level
     */
    private static function computeLevel(int $needle, array $haystack, $return_key = 1)
    {
        $level = 0;
        foreach ($haystack as $required => $message) {
            if($needle >= $required) {
                if($return_key) $level = $required;
                else $level = $message;
                break;
            }
        }

        return $level;
    }


    /**
     * Check whether quantity is a key of the haystack
     * @param int $needle
     * @param array $haystack
     * @return bool $just_unlocked
     */
    private static function levelInKeys(int $needle, array $haystack)
    {
        return isset($haystack[$needle]);
    }

}
