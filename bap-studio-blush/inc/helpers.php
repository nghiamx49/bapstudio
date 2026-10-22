<?php
/**
 * Template helpers for the Blush look. Data helpers (bap_option, bap_albums,
 * bap_packages, bap_dialog_attrs, ...) come from the parent theme.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

/**
 * Escape a headline and turn *words* into italic emphasis, line breaks into <br>.
 * Same convention as the banner title in Studio settings.
 */
function bap_blush_title( string $text ): string {
	return nl2br( preg_replace( '/\*(.+?)\*/u', '<em>$1</em>', esc_html( $text ) ) );
}

/** Small tracked caps label, e.g. "01 / THƯ VIỆN ẢNH". */
function bap_blush_kicker( string $label, int $num = 0, string $class = '' ): void {
	printf(
		'<p class="kicker%1$s">%2$s%3$s</p>',
		$class ? ' ' . esc_attr( $class ) : '',
		$num ? '<span class="kicker-num">' . esc_html( sprintf( '%02d', $num ) ) . ' /</span> ' : '',
		esc_html( $label )
	);
}

/** Arrow that trails every action link. */
function bap_blush_arrow( string $dir = 'up' ): string {
	return '<span class="arrow" aria-hidden="true">' . ( 'down' === $dir ? '↓' : '↗' ) . '</span>';
}

/** Short monogram for the round logo mark when no custom logo is set. */
function bap_blush_monogram(): string {
	$name  = trim( bap_studio_name() );
	$words = preg_split( '/\s+/u', $name ) ?: array();
	$first = preg_replace( '/[^\p{L}\p{N}]/u', '', (string) ( $words[0] ?? '' ) );
	if ( $first && mb_strlen( $first ) <= 3 ) {
		return mb_strtoupper( $first );
	}
	$mono = '';
	foreach ( array_slice( $words, 0, 2 ) as $word ) {
		$word  = preg_replace( '/[^\p{L}\p{N}]/u', '', $word );
		$mono .= $word ? mb_substr( $word, 0, 1 ) : '';
	}
	return mb_strtoupper( $mono ?: 'S' );
}

/** Link to the contact section, on this page when it has one. */
function bap_blush_contact_url(): string {
	return ( is_front_page() || is_page_template( 'page-pricing.php' ) ) ? '#lien-he' : home_url( '/#lien-he' );
}

/**
 * Pinned albums (up to 3) from Studio settings, falling back to the first 3.
 *
 * @return WP_Term[]
 */
function bap_blush_pinned_albums(): array {
	$terms = array();
	foreach ( array_slice( array_filter( array_map( 'intval', (array) bap_option( 'pinned_albums', array() ) ) ), 0, 3 ) as $tid ) {
		$term = get_term( $tid, 'bap_album' );
		if ( $term && ! is_wp_error( $term ) ) {
			$terms[] = $term;
		}
	}
	return $terms ? $terms : array_slice( bap_albums(), 0, 3 );
}
