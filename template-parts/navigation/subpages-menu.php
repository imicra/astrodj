<?php
/**
 * Displays Subpages Navigation Menu in Page template.
 *
 * @package astrodj
 */

?>

<nav id="subpages-navigation" class="subpages-navigation">
  <?php
  wp_nav_menu( array(
    'theme_location' => 'subpages',
    'container'      => false,
    'menu_id'        => 'subpages-menu',
  ) );
  ?>
</nav><!-- #site-navigation -->