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

}