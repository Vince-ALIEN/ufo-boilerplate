<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some functionality here could be replaced by core features.
 *
 * @package Ufo_Boilerplate
 */

if ( ! function_exists( '_ufo_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function _ufo_posted_on() {
		$time_string = '<time class="published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		printf(
			'<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
endif;

if ( ! function_exists( '_ufo_posted_by' ) ) :
	/**
	 * Prints HTML with meta information about theme author.
	 */
	function _ufo_posted_by() {
		printf(
		/* translators: 1: posted by label, only visible to screen readers. 2: author link. 3: post author. */
			'<span class="sr-only">%1$s</span><span class="author vcard"><a class="url fn n" href="%2$s">%3$s</a></span>',
			esc_html__( 'Posted by', 'ufo-boilerplate' ),
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		);
	}
endif;

if ( ! function_exists( '_ufo_entry_meta' ) ) :
	/**
	 * Prints HTML with meta information for the categories and tags.
	 * This template tag is used in the entry header.
	 */
	function _ufo_entry_meta() {

		// Hide author, post date, category and tag text for pages.
		if ( 'post' === get_post_type() ) {

			// Posted by.
			_ufo_posted_by();

			// Posted on.
			_ufo_posted_on();

			/* translators: used between list items, there is a space after the comma. */
			$categories_list = get_the_category_list( __( ', ', 'ufo-boilerplate' ) );
			if ( $categories_list ) {
				printf(
				/* translators: 1: posted in label, only visible to screen readers. 2: list of categories. */
					'<span><span class="sr-only">%1$s</span>%2$s</span>',
					esc_html__( 'Posted in', 'ufo-boilerplate' ),
					$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}

			/* translators: used between list items, there is a space after the comma. */
			$tags_list = get_the_tag_list( '', __( ', ', 'ufo-boilerplate' ) );
			if ( $tags_list ) {
				printf(
				/* translators: 1: tags label, only visible to screen readers. 2: list of tags. */
					'<span><span class="sr-only">%1$s</span>%2$s</span>',
					esc_html__( 'Tags:', 'ufo-boilerplate' ),
					$tags_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
		}

		// Edit post link.
		edit_post_link(
			sprintf(
				wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers. */
					__( 'Edit <span class="sr-only">%s</span>', 'ufo-boilerplate' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
	}
endif;

if ( ! function_exists( '_ufo_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories and tags.
	 */
	function _ufo_entry_footer() {

		// Hide author, post date, category and tag text for pages.
		if ( 'post' === get_post_type() ) {

			// Posted by.
			_ufo_posted_by();

			// Posted on.
			_ufo_posted_on();

			/* translators: used between list items, there is a space after the comma. */
			$categories_list = get_the_category_list( __( ', ', 'ufo-boilerplate' ) );
			if ( $categories_list ) {
				printf(
				/* translators: 1: posted in label, only visible to screen readers. 2: list of categories. */
					'<span><span class="sr-only">%1$s</span>%2$s</span>',
					esc_html__( 'Posted in', 'ufo-boilerplate' ),
					$categories_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}

			/* translators: used between list items, there is a space after the comma. */
			$tags_list = get_the_tag_list( '', __( ', ', 'ufo-boilerplate' ) );
			if ( $tags_list ) {
				printf(
				/* translators: 1: tags label, only visible to screen readers. 2: list of tags. */
					'<span><span class="sr-only">%1$s</span>%2$s</span>',
					esc_html__( 'Tags:', 'ufo-boilerplate' ),
					$tags_list // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				);
			}
		}

		// Edit post link.
		edit_post_link(
			sprintf(
				wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers. */
					__( 'Edit <span class="sr-only">%s</span>', 'ufo-boilerplate' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
	}
endif;

if ( ! function_exists( '_ufo_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail, wrapping the post thumbnail in an
	 * anchor element except when viewing a single post.
	 */
	function _ufo_post_thumbnail() {
		if ( ! _ufo_can_show_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<figure>
				<?php the_post_thumbnail(); ?>
			</figure><!-- .post-thumbnail -->

			<?php
		else :
			?>

			<figure>
				<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
					<?php the_post_thumbnail(); ?>
				</a>
			</figure>

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( '_ufo_the_posts_navigation' ) ) :
	/**
	 * Wraps `the_posts_pagination` for use throughout the theme.
	 *
	 * Styled in components.css (`.pagination`), the Lucide chevrons are
	 * swapped for their SVG by `initIcons()` like everywhere else.
	 */
	function _ufo_the_posts_navigation() {
		the_posts_pagination(
			array(
				'mid_size'           => 2,
				'prev_text'          => '<i data-lucide="chevron-left" class="size-4" aria-hidden="true"></i>' . esc_html__( 'Previous', 'ufo-boilerplate' ),
				'next_text'          => esc_html__( 'Next', 'ufo-boilerplate' ) . '<i data-lucide="chevron-right" class="size-4" aria-hidden="true"></i>',
				'screen_reader_text' => __( 'Posts navigation', 'ufo-boilerplate' ),
				'aria_label'         => __( 'Posts', 'ufo-boilerplate' ),
				'before_page_number' => '<span class="sr-only">' . esc_html__( 'Page', 'ufo-boilerplate' ) . ' </span>',
			)
		);
	}
endif;

if ( ! function_exists( '_ufo_the_post_navigation' ) ) :
	/**
	 * Displays links to the previous and next posts on a single post.
	 *
	 * Custom markup rather than `the_post_navigation()`, whose fixed
	 * `.nav-links` wrapper can't carry the content-width layout. The post
	 * title is visible from `md` up; below that it stays in the link as
	 * `sr-only` text, so the accessible name is always "Previous <title>"
	 * and still starts with the visible label.
	 */
	function _ufo_the_post_navigation() {
		$links = array(
			'previous' => get_previous_post(),
			'next'     => get_next_post(),
		);

		if ( ! $links['previous'] && ! $links['next'] ) {
			return;
		}
		?>
		<nav class="post-navigation entry-content" aria-label="<?php esc_attr_e( 'Post navigation', 'ufo-boilerplate' ); ?>">
			<div class="max-w-content mx-auto flex justify-between gap-x-2 items-center px-4 pb-4">
				<?php
				foreach ( $links as $direction => $adjacent_post ) :
					if ( ! $adjacent_post ) {
						continue;
					}

					$is_next = 'next' === $direction;
					$label   = $is_next ? __( 'Next', 'ufo-boilerplate' ) : __( 'Previous', 'ufo-boilerplate' );
					$icon    = sprintf(
						'<i data-lucide="%1$s" class="size-5 p-1 %2$s bg-primary rounded-full text-background %3$s transition duration-200 ease-in-out" aria-hidden="true"></i>',
						$is_next ? 'arrow-right' : 'arrow-left',
						$is_next ? 'ml-1' : 'mr-1',
						$is_next ? 'group-hover:translate-x-2' : 'group-hover:-translate-x-2'
					);
					?>
					<div class="nav-<?php echo esc_attr( $direction ); ?><?php echo $is_next ? ' ml-auto' : ''; ?>">
						<a href="<?php echo esc_url( get_permalink( $adjacent_post ) ); ?>" rel="<?php echo $is_next ? 'next' : 'prev'; ?>" class="group flex flex-col <?php echo $is_next ? 'items-end text-right' : 'items-start'; ?>">
							<span class="text-sm mb-1 flex items-center">
								<?php
								// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- static icon markup built above.
								echo $is_next ? '' : $icon;
								echo esc_html( $label );
								echo $is_next ? $icon : '';
								// phpcs:enable
								?>
							</span>
							<?php // The space keeps the label and title apart in the link's accessible name. ?>
							&#32;<span class="sr-only md:not-sr-only md:block text-base group-hover:text-primary transition-colors duration-200"><?php echo esc_html( get_the_title( $adjacent_post ) ); ?></span>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</nav>
		<?php
	}
endif;

if ( ! function_exists( '_ufo_content_class' ) ) :
	/**
	 * Displays the class names for the post content wrapper.
	 *
	 * This allows us to add Tailwind Typography’s modifier classes throughout
	 * the theme without repeating them in multiple files. (They can be edited
	 * at the top of the `../functions.php` file via the
	 * _UFO_TYPOGRAPHY_CLASSES constant.)
	 *
	 * Based on WordPress core’s `body_class` and `get_body_class` functions.
	 *
	 * @param string|string[] $classes Space-separated string or array of class
	 *                                 names to add to the class list.
	 */
	function _ufo_content_class( $classes = '' ) {
		$all_classes = array( $classes, _UFO_TYPOGRAPHY_CLASSES );

		foreach ( $all_classes as &$class_groups ) {
			if ( ! empty( $class_groups ) ) {
				if ( ! is_array( $class_groups ) ) {
					$class_groups = preg_split( '#\s+#', $class_groups );
				}
			} else {
				// Ensure that we always coerce class to being an array.
				$class_groups = array();
			}
		}

		$combined_classes = array_merge( $all_classes[0], $all_classes[1] );
		$combined_classes = array_map( 'esc_attr', $combined_classes );

		// Separates class names with a single space, preparing them for the
		// post content wrapper.
		echo 'class="' . esc_attr( implode( ' ', $combined_classes ) ) . '"';
	}
endif;
