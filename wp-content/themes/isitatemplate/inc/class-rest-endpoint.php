<?php
namespace isItaTemplate;

class RestEndpoint {

	public function hooks() {
		add_action( 'rest_api_init', function () {
			register_rest_route( 'template/v1', '/fetchtheme/', array(
				'methods'  => 'GET',
				'callback' => [ $this, 'fetch_themes' ],
			) );
		} );
	}

	public function fetch_themes( \WP_REST_Request $request ) {
		$url = $request->get_param( 'url' );

		if ( ! FetchTheme::validate_url( $url ) ) {
			return JsonResponse::error( 'Invalid URl provided.' );
		}

		$theme_data = FetchTheme::fetch_theme_data( esc_url( $url ) );

		if ( is_wp_error( $theme_data ) ) {
			return JsonResponse::error( $theme_data->get_error_message() );
		}

		if ( empty( $theme_data ) ) {
			return JsonResponse::error( 'Not sure, I couldn\'t find a theme 😢 This might not be a WordPress site or the resources might be concatenated.' );
		}

		// Keep a record of requests so that we can identify trends and better
		// identify templates in the future.
		SaveRequest::insert( current( $theme_data ) );

		return JsonResponse::success( 'Found the theme.', $theme_data );
	}
}
