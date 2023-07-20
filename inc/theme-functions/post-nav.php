<?php
/**
 * Modifying Post Navigation functions.
 *
 * @package astrodj
 */

/**
 * Modifying the_post_navigation for reverse "next" and "prev"
 */
function astrodj_post_navigation( $args = array() ) {
	echo astrodj_get_the_post_navigation( $args );
}

function astrodj_get_the_post_navigation( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'prev_text'          => '%title',
			'next_text'          => '%title',
			'in_same_term'       => false,
			'excluded_terms'     => '',
			'taxonomy'           => 'category',
			'screen_reader_text' => __( 'Post navigation' ),
		)
	);

	$navigation = '';

	$previous = get_previous_post_link(
		'<div class="nav-previous">%link</div>',
		$args['prev_text'],
		$args['in_same_term'],
		$args['excluded_terms'],
		$args['taxonomy']
	);

	$next = get_next_post_link(
		'<div class="nav-next">%link</div>',
		$args['next_text'],
		$args['in_same_term'],
		$args['excluded_terms'],
		$args['taxonomy']
	);

	// Only add markup if there's somewhere to navigate to.
	if ( $previous || $next ) {
		$navigation = _navigation_markup( $next . $previous, 'post-navigation', $args['screen_reader_text'] );
	}

	return $navigation;
}

/**
 * Filter previous_post_link and next_post_link.
 * 
 * Add data-hash-id attribute. 
 * Used in Local Storage item for create hash in Portfolio Pages for back to certain post.
 */
function astrodj_filter_next_post_link( $link ) {
  if ( ! get_next_post() ) return;

  $next_post = get_next_post();
  $id = $next_post->ID;
  $link = str_replace( 'rel=', 'data-hash-id="' . $id . '" rel=', $link );

  return $link;
}
add_filter( 'next_post_link', 'astrodj_filter_next_post_link' );

function astrodj_filter_previous_post_link( $link ) {
  if ( ! get_previous_post() ) return;

  $previous_post = get_previous_post();
  $id = $previous_post->ID;
  $link = str_replace( 'rel=', 'data-hash-id="' . $id . '" rel=', $link );

  return $link;
}
add_filter( 'previous_post_link', 'astrodj_filter_previous_post_link' );