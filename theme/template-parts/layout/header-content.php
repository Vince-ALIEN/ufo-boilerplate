<?php
/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Ufo_Boilerplate
 */

?>

<header id="masthead" data-burger class="entry-content sticky top-0 z-40">
	<nav aria-label="<?php esc_attr_e( 'Global', 'ufo-boilerplate' ); ?>">
		<!--
			This inner div (not `nav` itself) carries the content-width layout.
			`nav` is a direct child of `header.entry-content`, so without this
			wrapper it would inherit `.entry-content > * { max-w-content mx-auto }`
			and shrink to the centered content column — leaving the scroll
			background (applied to `nav`, see utilities.css) narrower than the
			full-width `#masthead`, with transparent gaps on both sides.
		-->
		<div class="mx-auto flex max-w-content items-center justify-between gap-x-6 p-2.5 lg:relative">
			<div class="flex lg:flex-1">
				<?php if ( has_custom_logo() ) : ?>
					<div class="-m-1.5 p-1.5">
						<?php the_custom_logo(); ?>
					</div>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="-m-1.5 p-1.5" rel="home">
						<span class="text-base font-semibold text-foreground"><?php bloginfo( 'name' ); ?></span>
					</a>
				<?php endif; ?>
			</div>

			<?php if ( has_nav_menu( 'menu-1' ) ) : ?>
				<div class="hidden lg:block">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'container'      => false,
							'items_wrap'     => '<ul class="flex gap-x-12">%3$s</ul>',
							'walker'         => new _Ufo_Header_Nav_Walker( 'text-sm/6 font-semibold text-foreground', 'primary' ),
						)
					);
					?>
				</div>
			<?php endif; ?>

			<!--
				Always rendered (even with no CTA URL and no widget), so this
				flex-1 item keeps balancing the logo's flex-1 item on the
				left and the nav menu stays centered on desktop.
			-->
			<div class="flex flex-1 items-center justify-end gap-x-6 lg:min-w-40">
				<?php if ( _Ufo_Header_Cta::url() ) : ?>
					<a href="<?php echo esc_url( _Ufo_Header_Cta::url() ); ?>" class="rounded-md bg-primary px-3 py-2 text-sm font-semibold text-background shadow-xs hover:bg-barossa-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"><?php echo esc_html( _Ufo_Header_Cta::text() ); ?></a>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'menu_widget_area' ) ) : ?>
					<div class="flex items-center gap-x-2">
						<?php dynamic_sidebar( 'menu_widget_area' ); ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( has_nav_menu( 'menu-1' ) ) : ?>
				<div class="flex lg:hidden">
					<button
						type="button"
						data-burger-btn
						aria-expanded="false"
						aria-controls="mobile-menu"
						class="relative z-60 -m-2.5 inline-flex cursor-pointer items-center justify-center rounded-md p-2.5 text-primary"
					>
						<span class="sr-only"><?php esc_html_e( 'Open main menu', 'ufo-boilerplate' ); ?></span>
						<div class="relative flex h-6 w-6 items-center justify-center">
							<span data-bar="top" class="absolute h-0.5 w-6 -translate-y-2 transform bg-primary transition duration-300 ease-in-out"></span>
							<span data-bar="mid" class="absolute h-0.5 w-6 transform bg-primary opacity-100 transition duration-300"></span>
							<span data-bar="bot" class="absolute h-0.5 w-6 translate-y-2 transform bg-primary transition duration-300 ease-in-out"></span>
						</div>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</nav>

	<?php if ( has_nav_menu( 'menu-1' ) ) : ?>
		<div id="mobile-menu" data-mobile-menu class="absolute inset-x-0 z-50 hidden max-h-[calc(100dvh-100%)] overflow-y-auto bg-background p-6 shadow-lg ring-1 ring-foreground/10 lg:hidden">
			<nav aria-label="<?php esc_attr_e( 'Mobile', 'ufo-boilerplate' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'menu-1',
						'container'      => false,
						'items_wrap'     => '<ul class="space-y-2">%3$s</ul>',
						'walker'         => new _Ufo_Header_Nav_Walker( '-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-foreground hover:bg-foreground/5', 'mobile' ),
					)
				);
				?>
			</nav>
		</div>
	<?php endif; ?>
</header><!-- #masthead -->
