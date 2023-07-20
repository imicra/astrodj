<?php
/**
 * Frontpage ajax initial posts query.
 * 
 * @package astrodj
 */

/**
 * Ajax action.
 */
function astrodj_frontpage_init_query_cb() {
  $posts_per_page = ! empty( $_POST['posts_per_page'] ) ? $_POST['posts_per_page'] : 2;

  $posts_per_page = $posts_per_page - 1;

  $args = array(
    'posts_per_page' => $posts_per_page,
    'post_status'    => 'publish',
    'offset' =>      1
  );

  $latest_posts_query = new WP_Query( $args );

  // The Loop
  if ( $latest_posts_query->have_posts() ) {
      while ( $latest_posts_query->have_posts() ) {
        $latest_posts_query->the_post();

        // Get the standard index page content
        get_template_part( 'template-parts/content', get_post_format() );
      }
  }

  wp_reset_postdata();

  wp_die();
}
add_action( 'wp_ajax_astrodj_frontpage_init_query', 'astrodj_frontpage_init_query_cb' );
add_action( 'wp_ajax_nopriv_astrodj_frontpage_init_query', 'astrodj_frontpage_init_query_cb' );
