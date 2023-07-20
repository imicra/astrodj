<?php
/**
 * Archive pages links for ajax loading.
 * 
 * @package astrodj
 */

 /**
 * Ajax action.
 */
add_action( 'wp_ajax_astrodj_archive_links', 'astrodj_archive_links_cb' );
add_action( 'wp_ajax_nopriv_astrodj_archive_links', 'astrodj_archive_links_cb' );

function astrodj_archive_links_cb() {
  $link = ! empty( $_POST['link'] ) ? esc_attr( $_POST['link'] ) : false;

  $args = array(
    'posts_per_page' => get_option( 'posts_per_page' ),
    'post__not_in'   => get_option( 'sticky_posts' ),
    'post_status'    => 'publish',
  );

  if ( $link ) {
    $archVal = explode( '/', $link );
    // print_r($archVal);
    
    if ( in_array( "category", $archVal ) ) {
      $type = "category_name";
      $currKey = array_keys( $archVal, "category" );
      // var_dump( $currKey );
      $nextKey = $currKey[0] + 1;
      $value = $archVal[ $nextKey ];

      // echo $value;
      $args[ $type ] = $value;

      $pagination_link = '/blog/category/' . $value . '/';
    }

    if( in_array( "tag", $archVal ) ) {
      $type = "tag";
      $currKey = array_keys( $archVal, "tag" );
      $nextKey = $currKey[0] + 1;
      $value = $archVal[ $nextKey ];

      $args[ $type ] = $value;

      $pagination_link = '/blog/tag/' . $value . '/';
    }

    if ( in_array( "author", $archVal ) ) {
      $type = "author";
      $currKey = array_keys( $archVal, "author" );
      $nextKey = $currKey[0] + 1;
      $value = $archVal[ $nextKey ];

      $args[ $type ] = $value;

      $pagination_link = '/author/' . $value . '/';
    }
  }

  query_posts( $args );

  add_filter( 'editor_max_image_size', 'astrodj_blog_thumbnail' );

  if ( have_posts() ) : ?>

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
    echo '<div class="page-limit" data-page="' . $pagination_link . '">';

    while ( have_posts() ) :
      the_post();

      get_template_part( 'template-parts/content', get_post_type() );

    endwhile;

    echo '</div><!-- .page-limit -->';

    $_SERVER["REQUEST_URI"] = $pagination_link; // replace "/wp-admin/admin-ajax.php" with "/category/blabla"

    astrodj_load_more_pugination();

  else :

    get_template_part( 'template-parts/content', 'none' );

  endif;

  remove_filter( 'editor_max_image_size', 'astrodj_blog_thumbnail' );

  wp_reset_query();

  wp_die();
}