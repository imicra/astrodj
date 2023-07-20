<?php
/**
 * The header for displaying the content portfolio pages with iframe or normal variants.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astrodj
 */

?>

	</div><!-- #content -->
	<footer id="colophon" class="site-footer">
		<div class="site-info">
			<div class="site-rights">
				<span class="dev">
					&copy; 2019 - <?php echo date( 'Y' ); ?> astrodj.ru
				</span>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->
<?php //astrodj_include_svg_icons(); ?>
<?php wp_footer(); ?>
</body>
</html>
