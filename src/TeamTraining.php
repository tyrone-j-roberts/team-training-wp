<?php

namespace TeamTraining;

class TeamTraining
{

    public static function init() 
    {
        PostTypes\Programme::init();
        PostTypes\WorkoutOfTheDay::init();
        API\Routes::init();
    }

}