<?php
/**
 * Loads every PHP file in `template-parts/custom/`, plus a fixed list of
 * feature includes that need to load in a specific order.
 *
 * Files dropped into `template-parts/custom/` (nav walkers, theme-support
 * registrations, etc.) are picked up automatically — no further wiring
 * needed. Anything that lives elsewhere in `inc/` must be added to
 * `$_ufo_class_files` below.
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

foreach ( glob( get_template_directory() . '/template-parts/custom/*.php' ) as $_ufo_custom_file ) {
	require_once $_ufo_custom_file;
}
unset( $_ufo_custom_file );

$_ufo_inc_dir = __DIR__;

$_ufo_class_files = array(
	$_ufo_inc_dir . '/class-speculative.php',
	$_ufo_inc_dir . '/navigation/class-navwalker.php',
	$_ufo_inc_dir . '/navigation/menu-custom-fields.php',
	$_ufo_inc_dir . '/navigation/walker-nav-menu-edit.php',
	$_ufo_inc_dir . '/ajax-filter/init.php',
	$_ufo_inc_dir . '/truncate/class-truncate.php',
);

foreach ( $_ufo_class_files as $_ufo_file ) {
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
unset( $_ufo_inc_dir, $_ufo_class_files, $_ufo_file );
