<?php
/**
 * Contact section: section header, studio details as hairline rows, and the booking form.
 *
 * Args: num (int), is_page (bool) — true on the contact page, where the title is the page's h1.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

$bap_phone   = bap_option( 'phone' );
$bap_email   = bap_option( 'email' );
$bap_address = bap_option( 'address' );
$bap_num     = (int) ( $args['num'] ?? 0 );
$bap_is_page = ! empty( $args['is_page'] );
?>
<section class="section contact<?php echo $bap_is_page ? ' contact-page' : ''; ?>" id="lien-he">
	<div class="container">
		<?php
		get_template_part(
			'template-parts/section-head',
			null,
			array(
				'num'    => $bap_num,
				'kicker' => __( 'Liên hệ', 'bap-studio-blush' ),
				'title'  => bap_option( 'contact_title', 'Hãy để chúng tôi đồng hành cùng *ngày trọng đại* của bạn' ),
				'aside'  => bap_option( 'contact_text', 'Liên hệ để nhận tư vấn ý tưởng và báo giá chi tiết — miễn phí.' ),
				'tag'    => $bap_is_page ? 'h1' : 'h2',
			)
		);
		?>
		<div class="contact-grid" data-reveal>
			<dl class="contact-details">
				<?php if ( $bap_phone ) : ?>
					<div><dt><?php esc_html_e( 'Điện thoại', 'bap-studio-blush' ); ?></dt><dd><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $bap_phone ) ); ?>"><?php echo esc_html( $bap_phone ); ?></a></dd></div>
				<?php endif; ?>
				<?php if ( $bap_email ) : ?>
					<div><dt>Email</dt><dd><a href="mailto:<?php echo esc_attr( antispambot( $bap_email ) ); ?>"><?php echo esc_html( antispambot( $bap_email ) ); ?></a></dd></div>
				<?php endif; ?>
				<?php if ( $bap_address ) : ?>
					<div><dt><?php esc_html_e( 'Địa chỉ', 'bap-studio-blush' ); ?></dt><dd><?php echo nl2br( esc_html( $bap_address ) ); // phpcs:ignore ?></dd></div>
				<?php endif; ?>
			</dl>
			<div class="contact-form">
				<?php bap_render_contact_form(); ?>
			</div>
		</div>
	</div>
</section>
