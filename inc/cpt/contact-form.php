<?php
/**
 * File contact-form.php.
 * 
 * Custom Post Type for collection of Contact Form messages.
 * 
 * @package Astrodj
 */

function astrodj_contact_posttypes() {
  
  $labels = array(
      'name'               => __( 'Письмо', 'astrodj' ),
      'singular_name'      => __( 'Contact item', 'astrodj' ),
      'menu_name'          => __( 'Письма', 'astrodj' ),
      'name_admin_bar'     => __( 'Письма', 'astrodj' ),
      'add_new'            => __( 'Новое', 'astrodj' ),
      'add_new_item'       => __( 'Добавить', 'astrodj' ),
      'new_item'           => __( 'New Contact item', 'astrodj' ),
      'edit_item'          => __( 'Редактировать письмо', 'astrodj' ),
      'view_item'          => __( 'View Contact item', 'astrodj' ),
      'all_items'          => __( 'Все письма', 'astrodj' ),
      'search_items'       => __( 'Search Contact items', 'astrodj' ),
      'parent_item_colon'  => __( 'Parent Contact item:', 'astrodj' ),
      'not_found'          => __( 'No Contact items found.', 'astrodj' ),
      'not_found_in_trash' => __( 'No Contact items found in Trash.', 'astrodj' ),
  );
  
  $args = array(
      'labels'              => $labels,
      'public'              => false,
      'publicly_queryable'  => false,
      'exclude_from_search' => true,
      'show_ui'             => true,
      'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'menu_position'       => 23,
			'menu_icon'           => 'dashicons-email-alt',
			'capability_type'     => 'post',
			'hierarchical'        => false,
      'supports'            => array( 'title', 'editor', 'author' ),
      'show_in_rest'        => false,
  );
  register_post_type( 'contact', $args );
}
add_action( 'init', 'astrodj_contact_posttypes' );

/**
 * Customize Columns in Posts Table.
 */
function astrodj_manage_contact_columns( $columns ) {
	$newColumns = array();

	$newColumns['cb'] = '<input type="checkbox" />';
	$newColumns['title'] = 'Имя';
	$newColumns['message'] = 'Письмо';
	$newColumns['email'] = 'Email';
	$newColumns['date'] = 'Дата';

	return $newColumns;
}
add_filter( 'manage_contact_posts_columns', 'astrodj_manage_contact_columns' );

/**
 * Collect Columns in Posts Table with data.
 */
function astrodj_manage_contact_custom_column( $column, $post_id ) {
	global $post;
	
	switch( $column ) {
		case 'message' :
			echo wp_trim_words( $post->post_content, 20 );
			break;

		case 'email' :
			$email = get_post_meta( $post_id, '_contact_email_value_key', true );
			echo '<a href="mailto:' . $email . '">' . $email . '</a>';
			break;
	}
}
add_action( 'manage_contact_posts_custom_column', 'astrodj_manage_contact_custom_column', 10, 2 );

/**
 * Disable trash (force delete post).
 */
function astrodj_disable_trash_for_contact( $null, $post ) {

	if ( in_array( $post->post_type, ['contact'] ) ) {
		return wp_delete_post( $post->ID, true );
	}

	return $null;
}
add_filter( 'pre_trash_post', 'astrodj_disable_trash_for_contact', 10, 2 );

/**
 * Contact Meta Boxes.
 */
function astrodj_contact_add_meta_box() {
	add_meta_box( 'contact_email', 'Email', 'astrodj_contact_email_mb_cb', 'contact', 'side' );
}
add_action( 'add_meta_boxes', 'astrodj_contact_add_meta_box' );

function astrodj_contact_email_mb_cb( $post ) {
	wp_nonce_field( 'astrodj_save_contact_email_data', 'astrodj_contact_email_meta_box_nonce' );

	$value = get_post_meta( $post->ID, '_contact_email_value_key', true );

	echo '<label for="astrodj_contact_email_field">Email: </label>';
	echo '<input type="email" id="astrodj_contact_email_field" name="astrodj_contact_email_field" value="' . esc_attr( $value ) . '" size="25" />';
}

function astrodj_save_contact_email_data( $post_id ) {
	if ( ! isset( $_POST['astrodj_contact_email_meta_box_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['astrodj_contact_email_meta_box_nonce'], 'astrodj_save_contact_email_data' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! isset( $_POST['astrodj_contact_email_field'] ) ) {
		return;
	}

	$my_data = sanitize_text_field( $_POST['astrodj_contact_email_field'] );
	
	update_post_meta( $post_id, '_contact_email_value_key', $my_data );
}
add_action( 'save_post', 'astrodj_save_contact_email_data' );