<?php
/**
 * Album tab bar (Cưới / Sự kiện / Prom ...), as in the mockup.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

$bap_albums  = bap_albums();
$bap_current = is_tax( 'bap_album' ) ? get_queried_object_id() : 0;
if ( count( $bap_albums ) < 2 ) {
	return;
}
?>
<nav class="album-tabs" aria-label="<?php esc_attr_e( 'Albums', 'bap-studio' ); ?>" data-tabs>
	<?php foreach ( $bap_albums as $bap_album ) : ?>
		<a class="album-tab" href="<?php echo esc_url( get_term_link( $bap_album ) ); ?>"<?php echo $bap_current === $bap_album->term_id ? ' aria-current="page"' : ''; ?>>
			<?php echo esc_html( $bap_album->name ); ?> <span class="count">(<?php echo (int) $bap_album->count; ?>)</span>
		</a>
	<?php endforeach; ?>
</nav>
