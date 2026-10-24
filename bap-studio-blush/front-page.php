<?php
/**
 * Home page: hero, carousel, pinned albums, pricing. The contact form lives on the contact page.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

get_header();

get_template_part( 'template-parts/banner' );
get_template_part( 'template-parts/carousel' );

$bap_num = 0;
if ( bap_blush_pinned_albums() ) {
	get_template_part( 'template-parts/pinned-albums', null, array( 'num' => ++$bap_num ) );
}

if ( bap_packages() ) :
	$bap_pricing_url = bap_option( 'pricing_page' );
	?>
	<section class="section pricing" id="bang-gia">
		<div class="container">
			<?php
			get_template_part(
				'template-parts/section-head',
				null,
				array(
					'num'        => ++$bap_num,
					'kicker'     => __( 'Dịch vụ', 'bap-studio-blush' ),
					'title'      => bap_option( 'pricing_title', 'Bảng giá *dịch vụ*' ),
					'aside'      => __( 'Mỗi gói đều có thể điều chỉnh theo nhu cầu của bạn. Liên hệ để nhận báo giá chi tiết.', 'bap-studio-blush' ),
					'link_url'   => $bap_pricing_url,
					'link_label' => __( 'Xem bảng giá chi tiết', 'bap-studio-blush' ),
				)
			);
			get_template_part( 'template-parts/pricing-cards' );
			?>
		</div>
	</section>
	<?php
endif;

get_footer();
