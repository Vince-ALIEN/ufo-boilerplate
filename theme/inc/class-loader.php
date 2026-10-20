<?php
/**
 * Boots the theme's custom features.
 *
 * Classes and interfaces are never `require`d by hand: `autoload.php` loads
 * a `_Ufo_*` class from its `class-*.php` file the first time it is used
 * (see that file for the naming rule and the folders it searches). PHP
 * also autoloads `_Ufo_Service` when a class implementing it is declared,
 * so load order no longer matters.
 *
 * Files of plain functions or hook registrations cannot be autoloaded:
 * they are listed in `$_ufo_function_files` and loaded on every request.
 *
 * A class implementing `_Ufo_Service` still has to be added to
 * `$_ufo_services` to boot: that list is the theme's feature switchboard.
 * A name in it that matches no file triggers a `_doing_it_wrong()` notice
 * (shown when `WP_DEBUG` is on) instead of being skipped silently.
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/autoload.php';

/**
 * Files of functions and hook registrations, relative to `inc/`.
 */
$_ufo_function_files = array(
	'features/breadcrumb.php',
	'navigation/menu-custom-fields.php',
	'ajax-filter/init.php',
	'truncate/truncate.php',
);

foreach ( $_ufo_function_files as $_ufo_file ) {
	require_once __DIR__ . '/' . $_ufo_file;
}
unset( $_ufo_function_files, $_ufo_file );

/**
 * Services implementing `_Ufo_Service::register()`.
 */
$_ufo_services = array(
	'_Ufo_Speculative',
	'_Ufo_Contact_Form7',
	'_Ufo_Custom_Logo',
	'_Ufo_Disable_Comments',
	'_Ufo_Font_Preload',
	'_Ufo_Front_Page_Hero',
	'_Ufo_Header_Cta',
	'_Ufo_Image_Block',
	'_Ufo_Paragraph_Block',
	'_Ufo_Social_Media',
	'_Ufo_Options',
);

foreach ( $_ufo_services as $_ufo_service ) {
	if ( class_exists( $_ufo_service ) ) {
		$_ufo_service::register();
	} else {
		_doing_it_wrong(
			'class-loader.php',
			esc_html( sprintf( 'Service %s not found: check that its file follows the naming rule in inc/autoload.php.', $_ufo_service ) ),
			'0.1.0'
		);
	}
}
unset( $_ufo_services, $_ufo_service );
