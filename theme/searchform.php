<?php
/**
 * The template for displaying search forms (`get_search_form()`)
 *
 * Same look as the header search dropdown (see
 * template-parts/layout/header-search.php). Ids go through
 * `wp_unique_id()`, since a page can show this form more than once.
 *
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 * @package Ufo_Boilerplate
 */

$_ufo_input_id = wp_unique_id( 'search-form-input-' );

?>

<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="not-prose flex w-full max-w-md items-center gap-2">
	<label for="<?php echo esc_attr( $_ufo_input_id ); ?>" class="sr-only"><?php esc_html_e( 'Search the site', 'ufo-boilerplate' ); ?></label>
	<input
		type="search"
		id="<?php echo esc_attr( $_ufo_input_id ); ?>"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'Search…', 'ufo-boilerplate' ); ?>"
		class="w-full rounded-md border-0 bg-foreground/5 px-3 py-2.5 text-sm text-foreground placeholder:text-foreground/50 focus:outline-2 focus:outline-primary"
	>
	<button type="submit" class="btn is-style-primary shrink-0 cursor-pointer">
		<i data-lucide="search" class="size-4" aria-hidden="true"></i>
		<?php esc_html_e( 'Search', 'ufo-boilerplate' ); ?>
	</button>
</form>
