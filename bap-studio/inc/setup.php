<?php
/**
 * Theme supports, image sizes, roles and front-end tweaks.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	static function () {
		load_theme_textdomain( 'bap-studio', BAP_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-width' => true, 'flex-height' => true ) );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support( 'responsive-embeds' );

		// Sizes used by the grid, carousel and image dialog.
		add_image_size( 'bap-grid', 600, 0 );
		add_image_size( 'bap-card', 800, 1000, true );
		add_image_size( 'bap-slide', 1600, 900, true );
		add_image_size( 'bap-dialog', 2000, 2000 );

		register_nav_menus(
			array(
				'extra' => __( 'Header: extra links (after albums)', 'bap-studio' ),
			)
		);
	}
);

/** Save resized JPEG/PNG copies as WebP (smaller files, same quality). */
add_filter(
	'image_editor_output_format',
	static function ( $formats ) {
		if ( wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) ) ) {
			$formats['image/jpeg'] = 'image/webp';
			$formats['image/png']  = 'image/webp';
		}
		return $formats;
	}
);

/** Remove WordPress's 2560px "-scaled" cap so the dialog can show large photos. */
add_filter( 'big_image_size_threshold', static fn() => 3000 );

/**
 * "Studio editor" role: manages photos, albums and packages only.
 * Created when the theme is activated.
 */
function bap_add_roles(): void {
	remove_role( 'bap_studio_editor' );
	add_role(
		'bap_studio_editor',
		__( 'Studio editor', 'bap-studio' ),
		array(
			'read'                   => true,
			'upload_files'           => true,
			'edit_posts'             => true,
			'edit_others_posts'      => true,
			'edit_published_posts'   => true,
			'publish_posts'          => true,
			'delete_posts'           => true,
			'delete_published_posts' => true,
			'delete_others_posts'    => true,
			'manage_categories'      => true,
			'bap_manage_studio'      => true,
		)
	);
	$admin = get_role( 'administrator' );
	if ( $admin ) {
		$admin->add_cap( 'bap_manage_studio' );
	}
}
add_action( 'after_switch_theme', 'bap_add_roles' );

/** Body class for pages that start with a full-bleed banner. */
add_filter(
	'body_class',
	static function ( $classes ) {
		if ( is_front_page() ) {
			$classes[] = 'has-banner';
		}
		return $classes;
	}
);

/** Shorter excerpts on album intro text. */
add_filter( 'excerpt_length', static fn() => 30 );
