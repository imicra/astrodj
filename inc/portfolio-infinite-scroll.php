<?php
/**
 * Portfolio Ajax Infinite Scroll Loader.
 * 
 * @package astrodj
 */

/**
 * Display Load More Previous Posts.
 */
function astrodj_portfolio_more_prev_posts() {
  if ( ! get_previous_post_link() ) return;
  ?>
  <div id="loader_wrapper">
    <div class="loader_inner">
      <div class="portfolio-content"></div>
      <?php $previous_post = get_previous_post(); ?>
      <div id="loader_container" data-previous-id="<?php echo $previous_post->ID; ?>" 
      data-this-id="<?php the_ID(); ?>" data-cpt="<?php echo get_post_type(); ?>">
        <?php echo astrodj_get_svg( array( 'icon' => 'loader-ring' ) ); ?>
      </div>
    </div>
  </div><!-- #loader_wrapper -->
  <?php
}

/**
 * Ajax action.
 */
add_action( 'wp_ajax_astrodj_portfolio_infinite', 'astrodj_portfolio_infinite_cb' );
add_action( 'wp_ajax_nopriv_astrodj_portfolio_infinite', 'astrodj_portfolio_infinite_cb' );

function astrodj_portfolio_infinite_cb() {
  $post_ID = ! empty( $_POST['post_id'] ) ? $_POST['post_id'] : '';
  $post_type = ! empty( $_POST['post_type'] ) ? $_POST['post_type'] : '';

  // calculate offset
  $position_query = array(
    'post_type'              => $post_type,
    'orderby'                => 'date',
    'order'                  => 'DESC',
    'posts_per_archive_page' => -1
  );

  $position_posts = get_posts( $position_query );
  $count = 0;

  foreach ( $position_posts as $position_post ) {
    $count++;
    if ( $position_post->ID == $post_ID ) {
      $position = $count;
      break;
    }
  }

  $portfolio_posts_per_page = get_option( 'astrodj_portfolio_posts_per_page' );

  // WP_Query
  $args = array(
    'post_type'      => $post_type,
    'post_status'    => 'publish',
    'posts_per_page' => $portfolio_posts_per_page,
    'offset'         => $position
  );

  $query = new WP_Query( $args );

  $GLOBALS['wp_query'] = $query;

  // add_filter( 'editor_max_image_size', 'astrodj_blog_thumbnail' );

  while ( $query->have_posts() ) :
    $query->the_post();

    get_template_part( 'template-parts/portfolio', 'fullpage' );

  endwhile;

  // remove_filter( 'editor_max_image_size', 'astrodj_blog_thumbnail' );

  wp_reset_postdata();

  wp_die();
}

/**
 * Enqueue JavaScript
 */
add_action( 'wp_enqueue_scripts', 'astrodj_portfolio_infinite_script' );
function astrodj_portfolio_infinite_script() {
	if ( is_singular( array( 'portfolio', 'stock' ) ) ) {
    wp_enqueue_script( 'astrodj-portfolio-infinite', get_template_directory_uri() . '/js/portfolio-infinite.inc.js', array( 'jquery' ), _S_VERSION, true );
    wp_localize_script( 'astrodj-portfolio-infinite', 'portfolio_data',
      array(
        'rest_url' => rest_url( 'wp/v2/' . get_post_type() . '/'),
        'ajax_url' => admin_url( 'admin-ajax.php' ),
      )
    );
  }
}