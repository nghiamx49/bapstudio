<?php
/**
 * Contact Form 7 integration.
 *
 * - [bap_package your-package] form tag: dropdown filled from pricing packages.
 * - [_bap_studio_email] mail tag: the email set in Studio settings.
 * - A ready-made "Đặt lịch tư vấn" form is created once if none is configured.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wpcf7_init',
	static function () {
		wpcf7_add_form_tag(
			array( 'bap_package', 'bap_package*' ),
			static function ( $tag ) {
				if ( empty( $tag->name ) ) {
					return '';
				}
				$options = '<option value="">' . esc_html__( '— Chọn gói (không bắt buộc) —', 'bap-studio' ) . '</option>';
				foreach ( bap_packages() as $pkg ) {
					$title    = get_the_title( $pkg );
					$options .= sprintf( '<option value="%1$s">%1$s</option>', esc_attr( $title ) );
				}
				$class = wpcf7_form_controls_class( $tag->type );
				return sprintf(
					'<span class="wpcf7-form-control-wrap" data-name="%1$s"><select name="%1$s" class="%2$s" aria-label="%3$s">%4$s</select></span>',
					esc_attr( $tag->name ),
					esc_attr( $class ),
					esc_attr__( 'Gói dịch vụ', 'bap-studio' ),
					$options
				);
			},
			array( 'name-attr' => true )
		);
	}
);

/** Keep the theme's form markup as written (no automatic <p> and <br>). */
add_filter( 'wpcf7_autop_or_not', '__return_false' );

add_filter(
	'wpcf7_special_mail_tags',
	static function ( $output, $name ) {
		if ( '_bap_studio_email' === $name ) {
			return (string) bap_option( 'email', get_option( 'admin_email' ) );
		}
		return $output;
	},
	10,
	2
);

/** The default form body, styled by the theme. */
function bap_default_form_body(): string {
	return <<<'FORM'
<div class="form-grid">
<label>Họ và tên *[text* your-name autocomplete:name]</label>
<label>Số điện thoại *[tel* your-phone autocomplete:tel]</label>
<label>Email[email your-email autocomplete:email]</label>
<label>Ngày dự kiến[date event-date]</label>
<label class="span-2">Gói dịch vụ[bap_package your-package]</label>
<label class="span-2">Lời nhắn[textarea your-message x4]</label>
</div>
[submit class:btn class:btn-primary "Gửi yêu cầu"]
FORM;
}

/** Create the default form once and remember its ID. */
function bap_ensure_contact_form(): int {
	$id = (int) get_option( 'bap_cf7_form_id' );
	if ( $id && get_post( $id ) ) {
		return $id;
	}
	if ( ! class_exists( 'WPCF7_ContactForm' ) || ! current_user_can( 'wpcf7_edit_contact_forms' ) ) {
		return 0;
	}
	$form = WPCF7_ContactForm::get_template( array( 'title' => 'Đặt lịch tư vấn' ) );
	$form->set_properties(
		array(
			'form' => bap_default_form_body(),
			'mail' => array(
				'active'             => true,
				'subject'            => '[_site_title] Yêu cầu tư vấn từ [your-name]',
				'sender'             => '[_site_title] <wordpress@[_site_domain]>',
				'recipient'          => '[_bap_studio_email]',
				'body'               => "Họ tên: [your-name]\nĐiện thoại: [your-phone]\nEmail: [your-email]\nNgày dự kiến: [event-date]\nGói: [your-package]\n\n[your-message]\n\n-- \nGửi từ [_site_url]",
				'additional_headers' => 'Reply-To: [your-email]',
				'attachments'        => '',
				'use_html'           => false,
				'exclude_blank'      => true,
			),
		)
	);
	$id = (int) $form->save();
	if ( $id ) {
		update_option( 'bap_cf7_form_id', $id );
	}
	return $id;
}
add_action( 'admin_init', 'bap_ensure_contact_form' );

/** Echo the contact form, or contact details if Contact Form 7 is missing. */
function bap_render_contact_form(): void {
	$shortcode = trim( (string) bap_option( 'contact_form' ) );
	if ( ! $shortcode && shortcode_exists( 'contact-form-7' ) ) {
		$id = (int) get_option( 'bap_cf7_form_id' );
		if ( $id ) {
			$shortcode = sprintf( '[contact-form-7 id="%d" html_class="bap-form"]', $id );
		}
	}
	if ( $shortcode ) {
		echo do_shortcode( $shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput
		return;
	}
	$email = bap_option( 'email' );
	if ( $email ) {
		printf(
			'<p class="contact-fallback">%1$s <a href="mailto:%2$s">%3$s</a></p>',
			esc_html__( 'Gửi email cho chúng tôi:', 'bap-studio' ),
			esc_attr( antispambot( $email ) ),
			esc_html( antispambot( $email ) )
		);
	}
}
