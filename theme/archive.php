<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Ufo_Boilerplate
 */

get_header();
?>

<section class="entry-content relative isolate mx-auto mt-12 px-4" id="primary">

	<?php
	get_template_part(
		'template-parts/layout/page-header',
		null,
		array(
			'title'       => wp_kses_post( get_the_archive_title() ),
			'description' => esc_html( wp_strip_all_tags( get_the_archive_description() ) ),
		)
	);
	?>

	<main id="main">

		<?php if ( have_posts() ) : ?>

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
