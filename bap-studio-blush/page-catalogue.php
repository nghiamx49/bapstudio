<?php
/**
 * Template Name: Bộ sưu tập (Catalogue)
 *
 * Page intro from the editor, then every album that has photos as hairline columns,
 * then the contact form.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

get_header();
$bap_terms = bap_albums_with_photos();
?>
<section class="section catalogue-page">
	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part(
				'template-parts/section-head',
				null,
				array(
					'kicker' => sprintf( /* translators: %d: number of albums */ _n( 'Thư viện ảnh · %d album', 'Thư viện ảnh · %d album', count( $bap_terms ), 'bap-studio-blush' ), count( $bap_terms ) ),
					'title'  => get_the_title(),
					'aside'  => has_excerpt() ? wp_strip_all_tags( get_the_excerpt() ) : '',
					'tag'    => 'h1',
				)
			);
			if ( trim( get_the_content() ) ) :
				?>
				<div class="entry catalogue-intro" data-reveal><?php the_content(); ?></div>
				<?php
			endif;
		endwhile;
		?>

		<?php if ( $bap_terms ) : ?>
			<div class="catalogue-grid hairline-cols" data-stagger>
				<?php
				foreach ( $bap_terms as $bap_i => $bap_term ) {
					get_template_part( 'template-parts/album-card', null, array( 'term' => $bap_term, 'index' => $bap_i ) );
				}
				?>
			</div>
		<?php else : ?>
			<p class="empty"><?php esc_html_e( 'Chưa có album nào.', 'bap-studio-blush' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_template_part( 'template-parts/contact' );
get_footer();
