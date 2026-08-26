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

// Only load this class in admin context where Walker_Nav_Menu_Edit is available.
if ( ! class_exists( 'Walker_Nav_Menu_Edit' ) ) {
	require_once ABSPATH . 'wp-admin/includes/nav-menu.php';
}

if ( ! class_exists( '_Ufo_Walker_Nav_Menu_Edit' ) ) {
	class _Ufo_Walker_Nav_Menu_Edit extends Walker_Nav_Menu_Edit {

		/**
		 * @see Walker_Nav_Menu_Edit::start_el()
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			parent::start_el( $output, $item, $depth, $args, $id );
		}
	}
}
