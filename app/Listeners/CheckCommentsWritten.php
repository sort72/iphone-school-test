<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\CommentWritten;
use App\Helpers\Achievements\CommentWrittenHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CheckCommentsWritten
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
     * @param  CommentWritten  $event
     * @return void
     */
    public function handle(CommentWritten $event)
    {
        $total_comments_written = count($event->comment->user->comments);
        $achievement = new CommentWrittenHelper();
        $just_unlocked = $achievement->justUnlockedAchievement($total_comments_written);

        if($just_unlocked) {
            $achievement_name = $achievement->getAchievementLevel($total_comments_written, false);
            AchievementUnlocked::dispatch($achievement_name, $event->user);
        }

    }
}
