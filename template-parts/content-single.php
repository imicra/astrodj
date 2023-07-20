<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Astrodj
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<?php astrodj_posted_on(); ?>
			<?php	
			the_title( '<h1 class="entry-title">', '</h1>' );

			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta">
					<?php astrodj_the_category_list(); ?>
				</div><!-- .entry-meta -->
				<?php if ( has_excerpt() ) : ?>
				<div class="single-excerpt">
					<?php	the_excerpt(); ?>
				</div><!-- .single-excerpt -->
				<?php endif; ?>
				<?php if ( get_post_meta( get_the_ID(), 'astrodj_postmeta_checkbox', 1 ) ) : ?>
					<div class="metabox borders">
						<?php
							astrodj_event_date();
							astrodj_camera();
						?>
					</div><!-- .metabox borders -->
				<?php endif; ?>
			<?php endif; ?>
		</header><!-- .entry-header -->

		<?php if ( has_post_thumbnail() ) : 
		
		$featured_image_format = get_post_meta( get_the_ID(), 'featured_image_format_meta', true );
		$class = '';

		if ( $featured_image_format ) :
		
			if ( 'vertical' === $featured_image_format ) {
				$class = 'vertical';
			} elseif ( 'panorama' === $featured_image_format ) {
				$class = 'full-bleed';
			} elseif ( 'normal' === $featured_image_format ) {
				$class = '';
			}

		endif;

		astrodj_post_thumbnail_lqip( array( 'post-thumbnail', $class ), 'full' ); 
		?>
		<figcaption class="post-thumbnail-caption"><?php the_post_thumbnail_caption(); ?></figcaption>

	<?php endif; ?>

	<div class="entry-content">
		<?php astrodj_single_comments_popup_link(); ?>
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

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'astrodj' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php astrodj_entry_footer(); ?>
		<hr class="styled-separator">
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
