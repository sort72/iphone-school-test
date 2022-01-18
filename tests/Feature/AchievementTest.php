<?php

namespace Tests\Feature;

use App\Events\CommentWritten;
use App\Events\LessonWatched;
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

    public function user_factory()
    {
        $comments_quantity = 5;
        $lessons_quantity = 12;
        return User::factory()->has(Comment::factory()->count($comments_quantity), 'comments')->hasAttached(Lesson::factory()->count($lessons_quantity), ['watched' => true], 'watched')->create();
    }

    public function test_badge_level()
    {

        $comments_quantity = 5;
        $lessons_quantity = 12;
        $expected_badge = 4; // 3 comment achievements, 3 lesson achievements = 6 achievements. Badge would be Intermediate (4 achievements)

        $user = $this->user_factory();

        $this->assertEquals($comments_quantity, count($user->comments));
        $this->assertEquals($lessons_quantity, count($user->watched));

        $badge = BadgeHelper::getUserBadgeLevel($user, false);

        $this->assertEquals($expected_badge, $badge);


    }

    public function test_unlocked_badge()
    {
        /**
         * $key is the quantity of achievements unlocked required to get the level, $value is the quantity of achievements unlocked
         */
        $levels = [
            0 => range(0, 3),
            4 => range(4, 7),
            8 => range(8, 9),
            10 => range(10, 15),
        ];

        foreach ($levels as $level_value => $range_values) {
            // Assert if level value is the needed to unlock a badge
            $this->assertTrue(BadgeHelper::justUnlockedBadge($level_value));
            foreach ($range_values as $value) {
                // Assert if a value that is not the needed to unlock a badge (Ex: You unlock the badge with quantity 5 and your quantity is 6, it would assert)
                if($value !== $level_value) $this->assertFalse(BadgeHelper::justUnlockedBadge($value));
            }
        }
    }

    // public function test_dispatch_lesson_watched()
    // {
    //     $user = $this->user_factory();

    //     LessonWatched::dispatch(Lesson::first(), $user);
    //     $this->assertTrue(true);
    // }

    // public function test_dispatch_comment_written()
    // {
    //     $user = $this->user_factory();

    //     CommentWritten::dispatch(Comment::first());
    //     $this->assertTrue(true);
    // }

    public function test_next_lesson_watched_achievement()
    {
        $achievement = new LessonWatchedHelper();

        // Check if the next achievement ($value) is the expected for a quantity ($key)
        $data = [
            0 => 'First Lesson Watched',
            1 => '5 Lessons Watched',
            3 => '5 Lessons Watched',
            10 => '25 Lessons Watched',
            51 => '',
        ];

        foreach ($data as $quantity => $next_expected) {
            $next = $achievement->nextAchievement($quantity);
            $this->assertEquals($next_expected, $next, 'Failed asserting that ' . $next . ' is equal to expected ' . $next_expected);
        }

    }

    public function test_achievements_endpoint()
    {
        $user = $this->user_factory();

        $response = $this->get('/users/' . $user->id . '/achievements');

        $response->assertStatus(200);

        $content = $response->json();

        Log::info([$content]);
    }
}
