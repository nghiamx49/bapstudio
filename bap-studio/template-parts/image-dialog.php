<?php
/**
 * Shared image dialog: photo on the left, title and description on the right.
 * Filled by src/js/dialog.js from the clicked element's data-* attributes.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;
?>
<dialog class="photo-dialog" data-dialog aria-labelledby="photo-dialog-title">
	<div class="photo-dialog-inner">
		<figure class="photo-dialog-media" data-dialog-media>
			<img src="" alt="" data-dialog-img decoding="async">
			<button type="button" class="dialog-arrow prev" data-dialog-prev aria-label="<?php esc_attr_e( 'Ảnh trước', 'bap-studio' ); ?>"><?php echo bap_icon( 'prev' ); // phpcs:ignore ?></button>
			<button type="button" class="dialog-arrow next" data-dialog-next aria-label="<?php esc_attr_e( 'Ảnh tiếp theo', 'bap-studio' ); ?>"><?php echo bap_icon( 'next' ); // phpcs:ignore ?></button>
		</figure>
		<div class="photo-dialog-text" data-dialog-text>
			<p class="eyebrow small" data-dialog-album></p>
			<h2 class="photo-dialog-title" id="photo-dialog-title" data-dialog-title></h2>
			<div class="photo-dialog-desc" data-dialog-desc></div>
			<p class="photo-dialog-pos" data-dialog-pos aria-live="polite"></p>
		</div>
		<button type="button" class="dialog-close" data-dialog-close aria-label="<?php esc_attr_e( 'Đóng', 'bap-studio' ); ?>"><?php echo bap_icon( 'close' ); // phpcs:ignore ?></button>
	</div>
</dialog>
