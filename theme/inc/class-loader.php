<?php
/**
 * Boots the theme's custom features.
 *
 * No autoloader: every file below is `require`d directly. Files dropped
 * into `inc/features/`, `inc/navigation/`, `inc/ajax-filter/` or
 * `inc/truncate/` are picked up automatically by the glob loop below,
 * whether they declare a class or are plain functions/hook registrations
 * — no wiring needed either way. Anything living elsewhere in `inc/`
 * (currently just `class-speculative.php`) needs a line of its own in
 * `$_ufo_top_level_files`.
 *
 * `interface-service.php` loads first, on its own: PHP resolves a class's
 * `implements` clause when that class is declared, so `_Ufo_Service` has
 * to exist before any file declaring a class that implements it runs.
 *
 * Loading a file only wires up whatever it does unconditionally (hooks,
 * function definitions). A class that implements `_Ufo_Service` still
 * needs to be added to `$_ufo_services` below to actually boot — that's
 * the one piece of wiring a new feature can't avoid.
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_ufo_inc_dir = __DIR__;

require_once $_ufo_inc_dir . '/interface-service.php';

$_ufo_top_level_files = array(
	$_ufo_inc_dir . '/class-speculative.php',
);

foreach ( $_ufo_top_level_files as $_ufo_file ) {
	require_once $_ufo_file;
}
unset( $_ufo_top_level_files, $_ufo_file );

$_ufo_auto_loaded_files = array_merge(
	glob( $_ufo_inc_dir . '/features/*.php' ),
	glob( $_ufo_inc_dir . '/navigation/*.php' ),
	glob( $_ufo_inc_dir . '/ajax-filter/*.php' ),
	glob( $_ufo_inc_dir . '/truncate/*.php' )
);

foreach ( $_ufo_auto_loaded_files as $_ufo_file ) {
	require_once $_ufo_file;
}
unset( $_ufo_inc_dir, $_ufo_auto_loaded_files, $_ufo_file );

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
	}
}
unset( $_ufo_services, $_ufo_service );
