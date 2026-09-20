<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Ufo_Boilerplate
 */

?>

<article class="px-4 mt-32" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-content relative mx-auto mb-6">

	<div class="bg-slate-900 duration-700 relative mb-[50px] h-[500px] animate-fade overflow-hidden rounded-xl animate-once animate-ease-in-out md:h-[400px] lg:h-[500px]">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail(
				'full',
				array(
					'class'    => 'not-prose w-full h-full object-cover object-center rounded-xl',
					'itemprop' => 'image',
				)
			);
		}
		?>

			<svg viewBox="0 0 1024 1024" aria-hidden="true" class="absolute top-1/2 left-1/2 z-10 size-256 -translate-x-1/2 mask-[radial-gradient(closest-side,white,transparent)]">
				<circle r="512" cx="512" cy="512" fill="url(#827591b1-ce8c-4110-b064-7cb85a0b1217-single)" fill-opacity="0.7" />
				<defs>
					<radialGradient id="827591b1-ce8c-4110-b064-7cb85a0b1217-single">
					<stop stop-color="#7775D6" />
					<stop offset="1" stop-color="#E935C1" />
					</radialGradient>
				</defs>
				</svg>

			<div class="absolute mx-0 left-0 right-0 top-0 z-20 flex w-full h-full items-end">
				<div class="not-prose font-sans my-8 ml-10 mr-10 flex flex-col gap-y-3">
					<?php if ( function_exists( '_ufo_the_breadcrumb' ) ) : ?>
					<div class="text-background font-semibold italic flex overflow-x-auto no-scrollbar items-center text-sm">
						<?php _ufo_the_breadcrumb(); ?>
					</div>
					<?php endif; ?>
					<?php the_title( '<h1 class="entry-title text-background my-0" itemprop="name" data-anime-heading>', '</h1>' ); ?>
					<div class="flex flex-wrap items-center gap-x-1 text-sm text-slate-300">
						<?php echo get_avatar( get_the_author_meta( 'email' ), 50, '', '', array( 'class' => 'w-9 h-9 rounded-full mr-2' ) ); ?>
						<?php _ufo_entry_footer(); ?>
					</div>
				</div>
			</div>
		</div>

	</header><!-- .entry-header  -->

	<!-- Grille de contenu avec sidebar -->
	<div class="max-w-content mx-auto">
		<div class="mb-16 grid gap-8 lg:grid-cols-3 min-h-screen">
			<!-- Sidebar avec table des matières et CTA -->
			<div class="lg:col-span-1 md:h-fit md:sticky md:top-22 md:overflow-y-auto">
				<div class="p-6 grid gap-y-6 mt-6">
					<div class="mb-4">
						<p class="not-prose font-primary font-semibold text-primary text-lg mb-4">Table des matières</p>
						<div class="table-of-contents">
							<?php
							// Extraire les titres h2 du contenu pour la table des matières.
							$_ufo_toc_content = get_the_content();
							preg_match_all( '/<h2.*?>(.*?)<\/h2>/i', $_ufo_toc_content, $_ufo_toc_matches );

							if ( ! empty( $_ufo_toc_matches[0] ) ) {
								echo '<ul class="space-y-2">';
								foreach ( $_ufo_toc_matches[1] as $_ufo_toc_title ) {
									// Générer un ID basé sur le titre (même logique que l'injection plus bas).
									$_ufo_toc_id = sanitize_title( $_ufo_toc_title );
									echo '<li class="not-prose transition-colors">';
									echo '<a href="#' . esc_attr( $_ufo_toc_id ) . '" class="text-sm text-slate-600 flex items-center gap-2">';
									echo '<i data-lucide="chevron-right" class="size-5 text-primary"></i>';
									echo esc_html( wp_strip_all_tags( $_ufo_toc_title ) );
									echo '</a></li>';
								}
								echo '</ul>';
							} else {
								echo '<p>' . esc_html__( 'Aucun titre trouvé dans l\'article', 'ufo-boilerplate' ) . '</p>';
							}
							?>
						</div>
					</div>

					<!-- Call to Action -->
					<div class="mt-6 bg-primary p-6 rounded-lg">
						<h3 class="font-primary font-semibold text-lg text-background mb-2"><?php esc_html_e( 'Des questions ?', 'ufo-boilerplate' ); ?></h3>
						<p class="mb-4 text-sm text-background"><?php esc_html_e( 'Nous sommes là pour vous accompagner dans vos projets.', 'ufo-boilerplate' ); ?></p>
						<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="inline-flex items-center justify-center gap-2 bg-background hover:bg-slate-200 text-primary btn rounded-md">
							<i data-lucide="message-circle" class="size-4"></i>
							<?php esc_html_e( 'Contactez nous !', 'ufo-boilerplate' ); ?>
						</a>
					</div>
					<div class="w-full my-1 grid grid-cols-2 gap-2 items-center justify-start">
						<div class="h-8 col-span-1 items-center"><span class="text-base font-semibold uppercase"><?php esc_html_e( 'Partager', 'ufo-boilerplate' ); ?></span></div>
						<div class="col-span-1 flex justify-start gap-1">
							<a target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr__( 'Partager sur LinkedIn', 'ufo-boilerplate' ); ?>" class="w-8 h-8 inline-flex items-center justify-center rounded-full mr-2 mt-1 bg-primary hover:bg-primary-800" href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo rawurlencode( get_permalink() ); ?>">
								<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-background" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.605 0 4.276 2.372 4.276 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
							</a>
							<a target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr__( 'Partager sur Facebook', 'ufo-boilerplate' ); ?>" class="w-8 h-8 inline-flex items-center justify-center rounded-full mr-2 mt-1 bg-primary hover:bg-primary-800" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( get_permalink() ); ?>">
								<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5 text-background" aria-hidden="true"><path d="M9.101 23.691v-7.98H6.627v-3.667h2.474v-1.58c0-4.085 1.848-5.978 5.858-5.978.401 0 .955.042 1.468.103a8.68 8.68 0 0 1 1.141.195v3.325a8.623 8.623 0 0 0-.653-.036 26.805 26.805 0 0 0-.733-.009c-.707 0-1.259.096-1.675.309a1.686 1.686 0 0 0-.679.622c-.258.42-.374.995-.374 1.752v1.297h3.919l-.386 2.103-.287 1.564h-3.246v8.245C19.396 23.238 24 18.179 24 12.044c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.628 3.874 10.35 9.101 11.647Z"/></svg>
							</a>
						</div>
					</div>
				</div>
			</div>

			<!-- Contenu principal -->
			<div class="prose prose-ufo-boilerplate max-w-none lg:col-span-2 pt-8">
				<?php
				// Ajouter un ID d'ancre à chaque <h2> pour la table des matières.
				// L'ID est dérivé du texte du titre (même logique que la TOC
				// ci-dessus) puis injecté via WP_HTML_Tag_Processor : aucun
				// fragment du contenu de l'article n'est réémis sans échappement
				// (protection XSS). the_content n'est appliqué qu'une seule fois
				// en aval : pas de double filtrage.
				$_ufo_content = get_the_content();

				if ( class_exists( 'WP_HTML_Tag_Processor' ) ) {
					preg_match_all( '/<h2.*?>(.*?)<\/h2>/i', $_ufo_content, $_ufo_h2_matches );
					$_ufo_processor = new WP_HTML_Tag_Processor( $_ufo_content );
					$_ufo_h2_index  = 0;
					while ( $_ufo_processor->next_tag( 'h2' ) ) {
						// Retirer tout gestionnaire d'événement inline glissé dans le titre.
						foreach ( (array) $_ufo_processor->get_attribute_names_with_prefix( 'on' ) as $_ufo_handler ) {
							$_ufo_processor->remove_attribute( $_ufo_handler );
						}
						if ( isset( $_ufo_h2_matches[1][ $_ufo_h2_index ] ) && null === $_ufo_processor->get_attribute( 'id' ) ) {
							$_ufo_processor->set_attribute( 'id', sanitize_title( $_ufo_h2_matches[1][ $_ufo_h2_index ] ) );
						}
						++$_ufo_h2_index;
					}
					$_ufo_content = $_ufo_processor->get_updated_html();
				}

				// Core hook; content already escaped by the_content filters.
				echo apply_filters( 'the_content', $_ufo_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound

				wp_link_pages(
					array(
						'before'           => '<nav class="mx-auto items-center" aria-label="' . esc_attr__( 'Pages de l\'article', 'ufo-boilerplate' ) . '"><span class="page-links-title">' . __( 'Pages:', 'ufo-boilerplate' ) . '</span>',
						'after'            => '</nav>',
						'link_before'      => '<span class="page-number">',
						'link_after'       => '</span>',
						'next_or_number'   => 'number',
						'separator'        => ' ',
						'nextpagelink'     => __( 'Page suivante', 'ufo-boilerplate' ),
						'previouspagelink' => __( 'Page précédente', 'ufo-boilerplate' ),
						'pagelink'         => '%',
						'echo'             => 1,
					)
				);
				?>
			</div>
		</div>
	</div>

	<footer class="entry-footer max-w-content mx-auto">
		<!-- Footer content -->
	</footer><!-- .entry-footer -->

</article><!-- #post-${ID} -->
