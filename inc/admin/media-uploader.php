<?php
/**
 * Add an image Media Uploader field to the Taxonomy.
 * 
 * https://pluginrepublic.com/adding-an-image-upload-field-to-categories/
 *
 * @package astrodj
 */

/**
 * Add a form field in the new Taxonomy page.
 */
function add_taxonomy_image( $taxonomy ) {
  $placeholder = get_stylesheet_directory_uri() . '/images/placeholder_admin.png';
  ?>
  <div class="form-field astrodj-term-group">
    <label for="taxonomy-image-id"><?php _e('Картинка', 'astrodj'); ?></label>
    <input type="hidden" id="taxonomy-image-id" name="taxonomy-image-id" class="custom_media_url" value="">
    <div id="taxonomy-image-wrapper" style="width: 100px; height: 100px;">
      <img data-src="<?php echo $placeholder; ?>" src="<?php echo $placeholder; ?>" style="width: 100px; height: 100px; object-fit: cover;"/>
    </div>
    <p>
      <input type="button" class="button button-secondary tax_media_button" id="tax_media_button" name="tax_media_button" value="<?php _e( 'Add Image', 'astrodj' ); ?>" />
      <input type="button" class="button button-secondary tax_media_remove" id="tax_media_remove" name="tax_media_remove" value="<?php _e( 'Remove Image', 'astrodj' ); ?>" />
   </p>
  </div>
<?php
}

/**
 * Edit the form field.
 */
function update_taxonomy_image( $term, $taxonomy ) {
  $placeholder = get_stylesheet_directory_uri() . '/images/placeholder_admin.png';
  ?>
  <tr class="form-field astrodj-term-group">
    <th scope="row">
      <label for="taxonomy-image-id"><?php _e( 'Картинка', 'astrodj' ); ?></label>
    </th>
    <td>
      <?php $image_id = get_term_meta( $term->term_id, 'taxonomy-image-id', true ); ?>
      <input type="hidden" id="taxonomy-image-id" name="taxonomy-image-id" value="<?php echo $image_id; ?>">
      <div id="taxonomy-image-wrapper" style="width: 100px; height: 100px;">
        <?php 
        if ( $image_id ) {
          $thumbnail = wp_get_attachment_image_src( $image_id, 'thumbnail' );
          $src = $thumbnail[0];
        } else {
          $src = $placeholder;
        }
        ?>
        <img data-src="<?php echo $placeholder; ?>" src="<?php echo $src; ?>" style="width: 100px; height: 100px; object-fit: cover;"/>
      </div>
      <p>
        <input type="button" class="button button-secondary tax_media_button" id="tax_media_button" name="tax_media_button" value="<?php _e( 'Add Image', 'astrodj' ); ?>" />
        <input type="button" class="button button-secondary tax_media_remove" id="tax_media_remove" name="tax_media_remove" value="<?php _e( 'Remove Image', 'astrodj' ); ?>" />
      </p>
    </td>
  </tr>
<?php
}

/**
 * Save the form field.
 */
function save_taxonomy_image( $term_id, $tt_id ) {
  if( isset( $_POST['taxonomy-image-id'] ) && '' !== $_POST['taxonomy-image-id'] ) {
    $image = $_POST['taxonomy-image-id'];
    add_term_meta( $term_id, 'taxonomy-image-id', $image, true );
  }
}

/**
 * Update the form field value.
 */
function updated_taxonomy_image( $term_id, $tt_id ) {
  if( isset( $_POST['taxonomy-image-id'] ) && '' !== $_POST['taxonomy-image-id'] ) {
    $image = $_POST['taxonomy-image-id'];
    update_term_meta( $term_id, 'taxonomy-image-id', $image );
  } else {
    delete_term_meta( $term_id, 'taxonomy-image-id' );
  }
}

/**
 * Image column added to category admin screen.
 *
 * @param mixed $columns
 * @return array
 */
function astrodj_register_taxonomy_column( $columns ) {
  $astrodj_columns['astrodj_meta_image'] = 'Image';

  return array_slice( $columns, 0, 2 ) + $astrodj_columns + $columns;
}

/**
 * Image column value added to category admin screen.
 *
 * @param string $columns
 * @param string $column
 * @param int $id term ID
 *
 * @return string
 */
function astrodj_add_value_taxonomy_column( $columns, $column, $id ) {
  $placeholder = get_stylesheet_directory_uri() . '/images/placeholder_admin.png';

  if ( 'astrodj_meta_image' == $column ) {
      $image_id = esc_html( get_term_meta( $id, 'taxonomy-image-id', true ) );

      if ( $image_id ) {
        $thumbnail = wp_get_attachment_image_src( $image_id, 'thumbnail' );
        $columns = $thumbnail[0];
      } else {
        $columns = $placeholder;
      }
  }

  echo '<a href="' . get_edit_post_link() . '" style="display: inline-block;">';
  echo '<img src="' . $columns . '" style="width: 40px; height: 40px; object-fit: cover;"/>';
  echo '</a>';
}

/**
 * Add Image columns on Custom Posts screen
 */
/**
 * Image column.
 *
 * @param mixed $columns
 * @return array
 */
function astrodj_register_custom_post_column( $columns ) {
  $astrodj_columns['astrodj_thumb_post'] = 'Image';

  return array_slice( $columns, 0, 1 ) + $astrodj_columns + $columns;
}

/**
 * Image column value.
 *
 * @param string $column
 * @param int $id term ID
 *
 * @return string
 */
function astrodj_add_value_custom_post_column( $column, $id ) {
  $placeholder = get_stylesheet_directory_uri() . '/images/placeholder_admin.png';

  if ( 'astrodj_thumb_post' == $column ) {
      $image_id = esc_html( get_post_meta( $id, '_thumbnail_id', true ) );

      if ( $image_id ) {
        $thumbnail = wp_get_attachment_image_src( $image_id, 'thumbnail' );
        $columns = $thumbnail[0];
      } else {
        $columns = $placeholder;
      }
  }

  echo '<a href="' . get_edit_post_link() . '" style="display: inline-block;">';
  echo '<img src="' . $columns . '" style="width: 40px; height: 40px; object-fit: cover;"/>';
  echo '</a>';
}

/**
 * Add styles for columns
 */
add_action( 'admin_print_footer_scripts-edit-tags.php', function () {
	?>
	<style>
		.column-astrodj_meta_image {
			width: 50px;
      text-align: center;
		}
	</style>
	<?php
} );

add_action( 'admin_print_footer_scripts-edit.php', function () {
	?>
	<style>
		.column-astrodj_thumb_post {
			width: 50px;
      text-align: center;
		}
	</style>
	<?php
} );

/**
 * Add an image Media Uploader field to the Category.
 */
require_once dirname(__FILE__) . '/media-uploader-add.php';
