<?php
/**
 * Media Library helpers: album filter and bulk "add to album".
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

/** Album dropdown filter on Media → Library (list view). */
add_action(
	'restrict_manage_posts',
	static function ( $post_type ) {
		if ( 'attachment' !== $post_type ) {
			return;
		}
		$current = isset( $_GET['bap_album'] ) ? sanitize_text_field( wp_unslash( $_GET['bap_album'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification
		echo '<label class="screen-reader-text" for="bap-album-filter">' . esc_html__( 'Filter by album', 'bap-studio' ) . '</label>';
		echo '<select name="bap_album" id="bap-album-filter">';
		echo '<option value="">' . esc_html__( 'All albums', 'bap-studio' ) . '</option>';
		echo '<option value="__none"' . selected( $current, '__none', false ) . '>' . esc_html__( 'No album', 'bap-studio' ) . '</option>';
		foreach ( bap_albums() as $term ) {
			printf( '<option value="%s"%s>%s (%d)</option>', esc_attr( $term->slug ), selected( $current, $term->slug, false ), esc_html( $term->name ), (int) $term->count );
		}
		echo '</select>';
	}
);

/** Support the "No album" filter value. */
add_action(
	'pre_get_posts',
	static function ( WP_Query $query ) {
		if ( ! is_admin() || ! $query->is_main_query() || '__none' !== $query->get( 'bap_album' ) ) {
			return;
		}
		$query->set( 'bap_album', '' );
		$query->set(
			'tax_query',
			array(
				array(
					'taxonomy' => 'bap_album',
					'operator' => 'NOT EXISTS',
				),
			)
		);
	}
);

/** Bulk actions: "Add to album: <name>" for each album. */
add_filter(
	'bulk_actions-upload',
	static function ( $actions ) {
		foreach ( bap_albums() as $term ) {
			/* translators: %s: album name */
			$actions[ 'bap_add_' . $term->term_id ] = sprintf( __( 'Add to album: %s', 'bap-studio' ), $term->name );
		}
		foreach ( bap_albums() as $term ) {
			/* translators: %s: album name */
			$actions[ 'bap_remove_' . $term->term_id ] = sprintf( __( 'Remove from album: %s', 'bap-studio' ), $term->name );
		}
		return $actions;
	}
);

add_filter(
	'handle_bulk_actions-upload',
	static function ( $redirect, $action, $ids ) {
		if ( ! preg_match( '/^bap_(add|remove)_(\d+)$/', $action, $m ) || ! current_user_can( 'upload_files' ) ) {
			return $redirect;
		}
		$term_id = (int) $m[2];
		foreach ( array_map( 'intval', (array) $ids ) as $id ) {
			if ( 'add' === $m[1] ) {
				wp_set_object_terms( $id, $term_id, 'bap_album', true );
			} else {
				wp_remove_object_terms( $id, $term_id, 'bap_album' );
			}
		}
		return add_query_arg( 'bap_album_updated', count( (array) $ids ), $redirect );
	},
	10,
	3
);

add_action(
	'admin_notices',
	static function () {
		if ( empty( $_GET['bap_album_updated'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}
		$n = (int) $_GET['bap_album_updated']; // phpcs:ignore WordPress.Security.NonceVerification
		/* translators: %d: number of photos */
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html( sprintf( _n( '%d photo updated.', '%d photos updated.', $n, 'bap-studio' ), $n ) ) . '</p></div>';
	}
);

/** Explain where the dialog text comes from, on the image edit screen. */
add_filter(
	'attachment_fields_to_edit',
	static function ( $fields ) {
		$fields['bap_hint'] = array(
			'label' => '',
			'input' => 'html',
			'html'  => '<p class="description">' . esc_html__( 'Title and Description are shown next to the photo when visitors open it. Tick an album to place it in the gallery.', 'bap-studio' ) . '</p>',
		);
		return $fields;
	}
);
