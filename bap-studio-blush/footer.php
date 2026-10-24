<?php
/**
 * Footer on night plum: large serif sign-off, studio details, social links.
 * Also prints the shared image dialog (template-parts/image-dialog.php from the parent).
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

$bap_phone   = bap_option( 'phone' );
$bap_email   = bap_option( 'email' );
$bap_address = bap_option( 'address' );
$bap_map     = bap_option( 'map_url' );
$bap_social  = bap_option( 'social', array() );
?>
</main>

<footer class="site-footer">
	<div class="container">
		<div class="footer-top">
			<div class="footer-brand">
				<?php bap_blush_kicker( bap_option( 'footer_tagline', 'Ảnh cưới · Sự kiện · Prom' ), 0, 'on-dark' ); ?>
				<p class="footer-name"><?php echo esc_html( bap_studio_name() ); ?></p>
			</div>
			<a class="btn btn-pill" href="<?php echo esc_url( bap_contact_url() ); ?>"><?php esc_html_e( 'Đặt lịch tư vấn', 'bap-studio-blush' ); ?> <?php echo bap_blush_arrow(); // phpcs:ignore ?></a>
		</div>

		<div class="footer-cols">
			<?php if ( $bap_address ) : ?>
				<div class="footer-col">
					<p class="footer-label"><?php esc_html_e( 'Địa chỉ', 'bap-studio-blush' ); ?></p>
					<address>
						<?php if ( $bap_map ) : ?>
							<a href="<?php echo esc_url( $bap_map ); ?>" target="_blank" rel="noopener"><?php echo nl2br( esc_html( $bap_address ) ); // phpcs:ignore ?></a>
						<?php else : ?>
							<?php echo nl2br( esc_html( $bap_address ) ); // phpcs:ignore ?>
						<?php endif; ?>
					</address>
				</div>
			<?php endif; ?>
			<?php if ( $bap_phone || $bap_email ) : ?>
				<div class="footer-col">
					<p class="footer-label"><?php esc_html_e( 'Liên hệ', 'bap-studio-blush' ); ?></p>
					<?php if ( $bap_phone ) : ?>
						<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $bap_phone ) ); ?>"><?php echo esc_html( $bap_phone ); ?></a></p>
					<?php endif; ?>
					<?php if ( $bap_email ) : ?>
						<p><a href="mailto:<?php echo esc_attr( antispambot( $bap_email ) ); ?>"><?php echo esc_html( antispambot( $bap_email ) ); ?></a></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( $bap_social ) : ?>
				<div class="footer-col">
					<p class="footer-label"><?php esc_html_e( 'Theo dõi', 'bap-studio-blush' ); ?></p>
					<ul class="footer-social">
						<?php foreach ( $bap_social as $bap_link ) : ?>
							<?php
							if ( empty( $bap_link['url'] ) ) {
								continue;
							}
							?>
							<li><a href="<?php echo esc_url( $bap_link['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( ucfirst( (string) $bap_link['name'] ) ); ?> <?php echo bap_blush_arrow(); // phpcs:ignore ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>

		<div class="footer-bottom">
			<span>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( bap_studio_name() ); ?>. <?php esc_html_e( 'Bảo lưu mọi quyền.', 'bap-studio-blush' ); ?></span>
			<a href="#top" class="to-top"><?php esc_html_e( 'Lên đầu trang', 'bap-studio-blush' ); ?> <span aria-hidden="true">↑</span></a>
		</div>
	</div>
</footer>

<?php get_template_part( 'template-parts/image-dialog' ); ?>
<?php wp_footer(); ?>
</body>
</html>
