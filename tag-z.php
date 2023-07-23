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
			
		<?php
		/**
		 * astrodj_placeholder_content_preloader - 10
		 */
		do_action( 'astrodj_before_content' );
		?>
			
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

		<?php else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer( 'hidden' );
