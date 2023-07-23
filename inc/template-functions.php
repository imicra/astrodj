<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Astrodj
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function astrodj_body_classes( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'front-page';
		// $classes[] = 'frontpage__loader';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() && ! is_post_type_archive() ) {
		$classes[] = 'archive-view';
	}

	if ( 'portfolio' == get_post_type() ) {
		$classes[] = 'archive-portfolio';
	}

	if ( in_array( get_post_type(), ['stock', 'cats', 'archive'] ) ) {
		$classes[] = 'archive-stock';
	}

	if ( 'cats' == get_post_type() ) {
		$classes[] = 'archive-iframe';
	}

	if ( 'archive' == get_post_type() ) {
		$classes[] = 'archive-photography';
	}

	// Adds a class for content placeholder loading.
	// if ( is_home() || is_archive() || is_search() ) {
	// 	$classes[] = 'placeholder__preloading';
	// }

	// for cpt used iframe
	if ( is_singular( array( 'cats' ) ) ) {
		$classes[] = 'astrodj-iframe-portfolio';
	}

	// for cpt portfolio type on full page
	if ( is_singular( array( 'portfolio', 'stock' ) ) ) {
		$classes[] = 'astrodj-full-portfolio';
	}

	return $classes;
}
add_filter( 'body_class', 'astrodj_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function astrodj_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'astrodj_pingback_header' );

/**
 * Exclude Sticky Post from archive pages. Show it only on first of Home page.
 */
function astrodj_exclude_sticky( $query ) {
	if ( ! is_admin() && $query->is_main_query() && $query->is_paged() || is_archive() ) {
		$query->set( 'post__not_in', get_option( 'sticky_posts' ) );
	}
}
add_action( 'pre_get_posts', 'astrodj_exclude_sticky' );

/**
 * Exclude Pages from Search results.
 */
function astrodj_search_exclude_pages( $query ) {
	if ( ! is_admin() && $query->is_main_query() ) {
		if ( $query->is_search ) {
			$query->set( 'post_type', 'post' );
		}
	}
}
add_action( 'pre_get_posts', 'astrodj_search_exclude_pages' );

/**
 * Change Document Title Tag.
 */
function astrodj_document_title_parts( $title ) {
	if( isset( $title['page'] ) )
		unset( $title['page'] );

	if ( is_archive() && ! is_post_type_archive() ) {
		//re-build and order
		unset( $title['site'], $title['tagline'] );
		
		$title['tagline'] = get_bloginfo( 'description', 'display' );
		$title['site'] = get_bloginfo( 'name', 'display' );
	}

	return $title;
}
add_filter( 'document_title_parts', 'astrodj_document_title_parts' );

/**
 * Remove archive type words in Archive Title on Archive Pages.
 */
function astrodj_the_archive_title( $title ) {
	if ( is_tag() ) {
		$tag = get_queried_object();

		$title = sprintf( '<span class="%s">%s</span>' , $tag->slug, single_tag_title( '', false ) );
	} else {
		$title = preg_replace( '~^[^:]+: ~', '', $title );
	}

	return $title;
}
add_filter( 'get_the_archive_title', 'astrodj_the_archive_title' );

/**
 * Exclude author path from url.
 */
function astrodj_author_page_redirect() {
	if ( is_author() ) {
		wp_redirect( home_url( '/404/' ) );
		exit;
	}
}
add_action( 'template_redirect', 'astrodj_author_page_redirect' );

/**
 * Exclude user from sitemap
 */
function astrodj_sitemaps_users_query_args( $args ) {
	// учтем что этот параметр может быть уже установлен
	if( ! isset( $args['exclude'] ) )
		$args['exclude'] = array();

	$args['exclude'] = array_merge( $args['exclude'], [1] );

	return $args;
}
add_filter( 'wp_sitemaps_users_query_args', 'astrodj_sitemaps_users_query_args' );

/**
 * Disables the default WordPress option of converting emoticons to image smilies
 */
add_filter( 'option_use_smilies', '__return_false' );
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('admin_print_styles', 'print_emoji_styles');

/**
 * Clean head.
 */
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'wlwmanifest_link');
// meta rel='dns-prefetch' href='//s.w.org'
// remove_action( 'wp_head', 'wp_resource_hints', 2 );

/**
 * Disable Feeds
 */
// Remove links in head
function remove_feed_links() {
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'rsd_link' );
}
add_action( 'wp_loaded', 'remove_feed_links' );

// disable feeds
function disable_feed() {
	if ( get_query_var( 'feed' ) !== 'old' ) {
		set_query_var( 'feed', '' );
	}

	redirect_canonical();

	exit;
	// wp_die( __( "Feed Disabled" ) );
}
add_action('do_feed',      'disable_feed', 1);
add_action('do_feed_rdf',  'disable_feed', 1);
add_action('do_feed_rss',  'disable_feed', 1);
add_action('do_feed_rss2', 'disable_feed', 1);
add_action('do_feed_atom', 'disable_feed', 1);
add_action('do_feed_rss2_comments', 'disable_feed', 1);
add_action('do_feed_atom_comments', 'disable_feed', 1);

remove_action( 'do_feed_rdf',  'do_feed_rdf',  10, 1 );
remove_action( 'do_feed_rss',  'do_feed_rss',  10, 1 );
remove_action( 'do_feed_rss2', 'do_feed_rss2', 10, 1 );
remove_action( 'do_feed_atom', 'do_feed_atom', 10, 1 );

/**
 * 404. Not work on frontpage.
 */
function delete_all_feed_rewrites_rules( $rules ) {
	foreach ( $rules as $rule => $val ) {
		if ( strpos( $rule, 'feed/(' ) || strpos( $rule, '/(feed' ) || 0 === strpos( $rule, 'feed/(feed' ) ) {
			unset( $rules[ $rule ] );
		}
	}

	return $rules;
}
is_admin() && add_filter( 'rewrite_rules_array', 'delete_all_feed_rewrites_rules' );

/**
 * Rewrite get_the_tag_list.
 */
function astrodj_get_the_tag_list( $before = '', $sep = '', $after = '', $post_id = 0 ) {
	$terms = get_the_terms( $post_id, 'post_tag' );

	if ( is_wp_error( $terms ) ) {
		return $terms;
	}

	if ( empty( $terms ) ) {
		return false;
	}

	$links = array();

	foreach ( $terms as $term ) {
		$link = get_term_link( $term, 'post_tag' );
		if ( is_wp_error( $link ) ) {
			return $link;
		}
		$links[] = '<a href="' . esc_url( $link ) . '" class="' . $term->slug . '" rel="tag">' . $term->name . '</a>';
	}

	return $before . implode( $sep, $links ) . $after;
}

/**
 * Add a class to Tag Cloud items.
 */
function astrodj_tag_cloud_class( $tags_data ) {
	return array_map(
		function ( $item ) {
			$item['class'] .= " {$item['slug']}";
			return $item;
		},
		(array) $tags_data
	);
}
add_filter( 'wp_generate_tag_cloud_data', 'astrodj_tag_cloud_class' );

/**
 * Unset certain tag from Tags Cloud.
 */
function astrodj_tag_cloud_unset( $tags_data ) {
	foreach ( $tags_data as $key => $tag_data ) {
		if (  'z' === $tag_data['slug'] ) {
			unset( $tags_data[$key] );
			continue;
		}
	}

	return $tags_data;
}
add_filter( 'wp_generate_tag_cloud_data', 'astrodj_tag_cloud_unset' );
