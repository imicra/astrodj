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

	$portfolio_posts_per_page = get_option( 'astrodj_portfolio_posts_per_page' );

	if( $query->is_post_type_archive( 'stock' ) ) {
		$query->set( 'posts_per_page', $portfolio_posts_per_page ); // can create custom option for stock posts_per_page
	}

	if( $query->is_post_type_archive( 'portfolio' ) ) {
		$query->set( 'posts_per_page', $portfolio_posts_per_page ); // can create custom option for portfolio posts_per_page
	}

	if( $query->is_post_type_archive( 'cats' ) ) {
		$query->set( 'posts_per_page', $portfolio_posts_per_page ); // can create custom option for cats posts_per_page
	}

	if( $query->is_post_type_archive( 'archive' ) ) {
		$query->set( 'posts_per_page', $portfolio_posts_per_page ); // can create custom option for cats posts_per_page
	}
}
add_filter( 'pre_get_posts', 'modify_cpt_query' );

/**
 * Default sorting by date.
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
