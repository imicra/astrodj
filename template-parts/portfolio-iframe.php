<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Astrodj
 */

/* CMB thumbnail image class. */
$meta_orientation = get_post_meta( get_the_ID(), 'astrodj_radio_orientation', true );

if ( isset( $meta_orientation ) ) :

	if ( 'featured' === $meta_orientation ) {
		$class = 'featured';
	} elseif ( 'vertical' === $meta_orientation ) {
		$class = 'vertical';
	} elseif ( 'panorama' === $meta_orientation ) {
		$class = 'panorama';
	}

else
	$class = '';
endif;

$classes = array(
	'astrodj-portfolio',
	$class
);

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
	<a data-portfolio data-type="iframe" data-src="<?php echo esc_url( get_permalink() ); ?>" data-id="<?php the_ID(); ?>" href="javascript:;">
		<?php astrodj_post_thumbnail_lqip( '', 'full' ); ?>
	</a>
</article><!-- #post-<?php the_ID(); ?> -->
