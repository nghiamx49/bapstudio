<?php
/**
 * Not found.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section">
	<div class="container narrow center">
		<p class="eyebrow">404</p>
		<h1 class="section-title"><?php esc_html_e( 'Không tìm thấy trang', 'bap-studio' ); ?></h1>
		<p><a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Về trang chủ', 'bap-studio' ); ?></a></p>
	</div>
</section>
<?php
get_footer();
