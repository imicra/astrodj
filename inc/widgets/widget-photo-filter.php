<?php
/**
 * Widget to display photo filter by categiry.
 * 
 * @package astrodj
 */

class Astrodj_Photo_Filter_Widget extends WP_Widget {

  /**
   * Sets up a new Photo Filter widget instance.
   */
  public function __construct() {
		$widget_ops = array(
			'description'                 => __( 'Display photo filter by categiry and date.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'photo_filter', __( 'Astrodj Photo Filter' ), $widget_ops );
  }

  /**
   * Outputs the content for the Photo Filter widget instance.
   */
  public function widget( $args, $instance ) {
    $current_cpt = ! empty( $instance['cpt'] ) ? $instance['cpt'] : '';

    $cpt = get_post_type_object( $current_cpt );
    $taxonomy = $cpt->taxonomies[0];
    $terms = get_terms( $taxonomy );

    echo $args['before_widget'];

    echo '<div class="widget-filter__wrapper" data-cpt="' . $current_cpt . '">';

      echo '<div class="widget-item widget-filter__header">';
      echo '<div class="widget-filter__btn success">Показать</div>';
      echo '<div class="widget-filter__btn reset">Очистить</div>';
      echo '<div class="widget-filter__btn close">' . astrodj_get_svg( array( 'icon' => 'close' ) ) . '</div>';
      echo '</div>';

      if ( ! empty( $instance['date'] ) ) :

        echo '<div class="widget-item widget-filter__options">';
        echo '<h6>Сортировать</h6>';
        echo '<div class="widget-filter__select">';
        echo '<div class="selected"><span data-order="">Дата съемки</span><div class="icon-wrap">' . astrodj_get_svg( array( 'icon' => 'angle-down' ) ) . '</div></div>';
        echo '<div class="options">';
        echo '<div class="options-item" data-order="DESC">Сначала новые</div>';
        echo '<div class="options-item" data-order="ASC">Сначала ранние</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

      endif;

      if ( ! empty( $instance['categories'] ) ) :

        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

          echo '<div class="widget-item widget-filter__grid">';
          echo '<h6>Категории</h6>';

          foreach ( $terms as $term ) {

            $attachment_ID = get_term_meta( $term->term_taxonomy_id, 'taxonomy-image-id', true );

            if ( $attachment_ID ) {
              $image = wp_get_attachment_image_src( $attachment_ID, 'thumbnail' );
              $src = $image[0];
            } else {
              $src = get_stylesheet_directory_uri() . '/images/placeholder_admin.png';
            }

            echo '<div class="widget-filter__grid--item" data-id="' . $term->term_taxonomy_id . '" data-slug="' . $term->slug . '">';
            echo '<img width="' . $image[1] . '" height="' . $image[2] . '" alt="" src="' . $src . '">';
            echo '<span>' . $term->name . '</span>';
            echo  '</div>';

          }

          echo '</div>';
        }

      endif;

      echo '<div class="widget-item widget-filter__count">';
      echo '<h6>';
      echo '<span>Всего </span>';
      echo '<span id="count">' . wp_count_posts( $current_cpt, 'readable' )->publish . '</span>';
      echo '<span> Фото</span>';
      echo '</h6>';
      echo '</div>';

    echo '</div>';

    echo $args['after_widget'];
  }
  
  /**
   * Outputs the settings form for the Photo Filter widget.
   */
  public function form( $instance ) {
    // Widget Form Select CPT
    $current_cpt = ! empty( $instance['cpt'] ) ? $instance['cpt'] : '';
    $categories  = isset( $instance['categories'] ) ? (bool) $instance['categories'] : false;
    $date        = isset( $instance['date'] ) ? (bool) $instance['date'] : false;

    $args = array(
      'public'   => true,
      '_builtin' => false
    );

    $post_types = get_post_types( $args, 'objects' );

    $id   = $this->get_field_id( 'cpt' );
    $name = $this->get_field_name( 'cpt' );

    $categories_checkbox = sprintf(
			'<p><input type="checkbox" class="checkbox" id="%1$s" name="%2$s"%3$s /> <label for="%1$s">%4$s</label></p>',
			$this->get_field_id( 'categories' ),
			$this->get_field_name( 'categories' ),
			checked( $categories, true, false ),
			__( 'Фильтр по категориям' )
    );

    $date_checkbox = sprintf(
			'<p><input type="checkbox" class="checkbox" id="%1$s" name="%2$s"%3$s /> <label for="%1$s">%4$s</label></p>',
			$this->get_field_id( 'date' ),
			$this->get_field_name( 'date' ),
			checked( $date, true, false ),
			__( 'Фильтр по дате съемки' )
    );

    printf(
      '<p><label for="%1$s">%2$s</label>' .
      '<select class="widefat" id="%1$s" name="%3$s">',
      $id,
      __( 'Тип поста:' ),
      $name
    );

    foreach ( $post_types as $post_type => $cpt ) {
      printf(
        '<option value="%s"%s>%s</option>',
        esc_attr( $post_type ),
        selected( $post_type, $current_cpt, false ),
        $cpt->label
      );
    }

    echo '</select></p>' . $categories_checkbox . $date_checkbox;
  }

  /**
   * Handles updating settings for the Photo Filter widget instance.
   */
  public function update( $new_instance, $old_instance ) {
    $instance               = array();
    $instance['cpt']        = stripslashes( $new_instance['cpt'] );
    $instance['categories'] = ! empty( $new_instance['categories'] ) ? 1 : 0;
    $instance['date']       = ! empty( $new_instance['date'] ) ? 1 : 0;
		return $instance;
  }
}

function astrodj_register_photo_filter_widget() {
  register_widget( 'Astrodj_Photo_Filter_Widget' );
}
add_action( 'widgets_init', 'astrodj_register_photo_filter_widget' );