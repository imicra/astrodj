<?php
/**
 * The template for displaying page with navigation by subpages.
 *
 * Template name: Navigation Page
 *
 * @package Astrodj
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php get_template_part( 'template-parts/navigation/subpages', 'menu' ); ?>
			<div class="subpage-content-wrapper">
				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>
			</div><!-- .subpage-content-wrapper -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
