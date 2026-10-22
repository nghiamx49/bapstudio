<?php
/**
 * B.A.P Studio theme bootstrap.
 *
 * @package bap-studio
 */

defined( 'ABSPATH' ) || exit;

define( 'BAP_VERSION', '1.0.0' );
define( 'BAP_DIR', get_template_directory() );
define( 'BAP_URI', get_template_directory_uri() );

require BAP_DIR . '/inc/helpers.php';
require BAP_DIR . '/inc/setup.php';
require BAP_DIR . '/inc/assets.php';
require BAP_DIR . '/inc/taxonomies.php';
require BAP_DIR . '/inc/post-types.php';
require BAP_DIR . '/inc/acf-fields.php';
require BAP_DIR . '/inc/admin.php';
require BAP_DIR . '/inc/rest-load-more.php';
require BAP_DIR . '/inc/contact-form.php';
