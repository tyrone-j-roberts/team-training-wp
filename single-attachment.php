<?php 
global $post;

$url = wp_get_attachment_url( $post->ID );
wp_redirect($url);
exit;