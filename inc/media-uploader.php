<?php
/**
 * Media Uploader custom fields
 * 
 * @package astrodj
 */


/**
 * Filters the attachment fields to edit.
 * 
 * https://kinsta.com/blog/wordpress-media-library-hacks/
 * https://gist.github.com/carlodaniele/3adf6a18e79233b98c96401d194e3420#file-media-hacks-php-L21
 *
 * @param array   $form_fields An array of attachment form fields.
 * @param WP_Post $post        The WP_Post attachment object.
 */

function astrodj_attachment_fields_to_edit( $form_fields, $post ) {

	// get post mime type
	$type = get_post_mime_type( $post->ID );

	if( 'image/jpeg' == $type ) :
  
		// https://codex.wordpress.org/Function_Reference/wp_get_attachment_metadata
		$media_exif   = (bool) get_post_meta( $post->ID, 'media_exif', true );
		$media_author   = get_post_meta( $post->ID, 'media_author', true );
		$media_camera   = get_post_meta( $post->ID, 'media_camera', true );
		$media_lens   = get_post_meta( $post->ID, 'media_lens', true );
		$media_focal = get_post_meta( $post->ID, 'media_focal', true );
		$media_aperture = get_post_meta( $post->ID, 'media_aperture', true );
		$media_time = get_post_meta( $post->ID, 'media_time', true );
		$media_iso = get_post_meta( $post->ID, 'media_iso', true );
		$media_datetime = get_post_meta( $post->ID, 'media_datetime', true );

		$form_fields['media_exif'] = array(
			'value' => $media_exif,
			'label' => __( 'Show EXIF' ),
			'input' => 'html',
			'html' => '<input type="checkbox" id="attachments-'.$post->ID.'-checkbox_field" name="attachments['  .$post->ID . '][checkbox_field]" value="1"' . ( $media_exif ? ' checked="checked"' : '' ) . ' /> ',
		);
		
		$astrodj_rights = esc_attr( get_option( 'astrodj_rights' ) );
		
		$form_fields['media_author'] = array(
			'value' => $media_author ? $media_author : $astrodj_rights,
			'label' => __( 'Author' )
		);

		//Allow selection of a post type on media attachments in WordPress 
		//https://gist.github.com/philipdowner/3296543
		$form_fields['media_camera'] = array(
			'label' => __( 'Choose Camera' ),
			'input' => 'html',
		);

		if ( !isset( $media_camera ) ) {
			$media_camera = 0;	
		}

		$form_fields['media_camera']['html'] = '<select name="attachments[' . $post->ID . '][media_camera]" id="attachments[' . $post->ID . '][media_camera]">';
		$form_fields['media_camera']['html'] .= '<option value="Canon EOS 1D X" ' . selected('Canon EOS 1D X', $media_camera, false) . '>Canon EOS-1D X</option>';
		$form_fields['media_camera']['html'] .= '<option value="Canon EOS 550D" ' . selected('Canon EOS 550D', $media_camera, false) . '>Canon 550D</option>';
		$form_fields['media_camera']['html'] .= '<option value="Смена-7" ' . selected('Смена-7', $media_camera, false) . '>Смена-7</option>';
		$form_fields['media_camera']['html'] .= '</select>';

		$form_fields['media_lens'] = array(
			'label' => __( 'Choose Lens' ),
			'input' => 'html',
		);

		if ( !isset( $media_lens ) ) {
			$media_lens = 0;	
		}

		$form_fields['media_lens']['html'] = '<select name="attachments[' . $post->ID . '][media_lens]" id="attachments[' . $post->ID . '][media_lens]">';
		$form_fields['media_lens']['html'] .= '<option value="0" ' . selected( 0, $media_lens, false ) . '>Choose one...</option>';
		$form_fields['media_lens']['html'] .= '<option value="EF 24mm f/1.4L II USM" ' . selected('EF 24mm f/1.4L II USM', $media_lens, false) . '>EF 24mm f/1.4L II USM</option>';
		$form_fields['media_lens']['html'] .= '<option value="EF 70-200mm f/2.8L IS II USM" ' . selected('EF 70-200mm f/2.8L IS II USM', $media_lens, false) . '>EF 70-200mm f/2.8L IS II USM</option>';
		$form_fields['media_lens']['html'] .= '<option value="EF-S 15-85mm f/3.5-5.6 IS USM" ' . selected('EF-S 15-85mm f/3.5-5.6 IS USM', $media_lens, false) . '>EF-S 15-85mm f/3.5-5.6 IS USM</option>';
		$form_fields['media_lens']['html'] .= '<option value="EF 50mm f/1.8 II" ' . selected('EF 50mm f/1.8 II', $media_lens, false) . '>EF 50mm f/1.8 II</option>';
		$form_fields['media_lens']['html'] .= '<option value="EF-S 55-250mm f/4-5.6 IS" ' . selected('EF-S 55-250mm f/4-5.6 IS', $media_lens, false) . '>EF-S 55-250mm f/4-5.6 IS</option>';
		$form_fields['media_lens']['html'] .= '<option value="EF-S 18-55mm f/3.5-5.6 IS II" ' . selected('EF-S 18-55mm f/3.5-5.6 IS II', $media_lens, false) . '>EF-S 18-55mm f/3.5-5.6 IS II</option>';
		$form_fields['media_lens']['html'] .= '</select>';

		$form_fields['media_focal'] = array(
			'value' => $media_focal ? $media_focal : '',
			'label' => __( 'Focal Lenght' )
		);
		
		$form_fields['media_aperture'] = array(
			'value' => $media_aperture ? $media_aperture : '',
			'label' => __( 'Aperture' )
		);

		$form_fields['media_time'] = array(
			'value' => $media_time ? $media_time : '',
			'label' => __( 'Exposure Time' )
		);

		$form_fields['media_iso'] = array(
			'value' => $media_iso ? $media_iso : '100',
			'label' => __( 'ISO' )
		);

		$form_fields['media_datetime'] = array(
			'value' => $media_datetime ? $media_datetime : '',
			'label' => __( 'Date/Time' )
		);
		
	endif;
	
	return $form_fields;
}
add_filter( 'attachment_fields_to_edit', 'astrodj_attachment_fields_to_edit', 10, 2 );

/**
 * Save the attachment fields.
 * 
 * https://wp-kama.ru/hook/attachment_fields_to_edit
 */
function astrodj_attachment_fields_to_save( $post, $attachment ) {
	//checkbox EXIF
	if ( isset( $_REQUEST['attachments'][$post['ID']]['checkbox_field'] ) ) {
		$media_exif = $_REQUEST['attachments'][$post['ID']]['checkbox_field'];
		update_post_meta( $post['ID'], 'media_exif', $media_exif );

		//Store all exif data in database only if checkbox is true
		//Author
		if ( isset( $attachment['media_author'] ) ) {
			update_post_meta( $post['ID'], 'media_author', $attachment['media_author'] );
		} elseif ( isset( $_REQUEST['attachments'][$post['ID']]['checkbox_field'] ) && ! isset( $attachment['media_author'] ) ) {
			//Default Author value
			$astrodj_rights = esc_attr( get_option( 'astrodj_rights' ) );
			
			update_post_meta( $post['ID'], 'media_author', $astrodj_rights );
		}	else {
			delete_post_meta( $post['ID'], 'media_author' );
		}

		//Camera
		if ( isset( $attachment['media_camera'] ) ) {
			update_post_meta( $post['ID'], 'media_camera', $attachment['media_camera'] );
		} elseif ( ! isset( $attachment['media_camera'] ) ) {
			//Default Camera value
			update_post_meta( $post['ID'], 'media_camera', 'Canon 550D' );
		}	else {
			delete_post_meta( $post['ID'], 'media_camera' );
		}

		//Lens
		if ( isset( $attachment['media_lens'] ) ) {
			update_post_meta( $post['ID'], 'media_lens', $attachment['media_lens'] );
		} else {
			delete_post_meta( $post['ID'], 'media_lens' );
		}

		//Focal Lenght
		if ( isset( $attachment['media_focal'] ) ) {
			update_post_meta( $post['ID'], 'media_focal', $attachment['media_focal'] );
		} else {
			delete_post_meta( $post['ID'], 'media_focal' );
		}
	
		//Aperture
		if ( isset( $attachment['media_aperture'] ) ) {
			update_post_meta( $post['ID'], 'media_aperture', $attachment['media_aperture'] );
		} else {
			delete_post_meta( $post['ID'], 'media_aperture' );
		}

		//Exposure Time
		if ( isset( $attachment['media_time'] ) ) {
			update_post_meta( $post['ID'], 'media_time', $attachment['media_time'] );
		} else {
			delete_post_meta( $post['ID'], 'media_time' );
		}

		//ISO
		if ( isset( $attachment['media_iso'] ) ) {
			update_post_meta( $post['ID'], 'media_iso', $attachment['media_iso'] );
		} elseif ( ! isset( $attachment['media_iso'] ) ) {
			//Default ISO value
			update_post_meta( $post['ID'], 'media_iso', '100' );
		}	else {
			delete_post_meta( $post['ID'], 'media_iso' );
		}

		//Exposure Time
		if ( isset( $attachment['media_datetime'] ) ) {
			update_post_meta( $post['ID'], 'media_datetime', $attachment['media_datetime'] );
		} else {
			delete_post_meta( $post['ID'], 'media_datetime' );
		}
		
	} else {
		// Delete exif data from database if checkbox is false
		delete_post_meta( $post['ID'], 'media_exif' );
  }

	return $post;
}
add_filter( 'attachment_fields_to_save', 'astrodj_attachment_fields_to_save', null, 2 );

/**
 * Add meta fields to the REST.
 */
function asrtodj_register_exif_fields() {
	register_rest_field( 'attachment',
		'astrodj_exif',
		array(
			'get_callback' => function( $object ) {
				$post_id = $object['id'];
				$metas = [
					'media_author',
					'media_camera',
					'media_lens',
					'media_focal',
					'media_aperture',
					'media_time',
					'media_iso',
					'media_datetime'
				];
				$exif_items = array();

				foreach( $metas as $meta ) {
					$exif_items[ $meta ] = get_post_meta( $post_id, $meta, true );
				}

				return $exif_items;
			},
			'update_callback'	=> null,
			'schema' => null
		)
	);
}
add_action( 'rest_api_init', 'asrtodj_register_exif_fields' );

/**
 * Enqueue JavaScript.
 */
function astrodj_attachment_fields_scripts() {
	if( is_singular( 'post' ) && is_main_query() ) {
		wp_enqueue_script( 'astrodj-media-uploader', get_template_directory_uri() . '/js/media-uploader.inc.js', array( 'jquery' ), _S_VERSION, true );
		wp_localize_script( 'astrodj-media-uploader', 'exifdata',
			array(
				'rest_url' => rest_url( 'wp/v2/media/'),
				'theme_uri' => get_stylesheet_directory_uri()
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'astrodj_attachment_fields_scripts' );

/**
 * Display image id if it has exif data.
 * 
 * https://wordpress.stackexchange.com/questions/220739/rewrite-inline-image-markup
 */
function astrodj_attachment_fields_to_display( $content ) {
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
			$exif = get_post_meta( $attachment_id, 'media_exif', true );
			if ( 1 == (int)$exif ) {
				$selected_images[ $image ] = $attachment_id;
				// Overwrite the ID when the same image is included more than once.
				$attachment_ids[ $attachment_id ] = true;
			}
		}
	}

	foreach ( $selected_images as $image => $attachment_id ) {
		$content = str_replace( $image, astrodj_modify_image_tag( $image, $attachment_id ), $content );
	}

	return $content;
}
add_filter( 'the_content', 'astrodj_attachment_fields_to_display', 100 );

function astrodj_modify_image_tag( $image, $attachment_id ) {
	//Check if the image already have a data attribute
	if ( ! strpos( $image, 'data-exif-id' ) ) {
			$attr = sprintf( ' data-exif-id="%s"', esc_attr( $attachment_id ) );
	}

	// Add 'data' attribute
	$image = preg_replace( '/<img ([^>]+?)[\/ ]*>/', '<img $1' . $attr . ' />', $image );

	return $image;
}