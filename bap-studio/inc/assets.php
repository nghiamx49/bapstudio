<?php
/**
 * Front-end assets: one CSS and one JS bundle built by Vite into dist/.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	static function () {
		$css = BAP_DIR . '/dist/main.css';
		$js  = BAP_DIR . '/dist/main.js';

		if ( file_exists( $css ) ) {
			wp_enqueue_style( 'bap-main', BAP_URI . '/dist/main.css', array(), (string) filemtime( $css ) );
		}
		if ( file_exists( $js ) ) {
			wp_enqueue_script(
				'bap-main',
				BAP_URI . '/dist/main.js',
				array(),
				(string) filemtime( $js ),
				array(
					'in_footer' => true,
					'strategy'  => 'defer',
				)
			);
			wp_localize_script(
				'bap-main',
				'BAP',
				array(
					'restUrl'    => esc_url_raw( rest_url( 'bap/v1/' ) ),
					'contactUrl' => esc_url_raw( bap_contact_page_url() ),
					'i18n'       => array(
						'loading' => __( 'Đang tải…', 'bap-studio' ),
						'pause'   => __( 'Tạm dừng', 'bap-studio' ),
						'play'    => __( 'Tự chạy', 'bap-studio' ),
					),
				)
			);
		}
	}
);

/** Mark the bundle as an ES module. */
add_filter(
	'script_loader_tag',
	static function ( $tag, $handle ) {
		if ( 'bap-main' === $handle ) {
			$tag = str_replace( '<script ', '<script type="module" ', $tag );
		}
		return $tag;
	},
	10,
	2
);

/** Preload the banner image so it paints fast (Largest Contentful Paint). */
add_action(
	'wp_head',
	static function () {
		if ( ! is_front_page() ) {
			return;
		}
		$id = bap_attachment_id( bap_option( 'banner_image' ) );
		if ( ! $id ) {
			return;
		}
		$src    = wp_get_attachment_image_url( $id, 'bap-dialog' );
		$srcset = wp_get_attachment_image_srcset( $id, 'bap-dialog' );
		if ( $src ) {
			printf(
				'<link rel="preload" as="image" href="%s"%s fetchpriority="high">' . "\n",
				esc_url( $src ),
				$srcset ? ' imagesrcset="' . esc_attr( $srcset ) . '" imagesizes="100vw"' : ''
			);
		}
	},
	2
);
