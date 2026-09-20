<?php
/**
 * Excludes the image block from Tailwind Typography's `.prose` styles
 *
 * @package Ufo_Boilerplate
 */

if ( ! class_exists( '_Ufo_Image_Block' ) ) {

	/**
	 * Adds `not-prose` to the core image block output.
	 */
	class _Ufo_Image_Block implements _Ufo_Service {

		/**
		 * Hooks the render filter.
		 */
		public static function register() {
			add_filter( 'render_block_core/image', array( __CLASS__, 'add_not_prose_class' ) );
		}

		/**
		 * Add `not-prose` to the image block so Tailwind Typography's `.prose`
		 * styles (max-width, margin, etc.) don't apply to it.
		 *
		 * @param string $block_content Rendered block HTML.
		 * @return string
		 */
		public static function add_not_prose_class( $block_content ) {
			return preg_replace( '/\bclass="wp-block-image/', 'class="not-prose wp-block-image', $block_content, 1 );
		}
	}
}
