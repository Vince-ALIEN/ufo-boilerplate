<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Ufo_Boilerplate
 */

get_header();
?>

<section class="entry-content relative isolate mx-auto mt-12 px-4" id="primary">

	<?php
	// No breadcrumb here: on a missing page it would only read "Home >
	// Page not found", which the "Back to the homepage" button already covers.
	get_template_part(
		'template-parts/layout/page-header',
		null,
		array(
			'title'      => esc_html__( 'Page Not Found', 'ufo-boilerplate' ),
			'breadcrumb' => false,
			'class'      => 'text-center',
		)
	);
	?>

	<main id="main" class="mb-16 text-center">
		<p class="not-prose font-primary text-[7rem] leading-none font-semibold text-primary/15 select-none sm:text-[12rem]" aria-hidden="true">404</p>

		<p class="not-prose mx-auto mt-6 max-w-2xl text-lg/8 text-pretty text-foreground/70">
			<?php esc_html_e( 'This page could not be found.', 'ufo-boilerplate' ); ?><br>
			<?php esc_html_e( 'It might have been removed or renamed, or it may never have existed.', 'ufo-boilerplate' ); ?>
		</p>

		<div class="mt-8 grid justify-items-center gap-6">
			<?php get_search_form(); ?>

			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="not-prose btn is-style-secondary w-fit">
				<i data-lucide="arrow-left" class="size-4" aria-hidden="true"></i>
				<?php esc_html_e( 'Back to the homepage', 'ufo-boilerplate' ); ?>
			</a>
		</div>
	</main><!-- #main -->
</section><!-- #primary -->

<?php
get_footer();
