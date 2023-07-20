<?php
/**
 * Single Post ajax Navigation.
 * 
 * Single Template Older and Newer Posts Load More with REST API.
 * 
 * @package astrodj
 */

 /**
 * Display Load More Previous Posts. Used in single.php.
 */
function astrodj_single_more_prev_posts() {
  if ( is_sticky() || ! get_previous_post_link() ) return;
  ?>
    <div class="astrodj-post-navigation prev">
      <div class="astrodj-post-navigation__wrapper">
        <?php $previous_post = get_previous_post(); ?>
        <div class="astrodj-post-navigation__load--prev">
          <div class="astrodj-post-navigation__previous" data-id="<?php echo $previous_post->ID; ?>"></div>
          <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
        </div>
      </div>
    </div>
  <?php
}

 /**
 * Display Load More Next Posts. Used in single.php.
 */
function astrodj_single_more_next_posts() {
  if ( is_sticky() || ! get_next_post_link() ) return;
  ?>
    <div class="astrodj-post-navigation next">
      <div class="astrodj-post-navigation__wrapper">
        <?php $next_post = get_next_post(); ?>
        <div class="astrodj-post-navigation__load--next">
          <div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>
          <div class="astrodj-post-navigation__next" data-id="<?php echo $next_post->ID; ?>"></div>
        </div>
      </div>
    </div>
  <?php
}

/* 
 * Add fields to the REST API response
 */
add_action( 'rest_api_init', 'astrodj_previouse_single_register_fields' );
function astrodj_previouse_single_register_fields() {
  register_rest_field( array( 'post', 'portfolio', 'stock' ),
    'previous_post_ID',
    array(
      'get_callback'    => 'astrodj_get_previous_post_ID',
      'update_callback' => null,
      'schema'          => null,
    )
  );
  register_rest_field( array( 'post', 'portfolio', 'stock' ),
    'next_post_ID',
    array(
      'get_callback'    => 'astrodj_get_next_post_ID',
      'update_callback' => null,
      'schema'          => null,
    )
  );
  register_rest_field( 'post',
    'astrodj_blog_page',
    array(
      'get_callback'    => 'astrodj_post_blog_page',
      'update_callback' => null,
      'schema'          => null,
    )
  );
  register_rest_field( 'post',
    'astrodj_date',
    array(
      'get_callback'    => 'astrodj_get_post_date_format',
      'update_callback' => null,
      'schema'          => null,
    )
  );
}

function astrodj_get_previous_post_ID() {
  if ( ! empty( get_previous_post() ) ) :
    return get_previous_post()->ID;
  endif;
}

function astrodj_get_next_post_ID() {
  if ( ! empty( get_next_post() ) ) :
    return get_next_post()->ID;
  endif;
}

function astrodj_get_post_date_format() {
	return get_the_date( 'j M Y' );
}

function astrodj_post_blog_page() {
  return astrodj_post_archive_page( 'post' );
}

/**
 * Enqueue JavaScript
 */
add_action( 'wp_enqueue_scripts', 'astrodj_previouse_single' );
function astrodj_previouse_single() {
	if ( is_single() && ! is_singular( array( 'portfolio', 'stock', 'cats' ) ) ) {
    wp_enqueue_script( 'astrodj-single-nav', get_template_directory_uri() . '/js/single-nav.inc.js', array( 'jquery' ), _S_VERSION, true );
		wp_localize_script( 'astrodj-single-nav', 'nav_data',
			array(
        'rest_url' => rest_url( 'wp/v2/posts/')
			)
		);
	}
}
