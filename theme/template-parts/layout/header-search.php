<?php
/**
 * Template part for displaying the header search toggle and its dropdown
 *
 * Rendered twice by header-content.php (desktop, next to the nav; mobile,
 * inside the right-hand group), since the two sit under different flex
 * parents. Each instance gets its own ids through `$args['id']`.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Ufo_Boilerplate
 *
 * @param array $args {
 *     @type string $id           Suffix making the panel/input ids unique.
 *     @type string $class        Classes for the wrapper (breakpoint visibility,
 *                                `relative` to anchor the panel under the button).
 *     @type string $button_class Extra classes for the toggle button.
 * }
 */

$_ufo_args = wp_parse_args(
	$args,
	array(
		'id'           => 'desktop',
		'class'        => '',
		'button_class' => '',
	)
);

$_ufo_panel_id = 'header-search-panel-' . $_ufo_args['id'];
$_ufo_input_id = 'header-search-input-' . $_ufo_args['id'];

?>

<div data-dropdown-item class="<?php echo esc_attr( $_ufo_args['class'] ); ?>">
	<button
		type="button"
		data-dropdown-btn
		data-search-btn
		aria-expanded="false"
		aria-controls="<?php echo esc_attr( $_ufo_panel_id ); ?>"
		class="<?php echo esc_attr( trim( $_ufo_args['button_class'] . ' inline-flex cursor-pointer items-center justify-center rounded-md p-2.5 text-foreground hover:text-primary' ) ); ?>"
	>
		<span class="sr-only"><?php esc_html_e( 'Search', 'ufo-boilerplate' ); ?></span>
		<i data-lucide="search" class="size-5" aria-hidden="true"></i>
	</button>

	<!--
		`right-0`/`top-full` resolve against the nearest positioned
		ancestor, picked per instance through `$args['class']`:
		- desktop passes `relative`, so the panel is centered on its
		  button. That wrapper also stretches over the bar's full
		  height (`self-stretch` + `-my-2.5` cancelling the bar's
		  `p-2.5`), so `top-full` is the bar's bottom edge — the same
		  reference the mega-menu panels use (see class-navwalker.php)
		  — and `lg:mt-4` matches their gap (the inner card's `mt-4`;
		  the outer panel's own `mt-4` is reset with `lg:mt-0`), whatever
		  the logo's height;
		- mobile doesn't: this tight-fitting wrapper sits well left of
		  the right edge once the CTA/burger take their place beside
		  it, so a panel anchored here would overflow past the left
		  edge of the viewport. It bubbles up to the content-width
		  wrapper in header-content.php instead, which is `relative` at
		  every breakpoint (it has to be, since on the front page
		  `#masthead` itself is collapsed to `height: 0` — see `.home
		  #masthead` in utilities.css — so `top-full` relative to
		  *that* would place the panel at the very top of the page).
	-->
	<div
		data-dropdown-panel
		id="<?php echo esc_attr( $_ufo_panel_id ); ?>"
		class="hidden absolute right-0 top-full z-50 w-full lg:right-auto lg:left-1/2 lg:-translate-x-1/2 lg:mt-4 lg:w-72 overflow-hidden rounded-xl bg-background p-2 shadow-lg outline-1 outline-foreground/10 transition-[opacity,translate] duration-200 ease-out opacity-0 translate-y-1"
	>
		<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-2">
			<label for="<?php echo esc_attr( $_ufo_input_id ); ?>" class="sr-only"><?php esc_html_e( 'Search the site', 'ufo-boilerplate' ); ?></label>
			<input
				type="search"
				id="<?php echo esc_attr( $_ufo_input_id ); ?>"
				data-search-input
				name="s"
				value="<?php echo esc_attr( get_search_query() ); ?>"
				placeholder="<?php esc_attr_e( 'Search…', 'ufo-boilerplate' ); ?>"
				class="w-full rounded-md border-0 bg-foreground/5 px-3 py-2 text-sm text-foreground placeholder:text-foreground/50 focus:outline-2 focus:outline-primary"
			>
			<button type="submit" class="inline-flex shrink-0 items-center justify-center rounded-md bg-primary p-2 text-background hover:bg-primary-800">
				<span class="sr-only"><?php esc_html_e( 'Submit search', 'ufo-boilerplate' ); ?></span>
				<i data-lucide="search" class="size-4" aria-hidden="true"></i>
			</button>
		</form>
	</div>
</div>
