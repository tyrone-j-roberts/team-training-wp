<?php

namespace TeamTraining\API;

use Carbon\Carbon;

class Programmes
{

    public static function getProgrammes(\WP_REST_Request $request)
    { 

        $limit = isset($request['limit']) ? intval($request['limit']) : -1;

        $query_args = [
            'post_type' => 'programme',
            'posts_per_page' => $limit,
            'orderby' => 'date'
        ];

        $posts = get_posts($query_args);

        $programmes = [];

        foreach($posts as $post) {

            $focus = get_the_terms($post, 'focus');

            $header_image = get_field('header_image', $post);
            $post_thumbnail = has_post_thumbnail($post) ? get_the_post_thumbnail_url( $post, 'large' ) : null;
            $display_title = get_field('display_title', $post);

            $programmes[] = [
                'id' => $post->ID,
                'name' => !empty($display_title) ? $display_title : $post->post_title,
                'price' => get_field('price', $post),
                'image' => $post_thumbnail,
                'availability' => get_field('availability', $post),
                'frequency' => get_field('frequency',  $post),
                'cycles' => get_field('cycles', $post),
                'duration' => get_field('duration', $post),
                'focus' => $focus ? $focus[0]->name : null,
                'header_image' => $header_image ? $header_image : $post_thumbnail,
                'subtitle' => get_field('subtitle', $post),
                'quick_stats' => get_field('quick_stats', $post),
                'overview' => get_field('overview', $post),
                'equipment_list' => get_field('equipment_list', $post),
                'introductory_video' => get_field('introductory_video', $post),
                'programme_cycles' => get_field('programme_cycles', $post)
            ];
        }

        $response = new \WP_REST_Response([ 'programmes' => $programmes ]);

        return $response;
    }

    public static function getExercises(\WP_REST_Request $request)
    {
        $params = $request->get_url_params();
        $programme_id = $params['programme_id'];

        $post = get_post($programme_id);

        if (!$post) {
            return new \WP_REST_Response([ 'message' => "Programme #{$programme_id} not found." ], 404);
        }

        $programme_start_carbon = Carbon::createFromFormat('Y-m-d H:i:s', $post->post_date)->startOfDay();
        $today = Carbon::now()->startOfDay();

        $exercises = get_field('weeks', $programme_id);

        $past_exercises = [];
        $current_exercises = [];

        if ($exercises) {
            foreach($exercises as $i => $week) {
                foreach($week['days'] as $j => $day) {
    
                    $day_number = $i + $j;
                    $exercise_day_carbon = $programme_start_carbon->copy();
    
                    if (($day_number) > 0) {
                        $exercise_day_carbon->addDays($day_number);
                    }

                    if (!empty($day['exercises'])) {
                        foreach($day['exercises'] as $key => $value) {
                            $day['exercises'][$key]['handle'] = sanitize_title_with_dashes("{$value['title']}");
                        } 
                    }
                    
                    $exercise_day = [
                        'date' => $exercise_day_carbon->format('c'),
                        'today' => $exercise_day_carbon == $today,
                        'day' => $day
                    ];

                    
    
                    if ($exercise_day_carbon < $today) {
                        $past_exercises[] = $exercise_day;
                    } else {
                        $current_exercises[] = $exercise_day;
                    }
                }
            }
        }

        $response = [
            'past_exercises' => $past_exercises,
            'current_exercises' => $current_exercises
        ];

        return new \WP_REST_Response($response);

    }

    public static function purchase(\WP_REST_Request $request)
    {

        global $wpdb;

        $params = $request->get_url_params();
        $programme_id = (int)$params['programme_id'];
        $user_id = get_current_user_id();

        if (!$programme_id) {
            return new \WP_REST_Response([ 'message' => "Required parameters missing or invalid" ], 401);
        }

        $post = get_post($programme_id);

        if (!$post || $post->post_type != 'programme') {
            return new \WP_REST_Response([ 'message' => "Programme #{$programme_id} not found" ], 404);
        }

        $purchased_programme_row = $wpdb->get_row("SELECT * FROM `wp_user_programmes` WHERE `programme_id` = {$programme_id} AND `user_id` = {$user_id}");

        if (!$purchased_programme_row) {
            $purchased_programme_row = [
                'programme_id' => $programme_id,
                'user_id' => $user_id,
                'purchased_at' => date('Y-m-d H:i:s')
            ];

            $wpdb->insert('wp_user_programmes', $purchased_programme_row);
        
            $purchased_programme_row['id'] = $wpdb->insert_id;
        }

        return $purchased_programme_row;
        
    }

    public static function userBeginExercise(\WP_REST_Request $request)
    {   
        global $wpdb;

        $params = $request->get_url_params();
        $data = $request->get_json_params();
        $programme_id = (int)$params['programme_id'];
        $user_id = get_current_user_id();

        $exercise_handle = isset($data['exercise_handle']) ? sanitize_text_field($data['exercise_handle']) : null;
        $exercise_date = isset($data['exercise_date']) ? sanitize_text_field($data['exercise_date']) : null;
        $exercise_title = isset($data['exercise_title']) ? sanitize_text_field($data['exercise_title']) : null;
        
        $post = get_post($programme_id);

        if (!$post) {
            return new \WP_REST_Response([ 'message' => "Programme #{$programme_id} not found." ], 404);
        }

        $current_exercise_row = $wpdb->get_row("SELECT * FROM `wp_user_exercises` WHERE `completed_at` IS NULL", 'ARRAY_A');

        $now = date('Y-m-d H:i:s');

        if ($current_exercise_row) {
            
            $is_current_exercise = $current_exercise_row['exercise_handle'] == $exercise_handle && $current_exercise_row['exercise_date'] == $exercise_date && $current_exercise_row['programme_id'] == $programme_id;

            if (!$is_current_exercise) {
                $wpdb->update('wp_user_exercises', [ 'completed_at' => $now ], ['id' => $current_exercise_row['id']]);
            }

        }

        $exercise_row = $wpdb->get_row("SELECT * FROM `wp_user_exercises` WHERE `programme_id` = \"{$programme_id}\" AND `exercise_handle` = \"{$exercise_handle}\" AND `exercise_date` = \"{$exercise_date}\";", 'ARRAY_A');

        if (!$exercise_row) {
            $exercise_row = [
                'user_id' => $user_id,
                'exercise_handle' => $exercise_handle,
                'programme_id' => $programme_id,
                'exercise_date' => $exercise_date,
                'exercise_title' => $exercise_title,
                'started_at' => $now
            ];

      

            $wpdb->insert('wp_user_exercises', $exercise_row);
        
            $exercise_row['id'] = $wpdb->insert_id;
        }

        return $exercise_row;
    }

    public static function userCompleteExercise(\WP_REST_Request $request)
    {   
        global $wpdb;

        $params = $request->get_url_params();
        $data = $request->get_json_params();
        $programme_id = (int)$params['programme_id'];
        $user_id = get_current_user_id();

        $exercise_handle = isset($data['exercise_handle']) ? sanitize_text_field($data['exercise_handle']) : null;
        $exercise_date = isset($data['exercise_date']) ? sanitize_text_field($data['exercise_date']) : null;
        
        $post = get_post($programme_id);

        if (!$post) {
            return new \WP_REST_Response([ 'message' => "Programme #{$programme_id} not found." ], 404);
        }

        $exercise_row = $wpdb->get_row("SELECT * FROM `wp_user_exercises` WHERE `programme_id` = \"{$programme_id}\" AND `exercise_handle` = \"{$exercise_handle}\" AND `exercise_date` = {$exercise_date};", 'ARRAY_A');

        if (!$exercise_row) {
            return new \WP_REST_Response([ 'message' => "User exercise #{$programme_id} \"{$exercise_handle}\" not found." ], 404);
        }

        $completed_at = date('Y-m-d H:i:s');

        $wpdb->update('wp_user_exercises', [ 'completed_at' => $completed_at  ], [ 'id' => $exercise_row['id'] ]);
        
        $exercise_row['completed_at'] = $completed_at;

        return $exercise_row;
    }

    public static function userTrackExercise(\WP_REST_Request $request)
    {   
        global $wpdb;

        $params = $request->get_url_params();
        $data = $request->get_json_params();
        $programme_id = (int)$params['programme_id'];
        $user_id = get_current_user_id();

        $exercise_handle = isset($data['exercise_handle']) ? sanitize_text_field($data['exercise_handle']) : null;
        $exercise_date = isset($data['exercise_date']) ? sanitize_text_field($data['exercise_date']) : null;
        $tracking_value = isset($data['tracking_value']) ? sanitize_text_field($data['tracking_value']) : null;
        $tracking_score = isset($data['tracking_score']) ? sanitize_text_field($data['tracking_score']) : null;
        
        $post = get_post($programme_id);

        if (!$post) {
            return new \WP_REST_Response([ 'message' => "Programme #{$programme_id} not found." ], 404);
        }

        $exercise_row = $wpdb->get_row("SELECT * FROM `wp_user_exercises` WHERE `programme_id` = \"{$programme_id}\" AND `exercise_handle` = \"{$exercise_handle}\" AND `exercise_date` = {$exercise_date};", 'ARRAY_A');

        if (!$exercise_row) {
            return new \WP_REST_Response([ 'message' => "User exercise #{$programme_id} \"{$exercise_handle}\" not found." ], 404);
        }

        $wpdb->update('wp_user_exercises', [ 
            'tracking_value' => $tracking_value,
            'tracking_score' => $tracking_score
        ], [ 'id' => $exercise_row['id'] ]);
        
        $exercise_row['completed_at'] = $completed_at;

        return $exercise_row;
    }

}