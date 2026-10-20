<?php
/**
 * Autoloader for the theme's `_Ufo_*` classes and interfaces.
 *
 * A class is loaded from its own file the first time it is used, so adding
 * one only takes a file named after it. The file name follows the WordPress
 * convention: drop the `_Ufo_` prefix, lowercase, turn underscores into
 * hyphens, then prefix with `class-` (or `interface-`):
 *
 * - `_Ufo_Header_Cta`   → `class-header-cta.php`
 * - `_Ufo_Contact_Form7` → `class-contact-form7.php`
 * - `_Ufo_Service`      → `interface-service.php`
 *
 * The file is looked up in `inc/`, then in each folder of the list below.
 * Only classes and interfaces can be autoloaded: a file of plain functions
 * or hook registrations is listed in `class-loader.php` instead.
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

spl_autoload_register(
	static function ( $class_name ) {
		if ( 0 !== strpos( $class_name, '_Ufo_' ) ) {
			return;
		}

		$slug = str_replace( '_', '-', strtolower( substr( $class_name, strlen( '_Ufo_' ) ) ) );

		// Folders searched, in order. Add a folder here when it holds classes.
		$dirs = array( '', '/features', '/navigation', '/ajax-filter' );

		foreach ( $dirs as $dir ) {
			foreach ( array( 'class-', 'interface-' ) as $kind ) {
				$file = __DIR__ . $dir . '/' . $kind . $slug . '.php';

				if ( is_readable( $file ) ) {
					require_once $file;
					return;
				}
			}
		}
	}
);
