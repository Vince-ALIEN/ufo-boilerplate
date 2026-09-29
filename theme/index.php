<?php
/**
 * The main template file
 *
 * @package Ufo_Boilerplate
 */

get_header();
?>

<section class="entry-content relative isolate mx-auto mt-12 px-4" id="primary">

	<!-- En-tête de page reste inchangé -->
	<div class="relative">
		<h1 class="not-prose entry-title mb-2 text-primary">
			<?php single_post_title(); ?>
		</h1>

		<div class="mb-4 font-semibold pt-2">
			<?php
			if ( function_exists( '_ufo_the_breadcrumb' ) ) {
				_ufo_the_breadcrumb();}
			?>
		</div>

				<?php
				$_ufo_sticky_ids     = get_option( 'sticky_posts' );
				$_ufo_featured_query = new WP_Query(
					array(
						'posts_per_page' => 1,
						'post__in'       => ! empty( $_ufo_sticky_ids ) ? $_ufo_sticky_ids : array( 0 ),
						'orderby'        => 'date',
						'order'          => 'DESC',
					)
				);
				if ( ! $_ufo_featured_query->have_posts() ) {
					$_ufo_featured_query = new WP_Query( array( 'posts_per_page' => 1 ) );
				}
				if ( $_ufo_featured_query->have_posts() ) :
					$_ufo_featured_query->the_post();
					$_ufo_feat_categories = get_the_category();
					$_ufo_feat_category   = ! empty( $_ufo_feat_categories ) ? $_ufo_feat_categories[0] : null;
					?>
				<article class="relative isolate flex flex-col justify-end overflow-hidden rounded-3xl bg-slate-900 px-8 pb-8 min-h-[600px] mb-8">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php
						// Image LCP de la page blog : chargée en priorité (pas de
						// lazy-loading) et en taille adaptée. wp_get_attachment_image
						// ajoute width/height/srcset/sizes → pas de CLS.
						the_post_thumbnail(
							'large',
							array(
								'class'         => 'not-prose absolute inset-0 -z-10 w-full h-full object-cover',
								'alt'           => wp_strip_all_tags( get_the_title() ),
								'loading'       => 'eager',
								'fetchpriority' => 'high',
								'sizes'         => '(max-width: 768px) 100vw, 1152px',
							)
						);
						?>
					<?php endif; ?>
					<div class="absolute inset-0 -z-10 bg-linear-to-t from-slate-900 via-slate-900/40"></div>
					<div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-slate-900/10"></div>

					<div class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm/6 text-slate-100">
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="mr-8">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
						<?php if ( $_ufo_feat_category ) : ?>
							<div class="-ml-4 flex items-center gap-x-4">
								<svg viewBox="0 0 2 2" class="-ml-0.5 size-0.5 flex-none fill-white/50" aria-hidden="true"><circle r="1" cx="1" cy="1" /></svg>
								<span><?php echo esc_html( $_ufo_feat_category->name ); ?></span>
							</div>
						<?php endif; ?>
					</div>

					<h2 class="not-prose mt-3 text-2xl font-primary font-semibold text-background line-clamp-2">
						<?php the_title(); ?>
					</h2>
					<p class="not-prose mt-2 line-clamp-3 text-sm/6 text-slate-300 max-w-2xl">
						<?php echo esc_html( _ufo_truncate_text( get_the_excerpt(), 160 ) ); ?>
					</p>
					<a href="<?php the_permalink(); ?>" class="not-prose mt-4 inline-flex w-fit items-center is-style-primary btn">
						<span class="absolute inset-0" aria-hidden="true"></span>
						<?php esc_html_e( 'Lire l\'article', 'ufo-boilerplate' ); ?>
					</a>
				</article>
					<?php
				endif;
				wp_reset_postdata();
				?>
	</div>

	<main id="main">
		<?php
		// init_filter() returns the already-rendered template-parts/ajax-filter/base
		// markup (escaped at the point of output in that template).
		echo _Ufo_Ajax_Filter::get_instance()->init_filter( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'post_type'      => 'post',
				'posts_per_page' => 9,
				'multiselect'    => 'false',
				'tax'            => 'category',
			)
		);
		?>
	</main>
</section>

<?php
get_footer();
?>
