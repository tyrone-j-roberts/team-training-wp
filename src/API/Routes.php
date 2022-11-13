<?php

namespace TeamTraining\API;

class Routes
{
    public static function init()
    {   	
        add_action( 'rest_api_init', 'TeamTraining\API\Routes::registerApiRoutes');
    }

    public static function registerApiRoutes()
    {

        register_rest_route( 'tt/v1', '/options/initial', array(
            'methods' => 'GET',
            'callback' => ['TeamTraining\API\Options', 'getInitialContent']
        ));

        register_rest_route( 'tt/v1', '/user/validate-email', array(
            'methods' => 'POST',
            'callback' => 'TeamTraining\API\User::validateEmail',
        ) );

        register_rest_route( 'tt/v1', '/programmes', array(
            'methods' => 'GET',
            'callback' => ['TeamTraining\API\Programmes', 'getProgrammes']
        ));
        
        register_rest_route( 'tt/v1', 'programmes/(?P<programme_id>\S+)/exercises', array(
            'methods' => 'GET',
            'callback' => ['TeamTraining\API\Programmes', 'getExercises']
        ));

        register_rest_route( 'tt/v1', 'programmes/(?P<programme_id>\S+)/purchase', array(
            'methods' => 'POST',
            'callback' => ['TeamTraining\API\Programmes', 'purchase']
        ));

        register_rest_route( 'tt/v1', 'programmes/(?P<programme_id>\S+)/begin-exercise', array(
            'methods' => 'POST',
            'callback' => ['TeamTraining\API\Programmes', 'userBeginExercise']
        ));

        register_rest_route( 'tt/v1', 'programmes/(?P<programme_id>\S+)/complete-exercise', array(
            'methods' => 'POST',
            'callback' => ['TeamTraining\API\Programmes', 'userCompleteExercise']
        ));

        register_rest_route( 'tt/v1', 'programmes/(?P<programme_id>\S+)/track-exercise', array(
            'methods' => 'POST',
            'callback' => ['TeamTraining\API\Programmes', 'userTrackExercise']
        ));

        
    }

}