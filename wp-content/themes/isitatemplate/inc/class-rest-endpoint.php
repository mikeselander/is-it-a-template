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

		// Check if theme has already been run.
		// Also, check if into if outdated by say, 30 days.
		if ( $existing_theme = $this->check_if_theme_has_run( $url ) ) {
			return JsonResponse::success( 'Found the theme.', $existing_theme );
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
		SaveRequest::insert( current( $theme_data ), $url );

		return JsonResponse::success( 'Found the theme.', $theme_data );
	}

	/**
	 * Check first if theme data already exists in the database. If so, return it.
	 *
	 * @param string $url
	 */
	private function check_if_theme_has_run( $url ) {
		global $wpdb;

		$info = $wpdb->get_row(
			$wpdb->prepare( "SELECT * FROM {$wpdb->prefix}requests WHERE site_url = %s", str_replace( [ 'http://', 'https://' ], '', $url ) )
		 );

		 if ( $info ) {
			 return [
				 sanitize_title( $info->theme_name ) => [
					'url'         => $info->url,
		 			'Name'        => $info->theme_name,
		 			'ThemeURI'    => $info->theme_uri,
		 			'Author'      => $info->author,
		 			'AuthorURI'   => $info->author_uri,
		 			'Version'     => $info->version,
					'Description' => $info->description,
					'templateText' => ( $info->is_template ) ? '🤖 Yep, it is a Template 🤖' : '🎉 This is a Custom Theme 🎉'
				]
			];
		 }

		 return false;
	}

	/**
	 * Fetch theme vulnerabilities.
	 */
	public function fetch_vulnerabilities( \WP_REST_Request $request ) {
		$name = sanitize_text_field( $request->get_param( 'theme_name' ) );

		return JsonResponse::success( 'Vulnerabilities', FetchVulnerabilities::fetch( $name ) );
	}
}
