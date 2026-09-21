<?php
/**
 * Registers the "Ufo options" Customizer panel and the footer logo setting
 *
 * Other option classes (e.g. `class-social-media.php`) hook their own
 * sections into `_Ufo_Options::PANEL_ID` so everything theme-specific lives
 * under a single Customizer panel.
 *
 * @package Ufo_Boilerplate
 */

if ( ! class_exists( '_Ufo_Options' ) ) {

	/**
	 * Registers the "Ufo options" Customizer panel and footer logo setting.
	 */
	class _Ufo_Options implements _Ufo_Service {

		const PANEL_ID            = 'ufo_options';
		const FOOTER_LOGO_SETTING = 'ufo_footer_logo';

		/**
		 * Hooks the Customizer registration into `customize_register`.
		 */
		public static function register() {
			add_action( 'customize_register', array( __CLASS__, 'register_customizer' ) );
		}

		/**
		 * Registers the panel and the footer logo section/setting/control.
		 *
		 * @param WP_Customize_Manager $wp_customize Customizer instance.
		 */
		public static function register_customizer( $wp_customize ) {
			$wp_customize->add_panel(
				self::PANEL_ID,
				array(
					'title'       => __( 'Ufo options', 'ufo-boilerplate' ),
					'description' => __( 'Theme-specific settings for Ufo Boilerplate.', 'ufo-boilerplate' ),
					'priority'    => 160,
				)
			);

			$wp_customize->add_section(
				'ufo_footer_logo',
				array(
					'title' => __( 'Footer Logo', 'ufo-boilerplate' ),
					'panel' => self::PANEL_ID,
				)
			);

			$wp_customize->add_setting(
				self::FOOTER_LOGO_SETTING,
				array(
					'default'           => '',
					'sanitize_callback' => 'absint',
					'transport'         => 'refresh',
				)
			);

			/*
			 * `WP_Customize_Cropped_Image_Control` (the same control class
			 * WordPress core uses for the site logo, see `get_custom_logo()`
			 * and `_Ufo_Custom_Logo`) forces the same 50×50 crop step here,
			 * so the footer logo is guaranteed to be cropped like the header
			 * one rather than relying on whatever size was uploaded.
			 */
			$wp_customize->add_control(
				new WP_Customize_Cropped_Image_Control(
					$wp_customize,
					self::FOOTER_LOGO_SETTING,
					array(
						'label'       => __( 'Footer logo', 'ufo-boilerplate' ),
						'section'     => 'ufo_footer_logo',
						'width'       => 50,
						'height'      => 50,
						'flex_width'  => false,
						'flex_height' => false,
					)
				)
			);
		}

		/**
		 * Returns the footer logo image markup, falling back to the site’s
		 * custom-logo image, then to the site name as plain text.
		 *
		 * The result is never itself a link — wrap it in an `<a>` (with an
		 * accessible name) at the call site.
		 *
		 * @param string $classes Classes applied to the `<img>` or fallback `<span>`.
		 * @param string $alt     Alternative text for the image (use '' when the
		 *                         wrapping link already carries an accessible name).
		 */
		public static function footer_logo_html( $classes = 'h-9', $alt = '' ) {
			$attachment_id = get_theme_mod( self::FOOTER_LOGO_SETTING );

			if ( ! $attachment_id && has_custom_logo() ) {
				$attachment_id = get_theme_mod( 'custom_logo' );
			}

			if ( $attachment_id ) {
				return wp_get_attachment_image(
					$attachment_id,
					'full',
					false,
					array(
						'class' => esc_attr( $classes ),
						'alt'   => $alt,
					)
				);
			}

			return sprintf(
				'<span class="text-base font-semibold text-foreground">%s</span>',
				esc_html( get_bloginfo( 'name' ) )
			);
		}
	}
}
