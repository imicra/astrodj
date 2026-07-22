<?php
/**
 * The template for displaying page with cpt shop.
 *
 * Template name: Shop
 *
 * @package Astrodj
 */

get_header( 'shop' );
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">
			<?php get_template_part( 'template-parts/navigation/subpages', 'shop' ); ?>
			<div class="subpage-content-wrapper">
				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', 'page' );

				endwhile; // End of the loop.

				// get_template_part( 'template-parts/content', 'shop' );
				?>
			</div><!-- .subpage-content-wrapper -->
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
