<?php
/**
 * Registers the "Front Page Hero" Customizer section
 *
 * Manages the texts, CTAs and background image used by
 * `template-parts/content/content-frontpage.php`.
 *
 * @package Ufo_Boilerplate
 */

if ( ! class_exists( '_Ufo_Front_Page_Hero' ) ) {

	/**
	 * Registers the "Front Page Hero" Customizer section.
	 */
	class _Ufo_Front_Page_Hero implements _Ufo_Service {

		const SECTION_ID         = 'ufo_front_page_hero';
		const BACKGROUND_SETTING = 'ufo_hero_background';

		/**
		 * Text/URL fields, keyed by their theme_mod id.
		 */
		protected static function fields() {
			return array(
				'ufo_hero_badge_text'         => array(
					'type'    => 'text',
					'default' => '',
					'label'   => __( 'Badge text', 'ufo-boilerplate' ),
				),
				'ufo_hero_badge_link_text'    => array(
					'type'    => 'text',
					'default' => __( 'Read more', 'ufo-boilerplate' ),
					'label'   => __( 'Badge link text', 'ufo-boilerplate' ),
				),
				'ufo_hero_badge_link_url'     => array(
					'type'    => 'url',
					'default' => '',
					'label'   => __( 'Badge link URL', 'ufo-boilerplate' ),
				),
				'ufo_hero_heading'            => array(
					'type'    => 'text',
					'default' => __( 'Data to enrich your online business', 'ufo-boilerplate' ),
					'label'   => __( 'Heading', 'ufo-boilerplate' ),
				),
				'ufo_hero_description'        => array(
					'type'    => 'textarea',
					'default' => __( 'Anim aute id magna aliqua ad ad non deserunt sunt. Qui irure qui lorem cupidatat commodo. Elit sunt amet fugiat veniam occaecat.', 'ufo-boilerplate' ),
					'label'   => __( 'Description', 'ufo-boilerplate' ),
				),
				'ufo_hero_cta_primary_text'   => array(
					'type'    => 'text',
					'default' => __( 'Get started', 'ufo-boilerplate' ),
					'label'   => __( 'Primary button text', 'ufo-boilerplate' ),
				),
				'ufo_hero_cta_primary_url'    => array(
					'type'    => 'url',
					'default' => '#',
					'label'   => __( 'Primary button URL', 'ufo-boilerplate' ),
				),
				'ufo_hero_cta_secondary_text' => array(
					'type'    => 'text',
					'default' => __( 'Learn more', 'ufo-boilerplate' ),
					'label'   => __( 'Secondary link text', 'ufo-boilerplate' ),
				),
				'ufo_hero_cta_secondary_url'  => array(
					'type'    => 'url',
					'default' => '#',
					'label'   => __( 'Secondary link URL', 'ufo-boilerplate' ),
				),
			);
		}

		/**
		 * Hooks the Customizer registration into `customize_register`.
		 */
		public static function register() {
			add_action( 'customize_register', array( __CLASS__, 'register_customizer' ) );
		}

		/**
		 * Registers the section, settings and controls for the hero.
		 *
		 * @param WP_Customize_Manager $wp_customize Customizer instance.
		 */
		public static function register_customizer( $wp_customize ) {
			$wp_customize->add_section(
				self::SECTION_ID,
				array(
					'title' => __( 'Front Page Hero', 'ufo-boilerplate' ),
					'panel' => _Ufo_Options::PANEL_ID,
				)
			);

			foreach ( self::fields() as $id => $field ) {
				$wp_customize->add_setting(
					$id,
					array(
						'default'           => $field['default'],
						'sanitize_callback' => self::sanitize_callback( $field['type'] ),
						'transport'         => 'refresh',
					)
				);

				$wp_customize->add_control(
					$id,
					array(
						'type'    => $field['type'],
						'label'   => $field['label'],
						'section' => self::SECTION_ID,
					)
				);
			}

			$wp_customize->add_setting(
				self::BACKGROUND_SETTING,
				array(
					'default'           => 0,
					'sanitize_callback' => 'absint',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Media_Control(
					$wp_customize,
					self::BACKGROUND_SETTING,
					array(
						'label'       => __( 'Background image', 'ufo-boilerplate' ),
						'description' => __( 'JPG, PNG or WebP. Leave empty to hide it.', 'ufo-boilerplate' ),
						'section'     => self::SECTION_ID,
						'mime_type'   => 'image',
					)
				)
			);
		}

		/**
		 * Maps a control type to its sanitize callback.
		 *
		 * @param string $type Control type, e.g. `text`, `url`, `textarea`.
		 */
		protected static function sanitize_callback( $type ) {
			switch ( $type ) {
				case 'url':
					return 'esc_url_raw';
				case 'textarea':
					return 'sanitize_textarea_field';
				default:
					return 'sanitize_text_field';
			}
		}

		/**
		 * Returns a text/URL field’s current value (or its default).
		 *
		 * @param string $id Theme_mod id, e.g. `ufo_hero_heading`.
		 */
		public static function get( $id ) {
			$fields  = self::fields();
			$default = isset( $fields[ $id ] ) ? $fields[ $id ]['default'] : '';

			return get_theme_mod( $id, $default );
		}

		/**
		 * Returns the hero background `<img>` markup, or an empty string if
		 * no image has been set.
		 *
		 * @param string $classes Classes applied to the `<img>`.
		 */
		public static function background_image_html( $classes = '' ) {
			$attachment_id = get_theme_mod( self::BACKGROUND_SETTING );

			if ( ! $attachment_id ) {
				return '';
			}

			// Full-bleed background covering the viewport: strong LCP candidate on
			// the front page, so it is loaded eagerly with a high fetch priority.
			return wp_get_attachment_image(
				$attachment_id,
				'1536x1536',
				false,
				array(
					'class'         => esc_attr( $classes ),
					'alt'           => '',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'sizes'         => '100vw',
				)
			);
		}
	}
}
