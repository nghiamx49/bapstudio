<?php
/**
 * Generic page.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

get_header();
while ( have_posts() ) :
	the_post();
	?>
	<article class="section">
		<div class="container narrow">
			<h1 class="section-title"><?php the_title(); ?></h1>
			<div class="entry"><?php the_content(); ?></div>
		</div>
	</article>
	<?php
endwhile;
get_footer();
