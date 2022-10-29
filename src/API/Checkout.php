<?php

namespace TeamTraining\API;

class Checkout 
{

    public static function create(\WP_REST_Request $request) 
    {
        $params = $request->get_params();

        $programme_id = $params['programme_id'];
        $user = get_current_user();

        

    }

}