<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\LessonWatched;
use App\Helpers\Achievements\LessonWatchedHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckLessonsWatched
{
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
        $just_unlocked = $achievement->justUnlockedAchievement($total_lessons_watched);

        if($just_unlocked) {
            $achievement_name = $achievement->getAchievementLevel($total_lessons_watched, false);
            AchievementUnlocked::dispatch($achievement_name, $event->user);
        }

    }
}
