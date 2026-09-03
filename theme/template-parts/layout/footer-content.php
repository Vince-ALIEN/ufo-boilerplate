<?php
/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Ufo_Boilerplate
 */

$_ufo_tagline = get_bloginfo( 'description', 'display' );

?>

<footer id="colophon" class="bg-background">
	<div class="mx-auto max-w-content px-6 pt-16 pb-8 sm:pt-24 lg:px-8 lg:pt-32">
		<div class="xl:grid xl:grid-cols-3 xl:gap-8">
			<div class="space-y-8">
				<?php echo _Ufo_Options::footer_logo_html( '' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

				<?php if ( $_ufo_tagline ) : ?>
					<p class="text-sm/6 text-balance text-foreground my-4"><?php echo esc_html( $_ufo_tagline ); ?></p>
				<?php endif; ?>

				<div class="flex gap-x-6">
					<?php _Ufo_Social_Media::the_links( 'text-foreground/70 hover:text-foreground', 'size-6' ); ?>
				</div>
			</div>

			<div class="mt-16 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">
				<div class="md:grid md:grid-cols-2 md:gap-8">
					<?php if ( has_nav_menu( 'menu-solutions' ) ) : ?>
						<div>
							<h3 class="text-sm/6 font-semibold text-foreground"><?php esc_html_e( 'Solutions', 'ufo-boilerplate' ); ?></h3>
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'menu-solutions',
									'container'      => false,
									'items_wrap'     => '<ul role="list" class="mt-6 space-y-2">%3$s</ul>',
									'depth'          => 1,
								)
							);
							?>
						</div>
					<?php endif; ?>

					<?php if ( has_nav_menu( 'menu-support' ) ) : ?>
						<div class="mt-10 md:mt-0">
							<h3 class="text-sm/6 font-semibold text-foreground"><?php esc_html_e( 'Support', 'ufo-boilerplate' ); ?></h3>
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'menu-support',
									'container'      => false,
									'items_wrap'     => '<ul role="list" class="mt-6 space-y-2">%3$s</ul>',
									'depth'          => 1,
								)
							);
							?>
						</div>
					<?php endif; ?>
				</div>

				<div class="md:grid md:grid-cols-2 md:gap-8">
					<?php if ( has_nav_menu( 'menu-2' ) ) : ?>
						<div>
							<h3 class="text-sm/6 font-semibold text-foreground"><?php esc_html_e( 'Company', 'ufo-boilerplate' ); ?></h3>
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'menu-2',
									'container'      => false,
									'items_wrap'     => '<ul role="list" class="mt-6 space-y-2">%3$s</ul>',
									'depth'          => 1,
								)
							);
							?>
						</div>
					<?php endif; ?>
					<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
						<div class="">
							<?php dynamic_sidebar( 'sidebar-1' ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

		<div class="mt-16 border-t border-foreground/10 pt-8 sm:mt-20 lg:mt-24 lg:flex gap-y-2 sm:gap-y-0 sm:justify-between items-end sm:items-center">
			<p class="text-sm font-semibold text-foreground/70">
				<?php
				printf(
					/* translators: 1: Current year, 2: Site name. */
					esc_html__( '© %1$s %2$s. All rights reserved.', 'ufo-boilerplate' ),
					esc_html( gmdate( 'Y' ) ),
					esc_html( get_bloginfo( 'name' ) )
				);
				?>
			</p>
			<?php if ( has_nav_menu( 'menu-3' ) ) : ?>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-3',
							'container'      => false,
							'items_wrap'     => '<ul role="list" class="not-prose flex space-x-3 pb-4">%3$s</ul>',
							'depth'          => 1,
						)
					);
					?>
			<?php endif; ?>
		</div>
	</div>
</footer><!-- #colophon -->
