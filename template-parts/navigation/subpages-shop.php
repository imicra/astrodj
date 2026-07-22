<?php
/**
 * Displays Subpages Navigation Menu in Page template.
 *
 * @package astrodj
 */

if ( ! has_nav_menu( 'subpages-shop' ) ) {
  return;
}

?>

<nav id="subpages-navigation" class="subpages-navigation">
  <?php
  wp_nav_menu( array(
    'theme_location' => 'subpages-shop',
    'container'      => false,
    'menu_id'        => 'subpages-menu',
  ) );
  ?>
</nav><!-- #site-navigation -->