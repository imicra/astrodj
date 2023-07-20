<?php
/**
 * The sidebar containing Filter Panel widget area.
 *
 * @package astrodj
 */

if ( is_active_sidebar( 'filter-stock' ) && is_post_type_archive( 'stock' ) ) : ?>
	<aside id="filter-widget-area" class="filter-widgets">
		<?php dynamic_sidebar( 'filter-stock' ); ?>
	</aside><!-- #secondary -->
<?php
endif;

if ( is_active_sidebar( 'filter-portfolio' ) && is_post_type_archive( 'portfolio' ) ) : ?>
	<aside id="filter-widget-area" class="filter-widgets">
		<?php dynamic_sidebar( 'filter-portfolio' ); ?>
	</aside><!-- #secondary -->
<?php
endif;

if ( is_active_sidebar( 'filter-cats' ) && is_post_type_archive( 'cats' ) ) : ?>
	<aside id="filter-widget-area" class="filter-widgets">
		<?php dynamic_sidebar( 'filter-cats' ); ?>
	</aside><!-- #secondary -->
<?php
endif;