<?php
/**
 * Home page: banner, carousel, pinned albums, pricing. The contact form lives on the contact page.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/banner' );
get_template_part( 'template-parts/carousel' );
get_template_part( 'template-parts/pinned-albums' );

$bap_pricing_url = bap_option( 'pricing_page' );
if ( bap_packages() ) :
	?>
	<section class="section pricing" id="bang-gia">
		<div class="container">
			<header class="section-head" data-reveal>
				<p class="eyebrow"><?php esc_html_e( 'Dịch vụ', 'bap-studio' ); ?></p>
				<h2 class="section-title"><?php echo esc_html( bap_option( 'pricing_title', 'Bảng giá dịch vụ' ) ); ?></h2>
			</header>
			<?php get_template_part( 'template-parts/pricing-cards' ); ?>
			<?php if ( $bap_pricing_url ) : ?>
				<p class="section-more"><a class="link-arrow" href="<?php echo esc_url( $bap_pricing_url ); ?>"><?php esc_html_e( 'Xem bảng giá chi tiết', 'bap-studio' ); ?> <span aria-hidden="true">→</span></a></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
endif;

get_footer();
