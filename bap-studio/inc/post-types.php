<?php
/**
 * "Package" post type for pricing packages.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function () {
		register_post_type(
			'bap_package',
			array(
				'labels'        => array(
					'name'               => __( 'Packages', 'bap-studio' ),
					'singular_name'      => __( 'Package', 'bap-studio' ),
					'menu_name'          => __( 'Pricing packages', 'bap-studio' ),
					'all_items'          => __( 'All packages', 'bap-studio' ),
					'add_new_item'       => __( 'Add new package', 'bap-studio' ),
					'edit_item'          => __( 'Edit package', 'bap-studio' ),
					'not_found'          => __( 'No packages yet', 'bap-studio' ),
					'not_found_in_trash' => __( 'No packages in trash', 'bap-studio' ),
				),
				'public'        => false,
				'show_ui'       => true,
				'show_in_rest'  => true,
				'menu_icon'     => 'dashicons-tag',
				'menu_position' => 21,
				// Title + "Order" (page attributes). Price and inclusions are custom fields (inc/acf-fields.php).
				'supports'      => array( 'title', 'page-attributes' ),
				'hierarchical'  => false,
			)
		);
	}
);

/** Show price and order in the packages list. */
add_filter(
	'manage_bap_package_posts_columns',
	static function ( $cols ) {
		$date = $cols['date'] ?? null;
		unset( $cols['date'] );
		$cols['bap_price'] = __( 'Price', 'bap-studio' );
		$cols['bap_order'] = __( 'Order', 'bap-studio' );
		if ( $date ) {
			$cols['date'] = $date;
		}
		return $cols;
	}
);
add_action(
	'manage_bap_package_posts_custom_column',
	static function ( $col, $post_id ) {
		if ( 'bap_price' === $col ) {
			echo esc_html( bap_format_price( bap_field( 'price', $post_id ) ) );
			if ( bap_field( 'highlight', $post_id ) ) {
				echo ' <strong>★</strong>';
			}
		}
		if ( 'bap_order' === $col ) {
			echo (int) get_post_field( 'menu_order', $post_id );
		}
	},
	10,
	2
);

/** Admin list sorted by display order. */
add_action(
	'pre_get_posts',
	static function ( WP_Query $query ) {
		if ( is_admin() && $query->is_main_query() && 'bap_package' === $query->get( 'post_type' ) && ! $query->get( 'orderby' ) ) {
			$query->set( 'orderby', 'menu_order' );
			$query->set( 'order', 'ASC' );
		}
	}
);
