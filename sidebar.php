<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astrodj
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php get_template_part( 'template-parts/account', 'login' ); ?>
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<section class="widget_astrodj_social">
		<?php get_template_part( 'template-parts/navigation/social', 'menu' ); ?>
	</section>
</aside><!-- #secondary -->
