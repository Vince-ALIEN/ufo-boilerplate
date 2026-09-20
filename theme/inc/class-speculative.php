<?php
/**
 * Speculative loading configuration.
 *
 * Since WordPress 6.8 the Speculation Rules API is wired into core and
 * enabled by default in its most conservative form (`prefetch` /
 * `conservative`). This class only tweaks that default — it does not add a
 * second implementation — bumping the eagerness so internal navigation feels
 * instant, while keeping the safer `prefetch` mode (no target-page JS runs
 * ahead of the click, unlike `prerender`).
 *
 * On WordPress < 6.8 the filters below simply never fire, so this file is a
 * no-op there.
 *
 * Override points:
 *   - Constants `_UFO_SPECULATION_MODE` / `_UFO_SPECULATION_EAGERNESS`
 *     (define before `after_setup_theme`).
 *   - Filters `_ufo_speculation_mode`, `_ufo_speculation_eagerness`,
 *     `_ufo_speculation_exclude_paths`.
 *
 * @link https://developer.wordpress.org/reference/hooks/wp_speculation_rules_configuration/
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '_Ufo_Speculative' ) ) {

	/**
	 * Adjusts core's Speculation Rules configuration for this theme.
	 */
	class _Ufo_Speculative implements _Ufo_Service {

		/**
		 * Singleton instance.
		 *
		 * @var self|null
		 */
		private static $instance = null;

		/**
		 * Allowed speculation modes.
		 *
		 * @var string[]
		 */
		private $allowed_modes = array( 'prefetch', 'prerender' );

		/**
		 * Allowed eagerness values.
		 *
		 * @var string[]
		 */
		private $allowed_eagerness = array( 'conservative', 'moderate', 'eager' );

		/**
		 * Path fragments that must never be speculatively loaded, on top of the
		 * exclusions core already applies (wp-admin, query-string URLs,
		 * `nofollow` links, logged-in-only routes, WooCommerce cart/checkout…).
		 *
		 * Values are matched as URL Pattern paths, so a trailing `/*` covers a
		 * whole section. Adjust the French WooCommerce slugs to your install.
		 *
		 * @var string[]
		 */
		private $default_exclude_paths = array(
			'/panier/*',
			'/commande/*',
			'/mon-compte/*',
			'/checkout/*',
			'/cart/*',
			'/my-account/*',
		);

		/**
		 * Get the singleton instance.
		 *
		 * @return self
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Instantiates the singleton, which hooks into core from its constructor.
		 */
		public static function register() {
			self::get_instance();
		}

		/**
		 * Hook into core.
		 */
		private function __construct() {
			add_filter( 'wp_speculation_rules_configuration', array( $this, 'filter_configuration' ) );
			add_filter( 'wp_speculation_rules_href_exclude_paths', array( $this, 'filter_exclude_paths' ), 10, 2 );
		}

		/**
		 * Set the speculation mode and eagerness.
		 *
		 * @param array|null $config Core configuration array, or null to disable.
		 * @return array|null
		 */
		public function filter_configuration( $config ) {
			// Respect an explicit opt-out from core or another plugin.
			if ( null === $config ) {
				return $config;
			}

			$mode = defined( '_UFO_SPECULATION_MODE' ) ? _UFO_SPECULATION_MODE : 'prefetch';
			/**
			 * Filters the speculative loading mode.
			 *
			 * @param string $mode One of `prefetch`, `prerender`.
			 */
			$mode = apply_filters( '_ufo_speculation_mode', $mode );

			$eagerness = defined( '_UFO_SPECULATION_EAGERNESS' ) ? _UFO_SPECULATION_EAGERNESS : 'moderate';
			/**
			 * Filters the speculative loading eagerness.
			 *
			 * @param string $eagerness One of `conservative`, `moderate`, `eager`.
			 */
			$eagerness = apply_filters( '_ufo_speculation_eagerness', $eagerness );

			if ( in_array( $mode, $this->allowed_modes, true ) ) {
				$config['mode'] = $mode;
			}

			if ( in_array( $eagerness, $this->allowed_eagerness, true ) ) {
				$config['eagerness'] = $eagerness;
			}

			return $config;
		}

		/**
		 * Add the theme's path exclusions.
		 *
		 * @param string[] $paths Paths already excluded.
		 * @param string   $mode  Current speculation mode (`prefetch`/`prerender`).
		 * @return string[]
		 */
		public function filter_exclude_paths( $paths, $mode ) {
			/**
			 * Filters the list of path patterns excluded from speculative loading.
			 *
			 * @param string[] $exclude Default theme exclusions.
			 * @param string   $mode    Current speculation mode.
			 */
			$exclude = apply_filters( '_ufo_speculation_exclude_paths', $this->default_exclude_paths, $mode );

			return array_values( array_unique( array_merge( (array) $paths, (array) $exclude ) ) );
		}
	}
}
