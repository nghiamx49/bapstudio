<?php
/**
 * Editorial section header: kicker and big serif title on the left (7/12),
 * short aside and a text link on the right (5/12).
 *
 * Args: num (int), kicker, title (supports *italic*), aside, link_url, link_label, tag (h1|h2).
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

$bap_a = wp_parse_args(
	$args ?? array(),
	array(
		'num'        => 0,
		'kicker'     => '',
		'title'      => '',
		'aside'      => '',
		'link_url'   => '',
		'link_label' => '',
		'tag'        => 'h2',
	)
);
$bap_tag = 'h1' === $bap_a['tag'] ? 'h1' : 'h2';
?>
<header class="section-head" data-reveal>
	<div class="section-head-main">
		<?php if ( $bap_a['kicker'] ) : ?>
			<?php bap_blush_kicker( $bap_a['kicker'], (int) $bap_a['num'] ); ?>
		<?php endif; ?>
		<<?php echo $bap_tag; // phpcs:ignore ?> class="section-title" data-split><?php echo bap_blush_title( (string) $bap_a['title'] ); // phpcs:ignore ?></<?php echo $bap_tag; // phpcs:ignore ?>>
	</div>
	<?php if ( $bap_a['aside'] || $bap_a['link_url'] ) : ?>
		<div class="section-head-aside">
			<?php if ( $bap_a['aside'] ) : ?>
				<p class="aside"><?php echo esc_html( $bap_a['aside'] ); ?></p>
			<?php endif; ?>
			<?php if ( $bap_a['link_url'] ) : ?>
				<a class="text-link" href="<?php echo esc_url( $bap_a['link_url'] ); ?>"><?php echo esc_html( $bap_a['link_label'] ); ?> <?php echo bap_blush_arrow(); // phpcs:ignore ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</header>
