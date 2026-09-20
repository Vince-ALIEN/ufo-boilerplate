<?php
/**
 * Ajax Filter additional functions
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Point d'accroche pour modifier les arguments WP_Query du filtre.
 *
 * Enregistré sans effet par défaut ; à surcharger dans un thème enfant.
 *
 * @param array $args       Arguments WP_Query.
 * @param array $attributes Attributs de filtrage validés (non utilisés ici).
 */
add_filter(
	'_ufo_ajax_filter_query_args',
	function ( $args, $attributes ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter -- signature du filtre conservée.
		return $args;
	},
	10,
	2
);

/**
 * Scripts et styles spécifiques
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		// Uniquement la localisation des textes.
		wp_localize_script(
			'ajax-filter',
			'ajaxFilterTheme',
			array(
				'noResults' => __( 'Aucun résultat trouvé', 'ufo-boilerplate' ),
				'loading'   => __( 'Chargement...', 'ufo-boilerplate' ),
			)
		);
	},
	20
);

if ( ! function_exists( '_ufo_display_ajax_filter' ) ) :
	/**
	 * Fonction d'aide pour afficher le filtre.
	 *
	 * @param array $args Arguments passés à _Ufo_Ajax_Filter (fusionnés avec les valeurs par défaut).
	 * @return string
	 */
	function _ufo_display_ajax_filter( $args = array() ) {
		if ( ! class_exists( '_Ufo_Ajax_Filter' ) ) {
			return '';
		}

		$default_args = array(
			'post_type'      => 'post',
			'posts_per_page' => get_option( 'posts_per_page' ),
			'tax'            => 'category',
			'order'          => 'DESC',
			'orderby'        => 'date',
			'multiselect'    => 'false',
		);

		$filter_args = wp_parse_args( $args, $default_args );
		return _Ufo_Ajax_Filter::get_instance()->init_filter( $filter_args );
	}
endif;
