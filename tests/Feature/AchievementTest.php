<?php

namespace Tests\Feature;

use App\Helpers\Achievements\AchievementHandler;
use App\Helpers\Achievements\AchievementHelper;
use App\Helpers\Achievements\LessonWatchedHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Check if a quantity of lessons watched belongs to the expected achievement level
     *
     * @return void
     */
    public function test_lesson_watched_levels()
    {
        $achievement = new LessonWatchedHelper();

        $levels = [
            1 => range(1, 4),
            5 => range(5, 9),
            10 => range(10, 24),
            25 => range(25, 49),
            50 => range(50, 60),
        ];

        foreach ($levels as $achievement_level => $possible_values) {
            foreach ($possible_values as $value) {
                $this->assertTrue($achievement->getAchievementLevel($value) === $achievement_level);
            }
        }
    }
}
