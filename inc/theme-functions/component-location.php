<?php
/**
 * Component for display Location Murkup.
 *
 * @package astrodj
 */

function astrodj_meta_location() {
  $prefix = 'astrodj_';

  $country  = get_post_meta( get_the_ID(), $prefix . 'image_location_country', true );
  $region   = get_post_meta( get_the_ID(), $prefix . 'image_location_region', true );
  $location = get_post_meta( get_the_ID(), $prefix . 'image_location_location', true );

  $meta_array = array_diff( array(
    $country,
    $region,
    $location
  ), array('') );

  if ( ! empty( $meta_array ) ) :

    echo '<div class="meta-inline">';
    echo '<div class="inner">';
    echo astrodj_get_svg( array( 'icon' => 'location' ) );
    echo '<span class="item">';

    $filter_meta = implode( ', </span><span class="item">', $meta_array );

    echo $filter_meta;

    echo '</span>';
    echo '</div>';
    echo '</div>';

  endif;
}