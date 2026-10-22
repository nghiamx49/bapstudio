<?php
/**
 * Template Name: Bảng giá (Pricing)
 *
 * Page intro from the editor, then every pricing package, then the contact form.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section pricing-page">
	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part(
				'template-parts/section-head',
				null,
				array(
					'kicker' => __( 'Dịch vụ', 'bap-studio-blush' ),
					'title'  => get_the_title(),
					'aside'  => has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : '',
					'tag'    => 'h1',
				)
			);
			if ( trim( get_the_content() ) ) :
				?>
				<div class="entry pricing-intro" data-reveal><?php the_content(); ?></div>
				<?php
			endif;
		endwhile;
		get_template_part( 'template-parts/pricing-cards' );
		?>
	</div>
</section>
<?php
get_template_part( 'template-parts/contact' );
get_footer();
