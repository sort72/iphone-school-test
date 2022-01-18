<?php

namespace App\Http\Controllers;

use App\Helpers\Achievements\BadgeHelper;
use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        return response()->json([
            'unlocked_achievements' => BadgeHelper::getUserUnlockedAchievements($user),
            'next_available_achievements' => BadgeHelper::getUserNextAchievements($user),
            'current_badge' => BadgeHelper::getUserBadgeLevel($user),
            'next_badge' => BadgeHelper::getUserNextBadgeLevel($user, false),
            'remaing_to_unlock_next_badge' => BadgeHelper::getUserNextBadgeLevel($user, true)
        ]);
    }
}
