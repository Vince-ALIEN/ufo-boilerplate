<?php
/**
 * Disables comments, pingbacks and trackbacks site-wide
 *
 * Sites built on this theme never use comments, so the theme ships no
 * comment templates: this closes them everywhere and removes their admin
 * UI, feeds and pingback endpoints, so none can be posted or moderated
 * behind the scenes (spam included).
 *
 * @package Ufo_Boilerplate
 */

if ( ! class_exists( '_Ufo_Disable_Comments' ) ) {

	/**
	 * Disables comments, pingbacks and trackbacks site-wide.
	 */
	class _Ufo_Disable_Comments implements _Ufo_Service {

		/**
		 * Hooks everything that turns comments off.
		 */
		public static function register() {
			// Front end: closed everywhere, existing ones never shown.
			add_filter( 'comments_open', '__return_false', 20 );
			add_filter( 'pings_open', '__return_false', 20 );
			add_filter( 'comments_array', '__return_empty_array', 10 );
			add_filter( 'get_comments_number', '__return_zero' );
			add_filter( 'feed_links_show_comments_feed', '__return_false' );

			// Pingbacks: no X-Pingback header, no XML-RPC pingback methods.
			add_filter( 'wp_headers', array( __CLASS__, 'remove_pingback_header' ) );
			add_filter( 'xmlrpc_methods', array( __CLASS__, 'remove_pingback_methods' ) );

			add_action( 'init', array( __CLASS__, 'remove_post_type_support' ), 100 );

			// Admin UI.
			add_action( 'admin_menu', array( __CLASS__, 'remove_admin_menu' ) );
			add_action( 'admin_init', array( __CLASS__, 'redirect_comment_screens' ) );
			add_action( 'admin_bar_menu', array( __CLASS__, 'remove_admin_bar_node' ), 999 );
		}

		/**
		 * Removes comment and trackback support from every post type, which
		 * also hides the Discussion/Comments boxes in the editor.
		 */
		public static function remove_post_type_support() {
			foreach ( get_post_types() as $post_type ) {
				remove_post_type_support( $post_type, 'comments' );
				remove_post_type_support( $post_type, 'trackbacks' );
			}
		}

		/**
		 * Drops the `X-Pingback` HTTP header.
		 *
		 * @param array $headers HTTP headers.
		 * @return array
		 */
		public static function remove_pingback_header( $headers ) {
			unset( $headers['X-Pingback'] );
			return $headers;
		}

		/**
		 * Drops the XML-RPC pingback methods.
		 *
		 * @param array $methods XML-RPC methods.
		 * @return array
		 */
		public static function remove_pingback_methods( $methods ) {
			unset( $methods['pingback.ping'], $methods['pingback.extensions.getPingbacks'] );
			return $methods;
		}

		/**
		 * Removes the Comments and Settings > Discussion admin menus.
		 */
		public static function remove_admin_menu() {
			remove_menu_page( 'edit-comments.php' );
			remove_submenu_page( 'options-general.php', 'options-discussion.php' );
		}

		/**
		 * Sends direct visits to the comment admin screens back to the dashboard.
		 */
		public static function redirect_comment_screens() {
			global $pagenow;

			if ( in_array( $pagenow, array( 'edit-comments.php', 'comment.php', 'options-discussion.php' ), true ) ) {
				wp_safe_redirect( admin_url() );
				exit;
			}
		}

		/**
		 * Removes the comments bubble from the admin bar.
		 *
		 * @param WP_Admin_Bar $wp_admin_bar Admin bar instance.
		 */
		public static function remove_admin_bar_node( $wp_admin_bar ) {
			$wp_admin_bar->remove_node( 'comments' );
		}
	}
}
