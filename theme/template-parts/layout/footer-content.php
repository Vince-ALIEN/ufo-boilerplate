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
<!-- ========== FOOTER ========== -->
<footer id="colophon" class="mt-auto bg-primary-950 text-background!">
	<div class="h-10 bg-background rounded-b-4xl"></div>
	<div class="entry-content p-6 sm:p-8 lg:p-12 text-background!">

		<!-- Grid -->
		<div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-6">

			<div class="lg:col-span-1">
				<a
					href="<?php echo esc_url( home_url( '/' ) ); ?>"
					class="mb-6 inline-block"
					rel="home"
					aria-label="<?php echo esc_attr( sprintf( /* translators: %s: site name. */ __( '%s – home', 'ufo-boilerplate' ), get_bloginfo( 'name' ) ) ); ?>"
				>
					<?php echo _Ufo_Options::footer_logo_html( 'size-12.5 object-contain' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markup built and escaped inside _Ufo_Options. ?>
				</a>
				<?php if ( $_ufo_tagline ) : ?>
					<p class="not-prose text-background text-sm"><?php echo esc_html( $_ufo_tagline ); ?></p>
				<?php endif; ?>
			</div>
			<!-- End Col -->

			<div class="lg:col-span-1">
				<p class="not-prose mb-2 text-background font-primary"><?php esc_html_e( 'Contact', 'ufo-boilerplate' ); ?></p>
				<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
					<aside class="mt-3 grid space-y-1 not-prose text-sm text-background!" role="complementary" aria-label="<?php esc_attr_e( 'Footer contact', 'ufo-boilerplate' ); ?>">
						<?php dynamic_sidebar( 'sidebar-2' ); ?>
					</aside>
				<?php endif; ?>
			</div>
			<!-- End Col -->

			<div class="lg:col-span-1">
				<p class="not-prose mb-2 text-background font-primary"><?php esc_html_e( 'Services', 'ufo-boilerplate' ); ?></p>
				<?php if ( has_nav_menu( 'menu-2' ) ) : ?>
					<nav class="mt-3" aria-label="<?php esc_attr_e( 'Footer menu', 'ufo-boilerplate' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'menu-2',
								'container'      => false,
								'items_wrap'     => '<ul class="group space-y-1">%3$s</ul>',
								'depth'          => 1,
								'fallback_cb'    => false,
								'link_before'    => '<span class="not-prose text-background hover:text-primary transition-colors">',
								'link_after'     => '</span>',
							)
						);
						?>
					</nav>
				<?php endif; ?>
			</div>
			<!-- End Col -->

			<div class="lg:col-span-2">
				<div class="w-full lg:w-2/3 lg:float-right">
					<p class="not-prose mb-2 text-background font-primary"><?php esc_html_e( 'Me suivre', 'ufo-boilerplate' ); ?></p>

					<?php if ( is_active_sidebar( 'sidebar-3' ) ) : ?>
						<aside role="complementary" aria-label="<?php esc_attr_e( 'Footer newsletter', 'ufo-boilerplate' ); ?>">
							<?php dynamic_sidebar( 'sidebar-3' ); ?>
						</aside>
					<?php endif; ?>

					<!-- Social Brands -->
					<div class="my-4 flex flex-wrap gap-2">
						<?php _Ufo_Social_Media::the_links( 'inline-flex items-center justify-center p-2 rounded-full bg-primary text-background hover:bg-primary-800', 'size-5' ); ?>
					</div>
					<!-- End Social Brands -->

					<div id="wcb" class="carbonbadge wcb-d py-4"></div>
				</div>
			</div>
			<!-- End Col -->

		</div>
		<!-- End Grid -->

		<div class="mt-10 lg:flex gap-y-2 sm:gap-y-0 sm:justify-between items-end sm:items-center">
			<p class="not-prose text-sm text-background font-primary">
				<?php
				printf(
					/* translators: 1: site name, 2: copyright notice, 3: current year. */
					esc_html__( '%1$s. %2$s © %3$s', 'ufo-boilerplate' ),
					esc_html( get_bloginfo( 'name' ) ),
					esc_html( get_theme_mod( 'ufo_copyright_text', __( 'Tous droits réservés', 'ufo-boilerplate' ) ) ),
					esc_html( gmdate( 'Y' ) )
				);
				?>
			</p>
			<?php if ( has_nav_menu( 'menu-3' ) ) : ?>
				<nav aria-label="<?php esc_attr_e( 'Footer legal', 'ufo-boilerplate' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'menu-3',
							'container'      => false,
							'items_wrap'     => '<ul class="flex flex-wrap gap-x-4 gap-y-1">%3$s</ul>',
							'depth'          => 1,
							'fallback_cb'    => false,
							'link_before'    => '<span class="text-sm text-slate-100 hover:text-white transition-colors">',
							'link_after'     => '</span>',
						)
					);
					?>
				</nav>
			<?php endif; ?>
		</div>

	</div>

</footer>
<!-- ========== END FOOTER ========== -->
