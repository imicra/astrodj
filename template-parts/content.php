<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Astrodj
 */

// for infinite scroll ajax on Frontpage
$previous_post = get_previous_post();

if ( is_front_page() || wp_doing_ajax() ) :
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'astrodj-post' ) ); ?> data-previous-id="<?php echo $previous_post->ID; ?>">
<?php else : ?>
<article id="post-<?php the_ID(); ?>" <?php post_class( array( 'astrodj-post' ) ); ?>>
<?php endif; ?>
	<div class="post__content">
		<header class="entry-header">
			<?php // astrodj_the_tags_list(); ?>
			<?php astrodj_comments_popup_link(); ?>
			<?php
			astrodj_posted_on();

			if ( 'post' === get_post_type() ) :
				?>
				<div class="entry-meta">
					<?php
					astrodj_the_category_list();
					?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</header><!-- .entry-header -->
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="post-thumbnail-header">
				<a href="<?php echo esc_url( get_permalink() ) ?>">
					<?php
					$featured_image_format = get_post_meta( get_the_ID(), 'featured_image_format_meta', true );
					
					if ( 'vertical' === $featured_image_format ) {
						$class = 'vertical';
					} else {
						$class = 'blog-thumbnail';
					}

					astrodj_post_thumbnail_lqip( array( 'post-thumbnail', $class ), 'post-thumbnail' );
					?>
				</a>
			</div><!-- .post-thumbnail-header -->
		<?php endif; ?>

		<?php if ( has_excerpt() ) : ?>
			<div class="entry-content">
				<?php	the_excerpt(); ?>
			</div><!-- .entry-content -->
		<?php endif; ?>

		<footer class="entry-footer">
			<?php astrodj_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</div><!-- .post__content -->
</article><!-- #post-<?php the_ID(); ?> -->
