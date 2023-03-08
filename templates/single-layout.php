<?php
/**
 * The Post Grid Template Full Width
 * @package RT_TPG
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}

global $wp_version;

get_header();

while ( have_posts() ) : the_post();
	the_content();
endwhile; // End of the loop.

get_footer();
