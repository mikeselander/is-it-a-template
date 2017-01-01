<?php
namespace isItaTemplate;

class IsTemplate {

	public $theme_shops;

	public function fetch_theme_shops() {
		$filename = get_template_directory() . '/assets/data/theme-shops.json';

		if ( is_readable( $filename ) ) {
			$this->theme_shops = json_decode( file_get_contents( $filename ), true );
		}
	}

	public function check_theme( $theme ) {
		$this->fetch_theme_shops();

		if (
			$this->check_theme_name( $theme )
			|| $this->check_theme_url( $theme )
			|| $this->check_author_name( $theme )
		) {
			return true;
		}
		return false;
	}

	public function has_child_theme( $theme ) {
		return ( ! empty( $theme['child'] ) );
	}

	public function check_theme_name( $theme ) {
		$known_templates = [
			'divi',
			'avada',
			'oshin',
			'soledad',
			'uncode',
			'jevelin',
			'merchandiser',
			'thegem',
			'total',
			'shopkeeper',
			'x',
			'xstore', // 'xstore-default'
			'story', // 'thestory'
			'flatsome',
			'betheme', // 'be'
			'bridge',
			'monstroid',
			'hcode',
			'brando',
			'focuson',
			'brooklyn',
			'salient',
			'the7', // 'dt-the7'
			'amax',
			'specular',
			'brixton - wordpress theme', // 'brixton'
		];

		if ( in_array( strtolower( $theme['Name'] ), $known_templates ) ) {
			return true;
		}

		return false;
	}

	public function check_theme_url( $theme ) {
		foreach( $this->theme_shops as $name => $url ) {
			if ( false !== stripos( $theme['ThemeURI'], $url ) || false !== stripos( $theme['AuthorURI'], $url ) ) {
				return true;
			}
		}

		return false;
	}

	public function check_author_name( $theme ) {
		if ( array_key_exists( $theme['Author'], $this->theme_shops ) ) {
			return true;
		}

		return false;
	}
}
