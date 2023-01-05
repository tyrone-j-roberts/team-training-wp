<?php

namespace TeamTraining\API;

use Carbon\Carbon;

class Programmes
{

    public static function getProgrammes(\WP_REST_Request $request)
    { 

        global $wpdb;

        $params = $request->get_params();

        $limit = isset($params['limit']) ? intval($params['limit']) : -1;
        $my_programmes = isset($params['user_programmes']) ? $params['user_programmes'] == 1 : false;
        $query_args = [
            'post_type' => 'programme',
            'posts_per_page' => $limit,
            'orderby' => 'date'
        ];

        if ($my_programmes) {
            $user_id = get_current_user_id();
            $results = $wpdb->get_results("SELECT `programme_id` FROM `wp_user_programmes` WHERE `user_id` = {$user_id};", 'ARRAY_A');
            $query_args['post__in'] = array_map(function($result) {
                return $result['programme_id'];
            }, $results);
        }

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
                'programme_cycles' => get_field('programme_cycles', $post),
                'workout_of_the_day' => false,
                'workout_of_the_week' => false
            ];
        }

        $response = new \WP_REST_Response([ 'programmes' => $programmes ]);

        return $response;
    }

    public static function getWorkoutOfTheDay(\WP_REST_Request $request) 
    {
        $posts = get_posts([
            'post_type' => 'workoutoftheday',
            'posts_per_page' => 1,
            'orderby' => 'date'
        ]);

        $workout = null;

        if (count($posts) > 0) {
            
            $post = $posts[0];

            $focus = get_the_terms($post, 'focus');

            $header_image = get_field('header_image', $post);
            $post_thumbnail = has_post_thumbnail($post) ? get_the_post_thumbnail_url( $post, 'large' ) : null;
            $display_title = get_field('display_title', $post);

            $workout = [
                'id' => $post->ID,
                'name' => !empty($display_title) ? $display_title : $post->post_title,
                'price' => 0,
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
                'programme_cycles' => get_field('programme_cycles', $post),
                'workout_of_the_day' => true,
                'workout_of_the_week' => false
            ];

        }

        return new \WP_REST_Response([ 'workout' => $workout ]);
    }

    public static function getWorkoutOfTheWeek(\WP_REST_Request $request) 
    {
        $posts = get_posts([
            'post_type' => 'workoutoftheweek',
            'posts_per_page' => 1,
            'orderby' => 'date'
        ]);

        $workout = null;

        if (count($posts) > 0) {
            
            $post = $posts[0];

            $focus = get_the_terms($post, 'focus');

            $header_image = get_field('header_image', $post);
            $post_thumbnail = has_post_thumbnail($post) ? get_the_post_thumbnail_url( $post, 'large' ) : null;
            $display_title = get_field('display_title', $post);

            $workout = [
                'id' => $post->ID,
                'name' => !empty($display_title) ? $display_title : $post->post_title,
                'price' => 0,
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
                'programme_cycles' => get_field('programme_cycles', $post),
                'workout_of_the_day' => false,
                'workout_of_the_week' => true
            ];

        }

        return new \WP_REST_Response([ 'workout' => $workout ]);
    }

    public static function getExercises(\WP_REST_Request $request)
    {
        $params = $request->get_url_params();
        $query_params = $request->get_query_params();
        $programme_id = $params['programme_id'];

        $post = get_post($programme_id);

        $accepted_post_types = ['programme', 'workoutoftheday', 'workoutoftheweek'];

        if (!$post || !in_array($post->post_type, $accepted_post_types)) {
            return new \WP_REST_Response([ 'message' => "Programme #{$programme_id} not found." ], 404);
        }

        $response = [];
      
        if ($post->post_type == 'workoutoftheday') {
            
            $response = [
                'past_exercises' => [],
                'current_exercises' => []
            ];

            $day = get_field('day', $post->ID);

            if (!empty($day['exercises'])) {
                foreach($day['exercises'] as $key => $value) {
                    $day['exercises'][$key]['handle'] = sanitize_title_with_dashes("{$value['title']}");
                } 
            }

            $today = Carbon::now()->startOfDay();

            $exercise_day = [
                'date' => $today,
                'today' => true,
                'day' => $day
            ];

            $response['current_exercises'] = [$exercise_day];

        } else {
            //$programme_start_carbon = Carbon::createFromFormat('Y-m-d H:i:s', $post->post_date)->startOfDay();
            $today = Carbon::now()->startOfDay();
            $programme_start_carbon = Carbon::now()->startOfDay();

            $exercises = null;

            if ($post->post_type == 'workoutoftheweek') {
                $week = get_field('week', $post->ID);
                $exercises = [$week];
            } else {
                $exercises = get_field('weeks', $post->ID);
            }
   
    
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
        }

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

        $accepted_post_types = ['programme', 'workoutoftheday', 'workoutoftheweek'];

        if (!$post || !in_array($post->post_type, $accepted_post_types)) {
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

    public static function userCompleteWorkout(\WP_REST_Request $request)
    {
        global $wpdb;

        $params = $request->get_url_params();
        $data = $request->get_json_params();
        $programme_id = (int)$params['programme_id'];
        $workout_date = $data['workout_date'];
        $user_id = get_current_user_id();
        $post = get_post($programme_id);

        if (!$post) {
            return new \WP_REST_Response([ 'message' => "Programme #{$programme_id} not found." ], 404);
        }

        $completed_workout_row = $wpdb->get_row("SELECT * FROM `wp_completed_workouts` WHERE `programme_id` = \"{$programme_id}\" AND `date` = \"{$workout_date}\";", 'ARRAY_A');

        if (!$completed_workout_row) {

            $completed_workout_row = [
                'programme_id' => $programme_id,
                'date' => $workout_date,
                'completed_at' => date('Y-m-d H:i:s'),
                'user_id' => $user_id
            ];

            $wpdb->insert('wp_completed_workouts', $completed_workout_row);

            $completed_workout_row['id'] = $wpdb->insert_id;
        }

        return $completed_workout_row;
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

        $exercise_row = $wpdb->get_row("SELECT * FROM `wp_user_exercises` WHERE `programme_id` = \"{$programme_id}\" AND `exercise_handle` = \"{$exercise_handle}\" AND `exercise_date` = \"{$exercise_date}\";", 'ARRAY_A');

        if (!$exercise_row) {
            return new \WP_REST_Response([ 'message' => "User exercise #{$programme_id} \"{$exercise_handle}\" not found." ], 404);
        }

        $completed_at = date('Y-m-d H:i:s');

        $wpdb->update('wp_user_exercises', [ 
            'tracking_value' => $tracking_value,
            'tracking_score' => $tracking_score,
            'completed_at' => $completed_at
        ], [ 'id' => $exercise_row['id'] ]);
        
        $exercise_row['tracking_value'] = $tracking_value;
        $exercise_row['tracking_score'] = $tracking_score;
        $exercise_row['completed_at'] = $completed_at;

        return $exercise_row;
    }

}