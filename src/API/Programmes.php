<?php

namespace TeamTraining\API;

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

}