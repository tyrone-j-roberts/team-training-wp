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
            'new_item'              => 'New Workout',
            'edit_item'             => 'Edit Workout',
            'update_item'           => 'Update Workout',
            'view_item'             => 'View Workout',
            'view_items'            => 'View Workouts',
            'search_items'          => 'Search Workout',
            'not_found'             => 'No workouts found',
            'not_found_in_trash'    => 'No workouts found in Trash',
            'featured_image'        => 'Featured Image',
            'set_featured_image'    => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image'    => 'Use as featured image',
            'insert_into_item'      => 'Insert into item',
            'uploaded_to_this_item' => 'Uploaded to this item',
            'items_list'            => 'Workout list',
            'items_list_navigation' => 'Workout list navigation',
            'filter_items_list'     => 'Filter Workout list',
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
            'menu_icon'             => 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M128 0c17.7 0 32 14.3 32 32V64H288V32c0-17.7 14.3-32 32-32s32 14.3 32 32V64h48c26.5 0 48 21.5 48 48v48H0V112C0 85.5 21.5 64 48 64H96V32c0-17.7 14.3-32 32-32zM0 192H448V464c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V192zm80 64c-8.8 0-16 7.2-16 16v96c0 8.8 7.2 16 16 16h96c8.8 0 16-7.2 16-16V272c0-8.8-7.2-16-16-16H80z" fill="currentColor" /></svg>'),
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