<?php
/**
 * Home carousel on a night-plum band. Clicking a slide opens the image dialog.
 * Markup hooks (data-carousel, data-slide, ...) match the parent's carousel script.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

$bap_ids = array_values( array_filter( array_map( 'bap_attachment_id', (array) bap_option( 'home_carousel', array() ) ) ) );
if ( ! $bap_ids ) {
	return;
}
$bap_total = count( $bap_ids );
?>
<section class="section showcase" id="noi-bat" aria-label="<?php esc_attr_e( 'Ảnh nổi bật', 'bap-studio-blush' ); ?>">
	<div class="container">
		<div class="carousel" data-carousel aria-roledescription="carousel">
			<div class="carousel-viewport">
				<div class="carousel-track" data-carousel-track>
					<?php foreach ( $bap_ids as $bap_i => $bap_id ) : ?>
						<?php
						$bap_album = bap_photo_album( $bap_id );
						$bap_title = get_the_title( $bap_id );
						?>
						<div class="carousel-slide" data-slide role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( ( $bap_i + 1 ) . ' / ' . $bap_total ); ?>"<?php echo $bap_i ? ' aria-hidden="true"' : ''; ?>>
							<button type="button" class="carousel-open" <?php bap_dialog_attrs( $bap_id ); ?> aria-label="<?php echo esc_attr( sprintf( /* translators: %s photo title */ __( 'Xem ảnh lớn: %s', 'bap-studio-blush' ), $bap_title ) ); ?>">
								<?php
								echo wp_get_attachment_image(
									$bap_id,
									'bap-slide',
									false,
									array(
										'loading' => $bap_i ? 'lazy' : 'eager',
										'sizes'   => '(min-width: 1440px) 1280px, 100vw',
									)
								);
								?>
							</button>
							<div class="carousel-caption">
								<?php if ( $bap_album ) : ?>
									<p class="kicker on-dark"><?php echo esc_html( $bap_album->name ); ?></p>
								<?php endif; ?>
								<p class="carousel-title"><?php echo esc_html( $bap_title ); ?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( $bap_total > 1 ) : ?>
				<div class="carousel-bar">
					<div class="carousel-dots" role="group" aria-label="<?php esc_attr_e( 'Chọn ảnh', 'bap-studio-blush' ); ?>">
						<?php for ( $bap_d = 0; $bap_d < $bap_total; $bap_d++ ) : ?>
							<button type="button" class="carousel-dot" data-carousel-dot="<?php echo (int) $bap_d; ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %d slide number */ __( 'Đến ảnh %d', 'bap-studio-blush' ), $bap_d + 1 ) ); ?>"<?php echo 0 === $bap_d ? ' aria-current="true"' : ''; ?>><span><?php echo esc_html( sprintf( '%02d', $bap_d + 1 ) ); ?></span></button>
						<?php endfor; ?>
					</div>
					<div class="carousel-controls">
						<button type="button" class="carousel-play" data-carousel-play aria-pressed="false">
							<span class="when-playing"><?php echo bap_icon( 'pause' ); // phpcs:ignore ?></span>
							<span class="when-paused"><?php echo bap_icon( 'play' ); // phpcs:ignore ?></span>
							<span data-carousel-play-label><?php esc_html_e( 'Tạm dừng', 'bap-studio-blush' ); ?></span>
						</button>
						<button type="button" class="carousel-arrow prev" data-carousel-prev aria-label="<?php esc_attr_e( 'Ảnh trước', 'bap-studio-blush' ); ?>"><?php echo bap_icon( 'prev' ); // phpcs:ignore ?></button>
						<button type="button" class="carousel-arrow next" data-carousel-next aria-label="<?php esc_attr_e( 'Ảnh tiếp theo', 'bap-studio-blush' ); ?>"><?php echo bap_icon( 'next' ); // phpcs:ignore ?></button>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
