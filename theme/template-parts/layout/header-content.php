<?php
/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Ufo_Boilerplate
 */

$ufo_has_menu = has_nav_menu( 'menu-1' );
$ufo_cta_url  = _Ufo_Header_Cta::url();
?>

<header id="masthead" data-burger class="sticky top-0 z-40">
	<!--
		Full-width bar carrying the scroll background (see `.masthead-bar`
		in utilities.css); the inner div below holds the content-width layout.
	-->
	<div class="masthead-bar">
		<div class="relative mx-auto flex max-w-content items-center justify-between gap-x-6 p-2.5">
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

			<?php if ( $ufo_has_menu ) : ?>
				<nav aria-label="<?php esc_attr_e( 'Global', 'ufo-boilerplate' ); ?>" class="hidden lg:block lg:-my-2.5 lg:self-stretch">
					<?php
					/*
					 * The nav spans the bar's full height (padding included, like the
					 * desktop search below), so each top-level `<li>`'s bottom edge
					 * matches the header's: regular dropdowns (anchored to their `<li>`)
					 * and mega menus (anchored to the wrapper above) then both open
					 * from the same `lg:top-full` line. See class-navwalker.php.
					 */
					wp_nav_menu(
						array(
							'theme_location' => 'menu-1',
							'container'      => false,
							'items_wrap'     => '<ul class="flex h-full gap-x-12">%3$s</ul>',
							'walker'         => new _Ufo_Header_Nav_Walker( 'text-sm/6 font-semibold text-foreground', 'primary' ),
						)
					);
					?>
				</nav>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/layout/header-search', null, array( 'id' => 'desktop', 'class' => 'relative hidden lg:-my-2.5 lg:flex lg:items-center lg:self-stretch' ) ); ?>

			<!--
				Always rendered (even with no CTA URL and no widget), so this
				flex-1 item keeps balancing the logo's flex-1 item on the
				left and the nav menu stays centered on desktop.
			-->
			<div class="flex flex-1 items-center justify-end gap-x-6 lg:min-w-40">
				<?php get_template_part( 'template-parts/layout/header-search', null, array( 'id' => 'mobile', 'class' => 'lg:hidden', 'button_class' => '-m-2.5' ) ); ?>

				<?php if ( $ufo_cta_url ) : ?>
					<a href="<?php echo esc_url( $ufo_cta_url ); ?>" class="rounded-md bg-primary px-3 py-2 text-sm font-semibold text-background shadow-xs hover:bg-primary-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"><?php echo esc_html( _Ufo_Header_Cta::text() ); ?></a>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'menu_widget_area' ) ) : ?>
					<div class="flex items-center gap-x-2">
						<?php dynamic_sidebar( 'menu_widget_area' ); ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $ufo_has_menu ) : ?>
				<div class="flex lg:hidden">
					<button
						type="button"
						data-burger-btn
						aria-expanded="false"
						aria-controls="mobile-menu"
						class="relative z-60 -m-2.5 inline-flex cursor-pointer items-center justify-center rounded-md p-2.5 text-primary"
					>
						<span class="sr-only"><?php esc_html_e( 'Open main menu', 'ufo-boilerplate' ); ?></span>
						<span class="relative flex size-6 items-center justify-center" aria-hidden="true">
							<span data-bar="top" class="absolute h-0.5 w-6 -translate-y-2 bg-primary transition duration-300 ease-in-out"></span>
							<span data-bar="mid" class="absolute h-0.5 w-6 bg-primary opacity-100 transition duration-300"></span>
							<span data-bar="bot" class="absolute h-0.5 w-6 translate-y-2 bg-primary transition duration-300 ease-in-out"></span>
						</span>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( $ufo_has_menu ) : ?>
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
