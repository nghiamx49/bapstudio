<?php
/**
 * Album page: intro, album tabs and a grid of the album's photos with "load more".
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

get_header();

$bap_term  = get_queried_object();
$bap_intro = bap_field( 'intro', $bap_term, $bap_term->description );
global $wp_query;
?>
<section class="section album-page">
	<div class="container">
		<header class="album-head">
			<div data-reveal>
				<p class="eyebrow"><?php esc_html_e( 'Thư viện ảnh', 'bap-studio' ); ?></p>
				<h1 class="section-title"><?php single_term_title(); ?></h1>
			</div>
			<?php get_template_part( 'template-parts/album-tabs' ); ?>
		</header>
		<?php if ( $bap_intro ) : ?>
			<p class="lead album-intro" data-reveal><?php echo esc_html( $bap_intro ); ?></p>
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>
			<div class="gallery-grid" data-gallery>
				<?php
				while ( have_posts() ) :
					the_post();
					bap_render_photo_tile( get_the_ID() );
				endwhile;
				?>
			</div>
			<?php if ( $wp_query->max_num_pages > 1 ) : ?>
				<p class="load-more-wrap">
					<button type="button" class="btn btn-ghost" data-load-more data-album="<?php echo (int) $bap_term->term_id; ?>" data-page="1" data-pages="<?php echo (int) $wp_query->max_num_pages; ?>">
						<?php esc_html_e( 'Xem thêm ảnh', 'bap-studio' ); ?>
					</button>
				</p>
				<noscript><?php the_posts_pagination(); ?></noscript>
			<?php endif; ?>
		<?php else : ?>
			<p class="empty"><?php esc_html_e( 'Album này chưa có ảnh.', 'bap-studio' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
