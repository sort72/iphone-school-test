<?php

namespace Tests\Feature;

use App\Helpers\Achievements\BadgeHelper;
use App\Helpers\Achievements\CommentWrittenHelper;
use App\Helpers\Achievements\LessonWatchedHelper;
use App\Models\Comment;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    // use RefreshDatabase;

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
                $this->assertEquals($achievement_level, $achievement->getAchievementLevel($value));
            }
        }
    }

    /**
     * Check if a quantity of comments written belongs to the expected achievement level
     *
     * @return void
     */
    public function test_comment_written_levels()
    {
        $achievement = new CommentWrittenHelper();

        $levels = [
            1 => range(1, 2),
            3 => range(3, 4),
            5 => range(5, 9),
            10 => range(10, 19),
            20 => range(20, 30),
        ];

        foreach ($levels as $achievement_level => $possible_values) {
            foreach ($possible_values as $value) {
                $this->assertEquals($achievement_level, $achievement->getAchievementLevel($value));
            }
        }
    }

    /**
     * This test is intended to be sure we are giving achievement to the user only when he unlocks it
     * @return void
     */
    public function test_unlocking_lesson_watched_achievement()
    {
        $achievement = new LessonWatchedHelper();

        $levels = [
            1 => range(1, 4),
            5 => range(5, 9),
            10 => range(10, 24),
            25 => range(25, 49),
            50 => range(50, 60),
        ];

        foreach ($levels as $level_value => $range_values) {
            // Assert if level value is the needed to unlock an achievement
            $this->assertTrue($achievement->justUnlockedAchievement($level_value));
            foreach ($range_values as $value) {
                // Assert if a value that is not the needed to unlock an achievement (Ex: You unlock the achievement with quantity 5 and your quantity is 6, it would assert)
                if($value !== $level_value) $this->assertFalse($achievement->justUnlockedAchievement($value));
            }
        }
    }

    /**
     * This test is intended to be sure we are giving achievement to the user only when he unlocks it
     * @return void
     */
    public function test_unlocking_comment_written_achievement()
    {
        $achievement = new CommentWrittenHelper();

        $levels = [
            1 => range(1, 2),
            3 => range(3, 4),
            5 => range(5, 9),
            10 => range(10, 19),
            20 => range(20, 30),
        ];

        foreach ($levels as $level_value => $range_values) {
            // Assert if level value is the needed to unlock an achievement
            $this->assertTrue($achievement->justUnlockedAchievement($level_value));
            foreach ($range_values as $value) {
                // Assert if a value that is not the needed to unlock an achievement (Ex: You unlock the achievement with quantity 5 and your quantity is 6, it would assert)
                if($value !== $level_value) $this->assertFalse($achievement->justUnlockedAchievement($value));
            }
        }
    }

    public function test_unlocked_lesson_watched()
    {
        $achievement = new LessonWatchedHelper();

        /**
         * $key is the quantity of lesson watched achievements unlocked, $value is the quantity of lessons watched to get the level
         */
        $unlockeds = [
            0 => [0],
            1 => range(1, 4),
            2 => range(5, 9),
            3 => range(10, 24),
            4 => range(25, 49),
            5 => range(50, 60),
        ];

        foreach ($unlockeds as $achievements_unlocked => $range_values) {
            foreach ($range_values as $value) {
                $this->assertEquals($achievements_unlocked, $achievement->unlockedAchievements($value));
            }
        }
    }

    public function test_unlocked_comment_written()
    {
        $achievement = new CommentWrittenHelper();

        /**
         * $key is the quantity of comment written achievements unlocked, $value is the quantity of comments written to get the level
         */
        $unlockeds = [
            0 => [0],
            1 => range(1, 2),
            2 => range(3, 4),
            3 => range(5, 9),
            4 => range(10, 19),
            5 => range(20, 30),
        ];

        foreach ($unlockeds as $achievements_unlocked => $range_values) {
            foreach ($range_values as $value) {
                $this->assertEquals($achievements_unlocked, $achievement->unlockedAchievements($value));
            }
        }
    }

    public function test_badge_level()
    {

        $comments_quantity = 5;
        $lessons_quantity = 12;

        $user = User::factory()->has(Comment::factory()->count($comments_quantity), 'comments')->hasAttached(Lesson::factory()->count($lessons_quantity), ['watched' => true], 'watched')->create();

        $this->assertEquals($comments_quantity, count($user->comments));
        $this->assertEquals($lessons_quantity, count($user->watched));

        $achievements = BadgeHelper::get_achievements();

        // foreach ($variable as $key => $value) {
        //     # code...
        // }
    }
}