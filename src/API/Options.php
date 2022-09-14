<?php

namespace TeamTraining\API;

class Options
{

    public static function getInitialContent()
    { 
        $initial_content = [
            'welcome_text' => get_field("welcome_text", 'option')
        ];

        $response = new \WP_REST_Response([ 'content' => $initial_content ]);

        return $response;
    }

}