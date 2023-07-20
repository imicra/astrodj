<?php
/**
 * The template for displaying page with custom design.
 *
 * Template name: Архив
 *
 * @package Astrodj
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<?php astrodj_placeholder_gallery_preloader(); ?>

			<div class="portfolio-content page-limit" data-page="<?php echo $_SERVER["REQUEST_URI"]; ?>">
				<?php get_template_part( 'template-parts/content', 'archive' ); ?>
			</div><!-- .page-limit -->

			<?php astrodj_load_more_pugination(); ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
