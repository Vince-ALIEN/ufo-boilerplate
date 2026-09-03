<?php
/**
 * Template part for displaying the front page hero
 *
 * Texts, CTAs and the background image are managed from
 * Customize → Ufo options → Front Page Hero.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Ufo_Boilerplate
 */

$_ufo_background     = _Ufo_Front_Page_Hero::background_image_html( 'absolute inset-0 -z-10 w-full h-full object-cover opacity-20' );
$_ufo_badge_text     = _Ufo_Front_Page_Hero::get( 'ufo_hero_badge_text' );
$_ufo_badge_link_url = _Ufo_Front_Page_Hero::get( 'ufo_hero_badge_link_url' );

?>

<div class="relative isolate overflow-hidden mb-8">
	<?php echo $_ufo_background; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

	<div aria-hidden="true" class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-xl sm:-top-80">
      <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%-11rem)] aspect-1155/678 w-144.5 -translate-x-1/2 rotate-30 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%-30rem)] sm:w-288.75"></div>
    </div>

	<div class="mx-auto max-w-7xl h-screen px-6 lg:px-8">
		<div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
			<?php if ( $_ufo_badge_text ) : ?>
				<div class="sm:mb-8 flex justify-center">
					<div class="relative rounded-full px-3 py-1 text-sm/6 text-foreground/70 ring-1 ring-foreground/10 hover:ring-foreground/20">
						<?php echo esc_html( $_ufo_badge_text ); ?>
						<?php if ( $_ufo_badge_link_url ) : ?>
							<a href="<?php echo esc_url( $_ufo_badge_link_url ); ?>" class="font-semibold text-primary ml-2">
								<span aria-hidden="true" class="absolute inset-0"></span>
								<?php echo esc_html( _Ufo_Front_Page_Hero::get( 'ufo_hero_badge_link_text' ) ); ?> <span aria-hidden="true">&rarr;</span>
							</a>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>

			<div class="text-center">
				<h1 class="text-5xl font-semibold tracking-tight text-balance text-foreground sm:text-7xl"><?php echo esc_html( _Ufo_Front_Page_Hero::get( 'ufo_hero_heading' ) ); ?></h1>
				<p class="mt-8 text-lg font-medium text-pretty text-foreground/70 sm:text-xl/8"><?php echo esc_html( _Ufo_Front_Page_Hero::get( 'ufo_hero_description' ) ); ?></p>
				<div class="mt-10 flex items-center justify-center gap-x-6">
					<a href="<?php echo esc_url( _Ufo_Front_Page_Hero::get( 'ufo_hero_cta_primary_url' ) ); ?>" class="rounded-md bg-primary px-3.5 py-2.5 text-sm font-semibold text-background shadow-xs hover:bg-barossa-800 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"><?php echo esc_html( _Ufo_Front_Page_Hero::get( 'ufo_hero_cta_primary_text' ) ); ?></a>
					<a href="<?php echo esc_url( _Ufo_Front_Page_Hero::get( 'ufo_hero_cta_secondary_url' ) ); ?>" class="text-sm/6 font-semibold text-foreground"><?php echo esc_html( _Ufo_Front_Page_Hero::get( 'ufo_hero_cta_secondary_text' ) ); ?> <span aria-hidden="true">→</span></a>
				</div>
			</div>
		</div>
	</div>

	<div aria-hidden="true" class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-xl sm:top-[calc(100%-30rem)]">
      <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" class="relative left-[calc(50%+3rem)] aspect-1155/678 w-144.5 -translate-x-1/2 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-20 sm:left-[calc(50%+36rem)] sm:w-288.75"></div>
    </div>
</div>
