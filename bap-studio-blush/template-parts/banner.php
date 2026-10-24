<?php
/**
 * Home hero: full-bleed photo darkened to night plum, left-aligned serif title
 * with italic emphasis, short intro, mauve button + text link, and a tilted
 * paper card pointing to the first pinned album.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

$bap_image   = bap_attachment_id( bap_option( 'banner_image' ) );
$bap_primary = function_exists( 'get_field' ) ? (string) get_field( 'banner_primary', 'option', false ) : '';
$bap_primary = $bap_primary ? $bap_primary : "Lưu giữ khoảnh khắc\n*đẹp nhất đời bạn*";

$bap_cta  = bap_option( 'banner_cta', array( 'url' => '#bo-suu-tap', 'title' => 'Xem thư viện ảnh' ) );
$bap_cta2 = bap_option( 'banner_cta2', array( 'url' => bap_contact_url(), 'title' => 'Đặt lịch tư vấn' ) );
$bap_cta['url']  = bap_resolve_contact_link( (string) ( $bap_cta['url'] ?? '' ) );
$bap_cta2['url'] = bap_resolve_contact_link( (string) ( $bap_cta2['url'] ?? '' ) );
// A down arrow only makes sense for a link that scrolls within this page.
$bap_cta2_arrow = str_starts_with( $bap_cta2['url'], '#' ) ? 'down' : 'up';

$bap_cards = bap_blush_hero_cards();
?>
<section class="hero" data-banner>
	<div class="hero-media" data-banner-media>
		<?php
		if ( $bap_image ) {
			echo wp_get_attachment_image(
				$bap_image,
				'bap-dialog',
				false,
				array(
					'class'         => 'hero-img',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'sizes'         => '100vw',
				)
			);
		}
		?>
	</div>
	<div class="hero-shade" aria-hidden="true"></div>

	<div class="container hero-inner">
		<div class="hero-content" data-banner-content>
			<p class="kicker on-dark" data-banner-eyebrow><?php echo esc_html( bap_option( 'banner_eyebrow', 'Nhiếp ảnh cưới · Sự kiện · Prom' ) ); ?></p>
			<h1 class="hero-title" data-split><?php echo bap_blush_title( $bap_primary ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in helper. ?></h1>
			<p class="hero-sub" data-banner-sub><?php echo esc_html( bap_option( 'banner_secondary', 'Từng khung hình được chăm chút về ánh sáng, bố cục và cảm xúc — để mỗi lần xem lại là một lần sống lại ngày hôm ấy.' ) ); ?></p>
			<div class="btn-row" data-banner-buttons>
				<?php if ( ! empty( $bap_cta['url'] ) ) : ?>
					<a class="btn btn-primary" href="<?php echo esc_url( $bap_cta['url'] ); ?>"<?php echo ! empty( $bap_cta['target'] ) ? ' target="' . esc_attr( $bap_cta['target'] ) . '"' : ''; ?>><?php echo esc_html( $bap_cta['title'] ); ?> <?php echo bap_blush_arrow(); // phpcs:ignore ?></a>
				<?php endif; ?>
				<?php if ( ! empty( $bap_cta2['url'] ) ) : ?>
					<a class="text-link on-dark" href="<?php echo esc_url( $bap_cta2['url'] ); ?>"<?php echo ! empty( $bap_cta2['target'] ) ? ' target="' . esc_attr( $bap_cta2['target'] ) . '"' : ''; ?>><?php echo esc_html( $bap_cta2['title'] ); ?> <?php echo bap_blush_arrow( $bap_cta2_arrow ); // phpcs:ignore ?></a>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( $bap_cards ) : ?>
			<div class="hero-cards" data-hero-cards>
				<?php foreach ( $bap_cards as $bap_card ) : ?>
					<a class="hero-card" href="<?php echo esc_url( $bap_card['url'] ); ?>" data-hero-card>
						<?php if ( $bap_card['image'] ) : ?>
							<span class="hero-card-photo">
								<?php echo wp_get_attachment_image( $bap_card['image'], 'bap-card', false, array( 'alt' => '', 'loading' => 'eager', 'sizes' => '200px' ) ); ?>
							</span>
						<?php endif; ?>
						<span class="hero-card-kicker"><?php echo esc_html( $bap_card['kicker'] ); ?></span>
						<span class="hero-card-title"><?php echo esc_html( $bap_card['title'] ); ?></span>
						<span class="hero-card-foot"><?php echo esc_html( $bap_card['link_label'] ); ?> <?php echo bap_blush_arrow(); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					</a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

</section>
