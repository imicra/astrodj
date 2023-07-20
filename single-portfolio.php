<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astrodj
 */

get_header( 'portfolio' );
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			// not used in content-portfolio-fullpage template part
			// astrodj_post_navigation(
			// 	[
			// 		'next_text' => astrodj_get_svg( array( 'icon' => 'arrow-left-simple' ) ), 
			// 		'prev_text' => astrodj_get_svg( array( 'icon' => 'arrow-right-simple' ) ) 
			// 	]
			// );

			// use one of content-portfolio-fullpage or content-portfolio-iframe template part
			get_template_part( 'template-parts/content', 'portfolio-fullpage' );

		endwhile; // End of the loop.
		?>
			<?php astrodj_portfolio_more_prev_posts(); ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer( 'portfolio' );
