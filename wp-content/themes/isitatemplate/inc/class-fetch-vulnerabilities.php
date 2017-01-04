<?php
namespace isItaTemplate;

class FetchVulnerabilities {
	public static function fetch( $theme_name, $theme ) {
		$vulnerabilities = self::fetch_from_wpvulndb( $theme_name );

		$active_vulnerabilities = array_filter( $vulnerabilities, function( $vuln ) use ( $theme ) {
			if ( null !== $vuln->fixed_in && version_compare( $theme['Version'], $vuln->fixed_in, '<' ) ) {
				return true;
			}
			return false;
		} );

		return [
			'all'    => $vulnerabilities,
			'active' => $active_vulnerabilities,
		];
	}

	private static function fetch_from_wpvulndb( $theme_name ) {
		$url = 'https://wpvulndb.com/api/v2/themes/' . $theme_name;

		$ch = curl_init( $url );
	    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	    curl_exec( $ch );
	    $retCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		$data = json_decode( curl_exec( $ch ) );
	    curl_close( $ch );

		// Need to check that the data returned ad is an array.
		$data = current( $data );

		return $data->vulnerabilities;
	}
}
