<?php
/**
 * Generate inline styles responsive images for css background-image in header.
 *
 * @package astrodj
 */

/**
 * https://gist.github.com/joshuadavidnelson/eb4650aa1ee8da9c7d731960e9402e21
 */
function astrodj_background_header_image( $attachment_id, $class ) {
  /**
   * Generate some inline styles for use with the background-image and responsive images in WordPress.
   * 
   * Uses wp_get_attachment_image_srcset to gather the image urls and device sizes. 
   * A mobile-friendly application of the background-image property.
   * In this example, it's setup for a full-width image
   * 
   * @author Joshua David Nelson, josh@joshuadnelson.com
   */

  $img_srcset = wp_get_attachment_image_srcset( $attachment_id, 'full' );
  $sizes = explode( ", ", $img_srcset );
  $css = '';
  $prev_size = '';
  foreach( $sizes as $size ) {
    
    // Split up the size string, into an array with [0]=>img_url, [1]=>size
    $split = explode( " ", $size );
    if( !isset( $split[0], $split[1] ) )
      continue;
    
    $background_css = '.' . $class . ' {
      background-image: url(' . esc_url( $split[0] ) . ');
      background-repeat: no-repeat;
      background-position: center;
      background-size: cover;
    }';
    
    // Grab the previous image size as the min-width and/or add the background css to the main css string
    if( !empty( $prev_size ) ) {
      $css .= '@media only screen and (min-width: ' . $prev_size . ') {';
      $css .= $background_css;
      $css .= "}\n";
    } else {
      $css .= $background_css;
    }
    
    // Set the current image size as the "previous image" size, for use with media queries
    $prev_size = str_replace( "w", "px", $split[1] );
  }

  // The final css, wrapped in a <style> tag
  $css = !empty( $css ) ? '<style>' . $css . '</style>' : '';

  return $css;
}