<?php

namespace TeamTraining\PostTypes;

class WorkoutOfTheDay
{

    public static function init() 
    {
        add_action('init', 'TeamTraining\PostTypes\WorkoutOfTheDay::registerPostType');
        add_action('init', 'TeamTraining\PostTypes\WorkoutOfTheDay::registerFocusTaxonomy');
        add_action('rest_api_init', 'TeamTraining\PostTypes\WorkoutOfTheDay::registerRestFields');
        add_filter( 'rest_workoutoftheday_collection_params', 'TeamTraining\PostTypes\WorkoutOfTheDay::addRestOrderByParams', 10, 1 );
    }

    public static function registerPostType()
    {
        $labels = array(
            'name'                  => 'Workout',
            'singular_name'         => 'Workout of the day',
            'menu_name'             => 'Workout of the day',
            'name_admin_bar'        => 'Post Day',
            'archives'              => 'Workout of the day Archives',
            'attributes'            => 'Workout of the day Attributes',
            'parent_item_colon'     => 'Parent workout of the day:',
            'all_items'             => 'All Workouts',
            'add_new_item'          => 'Add Workout',
            'add_new'               => 'Add New Workout',
            'new_item'              => 'New WorkoutOfTheDay',
            'edit_item'             => 'Edit WorkoutOfTheDay',
            'update_item'           => 'Update WorkoutOfTheDay',
            'view_item'             => 'View WorkoutOfTheDay',
            'view_items'            => 'View WorkoutOfTheDays',
            'search_items'          => 'Search WorkoutOfTheDay',
            'not_found'             => 'No workouts found',
            'not_found_in_trash'    => 'No workouts found in Trash',
            'featured_image'        => 'Featured Image',
            'set_featured_image'    => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image'    => 'Use as featured image',
            'insert_into_item'      => 'Insert into item',
            'uploaded_to_this_item' => 'Uploaded to this item',
            'items_list'            => 'WorkoutOfTheDays list',
            'items_list_navigation' => 'WorkoutOfTheDays list navigation',
            'filter_items_list'     => 'Filter WorkoutOfTheDays list',
        );

        register_post_type( 'workoutoftheday', [
            'label'                 => 'WorkoutOfTheDay',
            'description'           => 'Collection of WorkoutOfTheDays',
            'labels'                => $labels,
            'supports'              => ['title', 'thumbnail', 'revisions', 'custom-fields', 'page-attributes', 'post-formats', 'permalink'],
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'show_in_rest'          => true,
            'has_archive'           => 'WorkoutOfTheDays',
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_day'       => 'page',
            'menu_icon'             => 'data:image/svg+xml;base64,' . base64_encode( "<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 448 512\"><path d=\"M160 32V64H288V32C288 14.33 302.3 0 320 0C337.7 0 352 14.33 352 32V64H400C426.5 64 448 85.49 448 112V160H0V112C0 85.49 21.49 64 48 64H96V32C96 14.33 110.3 0 128 0C145.7 0 160 14.33 160 32zM0 192H448V464C448 490.5 426.5 512 400 512H48C21.49 512 0 490.5 0 464V192zM308.8 267C294.1 252.3 270.2 252.3 255.5 267L240.6 281.1L293.9 335.3L308.8 320.4C323.5 305.6 323.5 281.8 308.8 267V267zM137.6 391.4L128.5 428C127.1 433.5 128.7 439.3 132.7 443.2C136.7 447.2 142.4 448.8 147.9 447.4L184.5 438.3C190.1 436.9 195.3 433.1 199.4 429.9L271.3 357.9L217.1 304.6L146.1 376.5C141.1 380.6 139 385.8 137.6 391.4H137.6z\" fill=\"black\"/></svg>"),
        ]);
    }

    public static function registerFocusTaxonomy()
    {
        $labels = [
            'name'                       => 'Focuses',
            'singular_name'              => 'Focus',
            'menu_name'                  => 'Focuses',
            'all_items'                  => 'All Focuses',
            'parent_item'                => 'Parent Focus',
            'parent_item_colon'          => 'Parent Focus:',
            'new_item_name'              => 'New Focus',
            'add_new_item'               => 'Add New Focus',
            'edit_item'                  => 'Edit Focus',
            'update_item'                => 'Update Focus',
            'view_item'                  => 'View Focus',
            'separate_items_with_commas' => 'Separate Focuses with commas',
            'add_or_remove_items'        => 'Add or remove iFocuses',
            'choose_from_most_used'      => 'Choose from the most used',
            'popular_items'              => 'Popular Focuses',
            'search_items'               => 'Search Focuses',
            'not_found'                  => 'Not Found',
            'no_terms'                   => 'No Focuses',
            'items_list'                 => 'Items list',
            'items_list_navigation'      => 'Items list navigation',
        ];

        register_taxonomy('focus', 'workoutoftheday', [
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
            'single_value'               => true
        ]);
    }

    public static function registerRestFields() 
    {

        register_rest_field( 'WorkoutOfTheDay', 'logo_url', [
            'get_callback' => function( $workoutoftheday ) {
                $logo = get_field('logo', $workoutoftheday['id']);
                return $logo ? $logo['url'] : null;
            },
            'schema' => array(
                'description' => "logo_url",
                'type'        => 'string'
            ),
        ]);
    }

}