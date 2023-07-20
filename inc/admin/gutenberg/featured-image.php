<?php
/**
 * Featured Image Format.
 *
 * @package astrodj
 */

// register block type
function astrodj_register_featured_image_format() {
  wp_enqueue_script(
      'featured-image-format-js',
      get_template_directory_uri() . '/js/featured-image.inc.js',
      array( 'wp-compose', 'wp-element', 'wp-data', 'wp-hooks' )
  );

  register_block_type( 'enchance-featured-image/featured-image-format', array(
    'editor_script' => 'featured-image-format-js'
  ) );
}
add_action( 'enqueue_block_editor_assets', 'astrodj_register_featured_image_format' );

// register custom meta tag field
function astrodj_register_featured_image_meta() {
  register_post_meta( 'post', 'featured_image_format_meta', array(
      'show_in_rest' => true,
      'single' => true,
      'type' => 'string',
  ) );
}
add_action( 'init', 'astrodj_register_featured_image_meta' );