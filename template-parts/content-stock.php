<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Astrodj
 */

/* CMB class. */
$meta_orientation = get_post_meta( get_the_ID(), 'astrodj_radio_orientation', true );

if ( isset( $meta_orientation ) ) :

	if ( 'featured' === $meta_orientation ) {
		$class = 'feature';
	} elseif ( 'vertical' === $meta_orientation ) {
		$class = 'vertical';
	} elseif ( 'panorama' === $meta_orientation ) {
		$class = 'pano';
	}

else
	$class = '';
endif;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'iframe-post-stock', $class ) ); ?>>
	<!-- <div class='iframe__overlay'></div>
	<div class="ajax-loader"></div> -->
	<div class="post-thumbnail__wrapper">
		<figure class="post-thumbnail">
			<?php $url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
			<a href="<?php echo $url[0]; ?>" class="fancybox">
				<?php the_post_thumbnail(); ?>
			</a>
		</figure><!-- .post-thumbnail -->
		<div class="post-thumbnail__menu">
			<?php astrodj_dropdown_menu() ?>
		</div><!-- .post-thumbnail__menu -->
	</div><!-- .post-thumbnail__wrapper -->

	<div class="content-wrapper">
		<header class="entry-header">
			<?php	
			the_title( '<h1 class="entry-title">', '</h1>' );
			?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php
			the_content( sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'astrodj' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );
			?>

			<?php
			// CMB metabox
			$timestamp = get_post_meta( get_the_ID(), 'astrodj_portfolio_datetime_timestamp', true );
			
			if ( ! empty( $timestamp ) ) :
			?>
				<div class="metabox">
					<span>Снято: </span>
					<span><?php echo date_i18n( 'j M Y H:i', $timestamp ); ?></span>
				</div><!-- .metabox -->
			<?php endif; ?>
			
			<div class="media-exif__wrapper block">
				<?php astrodj_portfolio_exif(); ?>
			</div>
		</div><!-- .entry-content -->
	</div><!-- .content-wrapper -->

	<footer class="entry-footer">
		<span>&copy; · Vas Zhigalov</span>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
