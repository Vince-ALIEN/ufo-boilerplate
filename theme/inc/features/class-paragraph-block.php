<?php
/**
 * Wraps paragraphs using the "Label" block style in a `<div>`
 *
 * @package Ufo_Boilerplate
 */

if ( ! class_exists( '_Ufo_Paragraph_Block' ) ) {

	/**
	 * Wraps `core/paragraph` blocks with the `text-label` style (registered
	 * in `javascript/block-editor.js`) in a `<div>`.
	 *
	 * Done at render time rather than in the block's saved markup, so
	 * existing paragraphs don't fail block validation and removing this
	 * filter leaves the content untouched.
	 */
	class _Ufo_Paragraph_Block implements _Ufo_Service {

		/**
		 * Hooks the render filter.
		 */
		public static function register() {
			add_filter( 'render_block_core/paragraph', array( __CLASS__, 'wrap_text_label' ), 10, 2 );
		}

		/**
		 * Wrap the paragraph in a div when it uses the `text-label` style.
		 *
		 * @param string $block_content Rendered block HTML.
		 * @param array  $block         Parsed block, including its attributes.
		 * @return string
		 */
		public static function wrap_text_label( $block_content, $block ) {
			$class_name = isset( $block['attrs']['className'] ) ? $block['attrs']['className'] : '';

			if ( ! preg_match( '/(^|\s)is-style-text-label(\s|$)/', $class_name ) ) {
				return $block_content;
			}

			return '<div>' . $block_content . '</div>';
		}
	}
}
