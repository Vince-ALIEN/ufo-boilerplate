<?php
/**
 * Mega Menu Custom Fields
 *
 * Adds custom fields to the WordPress menu editor for icon selection,
 * descriptions, and the mega menu (featured posts) option.
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( '_ufo_get_lucide_icons' ) ) :
	/**
	 * Get list of available Lucide icons.
	 *
	 * This list must match the icons imported in /javascript/icons.js
	 *
	 * @return array List of icon names.
	 */
	function _ufo_get_lucide_icons() {
		return array(
			''                => __( '-- No Icon --', 'ufo-boilerplate' ),
			'airplay'         => __( 'Airplay', 'ufo-boilerplate' ),
			'archive'         => __( 'Archive', 'ufo-boilerplate' ),
			'arrow-left'      => __( 'Arrow Left', 'ufo-boilerplate' ),
			'arrow-right'     => __( 'Arrow Right', 'ufo-boilerplate' ),
			'bar-chart-3'     => __( 'Bar Chart', 'ufo-boilerplate' ),
			'bell'            => __( 'Bell', 'ufo-boilerplate' ),
			'book-open'       => __( 'Book Open', 'ufo-boilerplate' ),
			'briefcase'       => __( 'Briefcase', 'ufo-boilerplate' ),
			'calendar'        => __( 'Calendar', 'ufo-boilerplate' ),
			'check'           => __( 'Check', 'ufo-boilerplate' ),
			'chess-king'      => __( 'Chess King', 'ufo-boilerplate' ),
			'chevron-down'    => __( 'Chevron Down', 'ufo-boilerplate' ),
			'chevron-left'    => __( 'Chevron Left', 'ufo-boilerplate' ),
			'chevron-right'   => __( 'Chevron Right', 'ufo-boilerplate' ),
			'code'            => __( 'Code', 'ufo-boilerplate' ),
			'command'         => __( 'Command', 'ufo-boilerplate' ),
			'crown'           => __( 'Crown', 'ufo-boilerplate' ),
			'external-link'   => __( 'External Link', 'ufo-boilerplate' ),
			'folder'          => __( 'Folder', 'ufo-boilerplate' ),
			'globe'           => __( 'Globe', 'ufo-boilerplate' ),
			'grid-3x3'        => __( 'Grid', 'ufo-boilerplate' ),
			'key'             => __( 'Key', 'ufo-boilerplate' ),
			'languages'       => __( 'Languages', 'ufo-boilerplate' ),
			'menu'            => __( 'Menu', 'ufo-boilerplate' ),
			'message-circle'  => __( 'Message Circle', 'ufo-boilerplate' ),
			'monitor'         => __( 'Monitor', 'ufo-boilerplate' ),
			'moon'            => __( 'Moon', 'ufo-boilerplate' ),
			'package-open'    => __( 'Package Open', 'ufo-boilerplate' ),
			'phone'           => __( 'Phone', 'ufo-boilerplate' ),
			'play-circle'     => __( 'Play Circle', 'ufo-boilerplate' ),
			'refresh-cw'      => __( 'Refresh', 'ufo-boilerplate' ),
			'rocket'          => __( 'Rocket', 'ufo-boilerplate' ),
			'search'          => __( 'Search', 'ufo-boilerplate' ),
			'send'            => __( 'Send', 'ufo-boilerplate' ),
			'server'          => __( 'Server', 'ufo-boilerplate' ),
			'shield'          => __( 'Shield', 'ufo-boilerplate' ),
			'shopping-cart'   => __( 'Shopping Cart', 'ufo-boilerplate' ),
			'sport-shoe'      => __( 'Sport Shoe', 'ufo-boilerplate' ),
			'sun'             => __( 'Sun', 'ufo-boilerplate' ),
			'sun-moon'        => __( 'Sun Moon', 'ufo-boilerplate' ),
			'user'            => __( 'User', 'ufo-boilerplate' ),
			'user-round-plus' => __( 'User Round Plus', 'ufo-boilerplate' ),
			'x'               => __( 'X (Close)', 'ufo-boilerplate' ),
			'zap'             => __( 'Zap', 'ufo-boilerplate' ),
		);
	}
endif;

if ( ! function_exists( '_ufo_get_post_options' ) ) :
	/**
	 * Get published posts as `[ID => title]` pairs, for the featured-post pickers.
	 *
	 * Capped at 200 most recent posts so the <select> stays usable.
	 *
	 * @return array
	 */
	function _ufo_get_post_options() {
		$posts = get_posts(
			array(
				'post_type'         => 'post',
				'post_status'       => 'publish',
				'orderby'           => 'date',
				'order'             => 'DESC',
				'numberposts'       => 200,
				'suppress_filters'  => false,
			)
		);

		$options = array( '' => __( '-- Aucun article --', 'ufo-boilerplate' ) );
		foreach ( $posts as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}
		return $options;
	}
endif;

if ( ! function_exists( '_ufo_menu_fields_list' ) ) :
	/**
	 * Define custom fields for menu items.
	 *
	 * `type` drives both the admin field markup (see _ufo_menu_custom_fields())
	 * and how the submitted value is sanitized on save (see
	 * _ufo_menu_custom_fields_save()).
	 *
	 * @return array List of custom fields, keyed by field key.
	 */
	function _ufo_menu_fields_list() {
		return array(
			'icon'            => array(
				'label' => __( 'Icon', 'ufo-boilerplate' ),
				'type'  => 'select-icon',
			),
			'description'     => array(
				'label' => __( 'Description', 'ufo-boilerplate' ),
				'type'  => 'text',
			),
			'mega-menu'       => array(
				'label' => __( 'Méga menu (affiche les enfants de cet item en méga menu, avec articles à la une)', 'ufo-boilerplate' ),
				'type'  => 'checkbox',
			),
			'featured-post-1' => array(
				'label' => __( 'Article à la une 1 (méga menu)', 'ufo-boilerplate' ),
				'type'  => 'select-post',
			),
			'featured-post-2' => array(
				'label' => __( 'Article à la une 2 (méga menu)', 'ufo-boilerplate' ),
				'type'  => 'select-post',
			),
		);
	}
endif;

if ( ! function_exists( '_ufo_menu_custom_fields' ) ) :
	/**
	 * Display custom fields in the menu editor.
	 *
	 * @param int   $item_id Menu item ID.
	 * @param array $item    Menu item data.
	 * @param int   $depth   Menu item depth.
	 * @param array $args    Menu item arguments.
	 */
	function _ufo_menu_custom_fields( $item_id, $item, $depth, $args ) {
		$fields = _ufo_menu_fields_list();
		$icons  = _ufo_get_lucide_icons();
		$posts  = null; // Lazily fetched only if a select-post field is actually rendered.

		foreach ( $fields as $field_key => $field ) {
			$key   = sprintf( 'menu-item-%s', $field_key );
			$id    = sprintf( 'edit-%s-%s', $key, $item_id );
			$name  = sprintf( '%s[%s]', $key, $item_id );
			$value = get_post_meta( $item_id, '_' . $key, true );
			$class = sprintf( 'field-%s description description-wide', $key );
			?>
			<p class="<?php echo esc_attr( $class ); ?>">
				<label for="<?php echo esc_attr( $id ); ?>">
					<?php if ( 'checkbox' === $field['type'] ) : ?>
						<input
							type="checkbox"
							id="<?php echo esc_attr( $id ); ?>"
							name="<?php echo esc_attr( $name ); ?>"
							value="1"
							<?php checked( $value, '1' ); ?>
						/>
						<?php echo esc_html( $field['label'] ); ?>
					<?php else : ?>
						<?php echo esc_html( $field['label'] ); ?><br />
						<?php if ( 'select-icon' === $field['type'] ) : ?>
							<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" class="widefat">
								<?php foreach ( $icons as $icon_value => $icon_label ) : ?>
									<option value="<?php echo esc_attr( $icon_value ); ?>" <?php selected( $value, $icon_value ); ?>>
										<?php echo esc_html( $icon_label ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						<?php elseif ( 'select-post' === $field['type'] ) : ?>
							<?php if ( null === $posts ) : $posts = _ufo_get_post_options(); endif; ?>
							<select id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" class="widefat">
								<?php foreach ( $posts as $post_id => $post_title ) : ?>
									<option value="<?php echo esc_attr( $post_id ); ?>" <?php selected( (string) $value, (string) $post_id ); ?>>
										<?php echo esc_html( $post_title ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						<?php else : ?>
							<input
								type="text"
								id="<?php echo esc_attr( $id ); ?>"
								name="<?php echo esc_attr( $name ); ?>"
								class="widefat"
								value="<?php echo esc_attr( $value ); ?>"
							/>
						<?php endif; ?>
					<?php endif; ?>
				</label>
			</p>
			<?php
		}
	}
endif;
add_action( 'wp_nav_menu_item_custom_fields', '_ufo_menu_custom_fields', 10, 4 );

if ( ! function_exists( '_ufo_register_menu_item_meta_rest' ) ) :
	/**
	 * Register menu item meta for REST API access (block-based menu editor).
	 */
	function _ufo_register_menu_item_meta_rest() {
		$fields = _ufo_menu_fields_list();

		foreach ( $fields as $field_key => $field ) {
			register_meta(
				'post',
				'_menu-item-' . $field_key,
				array(
					'object_subtype' => 'nav_menu_item',
					'show_in_rest'   => true,
					'single'         => true,
					'type'           => 'select-post' === $field['type'] ? 'integer' : 'string',
					'auth_callback'  => function () {
						return current_user_can( 'edit_theme_options' );
					},
				)
			);
		}
	}
endif;
add_action( 'init', '_ufo_register_menu_item_meta_rest' );

if ( ! function_exists( '_ufo_menu_custom_fields_save' ) ) :
	/**
	 * Save custom field values.
	 *
	 * @param int   $menu_id         Menu ID.
	 * @param int   $menu_item_db_id Menu item ID.
	 * @param array $args            Menu item arguments.
	 */
	function _ufo_menu_custom_fields_save( $menu_id, $menu_item_db_id, $args ) {
		// Skip during REST API requests — $_POST is empty, which would erase custom meta.
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return;
		}

		// Verify capability and nonce. WordPress core already verifies this same
		// nonce ( 'update-nav_menu' / 'update-nav-menu-nonce' ) before the
		// wp_update_nav_menu_item hook fires — this is defence in depth and also
		// guards programmatic calls where the nonce is absent.
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		if (
			! isset( $_POST['update-nav-menu-nonce'] )
			|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['update-nav-menu-nonce'] ) ), 'update-nav_menu' )
		) {
			return;
		}

		$fields = _ufo_menu_fields_list();

		foreach ( $fields as $field_key => $field ) {
			$key = sprintf( 'menu-item-%s', $field_key );

			if ( 'checkbox' === $field['type'] ) {
				$value = isset( $_POST[ $key ][ $menu_item_db_id ] ) ? '1' : '';
			} elseif ( 'select-post' === $field['type'] ) {
				$value = isset( $_POST[ $key ][ $menu_item_db_id ] ) ? absint( wp_unslash( $_POST[ $key ][ $menu_item_db_id ] ) ) : 0;
				$value = $value ? (string) $value : '';
			} elseif ( isset( $_POST[ $key ][ $menu_item_db_id ] ) ) {
				$value = sanitize_text_field( wp_unslash( $_POST[ $key ][ $menu_item_db_id ] ) );
			} else {
				$value = '';
			}

			update_post_meta( $menu_item_db_id, '_' . $key, $value );
		}
	}
endif;
add_action( 'wp_update_nav_menu_item', '_ufo_menu_custom_fields_save', 10, 3 );

if ( ! function_exists( '_ufo_load_custom_walker' ) ) :
	/**
	 * Load custom walker for menu editor.
	 *
	 * @param string $walker Walker class name.
	 * @return string
	 */
	function _ufo_load_custom_walker( $walker ) {
		return '_Ufo_Walker_Nav_Menu_Edit';
	}
endif;
add_filter( 'wp_edit_nav_menu_walker', '_ufo_load_custom_walker' );
