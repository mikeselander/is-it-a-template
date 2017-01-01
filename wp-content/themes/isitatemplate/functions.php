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
