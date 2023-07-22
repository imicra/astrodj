<?php
/**
 * Widget photo filter ajax.
 * 
 * @package astrodj
 */

/**
 * Ajax action.
 */
add_action( 'wp_ajax_astrodj_widget_filter', 'astrodj_widget_filter_cb' );
add_action( 'wp_ajax_nopriv_astrodj_widget_filter', 'astrodj_widget_filter_cb' );

function astrodj_widget_filter_cb() {
  $cpt = esc_attr( $_POST['cpt'] );
  $cat_IDs = ! empty( $_POST['cat_IDs'] ) ? $_POST['cat_IDs'] : [];
  $order = ! empty( $_POST['order'] ) ? esc_attr( $_POST['order'] ) : '';

  $taxonomy = $cpt . '-categories';
  $portfolio_posts_per_page = get_option( "astrodj_{$cpt}_posts_per_page" );

  $args = array(
    'post_type'      => $cpt,
    'post_status'    => 'publish',
    'posts_per_page' => $portfolio_posts_per_page,
  );

  // CPT Categories
  if ( ! empty( $cat_IDs ) ) {
    $args['tax_query'] = array(
      array(
        'taxonomy' => $taxonomy,
        'field' => 'term_id',
        'terms' => $cat_IDs
      )
    );

    $cat_IDs_path = implode( '%2C', $cat_IDs );
    $quey_cat = 'terms=' . $cat_IDs_path;
  }

  // Shot Date meta
  if ( ! empty( $order ) ) {
    $args['meta_key'] = 'astrodj_portfolio_datetime_timestamp';
    $args['orderby'] = 'meta_value_num';
    $args['order'] = $order;

    $query_order = 'order=' . $order;
  }

  if ( $cpt == 'portfolio' ) {
    $archive_path = '/photo-gallery/';
  } elseif ( $cpt == 'stock' ) {
    $archive_path = '/stock-photography/';
  } elseif ( $cpt == 'cats' ) {
    $archive_path = '/cats-photography/';
  }

  $quey_cat = ! empty( $quey_cat ) ? $quey_cat : '';
  $query_order = ! empty( $query_order ) ? $query_order : '';

  // collect all filter queries
  $query_array = array_diff( array(
    $quey_cat,
    $query_order
  ), array('') );

  $filter_query = implode( '&', $query_array );
  // echo '<pre>';
  // var_dump($filter_query);
  // echo '</pre>';

  $query = new WP_Query( $args );

  // used in the_posts_pagination
  $GLOBALS['wp_query'] = $query;

  add_filter( 'editor_max_image_size', 'astrodj_blog_thumbnail' );

  if( $query->have_posts() ) :

    echo '<div class="portfolio-content page-limit" data-page="' . $archive_path . '?' . $filter_query . '">';

      while ( $query->have_posts() ) :
        $query->the_post();

        if ( $query->query_vars['post_type'] == 'portfolio' || $query->query_vars['post_type'] == 'stock' ):

          get_template_part( 'template-parts/portfolio', 'fullpage' );
  
        elseif ( $query->query_vars['post_type'] == 'cats' ):
  
          get_template_part( 'template-parts/portfolio', 'iframe' );

        endif;

      endwhile;

    echo '</div><!-- .page-limit -->';

    $_SERVER["REQUEST_URI"] = $archive_path . '?' . $filter_query;

    astrodj_load_more_pugination();

  else :

    get_template_part( 'template-parts/content', 'none' );
      
  endif;

  remove_filter( 'editor_max_image_size', 'astrodj_blog_thumbnail' );

  wp_reset_postdata();

  wp_die();
}

/**
 * Ajax action for Count Posts.
 */
add_action( 'wp_ajax_astrodj_widget_filter_count', 'astrodj_widget_filter_count_cb' );
add_action( 'wp_ajax_nopriv_astrodj_widget_filter_count', 'astrodj_widget_filter_count_cb' );

function astrodj_widget_filter_count_cb() {
  $cpt = esc_attr( $_POST['cpt'] );
  $cat_IDs = ! empty( $_POST['cat_IDs'] ) ? $_POST['cat_IDs'] : [];

  $taxonomy = $cpt . '-categories';

  $args = array(
    'post_type' => $cpt,
    'post_status'    => 'publish',
  );

  // CPT Categories
  if ( ! empty( $cat_IDs ) ) {
    $args['tax_query'] = array(
      array(
        'taxonomy' => $taxonomy,
        'field' => 'term_id',
        'terms' => $cat_IDs
      )
    );
  }

  $count_query = new WP_Query( $args );

  echo $count_query->found_posts;

  wp_reset_postdata();

  wp_die();
}

/**
 * Execute Widget Filter when page refresh if url has Query Parameters.
 */
function astrodj_widget_filter_query_init() {
  if ( empty( $_SERVER['QUERY_STRING'] ) ) {
    return;
  }

  // var_dump($_SERVER['QUERY_STRING']);

  // get paged
  $path = $_SERVER["REQUEST_URI"];

  $path_to_arr = explode( '/', $path );
  // var_dump($path_to_arr);

  // $archive_path = '/' . $path_to_arr[1] . '/';

  if ( in_array( 'page', $path_to_arr ) ) {
    $page_key = array_keys( $path_to_arr, 'page' );
    $paged_key = $page_key[0] + 1;
    $paged = $path_to_arr[$paged_key];
  } else {
    $paged = 1;
  }

  $cpt = get_query_var( 'post_type' );
  $portfolio_posts_per_page = get_option( "astrodj_{$cpt}_posts_per_page" );

  $args = array(
    'post_type'      => $cpt,
    'paged'          => $paged,
    'post_status'    => 'publish',
    'posts_per_page' => $portfolio_posts_per_page,
  );

  $filter_query = $_SERVER['QUERY_STRING'];

  parse_str( $filter_query, $query_array );

  if ( array_key_exists( 'terms', $query_array ) ) {
    $cat_IDs = explode( ',', $query_array['terms'] );

    $args['tax_query'] = array(
      array(
        'taxonomy' => $cpt . '-categories',
        'field' => 'term_id',
        'terms' => $cat_IDs
      )
    );
  }

  if ( array_key_exists( 'order', $query_array ) ) {
    $args['meta_key'] = 'astrodj_portfolio_datetime_timestamp';
    $args['orderby'] = 'meta_value_num';
    $args['order'] = $query_array['order'];
  }

  // var_dump($query_array['order']);

  query_posts( $args );

  // Display flter results
  $cat_names = array();
  if ( isset( $cat_IDs ) ) {
    foreach ( $cat_IDs as $cat_ID ) {
      $term = get_term( $cat_ID );
      $name = $term->name;
      $cat_names[] = $name;
    }
  }
  $cat_names_list = implode( '</span>, <span class="block">', $cat_names );

  ?>
  <header id="filter-init">
    <div class="widget-filter__page--inner">
      <span class="title"><?php echo astrodj_get_svg( array( 'icon' => 'equalizer' ) ); ?></span>
      <?php if ( isset( $query_array['order'] ) ) :
        $order = $query_array['order'] == 'DESC' ? 'Сначала новые' : 'Сначала ранние';
      ?>
      <span class="data">Дата съемки: <span class="block"><?php echo $order; ?></span></span>
      <?php endif;
      if ( ! empty( $cat_names_list ) ) : ?>
        <span class="cat">Категории: <span class="block"><?php echo $cat_names_list; ?></span></span>
        <?php global $wp_query; ?>
        <span class="count"><span class="block round"><?php echo $wp_query->found_posts; ?></span> Фото</span>
      <?php endif; ?>
      <div class="reset">Сбросить <?php echo astrodj_get_svg( array( 'icon' => 'close' ) ); ?></div>
    </div>
  </header>
  <?php
}
add_action( 'astrodj_archive_portfolio_before_loop', 'astrodj_widget_filter_query_init' );

/**
 * Filter for pagination buttons and arrows.
 */
function astrodj_posts_pagination_request_uri_widget_filter_query_init() {
  if ( is_post_type_archive() ) :

    $path = $_SERVER["REQUEST_URI"];  // "/photo-gallery/?terms=227&order=DESC" (for ex.)
    $path_to_arr = explode( '/', $path );
    $archive_path = '/' . $path_to_arr[1] . '/';  // "/photo-gallery/" (for ex.)

    $_SERVER["REQUEST_URI"] = $archive_path;

  endif;
}
add_action( 'astrodj_load_more_pugination_links_request_uri', 'astrodj_posts_pagination_request_uri_widget_filter_query_init' );
