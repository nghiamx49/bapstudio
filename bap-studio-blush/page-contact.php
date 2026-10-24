<?php
/**
 * Template Name: Liên hệ (Contact)
 *
 * The contact section (studio details and booking form) as the whole page.
 * Title and intro come from Studio settings → Contact & footer.
 *
 * @package bap-studio-blush
 */

defined( 'ABSPATH' ) || exit;

get_header();
get_template_part( 'template-parts/contact', null, array( 'is_page' => true ) );
get_footer();
