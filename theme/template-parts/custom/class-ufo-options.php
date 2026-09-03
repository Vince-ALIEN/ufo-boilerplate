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
	class _Ufo_Options {

		const PANEL_ID            = 'ufo_options';
		const FOOTER_LOGO_SETTING = 'ufo_footer_logo';

		/**
		 * Hooks the Customizer registration into `customize_register`.
		 */
		public static function init() {
			add_action( 'customize_register', array( __CLASS__, 'register' ) );
		}

		/**
		 * Registers the panel and the footer logo section/setting/control.
		 *
		 * @param WP_Customize_Manager $wp_customize Customizer instance.
		 */
		public static function register( $wp_customize ) {
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

			$wp_customize->add_control(
				new WP_Customize_Media_Control(
					$wp_customize,
					self::FOOTER_LOGO_SETTING,
					array(
						'label'     => __( 'Footer logo', 'ufo-boilerplate' ),
						'section'   => 'ufo_footer_logo',
						'mime_type' => 'image',
					)
				)
			);
		}

		/**
		 * Returns the footer logo markup, falling back to the site’s custom
		 * logo, then to the site name as plain text.
		 *
		 * @param string $classes Classes applied to the `<img>` or fallback `<span>`.
		 */
		public static function footer_logo_html( $classes = 'h-9' ) {
			$attachment_id = get_theme_mod( self::FOOTER_LOGO_SETTING );

			if ( $attachment_id ) {
				return wp_get_attachment_image(
					$attachment_id,
					'full',
					false,
					array( 'class' => esc_attr( $classes ) )
				);
			}

			if ( has_custom_logo() ) {
				return get_custom_logo();
			}

			return sprintf(
				'<span class="text-base font-semibold text-foreground">%s</span>',
				esc_html( get_bloginfo( 'name' ) )
			);
		}
	}

	_Ufo_Options::init();
}
