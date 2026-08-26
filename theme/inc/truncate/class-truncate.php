<?php
/**
 * Helper function pour tronquer le texte par caractères
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( '_ufo_truncate_text' ) ) :
	/**
	 * Tronque un texte à une longueur donnée, sans couper de mot en plein milieu.
	 *
	 * @param string $text   Le texte à tronquer.
	 * @param int    $length Longueur maximale en caractères.
	 * @param string $ending Caractères à ajouter à la fin.
	 * @return string
	 */
	function _ufo_truncate_text( $text, $length = 100, $ending = '...' ) {
		// Supprimer les tags HTML.
		$text = wp_strip_all_tags( $text );

		// Si le texte est plus court que la limite, le retourner tel quel.
		if ( mb_strlen( $text ) <= $length ) {
			return $text;
		}

		// Couper à la longueur maximale.
		$truncated = mb_substr( $text, 0, $length );

		// S'assurer qu'on ne coupe pas un mot en plein milieu.
		$last_space = mb_strrpos( $truncated, ' ' );

		if ( false !== $last_space ) {
			$truncated = mb_substr( $truncated, 0, $last_space );
		}

		return $truncated . $ending;
	}
endif;
