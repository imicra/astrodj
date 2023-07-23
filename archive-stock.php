<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Astrodj
 */

get_header();
?>

	<div id="primary" class="content-area">
		<div id="results" class="widget-filter__page"></div>
		<main id="main" class="site-main">

			<?php
			/**
			 * astrodj_placeholder_gallery_preloader - 10
			 */
			do_action( 'astrodj_gallery_before_content' );

			/**
			 * Execute Widget Filter when page refresh if url has Query Parameters.
			 */
			do_action( 'astrodj_archive_portfolio_before_loop' );
			?>

			<?php if ( have_posts() ) : ?>

				<div class="portfolio-content page-limit" data-page="<?php echo $_SERVER["REQUEST_URI"]; ?>">
					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						// use one of portfolio-fullpage or portfolio-iframe template part for any cases
						get_template_part( 'template-parts/portfolio', 'fullpage' );

					endwhile;
					?>
				</div><!-- .page-limit -->

				<?php 
				astrodj_load_more_pugination();

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
