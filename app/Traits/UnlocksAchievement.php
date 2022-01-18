<?php

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Helpers\Achievements\AchievementHelper;
use App\Helpers\Achievements\BadgeHelper;
use App\Models\User;

trait UnlocksAchievement
{
    /**
     * Logic to check if an achievement and a badge were just unlocked and dispatch related event
     * @param AchievementHelper $achievement
     * @param User $user
     * @param int $quantity
     */
    public function check_whether_unlocked_achievement(AchievementHelper $achievement, User $user, int $quantity)
    {
        $just_unlocked = $achievement->justUnlockedAchievement($quantity);

        if($just_unlocked) {
            $achievement_name = $achievement->getAchievementLevel($quantity, false);
            AchievementUnlocked::dispatch($achievement_name, $user);

            $achievements_quantity = BadgeHelper::getUserAchievementsQuantity($user);
            $badge_unlocked = BadgeHelper::justUnlockedBadge($achievements_quantity);
            if($badge_unlocked)
            {
                $badge_name = BadgeHelper::getBadgeLevel($achievements_quantity);
                BadgeUnlocked::dispatch($badge_name, $user);
            }
        }
    }
}
