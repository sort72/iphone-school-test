<?php

namespace App\Helpers\Achievements;

use Illuminate\Support\Facades\Log;

abstract class AchievementHelper
{
    /**
     * Levels for the given achievement
     * Add elements to this array (level => description) with descending order to evaluate it within a foreach. If order is not descending (Keys), you might have to order the array.
     * This was built this way to write it faster and without sorting logic.
     */
    public $achievementLevels = [];


    /**
     * Calculate the level for a given achievement. Example: if highest level is '50' and quantity is 54, then this level will be returned.
     * @param int $quantity
     * @param int $return_key Whether return achievement level key, or value (a.k.a achievement_name)
     * @return int|string $level
     */
    public function getAchievementLevel($quantity, $return_key = true)
    {
        $level = 0;
        foreach ($this->achievementLevels as $required => $message) {
            if($quantity >= $required) {
                if($return_key) $level = $required;
                else $level = $message;
                break;
            }
        }

        return $level;
    }

    /**
     * Check if a quantity unlocks a new achievement
     * @param int $quantity
     * @return bool
     */
    public function justUnlockedAchievement($quantity)
    {
        return isset($this->achievementLevels[$quantity]);
    }


    /**
     * Check achievements unlocked so far determined by a given quantity
     * @param int $quantity
     * @return int
     */
    public function unlockedAchievements($quantity)
    {
        $level = $this->getAchievementLevel($quantity);
        $achievements = 0;
        if($level)
        {
            /** Search the level in the keys of the achievement levels (Key is the quantity to unlock this level), and reverse it to get the achievements unlocked and add 1 because first element is 0 */
            $achievements = array_search($level, array_reverse(array_keys($this->achievementLevels))) + 1;
        }

        return $achievements;
    }


}
