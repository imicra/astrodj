<?php
/**
 * Displays Main navigation.
 *
 * @package astrodj
 */

?>

<nav id="site-navigation" class="main-navigation">
  <div class="inner-wrapper">
    <?php get_template_part( 'template-parts/navigation/menu', 'button' ); ?>
    <?php
    wp_nav_menu( array(
      'theme_location' => 'main',
      'container'      => false,
      'menu_id'        => 'primary-menu',
    ) );

    if ( has_nav_menu( 'sub_main' ) ) {
      astrodj_dropdown_menu();
    }
    ?>
    <?php get_template_part( 'template-parts/navigation/filter', 'button' ); ?>
  </div>
</nav><!-- #site-navigation -->