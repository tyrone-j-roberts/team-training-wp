<?php

namespace TeamTraining\API;

class Leaderboards
{

    public static function getLeaderboards(\WP_REST_Request $request)
    {
        global $wpdb;

        $query = $request->get_query_params();
        $limit = isset($query['limit']) ? (int)$query['limit'] : 15;

        $results = $wpdb->get_results("SELECT * FROM `wp_user_exercises` WHERE `tracking_score` IS NOT NULL GROUP BY `exercise_handle` ORDER BY `tracking_score` DESC LIMIT {$limit};", "ARRAY_A");
        $participant_results = $wpdb->get_results("SELECT COUNT(`id`) AS count, `exercise_handle` FROM `wp_user_exercises` WHERE `tracking_score` IS NOT NULL GROUP BY `exercise_handle`;", 'ARRAY_A');

        $leaderboards = [];

        foreach($results as $index => $result) {
            $leaderboards[] = [
                'name' => $result['exercise_title'],
                'handle' => $result['exercise_handle'],
                'participants' =>  $participant_results[$index]['count']
            ];
        }

        return [
            'leaderboards' => $leaderboards
        ];
    }

    public static function getLeaderboard(\WP_REST_Request $request) 
    {
        global $wpdb;

        $params = $request->get_url_params();
        $exercise_handle = $params['exercise_handle'];

        $sql = <<< SQL
        SELECT a.`id`, a.`exercise_handle`, a.`exercise_title`, a.`tracking_value`, b.`display_name` 
        FROM `wp_user_exercises` AS a
        JOIN `wp_users` AS b ON a.`user_id` = b.`ID`
        WHERE `tracking_score` IS NOT NULL 
        AND `exercise_handle` = "{$exercise_handle}" 
        ORDER BY `tracking_score` DESC 
        LIMIT 100;
        SQL;

        $results = $wpdb->get_results($sql);
    
        return [
            'leaderboard' => $results
        ];
    }

}