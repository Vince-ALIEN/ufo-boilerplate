<?php
/**
 * Boots the theme's custom features.
 *
 * Composer's classmap autoloader (declared in `composer.json`, covering all
 * of `inc/`, including `inc/features/`) resolves any class referenced below
 * to its file — dropping a new passive class there needs no wiring here.
 *
 * Two categories of file still need an explicit line in this file, because
 * nothing in the theme ever *references* them by class/function name, so
 * autoloading alone would never trigger them to load:
 *
 *   - Services: implement `_Ufo_Service` and are booted via `register()`
 *     in the loop below. Add a new one to `$_ufo_services` and it's active
 *     — no further wiring needed as long as its file lives under `inc/`.
 *   - Procedural files: plain hook registrations or helper functions with
 *     no class behind them at all, so Composer's classmap can't index them.
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_ufo_autoloader = dirname( __DIR__ ) . '/vendor/autoload.php';

if ( file_exists( $_ufo_autoloader ) ) {
	require_once $_ufo_autoloader;
} else {
	add_action(
		'admin_notices',
		function () {
			printf(
				'<div class="notice notice-error"><p>%s</p></div>',
				esc_html__( 'Ufo Boilerplate: run `composer install` in the theme directory to enable its custom services (breadcrumbs, header CTA, ajax filter, speculative loading…).', 'ufo-boilerplate' )
			);
		}
	);
}
unset( $_ufo_autoloader );

/**
 * Services implementing `_Ufo_Service::register()`.
 */
$_ufo_services = array(
	'_Ufo_Speculative',
	'_Ufo_Contact_Form7',
	'_Ufo_Custom_Logo',
	'_Ufo_Front_Page_Hero',
	'_Ufo_Header_Cta',
	'_Ufo_Image_Block',
	'_Ufo_Social_Media',
	'_Ufo_Options',
);

foreach ( $_ufo_services as $_ufo_service ) {
	if ( class_exists( $_ufo_service ) ) {
		$_ufo_service::register();
	}
}
unset( $_ufo_services, $_ufo_service );

/**
 * Procedural files: no class to autoload against, so require directly.
 */
$_ufo_inc_dir = __DIR__;

$_ufo_procedural_files = array(
	$_ufo_inc_dir . '/features/class-breadcrumb.php', // Plain functions, not a class, despite the file name.
	$_ufo_inc_dir . '/navigation/menu-custom-fields.php',
	$_ufo_inc_dir . '/ajax-filter/init.php',
	$_ufo_inc_dir . '/truncate/class-truncate.php', // Plain function, not a class, despite the file name.
);

foreach ( $_ufo_procedural_files as $_ufo_file ) {
	if ( file_exists( $_ufo_file ) ) {
		require_once $_ufo_file;
	} else {
		add_action(
			'admin_notices',
			function () use ( $_ufo_file ) {
				printf(
					'<div class="notice notice-error"><p>%s</p></div>',
					esc_html(
						sprintf(
							/* translators: 1: file name, 2: directory path. */
							__( 'Erreur : le fichier %1$s est manquant dans %2$s', 'ufo-boilerplate' ),
							basename( $_ufo_file ),
							dirname( $_ufo_file )
						)
					)
				);
			}
		);
	}
}
unset( $_ufo_inc_dir, $_ufo_procedural_files, $_ufo_file );
