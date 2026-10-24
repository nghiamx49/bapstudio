<?php
/**
 * Pricing package cards. Used on the home page and the pricing page.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

$bap_packages = bap_packages();
if ( ! $bap_packages ) {
	return;
}
?>
<div class="pricing-grid" data-stagger>
	<?php foreach ( $bap_packages as $bap_pkg ) : ?>
		<?php
		$bap_items     = (array) bap_field( 'items', $bap_pkg->ID, array() );
		$bap_highlight = (bool) bap_field( 'highlight', $bap_pkg->ID, false );
		$bap_summary   = bap_field( 'summary', $bap_pkg->ID );
		$bap_note      = bap_field( 'price_note', $bap_pkg->ID );
		$bap_btn       = bap_field( 'button_label', $bap_pkg->ID, __( 'Đặt lịch', 'bap-studio' ) );
		?>
		<article class="price-card<?php echo $bap_highlight ? ' is-highlight' : ''; ?>" data-stagger-item>
			<?php if ( $bap_highlight ) : ?>
				<span class="price-badge"><?php esc_html_e( 'Phổ biến', 'bap-studio' ); ?></span>
			<?php endif; ?>
			<h3 class="price-title"><?php echo esc_html( get_the_title( $bap_pkg ) ); ?></h3>
			<?php if ( $bap_summary ) : ?>
				<p class="price-summary"><?php echo esc_html( $bap_summary ); ?></p>
			<?php endif; ?>
			<p class="price-amount">
				<span class="amount"><?php echo esc_html( bap_format_price( bap_field( 'price', $bap_pkg->ID ) ) ); ?></span>
				<?php if ( $bap_note ) : ?>
					<span class="note"><?php echo esc_html( $bap_note ); ?></span>
				<?php endif; ?>
			</p>
			<?php if ( $bap_items ) : ?>
				<ul class="price-items">
					<?php foreach ( $bap_items as $bap_item ) : ?>
						<?php
						if ( empty( $bap_item['text'] ) ) {
							continue;
						}
						?>
						<li><?php echo bap_icon( 'check' ); // phpcs:ignore ?><span><?php echo esc_html( $bap_item['text'] ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			<a class="btn <?php echo $bap_highlight ? 'btn-primary' : 'btn-ghost'; ?>" href="<?php echo esc_url( bap_package_booking_url( $bap_pkg ) ); ?>" data-package="<?php echo esc_attr( get_the_title( $bap_pkg ) ); ?>"><?php echo esc_html( $bap_btn ); ?></a>
		</article>
	<?php endforeach; ?>
</div>
