<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Astrodj
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		/**
		 * astrodj_placeholder_content_preloader - 10
		 */
		do_action( 'astrodj_before_content' );
		?>

			<?php	if ( have_posts() ) : ?>

				<header class="page-header-search">
					<h1 class="page-title">
						<?php
						/* translators: %s: search query. */
						printf( esc_html__( 'Результаты поиска для: %s', 'astrodj' ), '<span>' . get_search_query() . '</span>' );
						?>
					</h1>
					<span>(<?php
						global $wp_query;

						echo $wp_query->found_posts;
						?>)</span>
				</header><!-- .page-header -->

				<?php
				echo '<div class="page-limit" data-page="' . $_SERVER["REQUEST_URI"] . '">';

				/* Start the Loop */
				while ( have_posts() ) :
					the_post();

					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					get_template_part( 'template-parts/content' );

				endwhile;

				echo '</div><!-- .page-limit -->';

				astrodj_load_more_pugination();

			else :

				get_template_part( 'template-parts/content', 'none' );
			
			endif;
			
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer( 'hidden' );
