<?php
/**
 * Post Thumbnail LQIP Functionality.
 *
 * @package astrodj
 */

/**
 * Display HTML markup for figure and post thumbnail.
 *
 * LQIP method for create placeholder image.
 * Used javascript for triggering image load 
 * when window scroll to image.
 *
 * @param string|array $class    One or more classes to add to the class list.
 * @param array|string $size     Optional. Image size. Accepts any valid image size, or an array of width
 *                               and height values in pixels (in that order).
 * @param array|string $size_lq  Optional. Image size for low quality size. 
 *                               Accepts any valid image size, or an array of width
 *                               and height values in pixels (in that order). Default 'astrodj_lqip'.
 * @param int          $ratio    Image ratio. Defaule = 66.7.
 * @return string      HTML markup.
 */
function astrodj_post_thumbnail_lqip( $class = '', $size, $size_lq = 'astrodj_lqip', $ratio = 66.7 ) {

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

  // exclude empty items
  $classes_output = array_diff( $classes, array('') );

  $class_attr = 'class="' . implode( ' ', $classes_output ) . '"';

  /**
   * Thumbnail attributes based on image size.
   */
  $thumbnail_id = get_post_thumbnail_id();
  $full_attributes = wp_get_attachment_image_src( $thumbnail_id, $size );
  $lq_attributes = wp_get_attachment_image_src( $thumbnail_id, $size_lq );
  $srcset = wp_get_attachment_image_srcset( $thumbnail_id, $size );
  $sizes = wp_get_attachment_image_sizes( $thumbnail_id, $size );
  $alt = get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );

  /**
   * Filter the $ratio
   */
  $ratio = apply_filters( 'astrodj_post_thumbnail_lqip_ratio', $ratio );

  ?>
  <figure <?php echo $class_attr; ?> data-alt="<?php echo $alt; ?>" data-src="<?php echo $full_attributes[0]; ?>" data-srcset="<?php //echo esc_attr( $srcset ); ?>" data-sizes="<?php //echo esc_attr( $sizes ); ?>">
    <div class="aspect-ratio-fill" style="padding-bottom: <?php echo $ratio; ?>%;width: 100%;height: 0;"></div>
    <?php do_action( 'astrodj_post_thumbnail_lqip_after_begin' ); ?>
    <div class="astrodj-lqip__wrap">
      <img width="<?php echo $full_attributes[1]; ?>" height="<?php echo $full_attributes[2]; ?>" alt="" class="placeholder" src="<?php echo $lq_attributes[0]; ?>">
    </div>
    <div class="astrodj-lqip__wrap">
      <?php do_action( 'astrodj_post_thumbnail_lqip_before_image', $full_attributes ); ?>
      <img width="<?php echo $full_attributes[1]; ?>" height="<?php echo $full_attributes[2]; ?>" class="lazy">
      <?php do_action( 'astrodj_post_thumbnail_lqip_after_image' ); ?>
    </div>
    <?php do_action( 'astrodj_post_thumbnail_lqip_before_end' ); ?>
  </figure>
  <?php
}

/**
 * Filtering $ratio for other conditions.
 */
function astrodj_post_thumbnail_lqip_ratio_settings( $ratio ) {
  /**
   * Portfolio and Stock $ratio.
   */
  if ( is_post_type_archive() ) :
    /* CMB thumbnail image class. */
    $meta_orientation = get_post_meta( get_the_ID(), 'astrodj_radio_orientation', true );

    if ( isset( $meta_orientation ) ) :

      if ( 'panorama' === $meta_orientation ) {
        $ratio = 33.7;
      } elseif ( 'vertical' === $meta_orientation ) {
        $ratio = 149.7;
      } else {
        $ratio = 66.7;
      }

    endif;
  endif;

  return $ratio;
}
// add_filter( 'astrodj_post_thumbnail_lqip_ratio', 'astrodj_post_thumbnail_lqip_ratio_settings' );

/**
 * Portfolio and Stock Post Title.
 */
function astrodj_post_thumbnail_lqip_portfolio_post_title() {
  if ( is_post_type_archive() ) :
  ?>
  <figcaption>
    <?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
  </figcaption>
  <?php
  endif;
}
add_action( 'astrodj_post_thumbnail_lqip_before_end', 'astrodj_post_thumbnail_lqip_portfolio_post_title' );

/**
 * Single Post Excerpt.
 */
function astrodj_post_thumbnail_lqip_single_excerpt() {
  if ( is_single() ) :
    if ( has_excerpt() ) : ?>
      <div class="close-icon">
        <span></span>
        <span></span>
      </div>
      <div class="post-thumbnail__excerpt">
        <?php	the_excerpt(); ?>
      </div>
    <?php endif;
  endif;
}
// add_action( 'astrodj_post_thumbnail_lqip_after_begin', 'astrodj_post_thumbnail_lqip_single_excerpt' );

/**
 * Single Post Fancybox.
 */
function astrodj_post_thumbnail_lqip_fancybox_start( $full_attributes ) {
  if ( is_single() ) : ?>
  <a href="<?php echo $full_attributes[0]; ?>" class="fancybox">
  <?php
  endif;
}
add_action( 'astrodj_post_thumbnail_lqip_before_image', 'astrodj_post_thumbnail_lqip_fancybox_start' );

function astrodj_post_thumbnail_lqip_fancybox_end() {
  if ( is_single() ) : ?>
  </a>
  <?php
  endif;
}
add_action( 'astrodj_post_thumbnail_lqip_after_image', 'astrodj_post_thumbnail_lqip_fancybox_end' );