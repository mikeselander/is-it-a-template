<?php
namespace isItaTemplate;

class IsTemplate {

	/**
	 * All theme shops known to us.
	 */
	public $theme_shops;

	/**
	 * Load in our theme shops file.
	 */
	public function fetch_theme_shops() {
		$filename = get_template_directory() . '/assets/data/theme-shops.json';

		if ( is_readable( $filename ) ) {
			$this->theme_shops = json_decode( file_get_contents( $filename ), true );
		}
	}

	/**
	 * Getter for checking whether a theme is a template or not.
	 *
	 * @param array $theme Theme information to compare.
	 * @return boolean true for template, false for custom
	 */
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

	/**
	 * Check whether it has a child theme or not.
	 *
	 * This is both an indication of a template and that a user has attempted to customise it.
	 *
	 * @param array $theme Theme information to compare.
	 * @return boolean true for template, false for custom
	 */
	public function has_child_theme( $theme ) {
		return ( ! empty( $theme['child'] ) );
	}

	/**
	 * Check common template names.
	 *
	 * @param array $theme Theme information to compare.
	 * @return boolean true for template, false for custom
	 */
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

	/**
	 * Check against know theme shop URLs. Check both theme and author URLs.
	 *
	 * @param array $theme Theme information to compare.
	 * @return boolean true for template, false for custom
	 */
	public function check_theme_url( $theme ) {
		foreach( $this->theme_shops as $name => $url ) {
			if ( false !== stripos( $theme['ThemeURI'], $url ) || false !== stripos( $theme['AuthorURI'], $url ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check against know template authors.
	 *
	 * @param array $theme Theme information to compare.
	 * @return boolean true for template, false for custom
	 */
	public function check_author_name( $theme ) {
		if ( array_key_exists( $theme['Author'], $this->theme_shops ) ) {
			return true;
		}

		return false;
	}
}
