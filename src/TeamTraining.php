<?php

namespace TeamTraining;

class TeamTraining
{

    public static function init() 
    {
        PostTypes\Programme::init();
        API\Routes::init();
    }

}