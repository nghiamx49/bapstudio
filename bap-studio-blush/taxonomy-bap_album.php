<?php
/**
 * Album page: editorial header, album tabs, masonry grid with "load more".
 * Tabs (album-tabs.php) and tiles (photo-tile.php) come from the parent theme.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

get_header();

$bap_term  = get_queried_object();
$bap_intro = bap_field( 'intro', $bap_term, $bap_term->description );
global $wp_query;
?>
<section class="section album-page">
	<div class="container">
		<?php
		get_template_part(
			'template-parts/section-head',
			null,
			array(
				'kicker' => sprintf( /* translators: %d: number of photos */ _n( 'Thư viện ảnh · %d ảnh', 'Thư viện ảnh · %d ảnh', (int) $bap_term->count, 'bap-studio-blush' ), (int) $bap_term->count ),
				'title'  => single_term_title( '', false ),
				'aside'  => $bap_intro,
				'tag'    => 'h1',
			)
		);
		?>
		<?php get_template_part( 'template-parts/album-tabs' ); ?>

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
					<button type="button" class="btn btn-outline" data-load-more data-album="<?php echo (int) $bap_term->term_id; ?>" data-page="1" data-pages="<?php echo (int) $wp_query->max_num_pages; ?>"><?php esc_html_e( 'Xem thêm ảnh', 'bap-studio-blush' ); ?></button>
				</p>
				<noscript><?php the_posts_pagination(); ?></noscript>
			<?php endif; ?>
		<?php else : ?>
			<p class="empty"><?php esc_html_e( 'Album này chưa có ảnh.', 'bap-studio-blush' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
