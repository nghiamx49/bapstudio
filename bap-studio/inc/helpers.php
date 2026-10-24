<?php
/**
 * Small helpers shared by templates.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

/**
 * Read a Studio settings value (ACF options page), with a fallback when
 * ACF Pro is not active or the field is empty.
 */
function bap_option( string $name, $fallback = '' ) {
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $name, 'option' );
		if ( null !== $value && '' !== $value && false !== $value && array() !== $value ) {
			return $value;
		}
	}
	return $fallback;
}

/** Read an ACF field on a post, with a fallback. */
function bap_field( string $name, $post_id = null, $fallback = '' ) {
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $name, $post_id );
		if ( null !== $value && '' !== $value && false !== $value ) {
			return $value;
		}
	}
	return $fallback;
}

/** Studio display name: Studio settings first, then the site title. */
function bap_studio_name(): string {
	return (string) bap_option( 'studio_name', get_bloginfo( 'name' ) );
}

/**
 * Normalise an ACF image/gallery value (ID, array or URL) to an attachment ID.
 *
 * @param mixed $value Raw value.
 */
function bap_attachment_id( $value ): int {
	if ( is_array( $value ) ) {
		return (int) ( $value['ID'] ?? $value['id'] ?? 0 );
	}
	if ( is_numeric( $value ) ) {
		return (int) $value;
	}
	if ( is_string( $value ) && $value ) {
		return (int) attachment_url_to_postid( $value );
	}
	return 0;
}

/** The first album an attachment belongs to, or null. */
function bap_photo_album( int $attachment_id ): ?WP_Term {
	$terms = get_the_terms( $attachment_id, 'bap_album' );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
}

/**
 * Data the image dialog needs for one photo.
 *
 * @return array{full:string,title:string,desc:string,album:string,alt:string}
 */
function bap_photo_data( int $attachment_id ): array {
	$post  = get_post( $attachment_id );
	$album = bap_photo_album( $attachment_id );
	$alt   = (string) get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

	return array(
		'full'  => (string) wp_get_attachment_image_url( $attachment_id, 'bap-dialog' ),
		'title' => $post ? get_the_title( $post ) : '',
		'desc'  => $post ? wpautop( wp_kses_post( $post->post_content ) ) : '',
		'album' => $album ? $album->name : '',
		'alt'   => $alt,
	);
}

/**
 * Print the data-* attributes that make an element open the image dialog.
 */
function bap_dialog_attrs( int $attachment_id ): void {
	$data = bap_photo_data( $attachment_id );
	printf(
		' data-photo data-full="%1$s" data-title="%2$s" data-album="%3$s" data-desc="%4$s"',
		esc_url( $data['full'] ),
		esc_attr( $data['title'] ),
		esc_attr( $data['album'] ),
		esc_attr( $data['desc'] )
	);
}

/** Render a header menu location as bare <li> items inside the existing nav list. */
function bap_render_header_menu( string $location ): void {
	wp_nav_menu(
		array(
			'theme_location' => $location,
			'container'      => false,
			'items_wrap'     => '%3$s',
			'fallback_cb'    => false,
			'depth'          => 1,
		)
	);
}

/** Render one gallery grid tile. */
function bap_render_photo_tile( int $attachment_id ): void {
	get_template_part( 'template-parts/photo-tile', null, array( 'id' => $attachment_id ) );
}

/**
 * Albums for the navigation and tab bars, ordered by the "Order" field.
 *
 * @return WP_Term[]
 */
function bap_albums(): array {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}
	$terms = get_terms(
		array(
			'taxonomy'   => 'bap_album',
			'hide_empty' => false,
		)
	);
	if ( is_wp_error( $terms ) ) {
		return $cache = array();
	}
	// Sort by the album "Order" field; albums without one keep name order at the end.
	usort(
		$terms,
		static function ( $a, $b ) {
			$oa = (int) get_term_meta( $a->term_id, 'bap_order', true ) ?: PHP_INT_MAX;
			$ob = (int) get_term_meta( $b->term_id, 'bap_order', true ) ?: PHP_INT_MAX;
			return $oa <=> $ob ?: strcasecmp( $a->name, $b->name );
		}
	);
	return $cache = $terms;
}

/**
 * Attachment IDs in an album, newest first.
 *
 * @return array{ids:int[],total:int,pages:int}
 */
function bap_album_photos( int $term_id, int $page = 1, int $per_page = 24 ): array {
	$query = new WP_Query(
		array(
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'post_mime_type' => 'image',
			'posts_per_page' => $per_page,
			'paged'          => max( 1, $page ),
			'fields'         => 'ids',
			'no_found_rows'  => false,
			'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery
				array(
					'taxonomy' => 'bap_album',
					'terms'    => $term_id,
				),
			),
		)
	);
	return array(
		'ids'   => array_map( 'intval', $query->posts ),
		'total' => (int) $query->found_posts,
		'pages' => (int) $query->max_num_pages,
	);
}

/**
 * What an album card shows: cover (the "Cover image" field, else the newest photo),
 * up to 3 other photos as thumbnails, the album link and its intro.
 *
 * @return array{cover:int,thumbs:int[],link:string,intro:string}
 */
function bap_album_card_data( WP_Term $term ): array {
	$photos = bap_album_photos( $term->term_id, 1, 4 );
	$cover  = bap_attachment_id( bap_field( 'cover', $term ) );
	if ( ! $cover && $photos['ids'] ) {
		$cover = $photos['ids'][0];
	}
	$link = get_term_link( $term );

	return array(
		'cover'  => $cover,
		'thumbs' => array_slice( array_values( array_diff( $photos['ids'], array( $cover ) ) ), 0, 3 ),
		'link'   => is_wp_error( $link ) ? '' : $link,
		'intro'  => (string) bap_field( 'intro', $term, $term->description ),
	);
}

/** Albums that have at least one photo, in menu order. */
function bap_albums_with_photos(): array {
	return array_values( array_filter( bap_albums(), static fn( WP_Term $term ) => $term->count > 0 ) );
}

/** ID of the first published page using the given page template (e.g. "page-catalogue.php"), or 0. */
function bap_page_id_by_template( string $template ): int {
	$pages = get_pages(
		array(
			'meta_key'   => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
			'meta_value' => $template, // phpcs:ignore WordPress.DB.SlowDBQuery
			'number'     => 1,
		)
	);
	return $pages ? (int) $pages[0]->ID : 0;
}

/** Permalink of the contact page, or '' when there is none. */
function bap_contact_page_url(): string {
	$contact_page = bap_page_id_by_template( 'page-contact.php' );
	return $contact_page ? (string) get_permalink( $contact_page ) : '';
}

/**
 * Where every "Đặt lịch" button points: the contact page. Without one, the contact
 * section on this page when it has one, else the home page.
 */
function bap_contact_url(): string {
	$contact_page = bap_contact_page_url();
	if ( $contact_page ) {
		return $contact_page;
	}
	$has_contact_section = is_page_template( array( 'page-pricing.php', 'page-catalogue.php', 'page-contact.php' ) );
	return $has_contact_section ? '#lien-he' : home_url( '/' );
}

/**
 * "Đặt lịch" link for a pricing package. When it goes to the contact page, the package
 * travels as ?goi=<title> so the contact form can pre-select it.
 */
function bap_package_booking_url( WP_Post $package ): string {
	$url = bap_contact_url();
	return str_starts_with( $url, '#' ) ? $url : add_query_arg( 'goi', rawurlencode( $package->post_title ), $url );
}

/**
 * The home page no longer has a contact section, so links to it ("#lien-he" or
 * "<home>/#lien-he", e.g. from a banner button or a menu) go to bap_contact_url().
 */
function bap_resolve_contact_link( string $url ): string {
	if ( '#lien-he' !== strstr( $url, '#' ) ) {
		return $url;
	}
	$target       = untrailingslashit( explode( '#', $url, 2 )[0] );
	$is_home_link = '' === $target || untrailingslashit( home_url( '/' ) ) === $target;
	return $is_home_link ? bap_contact_url() : $url;
}

/** Pricing packages in display order. */
function bap_packages(): array {
	return get_posts(
		array(
			'post_type'      => 'bap_package',
			'posts_per_page' => -1,
			'orderby'        => array(
				'menu_order' => 'ASC',
				'date'       => 'ASC',
			),
		)
	);
}

/** Format a package price (VND by default) for display. */
function bap_format_price( $price ): string {
	if ( '' === $price || null === $price ) {
		return __( 'Liên hệ', 'bap-studio' );
	}
	if ( is_numeric( $price ) ) {
		return number_format( (float) $price, 0, ',', '.' ) . ' ' . bap_option( 'currency', '₫' );
	}
	return (string) $price;
}

/** Inline SVG icons used in a few places. */
function bap_icon( string $name ): string {
	$paths = array(
		'prev'  => '<path d="M15 5l-7 7 7 7"/>',
		'next'  => '<path d="M9 5l7 7-7 7"/>',
		'close' => '<path d="M6 6l12 12M18 6L6 18"/>',
		'pause' => '<path d="M8 5v14M16 5v14"/>',
		'play'  => '<path d="M7 5l12 7-12 7z"/>',
		'menu'  => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'check' => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
	);
	return '<svg class="icon icon-' . esc_attr( $name ) . '" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false">' . ( $paths[ $name ] ?? '' ) . '</svg>';
}
