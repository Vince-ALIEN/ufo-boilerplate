<?php
/**
 * Breadcrumb template modernisé pour WordPress avec support du Category Base
 * Utilise Tailwind CSS et schema.org pour le SEO
 * Optimisé pour l'accessibilité et les lecteurs d'écran
 *
 * @package Ufo_Boilerplate
 */

if ( ! function_exists( '_ufo_get_breadcrumb' ) ) :
	function _ufo_get_breadcrumb() {
		// Configuration de base
		$config = array(
			'home_link'     => home_url( '/' ),
			'home_text'     => __( 'Accueil', 'ufo-boilerplate' ),
			'wrapper_class' => 'text-sm',
			'current_class' => 'font-semibold',
			'link_class'    => 'hover:text-primary transition-colors duration-200',
			'list_class'    => 'py-2 whitespace-nowrap flex flex-wrap', // Suppression de space-x-2 pour éviter les problèmes avec les lecteurs d'écran
			'item_class'    => 'flex items-center',
		);

		// Récupération du category base depuis les options
		$category_base = get_option( 'category_base' );

		// Initialisation du breadcrumb avec les microdata schema.org
		$breadcrumb  = '<nav aria-label="Breadcrumb">';
		$breadcrumb .= '<ol class="' . $config['list_class'] . '" itemscope itemtype="https://schema.org/BreadcrumbList">';

		// Fonction pour créer un élément du breadcrumb
		$create_item = function ( $position, $url, $text, $is_current = false, $is_last = false ) use ( $config ) {
			$item_class = $config['item_class'];
			$text       = esc_html( $text );

			// Ajouter aria-current pour l'élément actuel
			$aria_current = $is_current ? ' aria-current="page"' : '';

			$item = '<li class="' . $item_class . '"' . $aria_current . ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';

			if ( $is_current ) {
				$item .= sprintf( '<span class="%s" itemprop="name">%s</span>', $config['current_class'], $text );
			} else {
				$item .= sprintf(
					'<a href="%s" class="%s" itemprop="item"><span itemprop="name">%s</span></a>',
					esc_url( $url ),
					$config['link_class'],
					$text
				);
			}

			$item .= sprintf( '<meta itemprop="position" content="%d">', $position );

			// Ajouter le séparateur après chaque élément sauf le dernier
			if ( ! $is_last ) {
				// Utilisation d'un pseudo-élément CSS pour le séparateur visuel avec aria-hidden pour les lecteurs d'écran
				$item .= '<i class="size-4 mx-2" data-lucide="chevron-right"></i>';
			}

			$item .= '</li>';

			return $item;
		};

		// Ajout de l'accueil
		$items = array();
		if ( ! is_front_page() ) {
			$items[] = $create_item( 1, $config['home_link'], $config['home_text'], false, false );
		}

		// Gestion des différents types de pages
		$position = count( $items ) + 1;

		// Gestion spéciale pour index.php
		if ( is_home() && ! is_front_page() ) {
			$page_for_posts = get_option( 'page_for_posts' );
			if ( $page_for_posts ) {
				$items[] = $create_item( $position, get_permalink( $page_for_posts ), get_the_title( $page_for_posts ), true, true );
			} else {
				$items[] = $create_item( $position, '', __( 'Blog', 'ufo-boilerplate' ), true, true );
			}
		}
		// Posts et CPT
		elseif ( is_singular() ) {
			$post = get_queried_object();

			// Gestion commune pour les posts et CPT
			if ( 'post' === $post->post_type || ! in_array( $post->post_type, array( 'page', 'attachment' ) ) ) {
				// Récupération des catégories (pour posts et CPT)
				$categories = 'post' === $post->post_type ?
				get_the_category() :
				get_the_terms( $post->ID, 'category' );

				if ( $categories && ! is_wp_error( $categories ) ) {
					$category = $categories[0];

					// Ajout du category base si défini
					if ( $category_base ) {
						$items[] = $create_item(
							$position++,
							home_url( '/' . $category_base . '/' ),
							ucfirst( $category_base ),
							false,
							false
						);
					}

					// Ajout des catégories ancêtres
					$cat_ancestors = array_reverse( get_ancestors( $category->term_id, 'category' ) );
					foreach ( $cat_ancestors as $cat_id ) {
						$category_ancestor = get_term( $cat_id, 'category' );
						$items[]           = $create_item(
							$position++,
							get_term_link( $category_ancestor ),
							$category_ancestor->name,
							false,
							false
						);
					}

					// Ajout de la catégorie courante
					$items[] = $create_item(
						$position++,
						get_term_link( $category ),
						$category->name,
						false,
						false
					);
				}

				// Pour les CPT, ajout de l'archive du type de contenu si elle existe
				if ( $post->post_type !== 'post' ) {
					$post_type_obj = get_post_type_object( $post->post_type );
					if ( $post_type_obj && $post_type_obj->has_archive ) {
						$items[] = $create_item(
							$position++,
							get_post_type_archive_link( $post->post_type ),
							$post_type_obj->labels->name,
							false,
							false
						);
					}
				}
			}

			// Gestion des parents pour les pages et CPT
			if ( $post->post_parent ) {
				$ancestors = array_reverse( get_post_ancestors( $post ) );
				foreach ( $ancestors as $ancestor ) {
					$items[] = $create_item(
						$position++,
						get_permalink( $ancestor ),
						get_the_title( $ancestor ),
						false,
						false
					);
				}
			}

			// Ajout de la page/post/CPT courant
			$items[] = $create_item( $position, get_permalink(), get_the_title(), true, true );
		}

		// Archives
		elseif ( is_archive() ) {
			if ( is_category() ) {
				$term = get_queried_object();

				// Ajout du category base si défini
				if ( $category_base ) {
					$items[] = $create_item(
						$position++,
						home_url( '/' . $category_base . '/' ),
						ucfirst( $category_base ),
						false,
						false
					);
				}

				// Ajout des catégories ancêtres
				if ( $term->parent ) {
					$ancestors = array_reverse( get_ancestors( $term->term_id, 'category' ) );
					foreach ( $ancestors as $ancestor_id ) {
						$ancestor = get_term( $ancestor_id, 'category' );
						$items[]  = $create_item(
							$position++,
							get_term_link( $ancestor ),
							$ancestor->name,
							false,
							false
						);
					}
				}

				// Ajout de la catégorie courante
				$items[] = $create_item(
					$position,
					get_term_link( $term ),
					$term->name,
					true,
					true
				);
			} elseif ( is_tag() || is_tax() ) {
				$term     = get_queried_object();
				$taxonomy = get_taxonomy( $term->taxonomy );

				if ( $term->parent ) {
					$ancestors = array_reverse( get_ancestors( $term->term_id, $term->taxonomy ) );
					foreach ( $ancestors as $ancestor_id ) {
						$ancestor = get_term( $ancestor_id, $term->taxonomy );
						$items[]  = $create_item( $position++, get_term_link( $ancestor ), $ancestor->name, false, false );
					}
				}

				$items[] = $create_item( $position, get_term_link( $term ), $term->name, true, true );
			} elseif ( is_author() ) {
				$items[] = $create_item( $position, get_author_posts_url( get_queried_object_id() ), get_the_author(), true, true );
			} elseif ( is_post_type_archive() ) {
				$post_type = get_post_type_object( get_post_type() );

				// Ajout du category base pour les archives de CPT si défini
				if ( $category_base ) {
					$items[] = $create_item(
						$position++,
						home_url( '/' . $category_base . '/' ),
						ucfirst( $category_base ),
						false,
						false
					);
				}

				if ( $post_type ) {
					$items[] = $create_item( $position, get_post_type_archive_link( get_post_type() ), $post_type->labels->name, true, true );
				}
			}
		}

		// Recherche
		elseif ( is_search() ) {
			$items[] = $create_item( $position, '', sprintf( __( 'Résultats pour : %s', 'ufo-boilerplate' ), get_search_query() ), true, true );
		}

		// 404
		elseif ( is_404() ) {
			$items[] = $create_item( $position, '', __( 'Page non trouvée', 'ufo-boilerplate' ), true, true );
		}

		// Construction du HTML final
		$breadcrumb .= implode( '', $items );
		$breadcrumb .= '</ol></nav>';

		return $breadcrumb;
	}
endif;

if ( ! function_exists( '_ufo_the_breadcrumb' ) ) :
	/**
	 * Echo the breadcrumb trail.
	 *
	 * The markup is already escaped inside _ufo_get_breadcrumb() (esc_html / esc_url).
	 * It is passed through wp_kses() with an explicit allowlist that keeps the
	 * schema.org microdata (itemscope / itemtype / itemprop and the <meta> position
	 * elements) that wp_kses_post() would otherwise strip, breaking the
	 * BreadcrumbList structured data.
	 */
	function _ufo_the_breadcrumb() {
		$allowed = array(
			'nav'  => array(
				'aria-label' => true,
				'class'      => true,
			),
			'ol'   => array(
				'class'     => true,
				'itemscope' => true,
				'itemtype'  => true,
			),
			'li'   => array(
				'class'        => true,
				'itemprop'     => true,
				'itemscope'    => true,
				'itemtype'     => true,
				'aria-current' => true,
			),
			'a'    => array(
				'href'     => true,
				'class'    => true,
				'itemprop' => true,
			),
			'span' => array(
				'class'    => true,
				'itemprop' => true,
			),
			'meta' => array(
				'itemprop' => true,
				'content'  => true,
			),
			'i'    => array(
				'class'       => true,
				'data-lucide' => true,
				'aria-hidden' => true,
			),
		);

		echo wp_kses( _ufo_get_breadcrumb(), $allowed );
	}
endif;
