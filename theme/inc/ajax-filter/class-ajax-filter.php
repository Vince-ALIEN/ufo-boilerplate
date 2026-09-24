<?php
/**
 * Ajax Filter Core Class
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the ajax post filter: rendering the initial list plus the
 * `wp_ajax_process_filter_change` endpoint used to refresh it.
 */
class _Ufo_Ajax_Filter {

	/**
	 * Singleton instance.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Handle used for the enqueued script/style.
	 *
	 * @var string
	 */
	protected $name = 'ajax-filter';

	/**
	 * Asset version, for cache busting.
	 *
	 * @var string
	 */
	protected $version = '1.0.0';

	/**
	 * Default arguments that can be overwritten.
	 *
	 * @var array
	 */
	protected $default_attributes = array(
		'id'             => null,
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 9,
		'order'          => 'DESC',
		'orderby'        => 'date',
		'tax'            => 'category',
		'multiselect'    => 'false',
		'template'       => 'template-parts/content/content',
	);

	/**
	 * Allowed orderby values.
	 *
	 * @var array
	 */
	protected $allowed_orderby_values = array(
		'ID',
		'author',
		'title',
		'name',
		'type',
		'date',
		'modified',
		'parent',
		'rand',
		'comment_count',
		'relevance',
		'menu_order',
	);

	/**
	 * Get instance of the class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->setup_hooks();
	}

	/**
	 * Setup hooks.
	 */
	private function setup_hooks() {
		add_action( 'wp_ajax_process_filter_change', array( $this, 'process_filter_change' ) );
		add_action( 'wp_ajax_nopriv_process_filter_change', array( $this, 'process_filter_change' ) );
	}

	/**
	 * Enqueue assets — called only when init_filter() is used on the current page.
	 */
	private function enqueue_assets() {
		if ( wp_script_is( 'ajax-filter', 'enqueued' ) ) {
			return;
		}

		$script_vars = array(
			'nonce'              => wp_create_nonce( 'filter-posts-nonce' ),
			'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
			'timeoutMessage'     => __( 'Chargement trop long. Veuillez rafraîchir la page.', 'ufo-boilerplate' ),
			'serverErrorMessage' => __( 'Une erreur est survenue. Veuillez réessayer.', 'ufo-boilerplate' ),
		);

		if ( function_exists( 'icl_object_id' ) ) {
			$script_vars['language'] = ICL_LANGUAGE_CODE;
		}

		wp_enqueue_script(
			'ajax-filter',
			get_template_directory_uri() . '/js/ajax-filter.min.js',
			array(),
			$this->version,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		wp_localize_script( 'ajax-filter', 'filterPosts', $script_vars );
	}

	/**
	 * Initialize filter display.
	 *
	 * @param array $args Attributes overriding $default_attributes.
	 */
	public function init_filter( $args = array() ) {
		$this->enqueue_assets();

		$attributes = wp_parse_args( $args, $this->default_attributes );
		$attributes = $this->validate_attributes( $attributes );

		if ( is_wp_error( $attributes ) ) {
			return $attributes->get_error_message();
		}

		// Requête initiale pour tous les articles.
		$query = $this->query_posts(
			array(
				'post_type'      => $attributes['post_type'],
				'post_status'    => $attributes['post_status'],
				'posts_per_page' => $attributes['posts_per_page'],
				'order'          => $attributes['order'],
				'orderby'        => $attributes['orderby'],
				'paged'          => 1,
			),
			$attributes
		);

		$filterlists      = $this->get_filterlist( $attributes['tax'] );
		$plural_post_name = $this->get_post_type_plural_name( $query->query['post_type'] );

		return get_template_part(
			'template-parts/ajax-filter/base',
			null,
			array(
				'attributes'       => $attributes,
				'query'            => $query,
				'filterlists'      => $filterlists,
				'plural_post_name' => $plural_post_name,
				'is_last_page'     => $this->is_last_page( $query ),
				'current_page'     => $this->get_page_number( $query ),
			)
		);
	}

	/**
	 * Process Ajax filter change.
	 */
	public function process_filter_change() {
		check_ajax_referer( 'filter-posts-nonce', 'nonce' );

		$allowed_templates = apply_filters(
			'_ufo_ajax_filter_allowed_templates',
			array(
				'template-parts/content/content',
				'template-parts/content/content-realisation',
			)
		);

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- nonce verified above via check_ajax_referer().
		$params = isset( $_POST['params'] ) && is_array( $_POST['params'] ) ? $this->sanitize_params( $_POST['params'] ) : array();

		$requested_template = ! empty( $params['template'] ) ? sanitize_text_field( $params['template'] ) : 'template-parts/content/content';
		$template           = in_array( $requested_template, $allowed_templates, true ) ? $requested_template : 'template-parts/content/content';

		$order = isset( $params['order'] ) && in_array( $params['order'], array( 'ASC', 'DESC' ), true ) ? $params['order'] : 'DESC';

		$attributes = array(
			'id'          => ! empty( $params['id'] ) ? sanitize_text_field( $params['id'] ) : null,
			'post_type'   => isset( $params['postType'] ) ? sanitize_text_field( $params['postType'] ) : '',
			'post_status' => isset( $params['postStatus'] ) ? sanitize_text_field( $params['postStatus'] ) : '',
			'tax'         => isset( $params['tax'] ) && is_array( $params['tax'] ) ? $this->get_tax_query_vars( $params['tax'] ) : array(),
			'page'        => isset( $params['page'] ) ? intval( $params['page'] ) : 1,
			'orderby'     => isset( $params['orderby'] ) ? sanitize_text_field( $params['orderby'] ) : 'date',
			'order'       => $order,
			'quantity'    => $this->clamp_quantity( isset( $params['quantity'] ) ? intval( $params['quantity'] ) : 9 ),
			'language'    => isset( $params['language'] ) ? sanitize_text_field( $params['language'] ) : '',
			'template'    => $template,
		);

		$attributes = $this->validate_attributes( $attributes );

		if ( is_wp_error( $attributes ) ) {
			wp_send_json_error( $attributes->get_error_message() );
		}

		$query_args = array(
			'paged'          => $attributes['page'],
			'post_type'      => $attributes['post_type'],
			'post_status'    => $attributes['post_status'],
			'posts_per_page' => $attributes['quantity'],
			'orderby'        => $attributes['orderby'],
			'order'          => $attributes['order'],
		);

		// Ajouter la tax_query uniquement si des filtres sont actifs.
		if ( ! empty( $attributes['tax'] ) ) {
			$query_args['tax_query'] = $attributes['tax']; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
		}

		$query = $this->query_posts( $query_args, $attributes );

		ob_start();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				get_template_part( $attributes['template'] );
			}
		} else {
			get_template_part( 'template-parts/content/content', 'none' );
		}
		$content = ob_get_clean();
		wp_reset_postdata();

		$response = array(
			'content'    => $content,
			'found'      => $query->found_posts,
			'has_more'   => ! $this->is_last_page( $query ),
			'next_page'  => $this->get_page_number( $query ) + 1,
			'query_args' => $query_args,
		);

		wp_send_json_success( $response );
	}

	/**
	 * Deep-sanitize the client-supplied params array.
	 *
	 * Recursively applies sanitize_text_field() to every scalar value,
	 * ensuring nested arrays (such as the tax map) are fully cleaned.
	 *
	 * @param mixed $value Value to sanitize.
	 * @return mixed
	 */
	protected function sanitize_params( $value ) {
		if ( is_array( $value ) ) {
			$sanitized = array();
			foreach ( $value as $key => $item ) {
				$sanitized[ sanitize_text_field( (string) $key ) ] = $this->sanitize_params( $item );
			}
			return $sanitized;
		}

		if ( is_string( $value ) ) {
			return sanitize_text_field( wp_unslash( $value ) );
		}

		if ( is_int( $value ) || is_float( $value ) || is_bool( $value ) ) {
			return $value;
		}

		return '';
	}

	/**
	 * Clamp the client-supplied page size.
	 *
	 * The endpoint is public, so an unbounded value (or -1, "all posts")
	 * would let any visitor load every post in a single request. The
	 * ceiling never drops below the site's own "posts per page" setting.
	 *
	 * @param int $quantity Requested page size.
	 * @return int
	 */
	protected function clamp_quantity( $quantity ) {
		$max = (int) apply_filters(
			'_ufo_ajax_filter_max_quantity',
			max( 24, (int) get_option( 'posts_per_page' ) )
		);

		return min( max( 1, $quantity ), max( 1, $max ) );
	}

	/**
	 * Validate attributes.
	 *
	 * @param array $attributes Attributes to validate.
	 * @return array|WP_Error
	 */
	protected function validate_attributes( $attributes ) {
		// Convert string to array for post_type and post_status.
		$attributes['post_type']   = $this->delimited_to_array( $attributes['post_type'] );
		$attributes['post_status'] = $this->delimited_to_array( $attributes['post_status'] );

		// Validate post types and status.
		$is_post_type_viewable = apply_filters(
			'_ufo_ajax_filter_is_post_type_viewable',
			$this->is_every_post_type_viewable( $attributes['post_type'] ),
			$attributes
		);

		$is_post_status_viewable = apply_filters(
			'_ufo_ajax_filter_is_post_status_viewable',
			$this->is_every_post_status_viewable( $attributes['post_status'] ),
			$attributes
		);

		if ( ! $is_post_type_viewable || ! $is_post_status_viewable ) {
			return new WP_Error( 'posts_not_viewable', __( 'Les articles demandés n\'existent pas ou ne sont pas visibles.', 'ufo-boilerplate' ) );
		}

		if ( ! in_array( $attributes['orderby'], $this->allowed_orderby_values, true ) ) {
			return new WP_Error( 'invalid_orderby', __( 'La méthode de tri spécifiée n\'est pas valide.', 'ufo-boilerplate' ) );
		}

		return $attributes;
	}

	/**
	 * Convert comma delimited string to array.
	 *
	 * @param string|array $attribute Value to normalize.
	 */
	protected function delimited_to_array( $attribute ) {
		if ( ! is_string( $attribute ) ) {
			return $attribute;
		}
		return array_map( 'trim', explode( ',', $attribute ) );
	}

	/**
	 * Check if all post types are viewable.
	 *
	 * @param array $post_type Post types to check.
	 */
	protected function is_every_post_type_viewable( $post_type ) {
		foreach ( $post_type as $type ) {
			if ( ! is_post_type_viewable( $type ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Check if all post status are viewable.
	 *
	 * @param array $post_status Post statuses to check.
	 */
	protected function is_every_post_status_viewable( $post_status ) {
		foreach ( $post_status as $status ) {
			if ( ! is_post_status_viewable( $status ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Get filter list.
	 *
	 * @param string|array $taxonomies Taxonomies to build the filter list from.
	 */
	protected function get_filterlist( $taxonomies ) {
		$filterlists = $this->delimited_to_array( $taxonomies );
		$filterlists = array_filter( $filterlists, 'taxonomy_exists' );
		return $this->get_termlist( $filterlists );
	}

	/**
	 * Get term list.
	 *
	 * @param array $taxonomies Taxonomies to fetch terms for.
	 */
	protected function get_termlist( $taxonomies ) {
		$list = array();

		foreach ( $taxonomies as $taxonomy ) {
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => true,
				)
			);

			if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
				$taxonomy_data = get_taxonomy( $taxonomy );
				$list[]        = array(
					'name'      => $taxonomy_data->labels->singular_name,
					'all_items' => $taxonomy_data->labels->all_items,
					'taxonomy'  => $taxonomy,
					'id'        => 'taxonomy-' . str_replace( '_', '-', $taxonomy_data->name ),
					'filters'   => $terms,
				);
			}
		}

		return $list;
	}

	/**
	 * Get tax query vars.
	 *
	 * @param array $taxonomies Taxonomy => terms map, as sent by the client.
	 */
	protected function get_tax_query_vars( $taxonomies ) {
		$tax_query = array();

		foreach ( $taxonomies as $taxonomy => $terms ) {
			$taxonomy = sanitize_text_field( $taxonomy );
			if ( taxonomy_exists( $taxonomy ) && is_array( $terms ) ) {
				$valid_terms = $this->get_valid_terms( $terms, $taxonomy );
				if ( $valid_terms ) {
					$tax_query[] = array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $valid_terms,
						'operator' => 'IN',
					);
				}
			}
		}

		if ( count( $tax_query ) > 1 ) {
			$tax_query['relation'] = 'OR';
		}

		return $tax_query;
	}

	/**
	 * Get valid terms.
	 *
	 * @param array  $terms Terms to validate.
	 * @param string $tax   Taxonomy the terms belong to.
	 */
	protected function get_valid_terms( $terms, $tax ) {
		$valid_terms = array();

		foreach ( $terms as $term ) {
			$term = sanitize_text_field( $term );
			if ( term_exists( $term, $tax ) ) {
				$valid_terms[] = $term;
			}
		}

		return $valid_terms;
	}

	/**
	 * Query posts.
	 *
	 * @param array $args       Base WP_Query arguments.
	 * @param array $attributes Validated filter attributes.
	 */
	protected function query_posts( $args, $attributes ) {
		$query_args = apply_filters( '_ufo_ajax_filter_query_args', $args, $attributes );
		return new WP_Query( $query_args );
	}

	/**
	 * Get page number.
	 *
	 * @param WP_Query $query Query to read the page number from.
	 */
	protected function get_page_number( $query ) {
		$query_page = $query->get( 'paged' );
		return 0 === $query_page ? 1 : $query_page;
	}

	/**
	 * Check if is last page.
	 *
	 * @param WP_Query $query Query to check.
	 */
	protected function is_last_page( $query ) {
		return $this->get_page_number( $query ) >= $query->max_num_pages;
	}

	/**
	 * Get post type plural name.
	 *
	 * @param string|array $post_type Post type (or array of post types).
	 */
	protected function get_post_type_plural_name( $post_type ) {
		$post_type        = is_array( $post_type ) ? $post_type[0] : $post_type;
		$post_type_object = get_post_type_object( $post_type );
		return $post_type_object
			? strtolower( $post_type_object->labels->name )
			: __( 'articles', 'ufo-boilerplate' );
	}
}
