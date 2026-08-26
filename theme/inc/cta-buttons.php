<?php
/**
 * Header CTA Buttons
 *
 * Registers post meta for the two call-to-action buttons shown in the page
 * header (see `template-parts/content/content-page.php`), and exposes them
 * to the block editor's Document sidebar via `javascript/block-editor.js`.
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( '_ufo_cta_fields_list' ) ) :
	/**
	 * Define the header CTA meta fields, keyed by meta key.
	 *
	 * `default` is also used by `_ufo_get_cta_field()` as the front-end
	 * fallback, independent of whether `register_meta()`'s own default
	 * resolution applies.
	 *
	 * @return array List of CTA fields, keyed by meta key.
	 */
	function _ufo_cta_fields_list() {
		return array(
			'_ufo_cta_primary_label'   => array(
				'label'   => __( 'Bouton principal — texte', 'ufo-boilerplate' ),
				'default' => __( 'Get started', 'ufo-boilerplate' ),
				'type'    => 'text',
			),
			'_ufo_cta_primary_url'     => array(
				'label'   => __( 'Bouton principal — URL', 'ufo-boilerplate' ),
				'default' => '',
				'type'    => 'url',
			),
			'_ufo_cta_secondary_label' => array(
				'label'   => __( 'Bouton secondaire — texte', 'ufo-boilerplate' ),
				'default' => __( 'Learn more', 'ufo-boilerplate' ),
				'type'    => 'text',
			),
			'_ufo_cta_secondary_url'   => array(
				'label'   => __( 'Bouton secondaire — URL', 'ufo-boilerplate' ),
				'default' => '',
				'type'    => 'url',
			),
		);
	}
endif;

if ( ! function_exists( '_ufo_register_cta_meta' ) ) :
	/**
	 * Register the header CTA meta fields for the `page` post type, exposed
	 * to the block editor's REST-backed Document sidebar panel.
	 */
	function _ufo_register_cta_meta() {
		foreach ( _ufo_cta_fields_list() as $meta_key => $field ) {
			register_meta(
				'post',
				$meta_key,
				array(
					'object_subtype'    => 'page',
					'show_in_rest'      => true,
					'single'            => true,
					'type'              => 'string',
					'default'           => $field['default'],
					'sanitize_callback' => 'url' === $field['type'] ? 'esc_url_raw' : 'sanitize_text_field',
					'auth_callback'     => function () {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}
endif;
add_action( 'init', '_ufo_register_cta_meta' );

if ( ! function_exists( '_ufo_get_cta_field' ) ) :
	/**
	 * Get a header CTA field's value for a page, falling back to its default
	 * when unset.
	 *
	 * @param string   $meta_key Meta key, see `_ufo_cta_fields_list()`.
	 * @param int|null $post_id  Post ID. Defaults to the current post.
	 * @return string
	 */
	function _ufo_get_cta_field( $meta_key, $post_id = null ) {
		$fields = _ufo_cta_fields_list();

		if ( ! isset( $fields[ $meta_key ] ) ) {
			return '';
		}

		$value = get_post_meta( $post_id ? $post_id : get_the_ID(), $meta_key, true );

		return '' !== $value ? $value : $fields[ $meta_key ]['default'];
	}
endif;
