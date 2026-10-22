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
			<?php foreach ( $bap_terms as $bap_i => $bap_term ) : ?>
				<?php
				$bap_photos = bap_album_photos( $bap_term->term_id, 1, 4 );
				$bap_cover  = bap_attachment_id( bap_field( 'cover', $bap_term ) );
				if ( ! $bap_cover && $bap_photos['ids'] ) {
					$bap_cover = $bap_photos['ids'][0];
				}
				$bap_thumbs = array_slice( array_values( array_diff( $bap_photos['ids'], array( $bap_cover ) ) ), 0, 3 );
				$bap_link   = get_term_link( $bap_term );
				$bap_intro  = bap_field( 'intro', $bap_term, $bap_term->description );
				?>
				<article class="album-card" data-stagger-item>
					<a class="album-card-cover" href="<?php echo esc_url( $bap_link ); ?>" tabindex="-1" aria-hidden="true">
						<?php
						if ( $bap_cover ) {
							echo wp_get_attachment_image( $bap_cover, 'bap-card', false, array( 'loading' => 'lazy', 'sizes' => '(min-width: 1024px) 420px, 100vw' ) );
						}
						?>
					</a>
					<div class="album-card-body">
						<div class="album-card-num"><?php echo esc_html( sprintf( '%02d', $bap_i + 1 ) ); ?></div>
						<h3 class="album-card-title"><a href="<?php echo esc_url( $bap_link ); ?>"><?php echo esc_html( $bap_term->name ); ?></a></h3>
						<?php if ( $bap_intro ) : ?>
							<p class="album-card-intro"><?php echo esc_html( wp_trim_words( $bap_intro, 30 ) ); ?></p>
						<?php endif; ?>
						<?php if ( $bap_thumbs ) : ?>
							<div class="album-card-thumbs">
								<?php foreach ( $bap_thumbs as $bap_thumb ) : ?>
									<button type="button" class="thumb-btn" <?php bap_dialog_attrs( $bap_thumb ); ?> aria-label="<?php echo esc_attr( sprintf( /* translators: %s photo title */ __( 'Xem ảnh lớn: %s', 'bap-studio' ), get_the_title( $bap_thumb ) ) ); ?>">
										<?php echo wp_get_attachment_image( $bap_thumb, 'thumbnail', false, array( 'loading' => 'lazy' ) ); ?>
									</button>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
						<a class="link-arrow" href="<?php echo esc_url( $bap_link ); ?>"><?php esc_html_e( 'Xem album', 'bap-studio' ); ?> <span aria-hidden="true">→</span></a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
