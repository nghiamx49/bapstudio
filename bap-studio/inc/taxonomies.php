<?php
/**
 * "Album" taxonomy on Media Library images (Cưới, Sự kiện, Prom, ...).
 *
 * Each album gets its own page at /album/<slug>/ and appears in the header
 * navigation automatically.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'init',
	static function () {
		register_taxonomy(
			'bap_album',
			'attachment',
			array(
				'labels'                => array(
					'name'          => __( 'Albums', 'bap-studio' ),
					'singular_name' => __( 'Album', 'bap-studio' ),
					'menu_name'     => __( 'Albums', 'bap-studio' ),
					'all_items'     => __( 'All albums', 'bap-studio' ),
					'edit_item'     => __( 'Edit album', 'bap-studio' ),
					'add_new_item'  => __( 'Add new album', 'bap-studio' ),
					'new_item_name' => __( 'New album name', 'bap-studio' ),
					'search_items'  => __( 'Search albums', 'bap-studio' ),
					'not_found'     => __( 'No albums yet', 'bap-studio' ),
				),
				'public'                => true,
				'hierarchical'          => true, // Checkbox UI on each image.
				'show_ui'               => true,
				'show_admin_column'     => true,
				'show_in_rest'          => true,
				'show_in_nav_menus'     => true,
				'query_var'             => true,
				'rewrite'               => array( 'slug' => 'album', 'with_front' => false ),
				// Attachments have the "inherit" status, which the default counter ignores.
				'update_count_callback' => '_update_generic_term_count',
				'capabilities'          => array(
					'manage_terms' => 'upload_files',
					'edit_terms'   => 'upload_files',
					'delete_terms' => 'upload_files',
					'assign_terms' => 'upload_files',
				),
			)
		);
	}
);

/** Album archive queries need attachments, which have the "inherit" status. */
add_action(
	'pre_get_posts',
	static function ( WP_Query $query ) {
		if ( is_admin() || ! $query->is_main_query() || ! $query->is_tax( 'bap_album' ) ) {
			return;
		}
		$query->set( 'post_type', 'attachment' );
		$query->set( 'post_status', 'inherit' );
		$query->set( 'post_mime_type', 'image' );
		$query->set( 'posts_per_page', 24 );
	}
);

/** Flush rewrite rules once after the theme is activated so /album/ URLs work. */
add_action( 'after_switch_theme', static fn() => update_option( 'bap_flush_rewrites', 1 ) );
add_action(
	'init',
	static function () {
		if ( get_option( 'bap_flush_rewrites' ) ) {
			flush_rewrite_rules();
			delete_option( 'bap_flush_rewrites' );
		}
	},
	99
);
