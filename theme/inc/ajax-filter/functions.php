<?php
/**
 * Ajax Filter additional functions
 *
 * @package Ufo_Boilerplate
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Configuration du filtre
 */
add_filter('ajax_filter_config', function($config) {
    return array_merge($config, [
        'templates_path' => 'template-parts/ajax-filter',
        'assets_path' => '/inc/ajax-filter/assets',
        'text_domain' => 'ufo-boilerplate'
    ]);
});

/**
 * Modification des requêtes de filtrage si nécessaire
 */
add_filter('ajax_filter_query_args', function($args, $attributes) {
    return $args;
}, 10, 2);

/**
 * Scripts et styles spécifiques
 */
add_action('wp_enqueue_scripts', function() {
    // Uniquement la localisation des textes
    wp_localize_script('ajax-filter', 'ajaxFilterTheme', [
        'noResults' => __('Aucun résultat trouvé', 'ufo-boilerplate'),
        'loading' => __('Chargement...', 'ufo-boilerplate'),
    ]);
}, 20);

if (!function_exists('_ufo_display_ajax_filter')) :
    /**
     * Fonction d'aide pour afficher le filtre
     */
    function _ufo_display_ajax_filter($args = []) {
        if (!class_exists('_Ufo_Ajax_Filter')) {
            return '';
        }

        $default_args = [
            'post_type' => 'post',
            'posts_per_page' => get_option('posts_per_page'),
            'tax' => 'category',
            'order' => 'DESC',
            'orderby' => 'date',
            'multiselect' => 'false'
        ];

        $filter_args = wp_parse_args($args, $default_args);
        return _Ufo_Ajax_Filter::get_instance()->init_filter($filter_args);
    }
endif;
