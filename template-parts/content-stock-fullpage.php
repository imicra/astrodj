<?php
/**
 * Template part for displaying Portfolio content iframe variant.
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

<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'stock-post-portfolio', $class ) ); ?>>
	<div class="post-content__wrapper">
		<div class="content-inner">
			<div class="content-main__wrapper">
				<div class="content-main">
					<div class="post-thumbnail__wrapper">
						<?php
						astrodj_post_navigation(
							[
								'next_text' => astrodj_get_svg( array( 'icon' => 'arrow-left-simple' ) ), 
								'prev_text' => astrodj_get_svg( array( 'icon' => 'arrow-right-simple' ) ) 
							]
						);
						?>
						<?php astrodj_post_thumbnail_lqip( 'post-thumbnail', 'full', 'medium' ); ?>
						<div class="post-thumbnail__menu">
							<span class="rights">&copy; · <?php echo esc_attr( get_option( 'astrodj_rights' ) ); ?></span>
							<?php astrodj_dropdown_menu() ?>
						</div><!-- .post-thumbnail__menu -->
					</div><!-- .post-thumbnail__wrapper -->

					<div class="entry-content">
						<header class="entry-header">
							<?php	
							the_title( '<h1 class="entry-title">', '</h1>' );
							?>
						</header><!-- .entry-header -->
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
					</div><!-- .entry-content -->
				</div><!-- .content-main -->
			</div><!-- .content-main__wrapper -->

			<div class="media-exif__wrapper inline">
				<div class="media-exif__inner">
					<?php astrodj_portfolio_exif_inline(); ?>
				</div>
			</div>
			<div class="meta-content">
				<div class="meta-content__inner">
					<div class="box-inline">
						<?php
						// CMB metabox
						$timestamp = get_post_meta( get_the_ID(), 'astrodj_portfolio_datetime_timestamp', true );
						
						if ( ! empty( $timestamp ) ) :
						?>
							<div class="metabox">
								<span>Снято:&nbsp;</span>
								<span><?php echo date_i18n( 'j M Y H:i', $timestamp ); ?></span>
							</div><!-- .metabox -->
						<?php endif; ?>

						<?php astrodj_meta_location(); ?>
					</div><!-- .box-inline -->
					<?php astrodj_portfolio_category_list(); ?>
					<?php astrodj_portfolio_tags_list(); // entry-meta. ?>
				</div><!-- .meta-content__inner -->
			</div><!-- .meta-content -->
		</div><!-- .content-wrapper -->
	</div><!-- .post-content__wrapper -->
</article><!-- #post-<?php the_ID(); ?> -->
