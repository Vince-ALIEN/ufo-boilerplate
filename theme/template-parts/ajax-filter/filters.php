<?php
/**
 * Template part for displaying ajax filter filters
 *
 * @package Ufo_Boilerplate
 */

// S'assurer que la liste des filtres transmise au template existe.
$_ufo_filterlists = isset( $args['filterlists'] ) ? $args['filterlists'] : array();

if ( ! empty( $_ufo_filterlists ) ) :
	foreach ( $_ufo_filterlists as $_ufo_filterlist ) : ?>
	<!-- Version desktop des filtres -->
	<div class="ajax-posts__filterlist mb-6 hidden md:block">
		<p class="text-primary font-sans text-lg font-semibold mb-3">
			<?php echo esc_html( $_ufo_filterlist['name'] ); ?>
		</p>

		<ul class="ajax-post__filter-category flex flex-wrap gap-2 <?php echo esc_attr( $_ufo_filterlist['id'] ); ?>"
			aria-label="<?php /* translators: %s: nom de la liste de filtres. */ echo esc_attr( sprintf( __( 'Filtrer par %s', 'ufo-boilerplate' ), $_ufo_filterlist['name'] ) ); ?>">
			<!-- Bouton "Toutes les catégories" -->
			<li>
				<button type="button"
						class="ajax-posts__filter inline-block text-sm font-semibold bg-primary-200 hover:bg-primary hover:text-slate-100 transition-colors duration-300 px-3 py-1 rounded-full js-show-all-filters border-0 cursor-pointer"
						aria-pressed="true"
						data-filter="<?php echo esc_attr( $_ufo_filterlist['taxonomy'] ); ?>"
						data-term="all">
					<?php echo esc_html( $_ufo_filterlist['all_items'] ); ?>
				</button>
			</li>

			<?php foreach ( $_ufo_filterlist['filters'] as $_ufo_filter ) : ?>
				<li>
					<button type="button"
							class="ajax-posts__filter inline-block text-sm bg-primary-200 hover:bg-primary hover:text-slate-100 transition-colors duration-300 px-3 py-1 rounded-full border-0 cursor-pointer"
							aria-pressed="false"
							aria-label="<?php /* translators: 1: nom de la liste de filtres, 2: nom du terme. */ echo esc_attr( sprintf( __( 'Filtrer par %1$s : %2$s', 'ufo-boilerplate' ), $_ufo_filterlist['name'], $_ufo_filter->name ) ); ?>"
							data-filter="<?php echo esc_attr( $_ufo_filter->taxonomy ); ?>"
							data-term="<?php echo esc_attr( $_ufo_filter->slug ); ?>">
						<?php echo esc_html( $_ufo_filter->name ); ?>
						<span class="p-1  rounded-full text-sm ml-1 group-hover:text-background" aria-hidden="true">
							<?php echo esc_html( $_ufo_filter->count ); ?>
						</span>
					</button>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>

	<!-- Version mobile des filtres -->
	<div class="ajax-posts__filterlist-mobile mb-6 md:hidden">
		<button type="button"
				class="js-toggle-filters w-full flex items-center justify-between bg-neutral-100 p-3 rounded-lg"
				aria-expanded="false"
				aria-controls="mobile-filters-<?php echo esc_attr( $_ufo_filterlist['id'] ); ?>"
				aria-label="<?php /* translators: %s: nom de la liste de filtres. */ echo esc_attr( sprintf( __( 'Afficher/Masquer les filtres %s', 'ufo-boilerplate' ), $_ufo_filterlist['name'] ) ); ?>">
			<span class="font-primary text-lg font-semibold">
				<?php echo esc_html( $_ufo_filterlist['name'] ); ?>
			</span>
			<svg xmlns="https://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
				<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
			</svg>
		</button>

		<div class="ajax-posts__mobile-menu hidden mt-2 p-3 bg-white rounded-lg shadow-md"
			id="mobile-filters-<?php echo esc_attr( $_ufo_filterlist['id'] ); ?>">
			<ul class="space-y-2"
				aria-label="<?php /* translators: %s: nom de la liste de filtres. */ echo esc_attr( sprintf( __( 'Filtrer par %s', 'ufo-boilerplate' ), $_ufo_filterlist['name'] ) ); ?>">
				<li>
					<button type="button"
							class="ajax-posts__filter block w-full text-left text-sm font-semibold bg-neutral-100 hover:bg-primary hover:text-background transition-colors duration-300 px-3 py-2 rounded-md js-show-all-filters border-0 cursor-pointer"
							aria-pressed="true"
							data-filter="<?php echo esc_attr( $_ufo_filterlist['taxonomy'] ); ?>"
							data-term="all">
						<?php echo esc_html( $_ufo_filterlist['all_items'] ); ?>
					</button>
				</li>

				<?php foreach ( $_ufo_filterlist['filters'] as $_ufo_filter ) : ?>
					<li>
						<button type="button"
								class="ajax-posts__filter block w-full text-left text-sm bg-neutral-100 hover:bg-primary hover:text-background transition-colors duration-300 px-3 py-2 rounded-md border-0 cursor-pointer"
								aria-pressed="false"
								aria-label="<?php /* translators: 1: nom de la liste de filtres, 2: nom du terme. */ echo esc_attr( sprintf( __( 'Filtrer par %1$s : %2$s', 'ufo-boilerplate' ), $_ufo_filterlist['name'], $_ufo_filter->name ) ); ?>"
								data-filter="<?php echo esc_attr( $_ufo_filter->taxonomy ); ?>"
								data-term="<?php echo esc_attr( $_ufo_filter->slug ); ?>">
							<?php echo esc_html( $_ufo_filter->name ); ?>
							<span class="text-slate-600 text-xs ml-1 active:text-neutral-100" aria-hidden="true">
								(<?php echo esc_html( $_ufo_filter->count ); ?>)
							</span>
						</button>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php endforeach;
endif; ?>
