<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Ufo_Boilerplate
 */

get_header();

global $wp_query;
?>

<section class="entry-content relative isolate mx-auto mt-12 px-4" id="primary">

	<?php
	get_template_part(
		'template-parts/layout/page-header',
		null,
		array(
			'title'       => sprintf(
				/* translators: %s: search term. */
				esc_html__( 'Search results for “%s”', 'ufo-boilerplate' ),
				esc_html( get_search_query() )
			),
			'description' => have_posts()
				? esc_html(
					sprintf(
						/* translators: %s: number of search results. */
						_n( '%s result found.', '%s results found.', (int) $wp_query->found_posts, 'ufo-boilerplate' ),
						number_format_i18n( (int) $wp_query->found_posts )
					)
				)
				: '',
		)
	);
	?>

	<main id="main">

		<?php if ( have_posts() ) : ?>

			<div class="mb-10">
				<?php get_search_form(); ?>
			</div>

			<div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content/content' );
				endwhile;
				?>
			</div>

			<?php
			_ufo_the_posts_navigation();

		else :

			get_template_part( 'template-parts/content/content', 'none' );

		endif;
		?>

	</main><!-- #main -->
</section><!-- #primary -->

<?php
get_footer();
