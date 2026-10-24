<?php
/**
 * Home: the 3 albums pinned in Studio settings (falls back to the first 3).
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

$bap_pinned = array_filter( array_map( 'intval', (array) bap_option( 'pinned_albums', array() ) ) );
$bap_terms  = array();
foreach ( array_slice( $bap_pinned, 0, 3 ) as $bap_tid ) {
	$bap_t = get_term( $bap_tid, 'bap_album' );
	if ( $bap_t && ! is_wp_error( $bap_t ) ) {
		$bap_terms[] = $bap_t;
	}
}
if ( ! $bap_terms ) {
	$bap_terms = array_slice( bap_albums(), 0, 3 );
}
if ( ! $bap_terms ) {
	return;
}
?>
<section class="section pinned" id="bo-suu-tap">
	<div class="container">
		<header class="section-head" data-reveal>
			<p class="eyebrow"><?php esc_html_e( 'Thư viện ảnh', 'bap-studio' ); ?></p>
			<h2 class="section-title"><?php echo esc_html( bap_option( 'pinned_title', 'Tác phẩm tiêu biểu' ) ); ?></h2>
		</header>

		<div class="pinned-grid" data-stagger>
			<?php
			foreach ( $bap_terms as $bap_i => $bap_term ) {
				get_template_part( 'template-parts/album-card', null, array( 'term' => $bap_term, 'index' => $bap_i ) );
			}
			?>
		</div>
	</div>
</section>
