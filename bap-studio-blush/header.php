<?php
/**
 * Site header: round monogram + studio name, album links, outlined "Đặt lịch" button.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

$bap_pricing_url = bap_option( 'pricing_page' );
$bap_current     = is_tax( 'bap_album' ) ? get_queried_object_id() : 0;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body id="top" <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'Bỏ qua đến nội dung', 'bap-studio-blush' ); ?></a>

<header class="site-header" data-header>
	<nav class="site-nav" aria-label="<?php esc_attr_e( 'Điều hướng chính', 'bap-studio-blush' ); ?>">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php if ( has_custom_logo() ) : ?>
				<?php echo wp_get_attachment_image( (int) get_theme_mod( 'custom_logo' ), 'medium', false, array( 'class' => 'brand-logo', 'alt' => bap_studio_name() ) ); ?>
			<?php else : ?>
				<span class="brand-mark" aria-hidden="true"><?php echo esc_html( bap_blush_monogram() ); ?></span>
				<span class="brand-text">
					<span class="brand-name"><?php echo esc_html( bap_studio_name() ); ?></span>
					<span class="brand-tagline"><?php echo esc_html( bap_option( 'footer_tagline', 'Ảnh cưới · Sự kiện · Prom' ) ); ?></span>
				</span>
			<?php endif; ?>
		</a>

		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-menu" data-nav-toggle>
			<?php echo bap_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Mở menu', 'bap-studio-blush' ); ?></span>
		</button>

		<ul class="nav-links" id="site-menu" data-nav-menu>
			<?php if ( has_nav_menu( 'primary' ) ) : ?>
				<?php bap_render_header_menu( 'primary' ); ?>
			<?php else : ?>
				<?php foreach ( bap_albums() as $bap_album ) : ?>
					<li>
						<a href="<?php echo esc_url( get_term_link( $bap_album ) ); ?>"<?php echo $bap_current === $bap_album->term_id ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $bap_album->name ); ?></a>
					</li>
				<?php endforeach; ?>
				<?php if ( $bap_pricing_url ) : ?>
					<li><a href="<?php echo esc_url( $bap_pricing_url ); ?>"<?php echo is_page_template( 'page-pricing.php' ) ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'Bảng giá', 'bap-studio-blush' ); ?></a></li>
				<?php endif; ?>
			<?php endif; ?>
			<?php bap_render_header_menu( 'extra' ); ?>
			<li class="nav-cta-item"><a class="btn btn-outline nav-cta" href="<?php echo esc_url( bap_contact_url() ); ?>"<?php echo is_page_template( 'page-contact.php' ) ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'Đặt lịch', 'bap-studio-blush' ); ?> <?php echo bap_blush_arrow(); // phpcs:ignore ?></a></li>
		</ul>
	</nav>
</header>

<main id="main" class="site-main">
