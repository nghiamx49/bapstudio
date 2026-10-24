<?php
/**
 * Template Name: Bộ sưu tập (Catalogue)
 *
 * Page intro from the editor, then a card for every album that has photos.
 *
 * @package bap-studio
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
			?>
			<header class="section-head" data-reveal>
				<p class="eyebrow"><?php esc_html_e( 'Thư viện ảnh', 'bap-studio' ); ?></p>
				<h1 class="section-title"><?php the_title(); ?></h1>
				<?php if ( get_the_content() ) : ?>
					<div class="lead entry"><?php the_content(); ?></div>
				<?php endif; ?>
			</header>
		<?php endwhile; ?>

		<?php if ( $bap_terms ) : ?>
			<div class="pinned-grid" data-stagger>
				<?php
				foreach ( $bap_terms as $bap_i => $bap_term ) {
					get_template_part( 'template-parts/album-card', null, array( 'term' => $bap_term, 'index' => $bap_i ) );
				}
				?>
			</div>
		<?php else : ?>
			<p class="empty"><?php esc_html_e( 'Chưa có album nào.', 'bap-studio' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
