<?php
/**
 * Ufo Boilerplate functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( '_UFO_VERSION' ) ) {
	/*
	 * Set the theme’s version number.
	 *
	 * This is used primarily for cache busting. If you use `npm run bundle`
	 * to create your production build, the value below will be replaced in the
	 * generated zip file with a timestamp, converted to base 36.
	 */
	define( '_UFO_VERSION', '0.1.0' );
}

if ( ! defined( '_UFO_TYPOGRAPHY_CLASSES' ) ) {
	/*
	 * Set Tailwind Typography classes for the front end, block editor and
	 * classic editor using the constant below.
	 *
	 * For the front end, these classes are added by the `_ufo_content_class`
	 * function. You will see that function used everywhere an `entry-content`
	 * or `page-content` class has been added to a wrapper element.
	 *
	 * For the block editor, these classes are converted to a JavaScript array
	 * and then used by the `./javascript/block-editor.js` file, which adds
	 * them to the appropriate elements in the block editor (and adds them
	 * again when they’re removed.)
	 *
	 * For the classic editor (and anything using TinyMCE, like Advanced Custom
	 * Fields), these classes are added to TinyMCE’s body class when it
	 * initializes.
	 */
	define(
		'_UFO_TYPOGRAPHY_CLASSES',
		'prose prose-neutral max-w-none prose-a:text-primary'
	);
}

if ( ! function_exists( '_ufo_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function _ufo_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Ufo Boilerplate, use a find and replace
		 * to change 'ufo-boilerplate' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'ufo-boilerplate', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in five locations.
		register_nav_menus(
			array(
				'menu-1'         => __( 'Primary', 'ufo-boilerplate' ),
				'menu-2'         => __( 'Footer Menu (Company)', 'ufo-boilerplate' ),
				'menu-3'         => __( 'Footer Menu 2 (Legal)', 'ufo-boilerplate' ),
				'menu-solutions' => __( 'Footer Solutions', 'ufo-boilerplate' ),
				'menu-support'   => __( 'Footer Support', 'ufo-boilerplate' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Let blocks use the wide/full alignment options, matching the
		// `contentSize`/`wideSize` values declared in `theme.json`.
		add_theme_support( 'align-wide' );

		// Remove support for block templates.
		remove_theme_support( 'block-templates' );
	}
endif;
add_action( 'after_setup_theme', '_ufo_setup' );

/**
 * Add excerpt support for pages.
 */
function _ufo_add_page_excerpt_support() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', '_ufo_add_page_excerpt_support' );

/**
 * Register widget areas.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function _ufo_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer', 'ufo-boilerplate' ),
			'id'            => 'sidebar-1',
			'description'   => __( 'Add widgets here to appear in your footer.', 'ufo-boilerplate' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'Menu Widget Area', 'ufo-boilerplate' ),
			'id'            => 'menu_widget_area',
			'description'   => __( 'Add widgets here to appear in the header, next to the CTA button (e.g. the WooCommerce Cart widget).', 'ufo-boilerplate' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', '_ufo_widgets_init' );

/**
 * Add the Tailwind Typography classes to TinyMCE.
 *
 * @param array $settings TinyMCE settings.
 * @return array
 */
function _ufo_tinymce_add_class( $settings ) {
	$settings['body_class'] = _UFO_TYPOGRAPHY_CLASSES;
	return $settings;
}
add_filter( 'tiny_mce_before_init', '_ufo_tinymce_add_class' );

/**
 * Limit the block editor to heading levels supported by Tailwind Typography.
 *
 * @param array  $args Array of arguments for registering a block type.
 * @param string $block_type Block type name including namespace.
 * @return array
 */
function _ufo_modify_heading_levels( $args, $block_type ) {
	if ( 'core/heading' !== $block_type ) {
		return $args;
	}

	// Remove <h1>, <h5> and <h6>.
	$args['attributes']['levelOptions']['default'] = array( 2, 3, 4 );

	return $args;
}
add_filter( 'register_block_type_args', '_ufo_modify_heading_levels', 10, 2 );

/**
 * Enqueue scripts and styles.
 */
function _ufo_scripts() {
	wp_enqueue_style( 'ufo-boilerplate-style', get_stylesheet_uri(), array(), _UFO_VERSION );
	wp_enqueue_script( 'ufo-boilerplate-script', get_template_directory_uri() . '/js/script.min.js', array(), _UFO_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', '_ufo_scripts' );

/**
 * Enqueue the block editor script.
 */
function _ufo_enqueue_block_editor_script() {
	$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if (
		$current_screen &&
		$current_screen->is_block_editor() &&
		'widgets' !== $current_screen->id
	) {
		wp_enqueue_script(
			'ufo-boilerplate-editor',
			get_template_directory_uri() . '/js/block-editor.min.js',
			array(
				'wp-blocks',
				'wp-edit-post',
				'wp-plugins',
				'wp-element',
				'wp-components',
				'wp-data',
				'wp-i18n',
			),
			_UFO_VERSION,
			true
		);
		wp_add_inline_script( 'ufo-boilerplate-editor', "tailwindTypographyClasses = '" . esc_attr( _UFO_TYPOGRAPHY_CLASSES ) . "'.split(' ');", 'before' );
	}
}
add_action( 'enqueue_block_assets', '_ufo_enqueue_block_editor_script' );

/**
 * Enqueue the theme's custom scripts (added on top of the default boilerplate).
 */
require get_template_directory() . '/inc/enqueue-scripts.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Loads custom classes from `template-parts/custom/`.
 */
require get_template_directory() . '/inc/class-loader.php';

/**
 * Header CTA buttons (editable from the block editor's Document sidebar).
 */
require get_template_directory() . '/inc/cta-buttons.php';
