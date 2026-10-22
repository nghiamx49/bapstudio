<?php
/**
 * Home banner: background image, primary and secondary text, two buttons.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

$bap_image   = bap_attachment_id( bap_option( 'banner_image' ) );
$bap_primary = function_exists( 'get_field' ) ? (string) get_field( 'banner_primary', 'option', false ) : '';
$bap_primary = $bap_primary ? $bap_primary : "Lưu giữ khoảnh khắc\n*đẹp nhất đời bạn*";
// Escape, then turn *words* into gold italics and line breaks into <br>.
$bap_primary = nl2br( preg_replace( '/\*(.+?)\*/u', '<em>$1</em>', esc_html( $bap_primary ) ) );

$bap_cta  = bap_option( 'banner_cta', array( 'url' => '#bo-suu-tap', 'title' => 'Xem thư viện ảnh' ) );
$bap_cta2 = bap_option( 'banner_cta2', array( 'url' => '#lien-he', 'title' => 'Đặt lịch tư vấn' ) );
?>
<section class="banner" id="top" data-banner>
	<div class="banner-media" data-banner-media>
		<?php
		if ( $bap_image ) {
			echo wp_get_attachment_image(
				$bap_image,
				'bap-dialog',
				false,
				array(
					'class'         => 'banner-img',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'sizes'         => '100vw',
				)
			);
		}
		?>
	</div>
	<div class="banner-overlay" aria-hidden="true"></div>
	<div class="container banner-inner">
		<div class="banner-content" data-banner-content>
			<p class="eyebrow" data-banner-eyebrow><?php echo esc_html( bap_option( 'banner_eyebrow', 'Nhiếp ảnh cưới · Sự kiện · Prom' ) ); ?></p>
			<h1 class="banner-title" data-split><?php echo $bap_primary; // phpcs:ignore WordPress.Security.EscapeOutput -- escaped above. ?></h1>
			<span class="gold-rule" data-banner-rule aria-hidden="true"></span>
			<p class="banner-sub" data-banner-sub><?php echo esc_html( bap_option( 'banner_secondary', 'Từng khung hình được chăm chút về ánh sáng, bố cục và cảm xúc — để mỗi lần xem lại là một lần sống lại ngày hôm ấy.' ) ); ?></p>
			<div class="btn-row" data-banner-buttons>
				<?php if ( ! empty( $bap_cta['url'] ) ) : ?>
					<a class="btn btn-primary" href="<?php echo esc_url( $bap_cta['url'] ); ?>"<?php echo ! empty( $bap_cta['target'] ) ? ' target="' . esc_attr( $bap_cta['target'] ) . '"' : ''; ?>><?php echo esc_html( $bap_cta['title'] ); ?></a>
				<?php endif; ?>
				<?php if ( ! empty( $bap_cta2['url'] ) ) : ?>
					<a class="btn btn-ghost" href="<?php echo esc_url( $bap_cta2['url'] ); ?>"<?php echo ! empty( $bap_cta2['target'] ) ? ' target="' . esc_attr( $bap_cta2['target'] ) . '"' : ''; ?>><?php echo esc_html( $bap_cta2['title'] ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
