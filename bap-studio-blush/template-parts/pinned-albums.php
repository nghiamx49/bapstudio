<?php
/**
 * Home: the 3 albums pinned in Studio settings, as hairline-divided columns.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

$bap_terms = bap_blush_pinned_albums();
if ( ! $bap_terms ) {
	return;
}
$bap_num = (int) ( $args['num'] ?? 1 );
?>
<section class="section pinned" id="bo-suu-tap">
	<div class="container">
		<?php
		get_template_part(
			'template-parts/section-head',
			null,
			array(
				'num'    => $bap_num,
				'kicker' => __( 'Thư viện ảnh', 'bap-studio-blush' ),
				'title'  => bap_option( 'pinned_title', 'Tác phẩm *tiêu biểu*' ),
				'aside'  => __( 'Những bộ sưu tập được chọn lọc. Bấm vào ảnh để xem lớn cùng câu chuyện phía sau khung hình.', 'bap-studio-blush' ),
			)
		);
		?>

		<div class="pinned-grid hairline-cols" data-stagger>
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
					<span class="col-num"><?php echo esc_html( sprintf( '%02d', $bap_i + 1 ) ); ?></span>
					<a class="album-card-cover" href="<?php echo esc_url( $bap_link ); ?>" tabindex="-1" aria-hidden="true">
						<?php
						if ( $bap_cover ) {
							echo wp_get_attachment_image( $bap_cover, 'bap-card', false, array( 'loading' => 'lazy', 'sizes' => '(min-width: 1024px) 400px, 100vw' ) );
						}
						?>
					</a>
					<h3 class="album-card-title"><a href="<?php echo esc_url( $bap_link ); ?>"><?php echo esc_html( $bap_term->name ); ?></a></h3>
					<?php if ( $bap_intro ) : ?>
						<p class="album-card-intro"><?php echo esc_html( wp_trim_words( $bap_intro, 28 ) ); ?></p>
					<?php endif; ?>
					<?php if ( $bap_thumbs ) : ?>
						<div class="album-card-thumbs">
							<?php foreach ( $bap_thumbs as $bap_thumb ) : ?>
								<button type="button" class="thumb-btn" <?php bap_dialog_attrs( $bap_thumb ); ?> aria-label="<?php echo esc_attr( sprintf( /* translators: %s photo title */ __( 'Xem ảnh lớn: %s', 'bap-studio-blush' ), get_the_title( $bap_thumb ) ) ); ?>">
									<?php echo wp_get_attachment_image( $bap_thumb, 'thumbnail', false, array( 'loading' => 'lazy' ) ); ?>
								</button>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<a class="text-link" href="<?php echo esc_url( $bap_link ); ?>"><?php esc_html_e( 'Xem album', 'bap-studio-blush' ); ?> <span class="count">(<?php echo (int) $bap_term->count; ?>)</span> <?php echo bap_blush_arrow(); // phpcs:ignore ?></a>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
