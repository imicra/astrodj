<?php
/**
 * Displays Social Navigation Menu.
 *
 * @package astrodj
 */

?>

<nav id="icon-navigation" class="icon-navigation">
  <?php
  wp_nav_menu( array(
    'theme_location' => 'social',
    'menu_class'     => 'social-links-menu',
    'depth'          => 1,
    'link_before'    => '<span class="screen-reader-text">',
    'link_after'     => '</span>' . astrodj_get_svg( array( 'icon' => 'chain' ) ),
  ) );
  ?>
</nav><!-- #icon-navigation -->