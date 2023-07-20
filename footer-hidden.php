<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astrodj
 */

?>

	</div><!-- #content -->

	<?php get_sidebar( 'footer' ); ?>

	<footer id="colophon" class="site-footer footer__hidden">
		<div class="site-info">
			<div class="site-rights">
				<span class="dev">
					&copy; 2019 - <?php echo date( 'Y' ); ?> astrodj.ru
				</span>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php get_sidebar(); ?>

<div class="sidebar-overlay-layer"></div>

<?php wp_footer(); ?>

</body>
</html>
