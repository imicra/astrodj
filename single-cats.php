<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Astrodj
 */

get_header( 'iframe' );
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			astrodj_post_navigation(
				[
					'next_text' => astrodj_get_svg( array( 'icon' => 'arrow-left-simple' ) ), 
					'prev_text' => astrodj_get_svg( array( 'icon' => 'arrow-right-simple' ) ) 
				]
			);

			get_template_part( 'template-parts/content', 'stock' );

		endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer( 'iframe' );
