<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Astrodj
 */

if ( ! function_exists( 'astrodj_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function astrodj_posted_on() {
		// $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		
			// $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><span class="updated"> / Обновлено </span><time class="updated" datetime="%3$s">%4$s</time>';
			if ( ! is_single() ) :
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
			else :
				$time_string = '<time class="entry-date published" datetime="%3$s">%4$s</time>';
			endif;

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( '%s', 'post date', 'astrodj' ), $time_string );

		if ( ! is_single() ) :

			echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

		else :

			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				echo '<span class="posted-on"> Обновлено ' . $posted_on . '</span>'; // WPCS: XSS OK.
			} else {
				echo '<span class="posted-on"> Опубликовано ' . $posted_on . '</span>'; // WPCS: XSS OK.
			}

		endif;

	}
endif;

if ( ! function_exists( 'astrodj_event_date' ) ) :
	/**
	 * Prints HTML with meta information for the current post.
	 */
	function astrodj_event_date() {
		/* CMB field. */
		$event_meta = get_post_meta( get_the_ID(), 'astrodj_textdate_timestamp', true );

		if ( ! empty( $event_meta ) ) :

			$event_on = '<time>' . date_i18n( 'j M Y', $event_meta ) . '</time>';

			echo '<div class="metabox__item"><span>Дата поездки: </span>' . $event_on . '</div>';

		endif;
	}
endif;

if ( ! function_exists( 'astrodj_camera' ) ) :
	/**
	 * Prints HTML with meta information for the current post.
	 */
	function astrodj_camera() {
		/* CMB field. */
		$meta_camera = get_post_meta( get_the_ID(), 'astrodj_select_camera', true );

		if ( ! empty( $meta_camera ) ) :

			if ( 'canon' === $meta_camera ) {
				$camera = 'Canon 550D';
			} elseif ( 'canon1dx' === $meta_camera ) {
				$camera = 'Canon EOS-1D X';
			} elseif ( 'smena' === $meta_camera ) {
				$camera = 'Смена-7';
			}

			echo '<div class="metabox__item"><span>Камера: </span>' . $camera . '</div>';

		endif;
	}
endif;

if ( ! function_exists( 'astrodj_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function astrodj_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( '%s', 'post author', 'astrodj' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		if ( ! is_single() ) :

			echo '<span class="byline"> ' . $byline . '<span class="entry-meta__separator">|</span></span>'; // WPCS: XSS OK.

		else :

			echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

		endif;

	}
endif;

if ( ! function_exists( 'astrodj_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function astrodj_entry_footer() {
		// Hide tag text for pages and archives.
		if ( 'post' === get_post_type() && is_singular() && ! is_front_page() ) {
			/* translators: used between list items, there is a svg tag icon */
			$tag_icon = astrodj_get_svg( array( 'icon' => 'tag' ) );
			$tags_list = astrodj_get_the_tag_list( '<div class="meta-item"><span class="meta-icon">' . $tag_icon . '</span><span class="meta-text">', '</span></div><div class="meta-item"><span class="meta-icon">' . $tag_icon . '</span><span class="meta-text">', '</span></div>' );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links-single">' . esc_html__( '%1$s', 'astrodj' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'astrodj' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

function astrodj_the_category_list() {
	/* translators: used between list items, there is a space after the comma */
	$categories_list = get_the_category_list( esc_html__( ', ', 'astrodj' ) );
	if ( $categories_list ) {
		/* translators: 1: list of categories. */
		if ( ! is_single() ) :
			printf( '<span class="cat-links">' . esc_html__( '%1$s', 'astrodj' ) . '</span>', $categories_list ); // WPCS: XSS OK.

		else :

			printf( '<span class="cat-links">' . esc_html__( 'Архив %1$s', 'astrodj' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			
		endif;
	}
}

function astrodj_the_tags_list() {
	/* translators: used between list items, there is a space */
	$tags_list = get_the_tag_list( '', esc_html_x( ' ', 'list item separator', 'astrodj' ) );
	if ( $tags_list ) {
		/* translators: 1: list of tags. */
		printf( '<span class="tags-links">' . esc_html__( '%1$s', 'astrodj' ) . '</span>', $tags_list ); // WPCS: XSS OK.
	}
}

/**
 * Tags text only for portfolio poste.
 */
function astrodj_portfolio_tags_list() {
	global $post;
	
	$tags_list = get_the_terms( $post->ID, 'technics' );
	
	if ( $tags_list ) { ?>
		<div class="entry-meta">
			<h5>Техники съемки</h5>
			<?php foreach( $tags_list as $tag ) {
				printf( '<span class="tags-single">' . esc_html__( '%1$s', 'astrodj' ) . '</span>', $tag->name );
			} ?>
		</div>
	<?php }
}

function astrodj_single_comments_popup_link() {
	if ( is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link-single">';
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'astrodj' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
		echo '</span>';
	}
}

function astrodj_comments_popup_link() {
	if ( ! is_single() && ! post_password_required() && ( comments_open() && get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Оставить Комментарий<span class="screen-reader-text"> on %s</span>', 'astrodj' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
		echo '</span>';
	}
}

if ( ! function_exists( 'astrodj_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function astrodj_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

		<figure class="post-thumbnail full-bleed">
			<?php
			the_post_thumbnail( 'post-thumbnail', array(
				'alt' => the_title_attribute( array(
					'echo' => false,
				) ),
			) );
			?>
		</figure>

		<?php
		endif; // End is_singular().
	}
endif;

/**
 * Customize ellipsis at end of excerpts.
 */
function astrodj_excerpt_more( $more ) {
	return "";
}
add_filter( 'excerpt_more', 'astrodj_excerpt_more' );

/**
 * Filter excerpt length to 100 words.
 */
function astrodj_excerpt_length( $length ) {
	if ( is_admin() ) {
		return $length;
	}

	return 0;
}
add_filter( 'excerpt_length', 'astrodj_excerpt_length', 999 );
