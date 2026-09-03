<?php
/**
 * Template part for displaying posts
 *
 * @package Ufo_Boilerplate
 */

// Récupérer la catégorie principale
$categories = get_the_category();
$category = !empty($categories) ? $categories[0] : null;
$category_slug = $category ? $category->slug : '';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('relative isolate flex flex-col justify-end overflow-hidden rounded-2xl bg-slate-900 px-8 pb-8 min-h-[480px]'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <?php
        // wp_get_attachment_image ajoute width/height/srcset/sizes → pas de CLS.
        // Cartes sous la ligne de flottaison : lazy-loading conservé.
        the_post_thumbnail(
            'medium_large',
            array(
                'class'   => 'not-prose absolute inset-0 -z-10 w-full h-full object-cover',
                'alt'     => wp_strip_all_tags(get_the_title()),
                'loading' => 'lazy',
                'sizes'   => '(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw',
            )
        );
        ?>
    <?php endif; ?>
    <div class="absolute inset-0 -z-10 bg-linear-to-t from-slate-900 via-slate-900/50"></div>
    <div class="absolute inset-0 -z-10 rounded-2xl ring-1 ring-inset ring-slate-900/10"></div>

    <div class="flex flex-wrap items-center gap-y-1 overflow-hidden text-sm/6 text-slate-100">
        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>" class="mr-8">
            <?php echo esc_html(get_the_date()); ?>
        </time>
        <?php if ($category) : ?>
            <div class="-ml-4 flex items-center gap-x-4">
                <svg viewBox="0 0 2 2" class="-ml-0.5 size-0.5 flex-none fill-white/50" aria-hidden="true"><circle r="1" cx="1" cy="1" /></svg>
                <span><?php echo esc_html($category->name); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <h2 class="not-prose mt-3 h-12 line-clamp-2 text-lg/6 font-chillax font-semibold text-slate-100">
        <?php the_title(); ?>
    </h2>
    <p class="not-prose mt-2 line-clamp-2 text-sm/6 text-slate-100">
        <?php echo esc_html(_ufo_truncate_text(get_the_excerpt(), 100)); ?>
    </p>
    <a href="<?php the_permalink(); ?>" class="not-prose mt-4 inline-flex w-fit items-center is-style-primary btn">
        <span class="absolute inset-0" aria-hidden="true"></span>
        <?php esc_html_e('Lire l\'article', 'ufo-boilerplate'); ?>
    </a>
</article>
