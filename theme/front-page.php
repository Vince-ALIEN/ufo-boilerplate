<?php
/**
 * The template for displaying the front page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#front-page-display
 *
 * @package Ufo_Boilerplate
 */

get_header();
?>

	<section id="primary">
		<main id="main">

			<?php get_template_part( 'template-parts/content/content', 'frontpage' ); ?>

		</main><!-- #main -->
	</section><!-- #primary -->

	<article class="page-content" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( have_posts() ) : ?>
		<?php
		while ( have_posts() ) :
			the_post();
			?>

		<div <?php _ufo_content_class( 'entry-content' ); ?>>
			<?php
			the_content();

			wp_link_pages(
				array(
					'before' => '<div>' . __( 'Pages:', 'ufo-boilerplate' ),
					'after'  => '</div>',
				)
			);
			?>
		</div><!-- .entry-content -->

			<?php if ( get_edit_post_link() ) : ?>
			<footer class="entry-footer">
				<?php
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
				?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>

		<?php endwhile; ?>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php
get_footer();
