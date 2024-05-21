<?php
/**
 * Ajax save post meta in SEO metabox.
 * 
 * @package astrodj
 */

function astrodj_seo_metabox_submit_cb() {
  $prefix = 'astrodj_';
  $id = intval( $_POST['id'] );
  $title = wp_strip_all_tags( $_POST['title'] );
  $description = wp_strip_all_tags( $_POST['description'] );
  $result = false;

  if ( ! empty( $title ) ) {
    update_post_meta( $id, $prefix . 'seo_metabox_title', $title );
    $result = true;
  } else {
    delete_post_meta( $id, $prefix . 'seo_metabox_title' );
    $result = true;
  }
  if ( ! empty( $description ) ) {
    update_post_meta( $id, $prefix . 'seo_metabox_description', $description );
    $result = true;
  } else {
    delete_post_meta( $id, $prefix . 'seo_metabox_description' );
    $result = true;
  }

  wp_send_json( [
    'success' => $result
  ] );
}
add_action( 'wp_ajax_astrodj_seo_metabox_submit', 'astrodj_seo_metabox_submit_cb' );
