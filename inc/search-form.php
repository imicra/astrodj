<?php
/**
 * Search Form Ajax Processing.
 * 
 * @package astrodj
 */

/**
 * Ajax action.
 */
add_action( 'wp_ajax_astrodj_search_form', 'astrodj_search_form_cb' );
add_action( 'wp_ajax_nopriv_astrodj_search_form', 'astrodj_search_form_cb' );

function astrodj_search_form_cb() {
  $value = wp_strip_all_tags( $_POST['value'] );
  $search_trail = '/?s=' . $value;

  $args = array(
    'post_type'      => 'post',
    'posts_per_page' => get_option( 'posts_per_page' ),
    'post__not_in'   => get_option( 'sticky_posts' ),
    'post_status'    => 'publish',
    's'              => $value,
  );

  query_posts( $args );

  add_filter( 'editor_max_image_size', 'astrodj_blog_thumbnail' );

  if ( have_posts() ) : ?>

    <header class="page-header-search">
      <h1 class="page-title">
        <?php printf( esc_html__( 'Результаты поиска для: %s', 'astrodj' ), '<span>' . get_search_query() . '</span>' ); ?>
      </h1>
      <span>(<?php
        global $wp_query;

        echo $wp_query->found_posts;
        ?>)</span>
    </header><!-- .page-header -->

    <?php
    echo '<div class="page-limit" data-page="' . $search_trail . '">';

    while ( have_posts() ) :
      the_post();

      get_template_part( 'template-parts/content' );

    endwhile;

    echo '</div><!-- .page-limit -->';

    $_SERVER["REQUEST_URI"] = $search_trail; // replace "/wp-admin/admin-ajax.php" with "/?s=keyword"

    astrodj_load_more_pugination();

  else : ?>
  
    <header class="page-header-search">
      <h1 class="page-title">
        <?php printf( esc_html__( 'Ничего не найдено по &ldquo;%s&rdquo;', 'astrodj'), get_search_query() ); ?>
      </h1>
    </header><!-- .page-header -->
    <div class="page-content-search">
      <p><?php esc_html_e( 'Попробуйте другое сочетание.', 'astrodj' ); ?></p>
      <?php get_search_form(); ?>
    </div><!-- .page-content -->
    <div class="page-limit" data-page="<?php echo $search_trail; ?>">
      <h2 class="page-title secondary-title"><?php esc_html_e( 'Последние записи:', 'astrodj' ); ?></h2>
      <?php
      // Get the latest posts
      $args_latest_posts = array(
        'posts_per_page' => 5,
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'post__not_in'   => get_option( 'sticky_posts' ),
      );

      $latest_posts_query = new WP_Query( $args_latest_posts );

      // The Loop
      if ( $latest_posts_query->have_posts() ) {
          while ( $latest_posts_query->have_posts() ) {
            $latest_posts_query->the_post();

            // Get the standard index page content
            get_template_part( 'template-parts/content', get_post_format() );
          }
      }

    echo '</div><!-- .page-limit -->';

    /* Restore original Post Data */
    wp_reset_postdata();

  endif;

  remove_filter( 'editor_max_image_size', 'astrodj_blog_thumbnail' );

  wp_reset_query();

  wp_die();
}