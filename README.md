# Back-end Developer Test

### Written by Alejandro Ortega as a hiring test for iphonephotographyschool.com using Laravel 8. [Requirements can be read here](https://docs.google.com/document/d/1VfwrD9nnvricv2in9-IZBXkh4jzDVrC0HvE_qi5VIoM/edit).

# Description

## Achievements

All Achievements are stored on App\Helpers\Achievements and extends from AchievementHelper class, which defines all methods needed to calculate all things needed (get unlocked achievements list, get next achiemenet, get current achievement, etc). You can easily add new levels to a current achievement by adding them to the **$achievementLevels** property within an Achievement. Logic is ready to read that property and expanding levels is as easy as define them in that property.

To add a new achievement, just add the new Helper and expand the AchievementHelper base class, then define the levels of the new Achievement as in the previous defined achievements (LessonWatchedHelper and CommentWrittenHelper). You also have instructions to define this property within AchievementHelper base class. You also have to map the new class to a user relationship within the BadgeHelper class to make it readable and consider it during badges computing (Get unlocked achievements, next available achievements, etc).

## Badges

Badges are pretty similar to achievements, you have a BadgeHelper abstract class with some methods defined to get information related to badges (Current badge, next badge, achievements acquired, next achievements, etc).

## Testing

Tests were implemented for each case to make sure achievements are being unlocked when needed and a quantity of Lessons watched / Comments written is being well-located on a Badge/Achievement.



