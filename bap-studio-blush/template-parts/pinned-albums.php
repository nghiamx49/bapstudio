<?php
/**
 * Home: the 3 albums pinned in Studio settings, as hairline-divided columns.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

$bap_terms = bap_blush_pinned_albums();
if ( ! $bap_terms ) {
	return;
}
$bap_num = (int) ( $args['num'] ?? 1 );
?>
<section class="section pinned" id="bo-suu-tap">
	<div class="container">
		<?php
		get_template_part(
			'template-parts/section-head',
			null,
			array(
				'num'    => $bap_num,
				'kicker' => __( 'Thư viện ảnh', 'bap-studio-blush' ),
				'title'  => bap_option( 'pinned_title', 'Tác phẩm *tiêu biểu*' ),
				'aside'  => __( 'Những bộ sưu tập được chọn lọc. Bấm vào ảnh để xem lớn cùng câu chuyện phía sau khung hình.', 'bap-studio-blush' ),
			)
		);
		?>

		<div class="pinned-grid hairline-cols" data-stagger>
			<?php
			foreach ( $bap_terms as $bap_i => $bap_term ) {
				get_template_part( 'template-parts/album-card', null, array( 'term' => $bap_term, 'index' => $bap_i ) );
			}
			?>
		</div>
	</div>
</section>
