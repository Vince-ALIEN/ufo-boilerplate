<?php
/**
 * Custom nav walker used by the header template part
 *
 * Renders top-level items as `$link_class`-styled links/buttons, and for
 * items with children, a dropdown/mega menu panel driven by the existing
 * VanJS `initMegaMenus()` in `javascript/ui.js` (hooks: `data-dropdown-item`,
 * `data-dropdown-btn`, `data-dropdown-panel`, `data-chevron`).
 *
 * @package Ufo_Boilerplate
 */

if ( ! class_exists( '_Ufo_Header_Nav_Walker' ) ) {
	class _Ufo_Header_Nav_Walker extends Walker_Nav_Menu {

		/**
		 * @var string Classes applied to each top-level (depth 0) link/button.
		 */
		protected $link_class;

		/**
		 * The depth-0 menu item currently being processed.
		 *
		 * `start_lvl()`/`end_lvl()` only receive `$depth`, not the item itself,
		 * so `start_el()` stashes it here right before the walker descends
		 * into that item's children, letting `start_lvl()` read its mega menu
		 * settings (see `is_mega_menu()`).
		 *
		 * @var WP_Post|null
		 */
		protected $current_top_level_item = null;

		/**
		 * @param string $link_class Classes to apply to each top-level menu link/button.
		 */
		public function __construct( $link_class ) {
			$this->link_class = $link_class;
		}

		/**
		 * Whether a menu item has the "Méga menu" option enabled.
		 *
		 * @param WP_Post|null $item Menu item object.
		 * @return bool
		 */
		protected function is_mega_menu( $item ) {
			return $item && '1' === get_post_meta( $item->ID, '_menu-item-mega-menu', true );
		}

		/**
		 * Extract a Lucide icon name from menu item meta.
		 *
		 * @param WP_Post $item Menu item object.
		 * @return string|false Icon name, or false if none set.
		 */
		protected function get_menu_icon( $item ) {
			$icon = get_post_meta( $item->ID, '_menu-item-icon', true );
			return ! empty( $icon ) ? $icon : false;
		}

		/**
		 * Renders the featured-posts column for a mega menu item.
		 *
		 * Reads the two posts chosen in the menu editor (see
		 * menu-custom-fields.php) and renders a compact card for each:
		 * thumbnail, date, category, title.
		 *
		 * @param WP_Post $item Menu item object (the depth-0 mega menu item).
		 * @return string HTML, or an empty string if no posts were chosen.
		 */
		protected function render_featured_posts( $item ) {
			$post_ids = array_filter(
				array(
					(int) get_post_meta( $item->ID, '_menu-item-featured-post-1', true ),
					(int) get_post_meta( $item->ID, '_menu-item-featured-post-2', true ),
				)
			);

			if ( empty( $post_ids ) ) {
				return '';
			}

			$query = new WP_Query(
				array(
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'post__in'       => $post_ids,
					'orderby'        => 'post__in',
					'posts_per_page' => count( $post_ids ),
					'no_found_rows'  => true,
				)
			);

			if ( ! $query->have_posts() ) {
				return '';
			}

			$output = '<div class="mega-menu-featured-posts grid lg:grid-cols-2 grid-cols-1 gap-2">';

			while ( $query->have_posts() ) {
				$query->the_post();

				$categories = get_the_category();
				$category   = ! empty( $categories ) ? $categories[0] : null;

				$output .= '<a href="' . esc_url( get_permalink() ) . '" class="group relative gap-4 rounded-lg p-3 hover:bg-primary/5">';

				if ( has_post_thumbnail() ) {
					$output .= '<div class="flex-none overflow-hidden rounded-lg bg-primary/5">';
					$output .= '<img src="' . esc_url( get_the_post_thumbnail_url( get_the_ID(), 'medium' ) ) . '" alt="" class="w-full h-full object-cover" loading="lazy" />';
					$output .= '</div>';
				}

				$output .= '<div class="min-w-0 flex-auto">';
				$output .= '<div class="flex items-center gap-x-2 my-1 text-xs text-foreground/70">';
				$output .= '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
				if ( $category ) {
					$output .= '<span aria-hidden="true">&middot;</span><span>' . esc_html( $category->name ) . '</span>';
				}
				$output .= '</div>';
				$output .= '<p class="mt-2 line-clamp-2 text-sm font-semibold text-foreground group-hover:text-primary">' . esc_html( get_the_title() ) . '</p>';
				$output .= '<p class="line-clamp-2 text-xs text-foreground/70">' . esc_html( _ufo_truncate_text( get_the_excerpt() ) ) . '</p>';
				$output .= '</div>';
				$output .= '</a>';
			}

			wp_reset_postdata();

			$output .= '</div>';

			return $output;
		}

		/**
		 * @see Walker::start_lvl()
		 */
		public function start_lvl( &$output, $depth = 0, $args = null ) {
			if ( 0 !== $depth ) {
				$output .= '<ul class="submenu">';
				return;
			}

			$is_mega_menu = $this->is_mega_menu( $this->current_top_level_item );

			// Outer wrapper: positioning + transition classes (JS toggles hidden/opacity).
			//
			// Mega menus align to the header's content-width wrapper (the nearest
			// `lg:relative` ancestor, see header-content.php) via `lg:inset-x-0`, so
			// the panel's edges match the site's `max-w-content` column exactly.
			// Regular dropdowns stay centered under their trigger button instead —
			// see the `relative` class kept on the `<li>` in start_el() for those.
			$panel_position_classes = $is_mega_menu
				? 'lg:inset-x-0'
				: 'lg:left-1/2 lg:-translate-x-1/2';
			$output .= '<div data-dropdown-panel class="hidden lg:absolute lg:top-full ' . $panel_position_classes . ' lg:z-50 mx-auto overflow-visible mt-4 transition-[opacity,transform] duration-200 ease-out opacity-0 translate-y-1">';

			// Inner card wrapper: mega menus cap at the same `max-w-content` used
			// for the rest of the site so the card matches the page's content
			// column exactly (its `md:w-screen` base would otherwise stay full
			// viewport width). Regular dropdowns keep their narrower cap.
			$panel_max_width = $is_mega_menu ? 'lg:max-w-content' : 'lg:max-w-3xl';
			$output          .= '<div class="md:w-screen max-w-md mt-4 flex-auto overflow-hidden md:rounded-3xl bg-background text-sm/6 md:shadow-lg md:outline-1 outline-foreground/10 ' . $panel_max_width . '">';

			if ( $is_mega_menu ) {
				$output .= '<div class="grid grid-cols-1 gap-x-8 gap-y-6 p-4 lg:grid-cols-2 lg:p-6">';
				$output .= '<ul class="submenu grid md:grid-cols-2 grid-cols-1 gap-x-6 gap-y-1">';
			} else {
				$output .= '<ul class="submenu grid grid-cols-1 gap-x-6 gap-y-1 p-4 lg:grid-cols-2">';
			}
		}

		/**
		 * @see Walker::end_lvl()
		 */
		public function end_lvl( &$output, $depth = 0, $args = null ) {
			if ( 0 !== $depth ) {
				$output .= '</ul>';
				return;
			}

			$is_mega_menu = $this->is_mega_menu( $this->current_top_level_item );

			$output .= '</ul>';

			if ( $is_mega_menu ) {
				$output .= $this->render_featured_posts( $this->current_top_level_item );
				$output .= '</div>'; // Close two-column grid.
			}

			$output .= '</div>'; // Close inner card wrapper.
			$output .= '</div>'; // Close outer positioning wrapper.

			$this->current_top_level_item = null;
		}

		/**
		 * @see Walker::start_el()
		 */
		public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
			$classes      = empty( $item->classes ) ? array() : (array) $item->classes;
			$has_children = in_array( 'menu-item-has-children', $classes, true );

			if ( 0 === $depth ) {
				$this->current_top_level_item = $item;

				if ( $has_children ) {
					/*
					 * Regular dropdowns position their panel relative to this `<li>`
					 * (centered under the trigger button), so it needs `relative`.
					 * Mega menus instead align to the header's content-width
					 * wrapper (see start_lvl()), so this `<li>` must stay
					 * unpositioned and let the panel's `absolute` bubble up to
					 * that ancestor.
					 */
					$li_class = $this->is_mega_menu( $item ) ? 'list-none' : 'relative list-none';
					$output  .= '<li class="' . esc_attr( $li_class ) . '" data-dropdown-item>';
					$output  .= '<button type="button" data-dropdown-btn aria-expanded="false" aria-haspopup="true" class="inline-flex items-center gap-x-1 cursor-pointer ' . esc_attr( $this->link_class ) . '">';
					$output .= esc_html( $item->title );
					$output .= '<i data-lucide="chevron-down" data-chevron class="size-4 transition-transform duration-200" aria-hidden="true"></i>';
					$output .= '</button>';
				} else {
					$output .= '<li class="list-none">';
					$output .= '<a href="' . esc_url( $item->url ) . '" class="' . esc_attr( $this->link_class ) . '">' . esc_html( $item->title ) . '</a>';
				}
				return;
			}

			// Submenu items (depth > 0): icon + title (+ description) card, matching the dropdown/mega-menu panel.
			$icon_name = $this->get_menu_icon( $item );

			$output .= '<li class="list-none">';
			$output .= '<a href="' . esc_url( $item->url ) . '" class="group relative flex items-center gap-x-4 rounded-lg p-2 md:p-4 text-sm/6 hover:bg-primary/5">';

			if ( $icon_name ) {
				$output .= '<div class="flex flex-none items-center justify-center rounded-lg bg-primary/5 group-hover:bg-primary/10">';
				$output .= '<i data-lucide="' . esc_attr( $icon_name ) . '" class="size-6 text-barossa-600 group-hover:text-primary"></i>';
				$output .= '</div>';
			}

			$output .= '<div class="flex-auto">';
			$output .= '<span class="not-prose block font-semibold text-foreground group-hover:text-primary">' . esc_html( $item->title ) . '<span class="absolute inset-0"></span></span>';

			$description = get_post_meta( $item->ID, '_menu-item-description', true );
			if ( ! empty( $description ) ) {
				$output .= '<p class="not-prose text-sm text-foreground/70">' . esc_html( $description ) . '</p>';
			}

			$output .= '</div>';
			$output .= '</a>';
		}

		/**
		 * @see Walker::end_el()
		 */
		public function end_el( &$output, $item, $depth = 0, $args = null ) {
			$output .= '</li>';
		}
	}
}
