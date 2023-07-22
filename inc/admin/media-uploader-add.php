<?php
/**
 * Add an image Media Uploader field to the Category.
 *
 * @package astrodj
 */

$taxnames = array(
  'category',
  'portfolio-categories',
  'stock-categories',
  'cats-categories',
);

foreach ( $taxnames as $taxname ) {
  /**
   * Add a form field in the new Taxonomy page.
   */
  add_action("{$taxname}_add_form_fields", 'add_taxonomy_image', 10, 2 );
  /**
   * Edit the form field.
   */
  add_action("{$taxname}_edit_form_fields", 'update_taxonomy_image', 10, 2 );
  /**
   * Save the form field.
   */
  add_action("create_{$taxname}", 'save_taxonomy_image', 10, 2 );
  /**
   * Update the form field value.
   */
  add_action("edited_{$taxname}", 'updated_taxonomy_image', 10, 2 );
  /**
   * Image column added to category admin screen.
   */
  add_filter( "manage_edit-{$taxname}_columns", 'astrodj_register_taxonomy_column' );
  /**
   * Image column value added to category admin screen.
   */
  add_action( "manage_{$taxname}_custom_column", 'astrodj_add_value_taxonomy_column' , 10, 3);
}

/**
 * Add Image columns on Custom Posts screen
 */
$post_types = array(
  'post',
  'portfolio',
  'stock',
  'cats',
  'archive'
);

foreach ( $post_types as $post_type ) {
  /**
   * Image column added to category admin screen.
   */
  add_filter( "manage_{$post_type}_posts_columns", 'astrodj_register_custom_post_column' );
  /**
   * Image column value added to category admin screen.
   */
  add_action( "manage_{$post_type}_posts_custom_column", 'astrodj_add_value_custom_post_column' , 10, 2);
}