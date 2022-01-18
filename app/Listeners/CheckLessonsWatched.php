<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\LessonWatched;
use App\Helpers\Achievements\BadgeHelper;
use App\Helpers\Achievements\LessonWatchedHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use UnlocksAchievement;

class CheckLessonsWatched
{
    use UnlocksAchievement;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\LessonWatched  $event
     * @return void
     */
    public function handle(LessonWatched $event)
    {
        $total_lessons_watched = count($event->user->watched);
        $achievement = new LessonWatchedHelper();

        $this->check_whether_unlocked_achievement($achievement, $event->user, $total_lessons_watched);

    }
}
