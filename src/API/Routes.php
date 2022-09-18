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
        
    }

}