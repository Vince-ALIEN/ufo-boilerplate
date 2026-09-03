<?php
/**
 * Registers the "Header CTA" Customizer section
 *
 * Manages the text/URL of the "Sign up" button used by
 * `template-parts/layout/header-content.php`.
 *
 * @package Ufo_Boilerplate
 */

if ( ! class_exists( '_Ufo_Header_Cta' ) ) {
	class _Ufo_Header_Cta {

		const SECTION_ID   = 'ufo_header_cta';
		const TEXT_SETTING = 'ufo_header_cta_text';
		const URL_SETTING  = 'ufo_header_cta_url';

		/**
		 * Hooks the Customizer registration into `customize_register`.
		 */
		public static function init() {
			add_action( 'customize_register', array( __CLASS__, 'register' ) );
		}

		/**
		 * @param WP_Customize_Manager $wp_customize Customizer instance.
		 */
		public static function register( $wp_customize ) {
			$wp_customize->add_section(
				self::SECTION_ID,
				array(
					'title' => __( 'Header CTA', 'ufo-boilerplate' ),
					'panel' => _Ufo_Options::PANEL_ID,
				)
			);

			$wp_customize->add_setting(
				self::TEXT_SETTING,
				array(
					'default'           => __( 'Sign up', 'ufo-boilerplate' ),
					'sanitize_callback' => 'sanitize_text_field',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				self::TEXT_SETTING,
				array(
					'type'    => 'text',
					'label'   => __( 'Button text', 'ufo-boilerplate' ),
					'section' => self::SECTION_ID,
				)
			);

			$wp_customize->add_setting(
				self::URL_SETTING,
				array(
					'default'           => '',
					'sanitize_callback' => 'esc_url_raw',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				self::URL_SETTING,
				array(
					'type'        => 'url',
					'label'       => __( 'Button URL', 'ufo-boilerplate' ),
					'description' => __( 'Leave empty to hide the button.', 'ufo-boilerplate' ),
					'section'     => self::SECTION_ID,
				)
			);
		}

		/**
		 * Returns the configured button text.
		 */
		public static function text() {
			return get_theme_mod( self::TEXT_SETTING, __( 'Sign up', 'ufo-boilerplate' ) );
		}

		/**
		 * Returns the configured button URL, or an empty string if none was
		 * set — callers should hide the button in that case.
		 */
		public static function url() {
			return get_theme_mod( self::URL_SETTING, '' );
		}
	}

	_Ufo_Header_Cta::init();
}
