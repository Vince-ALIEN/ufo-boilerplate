<?php
/**
 * Enqueue custom scripts added on top of the default theme boilerplate.
 *
 * The default boilerplate enqueues (`ufo-boilerplate-style`,
 * `ufo-boilerplate-script`, `ufo-boilerplate-editor`) stay in functions.php.
 * This file is for additional, theme-specific scripts only, so functions.php
 * doesn't keep growing as more are added.
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( '_ufo_custom_scripts' ) ) :
	/**
	 * Enqueue the theme's custom front-end scripts.
	 */
	function _ufo_custom_scripts() {
		wp_enqueue_script(
			'ufo-boilerplate-headroom',
			get_template_directory_uri() . '/js/headroom.min.js',
			array(),
			_UFO_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);
	}
endif;
add_action( 'wp_enqueue_scripts', '_ufo_custom_scripts' );
