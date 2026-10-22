<?php
/**
 * Template Name: Bảng giá (Pricing)
 *
 * Page intro from the editor, then every pricing package.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section pricing-page">
	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<header class="section-head" data-reveal>
				<p class="eyebrow"><?php esc_html_e( 'Dịch vụ', 'bap-studio' ); ?></p>
				<h1 class="section-title"><?php the_title(); ?></h1>
				<?php if ( get_the_content() ) : ?>
					<div class="lead entry"><?php the_content(); ?></div>
				<?php endif; ?>
			</header>
		<?php endwhile; ?>
		<?php get_template_part( 'template-parts/pricing-cards' ); ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/contact' );
get_footer();
