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
	class _Ufo_Custom_Logo implements _Ufo_Service {

		/**
		 * Hooks the theme support registration into `after_setup_theme`, so
		 * it runs on every request, including in the Customizer.
		 */
		public static function register() {
			add_action( 'after_setup_theme', array( __CLASS__, 'add_support' ) );
			add_filter( 'get_custom_logo_image_attributes', array( __CLASS__, 'add_size_class' ) );
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

		/**
		 * Constrains the header logo `<img>` to 50×50 pixels as a safety
		 * net — `get_custom_logo()` always renders the `full`, uncropped
		 * size of the logo attachment, so this only matters if that
		 * attachment wasn't actually cropped to 50×50 through the
		 * Customizer's crop tool. Keep the `size-12.5` below in sync with
		 * the `add_support()` dimensions above, and with
		 * `footer_logo_html()`'s call in `footer-content.php`.
		 *
		 * @param array $attr Image attributes for the custom logo `<img>` tag.
		 * @return array
		 */
		public static function add_size_class( $attr ) {
			$attr['class'] = trim( ( $attr['class'] ?? '' ) . ' size-12.5 object-contain' );
			return $attr;
		}
	}
}
