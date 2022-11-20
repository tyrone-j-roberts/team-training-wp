<?php

namespace TeamTraining\API;

class User
{

    public static function validateEmail(\WP_REST_Request $request)
    {
        $params = $request->get_params();

        $required_params = ['email'];

        $errors = [];

        foreach($required_params as $field) {
            if (!isset($params[$field])) {
                $errors[] = "'{$field}' parameter can not be empty";
            }
        }

        if (!empty($errors)) {
            $response = new \WP_REST_Response([ "valid_email" => false, "errors" => $errors ]);
            return $response;
        }

        if (!is_email($params['email'])) {
            $response = new \WP_REST_Response([ "valid_email" => false, "errors" => ['Invalid email adress'] ]);
            return $response;
        }

        $existing_user = get_user_by('email', $params['email']);

        if ($existing_user) {
            $response = new \WP_REST_Response([ "valid_email" => false, "errors" => ["Email address is already taken"] ]);
            return $response;
        }

        $response = new \WP_REST_Response([ "valid_email" => true ]);

        return $response;
    }

    public static function getExercises(\WP_REST_Request $request)
    {
        global $wpdb;

        $user_id = get_current_user_id();

        $ts_last_week = time() - (60 * 60 * 24 * 7);
        $date_last_week = date('Y-m-d H:i:s', $ts_last_week);

        $results = $wpdb->get_results("SELECT * FROM `wp_user_exercises` WHERE `user_id` = {$user_id} AND `started_at` > \"{$date_last_week}\";");

        return $results;
    }

}