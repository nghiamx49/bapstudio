<?php
/**
 * Contact section: intro, studio details and the booking form.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

$bap_phone   = bap_option( 'phone' );
$bap_email   = bap_option( 'email' );
$bap_address = bap_option( 'address' );
?>
<section class="section contact" id="lien-he">
	<div class="container">
		<div class="contact-box" data-reveal>
			<div class="contact-intro">
				<p class="eyebrow"><?php esc_html_e( 'Liên hệ', 'bap-studio' ); ?></p>
				<h2 class="section-title"><?php echo esc_html( bap_option( 'contact_title', 'Hãy để chúng tôi đồng hành cùng ngày trọng đại của bạn' ) ); ?></h2>
				<p class="lead"><?php echo esc_html( bap_option( 'contact_text', 'Liên hệ để nhận tư vấn ý tưởng và báo giá chi tiết — miễn phí.' ) ); ?></p>
				<dl class="contact-details">
					<?php if ( $bap_phone ) : ?>
						<div><dt><?php esc_html_e( 'Điện thoại', 'bap-studio' ); ?></dt><dd><a href="tel:<?php echo esc_attr( preg_replace( '/[^\d+]/', '', $bap_phone ) ); ?>"><?php echo esc_html( $bap_phone ); ?></a></dd></div>
					<?php endif; ?>
					<?php if ( $bap_email ) : ?>
						<div><dt>Email</dt><dd><a href="mailto:<?php echo esc_attr( antispambot( $bap_email ) ); ?>"><?php echo esc_html( antispambot( $bap_email ) ); ?></a></dd></div>
					<?php endif; ?>
					<?php if ( $bap_address ) : ?>
						<div><dt><?php esc_html_e( 'Địa chỉ', 'bap-studio' ); ?></dt><dd><?php echo esc_html( $bap_address ); ?></dd></div>
					<?php endif; ?>
				</dl>
			</div>
			<div class="contact-form">
				<?php bap_render_contact_form(); ?>
			</div>
		</div>
	</div>
</section>
