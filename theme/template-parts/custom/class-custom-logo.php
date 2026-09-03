<?php
/**
 * Registers custom logo support, used by the header template part
 *
 * @package Ufo_Boilerplate
 */

if ( ! class_exists( '_Ufo_Custom_Logo' ) ) {

	/**
	 * Registers `custom-logo` theme support.
	 */
	class _Ufo_Custom_Logo {

		/**
		 * Hooks the theme support registration into `after_setup_theme`, so
		 * it runs on every request, including in the Customizer.
		 */
		public static function init() {
			add_action( 'after_setup_theme', array( __CLASS__, 'add_support' ) );
		}

		/**
		 * Registers the site logo, cropped to 50×50 pixels.
		 */
		public static function add_support() {
			add_theme_support(
				'custom-logo',
				array(
					'height' => 50,
					'width'  => 50,
				)
			);
		}
	}

	_Ufo_Custom_Logo::init();
}
