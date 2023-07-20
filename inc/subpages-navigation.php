<?php
/**
 * Page Navigation Ajax by Subpages.
 * 
 * @package astrodj
 */

/**
 * Ajax action.
 */
add_action( 'wp_ajax_astrodj_subpages_navigation', 'astrodj_subpages_navigation_cb' );
add_action( 'wp_ajax_nopriv_astrodj_subpages_navigation', 'astrodj_subpages_navigation_cb' );

function astrodj_subpages_navigation_cb() {
  $link = ! empty( $_POST['link'] ) ? esc_attr( $_POST['link'] ) : false;
  
  $path = parse_url( $link, PHP_URL_PATH );

  query_posts( array(
		'pagename'   => $path
  ) );

  while ( have_posts() ) :
    the_post();

    get_template_part( 'template-parts/content', 'page' );

    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) :
      comments_template();
    endif;

  endwhile; // End of the loop.

  wp_reset_query();

  wp_die();
}