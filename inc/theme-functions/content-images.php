<?php
/**
 * Filtering Markup for images in post_content.
 * 
 * @package astrodj
 */

/**
 * Display LQIP Markup for images in post_content
 */
function astrodj_attachment_lqip_markup( $content ) {
	if ( ! is_singular() ) {
		return;
	}

	if ( ! preg_match_all( '/<img [^>]+>/', $content, $matches ) ) {
		return $content;
	}

	$selected_images = $attachment_ids = array();

	foreach ( $matches[0] as $image ) {
		if ( preg_match( '/wp-image-([0-9]+)/i', $image, $class_id ) && $attachment_id = absint( $class_id[1] ) ) {
			/*
			* If exactly the same image tag is used more than once, overwrite it.
			* All identical tags will be replaced later with 'str_replace()'.
			*/
			$selected_images[ $image ] = $attachment_id;
			// Overwrite the ID when the same image is included more than once.
			$attachment_ids[ $attachment_id ] = true;
		}
	}

	foreach ( $selected_images as $image => $attachment_id ) {
		$content = str_replace( $image, astrodj_modify_image_lqip_markup( $image, $attachment_id ), $content );
	}

	return $content;
}
add_filter('the_content', 'astrodj_attachment_lqip_markup', 100);

function astrodj_modify_image_lqip_markup( $image, $attachment_id ) {
  $full_attributes = wp_get_attachment_image_src( $attachment_id, 'full' );
  $lq_attributes = wp_get_attachment_image_src( $attachment_id, 'astrodj_lqip' );
  $srcset = wp_get_attachment_image_srcset( $attachment_id, 'full' );
  $sizes = wp_get_attachment_image_sizes( $attachment_id, 'full' );
	$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

	if ( strpos( $image, 'class' ) ) {
		$image = preg_replace( '/<img(.*?)class=\"(.*?)\"(.*?)>/i', '<img$1class="$2 lazy"$3>', $image );
	}

	if ( strpos( $image, 'src' ) ) {
		// $image = str_replace( 'src="' . $full_attributes[0] . '"', '', $image );
		$image = preg_replace( '/<img(.*?)src=\"(.*?)\"(.*?)>/i', '<img$1$3>', $image );
	}

	if ( strpos( $image, 'srcset' ) ) {
			// $image = str_replace( 'srcset="' . $srcset . '"', '', $image );
		$image = preg_replace( '/<img(.*?)srcset=\"(.*?)\"(.*?)>/i', '<img$1$3>', $image );
	}

	if ( strpos( $image, 'sizes' ) ) {
		// $image = str_replace( 'sizes="' . $sizes . '"', '', $image );
		$image = preg_replace( '/<img(.*?)sizes=\"(.*?)\"(.*?)>/i', '<img$1$3>', $image );
	}

	$image_before = '<div class="astrodj-lqip" style="max-width:' . $full_attributes[1] . 'px"  data-alt="' . $alt . '" data-src="' . $full_attributes[0] . '" data-srcset="' . $srcset . '" data-sizes="' . $sizes . '">';
	$image_before .= '<div class="aspect-ratio-fill" style="padding-bottom: 66.7%;width: 100%;height: 0;"></div>';
	$image_before .= '<div class="astrodj-lqip__wrap">';
	$image_before .= '<img width="' . $full_attributes[1] . '" height="' . $full_attributes[2] . '" alt="" class="placeholder" src="' . $lq_attributes[0] . '">';
	$image_before .= '</div>';
	$image_before .= '<div class="astrodj-lqip__wrap">';
	$image_before .= '<a href="' . $full_attributes[0] . '" class="fancybox">';

	$image_after = '</a>';
	$image_after .= '</div>';
	$image_after .= '</div>';

	// Add 'data' attribute
	$image = preg_replace( '/<img ([^>]+?)[\/ ]*>/', $image_before . '<img width="' . $full_attributes[1] . '" height="' . $full_attributes[2] . '" $1 />' . $image_after, $image );

	return $image;
}

// add class to images
function image_tag_class($class, $id, $align, $size) {
	return $class . ' lazy';
}
// add_filter('get_image_tag_class', 'image_tag_class', 10, 4);