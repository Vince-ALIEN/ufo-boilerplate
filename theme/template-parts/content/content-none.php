<?php
/**
 * Template part for displaying a message when posts are not found
 *
 * The page title is printed by the calling template (see
 * template-parts/layout/page-header.php); this only renders the message.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Ufo_Boilerplate
 */

?>

<div class="mb-16 rounded-2xl bg-foreground/5 px-6 py-12 text-center">
	<i data-lucide="search" class="mx-auto size-12 text-foreground/40" aria-hidden="true"></i>

	<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

		<p class="not-prose mt-4 text-lg font-primary font-semibold text-foreground">
			<?php esc_html_e( 'Your site is set to show the most recent posts on your homepage, but you haven&rsquo;t published any posts.', 'ufo-boilerplate' ); ?>
		</p>
		<a href="<?php echo esc_url( admin_url( 'edit.php' ) ); ?>" class="not-prose btn is-style-primary mt-6">
			<?php esc_html_e( 'Add or publish posts', 'ufo-boilerplate' ); ?>
		</a>

	<?php else : ?>

		<p class="not-prose mt-4 text-lg font-primary font-semibold text-foreground">
			<?php
			if ( is_search() ) {
				esc_html_e( 'Your search generated no results. Please try a different search.', 'ufo-boilerplate' );
			} else {
				esc_html_e( 'No content matched your request.', 'ufo-boilerplate' );
			}
			?>
		</p>
		<div class="mt-6 flex justify-center">
			<?php get_search_form(); ?>
		</div>

	<?php endif; ?>
</div>
