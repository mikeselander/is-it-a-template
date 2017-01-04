<?php

namespace isItaTemplate;

// Required our Client library.
require 'vendor/autoload.php';

/**
 * Autoloader callback.
 *
 * Converts a class name to a file path and requires it if it exists.
 *
 * @param string $class Class name.
 */
function theme_autoloader( $class ) {
$namespace = explode( '\\', $class );

	if ( __NAMESPACE__ !== $namespace[0] ) {
		return;
	}

	$class = str_replace( __NAMESPACE__ . '\\', '', $class );
	$class = strtolower( preg_replace( '/(?<!^)([A-Z])/', '-\\1', $class ) );
	$class = str_replace( '_', '-', $class );
	$file  = dirname( __FILE__ ) . '/inc/class-' . $class . '.php';

	if ( is_readable( $file ) ) {
		require_once( $file );
	}
}
spl_autoload_register( __NAMESPACE__ . '\\theme_autoloader' );

// Register our endpoint.
( new RestEndpoint() )->hooks();

/**
 *
 */
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style( 'main', get_template_directory_uri() . '/assets/css/main.css', [] );
	wp_enqueue_style( 'fonts', 'https://fonts.googleapis.com/css?family=Average|Fjalla+One', [] );
	wp_enqueue_script( 'app', get_template_directory_uri() . '/app/build/static/js/main.js', [], '1.0', true );
} );

/**
 * Disable the emojis
 */
add_action( 'init', function() {

	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );

} );

/**
 * Filter function used to remove the tinymce emoji plugin.
 *
 * @param    array  $plugins
 * @return   array             Difference betwen the two arrays
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}
