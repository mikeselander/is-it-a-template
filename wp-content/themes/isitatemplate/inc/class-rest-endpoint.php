<?php
namespace isItaTemplate;

class RestEndpoint {

	/**
	 * Register our REST route.
	 */
	public function hooks() {
		add_action( 'rest_api_init', function () {
			// Fetch theme information.
			register_rest_route( 'template/v1', '/fetchtheme/', [
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'fetch_themes' ],
			] );

			// Fetch vulnerabilities.
			register_rest_route( 'template/v1', '/vulnerabilities/', [
				'methods'  => \WP_REST_Server::READABLE,
				'callback' => [ $this, 'fetch_vulnerabilities' ],
			] );
		} );
	}

	/**
	 * Fetch theme information.
	 */
	public function fetch_themes( \WP_REST_Request $request ) {
		$url = $request->get_param( 'url' );

		// Make sure that we actually have a URL.
		if ( ! FetchTheme::validate_url( $url ) ) {
			return JsonResponse::error( 'Invalid URL provided.' );
		}

		// Get our theme information.
		$theme_data = FetchTheme::fetch_theme_data( esc_url( $url ) );

		if ( is_wp_error( $theme_data ) ) {
			return JsonResponse::error( $theme_data->get_error_message() );
		}

		// No theme data, sadface.
		if ( empty( $theme_data ) ) {
			return JsonResponse::error( 'Not sure, I couldn\'t find a theme 😢 This might not be a WordPress site or the resources might be concatenated.' );
		}

		// If we also want security information, send it alongside.
		if ( ! empty( $request->get_param( 'security' ) ) ) {
			$theme_data = self::assign_vulnerabilities( $theme_data, sanitize_text_field( $request->get_param( 'security' ) ) );
		}

		// Keep a record of requests so that we can identify trends and better
		// identify templates in the future.
		SaveRequest::insert( current( $theme_data, $url ) );

		return JsonResponse::success( 'Found the theme.', $theme_data );
	}

	/**
	 * Fetch theme vulnerabilities.
	 */
	public function fetch_vulnerabilities( \WP_REST_Request $request ) {
		$name = sanitize_text_field( $request->get_param( 'theme_name' ) );

		return JsonResponse::success( 'Vulnerabilities', FetchVulnerabilities::fetch( $name ) );
	}
}
