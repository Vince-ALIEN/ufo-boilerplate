<?php
/**
 * Loads every PHP file in `template-parts/custom/`, plus a fixed list of
 * feature includes that need to load in a specific order.
 *
 * Files dropped into `template-parts/custom/` (nav walkers, theme-support
 * registrations, etc.) are picked up automatically — no further wiring
 * needed. Anything that lives elsewhere in `inc/` must be added to
 * `$class_files` below.
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

$inc_dir = __DIR__;

$class_files = array(
	$inc_dir . '/navigation/class-navwalker.php',
	$inc_dir . '/navigation/menu-custom-fields.php',
	$inc_dir . '/navigation/walker-nav-menu-edit.php',
	$inc_dir . '/ajax-filter/init.php',
	$inc_dir . '/truncate/class-truncate.php',
);

foreach ( $class_files as $file ) {
	if ( file_exists( $file ) ) {
		require_once $file;
	} else {
		add_action(
			'admin_notices',
			function () use ( $file ) {
				printf(
					'<div class="notice notice-error"><p>%s</p></div>',
					esc_html(
						sprintf(
							/* translators: 1: file name, 2: directory path. */
							__( 'Erreur : le fichier %1$s est manquant dans %2$s', 'ufo-boilerplate' ),
							basename( $file ),
							dirname( $file )
						)
					)
				);
			}
		);
	}
}
unset( $inc_dir, $class_files, $file );
