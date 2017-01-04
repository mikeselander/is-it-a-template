<?php
namespace isItaTemplate;

use Goutte\Client;

class FetchTheme {
	public static $file_headers = array(
		'Name'        => 'Theme Name',
		'ThemeURI'    => 'Theme URI',
		'Description' => 'Description',
		'Author'      => 'Author',
		'AuthorURI'   => 'Author URI',
		'Version'     => 'Version',
		'Template'    => 'Template',
		'Tags'        => 'Tags',
	);

	public static function fetch_theme_data( $url ) {
		if ( ! self::validate_url( $url ) ) {
			return;
		};

		try {
			$crawler = self::fetch_site( $url );
		} catch ( \Exception $e ) {
			return new \WP_Error( 'fetch-theme', $e->getMessage(), 'my best' );
		}

		// @todo:: also fetch javascript files.
		// @todo:: how to get past concatenators?
		$links = $crawler->filter( 'link' )->reduce( function ( $node, $i ) {
			return ( false !== strpos( $node->attr( 'href' ), 'themes' ) );
		} );

		$scripts = $crawler->filter( 'script' )->reduce( function ( $node, $i ) {
			return ( false !== strpos( $node->attr( 'src' ), 'themes' ) );
		} );

		$themes = array_merge( self::find_stylesheet( $links, 'href' ), self::find_stylesheet( $scripts, 'src' ) );

		try {
			foreach ( $themes as $key => $value ) {
				$data = @get_file_data( $value['url'] . 'style.css', self::$file_headers );
				$themes[ $key ] = array_merge( $value, $data );

				if ( empty( $data['Name'] ) ) {
					throw new \Exception( 'Not sure 💩 I couldn\'t access the theme stylesheet.' );
				}
			}
		} catch ( \Exception $e ) {
			return new \WP_Error( 'fetch-theme', $e->getMessage(), 'my best' );
		}

		$themes = self::assign_children_themes( $themes );
		$themes = self::assign_template_status( $themes );

		return $themes;
	}

	private static function find_stylesheet( $links, $attribute ) {
		$themes = [];
		foreach( $links as $link ) {
			preg_match( '/http(s\:|\:).*\/themes\/(.*?)\//', $link->getAttribute( $attribute ), $matches );

			if ( empty( $matches[0] ) || isset($themes[ $matches[1] ]) ) {
				continue;
			}

			$themes[ $matches[2] ] = [
				'url' => $matches[0],
			];
		}

		return $themes;
	}

	private static function fetch_site( $url ) {
		$client = new Client();
		return $client->request( 'GET', $url );
	}

	private static function assign_children_themes( $themes ) {
		if ( empty( $themes ) ) {
			return [];
		}

		foreach ( $themes as $key => $value ) {
			if ( empty( $value['Template'] ) || ! isset( $themes[ $value['Template'] ] ) ) {
				continue;
			}

			$themes[ $value['Template'] ]['child'] = $value;
			unset( $themes[ $key ] );
		}

		return $themes;
	}

	private static function assign_template_status( $themes ) {
		if ( empty( $themes ) ) {
			return [];
		}

		foreach ( $themes as $key => $value ) {
			$is_template = ( new IsTemplate() )->check_theme( $value );
			$themes[ $key ]['isTemplate']   = $is_template;
			$themes[ $key ]['templateText'] = ( $is_template ) ? '👀 This is a Template 👀' : '🎉 This is a Custom Theme 🎉';
			$themes[ $key ]['hasChild']     = ( new IsTemplate() )->has_child_theme( $value );
		}

		return $themes;
	}

	public static function assign_vulnerabilities( $themes, $type ) {
		if ( empty( $themes ) ) {
			return [];
		}

		foreach ( $themes as $key => $value ) {
			$themes[ $key ]['vulnerabilities'] = FetchVulnerabilities::fetch( $key, $value );
		}

		return $themes;
	}

	public static function validate_url( $url ) {
		return ( ! filter_var( $url, FILTER_VALIDATE_URL ) === false );
	}
}
