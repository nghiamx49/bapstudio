<?php
/**
 * One album card: cover, number, title, intro, 3 thumbnails, "Xem album" link.
 * Used by the home pinned albums and the catalogue page.
 *
 * Args: term (WP_Term), index (int, 0-based).
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

$bap_term = $args['term'] ?? null;
if ( ! $bap_term instanceof WP_Term ) {
	return;
}
$bap_card = bap_album_card_data( $bap_term );
?>
<article class="album-card" data-stagger-item>
	<a class="album-card-cover" href="<?php echo esc_url( $bap_card['link'] ); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( $bap_card['cover'] ) {
			echo wp_get_attachment_image( $bap_card['cover'], 'bap-card', false, array( 'loading' => 'lazy', 'sizes' => '(min-width: 1024px) 420px, 100vw' ) );
		}
		?>
	</a>
	<div class="album-card-body">
		<div class="album-card-num"><?php echo esc_html( sprintf( '%02d', (int) ( $args['index'] ?? 0 ) + 1 ) ); ?></div>
		<h3 class="album-card-title"><a href="<?php echo esc_url( $bap_card['link'] ); ?>"><?php echo esc_html( $bap_term->name ); ?></a></h3>
		<?php if ( $bap_card['intro'] ) : ?>
			<p class="album-card-intro"><?php echo esc_html( wp_trim_words( $bap_card['intro'], 30 ) ); ?></p>
		<?php endif; ?>
		<?php if ( $bap_card['thumbs'] ) : ?>
			<div class="album-card-thumbs">
				<?php foreach ( $bap_card['thumbs'] as $bap_thumb ) : ?>
					<button type="button" class="thumb-btn" <?php bap_dialog_attrs( $bap_thumb ); ?> aria-label="<?php echo esc_attr( sprintf( /* translators: %s photo title */ __( 'Xem ảnh lớn: %s', 'bap-studio' ), get_the_title( $bap_thumb ) ) ); ?>">
						<?php echo wp_get_attachment_image( $bap_thumb, 'thumbnail', false, array( 'loading' => 'lazy' ) ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<a class="link-arrow" href="<?php echo esc_url( $bap_card['link'] ); ?>"><?php esc_html_e( 'Xem album', 'bap-studio' ); ?> <span aria-hidden="true">→</span></a>
	</div>
</article>
