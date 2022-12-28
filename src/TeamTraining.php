<?php

namespace TeamTraining;

class TeamTraining
{

    public static function init() 
    {
        PostTypes\Programme::init();
        PostTypes\WorkoutOfTheDay::init();
        PostTypes\WorkoutOfTheWeek::init();
        PostTypes\Workshop::init();
        API\Routes::init();
    }

}