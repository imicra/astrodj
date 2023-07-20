<?php
/**
 * Header Image Ajax Loading.
 * 
 * @package astrodj
 */

 /**
 * Ajax action.
 */
add_action( 'wp_ajax_astrodj_header_archive', 'astrodj_header_archive_cb' );
add_action( 'wp_ajax_nopriv_astrodj_header_archive', 'astrodj_header_archive_cb' );

function astrodj_header_archive_cb() {
  $link = ! empty( $_POST['link'] ) ? esc_attr( $_POST['link'] ) : false;

  $slug = $link ? wp_basename( $link ) : false;
  $cat = get_category_by_slug( $slug );
  $cat_ID = $cat->cat_ID;

  $attachment_ID = get_term_meta( $cat_ID, 'taxonomy-image-id', true );

  if ( empty( $attachment_ID ) ) {
    return;
  }

  $lq_url = wp_get_attachment_image_url( $attachment_ID, 'medium_large' );
  $hq_url = wp_get_attachment_image_url( $attachment_ID, 'full' );

  $lq_style_attr = sprintf( 'style="background-image: url(%s);"', $lq_url );

  if ( ! empty( $lq_url ) ) :
    $output = '<div class="site-header__bg--lq"' . $lq_style_attr . '></div>';
    $output .= '<div id="bgLazy" class="site-header__bg--hq" data-src="' . $hq_url . '"></div>';

    echo $output;

  else :
    echo 0;
  endif;

  wp_die();
}