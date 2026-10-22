<?php
/**
 * One photo in a gallery grid. Expects $args['id'] (attachment ID).
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

$bap_id    = (int) ( $args['id'] ?? 0 );
$bap_title = get_the_title( $bap_id );
if ( ! $bap_id ) {
	return;
}
?>
<figure class="tile" data-tile>
	<button type="button" class="tile-btn" <?php bap_dialog_attrs( $bap_id ); ?> aria-label="<?php echo esc_attr( sprintf( /* translators: %s photo title */ __( 'Xem ảnh lớn: %s', 'bap-studio' ), $bap_title ) ); ?>">
		<?php
		echo wp_get_attachment_image(
			$bap_id,
			'bap-grid',
			false,
			array(
				'loading' => 'lazy',
				'sizes'   => '(min-width: 1024px) 420px, (min-width: 600px) 50vw, 100vw',
			)
		);
		?>
	</button>
	<?php if ( $bap_title ) : ?>
		<figcaption class="tile-caption"><?php echo esc_html( $bap_title ); ?></figcaption>
	<?php endif; ?>
</figure>
