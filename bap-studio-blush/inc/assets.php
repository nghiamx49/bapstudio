<?php
/**
 * Swap the parent theme's CSS/JS bundle for this theme's own build.
 *
 * The bundle keeps the parent's "bap-main" handle so the parent's
 * type="module" filter still applies.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	static function () {
		wp_dequeue_style( 'bap-main' );
		wp_deregister_style( 'bap-main' );
		wp_dequeue_script( 'bap-main' );
		wp_deregister_script( 'bap-main' );

		$css = BAP_BLUSH_DIR . '/dist/main.css';
		$js  = BAP_BLUSH_DIR . '/dist/main.js';

		if ( file_exists( $css ) ) {
			wp_enqueue_style( 'bap-main', BAP_BLUSH_URI . '/dist/main.css', array(), (string) filemtime( $css ) );
		}
		if ( file_exists( $js ) ) {
			wp_enqueue_script(
				'bap-main',
				BAP_BLUSH_URI . '/dist/main.js',
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
					'restUrl' => esc_url_raw( rest_url( 'bap/v1/' ) ),
					'i18n'    => array(
						'loading' => __( 'Đang tải…', 'bap-studio-blush' ),
						'pause'   => __( 'Tạm dừng', 'bap-studio-blush' ),
						'play'    => __( 'Tự chạy', 'bap-studio-blush' ),
					),
				)
			);
		}
	},
	20
);
