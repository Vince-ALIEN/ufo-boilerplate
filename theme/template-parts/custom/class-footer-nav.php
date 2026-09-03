<?php
/**
 * Applies Tailwind classes to the footer nav menu links
 *
 * The footer’s columns use plain `wp_nav_menu()` calls (default `<ul>`/`<li>`
 * markup already matches the footer design), so a link class only needs to
 * be merged onto the `<a>` tag for the theme locations used in the footer.
 *
 * @package Ufo_Boilerplate
 */

if ( ! class_exists( '_Ufo_Footer_Nav' ) ) {

	/**
	 * Merges a Tailwind link class onto the footer nav menus.
	 */
	class _Ufo_Footer_Nav {

		/**
		 * Hooks the link class filter.
		 */
		public static function init() {
			add_filter( 'nav_menu_link_attributes', array( __CLASS__, 'add_link_class' ), 10, 3 );
		}

		/**
		 * Tailwind classes to apply to each footer nav link, keyed by theme_location.
		 */
		protected static function link_classes() {
			$class = 'text-sm/6 text-foreground/70 hover:text-foreground';

			return array(
				'menu-2'         => $class,
				'menu-3'         => $class,
				'menu-solutions' => $class,
				'menu-support'   => $class,
			);
		}

		/**
		 * Append the footer link class for the footer theme locations.
		 *
		 * @param array    $atts Link attributes.
		 * @param WP_Post  $item Menu item.
		 * @param stdClass $args wp_nav_menu() args, including `theme_location`.
		 * @return array
		 */
		public static function add_link_class( $atts, $item, $args ) {
			$classes = self::link_classes();

			if ( empty( $args->theme_location ) || empty( $classes[ $args->theme_location ] ) ) {
				return $atts;
			}

			$atts['class'] = isset( $atts['class'] )
				? $atts['class'] . ' ' . $classes[ $args->theme_location ]
				: $classes[ $args->theme_location ];

			return $atts;
		}
	}

	_Ufo_Footer_Nav::init();
}
