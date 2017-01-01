<?php
namespace isItaTemplate;

class IsTemplate {

	public function check_theme( $theme ) {
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
		$known_template_shops = [
			'wordpress.org',
			'themeforest',
			'themelovin',
			'woothemes',
			'elegantthemes',
			'theme.co',
			'8theme',
			'pexetothemes',
			'themes.muffingroup',
			'qodethemes',
			'templatemonster',
			'themezaa',
			'ninzio',
			'unitedthemes',
			'dream-theme',
			'premiumcoding',
			'studiopress',
			'churchthemes',
			'mhthemes',
			'themezilla',

		];

		foreach( $known_template_shops as $template_shop ) {
			if ( false !== stripos( $theme['ThemeURI'], $template_shop ) || false !== stripos( $theme['AuthorURI'], $template_shop ) ) {
				return true;
			}
		}

		return false;
	}

	public function check_author_name( $theme ) {
		$known_template_authors = [
			'Themelovin',
			'WPExplorer',
			'Themeco',
			'8theme',
			'Pexeto',
			'Muffin group',
			'Qode Interactive',
			'ThemeZaa',
			'Ninzio Team',
			'United Themes',
			'Dream-Theme',
			'gljivec',
			'StudioPress',
			'Church Themes',
			'AudioTheme',
			'Meridian Themes',
			'StrictThemes',
			'PixelGrade',
			'Graph Paper Press'
		];

		if ( in_array( $theme['Author'], $known_template_authors ) ) {
			return true;
		}

		return false;
	}
}
