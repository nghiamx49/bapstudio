<?php
/**
 * REST endpoint for "Xem thêm" (load more) on album pages.
 *
 * GET /wp-json/bap/v1/album/<term_id>?page=2  →  { html, page, pages }
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'rest_api_init',
	static function () {
		register_rest_route(
			'bap/v1',
			'/album/(?P<id>\d+)',
			array(
				'methods'             => 'GET',
				'permission_callback' => '__return_true',
				'args'                => array(
					'id'   => array( 'sanitize_callback' => 'absint' ),
					'page' => array(
						'sanitize_callback' => 'absint',
						'default'           => 2,
					),
				),
				'callback'            => static function ( WP_REST_Request $req ) {
					$term = get_term( (int) $req['id'], 'bap_album' );
					if ( ! $term || is_wp_error( $term ) ) {
						return new WP_Error( 'bap_no_album', 'Album not found', array( 'status' => 404 ) );
					}
					$page   = max( 1, (int) $req['page'] );
					$photos = bap_album_photos( $term->term_id, $page );

					ob_start();
					foreach ( $photos['ids'] as $id ) {
						bap_render_photo_tile( $id );
					}
					return array(
						'html'  => ob_get_clean(),
						'page'  => $page,
						'pages' => $photos['pages'],
					);
				},
			)
		);
	}
);
