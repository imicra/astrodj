<?php
/**
 * Theme functions.
 *
 * @package astrodj
 */

/**
 * Custom Logo Murkup.
 * Same murkup as a core the_custom_logo function,
 * but output empty alt attribute.
 */
function astrodj_the_custom_logo() {
	if ( has_custom_logo() ) :
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$custom_logo_attributes = wp_get_attachment_image_src( $custom_logo_id , 'full' );
		$custom_logo_srcset = wp_get_attachment_image_srcset( $custom_logo_id, 'full' );
		$custom_logo_sizes = wp_get_attachment_image_sizes( $custom_logo_id, 'full' );
		$custom_logo_url = $custom_logo_attributes[0];
		$custom_logo_width = $custom_logo_attributes[1];
		$custom_logo_height = $custom_logo_attributes[2];
		?>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link" rel="home">
			<img width="<?php echo $custom_logo_width; ?>" height="<?php echo $custom_logo_height; ?>" 
				src="<?php echo esc_url( $custom_logo_url ); ?>" 
				class="custom-logo" 
				alt="" 
				srcset="<?php echo esc_attr( $custom_logo_srcset ); ?>" 
				sizes="<?php echo esc_attr( $custom_logo_sizes ); ?>">
		</a>
		<?php
	endif;
}

/**
 * astrodj_paginate_links.
 */
require_once dirname( __FILE__ ) . '/custom-pagination.php';

/**
 * Wrap page numbers from the_posts_pagination into div.
 * 
 * https://stackoverflow.com/questions/37580965/how-can-i-wrap-page-numbers-from-the-posts-pagination-in-wordpress-into-my-own-d
 *
 */
function astrodj_posts_pagination( $args = [], $class = 'pagination' ) {

	if ( $GLOBALS['wp_query']->max_num_pages <= 1 ) return;

	$args = wp_parse_args( $args, [
		'mid_size'           => 1,
		'prev_next'          => false,
		'prev_text'          => __( 'Старее', 'astrodj' ),
		'next_text'          => __( 'Новее', 'astrodj' ),
		'screen_reader_text' => __( 'Posts navigation', 'astrodj' ),
		'before_page_number' => '<span class="screen-reader-text">' . __(  'Стр. ', 'astrodj' ) . '</span>',
	] );

	$links     = astrodj_paginate_links( $args );
	$next_link = get_previous_posts_link( $args['next_text'] );
	$prev_link = get_next_posts_link( $args['prev_text'] );
	$template  = apply_filters( 'navigation_markup_template', '
	<nav class="navigation %1$s" role="navigation">
			<h2 class="screen-reader-text">%2$s</h2>
			<div class="nav-links"><div class="nav-links__arrows nav-links__prev">%3$s</div><div class="nav-links__container">%4$s</div><div class="nav-links__arrows nav-links__next">%5$s</div></div>
	</nav>', $args, $class );

	echo sprintf( $template, $class, $args['screen_reader_text'], $next_link, $links, $prev_link );
}

/**
 * Modifying Post Navigation functions.
 */
require_once dirname( __FILE__ ) . '/post-nav.php';

/**
 * Placeholder Content Murkup.
 * Used for page loading placeholder.
 */
function astrodj_placeholder_content_preloader() {
	?>
	<div id="placeholder__content">
		<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>
		<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>
		<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>
		<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>
	</div>
	<?php
}

function astrodj_placeholder_gallery_preloader() {
	?>
	<div id="placeholder__gallery">
		<div class="placeholder__gallery-item"><?php echo astrodj_get_svg( array( 'icon' => 'image' ) ); ?></div>
		<div class="placeholder__gallery-item"><?php echo astrodj_get_svg( array( 'icon' => 'image' ) ); ?></div>
		<div class="placeholder__gallery-item"><?php echo astrodj_get_svg( array( 'icon' => 'image' ) ); ?></div>
		<div class="placeholder__gallery-item"><?php echo astrodj_get_svg( array( 'icon' => 'image' ) ); ?></div>
		<div class="placeholder__gallery-item"><?php echo astrodj_get_svg( array( 'icon' => 'image' ) ); ?></div>
	</div>
	<?php
}

/**
 * Edit default WordPress widgets.
 */
function astrodj_tag_cloud_font_change( $args ) {
	
	$args['smallest'] = 11;
	$args['largest'] = 11;
	
	return $args;
	
}
add_filter( 'widget_tag_cloud_args', 'astrodj_tag_cloud_font_change' );

function astrodj_list_categories_output_change( $links ) {
	
	$links = str_replace( '</a> (', '</a> <span>', $links );
	$links = str_replace( ')', '</span>', $links );
	
	return $links;
	
}
add_filter( 'wp_list_categories', 'astrodj_list_categories_output_change' );

/**
 * Portfolio Dropdown Menu.
 */
require_once dirname( __FILE__ ) . '/dropdown-menu.php';

/**
 * Post Thumbnail LQIP Functionality.
 */
require_once dirname( __FILE__ ) . '/thumbnail-lqip.php';

/**
 * Generate inline styles responsive images for css background-image in header.
 */
require_once dirname( __FILE__ ) . '/header-image.php';

/**
 * Filtering Markup for images in post_content.
 */
require_once dirname( __FILE__ ) . '/content-images.php';

/**
 * Component for display Exif Data Murkup.
 */
require_once dirname( __FILE__ ) . '/component-exif.php';

/**
 * Component for display Location Murkup.
 */
require_once dirname( __FILE__ ) . '/component-location.php';

/**
 * Insert HTML tag <br> in string line.
 * 
 * @param string $expr Part of string which join with <br> tag.
 * @param string $string String for add <br> tag.
 * @param bool $after true to add <br> tag after $expr, false to add <br> tag before $expr.
 * @return string $string with <br> tag.
 */
function astrodj_insert_break( $expr, $string, $after = true ) {
	if ( $after ) :

		$result = str_replace( $expr, $expr . '<br>', $string );

	else :

		$result = str_replace( $expr, '<br>' . $expr, $string );

	endif;

	return preg_replace( '/<p>|<\/p>/', '', wpautop( $result ) );
}

/**
 * Calculate Archive Page Number for certain Post.
 * Used for REST.
 * 
 * $num for CPT would get from custom option posts_per_page.
 * 
 * @param string $type    Post type.
 * @param bool $num       posts_per_page option value.
 * @return string $current_page.
 */
function astrodj_post_archive_page( $type, $num = false ) {
  if ( is_admin() ) {
    return;
  }
  
  global $post;

  $position_query = array(
    'post_type'   => $type,
    'orderby'     => 'date',
    'order'       => 'DESC',
    'numberposts' => -1,
    'posts_per_archive_page' => -1
  );

  $position_posts = get_posts( $position_query );
  $count = 0;

  foreach ( $position_posts as $position_post ) {
    $count++;
    if ( $position_post->ID == $post->ID ) {
      $position = $count;
      break;
    }
  }
  
  if ( $num ) {
    $posts_per_page = $num;
  } else {
    $posts_per_page = get_option( 'posts_per_page' );
  }
  
  $result = $position/$posts_per_page;
  $current_page = ceil( $result );

  return $current_page;
}

/**
 * Display Murkup for Back Link to archive page with certain number page.
 * 
 * Usefull in portfolio fullpage templates.
 * 
 * $num for CPT would get from custom option posts_per_page.
 */
function astrodj_get_archive_back_link() {
	$post_type = get_post_type();
	$portfolio_posts_per_page = get_option( "astrodj_{$post_type}_posts_per_page" );

	$paged = astrodj_post_archive_page( $post_type, $portfolio_posts_per_page );

	if ( $paged == 1 ) {
		$path = '';
	} else {
		$path = 'page/' . $paged . '/';
	}
	?>
	<div class="back-link">
		<a href="<?php echo get_post_type_archive_link( $post_type ) . $path; ?>">
			<?php echo astrodj_get_svg( array( 'icon' => 'arrow-left' ) ); ?>
			<span>Назад</span>
		</a>
	</div>
	<?php
}

/**
 * List Categories of certain Custom Post Type.
 * Used in portfolios templates.
 */
function astrodj_portfolio_category_list() {
	global $post;

	$cpt = get_post_type_object( get_post_type() );
	$taxonomy = $cpt->taxonomies[0];
	$terms = get_the_terms( $post->ID, $taxonomy );

	if ( empty( $terms ) ) {
		return;
	}

	$count = count( $terms );
	if ( $count == 1 ) {
		$cat_word = 'категории';
	} else {
		$cat_word = 'категориях';
	}
	?>
	<div class="meta-list__container col">
		<h5>Снимок в <?php echo $count; ?> <?php echo $cat_word; ?></h5>
		<div class="meta-list__inner">
			<?php
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$attachment_ID = get_term_meta( $term->term_taxonomy_id, 'taxonomy-image-id', true );
					$image = wp_get_attachment_image_src( $attachment_ID, 'thumbnail' ); ?>

					<div class="meta-list--item">
						<div class="item-mage">
							<img width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" alt="" src="<?php echo $image[0]; ?>">
						</div>
						<div class="item-text">
							<span class="item-name"><?php echo $term->name; ?></span>
							<?php if ( $term->description ) : ?>
								<span class="item-description">(<?php echo $term->description; ?>)</span>
							<?php endif; ?>
						</div>
					</div>
				<?php }
			}
			?>
		</div><!-- .meta-list__inner -->
	</div><!-- .meta-list__container -->
<?php
}

/**
 * Display HTML Short exif description.
 * Used in portfolios templates.
 */
function astrodj_portfolio_exif_description() {
	$prefix = 'astrodj_';

	$short_details = get_post_meta( get_the_ID(), $prefix . 'short_detail_repeat_group', true );
	$short_details_desc = get_post_meta( get_the_ID(), $prefix . 'short_detail_description', true );

	if ( $short_details_desc || $short_details ) :
	?>
	<div class="meta-list__container line">
		<h5>Детали съемки</h5>
		<div class="meta-list__inner">
			<?php
			if ( $short_details_desc ) :
				$colon = $short_details ? ':' : '';
				
				echo '<p>' . esc_html( $short_details_desc ) . $colon . '</p>';
			endif;
			?>
			<?php
			if ( $short_details ) :
				foreach ( $short_details as $short_detail => $item ) :
					if ( isset( $item[$prefix . 'short_detail_item'] ) || isset( $item[$prefix . 'short_detail_item_param'] ) ) : ?>
						<div class="meta-list--item">
							<?php
							if ( isset( $item[$prefix . 'short_detail_item'] ) ) {
								echo '<span>' . esc_html( $item[$prefix . 'short_detail_item'] ) . ': </span>';
							}
							if ( isset( $item[$prefix . 'short_detail_item_param'] ) ) {
								echo '<span>' . esc_html( $item[$prefix . 'short_detail_item_param'] ) . '</span>';
							}
							?>
						</div>
					<?php 
					endif;
				endforeach; 
			endif; ?>
		</div><!-- .meta-list__inner -->
	</div><!-- .meta-list__container -->
	<?php
	endif;
}