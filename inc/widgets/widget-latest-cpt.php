<?php
/**
 * Widget to display one latest post of choosen CPT.
 * 
 * @package astrodj
 */

class Astrodj_CPT_Post_Widget extends WP_Widget {

  /**
   * Sets up a new Latest CPT widget instance.
   */
  public function __construct() {
		$widget_ops = array(
			'description'                 => __( 'Display one latest post of choosen CPT.' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'latest_cpt', __( 'Astrodj CPT Post Thumbnail' ), $widget_ops );
  }

  /**
   * Outputs the content for the Latest CPT widget instance.
   */
  public function widget( $args, $instance ) {
    $current_cpt = ! empty( $instance['cpt'] ) ? $instance['cpt'] : '';

    $cpt = get_post_type_object( $current_cpt );

    if ( ! empty( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = 'Последнее из ' . $cpt->labels->name;
		}

    $cpt_args = array(
      'post_type'              => $current_cpt,
      'post_status'            => 'publish',
      'posts_per_archive_page' => 1
    );

    $latest_cpt = new WP_Query( $cpt_args );

    $title = apply_filters( 'widget_title', $title );

    echo $args['before_widget'];

    if ( ! empty( $instance['header'] ) ) :
      if ( $title ) {
        echo $args['before_title'] . $title . $args['after_title'];
      }
    endif;
    
    if ( $latest_cpt->have_posts() ):

      echo '<div class="widget_cpt__wrapper">';

        while( $latest_cpt->have_posts() ) :
          $latest_cpt->the_post();

          if ( has_post_thumbnail() ) :
            $link = ! empty( $instance['image_link'] ) ? get_post_type_archive_link( $current_cpt ) : esc_url( get_permalink() );

            echo '<a href="' . $link . '">';
            $this->_astrodj_thumbnail_lqip( 'widget_cpt__image', 'large' );
            echo '<div class="widget_cpt__overlay"></div>';

            if ( ! empty( $instance['image_link'] ) ) {
              echo '<span class="widget_cpt__title">' . $cpt->labels->name . '</span>';
            } else {
              echo '<span class="widget_cpt__title">';
              the_title();
              echo '</span>';
            }
            echo '</a>';

          endif;

          if ( ! empty( $instance['text'] ) ) :

            echo '<div class="widget_cpt__text">';
            echo '<p>Перейти в <a href="' . get_post_type_archive_link( $current_cpt ) . '">архив ' . $cpt->labels->name . '</a></p>';
            echo '</div>';

          endif;

        endwhile;

      echo "</div>\n";
    
    endif;
		echo $args['after_widget'];
  }
  
  /**
   * Outputs the settings form for the Latest CPT widget.
   */
  public function form( $instance ) {
    // Widhet Form Title
    $title_id   = $this->get_field_id( 'title' );
    $title      = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
    $image_link = isset( $instance['image_link'] ) ? (bool) $instance['image_link'] : false;
    $header     = isset( $instance['header'] ) ? (bool) $instance['header'] : false;
    $text       = isset( $instance['text'] ) ? (bool) $instance['text'] : false;

    echo '<p><label for="' . $title_id . '">' . __( 'Title:' ) . '</label>
			<input type="text" class="widefat" id="' . $title_id . '" name="' . $this->get_field_name( 'title' ) . '" value="' . $instance['title'] . '" />
    </p>';
    
    // Widget Form Select CPT
    $current_cpt = ! empty( $instance['cpt'] ) ? $instance['cpt'] : '';
    $args = array(
      'public'   => true,
      '_builtin' => false
    );

    $post_types = get_post_types( $args, 'objects' );

    $id   = $this->get_field_id( 'cpt' );
    $name = $this->get_field_name( 'cpt' );

    $image_link_checkbox = sprintf(
			'<p><input type="checkbox" class="checkbox" id="%1$s" name="%2$s"%3$s /> <label for="%1$s">%4$s</label></p>',
			$this->get_field_id( 'image_link' ),
			$this->get_field_name( 'image_link' ),
			checked( $image_link, true, false ),
			__( 'Сылка картинки на архив' )
    );
    
    $header_checkbox = sprintf(
			'<p><input type="checkbox" class="checkbox" id="%1$s" name="%2$s"%3$s /> <label for="%1$s">%4$s</label></p>',
			$this->get_field_id( 'header' ),
			$this->get_field_name( 'header' ),
			checked( $header, true, false ),
			__( 'Отобразить заголовок' )
    );
    
    $text_checkbox = sprintf(
			'<p><input type="checkbox" class="checkbox" id="%1$s" name="%2$s"%3$s /> <label for="%1$s">%4$s</label></p>',
			$this->get_field_id( 'text' ),
			$this->get_field_name( 'text' ),
			checked( $text, true, false ),
			__( 'Отобразить текст (ссылка на архив)' )
		);
    
    printf(
      '<p><label for="%1$s">%2$s</label>' .
      '<select class="widefat" id="%1$s" name="%3$s">',
      $id,
      __( 'CPT:' ),
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

    echo '</select></p>' . $image_link_checkbox . $header_checkbox . $text_checkbox;
  }

  /**
   * Handles updating settings for the Latest CPT widget instance.
   */
  public function update( $new_instance, $old_instance ) {
		$instance               = array();
		$instance['title']      = sanitize_text_field( $new_instance['title'] );
    $instance['cpt']        = stripslashes( $new_instance['cpt'] );
    $instance['image_link'] = ! empty( $new_instance['image_link'] ) ? 1 : 0;
    $instance['header']     = ! empty( $new_instance['header'] ) ? 1 : 0;
    $instance['text']       = ! empty( $new_instance['text'] ) ? 1 : 0;
		return $instance;
  }
  
  /**
   * Display Post Thumbnail LQIP effect.
   */
  public function _astrodj_thumbnail_lqip( $class = '', $size, $ratio = 66.7 ) {

    /**
     * Classes.
     */
    $classes = array();
  
    if ( $class ) {
      if ( ! is_array( $class ) ) {
        $class = preg_split( '#\s+#', $class );
      }
      $classes = array_map( 'esc_attr', $class );
    }
  
    // add function's unique class at start of classes list
    array_unshift( $classes, 'astrodj-lqip' );
  
    $class_attr = 'class="' . implode( ' ', $classes ) . '"';
  
    /**
     * Thumbnail attributes based on image size.
     */
    $thumbnail_id = get_post_thumbnail_id();
    $full_attributes = wp_get_attachment_image_src( $thumbnail_id, $size );
    $lq_attributes = wp_get_attachment_image_src( $thumbnail_id, 'astrodj_lqip' );
    $srcset = wp_get_attachment_image_srcset( $thumbnail_id, $size );
    $sizes = wp_get_attachment_image_sizes( $thumbnail_id, $size );
  
    ?>
    <figure <?php echo $class_attr; ?> data-src="<?php echo $full_attributes[0]; ?>" data-srcset="<?php echo esc_attr( $srcset ); ?>" data-sizes="<?php echo esc_attr( $sizes ); ?>">
      <div class="aspect-ratio-fill" style="padding-bottom: <?php echo $ratio; ?>%;width: 100%;height: 0;"></div>
      <div class="astrodj-lqip__wrap">
        <img width="<?php echo $full_attributes[1]; ?>" height="<?php echo $full_attributes[2]; ?>" class="placeholder" src="<?php echo $lq_attributes[0]; ?>">
      </div>
      <div class="astrodj-lqip__wrap">
        <img width="<?php echo $full_attributes[1]; ?>" height="<?php echo $full_attributes[2]; ?>" class="lazy">
      </div>
    </figure>
    <?php
  }
}

function astrodj_register_latest_cpt_widget() {
  register_widget( 'Astrodj_CPT_Post_Widget' );
}
add_action( 'widgets_init', 'astrodj_register_latest_cpt_widget' );
