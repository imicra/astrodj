<?php
/**
 * Widget to display photo gallery by recent CPT posts.
 * 
 * @package astrodj
 */

class Astrodj_Photo_Gallery_Widget extends WP_Widget {

  /**
   * Sets up a new Photo Gallery widget instance.
   */
  public function __construct() {
		$widget_ops = array(
			'description'                 => __( 'Display photo gallery by recent CPT posts.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'photo_gallery', __( 'Astrodj Gallery Filter' ), $widget_ops );
  }

  /**
   * Outputs the content for the Photo Gallery widget instance.
   */
  public function widget( $args, $instance ) {
    $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 4;
    if ( ! $number )
      $number = 4;

    $columns = ( ! empty( $instance['columns'] ) ) ? absint( $instance['columns'] ) : 2;
    if ( ! $columns )
      $columns = 2;

    $choosen_cpt = isset( $instance['choosen_cpt'] ) ? $instance['choosen_cpt'] : '';

    $gallery_args = array(
      'post_type'      => $choosen_cpt,
      'post_status'    => 'publish',
      'posts_per_page' => $number
    );

    echo $args['before_widget'];

    $latest_photos = new WP_Query( $gallery_args );

    if ( $latest_photos->have_posts() ):

      echo '<div class="gallery-columns-' . $columns . ' gallery">';

      echo '<div class="widget-loader-wrapper">' . astrodj_get_svg( array( 'icon' => 'loader-ring' ) ) . '</div>';

        while( $latest_photos->have_posts() ) :
          $latest_photos->the_post();

          $thumbnail_id = get_post_thumbnail_id();
          $link_attributes = wp_get_attachment_image_src( $thumbnail_id, 'medium_large' );

          echo '<figure class="gallery-item">';
          echo '<div class="gallery-item-inner">';
          echo '<a href="' . $link_attributes[0] . '" class="fancybox">';
          the_post_thumbnail( 'thumbnail' );
          echo '</a>';
          echo '</div>';
          echo '</figure>';

        endwhile;

      echo "</div>\n";
    
    endif;

    wp_reset_postdata();

		echo $args['after_widget'];
  }
  
  /**
   * Outputs the settings form for the Photo Gallery widget.
   */
  public function form( $instance ) {
    $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 4;
    $columns = isset( $instance['columns'] ) ? absint( $instance['columns'] ) : 2;
    $choosen_cpt = isset( $instance['choosen_cpt'] ) ? $instance['choosen_cpt'] : [];
    ?>

    <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Изображений:' ); ?></label>
    <input class="tiny-text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

    <p><label for="<?php echo $this->get_field_id( 'columns' ); ?>"><?php _e( 'Колонок:' ); ?></label>
    <input class="tiny-text" id="<?php echo $this->get_field_id( 'columns' ); ?>" name="<?php echo $this->get_field_name( 'columns' ); ?>" type="number" step="1" min="1" max="9" value="<?php echo $columns; ?>" size="3" /></p>
    <?php
    $args = array(
      'public'   => true,
      '_builtin' => false
    );

    $post_types = get_post_types( $args, 'objects' );

    $id   = $this->get_field_id( 'choosen_cpt' );
    $name = $this->get_field_name( 'choosen_cpt' );

    printf(
      '<p><label for="%1$s">%2$s</label>' .
      '<select multiple class="widefat" id="%1$s" name="%3$s" size = "3">',
      $id,
      __( 'Источники:' ),
      $name
    );

    foreach ( $post_types as $post_type => $cpt ) {

      $selected = '';
      if( in_array( $post_type, $choosen_cpt ) ) {
        $selected = 'selected';
      }

      printf(
        '<option value="%s" %s>%s</option>',
        esc_attr( $post_type ),
        $selected,
        $cpt->label
      );
    }
    echo '</select></p>';
  }

  /**
   * Handles updating settings for the Photo Gallery widget instance.
   */
  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['number']      = absint( $new_instance['number'] );
    $instance['columns']     = absint( $new_instance['columns'] );
    $instance['choosen_cpt'] = $new_instance['choosen_cpt'];
    return $instance;
  }
}

function astrodj_register_photo_gallery_widget() {
  register_widget( 'Astrodj_Photo_Gallery_Widget' );
}
add_action( 'widgets_init', 'astrodj_register_photo_gallery_widget' );