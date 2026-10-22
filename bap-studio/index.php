<?php
/**
 * Fallback template (blog posts, search, other archives).
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section">
	<div class="container narrow">
		<?php if ( have_posts() ) : ?>
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'entry-card' ); ?>>
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php if ( is_singular() ) : ?>
						<div class="entry"><?php the_content(); ?></div>
					<?php else : ?>
						<div class="entry"><?php the_excerpt(); ?></div>
					<?php endif; ?>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Không tìm thấy nội dung.', 'bap-studio' ); ?></p>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
