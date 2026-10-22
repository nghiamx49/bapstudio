<?php
/**
 * Site footer with studio details, plus the shared image dialog.
 *
 * @package bap-studio
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
	<div class="container footer-grid">
		<div class="footer-brand">
			<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html( bap_studio_name() ); ?></a>
			<p class="footer-tagline"><?php echo esc_html( bap_option( 'footer_tagline', 'Ảnh cưới · Sự kiện · Prom' ) ); ?></p>
		</div>

		<address class="footer-contact">
			<?php if ( $bap_address ) : ?>
				<p>
					<?php if ( $bap_map ) : ?>
						<a href="<?php echo esc_url( $bap_map ); ?>" target="_blank" rel="noopener"><?php echo nl2br( esc_html( $bap_address ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
					<?php else : ?>
						<?php echo nl2br( esc_html( $bap_address ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php endif; ?>
				</p>
			<?php endif; ?>
			<?php if ( $bap_phone ) : ?>
				<p><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $bap_phone ) ); ?>"><?php echo esc_html( $bap_phone ); ?></a></p>
			<?php endif; ?>
			<?php if ( $bap_email ) : ?>
				<p><a href="mailto:<?php echo esc_attr( antispambot( $bap_email ) ); ?>"><?php echo esc_html( antispambot( $bap_email ) ); ?></a></p>
			<?php endif; ?>
		</address>

		<?php if ( $bap_social ) : ?>
			<ul class="footer-social">
				<?php foreach ( $bap_social as $bap_link ) : ?>
					<?php
					if ( empty( $bap_link['url'] ) ) {
						continue;
					}
					?>
					<li><a href="<?php echo esc_url( $bap_link['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( ucfirst( (string) $bap_link['name'] ) ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<div class="container footer-bottom">
		<span>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php echo esc_html( bap_studio_name() ); ?>. <?php esc_html_e( 'Bảo lưu mọi quyền.', 'bap-studio' ); ?></span>
	</div>
</footer>

<?php get_template_part( 'template-parts/image-dialog' ); ?>
<?php wp_footer(); ?>
</body>
</html>
