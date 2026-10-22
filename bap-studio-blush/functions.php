<?php
/**
 * B.A.P Studio Blush: child theme bootstrap.
 *
 * Content model, Studio settings (ACF), album taxonomy, pricing packages,
 * the REST "load more" endpoint and the Contact Form 7 integration all come
 * from the parent theme (bap-studio). This theme only changes the look:
 * templates, styles, fonts and animations.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

define( 'BAP_BLUSH_DIR', get_stylesheet_directory() );
define( 'BAP_BLUSH_URI', get_stylesheet_directory_uri() );

require BAP_BLUSH_DIR . '/inc/helpers.php';
require BAP_BLUSH_DIR . '/inc/assets.php';
