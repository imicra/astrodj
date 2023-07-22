<?php
/**
 * Define Allowed Files to be included.
 * 
 * @package Astrodj
 */
function astrodj_filter_cpt( $array ) {
	return array_merge( $array, array(
		'cpt/posts-portfolio',
		'cpt/posts-stock',
		'cpt/posts-cats',
		'cpt/posts-archive',
		'cpt/contact-form'
	) );
}
add_filter( 'astrodj_filter_cpt', 'astrodj_filter_cpt' );

/**
 * Include files.
 */
function astrodj_include_cpt() {
	$astrodj_allowed_phps = array();
	$astrodj_allowed_phps = apply_filters( 'astrodj_filter_cpt', $astrodj_allowed_phps );
	foreach ( $astrodj_allowed_phps as $file ) {
		$astrodj_file_to_include = get_template_directory() . '/inc/' . $file . '.php';
		if ( file_exists( $astrodj_file_to_include ) ) {
			include_once( $astrodj_file_to_include );
		}
	}
}
add_action( 'after_setup_theme', 'astrodj_include_cpt' );

/**
 * 
 */
function modify_cpt_query( $query ) {
	if ( is_admin() ) {
		return;
	}

	$post_types = array(
		'portfolio',
		'stock',
		'cats',
		'archive'
	);

	foreach ( $post_types as $post_type ) {
		$posts_per_page = get_option( "astrodj_{$post_type}_posts_per_page" );

		if( $query->is_post_type_archive( $post_type ) ) {
			$query->set( 'posts_per_page', $posts_per_page );
		}
	}
}
add_filter( 'pre_get_posts', 'modify_cpt_query' );

/**
 * Redirect CPT single to a custom URL, archive page is publicly available.
 */
function astrodj_redirect_post() {
  if ( is_singular( 'archive' ) ) :
    wp_redirect( home_url(), 301 );
    exit;
  endif;
}
add_action( 'template_redirect', 'astrodj_redirect_post' );

/**
 * Default sorting by date in admin's post table.
 */
function astrodj_cpt_sort_query( $clauses, $query ) {
	global $wpdb;
	
	// error / not working after update on wp 6.1
	// require_once( ABSPATH . 'wp-admin/includes/screen.php' );
	// if ( is_admin() &&
	// ( get_current_screen()->id === 'edit-stock' || get_current_screen()->id === 'edit-portfolio' || get_current_screen()->id === 'edit-cats' ) ) {
	// 	$clauses['orderby'] = "{$wpdb->posts}.post_date DESC";
	// }

	// new method
	if ( is_admin() && ! wp_doing_ajax() &&
	( $query->is_post_type_archive( 'stock' ) || 
		$query->is_post_type_archive( 'portfolio' ) || 
		$query->is_post_type_archive( 'cats' ) ||
		$query->is_post_type_archive( 'archive' ) ) ) {
		$clauses['orderby'] = "{$wpdb->posts}.post_date DESC";
	}

	return $clauses;
}
add_filter( 'posts_clauses', 'astrodj_cpt_sort_query', 10, 2 );

// add_action( 'in_admin_header', function(){  
// 	global $wp_query;
// 	echo '<pre>'. print_r( get_current_screen()->id, 1 ) .'</pre>';
// 	echo '<pre>'. print_r( get_queried_object(), 1 ) .'</pre>';
// } );
