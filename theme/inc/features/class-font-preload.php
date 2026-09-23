<?php
/**
 * Preloads the theme's web fonts
 *
 * WordPress prints the `@font-face` rules declared in theme.json, but the
 * browser only requests a font once the stylesheet is applied and an
 * element actually uses it (HTML → CSS → font). A `<link rel="preload">`
 * in the `<head>` starts the download while the HTML is parsed instead,
 * so the font swaps in sooner (it's the heading font, often the LCP).
 *
 * The list comes straight from `settings.typography.fontFamilies` in
 * theme.json: a font face added there is preloaded with no extra wiring.
 *
 * @package Ufo_Boilerplate
 */

if ( ! class_exists( '_Ufo_Font_Preload' ) ) {

	/**
	 * Preloads the theme's web fonts.
	 */
	class _Ufo_Font_Preload implements _Ufo_Service {

		/**
		 * Hooks the preload links early in `wp_head`, before the stylesheets.
		 */
		public static function register() {
			add_action( 'wp_head', array( __CLASS__, 'print_preload_links' ), 1 );
		}

		/**
		 * Prints one `<link rel="preload">` per woff2 font face.
		 */
		public static function print_preload_links() {
			foreach ( self::get_font_urls() as $url ) {
				printf(
					'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
					esc_url( $url )
				);
			}
		}

		/**
		 * Collects the woff2 URLs of the font faces declared in theme.json.
		 *
		 * Only the first woff2 source of each face is kept (that's the one
		 * the browser picks). Filterable through `_ufo_preload_font_urls`,
		 * e.g. to drop a face that's only used below the fold.
		 *
		 * @return string[]
		 */
		public static function get_font_urls() {
			$families = wp_get_global_settings( array( 'typography', 'fontFamilies', 'theme' ) );
			$urls     = array();

			foreach ( (array) $families as $family ) {
				foreach ( $family['fontFace'] ?? array() as $face ) {
					foreach ( (array) ( $face['src'] ?? array() ) as $src ) {
						if ( ! str_ends_with( strtolower( $src ), '.woff2' ) ) {
							continue;
						}

						// theme.json paths are `file:./…`, relative to the theme root.
						$urls[] = str_starts_with( $src, 'file:./' )
							? get_theme_file_uri( substr( $src, strlen( 'file:./' ) ) )
							: $src;
						break;
					}
				}
			}

			return array_unique( apply_filters( '_ufo_preload_font_urls', $urls ) );
		}
	}
}
