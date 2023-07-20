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
		<main id="main" class="site-main">
			
		<?php astrodj_placeholder_content_preloader(); ?>
			
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php if ( is_category() ) : ?>
					<span>Рубрика</span>
				<?php elseif ( is_tag() ) : ?>
					<span>Тэг</span>
				<?php endif;
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			echo '<div class="page-limit" data-page="' . $_SERVER["REQUEST_URI"] . '">';

			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_type() );

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
