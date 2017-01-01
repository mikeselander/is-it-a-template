<?php
namespace isItaTemplate;

class SaveRequest {

	private static function _table() {
        global $wpdb;
        return $wpdb->prefix . 'requests';
    }

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
		];
	}

	public static function insert( $data ) {
        global $wpdb;
		$data = self::clean_data( $data );
        $wpdb->insert( self::_table(), $data );
    }
}
