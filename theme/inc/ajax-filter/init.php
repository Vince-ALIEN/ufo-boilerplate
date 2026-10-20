<?php
/**
 * Ajax Filter initialization
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// La classe principale (_Ufo_Ajax_Filter) est chargée par inc/autoload.php.

// Charger les fonctions additionnelles.
require_once get_template_directory() . '/inc/ajax-filter/functions.php';

// Initialiser le filtre.
if ( class_exists( '_Ufo_Ajax_Filter' ) ) {
	_Ufo_Ajax_Filter::get_instance();
}
