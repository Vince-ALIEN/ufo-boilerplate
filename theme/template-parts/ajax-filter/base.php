<?php
/**
 * Template part for displaying ajax filter base
 *
 * @package Ufo_Agency_Boilerplate
 */

// Extraire les variables
$attributes = isset($args['attributes']) ? $args['attributes'] : [];
$query = isset($args['query']) ? $args['query'] : null;
$filterlists = isset($args['filterlists']) ? $args['filterlists'] : [];
$plural_post_name = isset($args['plural_post_name']) ? $args['plural_post_name'] : 'articles';
$is_last_page = isset($args['is_last_page']) ? $args['is_last_page'] : true;
$current_page = isset($args['current_page']) ? $args['current_page'] : 1;

// Forcer le multiselect à false pour assurer la cohérence
$attributes['multiselect'] = 'false';
?>

<section class="js-container-async ajax-posts"
    role="region"
    aria-label="<?php _e('Filtrage et affichage des articles', 'ufo-boilerplate'); ?>"
    <?php if (!empty($attributes['id'])) : ?>
        id="ajax-posts-<?php echo esc_attr($attributes['id']); ?>"
        data-id="<?php echo esc_attr($attributes['id']); ?>"
    <?php endif; ?>
    data-post-type="<?php echo esc_attr(implode(',', $attributes['post_type'])); ?>"
    data-post-status="<?php echo esc_attr(implode(',', $attributes['post_status'])); ?>"
    data-quantity="<?php echo esc_attr($attributes['posts_per_page']); ?>"
    data-multiselect="false"
    data-orderby="<?php echo esc_attr($attributes['orderby']); ?>"
    data-order="<?php echo esc_attr($attributes['order']); ?>"
    data-template="<?php echo esc_attr($attributes['template'] ?? 'template-parts/content/content'); ?>"
>
    <div class="ajax-posts__status sr-only" aria-live="polite" aria-atomic="true"></div>

    <div class="ajax-posts__view grid grid-cols-1 md:grid-cols-12 gap-6">
        <!-- Filtres -->
        <aside class="ajax-posts__filters md:col-span-12">
            <?php if (!empty($filterlists)) : ?>
                <?php get_template_part('template-parts/ajax-filter/filters', null, ['filterlists' => $filterlists]); ?>
            <?php endif; ?>
        </aside>

        <!-- Posts -->
        <div class="ajax-posts__posts md:col-span-12">
            <?php if ($query && $query->have_posts()) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    $template = $attributes['template'] ?? 'template-parts/content/content';
                    while ($query->have_posts()) :
                        $query->the_post();
                        get_template_part($template);
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>

                <?php if (!$is_last_page) : ?>
                    <div class="ajax-posts__load-more text-center mt-8 my-4">
                        <button class="js-load-more is-style-primary btn cursor-pointer"
                                data-page="<?php echo $current_page + 1; ?>"
                                aria-label="<?php _e('Charger plus d\'articles', 'ufo-boilerplate'); ?>"
                                aria-live="polite">
                            <span class="button-text"><?php _e('Charger plus', 'ufo-boilerplate'); ?></span>
                            <span class="sr-only js-loading-text hidden" aria-hidden="true"><?php _e('Chargement en cours...', 'ufo-boilerplate'); ?></span>
                        </button>
                    </div>
                <?php endif; ?>

            <?php else : ?>
                <div class="ajax-posts-message ajax-posts-message--empty text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" role="presentation">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h4 class="mt-4 text-lg font-chillax font-semibold text-slate-900">
                        <?php printf(__('Aucun %s trouvé', 'ufo-boilerplate'), $plural_post_name); ?>
                    </h4>
                    <p class="mt-2 text-sm text-slate-600">
                        <?php _e('Essayez d\'autres filtres ou', 'ufo-boilerplate'); ?>
                        <button type="button"
                                class="js-reset-filters text-primary hover:text-barossa-800 transition-colors duration-300 underline bg-transparent border-0 cursor-pointer"
                                aria-label="<?php _e('Réinitialiser tous les filtres', 'ufo-boilerplate'); ?>">
                            <?php _e('réinitialisez-les tous', 'ufo-boilerplate'); ?>
                        </button>.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="ajax-posts__spinner fixed inset-0 z-50 items-center justify-center bg-black/20 hidden"
         role="status"
         aria-live="assertive">
        <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary border-t-transparent"></div>
        <span class="sr-only"><?php _e('Chargement des résultats...', 'ufo-boilerplate'); ?></span>
    </div>
</section>
