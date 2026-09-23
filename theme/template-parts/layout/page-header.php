<?php
/**
 * Template part for displaying the title + breadcrumb header of listing
 * and utility pages (archives, search results, 404)
 *
 * Same markup as the blog page header in index.php, so every page that
 * isn't a single post/page opens the same way.
 *
 * @package Ufo_Boilerplate
 *
 * @param array $args {
 *     @type string $title       Title HTML, already escaped by the caller.
 *     @type string $description Optional intro line, already escaped by the caller.
 *     @type bool   $breadcrumb  Whether to show the breadcrumb. Default true.
 *     @type string $class       Extra classes for the header (e.g. `text-center`).
 * }
 */

$_ufo_title       = $args['title'] ?? '';
$_ufo_description = $args['description'] ?? '';
$_ufo_breadcrumb  = $args['breadcrumb'] ?? true;
$_ufo_class       = $args['class'] ?? '';

?>

<header class="<?php echo esc_attr( trim( 'page-header relative ' . $_ufo_class ) ); ?>">
	<h1 class="not-prose entry-title mb-2 text-primary">
		<?php echo $_ufo_title; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</h1>

	<?php if ( $_ufo_breadcrumb && function_exists( '_ufo_the_breadcrumb' ) ) : ?>
		<div class="mb-4 font-semibold pt-2">
			<?php _ufo_the_breadcrumb(); ?>
		</div>
	<?php endif; ?>

	<?php if ( $_ufo_description ) : ?>
		<p class="not-prose mb-8 max-w-2xl text-lg/8 text-pretty text-foreground/70">
			<?php echo $_ufo_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</p>
	<?php endif; ?>
</header><!-- .page-header -->
