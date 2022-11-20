<?php

$composer_file_path = get_stylesheet_directory() . '/vendor/autoload.php';

if (file_exists($composer_file_path)) {
    require $composer_file_path;
    TeamTraining\TeamTraining::init();
}

add_filter('wp_title', 'tt_fixed_home_wp_title');
add_action('wp_enqueue_scripts', 'tt_enqueue_styles');

/**
 * Enqueues styles for the theme.
 */
function tt_enqueue_styles()
{
    wp_enqueue_script('theme-script',  get_template_directory_uri() . '/site.js', [], false, true);
    wp_localize_script('theme-script', 'ajax_object', ['ajax_url' => admin_url( 'admin-ajax.php' ) ]);
    wp_enqueue_style('theme-style', get_template_directory_uri() . '/style.css?t=' . time());
}

/**
 * Customize the title for the home page, if one is not set.
 *
 * @param string $title The original title.
 * @return string The title to use.
 */
function tt_fixed_home_wp_title($title)
{
    if (empty($title) && (is_home() || is_front_page())) {
        $title = __('Home', 'textdomain') . ' | ' . get_bloginfo('description');
    }
	
    return $title;
}

add_action('after_setup_theme', function() {
	add_theme_support( 'post-thumbnails' );
});

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'App Settings',
		'menu_title'	=> 'App Settings',
		'menu_slug' 	=> 'app-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
}

add_filter( 'use_block_editor_for_post', '__return_false' );

add_filter( 'jwt_auth_expire', function( $expire, $issued_at ) {
	return $issued_at + ( DAY_IN_SECONDS * 365 );
}, 10, 2 );

add_filter( 'jwt_auth_whitelist', function ( $endpoints ) {
    $custom_endpoints = array(
        '/wp-json/tt/v1/options/*',
		'/wp-json/tt/v1/user/validate-email',
    );

    return array_unique( array_merge( $endpoints, $custom_endpoints ) );
} );

/* On valid credentail response */
add_filter(
	'jwt_auth_valid_credential_response',
	function ( $response, $user ) {

        $purchased_programmes = $wpdb->get_results("SELECT `programme_id` FROM `wp_user_programmes` WHERE `user_id` = {$user->ID};", 'ARRAY_A');

        $purchased_programmes = array_map(function($purchased_programmes) {
            return (int)$purchased_programmes['programme_id'];
        }, $results);
		
		$profile_picture = get_field('profile_picture', "user_{$user->ID}");

        $ts_last_week = time() - (60 * 60 * 24 * 7);
        $date_last_week = date('Y-m-d H:i:s', $ts_last_week);

        $exercises = $wpdb->get_results("SELECT * FROM `wp_user_exercises` WHERE `user_id` = {$user_id} AND `started_at` > \"{$date_last_week}\";");

		$response['data'] = [
			'firstName' => $user->first_name,
			'lastName' => $user->last_name,
			'email' => $user->user_email,
			'profile_image' => $profile_picture ? $profile_picture['sizes']['medium'] : "https://eu.ui-avatars.com/api/?name={$user->first_name}+{$user->last_name}&size=400",
			'token' => $token
		];

        $response['purchased_programmes'] = $purchased_programmes;
        $response['exercises'] = $exercises;

		return $response;
	},
	10,
	2
);

add_filter(
	'jwt_auth_valid_token_response',
	function ( $response, $user, $token, $payload ) {

        global $wpdb;

        $purchased_programmes = $wpdb->get_results("SELECT `programme_id` FROM `wp_user_programmes` WHERE `user_id` = {$user->ID};", 'ARRAY_A');

        $purchased_programmes = array_map(function($purchased_programmes) {
            return (int)$purchased_programmes['programme_id'];
        }, $purchased_programmes);
		
		$profile_picture = get_field('profile_picture', "user_{$user->ID}");

        $ts_last_week = time() - (60 * 60 * 24 * 7);
        $date_last_week = date('Y-m-d H:i:s', $ts_last_week);

        $exercises = $wpdb->get_results("SELECT * FROM `wp_user_exercises` WHERE `user_id` = {$user->ID} AND `started_at` > \"{$date_last_week}\";", 'ARRAY_A');

		$response['data'] = [
			'firstName' => $user->first_name,
			'lastName' => $user->last_name,
			'email' => $user->user_email,
			'profile_image' => $profile_picture ? $profile_picture['sizes']['medium'] : "https://eu.ui-avatars.com/api/?name={$user->first_name}+{$user->last_name}&size=400",
			'token' => $token
		]; 

        $response['purchased_programmes'] = $purchased_programmes;
        $response['exercises'] = $exercises;
		
		return $response;
	},
	10,
	4
);

add_action( 'rest_api_init', 'tt_register_images_field' );

function tt_register_images_field() {
    register_rest_field( 
        'post',
        'images',
        array(
            'get_callback'    => 'tt_get_images_urls',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

function tt_get_images_urls( $object, $field_name, $request ) {
    $medium = wp_get_attachment_image_src( get_post_thumbnail_id( $object->id ), 'medium' );
    $medium_url = $medium['0'];

    $large = wp_get_attachment_image_src( get_post_thumbnail_id( $object->id ), 'large' );
    $large_url = $large['0'];

    return array(
        'medium' => $medium_url,
        'large'  => $large_url,
    );
}