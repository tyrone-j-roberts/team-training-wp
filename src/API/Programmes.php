<?php

namespace TeamTraining\API;

class Programmes
{

    public static function getProgrammes()
    { 
        $posts = get_posts([
            'post_type' => 'programme',
            'posts_per_page' => -1
        ]);

        $programmes = [];

        foreach($posts as $post) {

            $focus = get_the_terms($post, 'focus');

            $header_image = get_field('header_image', $post);
            $post_thumbnail = has_post_thumbnail($post) ? get_the_post_thumbnail_url( $post, 'large' ) : null;

            $programmes[] = [
                'id' => $post->ID,
                'name' => $post->post_title,
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