<?php

$composer_file_path = get_stylesheet_directory() . '/vendor/autoload.php';

if (file_exists($composer_file_path)) {
    require $composer_file_path;
    TeamTraining\TeamTraining::init();
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
		return $response;
	},
	10,
	2
);

add_filter(
	'jwt_auth_valid_token_response',
	function ( $response, $user, $token, $payload ) {
		
		$profile_picture = get_field('profile_picture', "user_{$user->ID}");

		$response['data'] = [
			'firstName' => $user->first_name,
			'lastName' => $user->last_name,
			'email' => $user->user_email,
			'profile_image' => $profile_picture ? $profile_picture['sizes']['medium'] : "https://eu.ui-avatars.com/api/?name={$user->first_name}+{$user->last_name}&size=400",
			'token' => $token
		];
		
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