<?php
/**
 * Custom Walker for the Nav Menu Editor
 *
 * Extends the default WordPress menu editor walker so the custom fields
 * added via `wp_nav_menu_item_custom_fields` (see menu-custom-fields.php)
 * are rendered on every menu item's edit box.
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Autoloaded only when core asks for this walker (menu screens); load the
// parent class first in case core has not loaded it yet.
if ( ! class_exists( 'Walker_Nav_Menu_Edit' ) ) {
	require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
}

if ( ! class_exists( '_Ufo_Walker_Nav_Menu_Edit' ) ) {

	/**
	 * Menu-editor walker kept as an explicit extension point.
	 *
	 * Core's Walker_Nav_Menu_Edit already renders the fields registered via
	 * `wp_nav_menu_item_custom_fields`, so `start_el()` only forwards to the
	 * parent for now; override it here if the edit box needs bespoke markup.
	 */
	class _Ufo_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {

		/**
		 * Start the element output for one menu item's edit box.
		 *
		 * @param string   $output Passed by reference. Used to append additional content.
		 * @param WP_Post  $item   Menu item data object.
		 * @param int      $depth  Depth of menu item. Used for padding.
		 * @param stdClass $args   Not used.
		 * @param int      $id     Not used.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found -- kept as a documented seam.
			parent::start_el( $output, $item, $depth, $args, $id );
		}
	}
}
