<?php
/**
 * Displays Filter Button in Main navigation.
 *
 * @package astrodj
 */

if ( is_active_sidebar( 'filter-stock' ) && is_post_type_archive( 'stock' ) ) : ?>
  <div id="menu-filter" class="menu-ui">
    <?php echo astrodj_get_svg( array( 'icon' => 'sort' ) ); ?>
  </div>
<?php
endif;

if ( is_active_sidebar( 'filter-portfolio' ) && is_post_type_archive( 'portfolio' ) ) : ?>
  <div id="menu-filter" class="menu-ui">
    <?php echo astrodj_get_svg( array( 'icon' => 'sort' ) ); ?>
  </div>
<?php
endif;

if ( is_active_sidebar( 'filter-cats' ) && is_post_type_archive( 'cats' ) ) : ?>
  <div id="menu-filter" class="menu-ui">
    <?php echo astrodj_get_svg( array( 'icon' => 'sort' ) ); ?>
  </div>
<?php
endif;