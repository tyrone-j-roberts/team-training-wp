<?php

namespace TeamTraining\API;

class Tracking
{

    public static function getData()
    { 
        global $wpdb;

        $user_id = get_current_user_id();

        $workouts_completed = $wpdb->get_var("SELECT COUNT(`id`) FROM `wp_completed_workouts` WHERE `user_id` = {$user_id};");
        $exercises_completed = $wpdb->get_var("SELECT COUNT(`id`) FROM `wp_user_exercises` WHERE `user_id` = {$user_id} AND `completed_at` IS NOT NULL;");
        $workout_duration_seconds = $wpdb->get_var("SELECT SUM(TIMESTAMPDIFF(SECOND, `started_at`, `completed_at`)) AS d FROM `wp_user_exercises` WHERE `user_id` = {$user_id} AND `completed_at` IS NOT NULL");
       
        $workout_duration_hours = floor($workout_duration_seconds / 3600);
        $workout_duration_minutes = floor(($workout_duration_seconds / 60) % 60);

        $response = new \WP_REST_Response([ 'tracking' => [
            'workouts_completed' => $workouts_completed,
            'exercises_completed' => $exercises_completed,
            'workout_time' => [
                'hours' => $workout_duration_hours,
                'minutes' => $workout_duration_minutes
            ]
        ]]);

        return $response;
    }

}