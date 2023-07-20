<?php
/**
 * The sidebar containing the frontpage widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astrodj
 */

if ( ! is_active_sidebar( 'frontpage-1' ) ) {
	return;
}
?>

<div id="frontpage-widget-area" class="frontpage-widgets">
	<?php dynamic_sidebar( 'frontpage-1' ); ?>
</div><!-- #secondary -->
