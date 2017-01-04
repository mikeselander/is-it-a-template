<?php
namespace isItaTemplate;

class SaveRequest {

	/**
	 * Define our table prefix.
	 */
	private static function _table() {
        global $wpdb;
        return $wpdb->prefix . 'requests';
    }

	/**
	 * Clean and whitelist the data according to key.
	 *
	 * @param array $data
	 */
	public static function clean_data( $data ) {
		return [
			'url'         => sanitize_url( $data['url'] ),
			'theme_name'  => sanitize_text_field( $data['Name'] ),
			'theme_uri'   => sanitize_url( $data['ThemeURI'] ),
			'author'      => sanitize_text_field( $data['Author'] ),
			'author_uri'  => sanitize_url( $data['AuthorURI'] ),
			'version'     => sanitize_text_field( $data['Version'] ),
			'child_theme' => ( isset( $data['child'] ) ) ? sanitize_text_field( json_encode( $data['child'] ) ) : '',
			'is_template' => ( $data['isTemplate'] ) ? true : false,
			'ip_address'  => self::get_ip_address(),
		];
	}

	/**
	 * Fetch the user's IP address.
	 */
	public static function get_ip_address() {
		return getenv('HTTP_CLIENT_IP')?:
			   getenv('HTTP_X_FORWARDED_FOR')?:
			   getenv('HTTP_X_FORWARDED')?:
			   getenv('HTTP_FORWARDED_FOR')?:
			   getenv('HTTP_FORWARDED')?:
			   getenv('REMOTE_ADDR');
	}

	/**
	 * Insert our data into the dtabase.
	 *
	 * @param array $data Information about the request.
	 */
	public static function insert( $data ) {
        global $wpdb;
		$data = self::clean_data( $data );
        $wpdb->insert( self::_table(), $data );
    }
}
