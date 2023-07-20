<?php
/**
 * Archive pages ajax Pagination with Load More button.
 * 
 * @package astrodj
 */

/**
 * Enqueue JavaScript.
 */
function astrodj_archive_ajax_scripts() {
	if( ! is_singular() ) {
    wp_enqueue_script( 'astrodj-archive-ajax', get_template_directory_uri() . '/js/archive-ajax.inc.js', array( 'jquery' ), '1.0.0', true );
		wp_localize_script( 'astrodj-archive-ajax', 'archive_ajax',
			array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'site_url' => site_url( '/' ),
        'last_page' => astrodj_last_page(),
        'home' => is_home(), // fixed imposible return sticky post from WP_Query (on Home page)
        'site_name' => get_bloginfo( 'name', 'display' ),
        'site_description' => get_bloginfo( 'description', 'display' ),
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'astrodj_archive_ajax_scripts' );

/**
 * Return maximum number of pages.
 */
function astrodj_last_page() {
  global $wp_query;

  return $wp_query->max_num_pages;
}

/**
 * Count of Posts.
 */
function astrodj_post_count() {
  global $wp_query;
  $not_visible = false;

  $post_count = $wp_query->post_count; // count of posts on page
  $max_num_pages = $wp_query->max_num_pages; // count of all pages
  $all_posts = $post_count * $max_num_pages;
  $posts_per_page = $wp_query->query_vars['posts_per_page'];
  $not_visible = $all_posts <= $posts_per_page ? true : false;

  return $not_visible;
}

/**
 * Display Load More Button.
 */
function astrodj_load_more_button() {
  // if we are on the last page or if the number of posts less than posts_per_page, do not display the button
  if ( astrodj_last_page() == get_query_var( 'paged' ) || astrodj_post_count() ) return;
  ?>
    <div class="load-more">
      <div class="load-more__loader">
        <?php if ( ! is_singular() && ! is_post_type_archive() ) : ?>
          <div class="loader-placeholder loader"><div></div><div></div><div></div></div>
        <?php elseif ( is_post_type_archive() ) : ?>
          <svg class="icon icon-loader-ring loader" viewBox="0 0 32 32">
          <path d="M16,5.2c5.9,0,10.8,4.8,10.8,10.8S21.9,26.8,16,26.8S5.2,21.9,5.2,16H0c0,8.8,7.2,16,16,16s16-7.2,16-16S24.8,0,16,0V5.2z"/>
          </svg>
        <?php endif; ?>
      </div>
      <div class="load-more__btn" data-page="<?php echo astrodj_check_paged(1); ?>" data-archive="<?php echo $_SERVER["REQUEST_URI"]; ?>">
        <span>Загрузить еще</span>
      </div>
    </div>
  <?php
}

/**
 * Wrapper function for all Load More functionality for use in templates files
 */
function astrodj_load_more_pugination() {
  echo '<div class="pagination-wrapper">';

    // change $_SERVER["REQUEST_URI"] for Load More data-archive
    do_action( 'astrodj_load_more_pugination_button_request_uri' );

    astrodj_load_more_button();

    // change $_SERVER["REQUEST_URI"] for Pagination buttons url
    do_action( 'astrodj_load_more_pugination_links_request_uri' );
    
    astrodj_posts_pagination(	[
      'next_text' => astrodj_get_svg( array( 'icon' => 'arrow-left' ) ) . __( '<span>Новее</span>', 'astrodj' ), 
      'prev_text' => __( '<span>Старее</span>', 'astrodj' ) . astrodj_get_svg( array( 'icon' => 'arrow-right' ) ) 
    ]	);

  echo '</div>';
  // echo '<pre>';
  // global $wp_query; 
  // var_dump($wp_query);
  // echo '</pre>';
}

/**
 * Ajax action.
 */
add_action( 'wp_ajax_astrodj_archive_pagination', 'astrodj_archive_pagination_cb' );
add_action( 'wp_ajax_nopriv_astrodj_archive_pagination', 'astrodj_archive_pagination_cb' );

function astrodj_archive_pagination_cb() {
  global $paged; // for pagination, it will be rewrite with our count

  $paged = $_POST['page'] ? $_POST['page'] + 1 : $_POST['paged'];
  $archive = $_POST['archive'];
  $load_more_btn = ( bool ) $_POST['load_more_btn'];

  // create widget filter query and for search (?s=) 
  if ( false !== strpos( $archive, '?' ) && false === strpos( $archive, '?s=' ) ) {
    $filter_query = substr( $archive, strpos( $archive, '?' ) + 1 );
    $archive = substr( $archive, 0, strpos( $archive, '?' ) );
  } elseif ( false !== strpos( $archive, '?s=' ) ) {
    $filter_query = substr( $archive, strpos( $archive, '?' ) + 1 );
  } else {
    $filter_query = '';
  }

  $args = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'post__not_in'   => get_option( 'sticky_posts' ), // if Theme use post Sticky
    'paged'          => $paged,
  );

  // global $wp_query;
  // echo '<pre>';
  // var_dump(get_option( 'sticky_posts' ));
  // echo '</pre>';

  if ( $archive != '0' && false === strpos( $archive, '?s=' ) ) {
    // $args for archive pages
    $archVal = explode( '/', $archive );
    // print_r($archVal);
    
    if ( in_array( "category", $archVal ) ) {
      $type = "category_name";
      $currKey = array_keys( $archVal, "category" );
      // var_dump( $currKey );
      $nextKey = $currKey[0] + 1;
      $value = $archVal[ $nextKey ];

      // echo $value;
      $args[ $type ] = $value;
    }

    if( in_array( "tag", $archVal ) ) {
      $type = "tag";
      $currKey = array_keys( $archVal, "tag" );
      $nextKey = $currKey[0] + 1;
      $value = $archVal[ $nextKey ];

      $args[ $type ] = $value;
    }

    if ( in_array( "author", $archVal ) ) {
      $type = "author";
      $currKey = array_keys( $archVal, "author" );
      $nextKey = $currKey[0] + 1;
      $value = $archVal[ $nextKey ];

      $args[ $type ] = $value;
    }

    if ( in_array( "photo-gallery", $archVal ) ) {
			$type = "post_type";
      $args[ $type ] = 'portfolio';
      $args[ 'posts_per_page' ] = get_option( 'astrodj_portfolio_posts_per_page' );

      // for widget filter
      if ( ! empty( $filter_query ) ) {
        parse_str( $filter_query, $query_array );

        if ( array_key_exists( 'terms', $query_array ) ) {
          $cat_IDs = explode( ',', $query_array['terms'] );

          $args['tax_query'] = array(
            array(
              'taxonomy' => 'portfolio-categories',
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
      }
    }

    if ( in_array( "stock-photography", $archVal ) ) {
			$type = "post_type";
      $args[ $type ] = 'stock';
      $args[ 'posts_per_page' ] = get_option( 'astrodj_portfolio_posts_per_page' );

      // for widget filter
      if ( ! empty( $filter_query ) ) {
        parse_str( $filter_query, $query_array );

        if ( array_key_exists( 'terms', $query_array ) ) {
          $cat_IDs = explode( ',', $query_array['terms'] );

          $args['tax_query'] = array(
            array(
              'taxonomy' => 'stock-categories',
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
      }
    }

    if ( in_array( "cats-photography", $archVal ) ) {
			$type = "post_type";
      $args[ $type ] = 'cats';
      $args[ 'posts_per_page' ] = get_option( 'astrodj_portfolio_posts_per_page' );

      // for widget filter
      if ( ! empty( $filter_query ) ) {
        parse_str( $filter_query, $query_array );

        if ( array_key_exists( 'terms', $query_array ) ) {
          $cat_IDs = explode( ',', $query_array['terms'] );

          $args['tax_query'] = array(
            array(
              'taxonomy' => 'cats-categories',
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
      }
    }
    
    //check page trail and remove "page" value
    if ( in_array( "page", $archVal ) ) {

      $pageVal = explode( 'page', $archive );
      $page_trail = $pageVal[0];
      
    } else {
      $page_trail = $archive;
    }
  } elseif ( false !== strpos( $archive, '?s=' ) ) {
    // $args for search query
    // preg_match( '/^.*(\/?s=)(.*)?$/', $archive, $matches );
    // print_r($archive);
    // var_dump($matches);
    // $search_query = $matches[2];
    parse_str( $filter_query, $query_array );
    $args['s'] = $query_array['s'];
    $page_trail = '/?s=' . $query_array['s'];

    // to avoid conflict in Load More button with Widget Filter case. 
    $filter_query = '';
    // $page_trail = '/';
  } else {
    $page_trail = '/';
  }

  // widget filter query for data-page
  $filter_query = ! empty( $filter_query ) ? '?' . $filter_query : '';

  $load_post = new WP_Query( $args );
  // print_r($page_trail);
  // echo '<pre>';
  // var_dump($load_post->query_vars['post_type']);
  // echo '</pre>';

  // used in the_posts_pagination
  $GLOBALS['wp_query'] = $load_post;

  add_filter( 'editor_max_image_size', 'post-thumbnail' );

  if( $load_post->have_posts() ) :
    
    // headers for Archives, Search results and Home
    if ( ( $load_post->query_vars['category_name'] !== '' || $load_post->query_vars['tag'] !== '' ) && ! $load_more_btn ) : ?>
      <header class="page-header">
        <?php if ( is_category() ) : ?>
					<span>Рубрика</span>
				<?php elseif ( is_tag() ) : ?>
					<span>Тэг</span>
				<?php endif;
        the_archive_title( '<h1 class="page-title">', '</h1>' );
        the_archive_description( '<div class="archive-description">', '</div>' );
        ?>
      </header><!-- .page-header -->
    <?php
    elseif ( $load_post->query_vars['s'] !== '' && ! $load_more_btn ) : ?>
      <header class="page-header-search">
				<h1 class="page-title">
					<?php printf( esc_html__( 'Результаты поиска для: %s', 'astrodj' ), '<span>' . get_search_query() . '</span>' ); ?>
				</h1>
        <span>(<?php echo $load_post->found_posts; ?>)</span>
			</header><!-- .page-header -->
    <?php
    elseif ( ! $load_more_btn && $load_post->query_vars['post_type'] == 'post' ) : ?>
      <header class="page-header-index">
        <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
        <div>Последние записи</div>
      </header>
    <?php
    endif;

    // if is CPT Archives
    if ( $load_post->query_vars['post_type'] == 'portfolio' || $load_post->query_vars['post_type'] == 'stock' || $load_post->query_vars['post_type'] == 'cats' ) :

      if ( $load_post->query_vars['paged'] == 1 ) :

        echo '<div class="portfolio-content page-limit" data-page="' . $page_trail . $filter_query . '">';

      else :

        echo '<div class="portfolio-content page-limit" data-page="' . $page_trail . 'page/' . $paged . '/' . $filter_query . '">';

      endif;

    // if is Search results
    elseif ( $load_post->query_vars['s'] !== '' ) :

      if ( $load_post->query_vars['paged'] == 1 ) :

        echo '<div class="page-limit" data-page="/?s=' . $load_post->query_vars['s'] . '">';

      else :

        echo '<div class="page-limit" data-page="/page/' . $paged . '/?s=' . $load_post->query_vars['s'] . '">';

      endif;

    // for standard Post Archives and Home
    else :

      if ( $load_post->query_vars['paged'] == 1 ) :

        echo '<div class="page-limit" data-page="' . $page_trail . '">';

      else :

        echo '<div class="page-limit" data-page="' . $page_trail . 'page/' . $paged . '/">';

      endif;

    endif;

    while( $load_post->have_posts() ) : 
      $load_post->the_post();
      
      if ( $load_post->query_vars['post_type'] == 'portfolio' || $load_post->query_vars['post_type'] == 'stock' ):

        get_template_part( 'template-parts/portfolio', 'fullpage' );

      elseif ( $load_post->query_vars['post_type'] == 'cats' ):

        get_template_part( 'template-parts/portfolio', 'iframe' );

      else :

        get_template_part( 'template-parts/content', get_post_type() );
  
      endif;
    
    endwhile;

    echo '</div><!-- .page-limit -->';

    // for pagination from admin ajax
    //$_SERVER["REQUEST_URI"] = $page_trail; // replace "/wp-admin/admin-ajax.php" with "/blog/" (for ex.)
    //$_SERVER["REQUEST_URI"] = $page_trail . $filter_query; // for widget filter

    echo '<div class="pagination-wrapper">';
  
    $_SERVER["REQUEST_URI"] = $page_trail . $filter_query; // for Load More button in Widget Filter case only.

    astrodj_load_more_button();

    $_SERVER["REQUEST_URI"] = $page_trail; // For normal pagination.
    
    astrodj_posts_pagination(	[
      'next_text' => astrodj_get_svg( array( 'icon' => 'arrow-left' ) ) . __( '<span>Новее</span>', 'astrodj' ), 
      'prev_text' => __( '<span>Старее</span>', 'astrodj' ) . astrodj_get_svg( array( 'icon' => 'arrow-right' ) ) 
    ]	);
  
    echo '</div>';

  else :

    get_template_part( 'template-parts/content', 'none' );
      
  endif;

  remove_filter( 'editor_max_image_size', 'post-thumbnail' );

  wp_reset_postdata();

  wp_die();
}

function astrodj_check_paged( $num = null ) {

  $output = '';

  if ( is_paged() ) { $output = 'page/' . get_query_var( 'paged' ) . '/'; }

  if ( $num == 1 ) {
      $paged = ( get_query_var( 'paged' ) == 0 ? 1 : get_query_var( 'paged' ) );
      return $paged;
  } else {
      return $output;
  }

}
