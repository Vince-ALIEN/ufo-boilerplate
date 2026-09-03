<?php
/**
 * Template part for displaying pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Ufo_Boilerplate
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header px-2 mb-4">
		<div class="bg-background">
			<div class="entry-content py-22 sm:px-6 sm:py-28 lg:px-8">
			<div class="relative isolate overflow-hidden bg-slate-900 px-6 py-24 shadow-2xl rounded-2xl sm:px-16">
				<?php if ( _ufo_can_show_post_thumbnail() ) : ?>
				<div class="absolute inset-0 -z-10">
					<?php the_post_thumbnail( 'full', array( 'class' => 'w-full h-full object-cover' ) ); ?>
				</div>
				<?php endif; ?>
				<?php
				if ( ! is_front_page() ) {
					the_title( '<h1 class="entry-title text-background"  itemprop="name">', '</h1>' );
				} else {
					the_title( '<h2 class="entry-title text-background"  itemprop="name">', '</h2>' );
				}
				?>
				<div class="text-background px-4">
				<?php
				if ( function_exists( '_ufo_the_breadcrumb' ) ) {
					_ufo_the_breadcrumb();}
				?>
				</div>
				<?php if ( has_excerpt() ) : ?>
				<p class="max-w-xl text-lg/8 text-pretty text-slate-100 px-4"><?php echo esc_html( get_the_excerpt() ); ?></p>
				<?php endif; ?>
				<?php
				$_ufo_cta_primary_url   = _ufo_get_cta_field( '_ufo_cta_primary_url' );
				$_ufo_cta_secondary_url = _ufo_get_cta_field( '_ufo_cta_secondary_url' );
				?>
				<?php if ( $_ufo_cta_primary_url || $_ufo_cta_secondary_url ) : ?>
				<div class="mt-10 flex items-center gap-x-6 px-4">
					<?php if ( $_ufo_cta_primary_url ) : ?>
				<a href="<?php echo esc_url( $_ufo_cta_primary_url ); ?>" class="rounded-md bg-background px-3.5 py-2.5 text-sm font-semibold text-slate-900 shadow-xs hover:bg-slate-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white dark:bg-white/10 dark:text-white dark:shadow-none dark:inset-ring dark:inset-ring-white/5 dark:hover:bg-white/15 dark:focus-visible:outline-white"> <?php echo esc_html( _ufo_get_cta_field( '_ufo_cta_primary_label' ) ); ?> </a>
				<?php endif; ?>
					<?php if ( $_ufo_cta_secondary_url ) : ?>
				<a href="<?php echo esc_url( $_ufo_cta_secondary_url ); ?>" class="text-sm/6 font-semibold text-background">
						<?php echo esc_html( _ufo_get_cta_field( '_ufo_cta_secondary_label' ) ); ?>
					<span aria-hidden="true">→</span>
				</a>
				<?php endif; ?>
				</div>
				<?php endif; ?>
				<svg viewBox="0 0 1024 1024" aria-hidden="true" class="absolute top-1/2 left-1/2 -z-10 size-256 -translate-x-1/2 mask-[radial-gradient(closest-side,white,transparent)]">
				<circle r="512" cx="512" cy="512" fill="url(#827591b1-ce8c-4110-b064-7cb85a0b1217)" fill-opacity="0.7" />
				<defs>
					<radialGradient id="827591b1-ce8c-4110-b064-7cb85a0b1217">
					<stop stop-color="#7775D6" />
					<stop offset="1" stop-color="#E935C1" />
					</radialGradient>
				</defs>
				</svg>
			</div>
			</div>
		</div>
	</header><!-- .entry-header -->

	<div itemprop="mainContentOfPage" <?php _ufo_content_class( 'entry-content' ); ?>>
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

</article><!-- #post-<?php the_ID(); ?> -->
