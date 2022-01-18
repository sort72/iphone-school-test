<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\CommentWritten;
use App\Helpers\Achievements\BadgeHelper;
use App\Helpers\Achievements\CommentWrittenHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use UnlocksAchievement;

class CheckCommentsWritten
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
     * @param  CommentWritten  $event
     * @return void
     */
    public function handle(CommentWritten $event)
    {
        $total_comments_written = count($event->comment->user->comments);
        $achievement = new CommentWrittenHelper();

        $this->check_whether_unlocked_achievement($achievement, $event->comment->user, $total_comments_written);

    }
}
