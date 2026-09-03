<?php
/**
 * Ajax Filter initialization
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Charger la classe principale
require_once get_template_directory() . '/inc/ajax-filter/class-ajax-filter.php';

// Charger les fonctions additionnelles
require_once get_template_directory() . '/inc/ajax-filter/functions.php';

// Initialiser le filtre
if ( class_exists( '_Ufo_Ajax_Filter' ) ) {
	_Ufo_Ajax_Filter::get_instance();
}
