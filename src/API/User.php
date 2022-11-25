<?php

namespace TeamTraining\API;

class User
{

    public static function create(\WP_REST_Request $request)
    {
        $params = $request->get_json_params();

        $required_params = [
            'firstName', 
            'lastName', 
            'email', 
            'birthDate', 
            'password', 
            'height', 
            'weight', 
            'trainingLocation', 
            'skillLevel', 
            'frequency', 
            'goal', 
            'focus'
        ];

        $errors = [];

        foreach($required_params as $field) {
            if (empty($params[$field])) {
                $errors[] = "'{$field}' parameter can not be empty";
            }
        }

        if (!empty($errors)) {
            $response = new \WP_REST_Response([ "errors" => $errors ]);
            // LogService::log(json_encode($params) . "\n" . json_encode($errors));
            $response->set_status( 400 );
            return $response;
        }

        $existing_user = get_user_by('email', $params['email']);

        if ($existing_user) {
            $response = new \WP_REST_Response([ "errors" => ["Email address already taken"] ]);
            // LogService::log(json_encode($params) . "\n" . "Email address already taken");
            $response->set_status( 406 );
            return $response;
        }

        $height = $params['height'];
        $height_cm = $height['unit'] =='cm' ? $height['value'] : $height['value'] * 30.48;

        $weight_kg = 0;

        if ($params['weight']['unit'] == 'kg') {
            $weight_kg = $params['weight']['value'];
        } elseif ($params['weight']['unit'] == 'lbs') {
            $weight_kg = $params['weight']['value'] / 2.205;
        } elseif ($params['weight']['unit'] == 'stone') {
            $weight_kg = $params['weight']['value'] * 6.35;
        }

        $user_meta = [
            'date_of_birth' => $params['birthDate'],
            'height' => $height_cm,
            'weight' => $weight_kg,
            'training_location' => $params['trainingLocation'],
            'skillLevel' => $params['skillLevel'],
            'frequency' => $params['frequency'],
            'goal' => $params['goal'],
            'focus' => $params['focus']
        ];

        $password = $params['password'];

        $user_id = wp_insert_user([
            'user_email' => $params['email'],
            'first_name' => $params['firstName'],
            'last_name' => $params['lastName'],
            'user_login' => $params['email'],
            'user_pass' => $password,
            'meta_input' => $user_meta
        ]);

        if (is_wp_error($user_id)) {
            $response = new \WP_REST_Response($user_id);
            // LogService::log(json_encode($params) . "\n" . json_encode($user_id->get_error_messages()));
            $response->set_status( 406 );
            return $response;
        }
        
        $response = new \WP_REST_Response([ "user_id" => $user_id, "password" => $password ]);

        return $response;
    }

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

    public static function updatePassword(\WP_REST_Request $request)
    {
        global $wpdb;

        $data = $request->get_json_params();

        $user = get_current_user();

        $check = wp_check_password($data['current_password'], $user->user_pass, $user->ID);

        if ($check) {
            return new \WP_REST_Response([ "error" => "Your current password is incorrect" ]);
        }

        if (strlen($data['new_password']) < 8) {
            return new \WP_REST_Response([ "error" => "New Password length must be greater than 8 characters" ]);
        }

        wp_set_password($data['new_password'], $user->ID);

        return new \WP_REST_Response([ "success" => true ]);
    }

}